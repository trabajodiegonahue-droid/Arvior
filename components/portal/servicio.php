<?php
/** Detalle de un servicio + formulario de contacto. Render desde index.php
 *  (scope: $service [array], $error, $sent). */
$svc = $service;
layoutStart([
    'current_slug' => 'servicios',
    'title'        => $svc['title'],
    'description'  => $svc['summary'],
    'canonical'    => '/servicios/' . $svc['slug'],
]);
?>
<section class="page-hero">
    <div class="container">
        <div class="page-hero__icon"><?= portalIcon($svc['icon']) ?></div>
        <span class="section__eyebrow"><a href="/servicios" style="color:inherit;">Servicios</a> / <?= htmlspecialchars($svc['title']) ?></span>
        <h1 class="page-hero__title"><?= htmlspecialchars($svc['title']) ?></h1>
        <p class="page-hero__lead"><?= htmlspecialchars($svc['tagline']) ?></p>
    </div>
</section>

<section class="section section--tight">
    <div class="container">
        <div class="service-detail reveal">
            <div class="service-detail__main">
                <h2 class="section__title">De qué se trata</h2>
                <p class="section__lead" style="margin-bottom:1.6rem;"><?= htmlspecialchars($svc['description']) ?></p>

                <h3 class="service-detail__subtitle">Qué obtenés</h3>
                <ul class="benefit-list">
                    <?php foreach ($svc['benefits'] as $b): ?>
                        <li><span class="benefit-list__ic"><?= portalIcon('check') ?></span><?= htmlspecialchars($b) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <aside class="service-detail__form" id="cotizar">
                <h3>Solicitar información</h3>
                <p class="text-muted" style="font-size:.9rem;margin:.2rem 0 1rem;">Te respondemos con una propuesta clara para <strong><?= htmlspecialchars($svc['title']) ?></strong>.</p>
                <?php
                $formSource       = 'servicio:' . $svc['slug'];
                $preselectService = $svc['title'];
                $returnPath       = '/servicios/' . $svc['slug'];
                $showBudget       = true;
                require __DIR__ . '/quote_form.php';
                ?>
            </aside>
        </div>
    </div>
</section>

<?php require __DIR__ . '/_reveal.php'; ?>
<?php layoutEnd(); ?>
