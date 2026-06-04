<?php
/** Home del portal comercial. Render desde index.php (scope: $error, $sent). */
layoutStart([
    'current_slug' => '',
    // Título SEO descriptivo para la home (la portada es la que más posiciona):
    // keyword-led + marca. El resto de las páginas ya traen su propio título.
    'title'        => 'Desarrollo web, software y marketing digital',
    'description'  => 'ARVIOR — Desarrollo web, software, automatización, marketing digital e IA. Construimos sistemas que generan oportunidades comerciales para tu negocio.',
]);
?>

<!-- ================= HERO ================= -->
<section class="hero">
    <div class="hero__glow" aria-hidden="true"></div>
    <div class="container">
        <span class="hero__badge"><span class="dot"></span> Tecnología &middot; Marketing &middot; Automatización &middot; IA</span>
        <span class="hero__brand">ARVIOR</span>
        <h1 class="hero__title">Construimos <span class="text-gradient">sistemas</span> que generan negocio.</h1>
        <p class="hero__sub">Desarrollo web, software, automatización y marketing digital para empresas que quieren captar más oportunidades y convertirlas en ventas — con tecnología de nivel y todo medible.</p>
        <div class="hero__actions">
            <a href="/cotizacion" class="btn">Solicitar cotización</a>
            <a href="/servicios" class="btn btn--secondary">Ver servicios</a>
        </div>
    </div>
</section>

<!-- ================= PROPUESTA DE VALOR ================= -->
<section class="section section--tight">
    <div class="container">
        <div class="section__head section__head--center reveal">
            <span class="section__eyebrow">Por qué ARVIOR</span>
            <h2 class="section__title">No hacemos webs bonitas. Construimos activos que venden.</h2>
            <p class="section__lead">Tu sitio debería traerte clientes, no solo existir. Diseñamos cada proyecto para captar oportunidades reales y dejarlas ordenadas en un sistema comercial que podés medir.</p>
        </div>
        <div class="card-grid reveal">
            <?php foreach (portalBenefits() as $b): ?>
                <article class="s-card s-card--static">
                    <div class="s-card__icon"><?= portalIcon($b['icon']) ?></div>
                    <h3 class="s-card__title"><?= htmlspecialchars($b['title']) ?></h3>
                    <p class="s-card__text"><?= htmlspecialchars($b['text']) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ================= SERVICIOS DESTACADOS ================= -->
<section class="section" id="servicios">
    <div class="container">
        <div class="section__head reveal">
            <span class="section__eyebrow">Servicios</span>
            <h2 class="section__title">Todo lo que tu negocio necesita para crecer online.</h2>
            <p class="section__lead">De la web a la operación: diseño, tráfico, automatización y datos, integrados en un solo lugar.</p>
        </div>
        <div class="card-grid reveal">
            <?php foreach (portalFeaturedServices(6) as $s): ?>
                <a class="s-card" href="/servicios/<?= htmlspecialchars($s['slug']) ?>">
                    <div class="s-card__icon"><?= portalIcon($s['icon']) ?></div>
                    <h3 class="s-card__title"><?= htmlspecialchars($s['title']) ?></h3>
                    <p class="s-card__text"><?= htmlspecialchars($s['tagline']) ?></p>
                    <span class="s-card__more">Ver más →</span>
                </a>
            <?php endforeach; ?>
        </div>
        <div class="section__cta reveal">
            <a href="/servicios" class="btn btn--secondary">Ver todos los servicios</a>
        </div>
    </div>
</section>

<!-- ================= PROCESO ================= -->
<section class="section section--tight">
    <div class="container">
        <div class="section__head reveal">
            <span class="section__eyebrow">Cómo trabajamos</span>
            <h2 class="section__title">Un camino claro de la idea al resultado.</h2>
        </div>
        <div class="process reveal">
            <?php foreach (portalProcess() as $step): ?>
                <div class="process__step">
                    <span class="process__num"><?= htmlspecialchars($step['num']) ?></span>
                    <h3><?= htmlspecialchars($step['title']) ?></h3>
                    <p><?= htmlspecialchars($step['text']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ================= PROYECTOS DESTACADOS ================= -->
<section class="section">
    <div class="container">
        <div class="section__head reveal">
            <span class="section__eyebrow">Proyectos</span>
            <h2 class="section__title">El tipo de proyectos que construimos.</h2>
            <p class="section__lead">Ejemplos por rubro de lo que podemos construir para que tu negocio crezca.</p>
        </div>
        <div class="portfolio reveal">
            <?php foreach (array_slice(portalProjects(), 0, 3) as $p): ?>
                <article class="proj">
                    <span class="proj__tag"><?= htmlspecialchars($p['tag']) ?></span>
                    <h3 class="proj__title"><?= htmlspecialchars($p['title']) ?></h3>
                    <p class="proj__text"><?= htmlspecialchars($p['text']) ?></p>
                    <span class="proj__result"><?= htmlspecialchars($p['result']) ?></span>
                </article>
            <?php endforeach; ?>
        </div>
        <div class="section__cta reveal">
            <a href="/proyectos" class="btn btn--secondary">Ver más proyectos</a>
        </div>
    </div>
</section>

<!-- ================= CTA COTIZACIÓN ================= -->
<section class="section section--tight">
    <div class="container">
        <div class="cta reveal">
            <h2 class="cta__title">¿Listo para captar <span class="text-gradient">más oportunidades</span>?</h2>
            <p class="cta__sub">Contanos tu proyecto y te enviamos una propuesta clara. Sin compromiso.</p>
            <div class="hero__actions" style="justify-content:center;">
                <a href="/cotizacion" class="btn">Solicitar cotización</a>
                <a href="/contacto" class="btn btn--secondary">Hablar con nosotros</a>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/_reveal.php'; ?>
<?php layoutEnd(); ?>
