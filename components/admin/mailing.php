<?php
/** Requiere: $settings (subset $MAILING_KEYS) */
$provider = ($settings['mail_provider'] ?? 'mail') === 'resend' ? 'resend' : 'mail';
$resendOk = $provider === 'resend' && trim((string) ($settings['resend_api_key'] ?? '')) !== '' && trim((string) ($settings['resend_from_email'] ?? '')) !== '';
?>
<header class="admin-header">
    <div>
        <h1>Mailing</h1>
        <div class="admin-header__sub">Cómo se envían los correos generados por leads y otras notificaciones.</div>
    </div>
</header>

<?php if ($msg = flashGet('mailing_success')): ?>
    <div class="auth-alert auth-alert--success"><span><?= htmlspecialchars($msg) ?></span></div>
<?php endif; ?>
<?php if ($msg = flashGet('mailing_error')): ?>
    <div class="auth-alert auth-alert--error"><span><?= htmlspecialchars($msg) ?></span></div>
<?php endif; ?>

<div class="card">
    <h3 class="card__title">Estado</h3>
    <p style="margin:0;">
        Proveedor activo:
        <strong><?= $provider === 'resend' ? 'Resend (API)' : 'PHP mail() nativo' ?></strong>
        <?php if ($provider === 'resend'): ?>
            <?php if ($resendOk): ?>
                · <span class="badge badge--qualified">listo para enviar</span>
            <?php else: ?>
                · <span class="badge badge--closed">incompleto: falta API key o From</span>
            <?php endif; ?>
        <?php else: ?>
            · <span class="text-muted" style="font-size:.85rem;">apto para hosting compartido, sin auth.</span>
        <?php endif; ?>
    </p>
</div>

<form method="post">
    <input type="hidden" name="action" value="save_mailing">
    <input type="hidden" name="csrf" value="<?= csrfToken() ?>">

    <div class="card">
        <h3 class="card__title">Proveedor</h3>
        <p class="form__field">
            <label style="display:flex;gap:.5rem;align-items:center;">
                <input type="radio" name="m[mail_provider]" value="mail" <?= $provider === 'mail' ? 'checked' : '' ?>>
                <span><strong>PHP mail()</strong> — usa el MTA del servidor (Hostinger / cPanel).</span>
            </label>
        </p>
        <p class="form__field" style="margin:0;">
            <label style="display:flex;gap:.5rem;align-items:center;">
                <input type="radio" name="m[mail_provider]" value="resend" <?= $provider === 'resend' ? 'checked' : '' ?>>
                <span><strong>Resend</strong> — API HTTPS, mejor deliverability (recomendado).</span>
            </label>
        </p>
    </div>

    <div class="card">
        <h3 class="card__title">Configuración de Resend</h3>
        <p class="text-muted" style="margin:0 0 1rem;font-size:.88rem;">
            Creá una API key en <a href="https://resend.com/api-keys" target="_blank" rel="noopener">resend.com/api-keys</a> y verificá tu dominio en
            <a href="https://resend.com/domains" target="_blank" rel="noopener">Domains</a>. El From debe ser de un dominio verificado.
        </p>
        <p class="form__field"><label>API Key
            <input type="text" name="m[resend_api_key]" value="<?= htmlspecialchars((string) ($settings['resend_api_key'] ?? '')) ?>" placeholder="re_xxxxxxxxxxxxxxxxxx" autocomplete="off">
        </label></p>
        <p class="form__field"><label>From — nombre
            <input type="text" name="m[resend_from_name]" value="<?= htmlspecialchars((string) ($settings['resend_from_name'] ?? '')) ?>" placeholder="<?= htmlspecialchars(getSetting('site_name', 'Mi Sitio')) ?>">
        </label></p>
        <p class="form__field"><label>From — email (dominio verificado)
            <input type="email" name="m[resend_from_email]" value="<?= htmlspecialchars((string) ($settings['resend_from_email'] ?? '')) ?>" placeholder="no-reply@tudominio.com">
        </label></p>
        <p class="form__field" style="margin:0;"><label>Reply-To <span class="text-muted" style="font-weight:400;">(opcional)</span>
            <input type="email" name="m[resend_reply_to]" value="<?= htmlspecialchars((string) ($settings['resend_reply_to'] ?? '')) ?>" placeholder="ventas@tudominio.com">
        </label></p>
    </div>

    <div class="card">
        <h3 class="card__title">Email al recibir un lead</h3>
        <p class="text-muted" style="margin:0 0 1rem;font-size:.88rem;">
            Variables disponibles:
            <code>{{name}}</code> <code>{{email}}</code> <code>{{phone}}</code>
            <code>{{message}}</code> <code>{{source}}</code> <code>{{created_at}}</code>
            <code>{{admin_url}}</code> <code>{{site_name}}</code>
        </p>
        <p class="form__field"><label>Destinatario (email interno que recibe los leads)
            <input type="email" name="m[notification_email]" value="<?= htmlspecialchars((string) ($settings['notification_email'] ?? '')) ?>" placeholder="leads@tudominio.com">
        </label></p>
        <p class="form__field"><label>Asunto
            <input type="text" name="m[notification_subject]" value="<?= htmlspecialchars((string) ($settings['notification_subject'] ?? '')) ?>">
        </label></p>
        <p class="form__field" style="margin:0;"><label>Cuerpo (texto plano)
            <textarea name="m[notification_body]" rows="9"><?= htmlspecialchars((string) ($settings['notification_body'] ?? '')) ?></textarea>
        </label></p>
    </div>

    <div class="card">
        <h3 class="card__title">Auto-respuesta al lead</h3>
        <p class="form__field">
            <label style="display:flex;align-items:center;gap:.5rem;">
                <input type="checkbox" name="m[autoreply_enabled]" value="1" style="width:auto;" <?= ($settings['autoreply_enabled'] ?? '0') === '1' ? 'checked' : '' ?>>
                <span>Enviar auto-respuesta al lead cuando completa el formulario</span>
            </label>
        </p>
        <p class="form__field"><label>Asunto
            <input type="text" name="m[autoreply_subject]" value="<?= htmlspecialchars((string) ($settings['autoreply_subject'] ?? '')) ?>">
        </label></p>
        <p class="form__field"><label>Cuerpo — texto plano
            <textarea name="m[autoreply_body]" rows="5"><?= htmlspecialchars((string) ($settings['autoreply_body'] ?? '')) ?></textarea>
        </label></p>
        <p class="form__field" style="margin:0;"><label>Cuerpo — HTML <span class="text-muted" style="font-weight:400;">(opcional, mejor render en clientes)</span>
            <textarea name="m[autoreply_body_html]" rows="6" placeholder="<p>Hola {{name}}, gracias por escribirnos.</p>"><?= htmlspecialchars((string) ($settings['autoreply_body_html'] ?? '')) ?></textarea>
        </label></p>
    </div>

    <p style="margin-top:1.5rem;"><button type="submit" class="btn">Guardar configuración</button></p>
</form>

<div class="card">
    <h3 class="card__title">Enviar correo de prueba</h3>
    <p class="text-muted" style="margin:0 0 1rem;font-size:.88rem;">
        Usa las plantillas actuales con un lead ficticio para verificar la conexión con el proveedor y el formato.
    </p>
    <form method="post" style="display:flex;gap:.5rem;align-items:stretch;flex-wrap:wrap;">
        <input type="hidden" name="action" value="send_test_email">
        <input type="hidden" name="csrf" value="<?= csrfToken() ?>">
        <input type="email" name="to" value="<?= htmlspecialchars((string) ($settings['notification_email'] ?? '')) ?>" placeholder="destino@dominio.com" style="flex:1;min-width:240px;">
        <button type="submit" class="btn">Enviar prueba</button>
    </form>
</div>
