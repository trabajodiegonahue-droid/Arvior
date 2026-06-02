-- SEO por página + flag para landings sin header/footer.
-- Footer reutilizable: settings de copyright + links legales.
-- Página de agradecimiento seed.

ALTER TABLE pages
    ADD COLUMN og_image VARCHAR(500) NULL AFTER meta_description,
    ADD COLUMN hide_chrome TINYINT(1) NOT NULL DEFAULT 0 AFTER og_image;

INSERT INTO settings (setting_key, setting_value) VALUES
    ('footer_about',       ''),
    ('footer_copyright',   ''),
    ('legal_terms_url',    '/terminos'),
    ('legal_privacy_url',  '/privacidad'),
    ('whatsapp_float',     '1'),
    ('seo_default_image',  '')
ON DUPLICATE KEY UPDATE setting_key = setting_key;

-- Página de agradecimiento. Si ya existe (slug `gracias`), no se sobrescribe.
INSERT INTO pages (slug, title, body, meta_description, hide_chrome, is_published)
VALUES (
    'gracias',
    '¡Gracias por escribirnos!',
    '<p>Recibimos tu consulta y te vamos a responder a la brevedad.</p><p><a href="/">← Volver al inicio</a></p>',
    'Gracias por contactarnos.',
    0,
    1
)
ON DUPLICATE KEY UPDATE slug = slug;
