<?php
/*
 * Lead form (público)
 * ------------------------------------------------------------
 * Form de contacto con honeypot + timing check + CSRF.
 * El handler POST vive en /index.php (action = 'submit_lead').
 *
 * Uso:
 *   require __DIR__ . '/components/lead_form.php';
 *
 * Attribution:
 *   Setear $leadSource antes del require para distinguir origen
 *   (ej: 'facebook_ad', 'google_campaign_2026'). Default: 'website'.
 * ------------------------------------------------------------
 */
$leadSource = $leadSource ?? 'website';
?>
<form method="post" class="lead-form">
    <input type="hidden" name="action" value="submit_lead">
    <input type="hidden" name="csrf" value="<?= csrfToken() ?>">
    <input type="hidden" name="form_started" value="<?= time() ?>">
    <input type="hidden" name="source" value="<?= htmlspecialchars($leadSource) ?>">
    <input type="text" name="website" value="" style="display:none" tabindex="-1" autocomplete="off">

    <p class="lead-form__field">
        <label>Name <input name="name" required placeholder="Your name"></label>
    </p>
    <p class="lead-form__field">
        <label>Email <input name="email" type="email" required placeholder="you@company.com"></label>
    </p>
    <p class="lead-form__field">
        <label>Phone <input name="phone" placeholder="Optional"></label>
    </p>
    <p class="lead-form__field">
        <label>Message <textarea name="message" rows="4" placeholder="Tell us about your project"></textarea></label>
    </p>
    <p class="lead-form__submit">
        <button type="submit" class="btn">Send message</button>
    </p>
</form>
