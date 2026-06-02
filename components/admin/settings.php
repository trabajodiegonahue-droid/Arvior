<?php
/** Requiere: $settings (array key => value) */
?>
<header class="admin-header">
    <div>
        <h1>Configuración</h1>
        <div class="admin-header__sub">Ajustes generales del sitio, marca, notificaciones y tracking.</div>
    </div>
</header>

<?php if ($msg = flashGet('settings_success')): ?>
    <div class="auth-alert auth-alert--success"><span><?= htmlspecialchars($msg) ?></span></div>
<?php endif; ?>
<?php if ($msg = flashGet('settings_error')): ?>
    <div class="auth-alert auth-alert--error"><span><?= htmlspecialchars($msg) ?></span></div>
<?php endif; ?>

<form method="post">
    <input type="hidden" name="action" value="save_settings">
    <input type="hidden" name="csrf" value="<?= csrfToken() ?>">

    <div class="card">
        <h3 class="card__title">General</h3>
        <p class="form__field"><label>Nombre del sitio
            <input name="s[site_name]" value="<?= htmlspecialchars($settings['site_name'] ?? '') ?>" required>
        </label></p>
        <p class="form__field" style="margin:0;"><label>Timezone
            <input name="s[timezone]" value="<?= htmlspecialchars($settings['timezone'] ?? 'America/Argentina/Buenos_Aires') ?>">
        </label></p>
    </div>

    <div class="card">
        <h3 class="card__title">Logo del header</h3>
        <p class="text-muted" style="margin:0 0 .8rem;font-size:.88rem;">Imagen que aparece en el sidebar del admin y se puede usar en el front. Idealmente PNG o SVG con fondo transparente.</p>
        <?php
            $sifName  = 's[logo_image]';
            $sifValue = (string) ($settings['logo_image'] ?? '');
            $sifLabel = '';
            $sifPlaceholder = '/uploads/brand/logo.png';
            require __DIR__ . '/_single_image_field.php';
        ?>
    </div>

    <div class="card">
        <h3 class="card__title">Notificaciones de leads</h3>
        <p class="form__field"><label>Email destino (recibe los leads)
            <input type="email" name="s[notification_email]" value="<?= htmlspecialchars($settings['notification_email'] ?? '') ?>">
        </label></p>
        <p class="form__field"><label>From: (remitente — debe ser de tu dominio)
            <input type="email" name="s[notification_from]" value="<?= htmlspecialchars($settings['notification_from'] ?? '') ?>" placeholder="no-reply@tudominio.com">
        </label></p>

        <p class="form__field">
            <label style="display:flex;align-items:center;gap:.5rem;">
                <input type="checkbox" name="s[autoreply_enabled]" value="1" style="width:auto;" <?= ($settings['autoreply_enabled'] ?? '0') === '1' ? 'checked' : '' ?>>
                <span>Enviar auto-respuesta al lead</span>
            </label>
        </p>
        <p class="form__field"><label>Asunto auto-respuesta
            <input name="s[autoreply_subject]" value="<?= htmlspecialchars($settings['autoreply_subject'] ?? '') ?>">
        </label></p>
        <p class="form__field" style="margin:0;"><label>Cuerpo auto-respuesta (variables: <code>{{name}}</code>, <code>{{email}}</code>)
            <textarea name="s[autoreply_body]" rows="5"><?= htmlspecialchars($settings['autoreply_body'] ?? '') ?></textarea>
        </label></p>
    </div>

    <div class="card">
        <h3 class="card__title">Tracking</h3>
        <p class="form__field"><label>Google Analytics ID (G-XXXXXXX)
            <input name="s[ga_id]" value="<?= htmlspecialchars($settings['ga_id'] ?? '') ?>">
        </label></p>
        <p class="form__field" style="margin:0;"><label>Facebook Pixel ID
            <input name="s[pixel_id]" value="<?= htmlspecialchars($settings['pixel_id'] ?? '') ?>">
        </label></p>
    </div>

    <p style="margin-top:1.5rem;"><button type="submit" class="btn">Guardar cambios</button></p>
</form>

<?php
$faviconPath   = trim((string) ($settings['favicon_image'] ?? ''));
$faviconAbs    = $faviconPath ? (__DIR__ . '/../..' . $faviconPath) : '';
$faviconExists = $faviconPath !== '' && @file_exists($faviconAbs);
$faviconHref   = $faviconExists ? ($faviconPath . '?v=' . @filemtime($faviconAbs)) : '';
?>
<div class="card">
    <h3 class="card__title">Favicon</h3>
    <p class="text-muted" style="margin:0 0 1rem;font-size:.9rem;">El ícono que aparece en la pestaña del navegador. PNG, SVG o ICO (recomendado: PNG cuadrado 512×512 o SVG).</p>
    <div style="display:flex;gap:1.2rem;align-items:center;flex-wrap:wrap;">
        <div style="width:64px;height:64px;border:1px solid #e5e7eb;background:#f9fafb;display:flex;align-items:center;justify-content:center;flex-shrink:0;border-radius:6px;">
            <?php if ($faviconExists): ?>
                <img src="<?= htmlspecialchars($faviconHref) ?>" alt="" style="max-width:100%;max-height:100%;object-fit:contain;">
            <?php else: ?>
                <span class="text-muted" style="font-size:.7rem;">sin favicon</span>
            <?php endif; ?>
        </div>
        <form method="post" enctype="multipart/form-data" style="display:flex;gap:.6rem;align-items:center;flex-wrap:wrap;margin:0;">
            <input type="hidden" name="action" value="favicon_upload">
            <input type="hidden" name="csrf" value="<?= csrfToken() ?>">
            <input type="file" name="favicon" accept="image/png,image/svg+xml,image/x-icon,.png,.svg,.ico" required>
            <button type="submit" class="btn">Subir favicon</button>
        </form>
        <?php if ($faviconExists): ?>
            <form method="post" style="margin:0;" onsubmit="return confirm('¿Eliminar el favicon actual?');">
                <input type="hidden" name="action" value="favicon_remove">
                <input type="hidden" name="csrf" value="<?= csrfToken() ?>">
                <button type="submit" class="btn btn--ghost">Eliminar</button>
            </form>
        <?php endif; ?>
    </div>
    <?php if ($faviconExists): ?>
        <p class="text-muted" style="margin:.9rem 0 0;font-size:.82rem;">Archivo actual: <code><?= htmlspecialchars($faviconPath) ?></code></p>
    <?php endif; ?>
</div>
