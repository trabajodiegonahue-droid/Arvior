<?php
/** Página de cotización (formulario principal). Render desde index.php
 *  (scope: $error, $sent). */
layoutStart([
    'current_slug' => 'cotizacion',
    'title'        => 'Solicitar cotización',
    'description'  => 'Cuéntanos tu proyecto y te enviamos una propuesta clara con alcance, precio y fecha, en menos de 24 horas hábiles. Sin compromiso.',
]);
?>
<section class="page-hero">
    <div class="container">
        <span class="section__eyebrow">Cotización</span>
        <h1 class="page-hero__title">Cuéntanos tu proyecto.</h1>
        <p class="page-hero__lead">Completa el formulario y te enviamos una propuesta clara, con alcance, precio y fecha cerrados. Te respondemos en menos de 24 horas hábiles, sin compromiso.</p>
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
                    <ul class="benefit-list">
                        <li><span class="benefit-list__ic"><?= portalIcon('check') ?></span>Respuesta en menos de 24 horas hábiles</li>
                        <li><span class="benefit-list__ic"><?= portalIcon('check') ?></span>Sin compromiso ni letra chica</li>
                        <li><span class="benefit-list__ic"><?= portalIcon('check') ?></span>Atención directa con quien construye</li>
                    </ul>
                    <p class="text-muted" style="margin-top:1.1rem;font-size:.92rem;">¿Prefieres hablar directo? Visita <a href="/contacto">Contacto</a>.</p>
                </div>
            </div>
            <div class="quote-layout__form">
                <h2 class="section__title">Solicitud de cotización</h2>
                <?php
                $formSource  = 'cotizacion';
                $returnPath  = '/cotizacion';
                $showService = true; $showBudget = true; $showCompany = true;
                require __DIR__ . '/quote_form.php';
                ?>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/_reveal.php'; ?>
<?php layoutEnd(); ?>
