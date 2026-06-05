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
                <div class="service-meta">
                    <div class="service-meta__item">
                        <div class="service-meta__lb">Desde</div>
                        <div class="service-meta__val service-meta__val--price"><?= htmlspecialchars(str_replace('Desde ', '', $svc['price'])) ?></div>
                    </div>
                    <div class="service-meta__item">
                        <div class="service-meta__lb">Plazo</div>
                        <div class="service-meta__val"><?= htmlspecialchars($svc['timeline']) ?></div>
                    </div>
                </div>

                <div class="svc-block">
                    <span class="svc-block__eyebrow">El problema</span>
                    <p class="svc-block__lead"><?= htmlspecialchars($svc['problem']) ?></p>
                </div>

                <div class="svc-block">
                    <span class="svc-block__eyebrow">De qué se trata</span>
                    <p><?= htmlspecialchars($svc['description']) ?></p>
                </div>

                <div class="svc-block">
                    <span class="svc-block__eyebrow">Qué obtienes</span>
                    <p><?= htmlspecialchars($svc['gain']) ?></p>
                    <ul class="benefit-list benefit-list--cols">
                        <?php foreach ($svc['includes'] as $b): ?>
                            <li><span class="benefit-list__ic"><?= portalIcon('check') ?></span><?= htmlspecialchars($b) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="service-when">
                    <strong>¿Cuándo te conviene?</strong> <?= htmlspecialchars($svc['when']) ?>
                </div>
            </div>

            <aside class="service-detail__form" id="cotizar">
                <h3>Solicitar cotización</h3>
                <p class="text-muted" style="font-size:.92rem;margin:.3rem 0 1.1rem;">Te respondemos con una propuesta clara para <strong><?= htmlspecialchars($svc['title']) ?></strong>, con precio y fecha.</p>
                <?php
                $formSource       = 'servicio:' . $svc['slug'];
                $preselectService = $svc['title'];
                $returnPath       = '/servicios/' . $svc['slug'];
                $showService      = true;
                $showBudget       = true;
                $showCompany      = true;
                require __DIR__ . '/quote_form.php';
                ?>
            </aside>
        </div>
    </div>
</section>

<?php require __DIR__ . '/_reveal.php'; ?>
<?php layoutEnd(); ?>
