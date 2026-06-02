-- Settings de marca: logo del header del admin/sitio y favicon.
-- Se guardan como rutas relativas (ej: /uploads/brand/logo.png).

INSERT INTO settings (setting_key, setting_value) VALUES
    ('logo_image', ''),
    ('favicon_image', '')
ON DUPLICATE KEY UPDATE setting_key = setting_key;
