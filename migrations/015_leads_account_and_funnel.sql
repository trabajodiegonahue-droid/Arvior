-- ARVIOR Core · Fase 1 — Multi-cuenta en leads + campo de embudo (Fase 2).
--
-- Decisión D1: el embudo sigue siendo el ENUM `status` actual (mapeado, no
-- configurable). Aquí NO se toca `status`; solo se agrega el scoping por cuenta.
--
-- `next_action_at` se crea ya (lo consume el seguimiento de Fase 3) para no
-- re-migrar la tabla `leads` dos veces.
--
-- Orden importante:
--   1) agregar columnas NULL,
--   2) backfill de los leads históricos a la cuenta interna ARVIOR,
--   3) índice + FK (con la columna ya poblada, sin filas huérfanas).
-- account_id queda NULL-able a propósito (defensa ante inserts viejos); el
-- aislamiento real lo garantiza el código que siempre filtra por account_id.

ALTER TABLE leads
    ADD COLUMN account_id INT NULL AFTER id,
    ADD COLUMN next_action_at DATETIME NULL AFTER status;

-- Backfill: todo lead previo pasa a la cuenta interna ARVIOR.
UPDATE leads
   SET account_id = (SELECT id FROM accounts WHERE slug = 'arvior' LIMIT 1)
 WHERE account_id IS NULL;

ALTER TABLE leads
    ADD INDEX idx_account_status (account_id, status),
    ADD CONSTRAINT fk_leads_account
        FOREIGN KEY (account_id) REFERENCES accounts(id) ON DELETE SET NULL;
