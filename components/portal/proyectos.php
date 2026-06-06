<?php
/** Tipos de proyecto que construimos. Render desde index.php.
 *  No son casos de clientes con métricas reales (ver lib/portal.php): describen
 *  la capacidad que entrega cada tipo de proyecto, sin estadísticas inventadas. */
layoutStart([
    'current_slug' => 'proyectos',
    'title'        => 'Proyectos',
    'description'  => 'Los tipos de proyecto que construimos en KOVA: sitios corporativos, landing pages y tiendas online, con lo que entrega cada uno.',
]);
?>
<section class="page-hero">
    <div class="container">
        <span class="section__eyebrow">Proyectos</span>
        <h1 class="page-hero__title">El tipo de proyectos que construimos.</h1>
        <p class="page-hero__lead">Ejemplos representativos por tipo de proyecto, con lo que entrega cada uno. Sumamos casos reales de clientes con resultados verificados a medida que se publican: preferimos no inventar números.</p>
    </div>
</section>

<section class="section section--tight">
    <div class="container">
        <div class="portfolio portfolio--lg reveal">
            <?php foreach (portalProjects() as $p): ?>
                <article class="proj">
                    <span class="proj__tag"><?= htmlspecialchars($p['tag']) ?></span>
                    <h3 class="proj__title"><?= htmlspecialchars($p['title']) ?></h3>
                    <p class="proj__text"><?= htmlspecialchars($p['text']) ?></p>
                    <span class="proj__result"><?= portalIcon('check') ?> <?= htmlspecialchars($p['result']) ?></span>
                </article>
            <?php endforeach; ?>
        </div>
        <div class="section__cta reveal">
            <a href="/cotizacion" class="btn">Quiero un proyecto así</a>
        </div>
    </div>
</section>

<?php require __DIR__ . '/_reveal.php'; ?>
<?php layoutEnd(); ?>
