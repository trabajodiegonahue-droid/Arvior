<?php

function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    }
    return $pdo;
}

/**
 * Lee un setting con cache de TODOS los settings en una sola query por request.
 * Evita N round-trips cuando header/footer/SEO leen ~20 claves.
 */
function getSetting(string $key, ?string $default = null): ?string {
    if (!isset($GLOBALS['__settings_cache']) || $GLOBALS['__settings_cache'] === null) {
        try {
            $rows = getDB()->query('SELECT setting_key, setting_value FROM settings')->fetchAll();
            $GLOBALS['__settings_cache'] = [];
            foreach ($rows as $r) $GLOBALS['__settings_cache'][$r['setting_key']] = $r['setting_value'];
        } catch (Throwable $e) {
            $GLOBALS['__settings_cache'] = [];
        }
    }
    return array_key_exists($key, $GLOBALS['__settings_cache'])
        ? (string) $GLOBALS['__settings_cache'][$key]
        : $default;
}

function setSetting(string $key, string $value): void {
    $stmt = getDB()->prepare(
        'INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)
         ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)'
    );
    $stmt->execute([$key, $value]);
    // Mantener cache consistente.
    if (isset($GLOBALS['__settings_cache']) && is_array($GLOBALS['__settings_cache'])) {
        $GLOBALS['__settings_cache'][$key] = $value;
    }
}
