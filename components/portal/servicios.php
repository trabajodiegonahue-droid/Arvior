<?php
/** Listado de servicios. Render desde index.php. */
layoutStart([
    'current_slug' => 'servicios',
    'title'        => 'Servicios',
    'description'  => 'Desarrollo web, landing pages, tiendas online, Google y Meta Ads, SEO, automatización, CRM, software e inteligencia artificial. Conocé todos los servicios de ARVIOR.',
]);
?>
<section class="page-hero">
    <div class="container">
        <span class="section__eyebrow">Servicios</span>
        <h1 class="page-hero__title">Soluciones para captar y convertir más clientes.</h1>
        <p class="page-hero__lead">Cada servicio se diseña con un objetivo comercial concreto y se conecta a tu sistema de ventas. Elegí por dónde empezar.</p>
    </div>
</section>

<section class="section section--tight">
    <div class="container">
        <div class="card-grid reveal">
            <?php foreach (portalServices() as $s): ?>
                <a class="s-card" href="/servicios/<?= htmlspecialchars($s['slug']) ?>">
                    <div class="s-card__icon"><?= portalIcon($s['icon']) ?></div>
                    <h3 class="s-card__title"><?= htmlspecialchars($s['title']) ?></h3>
                    <p class="s-card__text"><?= htmlspecialchars($s['summary']) ?></p>
                    <span class="s-card__more">Ver servicio →</span>
                </a>
            <?php endforeach; ?>
        </div>
        <div class="section__cta reveal">
            <a href="/cotizacion" class="btn">Solicitar cotización</a>
        </div>
    </div>
</section>

<?php require __DIR__ . '/_reveal.php'; ?>
<?php layoutEnd(); ?>
