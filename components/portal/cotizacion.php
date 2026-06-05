<?php
/** Página de cotización (formulario principal). Render desde index.php
 *  (scope: $error, $sent). */
layoutStart([
    'current_slug' => 'cotizacion',
    'title'        => 'Solicitar cotización',
    'description'  => 'Cuéntanos tu proyecto y te enviamos una propuesta clara con alcance, precio y fecha, en menos de 24 horas hábiles. Sin compromiso.',
]);
$waLink = whatsappLink((string) getSetting('business_whatsapp', ''), (string) getSetting('business_whatsapp_text', ''));
?>
<section class="page-hero">
    <div class="container">
        <span class="section__eyebrow">Cotización</span>
        <h1 class="page-hero__title">Cuéntanos tu proyecto.</h1>
        <p class="page-hero__lead">Completa el formulario y te enviamos una propuesta con alcance, precio y fecha. En menos de 24 h, sin compromiso.</p>
    </div>
</section>

<section class="section section--tight">
    <div class="container">
        <div class="quote-layout reveal">
            <div class="quote-aside">
                <div class="quote-aside__card">
                    <h2 class="section__title" style="font-size:1.25rem;">Qué pasa después</h2>
                    <ol class="quote-steps">
                        <li><span><strong>Revisamos tu solicitud</strong><span>Leemos lo que nos cuentas con calma.</span></span></li>
                        <li><span><strong>Conversamos contigo</strong><span>Un breve contacto para entender el detalle.</span></span></li>
                        <li><span><strong>Te enviamos la propuesta</strong><span>Con alcance, precio y fecha cerrados.</span></span></li>
                    </ol>
                </div>
                <div class="quote-aside__card">
                    <ul class="assure">
                        <li><span class="assure__ic"><?= portalIcon('clock') ?></span><span>Respuesta en <strong>menos de 24 h</strong></span></li>
                        <li><span class="assure__ic"><?= portalIcon('check') ?></span><span>Precio y fecha <strong>cerrados</strong></span></li>
                        <li><span class="assure__ic"><?= portalIcon('phone') ?></span><span>Atención directa, sin intermediarios</span></li>
                        <li><span class="assure__ic"><?= portalIcon('shield') ?></span><span>Sin compromiso ni letra chica</span></li>
                    </ul>
                    <p class="quote-scarcity"><span class="dot"></span> Cupos limitados: tomamos pocos proyectos al mes.</p>
                    <?php if ($waLink): ?>
                        <a href="<?= htmlspecialchars($waLink) ?>" target="_blank" rel="noopener" class="quote-wa">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true"><path d="M20 3.5A11 11 0 0 0 3.6 18.2L2 22l3.9-1.6A11 11 0 1 0 20 3.5zm-8 18a9 9 0 0 1-4.6-1.3l-.3-.2-2.3 1 .8-2.3-.2-.3a9 9 0 1 1 6.6 3zm5-7c-.3-.1-1.7-.8-1.9-.9-.3-.1-.4-.1-.6.1-.2.2-.7.9-.9 1.1-.2.2-.3.2-.6.1-.3-.1-1.2-.4-2.3-1.4a8.6 8.6 0 0 1-1.5-2c-.2-.3 0-.4.1-.6.1-.1.3-.4.4-.5.1-.2.2-.3.3-.5 0-.2 0-.4 0-.5 0-.1-.6-1.4-.8-2-.2-.5-.4-.4-.6-.4h-.5c-.2 0-.5.1-.7.3-.3.3-.9.9-.9 2.2 0 1.3.9 2.6 1 2.8.1.2 1.8 2.8 4.5 3.9a15 15 0 0 0 1.6.6c.7.2 1.3.2 1.7.1.5-.1 1.7-.7 2-1.4.2-.6.2-1.2.2-1.4-.1-.1-.3-.2-.6-.3z"/></svg>
                            ¿Prefieres WhatsApp? Escríbenos
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="quote-layout__form quote-layout__form--accent">
                <h2 class="section__title">Solicitud de cotización</h2>
                <?php
                $formSource  = 'cotizacion';
                $returnPath  = '/cotizacion';
                $showService = true; $showBudget = true; $showCompany = true;
                // Preselección del servicio desde el selector "¿Qué necesitas?" (?servicio=).
                $reqSvc = trim((string) ($_GET['servicio'] ?? ''));
                $preselectService = in_array($reqSvc, portalServiceOptions(), true) ? $reqSvc : '';
                require __DIR__ . '/quote_form.php';
                ?>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/_reveal.php'; ?>
<?php layoutEnd(); ?>
