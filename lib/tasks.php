<?php

/**
 * Tareas comerciales internas — ARVIOR Core Fase 3.
 *
 * Centraliza la creación, completado y cancelación de tareas, y la
 * automatización interna que las genera (lead nuevo, propuesta enviada). Toda
 * tarea asociada a un lead deja rastro en el timeline (lead_activities), para
 * que el detalle del lead muestre una historia coherente sin duplicar lógica.
 *
 * Convive con `next_action_at` del lead: la próxima acción es el "qué sigue"
 * rápido; las tareas son la lista operativa con estado y vencimiento. No se
 * sincronizan automáticamente — son vistas complementarias del mismo trabajo.
 *
 * Defensivo ante esquema viejo (igual que accounts/leads): si la tabla `tasks`
 * todavía no existe (migración 019 sin correr), las lecturas devuelven []/0 y
 * las automatizaciones se saltan en silencio, sin romper la creación del lead.
 */

// Estados de una tarea, en orden operativo.
const TASK_STATUSES = ['pending', 'completed', 'cancelled'];

// Días por defecto para el seguimiento automático tras enviar una propuesta.
const TASK_PROPOSAL_FOLLOWUP_DAYS = 3;

// Umbral (días) para considerar un lead "sin actividad reciente" en el dashboard.
const LEAD_STALE_DAYS = 7;

/** Etiqueta legible (es) para un estado de tarea. */
function taskStatusLabel(string $status): string {
    static $map = [
        'pending'   => 'Pendiente',
        'completed' => 'Completada',
        'cancelled' => 'Cancelada',
    ];
    return $map[$status] ?? $status;
}

/** ¿Es un estado de tarea válido? */
function taskStatusIsValid(string $status): bool {
    return in_array($status, TASK_STATUSES, true);
}

/**
 * ¿La tabla `tasks` está disponible? (migración 019 aplicada). Permite que el
 * intake público y cualquier lectura degraden con elegancia si aún no se migró.
 */
function tasksSchemaReady(): bool {
    static $ready = null;
    if ($ready !== null) return $ready;
    try {
        $ready = (bool) getDB()->query("SHOW TABLES LIKE 'tasks'")->fetch();
    } catch (Throwable $e) {
        $ready = false;
    }
    return $ready;
}

/** Una tarea por id (con nombre de lead y cuenta resueltos), o null. */
function taskGet(int $id): ?array {
    if ($id <= 0 || !tasksSchemaReady()) return null;
    try {
        $stmt = getDB()->prepare(
            'SELECT t.*, l.name AS lead_name, a.name AS account_name
               FROM tasks t
               LEFT JOIN leads l    ON l.id = t.lead_id
               LEFT JOIN accounts a ON a.id = t.account_id
              WHERE t.id = ?'
        );
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    } catch (Throwable $e) {
        error_log('taskGet: ' . $e->getMessage());
        return null;
    }
}

/**
 * Normaliza un datetime-local ('Y-m-d\TH:i') o cadena de fecha a 'Y-m-d H:i:s'.
 * Devuelve null si está vacío, o false si es inválido.
 * @return string|null|false
 */
function taskParseDueAt(?string $at) {
    $at = trim((string) $at);
    if ($at === '') return null;
    $ts = strtotime($at);
    if ($ts === false) return false;
    return date('Y-m-d H:i:s', $ts);
}

/**
 * Crea una tarea. Si está asociada a un lead, registra la actividad
 * 'task_created' en su timeline. Nunca lanza por el log de actividad.
 *
 * @param array $input  title, description, account_id, lead_id, due_at
 * @return array{ok:bool, id?:int, error?:string}
 */
function taskCreate(array $input, ?int $userId = null): array {
    if (!tasksSchemaReady()) return ['ok' => false, 'error' => 'El módulo de tareas no está disponible todavía.'];

    $title = trim((string) ($input['title'] ?? ''));
    if ($title === '') return ['ok' => false, 'error' => 'El título de la tarea es requerido.'];
    $title = mb_substr($title, 0, 200);

    $desc    = trim((string) ($input['description'] ?? ''));
    $descVal = $desc !== '' ? $desc : null;

    $leadId    = (int) ($input['lead_id'] ?? 0) ?: null;
    $accountId = (int) ($input['account_id'] ?? 0) ?: null;

    $db = getDB();

    // Si viene lead pero no cuenta, hereda la cuenta del lead (coherencia de scope).
    if ($leadId && !$accountId) {
        $cur = $db->prepare('SELECT account_id FROM leads WHERE id = ?');
        $cur->execute([$leadId]);
        $accRaw = $cur->fetchColumn();
        if ($accRaw === false) return ['ok' => false, 'error' => 'Lead no encontrado.'];
        $accountId = $accRaw !== null ? (int) $accRaw : null;
    }

    $dueAt = taskParseDueAt($input['due_at'] ?? null);
    if ($dueAt === false) return ['ok' => false, 'error' => 'Fecha de vencimiento inválida.'];

    try {
        $stmt = $db->prepare(
            'INSERT INTO tasks (account_id, lead_id, title, description, status, due_at, created_by)
             VALUES (?, ?, ?, ?, "pending", ?, ?)'
        );
        $stmt->execute([$accountId, $leadId, $title, $descVal, $dueAt, $userId]);
        $taskId = (int) $db->lastInsertId();
    } catch (Throwable $e) {
        error_log('taskCreate: ' . $e->getMessage());
        return ['ok' => false, 'error' => 'No se pudo crear la tarea.'];
    }

    if ($leadId) {
        addLeadActivity($leadId, $accountId, 'task_created', [
            'user_id' => $userId,
            'body'    => 'Tarea creada: ' . $title . ($dueAt ? ' (vence ' . $dueAt . ')' : '') . '.',
            'meta'    => ['task_id' => $taskId],
        ]);
    }
    return ['ok' => true, 'id' => $taskId];
}

/**
 * Marca una tarea como completada (idempotente: no re-loguea si ya lo estaba).
 * Si está asociada a un lead, registra 'task_completed' en su timeline.
 *
 * @return array{ok:bool, error?:string}
 */
function taskComplete(int $id, ?int $userId = null): array {
    return taskTransition($id, 'completed', $userId);
}

/**
 * Cancela una tarea. Si está asociada a un lead, registra 'task_cancelled'.
 *
 * @return array{ok:bool, error?:string}
 */
function taskCancel(int $id, ?int $userId = null): array {
    return taskTransition($id, 'cancelled', $userId);
}

/**
 * Reabre una tarea (vuelve a pending y limpia completed_at). Pensado para
 * deshacer un completado/cancelado por error.
 *
 * @return array{ok:bool, error?:string}
 */
function taskReopen(int $id, ?int $userId = null): array {
    return taskTransition($id, 'pending', $userId);
}

/**
 * Cambia el estado de una tarea y deja rastro en el lead (si aplica).
 * No re-loguea si el estado no cambia. completed_at se setea/limpia coherente.
 *
 * @return array{ok:bool, error?:string}
 */
function taskTransition(int $id, string $status, ?int $userId = null): array {
    if (!tasksSchemaReady())        return ['ok' => false, 'error' => 'El módulo de tareas no está disponible.'];
    if ($id <= 0)                   return ['ok' => false, 'error' => 'Tarea inválida.'];
    if (!taskStatusIsValid($status))return ['ok' => false, 'error' => 'Estado de tarea inválido.'];

    $db  = getDB();
    $cur = $db->prepare('SELECT account_id, lead_id, title, status FROM tasks WHERE id = ?');
    $cur->execute([$id]);
    $task = $cur->fetch();
    if (!$task) return ['ok' => false, 'error' => 'Tarea no encontrada.'];
    if ($task['status'] === $status) return ['ok' => true];

    $completedAt = $status === 'completed' ? date('Y-m-d H:i:s') : null;
    $upd = $db->prepare('UPDATE tasks SET status = ?, completed_at = ? WHERE id = ?');
    $upd->execute([$status, $completedAt, $id]);

    $leadId = $task['lead_id'] !== null ? (int) $task['lead_id'] : 0;
    if ($leadId > 0) {
        $type = [
            'completed' => 'task_completed',
            'cancelled' => 'task_cancelled',
            'pending'   => 'task_reopened',
        ][$status] ?? 'task_updated';
        $verb = ['completed' => 'completada', 'cancelled' => 'cancelada', 'pending' => 'reabierta'][$status] ?? 'actualizada';
        addLeadActivity($leadId, $task['account_id'] !== null ? (int) $task['account_id'] : null, $type, [
            'user_id' => $userId,
            'body'    => 'Tarea ' . $verb . ': ' . (string) $task['title'] . '.',
            'meta'    => ['task_id' => $id],
        ]);
    }
    return ['ok' => true];
}

/** Tareas asociadas a un lead (pendientes primero, luego por vencimiento). */
function tasksForLead(int $leadId): array {
    if ($leadId <= 0 || !tasksSchemaReady()) return [];
    try {
        $stmt = getDB()->prepare(
            "SELECT * FROM tasks
              WHERE lead_id = ?
              ORDER BY (status = 'pending') DESC,
                       due_at IS NULL, due_at ASC, created_at DESC"
        );
        $stmt->execute([$leadId]);
        return $stmt->fetchAll();
    } catch (Throwable $e) {
        error_log('tasksForLead: ' . $e->getMessage());
        return [];
    }
}

/**
 * Lista tareas para la vista de admin, con filtros opcionales.
 *
 * @param array $f  account_id(int), status(string), bucket('overdue'|'today'|'upcoming'),
 *                  search(string), limit(int)
 */
function tasksList(array $f = []): array {
    if (!tasksSchemaReady()) return [];

    $where  = [];
    $params = [];

    $accountId = (int) ($f['account_id'] ?? 0);
    if ($accountId > 0) { $where[] = 't.account_id = ?'; $params[] = $accountId; }

    $status = (string) ($f['status'] ?? '');
    if (taskStatusIsValid($status)) { $where[] = 't.status = ?'; $params[] = $status; }

    // Buckets temporales: se evalúan contra el "ahora" de PHP (APP_TIMEZONE),
    // igual que las próximas acciones, para no descuadrar con la TZ de MySQL.
    $bucket = (string) ($f['bucket'] ?? '');
    if ($bucket === 'overdue') {
        $where[] = "t.status = 'pending' AND t.due_at IS NOT NULL AND t.due_at < ?";
        $params[] = date('Y-m-d H:i:s');
    } elseif ($bucket === 'today') {
        $where[] = "t.status = 'pending' AND t.due_at IS NOT NULL AND DATE(t.due_at) = ?";
        $params[] = date('Y-m-d');
    } elseif ($bucket === 'upcoming') {
        $where[] = "t.status = 'pending' AND (t.due_at IS NULL OR t.due_at > ?)";
        $params[] = date('Y-m-d H:i:s');
    }

    $search = trim((string) ($f['search'] ?? ''));
    if ($search !== '') {
        $where[] = '(t.title LIKE ? OR t.description LIKE ?)';
        $like = '%' . $search . '%';
        array_push($params, $like, $like);
    }

    $whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';
    $limit    = max(1, min(500, (int) ($f['limit'] ?? 200)));

    try {
        $stmt = getDB()->prepare(
            "SELECT t.*, l.name AS lead_name, a.name AS account_name
               FROM tasks t
               LEFT JOIN leads l    ON l.id = t.lead_id
               LEFT JOIN accounts a ON a.id = t.account_id
             $whereSql
             ORDER BY t.due_at IS NULL, t.due_at ASC, t.created_at DESC
             LIMIT $limit"
        );
        $stmt->execute($params);
        return $stmt->fetchAll();
    } catch (Throwable $e) {
        error_log('tasksList: ' . $e->getMessage());
        return [];
    }
}

/**
 * Conteos de tareas para el dashboard, respetando el filtro de cuenta.
 * @return array{overdue:int, today:int, upcoming:int, pending:int, completed:int}
 */
function taskCounts(int $accountId = 0): array {
    $out = ['overdue' => 0, 'today' => 0, 'upcoming' => 0, 'pending' => 0, 'completed' => 0];
    if (!tasksSchemaReady()) return $out;

    // $accountId es un entero ya casteado: interpolación segura para el scope.
    $scope = $accountId > 0 ? ' AND account_id = ' . $accountId : '';
    $now   = date('Y-m-d H:i:s');
    $today = date('Y-m-d');
    $db    = getDB();

    try {
        $q = function (string $cond, array $p) use ($db, $scope): int {
            $stmt = $db->prepare("SELECT COUNT(*) FROM tasks WHERE $cond$scope");
            $stmt->execute($p);
            return (int) $stmt->fetchColumn();
        };
        $out['overdue']   = $q("status = 'pending' AND due_at IS NOT NULL AND due_at < ?", [$now]);
        $out['today']     = $q("status = 'pending' AND due_at IS NOT NULL AND DATE(due_at) = ?", [$today]);
        $out['upcoming']  = $q("status = 'pending' AND (due_at IS NULL OR due_at > ?)", [$now]);
        $out['pending']   = $q("status = 'pending'", []);
        $out['completed'] = $q("status = 'completed'", []);
    } catch (Throwable $e) {
        error_log('taskCounts: ' . $e->getMessage());
    }
    return $out;
}

/**
 * Cantidad de leads activos sin actividad en los últimos LEAD_STALE_DAYS días,
 * respetando el filtro de cuenta. "Sin actividad" = sin filas recientes en
 * lead_activities. Excluye leads en estado terminal.
 */
function leadsWithoutRecentActivityCount(int $accountId = 0): int {
    try {
        $db    = getDB();
        $scope = $accountId > 0 ? ' AND l.account_id = ' . $accountId : '';
        $cutoff = date('Y-m-d H:i:s', strtotime('-' . LEAD_STALE_DAYS . ' days'));
        $stmt = $db->prepare(
            "SELECT COUNT(*) FROM leads l
              WHERE l.status NOT IN ('won','lost','closed','discarded')$scope
                AND NOT EXISTS (
                    SELECT 1 FROM lead_activities a
                     WHERE a.lead_id = l.id AND a.created_at >= ?
                )"
        );
        $stmt->execute([$cutoff]);
        return (int) $stmt->fetchColumn();
    } catch (Throwable $e) {
        error_log('leadsWithoutRecentActivityCount: ' . $e->getMessage());
        return 0;
    }
}

// ───────────────────── Automatización interna (Fase 3) ─────────────────────
//
// Sin n8n, sin emails, sin WhatsApp: solo creación de registros disparada por
// eventos internos. Todas las funciones son defensivas — si la tabla no existe
// o algo falla, loguean y siguen, porque el lead/estado ya quedó guardado.

/**
 * Lead nuevo → tarea automática "Contactar lead" (vence en 24h).
 * Idempotente por lead: no crea otra si ya existe una tarea de contacto pendiente.
 * La actividad 'created' la registra leadCreate(); aquí solo va la tarea.
 */
function autoTaskOnLeadCreated(int $leadId, ?int $accountId, string $leadName = ''): void {
    if ($leadId <= 0 || !tasksSchemaReady()) return;
    try {
        $db  = getDB();
        $chk = $db->prepare("SELECT COUNT(*) FROM tasks WHERE lead_id = ? AND status = 'pending'");
        $chk->execute([$leadId]);
        if ((int) $chk->fetchColumn() > 0) return; // ya hay seguimiento pendiente

        taskCreate([
            'title'      => 'Contactar lead' . ($leadName !== '' ? ': ' . $leadName : ''),
            'account_id' => $accountId,
            'lead_id'    => $leadId,
            'due_at'     => date('Y-m-d H:i:s', strtotime('+1 day')),
        ], null);
    } catch (Throwable $e) {
        error_log('autoTaskOnLeadCreated: ' . $e->getMessage());
    }
}

/**
 * Estado → proposal_sent → tarea de seguimiento a TASK_PROPOSAL_FOLLOWUP_DAYS días.
 * Idempotente: no duplica si ya hay un seguimiento de propuesta pendiente.
 */
function autoTaskOnProposalSent(int $leadId, ?int $accountId, ?int $userId = null): void {
    if ($leadId <= 0 || !tasksSchemaReady()) return;
    try {
        $db  = getDB();
        $chk = $db->prepare(
            "SELECT COUNT(*) FROM tasks WHERE lead_id = ? AND status = 'pending' AND title LIKE 'Seguimiento de propuesta%'"
        );
        $chk->execute([$leadId]);
        if ((int) $chk->fetchColumn() > 0) return;

        taskCreate([
            'title'      => 'Seguimiento de propuesta',
            'description' => 'Confirmar recepción y avanzar la propuesta enviada.',
            'account_id' => $accountId,
            'lead_id'    => $leadId,
            'due_at'     => date('Y-m-d H:i:s', strtotime('+' . TASK_PROPOSAL_FOLLOWUP_DAYS . ' days')),
        ], $userId);
    } catch (Throwable $e) {
        error_log('autoTaskOnProposalSent: ' . $e->getMessage());
    }
}
