-- ARVIOR Core · Fase 2 — Nota de próxima acción.
--
-- `next_action_at` ya existe desde 015 (Fase 1). Aquí solo agregamos la nota
-- asociada para que el operador registre QUÉ hay que hacer, no solo cuándo.
--
-- Idempotente: ADD COLUMN fallaría si la columna ya existe, así que se guarda
-- tras consultar information_schema y se ejecuta dinámicamente. Seguro porque
-- el nombre de columna es constante (no viene de input).

SET @col_exists := (
    SELECT COUNT(*) FROM information_schema.COLUMNS
     WHERE TABLE_SCHEMA = DATABASE()
       AND TABLE_NAME = 'leads'
       AND COLUMN_NAME = 'next_action_note'
);
SET @sql := IF(@col_exists = 0,
    'ALTER TABLE leads ADD COLUMN next_action_note VARCHAR(500) NULL AFTER next_action_at',
    'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
