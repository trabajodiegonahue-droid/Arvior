<?php

/**
 * Información del negocio: contacto, dirección, redes y sucursales.
 * Pensado para usarse desde el front (footer, contacto, header sticky) y
 * desde plantillas de mailing como variables {{business_*}}.
 */

const BUSINESS_KEYS = [
    'business_legal_name', 'business_tagline', 'business_description',
    'business_email', 'business_phone', 'business_whatsapp', 'business_whatsapp_text',
    'business_address', 'business_city', 'business_region', 'business_country', 'business_postal_code', 'business_maps_url',
    'business_hours',
    'social_facebook', 'social_instagram', 'social_linkedin', 'social_youtube', 'social_tiktok', 'social_x',
    'business_seo_jsonld',
];

const BUSINESS_SOCIAL_KEYS = [
    'social_facebook'  => 'Facebook',
    'social_instagram' => 'Instagram',
    'social_linkedin'  => 'LinkedIn',
    'social_youtube'   => 'YouTube',
    'social_tiktok'    => 'TikTok',
    'social_x'         => 'X / Twitter',
];

/** Devuelve todos los settings de negocio como array key=>string. */
function businessInfo(): array {
    $out = [];
    foreach (BUSINESS_KEYS as $k) {
        $out[$k] = (string) getSetting($k, '');
    }
    return $out;
}

/**
 * Convierte un número de WhatsApp a formato wa.me (sólo dígitos).
 * Acepta cualquier input con '+', espacios, guiones, paréntesis.
 */
function whatsappDigits(string $raw): string {
    return preg_replace('/\D+/', '', $raw) ?? '';
}

/** URL https://wa.me/... con texto pre-cargado, o '' si el número no es válido. */
function whatsappLink(string $raw, string $text = ''): string {
    $digits = whatsappDigits($raw);
    if (strlen($digits) < 7) return '';
    $url = 'https://wa.me/' . $digits;
    if ($text !== '') $url .= '?text=' . rawurlencode($text);
    return $url;
}

/**
 * Devuelve sólo las redes con URL no vacía.
 * @return array<string,array{label:string,url:string}>
 */
function socialLinks(): array {
    $out = [];
    foreach (BUSINESS_SOCIAL_KEYS as $key => $label) {
        $url = trim((string) getSetting($key, ''));
        if ($url !== '') $out[$key] = ['label' => $label, 'url' => $url];
    }
    return $out;
}

/**
 * Páginas publicadas, para usar como menú del front.
 * @return array<int,array{slug:string,title:string}>
 */
function publishedPagesMenu(): array {
    try {
        return getDB()->query(
            'SELECT slug, title FROM pages WHERE is_published = 1 ORDER BY title ASC'
        )->fetchAll();
    } catch (Throwable $e) {
        return [];
    }
}

/* =================== Sucursales =================== */

function branchesAll(): array {
    return getDB()->query(
        'SELECT * FROM branches ORDER BY sort_order ASC, id ASC'
    )->fetchAll();
}

function branchesActive(): array {
    return getDB()->query(
        'SELECT * FROM branches WHERE is_active = 1 ORDER BY sort_order ASC, id ASC'
    )->fetchAll();
}

function branchGet(int $id): ?array {
    if ($id <= 0) return null;
    $stmt = getDB()->prepare('SELECT * FROM branches WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}

/**
 * Crea o actualiza una sucursal a partir de un array (típicamente $_POST).
 * @return array{ok:bool, id?:int, error?:string}
 */
function branchSave(?int $id, array $data): array {
    $name = trim((string) ($data['name'] ?? ''));
    if ($name === '') return ['ok' => false, 'error' => 'El nombre es requerido.'];

    $fields = [
        'name'        => $name,
        'address'     => trim((string) ($data['address']     ?? '')) ?: null,
        'city'        => trim((string) ($data['city']        ?? '')) ?: null,
        'region'      => trim((string) ($data['region']      ?? '')) ?: null,
        'country'     => trim((string) ($data['country']     ?? '')) ?: null,
        'postal_code' => trim((string) ($data['postal_code'] ?? '')) ?: null,
        'phone'       => trim((string) ($data['phone']       ?? '')) ?: null,
        'whatsapp'    => trim((string) ($data['whatsapp']    ?? '')) ?: null,
        'email'       => trim((string) ($data['email']       ?? '')) ?: null,
        'hours'       => trim((string) ($data['hours']       ?? '')) ?: null,
        'maps_url'    => trim((string) ($data['maps_url']    ?? '')) ?: null,
        'sort_order'  => (int) ($data['sort_order'] ?? 0),
        'is_active'   => !empty($data['is_active']) ? 1 : 0,
    ];

    if ($fields['email'] && !filter_var($fields['email'], FILTER_VALIDATE_EMAIL)) {
        return ['ok' => false, 'error' => 'Email de la sucursal inválido.'];
    }

    if ($id && $id > 0) {
        $set = implode(', ', array_map(fn($k) => "$k = ?", array_keys($fields)));
        $stmt = getDB()->prepare("UPDATE branches SET $set WHERE id = ?");
        $stmt->execute([...array_values($fields), $id]);
        return ['ok' => true, 'id' => $id];
    }

    $cols = implode(', ', array_keys($fields));
    $ph   = implode(', ', array_fill(0, count($fields), '?'));
    $stmt = getDB()->prepare("INSERT INTO branches ($cols) VALUES ($ph)");
    $stmt->execute(array_values($fields));
    return ['ok' => true, 'id' => (int) getDB()->lastInsertId()];
}

function branchDelete(int $id): bool {
    if ($id <= 0) return false;
    $stmt = getDB()->prepare('DELETE FROM branches WHERE id = ?');
    return $stmt->execute([$id]);
}

function branchToggleActive(int $id): bool {
    if ($id <= 0) return false;
    $stmt = getDB()->prepare('UPDATE branches SET is_active = 1 - is_active WHERE id = ?');
    return $stmt->execute([$id]);
}

/* =================== JSON-LD para SEO =================== */

/**
 * Devuelve un array LocalBusiness listo para json_encode, o null si no hay
 * datos mínimos. Si hay sucursales activas, se incluyen como `department`.
 */
function businessJsonLd(): ?array {
    $name = trim((string) (getSetting('business_legal_name', '') ?: getSetting('site_name', '')));
    if ($name === '') return null;

    $host  = $_SERVER['HTTP_HOST'] ?? '';
    $proto = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $url   = $host ? ($proto . '://' . $host) : null;

    $ld = [
        '@context' => 'https://schema.org',
        '@type'    => 'LocalBusiness',
        'name'     => $name,
    ];
    if ($url) $ld['url'] = $url;

    $desc = trim((string) getSetting('business_description', ''));
    if ($desc) $ld['description'] = $desc;

    $email = trim((string) getSetting('business_email', ''));
    if ($email) $ld['email'] = $email;

    $phone = trim((string) getSetting('business_phone', ''));
    if ($phone) $ld['telephone'] = $phone;

    $addr = array_filter([
        'streetAddress'   => trim((string) getSetting('business_address', '')),
        'addressLocality' => trim((string) getSetting('business_city', '')),
        'addressRegion'   => trim((string) getSetting('business_region', '')),
        'postalCode'      => trim((string) getSetting('business_postal_code', '')),
        'addressCountry'  => trim((string) getSetting('business_country', '')),
    ]);
    if ($addr) $ld['address'] = ['@type' => 'PostalAddress'] + $addr;

    $hours = trim((string) getSetting('business_hours', ''));
    if ($hours) $ld['openingHours'] = $hours;

    $sames = [];
    foreach (array_keys(BUSINESS_SOCIAL_KEYS) as $k) {
        $v = trim((string) getSetting($k, ''));
        if ($v) $sames[] = $v;
    }
    if ($sames) $ld['sameAs'] = array_values($sames);

    try {
        $branches = branchesActive();
    } catch (Throwable $e) {
        $branches = [];
    }
    if ($branches) {
        $ld['department'] = [];
        foreach ($branches as $b) {
            $dept = [
                '@type' => 'LocalBusiness',
                'name'  => (string) $b['name'],
            ];
            if (!empty($b['phone']))    $dept['telephone'] = (string) $b['phone'];
            if (!empty($b['email']))    $dept['email']     = (string) $b['email'];
            if (!empty($b['hours']))    $dept['openingHours'] = (string) $b['hours'];
            if (!empty($b['maps_url'])) $dept['hasMap']    = (string) $b['maps_url'];
            $deptAddr = array_filter([
                'streetAddress'   => (string) ($b['address']     ?? ''),
                'addressLocality' => (string) ($b['city']        ?? ''),
                'addressRegion'   => (string) ($b['region']      ?? ''),
                'postalCode'      => (string) ($b['postal_code'] ?? ''),
                'addressCountry'  => (string) ($b['country']     ?? ''),
            ]);
            if ($deptAddr) $dept['address'] = ['@type' => 'PostalAddress'] + $deptAddr;
            $ld['department'][] = $dept;
        }
    }

    return $ld;
}
