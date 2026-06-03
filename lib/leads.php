<?php

/**
 * Creación de leads compartida — ARVIOR Core Fase 1.
 *
 * Centraliza validación, dedup por cuenta, INSERT con account_id y registro de
 * actividad, para que el sitio propio (index.php) y el intake público
 * (intake.php) compartan exactamente la misma lógica. El anti-spam de
 * presentación (honeypot + timing + CSRF) sigue viviendo en el controlador,
 * porque depende del request; aquí va la lógica de datos.
 *
 * Diseñado para Fase 2: tras crear el lead, este es el punto único donde luego
 * se encolará la `outbox` y se disparará n8n. Hoy solo registra 'created'.
 */

// ───────────────────────── Pipeline CRM (Fase 2) ─────────────────────────
//
// Estados operativos del embudo, en orden de avance. La lista la consumen el
// dashboard, la lista de leads, el detalle, el filtro y el export, para que
// todos hablen del mismo conjunto canónico.
const LEAD_PIPELINE_STATUSES = [
    'new', 'contacted', 'meeting_scheduled', 'proposal_sent', 'negotiation', 'won', 'lost',
];

// Estados legacy de Fase 1: ya no se ofrecen para clasificar, pero siguen
// siendo válidos para no romper leads históricos que aún los tengan.
const LEAD_LEGACY_STATUSES = ['qualified', 'closed', 'discarded'];

// Estados terminales: el lead salió del pipeline activo (no cuentan como
// "pendientes" para próximas acciones).
const LEAD_CLOSED_STATUSES = ['won', 'lost', 'closed', 'discarded'];

/** Etiqueta legible (es) para un estado del lead. */
function leadStatusLabel(string $status): string {
    static $map = [
        'new'               => 'Nuevo',
        'contacted'         => 'Contactado',
        'meeting_scheduled' => 'Reunión agendada',
        'proposal_sent'     => 'Propuesta enviada',
        'negotiation'       => 'Negociación',
        'won'               => 'Ganado',
        'lost'              => 'Perdido',
        // Legacy.
        'qualified'         => 'Calificado',
        'closed'            => 'Cerrado',
        'discarded'         => 'Descartado',
    ];
    return $map[$status] ?? $status;
}

/** ¿Es un estado válido (pipeline o legacy)? Para validar input del operador. */
function leadStatusIsValid(string $status): bool {
    return in_array($status, LEAD_PIPELINE_STATUSES, true)
        || in_array($status, LEAD_LEGACY_STATUSES, true);
}

/**
 * API centralizada de actividad: alias estable sobre leadLogActivity().
 * Existe para que los controladores usen un nombre único (Fase 2) sin duplicar
 * la lógica de inserción/serialización, que sigue viviendo en leadLogActivity().
 */
function addLeadActivity(int $leadId, ?int $accountId, string $type, array $opts = []): void {
    leadLogActivity($leadId, $accountId, $type, $opts);
}

/**
 * Timeline de actividad de un lead (más reciente primero), con el email del
 * autor resuelto. Punto único de lectura para el detalle del lead.
 */
function listLeadActivities(int $leadId): array {
    if ($leadId <= 0) return [];
    try {
        $stmt = getDB()->prepare(
            'SELECT a.type, a.from_status, a.to_status, a.body, a.created_at, u.email AS author_email
               FROM lead_activities a
               LEFT JOIN users u ON u.id = a.user_id
              WHERE a.lead_id = ?
              ORDER BY a.created_at DESC, a.id DESC'
        );
        $stmt->execute([$leadId]);
        return $stmt->fetchAll();
    } catch (Throwable $e) {
        error_log('listLeadActivities: ' . $e->getMessage());
        return [];
    }
}

/**
 * Cambia el estado de un lead y registra la actividad 'status_change'.
 * No re-loguea si el estado no cambia. Mantiene el account_id del lead.
 *
 * @return array{ok:bool, unchanged?:bool, error?:string}
 */
function updateLeadStatus(int $leadId, string $status, ?int $userId = null): array {
    if ($leadId <= 0)               return ['ok' => false, 'error' => 'Lead inválido.'];
    if (!leadStatusIsValid($status)) return ['ok' => false, 'error' => 'Estado inválido.'];

    $db  = getDB();
    $cur = $db->prepare('SELECT account_id, status FROM leads WHERE id = ?');
    $cur->execute([$leadId]);
    $before = $cur->fetch();
    if (!$before) return ['ok' => false, 'error' => 'Lead no encontrado.'];

    if ($before['status'] === $status) return ['ok' => true, 'unchanged' => true];

    $upd = $db->prepare('UPDATE leads SET status = ? WHERE id = ?');
    $upd->execute([$status, $leadId]);

    $accId = isset($before['account_id']) ? (int) $before['account_id'] : null;
    addLeadActivity($leadId, $accId, 'status_change', [
        'user_id'     => $userId,
        'from_status' => $before['status'],
        'to_status'   => $status,
    ]);

    // Automatización interna (Fase 3): al pasar a "propuesta enviada", crear
    // una tarea de seguimiento. Defensiva: se salta si no está el módulo.
    if ($status === 'proposal_sent' && function_exists('autoTaskOnProposalSent')) {
        autoTaskOnProposalSent($leadId, $accId, $userId);
    }
    return ['ok' => true];
}

/**
 * Registra / edita / limpia la próxima acción del lead (next_action_at + note)
 * y deja rastro en el timeline. `$at` acepta el formato de datetime-local
 * ('YYYY-MM-DDTHH:MM') o vacío; ambos vacíos = limpiar.
 *
 * @return array{ok:bool, error?:string}
 */
function updateLeadNextAction(int $leadId, ?string $at, ?string $note, ?int $userId = null): array {
    if ($leadId <= 0) return ['ok' => false, 'error' => 'Lead inválido.'];

    $db  = getDB();
    $cur = $db->prepare('SELECT account_id FROM leads WHERE id = ?');
    $cur->execute([$leadId]);
    $accRaw = $cur->fetchColumn();
    if ($accRaw === false) return ['ok' => false, 'error' => 'Lead no encontrado.'];
    $accId = $accRaw !== null ? (int) $accRaw : null;

    $at   = trim((string) $at);
    $note = trim((string) $note);

    $dt = null;
    if ($at !== '') {
        $ts = strtotime($at);
        if ($ts === false) return ['ok' => false, 'error' => 'Fecha de próxima acción inválida.'];
        $dt = date('Y-m-d H:i:s', $ts);
    }
    $noteVal = $note !== '' ? mb_substr($note, 0, 500) : null;

    $upd = $db->prepare('UPDATE leads SET next_action_at = ?, next_action_note = ? WHERE id = ?');
    $upd->execute([$dt, $noteVal, $leadId]);

    if ($dt === null && $noteVal === null) {
        addLeadActivity($leadId, $accId, 'next_action_cleared', [
            'user_id' => $userId,
            'body'    => 'Próxima acción eliminada.',
        ]);
    } else {
        $body = 'Próxima acción'
            . ($dt ? ' para ' . $dt : '')
            . ($noteVal ? ': ' . $noteVal : '') . '.';
        addLeadActivity($leadId, $accId, 'next_action', [
            'user_id' => $userId,
            'body'    => $body,
            'meta'    => ['at' => $dt, 'note' => $noteVal],
        ]);
    }
    return ['ok' => true];
}

/**
 * ¿El esquema multi-cuenta está disponible? (columna leads.account_id presente).
 * Lo usa intake.php para responder un error controlado en vez de fatal si las
 * migraciones todavía no corrieron desde /admin/ (decisión R1 del plan).
 */
function leadsSchemaReady(): bool {
    try {
        $db = getDB();
        $col = $db->query("SHOW COLUMNS FROM leads LIKE 'account_id'")->fetch();
        if (!$col) return false;
        $tbl = $db->query("SHOW TABLES LIKE 'lead_activities'")->fetch();
        return (bool) $tbl;
    } catch (Throwable $e) {
        return false;
    }
}

/**
 * Registra una actividad del lead. Nunca lanza: si falla, loguea y sigue
 * (el lead ya está guardado; perder un log no debe romper el flujo).
 *
 * @param array $opts user_id, from_status, to_status, body, meta(array)
 */
function leadLogActivity(int $leadId, ?int $accountId, string $type, array $opts = []): void {
    if ($leadId <= 0) return;
    try {
        $meta = isset($opts['meta']) && $opts['meta'] !== null
            ? json_encode($opts['meta'], JSON_UNESCAPED_UNICODE)
            : null;
        $stmt = getDB()->prepare(
            'INSERT INTO lead_activities (lead_id, account_id, user_id, type, from_status, to_status, body, meta)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $leadId,
            $accountId,
            $opts['user_id']     ?? null,
            $type,
            $opts['from_status'] ?? null,
            $opts['to_status']   ?? null,
            $opts['body']        ?? null,
            $meta,
        ]);
    } catch (Throwable $e) {
        error_log('leadLogActivity: ' . $e->getMessage());
    }
}

/**
 * Valida, deduplica y crea un lead para una cuenta.
 *
 * @param array $input  name, email, phone, message, source
 * @param int   $accountId  cuenta destino (interna o de cliente)
 * @param array $opts    'dedup_window_min' (int, default 5), 'skip_dedup' (bool)
 *
 * @return array{
 *   ok:bool,
 *   id?:int,
 *   lead?:array,
 *   duplicate?:bool,   // true = dedup silencioso (tratar como éxito de cara al bot/usuario)
 *   error?:string
 * }
 */
function leadCreate(array $input, int $accountId, array $opts = []): array {
    $name    = trim((string) ($input['name']    ?? ''));
    $email   = trim((string) ($input['email']   ?? ''));
    $phone   = trim((string) ($input['phone']   ?? ''));
    $message = trim((string) ($input['message'] ?? ''));
    $source  = trim((string) ($input['source']  ?? 'website')) ?: 'website';

    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['ok' => false, 'error' => 'Nombre y email válido son requeridos.'];
    }
    if ($accountId <= 0) {
        return ['ok' => false, 'error' => 'Cuenta destino inválida.'];
    }

    $db = getDB();

    // Dedup por cuenta: mismo email en los últimos N minutos → éxito silencioso.
    // El intervalo NO va como placeholder (`INTERVAL ? MINUTE` puede fallar con
    // prepares server-side según el motor): se interpola el entero ya casteado,
    // lo que es seguro frente a inyección y portable en MySQL/MariaDB. La ventana
    // de tiempo se evalúa con NOW() en la BD para no depender del reloj de PHP.
    if (empty($opts['skip_dedup'])) {
        $window = max(1, (int) ($opts['dedup_window_min'] ?? 5));
        $dupe = $db->prepare(
            "SELECT COUNT(*) FROM leads
             WHERE account_id = ? AND email = ?
               AND created_at > DATE_SUB(NOW(), INTERVAL $window MINUTE)"
        );
        $dupe->execute([$accountId, $email]);
        if ((int) $dupe->fetchColumn() > 0) {
            return ['ok' => true, 'duplicate' => true];
        }
    }

    $stmt = $db->prepare(
        'INSERT INTO leads (account_id, name, email, phone, message, source, status, ip_address, user_agent)
         VALUES (?, ?, ?, ?, ?, ?, "new", ?, ?)'
    );
    $stmt->execute([
        $accountId, $name, $email, $phone, $message, $source,
        clientIp(),
        substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 500),
    ]);

    $leadId = (int) $db->lastInsertId();
    $lead = [
        'id'         => $leadId,
        'account_id' => $accountId,
        'name'       => $name,
        'email'      => $email,
        'phone'      => $phone,
        'message'    => $message,
        'source'     => $source,
    ];

    // Actividad de creación (sistema). No rompe si falla.
    leadLogActivity($leadId, $accountId, 'created', [
        'body' => 'Lead capturado (' . $source . ').',
    ]);

    // Automatización interna (Fase 3): tarea automática "Contactar lead".
    // Defensiva: si el módulo de tareas no está disponible, se salta en silencio.
    if (function_exists('autoTaskOnLeadCreated')) {
        autoTaskOnLeadCreated($leadId, $accountId, $name);
    }

    return ['ok' => true, 'id' => $leadId, 'lead' => $lead];
}
