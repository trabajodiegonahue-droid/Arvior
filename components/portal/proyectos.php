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
        <h1 class="page-hero__title">Proyectos pensados para generar negocio.</h1>
        <p class="page-hero__lead">Ejemplos representativos del tipo de proyectos que construimos en distintos rubros, con la capacidad que entrega cada uno. Sumamos casos de clientes con resultados verificados a medida que se publican.</p>
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
