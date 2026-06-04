<?php

/**
 * Wrapper de layout para el front público.
 *
 *   layoutStart([
 *       'title'        => 'Servicios',
 *       'description'  => '...',          // meta description / og
 *       'og_image'     => '/uploads/...', // og:image (cae a seo_default_image)
 *       'canonical'    => '/servicios',    // path o URL absoluta
 *       'current_slug' => 'servicios',
 *       'hide_chrome'  => false,           // true = no header ni footer (landing)
 *       'jsonld'       => [...],           // arrays adicionales para inyectar
 *   ]);
 *   // ... contenido de la página ...
 *   layoutEnd();
 *
 * Sin opciones, usa defaults razonables del sitio.
 */

function layoutStart(array $opts = []): void {
    $siteName = (string) getSetting('site_name', 'Mi Sitio');
    $title    = trim((string) ($opts['title'] ?? ''));
    $pageTitle = $title !== '' ? ($title . ' — ' . $siteName) : $siteName;

    $description = trim((string) ($opts['description'] ?? ''));
    if ($description === '') $description = trim((string) getSetting('business_description', ''));

    $ogImage = trim((string) ($opts['og_image'] ?? ''));
    if ($ogImage === '') $ogImage = trim((string) getSetting('seo_default_image', ''));

    $host  = $_SERVER['HTTP_HOST'] ?? '';
    $proto = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $base  = $host ? ($proto . '://' . $host) : '';
    $absolutize = function (string $p) use ($base): string {
        if ($p === '' || preg_match('#^https?://#', $p)) return $p;
        return $base . (str_starts_with($p, '/') ? $p : '/' . $p);
    };

    // Canonical sin query string: evita que /cotizacion?sent=1 se autocanonicalice
    // con parámetros. Si el caller pasa un canonical explícito, se respeta tal cual.
    $canonical = trim((string) ($opts['canonical'] ?? ''));
    if ($canonical === '') {
        $canonical = (string) (parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');
    }
    $canonical = $absolutize($canonical);
    $ogImageAbs = $ogImage !== '' ? $absolutize($ogImage) : '';

    $faviconPath = (string) getSetting('favicon_image', '');
    $faviconAbs  = $faviconPath ? __DIR__ . '/..' . $faviconPath : '';
    $faviconHref = ($faviconPath && @file_exists($faviconAbs))
        ? $faviconPath . '?v=' . @filemtime($faviconAbs) : '';

    $gaId    = trim((string) getSetting('ga_id', ''));
    $pixelId = trim((string) getSetting('pixel_id', ''));

    $hideChrome  = !empty($opts['hide_chrome']);
    $currentSlug = (string) ($opts['current_slug'] ?? '');
    $extraJsonLd = (array)  ($opts['jsonld'] ?? []);

    // Cargar el header/footer disponibles desde el caller.
    $GLOBALS['__layout_opts'] = $opts + ['hide_chrome' => $hideChrome];
    $h = htmlspecialchars(...);
    ?><!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= $h($pageTitle) ?></title>
<?php if ($description): ?>
<meta name="description" content="<?= $h($description) ?>">
<?php endif; ?>
<link rel="canonical" href="<?= $h($canonical) ?>">

<!-- Open Graph -->
<meta property="og:type" content="website">
<meta property="og:site_name" content="<?= $h($siteName) ?>">
<meta property="og:title" content="<?= $h($title !== '' ? $title : $siteName) ?>">
<?php if ($description): ?><meta property="og:description" content="<?= $h($description) ?>"><?php endif; ?>
<meta property="og:url" content="<?= $h($canonical) ?>">
<?php if ($ogImageAbs): ?><meta property="og:image" content="<?= $h($ogImageAbs) ?>"><?php endif; ?>

<!-- Twitter -->
<meta name="twitter:card" content="<?= $ogImageAbs ? 'summary_large_image' : 'summary' ?>">
<meta name="twitter:title" content="<?= $h($title !== '' ? $title : $siteName) ?>">
<?php if ($description): ?><meta name="twitter:description" content="<?= $h($description) ?>"><?php endif; ?>
<?php if ($ogImageAbs): ?><meta name="twitter:image" content="<?= $h($ogImageAbs) ?>"><?php endif; ?>

<?php if ($faviconHref): ?>
<link rel="icon" href="<?= $h($faviconHref) ?>">
<?php endif; ?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap">
<link rel="stylesheet" href="/assets/css/base.css">
<link rel="stylesheet" href="/assets/css/layout.css">
<link rel="stylesheet" href="/assets/css/components.css">
<link rel="stylesheet" href="/assets/css/site.css">
<?php if (!$hideChrome): ?>
<link rel="stylesheet" href="/assets/css/site_header.css">
<?php endif; ?>

<?php if ($gaId): ?>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?= $h($gaId) ?>"></script>
<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','<?= $h($gaId) ?>');</script>
<?php endif; ?>
<?php if ($pixelId): ?>
<script>!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');fbq('init','<?= $h($pixelId) ?>');fbq('track','PageView');</script>
<?php endif; ?>
<?php
if (function_exists('businessJsonLd') && getSetting('business_seo_jsonld', '1') === '1') {
    $ld = businessJsonLd();
    if ($ld) echo '<script type="application/ld+json">' . json_encode($ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "</script>\n";
}
foreach ($extraJsonLd as $extra) {
    if (is_array($extra)) echo '<script type="application/ld+json">' . json_encode($extra, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "</script>\n";
}
?>
</head>
<body class="site-body">
<?php
    if (!$hideChrome) {
        $GLOBALS['currentSlug'] = $currentSlug;
        // Compatibilidad con site_header.php que lee $currentSlug del scope.
        // Aquí estamos en función, así que lo exponemos via include con variables.
        extract(['currentSlug' => $currentSlug]);
        require __DIR__ . '/../components/site_header.php';
    }
}

function layoutEnd(): void {
    $opts = (array) ($GLOBALS['__layout_opts'] ?? []);
    $hideChrome = !empty($opts['hide_chrome']);
    if (!$hideChrome) {
        require __DIR__ . '/../components/site_footer.php';
        if (getSetting('whatsapp_float', '1') === '1') {
            require __DIR__ . '/../components/whatsapp_float.php';
        }
    }
    ?></body>
</html>
<?php
}
