<?php
/** Home del portal comercial. Render desde index.php (scope: $error, $sent). */
layoutStart([
    'current_slug' => '',
    // Título SEO descriptivo (la portada es la que más posiciona): keyword-led + marca.
    'title'        => 'Diseño y desarrollo de sitios web para empresas',
    'description'  => 'ARVIOR — Estudio de desarrollo web. Diseñamos sitios corporativos, landing pages y tiendas online pensados para que tu empresa se vea profesional y reciba más consultas. Precio y fecha cerrados.',
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
                    <a href="/cotizacion" class="btn">Solicitar cotización</a>
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

<!-- ================= FRANJA DE CONFIANZA ================= -->
<div class="container">
    <div class="trust-strip reveal">
        <?php foreach (portalTrust() as $t): ?>
            <span class="trust-strip__item"><?= portalIcon($t['icon']) ?> <?= htmlspecialchars($t['text']) ?></span>
        <?php endforeach; ?>
    </div>
</div>

<!-- ================= PROBLEMA + PUNTO DE VISTA ================= -->
<section class="section">
    <div class="container">
        <div class="split">
            <div class="split__copy">
                <span class="section__eyebrow">El problema</span>
                <h2 class="section__title">Se ven bien. Pocas <span class="text-gradient">hacen vender</span>.</h2>
                <p class="section__lead">Una web que nadie usa para escribirte es un gasto. La nuestra está hecha para que te contacten.</p>
            </div>
            <div class="split__media reveal">
                <div class="compare">
                    <div class="compare__row compare__row--bad">
                        <span class="ic"><?= portalIcon('x') ?></span>
                        <p><strong>Web común:</strong> el visitante entra, mira y se va sin dejar rastro.</p>
                    </div>
                    <div class="compare__row compare__row--bad">
                        <span class="ic"><?= portalIcon('x') ?></span>
                        <p><strong>Web común:</strong> no se entiende rápido qué haces.</p>
                    </div>
                    <div class="compare__row compare__row--good">
                        <span class="ic"><?= portalIcon('check') ?></span>
                        <p><strong>Web ARVIOR:</strong> mensaje claro y un camino directo al contacto.</p>
                    </div>
                    <div class="compare__row compare__row--good">
                        <span class="ic"><?= portalIcon('check') ?></span>
                        <p><strong>Web ARVIOR:</strong> cada consulta llega ordenada y lista para responder.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ================= SERVICIOS ================= -->
<section class="section section--alt" id="servicios">
    <div class="container">
        <div class="section__head reveal">
            <span class="section__eyebrow">Qué hacemos</span>
            <h2 class="section__title">Cuatro servicios, hechos bien.</h2>
        </div>
        <div class="card-grid reveal">
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

<!-- ================= POR QUÉ ARVIOR ================= -->
<section class="section">
    <div class="container">
        <div class="section__head section__head--center reveal">
            <span class="section__eyebrow">Por qué ARVIOR</span>
            <h2 class="section__title">Un socio que se hace cargo.</h2>
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

<!-- ================= PROCESO ================= -->
<section class="section section--alt">
    <div class="container">
        <div class="section__head reveal">
            <span class="section__eyebrow">Cómo trabajamos</span>
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

<!-- ================= PLANES / PRECIOS ================= -->
<section class="section" id="planes">
    <div class="container">
        <div class="section__head section__head--center reveal">
            <span class="section__eyebrow">Planes</span>
            <h2 class="section__title">Precios claros desde el día uno.</h2>
        </div>
        <div class="plans reveal">
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
                    <p class="plan__timeline">Entrega en <?= htmlspecialchars($p['timeline']) ?></p>
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
        <p class="plans-disclaimer reveal">Precios en CLP. Pago 50% al inicio y 50% contra entrega. El valor final se cierra según tu proyecto.</p>
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

<!-- ================= CTA COTIZACIÓN ================= -->
<section class="section section--tight">
    <div class="container">
        <div class="cta reveal">
            <h2 class="cta__title">Cuéntanos tu proyecto y recibe una <span class="text-gradient">propuesta clara</span>.</h2>
            <p class="cta__sub">Alcance, precio y fecha en menos de 24 h. Sin compromiso.</p>
            <div class="hero__actions" style="justify-content:center;">
                <a href="/cotizacion" class="btn">Solicitar cotización</a>
                <a href="/contacto" class="btn btn--secondary">Hablar con nosotros</a>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/_reveal.php'; ?>
<?php layoutEnd(); ?>
