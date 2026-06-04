<?php
/** Contacto: formulario + WhatsApp + correo + datos. Render desde index.php
 *  (scope: $error, $sent). Reutiliza settings del negocio. */
layoutStart([
    'current_slug' => 'contacto',
    'title'        => 'Contacto',
    'description'  => 'Hablá con ARVIOR. Escribinos por formulario, WhatsApp o correo y te respondemos a la brevedad.',
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
        <p class="page-hero__lead">Escribinos por el medio que prefieras. Respondemos rápido.</p>
    </div>
</section>

<section class="section section--tight">
    <div class="container">
        <div class="quote-layout reveal">
            <div class="quote-layout__aside">
                <h2 class="section__title">Datos de contacto</h2>
                <ul class="contact-list">
                    <?php if ($waLink): ?>
                        <li><span class="contact-list__lb">WhatsApp</span><a href="<?= htmlspecialchars($waLink) ?>" target="_blank" rel="noopener">Escribir por WhatsApp</a></li>
                    <?php endif; ?>
                    <?php if ($bizEmail): ?>
                        <li><span class="contact-list__lb">Correo</span><a href="mailto:<?= htmlspecialchars($bizEmail) ?>"><?= htmlspecialchars($bizEmail) ?></a></li>
                    <?php endif; ?>
                    <?php if ($bizPhone): ?>
                        <li><span class="contact-list__lb">Teléfono</span><a href="tel:<?= htmlspecialchars(preg_replace('/\s+/', '', $bizPhone)) ?>"><?= htmlspecialchars($bizPhone) ?></a></li>
                    <?php endif; ?>
                    <?php if ($bizAddress || $bizCity): ?>
                        <li><span class="contact-list__lb">Dirección</span>
                            <?php if ($mapsUrl): ?><a href="<?= htmlspecialchars($mapsUrl) ?>" target="_blank" rel="noopener"><?php endif; ?>
                            <?= htmlspecialchars(trim($bizAddress . ($bizCity ? ', ' . $bizCity : ''))) ?>
                            <?php if ($mapsUrl): ?></a><?php endif; ?>
                        </li>
                    <?php endif; ?>
                    <?php if ($bizHours): ?>
                        <li><span class="contact-list__lb">Horario</span><?= htmlspecialchars($bizHours) ?></li>
                    <?php endif; ?>
                    <?php if (!$waLink && !$bizEmail && !$bizPhone && !$bizAddress && !$bizCity && !$bizHours): ?>
                        <li class="text-muted">Completá el formulario y te respondemos a la brevedad.</li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="quote-layout__form">
                <h2 class="section__title">Enviá un mensaje</h2>
                <?php
                $formSource = 'contacto';
                $returnPath = '/contacto';
                $showService = true; $showBudget = false; $showCompany = true;
                require __DIR__ . '/quote_form.php';
                ?>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/_reveal.php'; ?>
<?php layoutEnd(); ?>
