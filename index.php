<?php

require __DIR__ . '/lib/bootstrap.php';

// Router mínimo: si el path no es "/" y coincide con un slug publicado, renderizar esa página.
$path = trim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/');
if ($path !== '' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $slug = slugify($path);
    if ($slug === $path) {
        try {
            $stmt = getDB()->prepare('SELECT title, body, meta_description, og_image, hide_chrome FROM pages WHERE slug = ? AND is_published = 1');
            $stmt->execute([$slug]);
            $cms = $stmt->fetch();
        } catch (Throwable $e) {
            $cms = null; // tabla todavía no migrada (instalación vieja sin admin visita)
        }
        if (!empty($cms)) {
            layoutStart([
                'title'        => (string) $cms['title'],
                'description'  => (string) ($cms['meta_description'] ?? ''),
                'og_image'     => (string) ($cms['og_image'] ?? ''),
                'current_slug' => $slug,
                'hide_chrome'  => !empty($cms['hide_chrome']),
            ]);
            ?>
<main class="container">
    <article class="page">
        <h1><?= htmlspecialchars($cms['title']) ?></h1>
        <?= $cms['body'] /* HTML confiado: solo lo edita el admin autenticado */ ?>
    </article>
</main>
<?php
            layoutEnd();
            exit;
        }
    }
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'submit_lead') {

    // Anti-spam: honeypot + timing. Fake success (los bots no se enteran).
    $honeypotTripped = !empty($_POST['website']);
    $tooFast         = !isset($_POST['form_started']) || (time() - (int) $_POST['form_started']) < 2;

    if ($honeypotTripped || $tooFast) {
        redirect('/?sent=1');
    }

    csrfCheck();
    $name    = trim($_POST['name']    ?? '');
    $email   = trim($_POST['email']   ?? '');
    $phone   = trim($_POST['phone']   ?? '');
    $message = trim($_POST['message'] ?? '');
    $source  = trim($_POST['source']  ?? 'website');

    if (!$name || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Nombre y email válido son requeridos.';
    } else {
        // Duplicate check: mismo email en los últimos 5 minutos → fake success.
        $dupe = getDB()->prepare(
            'SELECT COUNT(*) FROM leads
             WHERE email = ? AND created_at > DATE_SUB(NOW(), INTERVAL 5 MINUTE)'
        );
        $dupe->execute([$email]);

        if ((int) $dupe->fetchColumn() > 0) {
            redirect('/?sent=1');
        }

        $db = getDB();
        $stmt = $db->prepare(
            'INSERT INTO leads (name, email, phone, message, source, status, ip_address, user_agent)
             VALUES (?, ?, ?, ?, ?, "new", ?, ?)'
        );
        $stmt->execute([
            $name, $email, $phone, $message, $source,
            clientIp(),
            substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 500),
        ]);

        $lead = [
            'id'      => (int) $db->lastInsertId(),
            'name'    => $name,
            'email'   => $email,
            'phone'   => $phone,
            'message' => $message,
            'source'  => $source,
        ];

        // Notificaciones: no deben romper el flujo si fallan.
        try { notifyLeadCreated($lead); }   catch (Throwable $e) { error_log('notifyLead: ' . $e->getMessage()); }
        try { sendLeadAutoReply($lead); }   catch (Throwable $e) { error_log('autoReply: ' . $e->getMessage()); }

        redirect('/gracias');
    }
}

layoutStart([
    'current_slug' => '',
    'title'        => '',
    'description'  => 'ARVIOR — Technology, automation, software and AI solutions designed to help modern businesses grow.',
]);

// Icono SVG inline reutilizable (sin dependencias externas).
$icon = function (string $key): string {
    $a = 'viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"';
    return [
        'web'   => "<svg $a><rect x='2' y='3' width='20' height='14' rx='2'/><path d='M2 9h20M8 21h8M12 17v4'/></svg>",
        'sys'   => "<svg $a><rect x='3' y='3' width='7' height='7' rx='1'/><rect x='14' y='3' width='7' height='7' rx='1'/><rect x='3' y='14' width='7' height='7' rx='1'/><rect x='14' y='14' width='7' height='7' rx='1'/></svg>",
        'auto'  => "<svg $a><path d='M12 2v4M12 18v4M4.9 4.9l2.8 2.8M16.3 16.3l2.8 2.8M2 12h4M18 12h4M4.9 19.1l2.8-2.8M16.3 7.7l2.8-2.8'/><circle cx='12' cy='12' r='3'/></svg>",
        'ai'    => "<svg $a><rect x='5' y='6' width='14' height='12' rx='3'/><path d='M9 2v4M15 2v4M9 11h.01M15 11h.01M9 15h6'/><path d='M2 12h3M19 12h3'/></svg>",
    ][$key] ?? '';
};
?>

<!-- ================= HERO ================= -->
<section class="hero">
    <div class="hero__glow" aria-hidden="true"></div>
    <div class="container">
        <span class="hero__badge"><span class="dot"></span> Technology &middot; Automation &middot; AI</span>
        <span class="hero__brand">ARVIOR</span>
        <h1 class="hero__title">Building <span class="text-gradient">systems</span> for modern businesses.</h1>
        <p class="hero__sub">Technology, automation, software and AI solutions designed to help businesses grow — engineered with the precision of a venture studio.</p>
        <div class="hero__actions">
            <a href="#solutions" class="btn">Explore Solutions</a>
            <a href="#contact" class="btn btn--secondary">Book a Meeting</a>
        </div>
    </div>
</section>

<!-- ================= QUÉ CONSTRUIMOS ================= -->
<section class="section" id="solutions">
    <div class="container">
        <div class="section__head reveal">
            <span class="section__eyebrow">What we build</span>
            <h2 class="section__title">Digital systems, end to end.</h2>
            <p class="section__lead">From the first line of code to the systems that run your operation — we design and build the technology that scales with you.</p>
        </div>
        <div class="card-grid reveal">
            <article class="s-card">
                <div class="s-card__icon"><?= $icon('web') ?></div>
                <h3 class="s-card__title">Websites</h3>
                <p class="s-card__text">High-performance, premium websites and landing pages engineered to convert and represent your brand at the highest level.</p>
            </article>
            <article class="s-card">
                <div class="s-card__icon"><?= $icon('sys') ?></div>
                <h3 class="s-card__title">Systems</h3>
                <p class="s-card__text">Custom internal platforms, dashboards and software that turn manual processes into reliable digital infrastructure.</p>
            </article>
            <article class="s-card">
                <div class="s-card__icon"><?= $icon('auto') ?></div>
                <h3 class="s-card__title">Automation</h3>
                <p class="s-card__text">Workflows and integrations that connect your tools and remove repetitive work — so your team focuses on what matters.</p>
            </article>
            <article class="s-card">
                <div class="s-card__icon"><?= $icon('ai') ?></div>
                <h3 class="s-card__title">AI</h3>
                <p class="s-card__text">AI assistants, agents and intelligent features built into your products and operations to create real leverage.</p>
            </article>
        </div>
    </div>
</section>

<!-- ================= PROCESO ================= -->
<section class="section section--tight">
    <div class="container">
        <div class="section__head reveal">
            <span class="section__eyebrow">Our process</span>
            <h2 class="section__title">A clear path from idea to scale.</h2>
        </div>
        <div class="process reveal">
            <div class="process__step">
                <span class="process__num">01</span>
                <h3>Strategy</h3>
                <p>We map your goals, constraints and opportunities to define what's worth building.</p>
            </div>
            <div class="process__step">
                <span class="process__num">02</span>
                <h3>Design</h3>
                <p>We craft the experience and architecture — clean, premium and built to last.</p>
            </div>
            <div class="process__step">
                <span class="process__num">03</span>
                <h3>Build</h3>
                <p>We engineer the product with modern, reliable technology and ship fast.</p>
            </div>
            <div class="process__step">
                <span class="process__num">04</span>
                <h3>Scale</h3>
                <p>We optimize, automate and grow the system as your business expands.</p>
            </div>
        </div>
    </div>
</section>

<!-- ================= PROYECTOS ================= -->
<section class="section">
    <div class="container">
        <div class="section__head reveal">
            <span class="section__eyebrow">Selected work</span>
            <h2 class="section__title">Systems we've shipped.</h2>
            <p class="section__lead">A glimpse of the digital products and infrastructure we build for ambitious companies.</p>
        </div>
        <div class="portfolio reveal">
            <article class="proj">
                <span class="proj__tag">Platform</span>
                <h3 class="proj__title">Operations Hub</h3>
                <p class="proj__text">An internal platform unifying sales, inventory and reporting into a single source of truth.</p>
            </article>
            <article class="proj">
                <span class="proj__tag">Automation</span>
                <h3 class="proj__title">Lead Engine</h3>
                <p class="proj__text">End-to-end automation capturing, qualifying and routing leads in real time.</p>
            </article>
            <article class="proj">
                <span class="proj__tag">AI</span>
                <h3 class="proj__title">Support Agent</h3>
                <p class="proj__text">An AI assistant resolving customer requests instantly, integrated with existing tools.</p>
            </article>
        </div>
    </div>
</section>

<!-- ================= TECNOLOGÍAS ================= -->
<section class="section section--tight">
    <div class="container">
        <div class="section__head section__head--center reveal">
            <span class="section__eyebrow">Technologies</span>
            <h2 class="section__title">Built on a modern stack.</h2>
        </div>
        <div class="tech-row reveal">
            <?php foreach (['PHP', 'MySQL', 'JavaScript', 'Python', 'Node', 'React', 'OpenAI', 'REST APIs', 'Automation', 'Cloud', 'Docker', 'Linux'] as $tech): ?>
                <span class="tech-pill"><?= htmlspecialchars($tech) ?></span>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ================= CONTACTO + CTA ================= -->
<section class="section" id="contact">
    <div class="container">
        <div class="cta reveal" style="margin-bottom: clamp(2.5rem, 5vw, 4rem);">
            <h2 class="cta__title">Let's build something <span class="text-gradient">valuable</span>.</h2>
            <p class="cta__sub">Tell us where you want to go. We'll design the systems to get you there.</p>
        </div>

        <div class="contact reveal">
            <div>
                <span class="section__eyebrow">Get in touch</span>
                <h2 class="section__title">Start a conversation.</h2>
                <p class="section__lead">Share your project and our team will get back to you shortly. No commitment — just a clear next step.</p>
            </div>
            <div class="contact__panel">
                <?php if ($error): ?>
                    <p class="alert alert--error"><?= htmlspecialchars($error) ?></p>
                <?php endif; ?>
                <?php require __DIR__ . '/components/lead_form.php'; ?>
            </div>
        </div>
    </div>
</section>

<script>
/* Reveal on scroll — sin librerías. */
(function () {
    var els = document.querySelectorAll('.reveal');
    if (!('IntersectionObserver' in window) || !els.length) {
        els.forEach(function (e) { e.classList.add('is-in'); });
        return;
    }
    var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (en) {
            if (en.isIntersecting) { en.target.classList.add('is-in'); io.unobserve(en.target); }
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -8% 0px' });
    els.forEach(function (e) { io.observe(e); });

    /* Glow que sigue al cursor en las cards. */
    document.querySelectorAll('.s-card').forEach(function (card) {
        card.addEventListener('pointermove', function (ev) {
            var r = card.getBoundingClientRect();
            card.style.setProperty('--mx', (ev.clientX - r.left) + 'px');
            card.style.setProperty('--my', (ev.clientY - r.top) + 'px');
        });
    });
})();
</script>
<?php layoutEnd(); ?>

