<?php
/** Portafolio del estudio. Render desde index.php.
 *  Muestra proyectos reales (tabla portfolio_projects) filtrables por rubro.
 *  Si todavía no hay proyectos publicados, cae al contenido descriptivo de
 *  portalProjects() para que la página nunca quede vacía (sin inventar métricas). */

$cats     = portfolioCategories();
$counts   = portfolioCounts();
$activeCat = isset($_GET['cat']) && isset($cats[$_GET['cat']]) ? (string) $_GET['cat'] : '';
$projects  = portfolioProjects($activeCat ?: null);
$totalAll  = array_sum($counts);
$hasReal   = $totalAll > 0; // hay portafolio real publicado → mostramos la grilla real

layoutStart([
    'current_slug' => 'proyectos',
    'title'        => 'Proyectos',
    'description'  => 'Portafolio de KOVA: sitios corporativos, landing pages y tiendas online que hemos construido. Mira ejemplos reales de nuestro trabajo por rubro.',
]);
?>
<section class="page-hero">
    <div class="container">
        <span class="section__eyebrow">Proyectos</span>
        <h1 class="page-hero__title">Trabajos que hemos construido.</h1>
        <p class="page-hero__lead">Explora nuestro portafolio por tipo de proyecto. Cada caso muestra lo que entregamos: diseño, estructura y un sitio listo para trabajar por tu negocio.</p>
    </div>
</section>

<section class="section section--tight">
    <div class="container">
        <?php if ($totalAll > 0): ?>
            <?php /* Filtros por rubro: solo se muestran las categorías que tienen proyectos. */ ?>
            <div class="pf-filters reveal" role="tablist" aria-label="Filtrar proyectos por tipo">
                <a class="pf-filter<?= $activeCat === '' ? ' pf-filter--active' : '' ?>" href="/proyectos">
                    Todos <span class="pf-filter__n"><?= $totalAll ?></span>
                </a>
                <?php foreach ($cats as $key => $label): if (empty($counts[$key])) continue; ?>
                    <a class="pf-filter<?= $activeCat === $key ? ' pf-filter--active' : '' ?>" href="/proyectos?cat=<?= urlencode($key) ?>">
                        <?= htmlspecialchars($label) ?> <span class="pf-filter__n"><?= (int) $counts[$key] ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($hasReal && !$projects): ?>
            <p class="page-hero__lead">Aún no hay proyectos en este rubro. <a href="/proyectos">Ver todos →</a></p>
        <?php elseif ($hasReal): ?>
            <div class="portfolio portfolio--lg reveal-stagger">
                <?php foreach ($projects as $p): $hasCover = !empty($p['cover_image']); $detailHref = '/proyectos/' . htmlspecialchars($p['slug']); ?>
                    <article class="proj proj--card">
                        <a class="proj__media<?= $hasCover ? '' : ' proj__media--empty' ?>" href="<?= $detailHref ?>" aria-label="<?= htmlspecialchars($p['title']) ?>">
                            <?php if ($hasCover): ?>
                                <img src="<?= htmlspecialchars($p['cover_image']) ?>" alt="<?= htmlspecialchars($p['title']) ?>" loading="lazy">
                            <?php else: ?>
                                <?= portalIcon('web') ?>
                            <?php endif; ?>
                        </a>
                        <div class="proj__body">
                            <span class="proj__tag"><?= htmlspecialchars(portfolioCategoryLabel($p['category'])) ?></span>
                            <h3 class="proj__title"><a href="<?= $detailHref ?>"><?= htmlspecialchars($p['title']) ?></a></h3>
                            <?php if (!empty($p['summary'])): ?>
                                <p class="proj__text"><?= htmlspecialchars($p['summary']) ?></p>
                            <?php endif; ?>
                            <?php if (!empty($p['result'])): ?>
                                <span class="proj__result"><?= portalIcon('check') ?> <?= htmlspecialchars($p['result']) ?></span>
                            <?php endif; ?>
                            <span class="proj__more">Ver proyecto →</span>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <?php /* Sin proyectos publicados aún: contenido descriptivo, sin métricas inventadas. */ ?>
            <p class="page-hero__lead" style="margin-bottom:1.6rem;">Estamos sumando casos reales con resultados verificados. Mientras tanto, esto es lo que construimos en cada tipo de proyecto:</p>
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
        <?php endif; ?>

        <div class="section__cta reveal">
            <a href="/cotizacion" class="btn">Quiero un proyecto así</a>
        </div>
    </div>
</section>

<?php require __DIR__ . '/_reveal.php'; ?>
<?php layoutEnd(); ?>
