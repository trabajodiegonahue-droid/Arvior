<?php
/** Contacto: formulario + WhatsApp + correo + datos. Render desde index.php
 *  (scope: $error, $sent). Reutiliza settings del negocio. */
layoutStart([
    'current_slug' => 'contacto',
    'title'        => 'Contacto',
    'description'  => 'Habla con KOVA. Escríbenos por formulario, WhatsApp o correo y te respondemos a la brevedad.',
]);

$bizPhone   = trim((string) getSetting('business_phone', ''));
$bizEmail   = trim((string) getSetting('business_email', ''));
$bizAddress = trim((string) getSetting('business_address', ''));
$bizCity    = trim((string) getSetting('business_city', ''));
$bizHours   = trim((string) getSetting('business_hours', ''));
$mapsUrl    = trim((string) getSetting('business_maps_url', ''));
$waLink     = whatsappLink((string) getSetting('business_whatsapp', ''), (string) getSetting('business_whatsapp_text', ''));
?>
<section class="page-hero">
    <div class="container">
        <span class="section__eyebrow">Contacto</span>
        <h1 class="page-hero__title">Hablemos de tu proyecto.</h1>
        <p class="page-hero__lead">Escríbenos por donde prefieras. Respondemos rápido.</p>
    </div>
</section>

<section class="section section--tight">
    <div class="container">
        <div class="quote-layout reveal">
            <div class="quote-aside">
                <?php if ($waLink): ?>
                <a href="<?= htmlspecialchars($waLink) ?>" target="_blank" rel="noopener" class="contact-wa">
                    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20 3.5A11 11 0 0 0 3.6 18.2L2 22l3.9-1.6A11 11 0 1 0 20 3.5zm-8 18a9 9 0 0 1-4.6-1.3l-.3-.2-2.3 1 .8-2.3-.2-.3a9 9 0 1 1 6.6 3zm5-7c-.3-.1-1.7-.8-1.9-.9-.3-.1-.4-.1-.6.1-.2.2-.7.9-.9 1.1-.2.2-.3.2-.6.1-.3-.1-1.2-.4-2.3-1.4a8.6 8.6 0 0 1-1.5-2c-.2-.3 0-.4.1-.6.1-.1.3-.4.4-.5.1-.2.2-.3.3-.5 0-.2 0-.4 0-.5 0-.1-.6-1.4-.8-2-.2-.5-.4-.4-.6-.4h-.5c-.2 0-.5.1-.7.3-.3.3-.9.9-.9 2.2 0 1.3.9 2.6 1 2.8.1.2 1.8 2.8 4.5 3.9a15 15 0 0 0 1.6.6c.7.2 1.3.2 1.7.1.5-.1 1.7-.7 2-1.4.2-.6.2-1.2.2-1.4-.1-.1-.3-.2-.6-.3z"/></svg>
                    <span class="contact-wa__txt"><b>Escríbenos por WhatsApp</b><small>La vía más rápida — te respondemos enseguida</small></span>
                </a>
                <?php endif; ?>

                <div class="quote-aside__card">
                    <h2 class="section__title" style="font-size:1.2rem;">Otros canales</h2>
                    <ul class="contact-methods">
                        <?php if ($bizEmail): ?>
                            <li class="contact-method">
                                <span class="contact-method__ic"><?= portalIcon('mail') ?></span>
                                <span><span class="contact-method__lb">Correo</span><a href="mailto:<?= htmlspecialchars($bizEmail) ?>"><?= htmlspecialchars($bizEmail) ?></a></span>
                            </li>
                        <?php endif; ?>
                        <?php if ($bizPhone): ?>
                            <li class="contact-method">
                                <span class="contact-method__ic"><?= portalIcon('phone') ?></span>
                                <span><span class="contact-method__lb">Teléfono</span><a href="tel:<?= htmlspecialchars(preg_replace('/\s+/', '', $bizPhone)) ?>"><?= htmlspecialchars($bizPhone) ?></a></span>
                            </li>
                        <?php endif; ?>
                        <?php if ($bizAddress || $bizCity): ?>
                            <li class="contact-method">
                                <span class="contact-method__ic"><?= portalIcon('pin') ?></span>
                                <span><span class="contact-method__lb">Dirección</span>
                                    <?php if ($mapsUrl): ?><a href="<?= htmlspecialchars($mapsUrl) ?>" target="_blank" rel="noopener"><?php endif; ?>
                                    <?= htmlspecialchars(trim($bizAddress . ($bizCity ? ', ' . $bizCity : ''))) ?>
                                    <?php if ($mapsUrl): ?></a><?php endif; ?>
                                </span>
                            </li>
                        <?php endif; ?>
                        <?php if ($bizHours): ?>
                            <li class="contact-method">
                                <span class="contact-method__ic"><?= portalIcon('clock') ?></span>
                                <span><span class="contact-method__lb">Horario</span><span class="contact-method__val"><?= htmlspecialchars($bizHours) ?></span></span>
                            </li>
                        <?php endif; ?>
                        <?php if (!$bizEmail && !$bizPhone && !$bizAddress && !$bizCity && !$bizHours): ?>
                            <li class="text-muted">Completa el formulario y te respondemos a la brevedad.</li>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="quote-aside__card contact-quote">
                    <p><strong>¿Prefieres una propuesta con precio y fecha?</strong> Pide una cotización y te respondemos en menos de 24 h.</p>
                    <a href="/cotizacion" class="btn btn--secondary">Solicitar cotización</a>
                </div>
            </div>
            <div class="quote-layout__form">
                <h2 class="section__title">Envía un mensaje</h2>
                <?php
                $formSource  = 'contacto';
                $returnPath  = '/contacto';
                $showService = true; $showBudget = false; $showCompany = true;
                require __DIR__ . '/quote_form.php';
                ?>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/_reveal.php'; ?>
<?php layoutEnd(); ?>
