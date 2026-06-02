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
    if (empty($opts['skip_dedup'])) {
        $window = (int) ($opts['dedup_window_min'] ?? 5);
        $dupe = $db->prepare(
            'SELECT COUNT(*) FROM leads
             WHERE account_id = ? AND email = ?
               AND created_at > DATE_SUB(NOW(), INTERVAL ? MINUTE)'
        );
        $dupe->execute([$accountId, $email, $window]);
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

    return ['ok' => true, 'id' => $leadId, 'lead' => $lead];
}
