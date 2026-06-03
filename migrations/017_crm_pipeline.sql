-- ARVIOR Core · Fase 2 — Pipeline CRM: ampliación del ENUM de estados.
--
-- Fase 1 dejó status = ENUM('new','contacted','qualified','closed','discarded').
-- Fase 2 opera un pipeline real de ventas. Ampliamos el ENUM con los estados
-- operativos nuevos SIN eliminar los legacy: así ningún lead histórico que aún
-- tenga 'qualified'/'closed'/'discarded' se rompe ni cambia de valor.
--
-- Mapeo de presentación (legacy → pipeline) se hace en la UI/labels, no en BD,
-- para no perder el dato original de leads ya clasificados.
--
-- MODIFY COLUMN es idempotente por naturaleza: re-aplicarlo deja el mismo ENUM.
-- El DEFAULT 'new' se mantiene. No se toca ninguna fila.

ALTER TABLE leads
    MODIFY COLUMN status ENUM(
        'new',
        'contacted',
        'meeting_scheduled',
        'proposal_sent',
        'negotiation',
        'won',
        'lost',
        'qualified',
        'closed',
        'discarded'
    ) NOT NULL DEFAULT 'new';

-- Índice por próxima acción: el dashboard CRM consulta vencidas/hoy y la lista
-- filtra pendientes. Idempotente vía information_schema (ADD INDEX fallaría si
-- ya existe).
SET @idx_exists := (
    SELECT COUNT(*) FROM information_schema.STATISTICS
     WHERE TABLE_SCHEMA = DATABASE()
       AND TABLE_NAME = 'leads'
       AND INDEX_NAME = 'idx_next_action'
);
SET @sql := IF(@idx_exists = 0,
    'ALTER TABLE leads ADD INDEX idx_next_action (next_action_at)',
    'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
