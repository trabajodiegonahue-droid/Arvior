<?php

/**
 * Intake público multi-cuenta — ARVIOR Core Fase 1.
 * ------------------------------------------------------------
 * Recibe leads desde el formulario/landing de CADA cuenta cliente y los guarda
 * con su `account_id`, resuelto a partir del `public_token` de la cuenta.
 *
 * Autenticación (decisión D2): SOLO el public_token de la cuenta. Sin login.
 * Validaciones que aplica:
 *   - método POST (y OPTIONS para preflight CORS)
 *   - public_token válido y de una cuenta activa
 *   - anti-spam: honeypot + timing (cuando el form los envía)
 *   - dedup por cuenta (mismo email en ventana corta)
 *   - rate limit básico por IP
 *
 * Defensa R1: si el esquema multi-cuenta todavía no fue migrado (las migraciones
 * corren al entrar a /admin/), responde un error controlado y lo loguea — nunca
 * un fatal. ARVIOR debe correr/verificar migraciones desde /admin/ ANTES de
 * instalar la landing de un cliente (checklist operativo).
 *
 * Modos de respuesta:
 *   - JSON  (format=json o cabecera Accept/X-Requested-With) → para fetch cross-domain
 *   - Form  (default)                                        → redirect a /gracias
 * ------------------------------------------------------------
 */

require __DIR__ . '/lib/bootstrap.php';

// --- ¿El cliente espera JSON? (fetch desde otra landing) ---
$wantsJson =
    (($_GET['format'] ?? $_POST['format'] ?? '') === 'json') ||
    (stripos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false) ||
    (strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest');

// CORS: la landing del cliente vive en otro dominio. Intake es escritura pública
// por token, así que permitimos el POST cross-origin para el modo JSON.
if ($wantsJson) {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, X-Requested-With');
    header('Vary: Origin');
}

/** Respuesta unificada: JSON o redirect según el modo. */
function intakeRespond(bool $ok, string $message, int $httpCode, bool $wantsJson, string $redirectTo = '/gracias'): void {
    if ($wantsJson) {
        http_response_code($httpCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['ok' => $ok, 'message' => $message]);
        exit;
    }
    if ($ok) {
        redirect($redirectTo);
    }
    // En modo form, un error de validación vuelve al referer con un flag.
    $back = $_SERVER['HTTP_REFERER'] ?? '/';
    redirect($back . (str_contains($back, '?') ? '&' : '?') . 'lead_error=1');
}

// Preflight CORS.
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Solo POST.
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    intakeRespond(false, 'Método no permitido.', 405, $wantsJson);
}

// Defensa R1: esquema migrado.
if (!leadsSchemaReady()) {
    error_log('intake.php: esquema multi-cuenta no disponible (¿migraciones sin correr desde /admin/?).');
    intakeRespond(false, 'El servicio no está disponible temporalmente.', 503, $wantsJson);
}

// Token de la cuenta.
$token   = trim((string) ($_POST['public_token'] ?? $_GET['public_token'] ?? ''));
$account = $token !== '' ? accountResolveByToken($token) : null;

// Anti-spam: honeypot + timing (solo si el form los envía).
$honeypotTripped = !empty($_POST['website']);
$tooFast = isset($_POST['form_started']) && (time() - (int) $_POST['form_started']) < 2;

if ($honeypotTripped || $tooFast) {
    // Éxito falso: el bot no se entera, no creamos nada.
    intakeRespond(true, 'Recibido.', 200, $wantsJson);
}

// Token inválido / cuenta no activa → rechazo genérico (no revela existencia).
if (!$account) {
    intakeRespond(false, 'No se pudo procesar la solicitud.', 403, $wantsJson);
}

$accountId = (int) $account['id'];

// Rate limit básico por IP: máx. 10 leads/min desde la misma IP (todas las cuentas).
try {
    $rl = getDB()->prepare(
        'SELECT COUNT(*) FROM leads
         WHERE ip_address = ? AND created_at > DATE_SUB(NOW(), INTERVAL 1 MINUTE)'
    );
    $rl->execute([clientIp()]);
    if ((int) $rl->fetchColumn() >= 10) {
        intakeRespond(false, 'Demasiadas solicitudes. Probá de nuevo en un momento.', 429, $wantsJson);
    }
} catch (Throwable $e) {
    error_log('intake rate-limit: ' . $e->getMessage());
}

// Crear el lead (validación + dedup + insert + actividad compartidos).
$result = leadCreate([
    'name'    => $_POST['name']    ?? '',
    'email'   => $_POST['email']   ?? '',
    'phone'   => $_POST['phone']   ?? '',
    'message' => $_POST['message'] ?? '',
    'source'  => $_POST['source']  ?? 'landing',
], $accountId);

if (empty($result['ok'])) {
    intakeRespond(false, $result['error'] ?? 'Datos inválidos.', 422, $wantsJson);
}

// Notificación por correo (best-effort, no bloquea). Reutiliza el mailer actual.
if (empty($result['duplicate']) && !empty($result['lead'])) {
    try { notifyLeadCreated($result['lead']); } catch (Throwable $e) { error_log('intake notify: ' . $e->getMessage()); }
    try { sendLeadAutoReply($result['lead']); } catch (Throwable $e) { error_log('intake autoreply: ' . $e->getMessage()); }
}

intakeRespond(true, '¡Gracias! Recibimos tu mensaje.', 200, $wantsJson);
