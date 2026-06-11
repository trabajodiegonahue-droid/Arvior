<?php
require __DIR__ . '/lib/bootstrap.php';

header('Content-Type: application/xml; charset=utf-8');

$host  = $_SERVER['HTTP_HOST'] ?? '';
$proto = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$base  = $proto . '://' . $host;

$today = date('Y-m-d');
$urls  = [['loc' => $base . '/', 'lastmod' => $today]];

// Rutas estáticas del portal comercial (no viven en la tabla `pages`).
foreach (['servicios', 'proyectos', 'proceso', 'cotizacion', 'contacto'] as $p) {
    $urls[] = ['loc' => $base . '/' . $p, 'lastmod' => $today];
}
// Detalle de cada servicio: /servicios/{slug}.
if (function_exists('portalServices')) {
    foreach (portalServices() as $s) {
        if (!empty($s['slug'])) {
            $urls[] = ['loc' => $base . '/servicios/' . $s['slug'], 'lastmod' => $today];
        }
    }
}

// Detalle de cada proyecto publicado del portafolio: /proyectos/{slug}.
if (function_exists('portfolioProjects')) {
    foreach (portfolioProjects(null, true) as $p) {
        if (!empty($p['slug'])) {
            $urls[] = [
                'loc'     => $base . '/proyectos/' . $p['slug'],
                'lastmod' => substr((string) ($p['updated_at'] ?? $today), 0, 10),
            ];
        }
    }
}

// Páginas del CMS publicadas.
try {
    foreach (getDB()->query('SELECT slug, updated_at FROM pages WHERE is_published = 1 ORDER BY updated_at DESC')->fetchAll() as $p) {
        $urls[] = [
            'loc'     => $base . '/' . $p['slug'],
            'lastmod' => substr((string) $p['updated_at'], 0, 10),
        ];
    }
} catch (Throwable $e) { /* tabla todavía no migrada */ }

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach ($urls as $u) {
    echo "  <url>\n";
    echo '    <loc>' . htmlspecialchars($u['loc']) . "</loc>\n";
    echo '    <lastmod>' . htmlspecialchars($u['lastmod']) . "</lastmod>\n";
    echo "  </url>\n";
}
echo "</urlset>\n";
