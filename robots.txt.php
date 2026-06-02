<?php
require __DIR__ . '/lib/bootstrap.php';
header('Content-Type: text/plain; charset=utf-8');

$host  = $_SERVER['HTTP_HOST'] ?? '';
$proto = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
?>User-agent: *
Disallow: /admin/
Disallow: /install/
Disallow: /uploads/logs/

Sitemap: <?= $proto . '://' . $host ?>/sitemap.xml
