<?php
/** Home del portal comercial. Render desde index.php (scope: $error, $sent). */

// JSON-LD adicional para la portada: FAQPage (resultados enriquecidos en Google)
// + lista de servicios ofrecidos. Se inyecta vía la opción 'jsonld' del layout.
$faqJsonLd = ['@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => []];
foreach (portalFaqs() as $f) {
    $faqJsonLd['mainEntity'][] = [
        '@type'          => 'Question',
        'name'           => $f['q'],
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']],
    ];
}

layoutStart([
    'current_slug' => '',
    // Título SEO descriptivo (la portada es la que más posiciona): keyword-led + marca.
    'title'        => 'Diseño y desarrollo de sitios web para empresas',
    'description'  => 'ARVIOR — Estudio de desarrollo web. Diseñamos sitios corporativos, landing pages y tiendas online pensados para que tu empresa se vea profesional y reciba más consultas. Precio y fecha cerrados.',
    'jsonld'       => [$faqJsonLd],
]);
$pkgs = portalPackages();
$maint = portalMaintenance();
?>

<!-- ================= HERO ================= -->
<section class="hero">
    <div class="container">
        <div class="hero__inner">
            <div class="hero__copy">
                <span class="hero__eyebrow"><span class="dot"></span> Estudio de desarrollo web</span>
                <h1 class="hero__title">Tu sitio web debería <span class="text-gradient">traerte clientes</span>.</h1>
                <p class="hero__sub">Sitios web que te hacen ver profesional y te traen consultas.</p>
                <div class="hero__actions">
                    <a href="/cotizacion" class="btn btn--arrow">Solicitar cotización</a>
                    <a href="/servicios" class="btn btn--secondary">Ver servicios</a>
                </div>
                <p class="hero__note">Respuesta en <strong>menos de 24 h</strong>. Sin compromiso.</p>
            </div>
            <?php /* Mini-demo animado (CSS, sin video): así es trabajar con ARVIOR. */ ?>
            <div class="hero__visual reveal">
                <div class="demo" aria-hidden="true">
                    <div class="demo__window">
                        <div class="demo__bar">
                            <span class="demo__dot"></span><span class="demo__dot"></span><span class="demo__dot"></span>
                            <span class="demo__url">tuempresa.cl</span>
                        </div>
                        <div class="demo__progress"><span></span></div>
                        <div class="demo__stage">
                            <!-- Acto 1: entendemos tu negocio (checklist) -->
                            <div class="demo__screen demo__screen--1">
                                <div class="demo__nav"><span class="demo__brand">DIAGNÓSTICO</span><span class="demo__links"><i></i><i></i></span></div>
                                <div class="demo__cl">
                                    <div class="demo__cl-row"><span class="demo__cl-box"><?= portalIcon('check') ?></span><i></i></div>
                                    <div class="demo__cl-row"><span class="demo__cl-box"><?= portalIcon('check') ?></span><i></i></div>
                                    <div class="demo__cl-row"><span class="demo__cl-box"><?= portalIcon('check') ?></span><i></i></div>
                                    <div class="demo__cl-row"><span class="demo__cl-box"><?= portalIcon('check') ?></span><i></i></div>
                                </div>
                            </div>
                            <!-- Acto 2: diseñamos (wireframe) -->
                            <div class="demo__screen demo__screen--2">
                                <div class="demo__nav"><span class="demo__brand">TU&nbsp;MARCA</span><span class="demo__links"><i></i><i></i><i></i></span></div>
                                <div class="demo__sk demo__sk--h"></div>
                                <div class="demo__sk demo__sk--w1"></div>
                                <div class="demo__sk demo__sk--w2"></div>
                                <div class="demo__grid"><span></span><span></span><span></span></div>
                            </div>
                            <!-- Acto 3: tu sitio en vivo (a color) -->
                            <div class="demo__screen demo__screen--3">
                                <div class="demo__nav"><span class="demo__brand">TU&nbsp;MARCA</span><span class="demo__links"><i></i><i></i><i></i></span></div>
                                <div class="demo__live">
                                    <div class="demo__lcopy">
                                        <div class="demo__ltitle"></div>
                                        <div class="demo__ltitle demo__ltitle--accent"></div>
                                        <div class="demo__lbtn"></div>
                                    </div>
                                    <div class="demo__limg"></div>
                                </div>
                                <div class="demo__feats"><span></span><span></span><span></span></div>
                            </div>
                            <!-- Acto 4: perfecto en el celular (mockup teléfono) -->
                            <div class="demo__screen demo__screen--4">
                                <div class="demo__phone">
                                    <div class="demo__phone-bar"><span></span></div>
                                    <div class="demo__phone-body">
                                        <div class="demo__phone-brand">TU&nbsp;MARCA</div>
                                        <div class="demo__phone-hero"></div>
                                        <div class="demo__phone-line"></div>
                                        <div class="demo__phone-line demo__phone-line--short"></div>
                                        <div class="demo__phone-btn"></div>
                                    </div>
                                </div>
                            </div>
                            <!-- Acto 5: llegan consultas (notificaciones) -->
                            <div class="demo__screen demo__screen--5">
                                <div class="demo__nav"><span class="demo__brand">TU&nbsp;MARCA</span><span class="demo__links"><i></i><i></i><i></i></span></div>
                                <div class="demo__title demo__title--dim"></div>
                                <div class="demo__notif"><span class="demo__check"><?= portalIcon('check') ?></span><b>Nueva consulta</b></div>
                                <div class="demo__notif"><span class="demo__check"><?= portalIcon('check') ?></span><b>Nueva consulta</b></div>
                                <div class="demo__notif"><span class="demo__check"><?= portalIcon('check') ?></span><b>Nueva consulta</b></div>
                            </div>
                        </div>
                    </div>
                    <div class="demo__caption">
                        <span class="demo__step demo__step--1">Entendemos tu negocio</span>
                        <span class="demo__step demo__step--2">Diseñamos tu sitio</span>
                        <span class="demo__step demo__step--3">Lo publicamos</span>
                        <span class="demo__step demo__step--4">Perfecto en el celular</span>
                        <span class="demo__step demo__step--5">Llegan tus consultas</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ================= FRANJA DE CONFIANZA (ancho completo) ================= -->
<div class="trust-strip reveal">
    <div class="container trust-strip__inner">
        <?php foreach (portalTrust() as $t): ?>
            <span class="trust-strip__item"><?= portalIcon($t['icon']) ?> <?= htmlspecialchars($t['text']) ?></span>
        <?php endforeach; ?>
    </div>
</div>

<!-- ================= MARQUEE ================= -->
<div class="marquee" aria-hidden="true">
    <div class="marquee__track">
        <?php for ($mi = 0; $mi < 2; $mi++): foreach (portalMarqueeWords() as $w): ?>
            <span class="marquee__word"><?= htmlspecialchars($w) ?></span>
            <span class="marquee__sep">•</span>
        <?php endforeach; endfor; ?>
    </div>
</div>

<!-- ================= ¿QUÉ NECESITAS? (selector → cotización) ================= -->
<section class="section section--tight">
    <div class="container">
        <div class="section__head section__head--center reveal">
            <span class="section__eyebrow">[ 01 ] · Empecemos</span>
            <h2 class="section__title">¿Qué necesitas?</h2>
            <p class="section__lead">Elige tu objetivo y te llevamos directo a una cotización, con el servicio ya seleccionado.</p>
        </div>
        <div class="goals reveal-stagger">
            <?php foreach (portalGoals() as $g): ?>
                <a class="goal" href="/cotizacion?servicio=<?= urlencode($g['service']) ?>">
                    <div class="goal__icon"><?= portalIcon($g['icon']) ?></div>
                    <div class="goal__body">
                        <h3 class="goal__title"><?= htmlspecialchars($g['title']) ?></h3>
                        <p class="goal__text"><?= htmlspecialchars($g['text']) ?></p>
                    </div>
                    <span class="goal__arrow">→</span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ================= PROBLEMA (grilla de dolores) ================= -->
<section class="section">
    <div class="container">
        <div class="section__head section__head--center reveal">
            <span class="section__eyebrow">[ 02 ] · El problema</span>
            <h2 class="section__title">Tener una web linda no es <span class="text-gradient">suficiente</span>.</h2>
            <p class="section__lead">Si no te encuentran, no da confianza o nadie te escribe, tu web es un gasto. La nuestra está hecha para que te contacten.</p>
        </div>
        <div class="card-grid reveal-stagger">
            <?php foreach (portalPains() as $pi => $p): ?>
                <article class="pain">
                    <span class="pain__num">/ 0<?= $pi + 1 ?></span>
                    <div class="pain__icon"><?= portalIcon($p['icon']) ?></div>
                    <h3 class="pain__title"><?= htmlspecialchars($p['title']) ?></h3>
                    <p class="pain__text"><?= htmlspecialchars($p['text']) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ================= SERVICIOS ================= -->
<section class="section section--alt" id="servicios">
    <div class="container">
        <div class="section__head reveal">
            <span class="section__eyebrow">[ 03 ] · Qué hacemos</span>
            <h2 class="section__title">Cuatro servicios, hechos bien.</h2>
        </div>
        <div class="card-grid reveal-stagger">
            <?php foreach (portalFeaturedServices(4) as $s): ?>
                <a class="s-card" href="/servicios/<?= htmlspecialchars($s['slug']) ?>">
                    <div class="s-card__icon"><?= portalIcon($s['icon']) ?></div>
                    <h3 class="s-card__title"><?= htmlspecialchars($s['title']) ?></h3>
                    <p class="s-card__text"><?= htmlspecialchars($s['summary']) ?></p>
                    <div class="s-card__meta">
                        <span><b><?= htmlspecialchars($s['price']) ?></b></span>
                        <span><?= htmlspecialchars($s['timeline']) ?></span>
                    </div>
                    <span class="s-card__more">Ver más →</span>
                </a>
            <?php endforeach; ?>
        </div>
        <div class="section__cta reveal">
            <a href="/servicios" class="btn btn--secondary">Ver todos los servicios</a>
        </div>
    </div>
</section>

<!-- ================= RUBROS ================= -->
<section class="section section--tight">
    <div class="container">
        <div class="sectors reveal">
            <span class="sectors__label">Ideal para</span>
            <div class="sectors__list">
                <?php foreach (portalSectors() as $sec): ?>
                    <span class="sectors__pill"><?= htmlspecialchars($sec) ?></span>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- ================= POR QUÉ ARVIOR ================= -->
<section class="section">
    <div class="container">
        <div class="section__head section__head--center reveal">
            <span class="section__eyebrow">[ 04 ] · Por qué ARVIOR</span>
            <h2 class="section__title">Un socio que se hace cargo.</h2>
        </div>
        <div class="card-grid reveal-stagger">
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

<!-- ================= PROCESO ================= -->
<section class="section section--alt">
    <div class="container">
        <div class="section__head reveal">
            <span class="section__eyebrow">[ 05 ] · Cómo trabajamos</span>
            <h2 class="section__title">Un camino claro, sin sorpresas.</h2>
        </div>
        <div class="process reveal">
            <?php foreach (portalProcess() as $step): ?>
                <div class="process__step">
                    <div class="process__icon"><?= portalIcon($step['icon']) ?></div>
                    <span class="process__num"><?= htmlspecialchars($step['num']) ?></span>
                    <h3><?= htmlspecialchars($step['title']) ?></h3>
                    <p><?= htmlspecialchars($step['text']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="section__cta reveal">
            <a href="/proceso" class="btn btn--secondary">Conocer el proceso en detalle</a>
        </div>
    </div>
</section>

<!-- ================= GARANTÍA / COMPROMISO ================= -->
<section class="section">
    <div class="container">
        <div class="section__head section__head--center reveal">
            <span class="section__eyebrow">Sin riesgo</span>
            <h2 class="section__title">Contratar no debería dar miedo.</h2>
        </div>
        <div class="card-grid reveal-stagger">
            <?php foreach (portalGuarantee() as $g): ?>
                <article class="guarantee">
                    <div class="guarantee__icon"><?= portalIcon($g['icon']) ?></div>
                    <h3 class="guarantee__title"><?= htmlspecialchars($g['title']) ?></h3>
                    <p class="guarantee__text"><?= htmlspecialchars($g['text']) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ================= SOBRE ARVIOR (banda oscura) ================= -->
<section class="section section--dark">
    <div class="container">
        <div class="about reveal">
            <span class="section__eyebrow">Sobre ARVIOR</span>
            <h2 class="section__title">Un estudio pequeño, con atención <span class="text-gradient">cercana</span>.</h2>
            <p class="about__text">Somos un estudio de desarrollo web en Chile. Trabajas directo con quien construye tu sitio, sin intermediarios. Tomamos pocos proyectos a la vez para cuidar cada detalle y cumplir los plazos.</p>
            <p class="about__scarcity"><span class="dot"></span> Cupos limitados: tomamos pocos proyectos al mes para asegurar calidad.</p>
        </div>
    </div>
</section>

<!-- ================= PLANES / PRECIOS ================= -->
<section class="section" id="planes">
    <div class="container">
        <div class="section__head section__head--center reveal">
            <span class="section__eyebrow">[ 06 ] · Planes</span>
            <h2 class="section__title">Precios claros desde el día uno.</h2>
        </div>
        <div class="plans reveal-stagger">
            <?php foreach ($pkgs as $p): ?>
                <article class="plan<?= !empty($p['featured']) ? ' plan--featured' : '' ?>">
                    <?php if (!empty($p['featured'])): ?><span class="plan__flag">Más elegido</span><?php endif; ?>
                    <div class="plan__icon"><?= portalIcon($p['icon']) ?></div>
                    <h3 class="plan__name"><?= htmlspecialchars($p['name']) ?></h3>
                    <p class="plan__tagline"><?= htmlspecialchars($p['tagline']) ?></p>
                    <div class="plan__price">
                        <?php if ($p['unit'] === 'desde'): ?><span>desde</span><?php endif; ?>
                        <b><?= htmlspecialchars($p['price']) ?></b>
                    </div>
                    <p class="plan__timeline">Entrega: <?= htmlspecialchars($p['timeline']) ?></p>
                    <ul class="plan__features">
                        <?php foreach ($p['features'] as $f): ?>
                            <li><?= portalIcon('check') ?> <?= htmlspecialchars($f) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <a href="/cotizacion" class="btn<?= empty($p['featured']) ? ' btn--secondary' : '' ?>"><?= htmlspecialchars($p['cta']) ?></a>
                </article>
            <?php endforeach; ?>
        </div>
        <p class="plan-note reveal">
            <span><?= portalIcon('shield') ?></span>
            <b><?= htmlspecialchars($maint['title']) ?>:</b> <?= htmlspecialchars($maint['price']) ?> — <?= htmlspecialchars($maint['text']) ?>
        </p>
        <p class="plans-disclaimer reveal">Precios en CLP · Pago 50/50 · Valor final según tu proyecto.</p>
    </div>
</section>

<!-- ================= FAQ ================= -->
<section class="section section--alt">
    <div class="container">
        <div class="section__head section__head--center reveal">
            <span class="section__eyebrow">Preguntas frecuentes</span>
            <h2 class="section__title">Antes de empezar.</h2>
        </div>
        <div class="faq reveal">
            <?php foreach (portalFaqs() as $f): ?>
                <details class="faq__item">
                    <summary class="faq__q"><?= htmlspecialchars($f['q']) ?></summary>
                    <p class="faq__a"><?= htmlspecialchars($f['a']) ?></p>
                </details>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ================= CTA COTIZACIÓN (formulario embebido) ================= -->
<section class="section section--tight" id="cotizar">
    <div class="container">
        <div class="cta-form reveal">
            <div class="cta-form__intro">
                <span class="section__eyebrow">[ 07 ] · Empecemos</span>
                <h2 class="cta__title">Cuéntanos tu proyecto y recibe una <span class="text-gradient">propuesta clara</span>.</h2>
                <p class="cta__sub">Alcance, precio y fecha en menos de 24 h hábiles. Sin compromiso.</p>
                <ul class="assure assure--cta">
                    <li><span class="assure__ic"><?= portalIcon('clock') ?></span><span>Respuesta en <strong>menos de 24 h</strong></span></li>
                    <li><span class="assure__ic"><?= portalIcon('check') ?></span><span>Precio y fecha <strong>cerrados</strong></span></li>
                    <li><span class="assure__ic"><?= portalIcon('phone') ?></span><span>Atención directa, sin intermediarios</span></li>
                    <li><span class="assure__ic"><?= portalIcon('shield') ?></span><span>Sin compromiso ni letra chica</span></li>
                </ul>
            </div>
            <div class="cta-form__card">
                <?php
                // Formulario embebido: elimina el clic intermedio a /cotizacion.
                // El POST cae en index.php (action=submit_lead) → leadCreate() → CRM.
                $formSource  = 'home';
                $returnPath  = '/';
                $showService = true; $showBudget = true; $showCompany = true;
                require __DIR__ . '/quote_form.php';
                ?>
            </div>
        </div>
    </div>
</section>

<?php if ($sent): ?>
<!-- Tras enviar el formulario embebido, lleva el foco al mensaje de éxito (queda abajo). -->
<script>
(function(){
    var el = document.getElementById('cotizar');
    if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
})();
</script>
<?php endif; ?>

<?php require __DIR__ . '/_reveal.php'; ?>
<?php layoutEnd(); ?>
