<?php
require __DIR__ . '/lib/bootstrap.php';

header('Content-Type: application/xml; charset=utf-8');

$host  = $_SERVER['HTTP_HOST'] ?? '';
$proto = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$base  = $proto . '://' . $host;

$urls = [['loc' => $base . '/', 'lastmod' => date('Y-m-d')]];
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
