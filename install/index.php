<?php

$configPath  = __DIR__ . '/../config.php';
$installLock = __DIR__ . '/installed.lock';

if (file_exists($installLock) || file_exists($configPath)) {
    http_response_code(403);
    exit('Instalación bloqueada. Elimina /install/ del servidor.');
}

// ─── Pre-flight checks ────────────────────────────────────────────────
$rootDir    = realpath(__DIR__ . '/..');
$uploadsDir = $rootDir . '/uploads';

$checks = [
    [
        'label'  => 'PHP ≥ 8.0',
        'ok'     => version_compare(PHP_VERSION, '8.0.0', '>='),
        'detail' => 'Versión detectada: PHP ' . PHP_VERSION,
    ],
    [
        'label'  => 'PDO MySQL',
        'ok'     => extension_loaded('pdo_mysql'),
        'detail' => extension_loaded('pdo_mysql')
            ? 'Driver disponible para conectar a MySQL/MariaDB.'
            : 'Instala la extensión php-pdo-mysql en tu hosting.',
    ],
    [
        'label'  => 'Extensión GD',
        'ok'     => extension_loaded('gd'),
        'detail' => extension_loaded('gd')
            ? ('Procesamiento de imágenes activo' . (function_exists('imagewebp') ? ' (con soporte WebP).' : ', pero sin WebP — las imágenes se guardarán como JPG.'))
            : 'Sin GD no podrás subir imágenes desde el panel.',
    ],
    [
        'label'  => 'Extensión finfo',
        'ok'     => class_exists('finfo'),
        'detail' => class_exists('finfo')
            ? 'Validación MIME de archivos subidos activa.'
            : 'Necesaria para validar uploads de forma segura.',
    ],
    [
        'label'  => 'Directorio /uploads/ escribible',
        'ok'     => is_dir($uploadsDir) && is_writable($uploadsDir),
        'detail' => is_dir($uploadsDir)
            ? (is_writable($uploadsDir)
                ? 'El directorio existe y tiene permisos correctos.'
                : 'Existe pero no es escribible. Aplica <code>chmod 755 uploads/</code>.')
            : 'No existe — crea la carpeta <code>/uploads/</code> en la raíz.',
    ],
];

$preflightOk = !in_array(false, array_column($checks, 'ok'), true);
$okCount     = count(array_filter($checks, fn($c) => $c['ok']));
$totalCount  = count($checks);

// ─── POST: instalar ───────────────────────────────────────────────────
$step  = 'form';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $preflightOk) {
    $siteName = trim($_POST['site_name'] ?? '');
    $host  = trim($_POST['db_host']     ?? '');
    $name  = trim($_POST['db_name']     ?? '');
    $user  = trim($_POST['db_user']     ?? '');
    $pass  = $_POST['db_pass']          ?? '';
    $email = trim($_POST['admin_email'] ?? '');
    $pw    = $_POST['admin_pw']         ?? '';

    if ($siteName === '') {
        $error = 'El nombre del sitio es requerido.';
    } elseif (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($pw) < 8) {
        $error = 'Email válido y contraseña (mín. 8 caracteres) son requeridos.';
    }

    if (!$error) {
        try {
            $pdo = new PDO(
                "mysql:host=$host;dbname=$name;charset=utf8mb4",
                $user, $pass,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $e) {
            error_log('Install DB error: ' . $e->getMessage());
            $error = 'No se pudo conectar a la base de datos. Revisa host, nombre, usuario y contraseña.';
        }
    }

    if (!$error) {
        foreach (['brand', 'library', 'library/general', 'library/marca'] as $sub) {
            $dir = $uploadsDir . '/' . $sub;
            if (!is_dir($dir) && !@mkdir($dir, 0755, true)) {
                $error = 'No se pudo crear ' . htmlspecialchars($dir) . '. Verifica los permisos.';
                break;
            }
            if (!is_writable($dir)) {
                $error = 'El directorio ' . htmlspecialchars($dir) . ' no es escribible.';
                break;
            }
        }
    }

    if (!$error) {
        $configContent = "<?php\n\n"
            . "define('DB_HOST', "     . var_export($host, true) . ");\n"
            . "define('DB_NAME', "     . var_export($name, true) . ");\n"
            . "define('DB_USER', "     . var_export($user, true) . ");\n"
            . "define('DB_PASS', "     . var_export($pass, true) . ");\n"
            . "define('DB_CHARSET', 'utf8mb4');\n\n"
            . "define('SITE_URL', (isset(\$_SERVER['HTTPS']) ? 'https://' : 'http://') . (\$_SERVER['HTTP_HOST'] ?? 'localhost'));\n"
            . "define('SESSION_LIFETIME', 7200);\n"
            . "define('APP_TIMEZONE', 'America/Argentina/Buenos_Aires');\n";

        if (file_put_contents($configPath, $configContent, LOCK_EX) === false) {
            $error = 'No se pudo escribir config.php. Revisa los permisos del directorio raíz.';
        }
    }

    if (!$error) {
        require $configPath;
        require __DIR__ . '/../lib/db.php';
        require __DIR__ . '/../lib/migrate.php';

        try {
            runMigrations();

            $hash = password_hash($pw, PASSWORD_BCRYPT, ['cost' => 12]);
            $stmt = getDB()->prepare('INSERT INTO users (email, password_hash, is_active) VALUES (?, ?, 1)');
            $stmt->execute([$email, $hash]);

            $defaults = [
                'site_name'          => $siteName,
                'notification_email' => $email,
                'logo_image'         => '',
                'favicon_image'      => '',
            ];
            $stmt = getDB()->prepare(
                'INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)
                 ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)'
            );
            foreach ($defaults as $k => $v) $stmt->execute([$k, $v]);

            $seedPages = [
                [
                    'slug'  => 'home',
                    'title' => 'Bienvenido a ' . $siteName,
                    'body'  => '<p>Esta es la página de inicio de ejemplo. Edítala desde <a href="/admin/?view=pages">Páginas</a> en el panel admin.</p>',
                    'meta'  => 'Sitio oficial de ' . $siteName . '.',
                ],
                [
                    'slug'  => 'sobre-nosotros',
                    'title' => 'Sobre nosotros',
                    'body'  => '<p>Cuéntanos quién eres y qué haces. Edita este texto desde el panel admin → Páginas.</p>',
                    'meta'  => 'Conoce más sobre ' . $siteName . '.',
                ],
                [
                    'slug'  => 'contacto',
                    'title' => 'Contacto',
                    'body'  => '<p>Escríbenos a <a href="mailto:' . htmlspecialchars($email) . '">' . htmlspecialchars($email) . '</a> o usa el formulario de la <a href="/">página principal</a>.</p>',
                    'meta'  => 'Contáctanos: ' . $email,
                ],
            ];
            $stmtPage = getDB()->prepare(
                'INSERT IGNORE INTO pages (slug, title, body, meta_description, is_published) VALUES (?, ?, ?, ?, 1)'
            );
            foreach ($seedPages as $p) $stmtPage->execute([$p['slug'], $p['title'], $p['body'], $p['meta']]);

            @file_put_contents($installLock, date('c'));
            @file_put_contents(__DIR__ . '/.htaccess', "Require all denied\n");

            $step = 'done';
        } catch (Throwable $e) {
            error_log('Install error: ' . $e->getMessage());
            @unlink($configPath);
            $error = 'Error durante la instalación. Revisa el log del servidor.';
        }
    }
}

$stepIdx = $step === 'done' ? 2 : 1;

// SVG helpers
$svgCheck = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>';
$svgCross = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>';
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Instalación · Configura tu sitio</title>
<meta name="robots" content="noindex,nofollow">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
<link rel="stylesheet" href="/assets/css/base.css">
<link rel="stylesheet" href="/assets/css/components.css">
<link rel="stylesheet" href="/assets/css/auth.css">
</head>
<body class="auth-body">

<div class="auth-card auth-card--wide">

    <?php if ($step === 'done'): ?>

        <div class="auth-done">
            <div class="auth-done__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <h1>Instalación completa</h1>
            <p class="auth-done__sub">Tu panel está listo. Antes de empezar a usarlo, asegúrate de eliminar la carpeta <code>/install/</code> de tu servidor.</p>

            <div class="auth-done__actions">
                <a class="btn" href="/admin/">Ir al panel admin →</a>
                <a class="btn btn--secondary" href="/" target="_blank">Ver el sitio</a>
            </div>

            <div class="auth-alert auth-alert--info">
                <span><strong>Bloqueo automático activado.</strong> Generamos <code>installed.lock</code> + <code>.htaccess</code> en <code>/install/</code>. Por seguridad, elimina toda la carpeta desde tu administrador de archivos cuando puedas.</span>
            </div>
        </div>

    <?php else: ?>

        <div class="install-header">
            <div class="install-header__logo">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2z"/></svg>
            </div>
            <h1>Configura tu sitio</h1>
            <p class="install-header__subtitle">En menos de un minuto tendrás el panel funcionando.<br>Tres pasos: revisamos requisitos, conectas la base y creas tu cuenta admin.</p>
        </div>

        <div class="auth-steps" role="list">
            <div class="auth-step <?= $stepIdx === 1 ? 'auth-step--active' : '' ?>" role="listitem">
                <span class="auth-step__dot"><?= $stepIdx > 1 ? '✓' : '1' ?></span>
                <span>Configuración</span>
            </div>
            <div class="auth-step__sep"></div>
            <div class="auth-step <?= $stepIdx === 2 ? 'auth-step--active' : '' ?>" role="listitem">
                <span class="auth-step__dot">2</span>
                <span>Finalizar</span>
            </div>
        </div>

        <div class="preflight">
            <div class="preflight__head">
                <h3 class="preflight__title">Requisitos del servidor</h3>
                <span class="preflight__status <?= $preflightOk ? 'preflight__status--ok' : 'preflight__status--bad' ?>">
                    <span class="dot"></span>
                    <?= $preflightOk ? 'Todo en orden · ' . $okCount . '/' . $totalCount : 'Faltan ' . ($totalCount - $okCount) . ' de ' . $totalCount ?>
                </span>
            </div>
            <ul class="preflight__list">
                <?php foreach ($checks as $c): ?>
                    <li class="pf-item <?= $c['ok'] ? 'pf-item--ok' : 'pf-item--bad' ?>">
                        <span class="pf-item__icon"><?= $c['ok'] ? $svgCheck : $svgCross ?></span>
                        <div>
                            <div class="pf-item__label"><?= htmlspecialchars($c['label']) ?></div>
                            <div class="pf-item__detail"><?= $c['detail'] /* HTML controlado: solo contiene <code> */ ?></div>
                        </div>
                        <span class="pf-item__badge"><?= $c['ok'] ? 'OK' : 'Falta' ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <?php if (!$preflightOk): ?>
            <div class="auth-alert auth-alert--error">
                <span>Resuelve los requisitos marcados arriba antes de continuar. Si no puedes instalar una extensión por tu cuenta, contacta al soporte de tu hosting.</span>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="auth-alert auth-alert--error">
                <span><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endif; ?>

        <form method="post" autocomplete="off" novalidate class="<?= $preflightOk ? '' : 'install-form--disabled' ?>">

            <?php
            // Íconos para prefijar inputs
            $icoSite   = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>';
            $icoServer = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="7" rx="2"/><rect x="2" y="14" width="20" height="7" rx="2"/><line x1="6" y1="6.5" x2="6.01" y2="6.5"/><line x1="6" y1="17.5" x2="6.01" y2="17.5"/></svg>';
            $icoDb     = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5v6c0 1.66 4.03 3 9 3s9-1.34 9-3V5"/><path d="M3 11v6c0 1.66 4.03 3 9 3s9-1.34 9-3v-6"/></svg>';
            $icoUser   = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>';
            $icoLock   = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>';
            $icoMail   = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>';
            $icoEye    = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>';
            $icoEyeOff = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>';
            ?>

            <section class="install-section">
                <header class="install-section__head">
                    <span class="install-section__num">1</span>
                    <h3 class="install-section__title">Sitio</h3>
                    <small class="install-section__sub">Identidad pública</small>
                </header>
                <div class="auth-field">
                    <label for="site_name">Nombre del sitio</label>
                    <div class="auth-field__control auth-field__control--icon">
                        <span class="auth-field__icon"><?= $icoSite ?></span>
                        <input id="site_name" name="site_name" value="<?= htmlspecialchars($_POST['site_name'] ?? '') ?>" required placeholder="Mi Sitio">
                    </div>
                    <small class="auth-field__hint">Aparece en el header, los emails y el título del navegador. Se puede cambiar después.</small>
                </div>
            </section>

            <section class="install-section">
                <header class="install-section__head">
                    <span class="install-section__num">2</span>
                    <h3 class="install-section__title">Base de datos</h3>
                    <small class="install-section__sub">MySQL / MariaDB</small>
                </header>

                <div class="auth-grid-2">
                    <div class="auth-field">
                        <label for="db_host">Host</label>
                        <div class="auth-field__control auth-field__control--icon">
                            <span class="auth-field__icon"><?= $icoServer ?></span>
                            <input id="db_host" name="db_host" value="<?= htmlspecialchars($_POST['db_host'] ?? 'localhost') ?>" required>
                        </div>
                        <small class="auth-field__hint">En Hostinger / cPanel suele ser <code>localhost</code>.</small>
                    </div>
                    <div class="auth-field">
                        <label for="db_name">Nombre de la base</label>
                        <div class="auth-field__control auth-field__control--icon">
                            <span class="auth-field__icon"><?= $icoDb ?></span>
                            <input id="db_name" name="db_name" value="<?= htmlspecialchars($_POST['db_name'] ?? '') ?>" required placeholder="u123_misitio">
                        </div>
                    </div>
                </div>

                <div class="auth-grid-2">
                    <div class="auth-field">
                        <label for="db_user">Usuario</label>
                        <div class="auth-field__control auth-field__control--icon">
                            <span class="auth-field__icon"><?= $icoUser ?></span>
                            <input id="db_user" name="db_user" value="<?= htmlspecialchars($_POST['db_user'] ?? '') ?>" required placeholder="u123_admin">
                        </div>
                    </div>
                    <div class="auth-field auth-field--password">
                        <label for="db_pass">Contraseña</label>
                        <div class="auth-field__control auth-field__control--icon">
                            <span class="auth-field__icon"><?= $icoLock ?></span>
                            <input id="db_pass" name="db_pass" type="password" placeholder="••••••••">
                            <button type="button" class="auth-field__toggle" data-toggle="db_pass" aria-label="Mostrar contraseña">
                                <span class="auth-field__toggle-eye"><?= $icoEye ?></span>
                                <span class="auth-field__toggle-eye-off" hidden><?= $icoEyeOff ?></span>
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            <section class="install-section">
                <header class="install-section__head">
                    <span class="install-section__num">3</span>
                    <h3 class="install-section__title">Tu cuenta admin</h3>
                    <small class="install-section__sub">Acceso al panel</small>
                </header>

                <div class="auth-field">
                    <label for="admin_email">Email</label>
                    <div class="auth-field__control auth-field__control--icon">
                        <span class="auth-field__icon"><?= $icoMail ?></span>
                        <input id="admin_email" type="email" name="admin_email" value="<?= htmlspecialchars($_POST['admin_email'] ?? '') ?>" required placeholder="tu@dominio.com">
                    </div>
                    <small class="auth-field__hint">A este email llegarán los leads del formulario.</small>
                </div>

                <div class="auth-field auth-field--password">
                    <label for="admin_pw">Contraseña</label>
                    <div class="auth-field__control auth-field__control--icon">
                        <span class="auth-field__icon"><?= $icoLock ?></span>
                        <input id="admin_pw" name="admin_pw" type="password" required minlength="8" placeholder="Mínimo 8 caracteres">
                        <button type="button" class="auth-field__toggle" data-toggle="admin_pw" aria-label="Mostrar contraseña">
                            <span class="auth-field__toggle-eye"><?= $icoEye ?></span>
                            <span class="auth-field__toggle-eye-off" hidden><?= $icoEyeOff ?></span>
                        </button>
                    </div>
                    <small class="auth-field__hint">Guárdala en un lugar seguro. Se puede cambiar más tarde desde el panel.</small>
                </div>
            </section>

            <button type="submit" class="btn auth-submit" <?= $preflightOk ? '' : 'disabled' ?>>
                <span>Instalar y empezar</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left:.15rem;"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </button>
        </form>

    <?php endif; ?>

    <div class="auth-footer">
        <span>Instalador seguro · Se bloquea automáticamente al terminar</span>
    </div>

</div>

<script>
document.querySelectorAll('[data-toggle]').forEach(btn => {
    btn.addEventListener('click', () => {
        const input = document.getElementById(btn.dataset.toggle);
        if (!input) return;
        const show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        btn.setAttribute('aria-label', show ? 'Ocultar contraseña' : 'Mostrar contraseña');
        const eye    = btn.querySelector('.auth-field__toggle-eye');
        const eyeOff = btn.querySelector('.auth-field__toggle-eye-off');
        if (eye && eyeOff) {
            eye.hidden    = show;
            eyeOff.hidden = !show;
        }
    });
});

// Feedback "instalando..." al submitear
const form = document.querySelector('form.install-form--disabled, form:not(.install-form--disabled)');
if (form) {
    form.addEventListener('submit', () => {
        const btn = form.querySelector('button[type=submit]');
        if (btn && !btn.disabled) {
            btn.disabled = true;
            btn.innerHTML = '<span>Instalando…</span>';
        }
    });
}
</script>

</body>
</html>
