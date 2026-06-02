<?php

require __DIR__ . '/lib/bootstrap.php';

// Router mínimo: si el path no es "/" y coincide con un slug publicado, renderizar esa página.
$path = trim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/');
if ($path !== '' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $slug = slugify($path);
    if ($slug === $path) {
        try {
            $stmt = getDB()->prepare('SELECT title, body, meta_description, og_image, hide_chrome FROM pages WHERE slug = ? AND is_published = 1');
            $stmt->execute([$slug]);
            $cms = $stmt->fetch();
        } catch (Throwable $e) {
            $cms = null; // tabla todavía no migrada (instalación vieja sin admin visita)
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
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'submit_lead') {

    // Anti-spam: honeypot + timing. Fake success (los bots no se enteran).
    $honeypotTripped = !empty($_POST['website']);
    $tooFast         = !isset($_POST['form_started']) || (time() - (int) $_POST['form_started']) < 2;

    if ($honeypotTripped || $tooFast) {
        redirect('/?sent=1');
    }

    csrfCheck();
    $name    = trim($_POST['name']    ?? '');
    $email   = trim($_POST['email']   ?? '');
    $phone   = trim($_POST['phone']   ?? '');
    $message = trim($_POST['message'] ?? '');
    $source  = trim($_POST['source']  ?? 'website');

    if (!$name || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Nombre y email válido son requeridos.';
    } else {
        // Duplicate check: mismo email en los últimos 5 minutos → fake success.
        $dupe = getDB()->prepare(
            'SELECT COUNT(*) FROM leads
             WHERE email = ? AND created_at > DATE_SUB(NOW(), INTERVAL 5 MINUTE)'
        );
        $dupe->execute([$email]);

        if ((int) $dupe->fetchColumn() > 0) {
            redirect('/?sent=1');
        }

        $db = getDB();
        $stmt = $db->prepare(
            'INSERT INTO leads (name, email, phone, message, source, status, ip_address, user_agent)
             VALUES (?, ?, ?, ?, ?, "new", ?, ?)'
        );
        $stmt->execute([
            $name, $email, $phone, $message, $source,
            clientIp(),
            substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 500),
        ]);

        $lead = [
            'id'      => (int) $db->lastInsertId(),
            'name'    => $name,
            'email'   => $email,
            'phone'   => $phone,
            'message' => $message,
            'source'  => $source,
        ];

        // Notificaciones: no deben romper el flujo si fallan.
        try { notifyLeadCreated($lead); }   catch (Throwable $e) { error_log('notifyLead: ' . $e->getMessage()); }
        try { sendLeadAutoReply($lead); }   catch (Throwable $e) { error_log('autoReply: ' . $e->getMessage()); }

        redirect('/gracias');
    }
}

layoutStart(['current_slug' => '']);
?>
<main class="container">

    <?php if ($error): ?>
        <p class="alert alert--error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <h2>Contacto</h2>
    <?php require __DIR__ . '/components/lead_form.php'; ?>

</main>
<?php layoutEnd(); ?>

