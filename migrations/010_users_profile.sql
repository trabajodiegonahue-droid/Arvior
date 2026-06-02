-- Perfil de usuario y flag de cambio forzado de contraseña.
-- - name: opcional, para mostrar en la UI en vez del email cuando esté seteado.
-- - must_change_password: cuando es 1, el usuario es redirigido a "Mi cuenta"
--   y no puede operar el panel hasta cambiarla. Se setea automáticamente al
--   crear un usuario o al resetear su contraseña desde otro admin.

ALTER TABLE users
    ADD COLUMN name VARCHAR(120) NULL AFTER email,
    ADD COLUMN must_change_password TINYINT(1) NOT NULL DEFAULT 0 AFTER is_active;
