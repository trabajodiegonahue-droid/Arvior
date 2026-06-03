<?php
/** Página de cotización (formulario principal). Render desde index.php
 *  (scope: $error, $sent). */
layoutStart([
    'current_slug' => 'cotizacion',
    'title'        => 'Solicitar cotización',
    'description'  => 'Pedí una cotización para tu proyecto: desarrollo web, software, marketing digital, automatización o IA. Te respondemos con una propuesta clara.',
]);
?>
<section class="page-hero">
    <div class="container">
        <span class="section__eyebrow">Cotización</span>
        <h1 class="page-hero__title">Contanos tu proyecto.</h1>
        <p class="page-hero__lead">Completá el formulario y te enviamos una propuesta clara, con alcance y precio. Sin compromiso.</p>
    </div>
</section>

<section class="section section--tight">
    <div class="container">
        <div class="quote-layout reveal">
            <div class="quote-layout__aside">
                <h2 class="section__title">Qué pasa después</h2>
                <ul class="benefit-list">
                    <li><span class="benefit-list__ic"><?= portalIcon('check') ?></span>Recibimos tu solicitud y la revisamos.</li>
                    <li><span class="benefit-list__ic"><?= portalIcon('check') ?></span>Te contactamos para entender el detalle.</li>
                    <li><span class="benefit-list__ic"><?= portalIcon('check') ?></span>Te enviamos una propuesta con alcance y precio.</li>
                </ul>
                <p class="text-muted" style="margin-top:1.4rem;font-size:.92rem;">¿Preferís hablar directo? Visitá <a href="/contacto">Contacto</a>.</p>
            </div>
            <div class="quote-layout__form">
                <h2 class="section__title">Solicitud de cotización</h2>
                <?php
                $formSource = 'cotizacion';
                $returnPath = '/cotizacion';
                $showService = true; $showBudget = true; $showCompany = true;
                require __DIR__ . '/quote_form.php';
                ?>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/_reveal.php'; ?>
<?php layoutEnd(); ?>
