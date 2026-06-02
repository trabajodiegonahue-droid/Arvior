<?php

function clientIp(): string {
    // Hostinger/Cloudflare: preferir cabeceras seteadas por el edge.
    foreach (['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP'] as $h) {
        if (!empty($_SERVER[$h])) {
            $ip = trim(explode(',', $_SERVER[$h])[0]);
            if (filter_var($ip, FILTER_VALIDATE_IP)) return $ip;
        }
    }
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

function flashSet(string $key, string $msg): void {
    $_SESSION['_flash'][$key] = $msg;
}

function flashGet(string $key): ?string {
    $msg = $_SESSION['_flash'][$key] ?? null;
    if ($msg !== null) unset($_SESSION['_flash'][$key]);
    return $msg;
}

function redirect(string $url): void {
    header('Location: ' . $url);
    exit;
}

/**
 * Sanitiza un SVG con allowlist usando DOMDocument.
 * Elimina: <script>, <foreignObject>, <iframe>, <use href=javascript:>,
 * todos los handlers on*, atributos href/xlink:href con javascript:/data:.
 * Devuelve la versión segura o null si no se puede parsear.
 */
function sanitizeSvgString(string $svg): ?string {
    if ($svg === '') return null;
    // Quitar declaraciones que pueden incluir entidades externas (XXE).
    $svg = preg_replace('/<\?xml[^>]*\?>/i', '', $svg) ?? $svg;
    $svg = preg_replace('/<!DOCTYPE[^>]*>/i', '', $svg) ?? $svg;

    $dom = new DOMDocument();
    $prev = libxml_use_internal_errors(true);
    $loadFlags = LIBXML_NONET | (defined('LIBXML_NOENT') ? 0 : 0);
    $ok = $dom->loadXML('<?xml version="1.0" encoding="UTF-8"?>' . $svg, $loadFlags);
    libxml_clear_errors();
    libxml_use_internal_errors($prev);
    if (!$ok || !$dom->documentElement || strtolower($dom->documentElement->nodeName) !== 'svg') {
        return null;
    }

    $blockedTags = ['script', 'foreignobject', 'iframe', 'object', 'embed', 'audio', 'video', 'animate', 'animatetransform', 'animatemotion', 'set'];
    $toRemove    = [];
    $xpath = new DOMXPath($dom);
    foreach ($xpath->query('//*') as $node) {
        /** @var DOMElement $node */
        if (in_array(strtolower($node->nodeName), $blockedTags, true)) {
            $toRemove[] = $node;
            continue;
        }
        if (!$node->hasAttributes()) continue;
        $attrs = [];
        foreach ($node->attributes as $a) $attrs[] = $a;
        foreach ($attrs as $a) {
            $n = strtolower($a->nodeName);
            $v = trim((string) $a->nodeValue);
            // Handlers de eventos.
            if (str_starts_with($n, 'on')) { $node->removeAttributeNode($a); continue; }
            // URLs con schemes peligrosos.
            if (in_array($n, ['href', 'xlink:href', 'src', 'action', 'formaction'], true)) {
                $low = strtolower(preg_replace('/\s+/', '', $v) ?? '');
                if (str_starts_with($low, 'javascript:') || str_starts_with($low, 'data:text/html') || str_starts_with($low, 'vbscript:')) {
                    $node->removeAttributeNode($a);
                }
            }
            // style con expression()/javascript:
            if ($n === 'style' && preg_match('/expression\s*\(|javascript:/i', $v)) {
                $node->removeAttributeNode($a);
            }
        }
    }
    foreach ($toRemove as $n) {
        if ($n->parentNode) $n->parentNode->removeChild($n);
    }
    return $dom->saveXML($dom->documentElement) ?: null;
}

function slugify(string $text): string {
    $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text) ?: $text;
    $text = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $text));
    return trim($text, '-');
}
