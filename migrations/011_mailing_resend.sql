-- Módulo de mailing con soporte para Resend (HTTPS API) además de mail() nativo.
--
-- Variables disponibles en plantillas (notificación interna y auto-respuesta):
--   {{name}}, {{email}}, {{phone}}, {{message}}, {{source}}, {{created_at}},
--   {{admin_url}}, {{site_name}}
--
-- mail_provider: 'mail' (PHP nativo) | 'resend' (API HTTPS).

INSERT INTO settings (setting_key, setting_value) VALUES
    ('mail_provider',         'mail'),
    ('resend_api_key',        ''),
    ('resend_from_name',      ''),
    ('resend_from_email',     ''),
    ('resend_reply_to',       ''),
    ('notification_subject',  '[{{site_name}}] Nuevo lead: {{name}}'),
    ('notification_body',     'Nuevo lead recibido desde {{site_name}}\n\nNombre:   {{name}}\nEmail:    {{email}}\nTeléfono: {{phone}}\nMensaje:\n{{message}}\n\nFuente:   {{source}}\nFecha:    {{created_at}}\n\nVer en admin: {{admin_url}}'),
    ('autoreply_body_html',   '')
ON DUPLICATE KEY UPDATE setting_key = setting_key;
