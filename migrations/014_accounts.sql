-- ARVIOR Core · Fase 1 — Cuentas (multi-cuenta por columna).
--
-- Una cuenta = un cliente cuyo formulario/landing captura leads hacia ARVIOR.
-- `public_token` es el secreto opaco que la landing externa usa en el intake
-- (NO el slug). Se genera con random_bytes en lib/accounts.php::accountCreate().
--
-- Idempotente: CREATE IF NOT EXISTS + seed con ON DUPLICATE KEY.
-- Se aplica vía runMigrations() (corre al entrar a /admin/), nunca desde el
-- endpoint público (decisión R1 del plan de Fase 1).

CREATE TABLE IF NOT EXISTS accounts (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    name         VARCHAR(150) NOT NULL,
    slug         VARCHAR(160) NOT NULL,
    public_token VARCHAR(80)  NOT NULL,
    status       ENUM('active','paused','archived') NOT NULL DEFAULT 'active',
    plan         VARCHAR(60)  NULL,
    created_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uniq_account_slug  (slug),
    UNIQUE KEY uniq_account_token (public_token),
    INDEX idx_account_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Cuenta interna de ARVIOR: a ella se asignan los leads del sitio propio y
-- los históricos (backfill en 015). public_token fijo y conocido SOLO para la
-- cuenta interna; las cuentas de clientes reciben tokens aleatorios desde el admin.
INSERT INTO accounts (name, slug, public_token, status, plan)
VALUES ('ARVIOR', 'arvior', 'arvior-internal', 'active', 'internal')
ON DUPLICATE KEY UPDATE slug = slug;
