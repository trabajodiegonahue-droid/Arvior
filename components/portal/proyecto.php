<?php
/** Detalle de un proyecto del portafolio. Render desde index.php
 *  (scope: $project [array desde portfolio_projects]). */
$pj      = $project;
$gallery = portfolioGallery($pj['gallery'] ?? null);
$catLbl  = portfolioCategoryLabel($pj['category']);

layoutStart([
    'current_slug' => 'proyectos',
    'title'        => $pj['title'],
    'description'  => (string) ($pj['summary'] ?? ''),
    'og_image'     => (string) ($pj['cover_image'] ?? ''),
    'canonical'    => '/proyectos/' . $pj['slug'],
]);
?>
<section class="page-hero">
    <div class="container">
        <span class="section__eyebrow"><a href="/proyectos" style="color:inherit;">Proyectos</a> / <?= htmlspecialchars($catLbl) ?></span>
        <h1 class="page-hero__title"><?= htmlspecialchars($pj['title']) ?></h1>
        <?php if (!empty($pj['summary'])): ?>
            <p class="page-hero__lead"><?= htmlspecialchars($pj['summary']) ?></p>
        <?php endif; ?>
    </div>
</section>

<section class="section section--tight">
    <div class="container">
        <div class="proj-detail reveal">
            <div class="proj-detail__main">
                <?php if (!empty($pj['cover_image'])): ?>
                    <figure class="proj-detail__cover">
                        <img src="<?= htmlspecialchars($pj['cover_image']) ?>" alt="<?= htmlspecialchars($pj['title']) ?>">
                    </figure>
                <?php endif; ?>

                <?php if (!empty($pj['description'])): ?>
                    <div class="svc-block">
                        <span class="svc-block__eyebrow">Sobre el proyecto</span>
                        <?php /* nl2br: respeta los saltos de párrafo del admin sin permitir HTML. */ ?>
                        <p><?= nl2br(htmlspecialchars($pj['description'])) ?></p>
                    </div>
                <?php endif; ?>

                <?php if ($gallery): ?>
                    <div class="proj-gallery">
                        <?php foreach ($gallery as $img): ?>
                            <figure class="proj-gallery__item">
                                <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($pj['title']) ?>" loading="lazy">
                            </figure>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <aside class="proj-detail__side">
                <div class="proj-detail__facts">
                    <div class="service-meta__item">
                        <div class="service-meta__lb">Tipo de proyecto</div>
                        <div class="service-meta__val"><?= htmlspecialchars($catLbl) ?></div>
                    </div>
                    <?php if (!empty($pj['client_name'])): ?>
                        <div class="service-meta__item">
                            <div class="service-meta__lb">Cliente</div>
                            <div class="service-meta__val"><?= htmlspecialchars($pj['client_name']) ?></div>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($pj['result'])): ?>
                        <div class="service-meta__item">
                            <div class="service-meta__lb">Resultado</div>
                            <div class="service-meta__val"><?= htmlspecialchars($pj['result']) ?></div>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if (!empty($pj['live_url'])): ?>
                    <a class="btn btn--secondary proj-detail__live" href="<?= htmlspecialchars($pj['live_url']) ?>" target="_blank" rel="noopener nofollow">Ver sitio en vivo →</a>
                <?php endif; ?>
                <div class="proj-detail__cta">
                    <h3>¿Quieres algo así?</h3>
                    <p class="text-muted" style="font-size:.92rem;margin:.3rem 0 1rem;">Cuéntanos tu proyecto y te enviamos una propuesta con precio y fecha.</p>
                    <a class="btn" href="/cotizacion?servicio=<?= urlencode($catLbl) ?>">Solicitar cotización</a>
                </div>
            </aside>
        </div>
    </div>
</section>

<?php require __DIR__ . '/_reveal.php'; ?>
<?php layoutEnd(); ?>
