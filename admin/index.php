<?php

require __DIR__ . '/../lib/bootstrap.php';

// Estados del pipeline CRM (Fase 2) para selects y filtros. La validación de
// input usa leadStatusIsValid() (acepta también legacy, para no romper filtros
// sobre leads históricos).
$LEAD_STATUSES    = LEAD_PIPELINE_STATUSES;
$SETTING_KEYS     = [
    'site_name', 'timezone',
    'logo_image', 'favicon_image',
    'notification_email', 'notification_from',
    'autoreply_enabled', 'autoreply_subject', 'autoreply_body',
    'ga_id', 'pixel_id',
];
// Settings exclusivas del módulo "Mailing" (separadas para no mezclarse con
// los toggles generales).
$MAILING_KEYS     = [
    'mail_provider',
    'resend_api_key', 'resend_from_name', 'resend_from_email', 'resend_reply_to',
    'notification_email', 'notification_subject', 'notification_body',
    'autoreply_enabled', 'autoreply_subject', 'autoreply_body', 'autoreply_body_html',
];

$action     = $_POST['action'] ?? $_GET['action'] ?? '';
$view       = $_GET['view'] ?? '';
$loginError = '';
$pwError    = '';
$pageError  = '';

// -------------------- acciones públicas (sin login) --------------------
if ($action === 'login') {
    csrfCheck();
    $result = login($_POST['email'] ?? '', $_POST['password'] ?? '');
    if ($result === 'ok') redirect('/admin/');
    $loginError = $result === 'rate_limited'
        ? 'Demasiados intentos fallidos. Esperá 15 minutos.'
        : 'Credenciales inválidas.';
}

if ($action === 'logout') {
    csrfCheck();
    logout();
    redirect('/admin/');
}

// -------------------- acciones autenticadas --------------------
$user = currentUser();

if ($user) {
    // Migraciones solo para usuarios autenticados (evita pingback anónimo).
    runMigrations();

    // Si la sesión arrastra la flag must_change_password, forzamos vista
    // "Mi cuenta" hasta que se cambie. Solo permitimos las acciones mínimas
    // necesarias para resolverlo o salir.
    if (!empty($user['must_change_password'])) {
        $allowedActions = ['change_password', 'logout', ''];
        if (!in_array($action, $allowedActions, true) || ($action === '' && $view !== 'account')) {
            flashSet('pw_must_change', 'Debés cambiar tu contraseña antes de continuar.');
            redirect('/admin/?view=account');
        }
        $view = 'account';
    }

    if ($action === 'change_password') {
        csrfCheck();
        $current = $_POST['current_password'] ?? '';
        $new     = $_POST['new_password']     ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        if (strlen($new) < 8)               $pwError = 'La nueva contraseña debe tener al menos 8 caracteres.';
        elseif ($new !== $confirm)          $pwError = 'Las contraseñas no coinciden.';
        elseif (!changePassword($_SESSION['user_id'], $current, $new))
                                             $pwError = 'Contraseña actual incorrecta.';
        else {
            flashSet('pw_success', 'Contraseña actualizada.');
            redirect('/admin/?view=account');
        }
        $view = 'account';
    }

    if ($action === 'update_lead_status') {
        csrfCheck();
        $id     = (int) ($_POST['id'] ?? 0);
        $status = (string) ($_POST['status'] ?? '');
        // Lógica centralizada: valida, actualiza y registra status_change.
        $res = updateLeadStatus($id, $status, (int) $user['id']);
        flashSet($res['ok'] ? 'lead_success' : 'lead_error',
            $res['ok'] ? 'Estado actualizado.' : ($res['error'] ?? 'No se pudo actualizar el estado.'));
        redirect('/admin/?id=' . $id);
    }

    if ($action === 'update_next_action') {
        csrfCheck();
        $id   = (int) ($_POST['id'] ?? 0);
        // Registrar/editar/limpiar la próxima acción. Vacío + vacío = limpiar.
        $res  = updateLeadNextAction(
            $id,
            (string) ($_POST['next_action_at'] ?? ''),
            (string) ($_POST['next_action_note'] ?? ''),
            (int) $user['id']
        );
        flashSet($res['ok'] ? 'lead_success' : 'lead_error',
            $res['ok'] ? 'Próxima acción actualizada.' : ($res['error'] ?? 'No se pudo guardar la próxima acción.'));
        redirect('/admin/?id=' . $id);
    }

    if ($action === 'update_lead_value') {
        csrfCheck();
        $id  = (int) ($_POST['id'] ?? 0);
        // Valor monetario del deal + motivo de pérdida (Fase 4).
        $res = updateLeadValue(
            $id,
            (string) ($_POST['value_amount'] ?? ''),
            (string) ($_POST['lost_reason'] ?? ''),
            (int) $user['id']
        );
        flashSet($res['ok'] ? 'lead_success' : 'lead_error',
            $res['ok'] ? 'Valor del deal actualizado.' : ($res['error'] ?? 'No se pudo guardar el valor.'));
        redirect('/admin/?id=' . $id);
    }

    if ($action === 'add_note') {
        csrfCheck();
        $id   = (int) ($_POST['id'] ?? 0);
        $body = trim($_POST['note'] ?? '');
        if ($body !== '' && $id > 0) {
            $db = getDB();
            $cur = $db->prepare('SELECT account_id FROM leads WHERE id = ?');
            $cur->execute([$id]);
            $accId = $cur->fetchColumn();
            // Las notas viven en el timeline de actividad (type='note').
            addLeadActivity($id, $accId !== false ? (int) $accId : null, 'note', [
                'user_id' => $user['id'],
                'body'    => $body,
            ]);
        }
        redirect('/admin/?id=' . $id);
    }

    // ─── Tareas (automatización operativa · Fase 3) ───
    // `return_to` decide a dónde volver: al detalle del lead o a la vista de tareas.
    if (in_array($action, ['task_create', 'task_complete', 'task_cancel', 'task_reopen'], true)) {
        csrfCheck();
        $returnTo  = (string) ($_POST['return_to'] ?? '');
        $leadIdPost = (int) ($_POST['lead_id'] ?? 0);
        // Destino de redirect: al lead si vino de su detalle, si no a /tareas.
        $back = $returnTo === 'lead' && $leadIdPost > 0
            ? '/admin/?id=' . $leadIdPost
            : '/admin/?view=tasks';

        if ($action === 'task_create') {
            $res = taskCreate([
                'title'       => (string) ($_POST['title'] ?? ''),
                'description' => (string) ($_POST['description'] ?? ''),
                'account_id'  => (int) ($_POST['account_id'] ?? 0),
                'lead_id'     => $leadIdPost,
                'due_at'      => (string) ($_POST['due_at'] ?? ''),
            ], (int) $user['id']);
            flashSet($res['ok'] ? 'task_success' : 'task_error',
                $res['ok'] ? 'Tarea creada.' : ($res['error'] ?? 'No se pudo crear la tarea.'));
        } else {
            $tid = (int) ($_POST['id'] ?? 0);
            $res = $action === 'task_complete' ? taskComplete($tid, (int) $user['id'])
                 : ($action === 'task_cancel' ? taskCancel($tid, (int) $user['id'])
                 : taskReopen($tid, (int) $user['id']));
            $msg = ['task_complete' => 'Tarea completada.', 'task_cancel' => 'Tarea cancelada.', 'task_reopen' => 'Tarea reabierta.'][$action] ?? 'Tarea actualizada.';
            flashSet($res['ok'] ? 'task_success' : 'task_error',
                $res['ok'] ? $msg : ($res['error'] ?? 'No se pudo actualizar la tarea.'));
        }
        redirect($back);
    }

    // ─── Cuentas (multi-cuenta · Fase 1) ───
    if ($action === 'account_create') {
        csrfCheck();
        $res = accountCreate((string) ($_POST['name'] ?? ''), (string) ($_POST['plan'] ?? ''));
        if ($res['ok']) {
            flashSet('account_success', 'Cuenta creada. Copiá su token público para la landing del cliente.');
            redirect('/admin/?view=account_edit&id=' . (int) $res['id']);
        }
        flashSet('account_error', $res['error'] ?? 'No se pudo crear la cuenta.');
        redirect('/admin/?view=accounts');
    }

    if ($action === 'account_update') {
        csrfCheck();
        $aid = (int) ($_POST['id'] ?? 0);
        $res = accountUpdate($aid, (string) ($_POST['name'] ?? ''), (string) ($_POST['plan'] ?? ''));
        flashSet($res['ok'] ? 'account_success' : 'account_error', $res['ok'] ? 'Cuenta actualizada.' : ($res['error'] ?? 'Error.'));
        redirect('/admin/?view=account_edit&id=' . $aid);
    }

    if ($action === 'account_set_status') {
        csrfCheck();
        $aid    = (int) ($_POST['id'] ?? 0);
        $status = (string) ($_POST['status'] ?? '');
        if (accountSetStatus($aid, $status)) {
            flashSet('account_success', 'Estado de la cuenta actualizado.');
        } else {
            flashSet('account_error', 'Estado inválido.');
        }
        redirect('/admin/?view=account_edit&id=' . $aid);
    }

    if ($action === 'account_regenerate_token') {
        csrfCheck();
        $aid = (int) ($_POST['id'] ?? 0);
        $tok = accountRegenerateToken($aid);
        flashSet($tok ? 'account_success' : 'account_error', $tok ? 'Token regenerado. La landing anterior dejará de funcionar hasta actualizar el token.' : 'No se pudo regenerar.');
        redirect('/admin/?view=account_edit&id=' . $aid);
    }

    if ($action === 'delete_lead') {
        csrfCheck();
        $id = (int) ($_POST['id'] ?? 0);
        $stmt = getDB()->prepare('DELETE FROM leads WHERE id = ?');
        $stmt->execute([$id]);
        redirect('/admin/');
    }

    if ($action === 'save_settings') {
        csrfCheck();
        $submitted = $_POST['s'] ?? [];
        // Checkbox: si no viene, es 0.
        $submitted['autoreply_enabled'] = !empty($submitted['autoreply_enabled']) ? '1' : '0';
        foreach ($SETTING_KEYS as $k) {
            if (array_key_exists($k, $submitted)) {
                setSetting($k, (string) $submitted[$k]);
            }
        }
        flashSet('settings_success', 'Configuración actualizada.');
        redirect('/admin/?view=settings');
    }

    if ($action === 'save_business') {
        csrfCheck();
        $submitted = $_POST['b'] ?? [];
        $submitted['business_seo_jsonld'] = !empty($submitted['business_seo_jsonld']) ? '1' : '0';
        foreach (BUSINESS_KEYS as $k) {
            if (array_key_exists($k, $submitted)) {
                setSetting($k, (string) $submitted[$k]);
            }
        }
        flashSet('business_success', 'Información del negocio actualizada.');
        redirect('/admin/?view=business');
    }

    if ($action === 'branch_save') {
        csrfCheck();
        $bid  = (int) ($_POST['id'] ?? 0);
        $data = $_POST['br'] ?? [];
        $res  = branchSave($bid > 0 ? $bid : null, is_array($data) ? $data : []);
        if ($res['ok']) {
            flashSet('business_success', $bid > 0 ? 'Sucursal actualizada.' : 'Sucursal creada.');
        } else {
            flashSet('business_error', $res['error'] ?? 'No se pudo guardar la sucursal.');
        }
        redirect('/admin/?view=business');
    }

    if ($action === 'branch_delete') {
        csrfCheck();
        branchDelete((int) ($_POST['id'] ?? 0));
        flashSet('business_success', 'Sucursal eliminada.');
        redirect('/admin/?view=business');
    }

    if ($action === 'branch_toggle') {
        csrfCheck();
        branchToggleActive((int) ($_POST['id'] ?? 0));
        redirect('/admin/?view=business');
    }

    if ($action === 'save_mailing') {
        csrfCheck();
        $submitted = $_POST['m'] ?? [];
        $submitted['autoreply_enabled'] = !empty($submitted['autoreply_enabled']) ? '1' : '0';
        $provider = ($submitted['mail_provider'] ?? 'mail') === 'resend' ? 'resend' : 'mail';
        $submitted['mail_provider'] = $provider;
        foreach ($MAILING_KEYS as $k) {
            if (array_key_exists($k, $submitted)) {
                setSetting($k, (string) $submitted[$k]);
            }
        }
        flashSet('mailing_success', 'Configuración de mailing actualizada.');
        redirect('/admin/?view=mailing');
    }

    if ($action === 'send_test_email') {
        csrfCheck();
        $to = trim((string) ($_POST['to'] ?? ''));
        if (!$to) $to = (string) getSetting('notification_email', '');
        $res = sendTestNotificationEmail($to);
        if ($res['ok']) flashSet('mailing_success', 'Correo de prueba enviado a ' . $to . '.');
        else flashSet('mailing_error', 'No se pudo enviar: ' . ($res['error'] ?? 'error desconocido'));
        redirect('/admin/?view=mailing');
    }

    if ($action === 'export_csv') {
        $search        = trim($_GET['search'] ?? '');
        $statusFilter  = $_GET['status_filter'] ?? '';
        $accountFilter = (int) ($_GET['account'] ?? 0);
        $pendingFilter = !empty($_GET['pending']);
        $where  = [];
        $params = [];
        if ($accountFilter > 0) {
            $where[] = 'l.account_id = ?';
            $params[] = $accountFilter;
        }
        if ($search !== '') {
            $where[] = '(l.name LIKE ? OR l.email LIKE ? OR l.phone LIKE ?)';
            $like = '%' . $search . '%';
            array_push($params, $like, $like, $like);
        }
        if (leadStatusIsValid((string) $statusFilter)) {
            $where[] = 'l.status = ?';
            $params[] = $statusFilter;
        }
        if ($pendingFilter) {
            // TZ-consistente con el dashboard: next_action_at se guarda en APP_TIMEZONE.
            $where[] = "l.next_action_at IS NOT NULL AND l.next_action_at <= ?
                        AND l.status NOT IN ('won','lost','closed','discarded')";
            $params[] = date('Y-m-d H:i:s');
        }
        $whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        $stmt = getDB()->prepare(
            "SELECT l.id, a.name AS account_name, l.name, l.email, l.phone, l.message,
                    l.source, l.status, l.next_action_at, l.next_action_note,
                    l.ip_address, l.created_at
               FROM leads l LEFT JOIN accounts a ON a.id = l.account_id
             $whereSql ORDER BY l.created_at DESC"
        );
        $stmt->execute($params);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="leads-' . date('Ymd-His') . '.csv"');
        $out = fopen('php://output', 'w');
        fwrite($out, "\xEF\xBB\xBF"); // BOM UTF-8 para Excel
        fputcsv($out, ['id', 'cuenta', 'nombre', 'email', 'telefono', 'mensaje', 'source', 'estado', 'proxima_accion', 'proxima_accion_nota', 'ip', 'fecha_creacion']);
        while ($row = $stmt->fetch()) {
            fputcsv($out, [
                $row['id'],
                $row['account_name'] ?? '',
                $row['name'],
                $row['email'],
                $row['phone'],
                $row['message'],
                $row['source'],
                leadStatusLabel((string) $row['status']),
                $row['next_action_at'] ?? '',
                $row['next_action_note'] ?? '',
                $row['ip_address'],
                $row['created_at'],
            ]);
        }
        fclose($out);
        exit;
    }

    if ($action === 'export_reports_csv') {
        $accId = (int) ($_GET['account'] ?? 0);
        $rng   = reportResolveRange($_GET['range'] ?? '', $_GET['from'] ?? '', $_GET['to'] ?? '');
        $cur   = reportCurrency();
        $kpis  = reportKpis($accId, $rng['from'], $rng['to']);
        $funnel = reportFunnel($accId, $rng['from'], $rng['to']);
        $bySrc  = reportBySource($accId, $rng['from'], $rng['to']);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="reporte-' . date('Ymd-His') . '.csv"');
        $out = fopen('php://output', 'w');
        fwrite($out, "\xEF\xBB\xBF");
        fputcsv($out, ['ARVIOR — Reporte comercial']);
        fputcsv($out, ['Rango', $rng['label'], 'Cuenta', $accId > 0 ? (string) ($accountsMap[$accId] ?? ('#' . $accId)) : 'Todas', 'Moneda', $cur]);
        fputcsv($out, []);
        fputcsv($out, ['KPI', 'Valor']);
        fputcsv($out, ['Leads en el período', $kpis['leads_in_period']]);
        fputcsv($out, ['Ganados', $kpis['won_count']]);
        fputcsv($out, ['Perdidos', $kpis['lost_count']]);
        fputcsv($out, ['Win rate (%)', $kpis['win_rate']]);
        fputcsv($out, ['Revenue ganado', $kpis['won_revenue']]);
        fputcsv($out, ['Ticket promedio', $kpis['avg_deal']]);
        fputcsv($out, ['Pipeline (abiertos)', $kpis['pipeline_count']]);
        fputcsv($out, ['Pipeline valor', $kpis['pipeline_value']]);
        fputcsv($out, ['Forecast ponderado', $kpis['forecast_weighted']]);
        fputcsv($out, []);
        fputcsv($out, ['Embudo', 'Leads', 'Conversión vs etapa previa (%)']);
        foreach ($funnel as $st) fputcsv($out, [leadStatusLabel($st['stage']), $st['count'], $st['conv']]);
        fputcsv($out, []);
        fputcsv($out, ['Fuente', 'Leads', 'Ganados', 'Revenue ganado', 'Win rate (%)']);
        foreach ($bySrc as $s) fputcsv($out, [$s['source'], $s['leads'], $s['won'], $s['won_revenue'], $s['win_rate']]);
        fclose($out);
        exit;
    }

    // ─── Gestión de usuarios ───
    if ($action === 'create_user') {
        csrfCheck();
        $newEmail = (string) ($_POST['email'] ?? '');
        $newName  = (string) ($_POST['name'] ?? '');
        $newPw    = (string) ($_POST['password'] ?? '');
        $mustChange = !empty($_POST['must_change_password']);
        $res = userCreate($newEmail, $newPw, $newName, $mustChange);
        if ($res['ok']) {
            flashSet('user_success', 'Usuario creado: ' . trim($newEmail));
            redirect('/admin/?view=users');
        }
        $userFormError = $res['error'] ?? 'No se pudo crear.';
        $userFormEmail = trim($newEmail);
        $userFormName  = trim($newName);
        $view = 'users';
    }

    if ($action === 'update_user_profile') {
        csrfCheck();
        $targetId = (int) ($_POST['id'] ?? 0);
        if ($targetId <= 0) redirect('/admin/?view=users');
        $res = userUpdateProfile(
            $targetId,
            (string) ($_POST['email'] ?? ''),
            (string) ($_POST['name'] ?? '')
        );
        if ($res['ok']) {
            flashSet('user_success', 'Perfil actualizado.');
            redirect('/admin/?view=user&id=' . $targetId);
        }
        flashSet('user_error', $res['error']);
        redirect('/admin/?view=user&id=' . $targetId);
    }

    if ($action === 'reset_user_password') {
        csrfCheck();
        $targetId = (int) ($_POST['id'] ?? 0);
        $newPw    = (string) ($_POST['new_password'] ?? '');
        if ($targetId <= 0) redirect('/admin/?view=users');
        $res = adminResetPassword($targetId, $newPw);
        if ($res['ok']) {
            flashSet('user_success', 'Contraseña actualizada.');
            redirect('/admin/?view=user&id=' . $targetId);
        }
        $userPwError = $res['error'];
        $view = 'user';
        $_GET['id'] = (string) $targetId;
    }

    if ($action === 'toggle_user_active') {
        csrfCheck();
        $targetId = (int) ($_POST['id'] ?? 0);
        if ($targetId <= 0 || $targetId === (int) $user['id']) {
            flashSet('user_error', 'No podés modificar tu propio estado de acceso.');
            redirect('/admin/?view=users');
        }
        $target = userGet($targetId);
        if (!$target) {
            flashSet('user_error', 'Usuario no encontrado.');
            redirect('/admin/?view=users');
        }
        $willDeactivate = (int) $target['is_active'] === 1;
        if ($willDeactivate && activeUserCount() <= 1) {
            flashSet('user_error', 'No podés desactivar al último usuario activo.');
            redirect('/admin/?view=user&id=' . $targetId);
        }
        userSetActive($targetId, !$willDeactivate);
        flashSet('user_success', $willDeactivate ? 'Usuario desactivado.' : 'Usuario reactivado.');
        redirect('/admin/?view=user&id=' . $targetId);
    }

    if ($action === 'delete_user') {
        csrfCheck();
        $targetId = (int) ($_POST['id'] ?? 0);
        if ($targetId <= 0 || $targetId === (int) $user['id']) {
            flashSet('user_error', 'No podés eliminar tu propia cuenta.');
            redirect('/admin/?view=users');
        }
        $target = userGet($targetId);
        if (!$target) {
            flashSet('user_error', 'Usuario no encontrado.');
            redirect('/admin/?view=users');
        }
        if ((int) $target['is_active'] === 1 && activeUserCount() <= 1) {
            flashSet('user_error', 'No podés eliminar al último usuario activo.');
            redirect('/admin/?view=user&id=' . $targetId);
        }
        userDelete($targetId);
        flashSet('user_success', 'Usuario eliminado: ' . $target['email']);
        redirect('/admin/?view=users');
    }

    if ($action === 'favicon_upload') {
        csrfCheck();
        $f = $_FILES['favicon'] ?? null;
        if (!$f || ($f['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            flashSet('settings_error', 'No se recibió un archivo válido.');
            redirect('/admin/?view=settings');
        }
        if ((int) ($f['size'] ?? 0) > 2 * 1024 * 1024) {
            flashSet('settings_error', 'El archivo supera 2MB.');
            redirect('/admin/?view=settings');
        }
        $mime = function_exists('mime_content_type') ? (mime_content_type($f['tmp_name']) ?: '') : '';
        $allowed = [
            'image/png'                => 'png',
            'image/svg+xml'            => 'svg',
            'image/svg'                => 'svg',
            'image/x-icon'             => 'ico',
            'image/vnd.microsoft.icon' => 'ico',
        ];
        $ext      = strtolower(pathinfo((string) ($f['name'] ?? ''), PATHINFO_EXTENSION));
        $finalExt = $allowed[$mime] ?? null;
        if (!$finalExt && in_array($ext, ['png', 'svg', 'ico'], true)) $finalExt = $ext;
        if (!$finalExt) {
            flashSet('settings_error', 'Formato no permitido. Subí PNG, SVG o ICO.');
            redirect('/admin/?view=settings');
        }
        $brandDir = __DIR__ . '/../uploads/brand';
        if (!is_dir($brandDir)) @mkdir($brandDir, 0755, true);
        foreach (['png', 'svg', 'ico'] as $oldExt) {
            @unlink($brandDir . '/favicon.' . $oldExt);
        }
        $dest = $brandDir . '/favicon.' . $finalExt;
        if ($finalExt === 'svg') {
            $svg = sanitizeSvgString((string) @file_get_contents($f['tmp_name']));
            if ($svg === null) {
                flashSet('settings_error', 'El SVG no se pudo procesar de forma segura.');
                redirect('/admin/?view=settings');
            }
            if (@file_put_contents($dest, $svg) === false) {
                flashSet('settings_error', 'No se pudo guardar el favicon.');
                redirect('/admin/?view=settings');
            }
        } else {
            if (!@move_uploaded_file($f['tmp_name'], $dest)) {
                flashSet('settings_error', 'No se pudo guardar el favicon.');
                redirect('/admin/?view=settings');
            }
        }
        setSetting('favicon_image', '/uploads/brand/favicon.' . $finalExt);
        flashSet('settings_success', 'Favicon actualizado.');
        redirect('/admin/?view=settings');
    }

    if ($action === 'favicon_remove') {
        csrfCheck();
        $brandDir = __DIR__ . '/../uploads/brand';
        foreach (['png', 'svg', 'ico'] as $oldExt) {
            @unlink($brandDir . '/favicon.' . $oldExt);
        }
        setSetting('favicon_image', '');
        flashSet('settings_success', 'Favicon eliminado.');
        redirect('/admin/?view=settings');
    }

    // ─── Central de Medios ───
    if ($action === 'media_folder_create') {
        csrfCheck();
        $name   = trim($_POST['name'] ?? '');
        $parent = (int) ($_POST['parent_id'] ?? 0) ?: null;
        $id = mediaFolderCreate($name, $parent);
        flashSet($id ? 'media_msg' : 'media_err', $id ? 'Carpeta creada.' : 'No se pudo crear la carpeta (¿slug duplicado?).');
        redirect('/admin/?view=media' . ($id ? '&folder=' . $id : ''));
    }
    if ($action === 'media_folder_delete') {
        csrfCheck();
        mediaFolderDelete((int) ($_POST['id'] ?? 0));
        flashSet('media_msg', 'Carpeta eliminada.');
        redirect('/admin/?view=media');
    }
    if ($action === 'media_upload_inline') {
        csrfCheck();
        header('Content-Type: application/json; charset=utf-8');
        $folderId = (int) ($_POST['folder_id'] ?? 0) ?: null;
        if (empty($_FILES['file']) || ($_FILES['file']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            echo json_encode(['ok' => false, 'error' => 'No se recibió un archivo válido.']);
            exit;
        }
        $r = mediaLibraryUpload($_FILES['file'], $folderId);
        if (empty($r['ok'])) {
            echo json_encode(['ok' => false, 'error' => $r['error'] ?? 'Falló la subida.']);
            exit;
        }
        echo json_encode(['ok' => true, 'id' => (int) ($r['id'] ?? 0), 'path' => $r['path'], 'name' => basename($r['path'])]);
        exit;
    }
    if ($action === 'media_upload') {
        csrfCheck();
        $folderId = (int) ($_POST['folder_id'] ?? 0) ?: null;
        $okN = 0; $errs = [];
        if (!empty($_FILES['files']['name']) && is_array($_FILES['files']['name'])) {
            $count = count($_FILES['files']['name']);
            for ($i = 0; $i < $count; $i++) {
                if (($_FILES['files']['error'][$i] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) continue;
                $f = [
                    'name'     => $_FILES['files']['name'][$i],
                    'type'     => $_FILES['files']['type'][$i],
                    'tmp_name' => $_FILES['files']['tmp_name'][$i],
                    'error'    => $_FILES['files']['error'][$i],
                    'size'     => $_FILES['files']['size'][$i],
                ];
                $r = mediaLibraryUpload($f, $folderId);
                if ($r['ok']) $okN++; else $errs[] = $r['error'] ?? 'fallo';
            }
        }
        flashSet('media_msg', "$okN imagen(es) subida(s)." . ($errs ? ' Errores: ' . implode('; ', $errs) : ''));
        redirect('/admin/?view=media' . ($folderId ? '&folder=' . $folderId : ''));
    }
    if ($action === 'media_delete') {
        csrfCheck();
        $id  = (int) ($_POST['id'] ?? 0);
        $row = mediaLibraryGet($id);
        $folderId = $row ? (int) $row['folder_id'] : 0;
        if ($id) mediaLibraryDelete($id);
        flashSet('media_msg', 'Imagen eliminada.');
        redirect('/admin/?view=media' . ($folderId ? '&folder=' . $folderId : ''));
    }
    if ($action === 'media_update') {
        csrfCheck();
        $mid = (int) ($_POST['id'] ?? 0);
        mediaLibraryUpdateAlt($mid, (string) ($_POST['alt'] ?? ''));
        $row = mediaLibraryGet($mid);
        $folderId = $row ? (int) $row['folder_id'] : 0;
        flashSet('media_msg', 'Texto alternativo actualizado.');
        redirect('/admin/?view=media' . ($folderId ? '&folder=' . $folderId : ''));
    }
    if ($action === 'media_move') {
        csrfCheck();
        $ids = (array) ($_POST['ids'] ?? []);
        $folderId = $_POST['folder_id'] !== '' ? (int) $_POST['folder_id'] : null;
        if ($folderId === 0) $folderId = null;
        mediaLibraryMove($ids, $folderId);
        flashSet('media_msg', 'Imagen(es) movida(s).');
        redirect('/admin/?view=media' . ($folderId ? '&folder=' . $folderId : ''));
    }

    if ($action === 'save_page' || $action === 'delete_page') {
        csrfCheck();
        $id = (int) ($_POST['id'] ?? 0);

        if ($action === 'delete_page' && $id > 0) {
            $stmt = getDB()->prepare('DELETE FROM pages WHERE id = ?');
            $stmt->execute([$id]);
            flashSet('page_success', 'Página eliminada.');
            redirect('/admin/?view=pages');
        }

        $slug    = slugify($_POST['slug'] ?? '');
        $title   = trim($_POST['title'] ?? '');
        $body    = $_POST['body'] ?? '';
        $meta    = trim($_POST['meta_description'] ?? '');
        $ogImage = trim($_POST['og_image'] ?? '');
        $hide    = !empty($_POST['hide_chrome']) ? 1 : 0;
        $pub     = !empty($_POST['is_published']) ? 1 : 0;

        if (!$slug || !$title) {
            $pageError = 'Slug y título son requeridos.';
            $pageFormData = ['id' => $id, 'slug' => $slug, 'title' => $title, 'body' => $body,
                             'meta_description' => $meta, 'og_image' => $ogImage,
                             'hide_chrome' => $hide, 'is_published' => $pub];
            $view = 'page';
        } else {
            try {
                if ($id > 0) {
                    $stmt = getDB()->prepare(
                        'UPDATE pages SET slug=?, title=?, body=?, meta_description=?, og_image=?, hide_chrome=?, is_published=? WHERE id=?'
                    );
                    $stmt->execute([$slug, $title, $body, $meta, $ogImage, $hide, $pub, $id]);
                } else {
                    $stmt = getDB()->prepare(
                        'INSERT INTO pages (slug, title, body, meta_description, og_image, hide_chrome, is_published) VALUES (?, ?, ?, ?, ?, ?, ?)'
                    );
                    $stmt->execute([$slug, $title, $body, $meta, $ogImage, $hide, $pub]);
                }
                flashSet('page_success', 'Página guardada.');
                redirect('/admin/?view=pages');
            } catch (PDOException $e) {
                $pageError = 'No se pudo guardar (¿slug duplicado?).';
                $pageFormData = ['id' => $id, 'slug' => $slug, 'title' => $title, 'body' => $body,
                                 'meta_description' => $meta, 'og_image' => $ogImage,
                                 'hide_chrome' => $hide, 'is_published' => $pub];
                $view = 'page';
            }
        }
    }
}

// -------------------- datos para views --------------------
$lead     = null;
$notes    = [];
$leads    = [];
$leadTasks  = [];
$taskStats  = ['overdue' => 0, 'today' => 0, 'upcoming' => 0, 'pending' => 0, 'completed' => 0];
$leadsStale = 0;
$leadId   = isset($_GET['id']) && $_GET['id'] !== 'new' ? (int) $_GET['id'] : 0;
$stats    = [
    'total' => 0, 'today' => 0, 'this_week' => 0, 'new' => 0,
    'contacted' => 0, 'meeting_scheduled' => 0, 'proposal_sent' => 0,
    'negotiation' => 0, 'won' => 0, 'lost' => 0,
    'na_overdue' => 0, 'na_today' => 0,
];
$search       = trim($_GET['search'] ?? '');
$statusFilter = $_GET['status_filter'] ?? '';
$accountFilter = (int) ($_GET['account'] ?? 0); // 0 = todas las cuentas
$pendingFilter = !empty($_GET['pending']);      // solo leads con próxima acción vencida
$accounts     = [];
$accountRec   = null;
$accountsMap  = []; // id => nombre, para mostrar la cuenta en listas
$page         = max(1, (int) ($_GET['page'] ?? 1));
$perPage      = 25;
$totalLeads   = 0;
$totalPages   = 1;
$settings     = [];
$pages        = [];
$pageRec      = $pageFormData ?? null; // datos del registro/form (distinto de $page paginación)
$users        = [];
$userRec      = null;
$userFormError = $userFormError ?? '';
$userFormEmail = $userFormEmail ?? '';
$userFormName  = $userFormName  ?? '';
$userPwError   = $userPwError   ?? '';
$userSearch    = $userSearch    ?? '';

if ($user) {
    $db = getDB();

    if ($view === 'settings') {
        foreach ($SETTING_KEYS as $k) {
            $settings[$k] = (string) getSetting($k, '');
        }
    } elseif ($view === 'mailing') {
        foreach ($MAILING_KEYS as $k) {
            $settings[$k] = (string) getSetting($k, '');
        }
    } elseif ($view === 'business') {
        $settings = businessInfo();
        $branches = branchesAll();
        $branchEdit = null;
        $editId = (int) ($_GET['branch'] ?? 0);
        if ($editId > 0) $branchEdit = branchGet($editId);
    } elseif ($view === 'pages') {
        $pages = $db->query('SELECT id, slug, title, is_published, updated_at FROM pages ORDER BY updated_at DESC')->fetchAll();
    } elseif ($view === 'page') {
        if ($pageRec === null) { // no vino de una re-render por error
            $pid = $_GET['id'] ?? '';
            if ($pid !== 'new' && $pid !== '') {
                $stmt = $db->prepare('SELECT * FROM pages WHERE id = ?');
                $stmt->execute([(int) $pid]);
                $pageRec = $stmt->fetch() ?: null;
            }
        }
    } elseif ($view === 'users') {
        $userSearch = trim($_GET['q'] ?? '');
        $users = usersList($userSearch);
    } elseif ($view === 'user') {
        $uid = (int) ($_GET['id'] ?? 0);
        $userRec = $uid > 0 ? userGet($uid) : null;
        if (!$userRec) redirect('/admin/?view=users');
    } elseif ($view === 'accounts') {
        $accounts = accountsAll();
    } elseif ($view === 'account_edit') {
        $aid = (int) ($_GET['id'] ?? 0);
        $accountRec = $aid > 0 ? accountGet($aid) : null;
        if (!$accountRec) redirect('/admin/?view=accounts');
    } elseif ($view === 'reports') {
        // Reportes comerciales (Fase 4): KPIs, embudo, revenue, forecast, fuente
        // y tendencia, filtrables por rango de fecha y cuenta.
        $accounts        = accountsAll();
        foreach ($accounts as $acc) $accountsMap[(int) $acc['id']] = $acc['name'];
        $reportRange     = reportResolveRange($_GET['range'] ?? '', $_GET['from'] ?? '', $_GET['to'] ?? '');
        $reportCur       = reportCurrency();
        $reportKpis      = reportKpis($accountFilter, $reportRange['from'], $reportRange['to']);
        $reportFunnel    = reportFunnel($accountFilter, $reportRange['from'], $reportRange['to']);
        $reportBySource  = reportBySource($accountFilter, $reportRange['from'], $reportRange['to']);
        $reportTrend     = reportMonthlyTrend($accountFilter, 6);
    } elseif ($view === 'tasks') {
        // Vista de tareas: cuatro buckets (hoy, vencidas, próximas, completadas)
        // respetando el filtro de cuenta, más el formulario de creación.
        $accounts      = accountsAll();
        $taskStatusF   = (string) ($_GET['task_status'] ?? '');
        $taskBucket    = (string) ($_GET['bucket'] ?? '');
        $taskSearch    = trim($_GET['search'] ?? '');
        $taskCountsAll = taskCounts($accountFilter);
        $commonF = ['account_id' => $accountFilter, 'search' => $taskSearch];
        // Si hay filtro explícito de estado/bucket, una sola lista; si no, los 4 buckets.
        if ($taskStatusF !== '' || $taskBucket !== '') {
            $tasksFiltered = tasksList($commonF + ['status' => $taskStatusF, 'bucket' => $taskBucket]);
        } else {
            $tasksOverdue   = tasksList($commonF + ['bucket' => 'overdue']);
            $tasksToday     = tasksList($commonF + ['bucket' => 'today']);
            $tasksUpcoming  = tasksList($commonF + ['bucket' => 'upcoming']);
            $tasksCompleted = tasksList($commonF + ['status' => 'completed', 'limit' => 50]);
        }
    } elseif ($leadId > 0) {
        $stmt = $db->prepare('SELECT * FROM leads WHERE id = ?');
        $stmt->execute([$leadId]);
        $lead = $stmt->fetch() ?: null;
        // Aislamiento por cuenta: si hay un filtro de cuenta activo y el lead
        // pertenece a otra cuenta, no se muestra (DoD #4).
        if ($lead && $accountFilter > 0 && (int) ($lead['account_id'] ?? 0) !== $accountFilter) {
            redirect('/admin/?account=' . $accountFilter);
        }
        if ($lead) {
            $lead['account_name'] = $lead['account_id']
                ? (string) ($db->query('SELECT name FROM accounts WHERE id = ' . (int) $lead['account_id'])->fetchColumn() ?: '')
                : '';
            // Timeline de actividad (notas + cambios de estado + próxima acción).
            $notes = listLeadActivities($leadId);
            // Tareas asociadas al lead (Fase 3).
            $leadTasks = tasksForLead($leadId);
        }
    } elseif (!in_array($view, ['account', 'accounts', 'account_edit', 'media', 'users', 'user', 'mailing', 'business', 'tasks', 'reports'], true)) {
        // Cuentas para el selector de filtro y el badge de cuenta en la lista.
        $accounts = accountsAll();
        foreach ($accounts as $acc) $accountsMap[(int) $acc['id']] = $acc['name'];
        // Scope de cuenta para las stats: '' (todas) o ' AND account_id = N'.
        // $accountFilter es un entero ya casteado: interpolación segura.
        $accScope = $accountFilter > 0 ? ' AND account_id = ' . $accountFilter : '';
        $accWhere = $accountFilter > 0 ? ' WHERE account_id = ' . $accountFilter : '';
        $stats['total']     = (int) $db->query("SELECT COUNT(*) FROM leads$accWhere")->fetchColumn();
        $stats['today']     = (int) $db->query("SELECT COUNT(*) FROM leads WHERE DATE(created_at) = CURDATE()$accScope")->fetchColumn();
        $stats['this_week'] = (int) $db->query("SELECT COUNT(*) FROM leads WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)$accScope")->fetchColumn();

        // Conteo por estado del pipeline (una sola pasada), respetando la cuenta.
        $byStatus = [];
        $rs = $db->query("SELECT status, COUNT(*) AS c FROM leads WHERE 1=1$accScope GROUP BY status");
        foreach ($rs as $row) $byStatus[$row['status']] = (int) $row['c'];
        foreach (['new','contacted','meeting_scheduled','proposal_sent','negotiation','won','lost'] as $st) {
            $stats[$st] = $byStatus[$st] ?? 0;
        }

        // Próximas acciones: vencidas (<= ahora) y de hoy, sin contar leads cerrados.
        // next_action_at se guarda con la zona horaria de la app (APP_TIMEZONE);
        // se compara contra el "ahora" de PHP — NO contra NOW()/CURDATE() de MySQL,
        // cuya @@session.time_zone puede diferir (Hostinger suele estar en UTC) y
        // descuadraría el conteo. created_at sigue usando NOW() de MySQL aparte.
        $nowPhp   = date('Y-m-d H:i:s');
        $todayPhp = date('Y-m-d');
        $naOverdueStmt = $db->prepare(
            "SELECT COUNT(*) FROM leads
              WHERE next_action_at IS NOT NULL AND next_action_at <= ?
                AND status NOT IN ('won','lost','closed','discarded')$accScope"
        );
        $naOverdueStmt->execute([$nowPhp]);
        $stats['na_overdue'] = (int) $naOverdueStmt->fetchColumn();
        $naTodayStmt = $db->prepare(
            "SELECT COUNT(*) FROM leads
              WHERE next_action_at IS NOT NULL AND DATE(next_action_at) = ?
                AND status NOT IN ('won','lost','closed','discarded')$accScope"
        );
        $naTodayStmt->execute([$todayPhp]);
        $stats['na_today'] = (int) $naTodayStmt->fetchColumn();

        // Alertas operativas (Fase 3): conteos de tareas + leads sin actividad,
        // respetando el filtro de cuenta. Defensivo si el módulo no migró aún.
        $taskStats   = taskCounts($accountFilter);
        $leadsStale  = leadsWithoutRecentActivityCount($accountFilter);

        $where  = [];
        $params = [];
        if ($accountFilter > 0) {
            $where[] = 'l.account_id = ?';
            $params[] = $accountFilter;
        }
        if ($search !== '') {
            $where[] = '(l.name LIKE ? OR l.email LIKE ? OR l.phone LIKE ?)';
            $like = '%' . $search . '%';
            array_push($params, $like, $like, $like);
        }
        if (leadStatusIsValid((string) $statusFilter)) {
            $where[] = 'l.status = ?';
            $params[] = $statusFilter;
        }
        if ($pendingFilter) {
            // Mismo criterio TZ-consistente que las stats: comparar contra $nowPhp.
            $where[] = "l.next_action_at IS NOT NULL AND l.next_action_at <= ?
                        AND l.status NOT IN ('won','lost','closed','discarded')";
            $params[] = $nowPhp;
        }
        $whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        $cnt = $db->prepare("SELECT COUNT(*) FROM leads l $whereSql");
        $cnt->execute($params);
        $totalLeads = (int) $cnt->fetchColumn();
        $totalPages = max(1, (int) ceil($totalLeads / $perPage));
        $page       = min($page, $totalPages);
        $offset     = ($page - 1) * $perPage;

        // Último movimiento = actividad más reciente del lead (subconsulta liviana).
        $sql = "SELECT l.id, l.account_id, l.name, l.email, l.phone, l.source, l.status,
                       l.next_action_at, l.next_action_note, l.created_at,
                       (SELECT MAX(la.created_at) FROM lead_activities la WHERE la.lead_id = l.id) AS last_activity_at
                  FROM leads l $whereSql
                 ORDER BY l.created_at DESC LIMIT ? OFFSET ?";
        $stmt = $db->prepare($sql);
        $i = 1;
        foreach ($params as $p) $stmt->bindValue($i++, $p, PDO::PARAM_STR);
        $stmt->bindValue($i++, $perPage, PDO::PARAM_INT);
        $stmt->bindValue($i++, $offset,  PDO::PARAM_INT);
        $stmt->execute();
        $leads = $stmt->fetchAll();
    }
}

$paginationUrl = function (int $p) use ($search, $statusFilter, $accountFilter, $pendingFilter): string {
    $params = array_filter([
        'account' => $accountFilter ?: '', 'search' => $search, 'status_filter' => $statusFilter,
        'pending' => $pendingFilter ? '1' : '', 'page' => $p,
    ], fn($v) => $v !== '' && $v !== null);
    return '/admin/?' . http_build_query($params);
};

// -------------------- render: login --------------------
if (!$user) {
    $siteName = getSetting('site_name', 'Mi Sitio');
    $initial  = strtoupper(mb_substr($siteName, 0, 1)) ?: 'A';
    require __DIR__ . '/../components/auth/login.php';
    exit;
}
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin — <?= htmlspecialchars(getSetting('site_name', 'Admin')) ?></title>
<?php
$faviconPath = (string) getSetting('favicon_image', '');
$faviconAbs  = $faviconPath ? __DIR__ . '/..' . $faviconPath : '';
if ($faviconPath && @file_exists($faviconAbs)):
    $faviconHref = htmlspecialchars($faviconPath . '?v=' . @filemtime($faviconAbs));
?>
<link rel="icon" href="<?= $faviconHref ?>">
<?php endif; ?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
<link rel="stylesheet" href="/assets/css/base.css">
<link rel="stylesheet" href="/assets/css/components.css">
<link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body class="admin-body">
<div class="admin-shell">

    <?php require __DIR__ . '/../components/admin_nav.php'; ?>

    <main class="admin-main">
        <?php
        if ($view === 'account') {
            require __DIR__ . '/../components/admin/account.php';
        } elseif ($view === 'settings') {
            require __DIR__ . '/../components/admin/settings.php';
        } elseif ($view === 'mailing') {
            require __DIR__ . '/../components/admin/mailing.php';
        } elseif ($view === 'business') {
            require __DIR__ . '/../components/admin/business.php';
        } elseif ($view === 'pages') {
            require __DIR__ . '/../components/admin/pages_list.php';
        } elseif ($view === 'page') {
            $page = $pageRec;
            require __DIR__ . '/../components/admin/page_edit.php';
        } elseif ($view === 'media') {
            require __DIR__ . '/../components/admin/media_library.php';
        } elseif ($view === 'users') {
            require __DIR__ . '/../components/admin/users_list.php';
        } elseif ($view === 'user') {
            require __DIR__ . '/../components/admin/user_edit.php';
        } elseif ($view === 'accounts') {
            require __DIR__ . '/../components/admin/accounts_list.php';
        } elseif ($view === 'account_edit') {
            require __DIR__ . '/../components/admin/account_edit.php';
        } elseif ($view === 'tasks') {
            require __DIR__ . '/../components/admin/tasks.php';
        } elseif ($view === 'reports') {
            require __DIR__ . '/../components/admin/reports.php';
        } elseif ($lead) {
            require __DIR__ . '/../components/admin/lead_detail.php';
        } else {
            require __DIR__ . '/../components/admin/dashboard.php';
        }
        ?>
    </main>

</div>

<script>
(function(){
    const toggle = document.getElementById('sidebar-toggle');
    const sidebar = document.getElementById('admin-sidebar');
    const backdrop = document.getElementById('sidebar-backdrop');
    if (!toggle || !sidebar || !backdrop) return;
    const close = () => { sidebar.classList.remove('is-open'); backdrop.classList.remove('is-open'); };
    toggle.addEventListener('click', () => {
        sidebar.classList.toggle('is-open');
        backdrop.classList.toggle('is-open');
    });
    backdrop.addEventListener('click', close);
    document.addEventListener('keydown', e => { if (e.key === 'Escape') close(); });
})();
</script>

</body>
</html>
