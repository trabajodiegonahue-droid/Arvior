-- ARVIOR Core · Fase 3 — Índice compuesto para el dashboard de tareas.
--
-- Las consultas del dashboard y de la vista de tareas filtran por cuenta + estado
-- y ordenan por vencimiento (vencidas / hoy / próximas). Este índice cubre ese
-- patrón (account_id, status, due_at) para no apoyarse solo en idx_task_status_due
-- cuando hay filtro de cuenta activo.
--
-- Idempotente: ADD INDEX falla si ya existe, así que se consulta primero
-- information_schema.STATISTICS y se ejecuta dinámicamente. Seguro porque los
-- nombres de tabla/índice son constantes (no vienen de input).

SET @idx_exists := (
    SELECT COUNT(*) FROM information_schema.STATISTICS
     WHERE TABLE_SCHEMA = DATABASE()
       AND TABLE_NAME = 'tasks'
       AND INDEX_NAME = 'idx_task_account_due'
);
SET @sql := IF(@idx_exists = 0,
    'ALTER TABLE tasks ADD INDEX idx_task_account_due (account_id, status, due_at)',
    'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
