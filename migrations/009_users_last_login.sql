-- Tracking básico de último login (visible en la lista de usuarios).
-- Se actualiza desde lib/auth.php::login() cuando el password match es exitoso.

ALTER TABLE users
    ADD COLUMN last_login_at DATETIME DEFAULT NULL AFTER is_active;
