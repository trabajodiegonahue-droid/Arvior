<?php
/** Proceso de trabajo. Render desde index.php. */
layoutStart([
    'current_slug' => 'proceso',
    'title'        => 'Proceso de trabajo',
    'description'  => 'Cómo trabajamos en KOVA: diagnóstico, propuesta con precio y fecha cerrados, diseño que apruebas, desarrollo, entrega y soporte. Un camino claro, sin sorpresas.',
]);
?>
<section class="page-hero">
    <div class="container">
        <span class="section__eyebrow">Proceso de trabajo</span>
        <h1 class="page-hero__title">De la idea al resultado, sin sorpresas.</h1>
        <p class="page-hero__lead">Cinco etapas. En cada una sabes qué hacemos y qué sigue.</p>
    </div>
</section>

<section class="section section--tight">
    <div class="container">
        <div class="timeline reveal">
            <?php foreach (portalProcess() as $step): ?>
                <div class="timeline__item">
                    <div class="timeline__num"><?= portalIcon($step['icon']) ?></div>
                    <div class="timeline__card">
                        <span class="timeline__step">Etapa <?= htmlspecialchars($step['num']) ?></span>
                        <h3><?= htmlspecialchars($step['title']) ?></h3>
                        <p><?= htmlspecialchars($step['text']) ?></p>
                        <span class="timeline__deliv"><?= portalIcon('check') ?> Recibes: <?= htmlspecialchars($step['deliverable']) ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ================= CTA (banda oscura) ================= -->
<section class="section section--tight">
    <div class="container">
        <div class="cta reveal">
            <h2 class="cta__title">¿Empezamos con un <span class="text-gradient">diagnóstico</span>?</h2>
            <p class="cta__sub">Sin compromiso. Te respondemos en menos de 24 horas hábiles.</p>
            <div class="hero__actions" style="justify-content:center;">
                <a href="/cotizacion" class="btn btn--arrow">Solicitar cotización</a>
                <a href="/contacto" class="btn btn--secondary">Hablar con nosotros</a>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/_reveal.php'; ?>
<?php layoutEnd(); ?>
