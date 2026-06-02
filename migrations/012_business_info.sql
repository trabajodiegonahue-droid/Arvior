-- Información del negocio (contacto, dirección, redes) + sucursales.
--
-- Todas las claves de contacto/redes viven en `settings` para edición simple
-- desde el panel. Sucursales múltiples van en su propia tabla con orden y
-- toggle de activo, ya que son N filas con CRUD propio.

INSERT INTO settings (setting_key, setting_value) VALUES
    -- Identidad
    ('business_legal_name',    ''),
    ('business_tagline',       ''),
    ('business_description',   ''),
    -- Contacto
    ('business_email',         ''),
    ('business_phone',         ''),
    ('business_whatsapp',      ''),
    ('business_whatsapp_text', 'Hola, vengo desde la web.'),
    -- Dirección principal
    ('business_address',       ''),
    ('business_city',          ''),
    ('business_region',        ''),
    ('business_country',       ''),
    ('business_postal_code',   ''),
    ('business_maps_url',      ''),
    -- Horarios (texto libre, ej. "Lun-Vie 9-18h · Sáb 10-13h")
    ('business_hours',         ''),
    -- Redes
    ('social_facebook',        ''),
    ('social_instagram',       ''),
    ('social_linkedin',        ''),
    ('social_youtube',         ''),
    ('social_tiktok',          ''),
    ('social_x',               ''),
    -- SEO local
    ('business_seo_jsonld',    '1')
ON DUPLICATE KEY UPDATE setting_key = setting_key;

CREATE TABLE IF NOT EXISTS branches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    address VARCHAR(255) NULL,
    city VARCHAR(120) NULL,
    region VARCHAR(120) NULL,
    country VARCHAR(120) NULL,
    postal_code VARCHAR(30) NULL,
    phone VARCHAR(60) NULL,
    whatsapp VARCHAR(30) NULL,
    email VARCHAR(180) NULL,
    hours VARCHAR(255) NULL,
    maps_url VARCHAR(500) NULL,
    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_active (is_active),
    INDEX idx_sort (sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
