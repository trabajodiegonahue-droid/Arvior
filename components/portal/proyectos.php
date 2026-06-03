<?php
/** Proyectos / casos. Render desde index.php. (Contenido demo en lib/portal.php) */
layoutStart([
    'current_slug' => 'proyectos',
    'title'        => 'Proyectos',
    'description'  => 'Proyectos y casos de éxito de ARVIOR: desarrollo web, e-commerce, campañas, CRM, automatización e IA con resultados medibles.',
]);
?>
<section class="page-hero">
    <div class="container">
        <span class="section__eyebrow">Proyectos</span>
        <h1 class="page-hero__title">Casos donde la tecnología generó resultados.</h1>
        <p class="page-hero__lead">Una selección de proyectos que construimos para empresas de distintos rubros. Los datos son ilustrativos y se reemplazan por casos reales a medida que se publican.</p>
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
                    <span class="proj__result"><?= htmlspecialchars($p['result']) ?></span>
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
