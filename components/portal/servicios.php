<?php
/** Listado de servicios. Render desde index.php. */
layoutStart([
    'current_slug' => 'servicios',
    'title'        => 'Servicios',
    'description'  => 'Sitios web corporativos, landing pages, tiendas online y mantención. Conoce los servicios de desarrollo web de ARVIOR, con precio y fecha cerrados.',
]);
?>
<section class="page-hero">
    <div class="container">
        <span class="section__eyebrow">Servicios</span>
        <h1 class="page-hero__title">Lo que construimos para tu empresa.</h1>
        <p class="page-hero__lead">Cuatro servicios, hechos bien. Cada uno resuelve un problema concreto y todos comparten lo mismo: precio y fecha cerrados, foco en captar clientes y soporte después de entregar.</p>
    </div>
</section>

<section class="section section--tight">
    <div class="container">
        <div class="card-grid card-grid--2 reveal">
            <?php foreach (portalServices() as $s): ?>
                <a class="s-card" href="/servicios/<?= htmlspecialchars($s['slug']) ?>">
                    <div class="s-card__icon"><?= portalIcon($s['icon']) ?></div>
                    <h3 class="s-card__title"><?= htmlspecialchars($s['title']) ?></h3>
                    <p class="s-card__text"><?= htmlspecialchars($s['summary']) ?></p>
                    <div class="s-card__meta">
                        <span><b><?= htmlspecialchars($s['price']) ?></b></span>
                        <span><?= htmlspecialchars($s['timeline']) ?></span>
                    </div>
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
