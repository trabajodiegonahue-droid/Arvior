<?php

require __DIR__ . '/lib/bootstrap.php';

/**
 * Front público — ARVIOR Portal Comercial (Fase 6).
 *
 * Orden:
 *   1) Manejo del POST de formularios (cotización / contacto / servicio / home).
 *      Todos usan action=submit_lead y caen en leadCreate() → CRM existente.
 *   2) Router GET: rutas del portal → plantillas en components/portal/;
 *      si no, página del CMS (Páginas); si no, home; si no, 404.
 *
 * No se crea esquema ni sistemas paralelos: el portal reutiliza el CRM, el
 * sistema de Páginas, settings y el layout/SEO ya existentes.
 */

/** Renderiza un 404 con el layout del sitio (sin re-incluir bootstrap). */
function portalRender404(): void {
    http_response_code(404);
    layoutStart([
        'title'       => 'Página no encontrada (404)',
        'description' => 'La página que buscas no existe o fue movida.',
    ]);
    ?>
<main class="container" style="padding:3rem 1.2rem;text-align:center;">
    <h1 style="font-size:2.4rem;margin:0 0 .5rem;">404</h1>
    <p style="font-size:1.1rem;color:#64748b;margin:0 0 1.5rem;">No encontramos la página que buscas.</p>
    <p><a href="/" class="btn">← Volver al inicio</a></p>
</main>
<?php
    layoutEnd();
}

/** Devuelve un path local seguro para redirección (evita open-redirect). */
function portalSafeReturn(string $p): string {
    $p = trim($p);
    if ($p === '' || $p[0] !== '/' || str_starts_with($p, '//')) return '/';
    // Solo el path, sin esquema ni host.
    $path = parse_url($p, PHP_URL_PATH);
    return is_string($path) && $path !== '' ? $path : '/';
}

$error = '';

// ───────────────────────── POST: alta de lead ─────────────────────────
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['action'] ?? '') === 'submit_lead') {
    $returnTo = portalSafeReturn((string) ($_POST['redirect_to'] ?? '/'));

    // Anti-spam: honeypot + timing. Éxito falso (los bots no se enteran).
    $honeypotTripped = !empty($_POST['website']);
    $tooFast         = !isset($_POST['form_started']) || (time() - (int) $_POST['form_started']) < 2;
    if ($honeypotTripped || $tooFast) {
        redirect($returnTo . '?sent=1');
    }

    csrfCheck();
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $phone   = trim($_POST['phone'] ?? '');
    $company = trim($_POST['company'] ?? '');
    $service = trim($_POST['service'] ?? '');
    $budget  = trim($_POST['budget'] ?? '');
    $userMsg = trim($_POST['message'] ?? '');
    // source identifica el origen dentro del portal (cotizacion, contacto,
    // servicio:slug, website). Sirve para "performance por fuente" en Reportes.
    $source  = substr(trim((string) ($_POST['source'] ?? 'website')) ?: 'website', 0, 100);

    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Nombre y email válido son requeridos.';
    } else {
        // El servicio solicitado, la empresa y el presupuesto se registran dentro
        // del mensaje del lead (sin tocar el esquema del CRM): quedan visibles en
        // el detalle del lead y en el timeline, y el origen va en `source`.
        $meta = [];
        if ($service !== '') $meta[] = 'Servicio de interés: ' . $service;
        if ($company !== '') $meta[] = 'Empresa: ' . $company;
        if ($budget  !== '') $meta[] = 'Presupuesto aproximado: ' . $budget;
        $composed = ($meta ? implode("\n", array_map(fn($m) => '• ' . $m, $meta)) . "\n\n" : '') . $userMsg;

        // Ventana de migración (R1): si el esquema multi-cuenta no corrió aún,
        // fallamos de forma honesta y visible en vez de simular un guardado.
        $accountId = leadsSchemaReady() ? accountInternalId() : null;
        if ($accountId === null) {
            error_log('index.php submit_lead: esquema multi-cuenta no disponible.');
            $error = 'No pudimos procesar tu solicitud en este momento. Probá de nuevo en unos minutos o escribinos por otro medio.';
        } else {
            $result = leadCreate([
                'name' => $name, 'email' => $email, 'phone' => $phone,
                'message' => $composed, 'source' => $source,
            ], $accountId);

            if (empty($result['ok'])) {
                $error = $result['error'] ?? 'No se pudo enviar el formulario.';
            } else {
                if (empty($result['duplicate']) && !empty($result['lead'])) {
                    $lead = $result['lead'];
                    try { notifyLeadCreated($lead); } catch (Throwable $e) { error_log('notifyLead: ' . $e->getMessage()); }
                    try { sendLeadAutoReply($lead); }  catch (Throwable $e) { error_log('autoReply: ' . $e->getMessage()); }
                }
                redirect($returnTo . '?sent=1');
            }
        }
    }
}

// ───────────────────────── Router GET ─────────────────────────
$path  = trim((string) parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/');
$parts = $path === '' ? [] : explode('/', $path);
$sent  = !empty($_GET['sent']);

// Home.
if ($path === '') {
    require __DIR__ . '/components/portal/home.php';
    exit;
}

// Rutas del portal. Sirven tanto en GET como tras un POST de submit_lead que
// falló validación (sin redirect): en ese caso se re-renderiza la misma página
// con $error visible. Los POST exitosos ya redirigieron arriba.

// Detalle de servicio: /servicios/{slug}
if ($parts[0] === 'servicios' && isset($parts[1])) {
    $service = portalServiceBySlug(slugify($parts[1]));
    if ($service) { require __DIR__ . '/components/portal/servicio.php'; exit; }
    portalRender404(); exit;
}
// Vistas del portal.
if ($path === 'servicios')  { require __DIR__ . '/components/portal/servicios.php'; exit; }
if ($path === 'proyectos')  { require __DIR__ . '/components/portal/proyectos.php';  exit; }
if ($path === 'proceso')    { require __DIR__ . '/components/portal/proceso.php';    exit; }
if ($path === 'cotizacion') { require __DIR__ . '/components/portal/cotizacion.php'; exit; }
if ($path === 'contacto')   { require __DIR__ . '/components/portal/contacto.php';   exit; }

// Página del CMS (sistema de Páginas existente).
$slug = slugify($path);
if ($slug === $path) {
    try {
        $stmt = getDB()->prepare('SELECT title, body, meta_description, og_image, hide_chrome FROM pages WHERE slug = ? AND is_published = 1');
        $stmt->execute([$slug]);
        $cms = $stmt->fetch();
    } catch (Throwable $e) {
        $cms = null;
    }
    if (!empty($cms)) {
        layoutStart([
            'title'        => (string) $cms['title'],
            'description'  => (string) ($cms['meta_description'] ?? ''),
            'og_image'     => (string) ($cms['og_image'] ?? ''),
            'current_slug' => $slug,
            'hide_chrome'  => !empty($cms['hide_chrome']),
        ]);
        ?>
<main class="container">
    <article class="page">
        <h1><?= htmlspecialchars($cms['title']) ?></h1>
        <?= $cms['body'] /* HTML confiado: solo lo edita el admin autenticado */ ?>
    </article>
</main>
<?php
        layoutEnd();
        exit;
    }
}

// Nada coincidió → 404.
portalRender404();
