<?php
/** Proceso de trabajo. Render desde index.php. */
layoutStart([
    'current_slug' => 'proceso',
    'title'        => 'Proceso de trabajo',
    'description'  => 'Cómo trabajamos en ARVIOR: diagnóstico, propuesta con precio y fecha cerrados, diseño que apruebas, desarrollo, entrega y soporte. Un camino claro, sin sorpresas.',
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
                    <div class="timeline__body">
                        <span class="timeline__step">Etapa <?= htmlspecialchars($step['num']) ?></span>
                        <h3><?= htmlspecialchars($step['title']) ?></h3>
                        <p><?= htmlspecialchars($step['text']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="section__cta reveal">
            <a href="/cotizacion" class="btn">Empezar con un diagnóstico</a>
        </div>
    </div>
</section>

<?php require __DIR__ . '/_reveal.php'; ?>
<?php layoutEnd(); ?>
