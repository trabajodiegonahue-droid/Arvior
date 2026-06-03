-- ARVIOR Core · Fase 4 — Bloque monetario en leads (Revenue System).
--
-- Convierte el pipeline en algo medible en dinero. Se agregan:
--   value_amount  — valor estimado/cerrado del deal (moneda global, ver settings).
--   won_at        — cuándo se ganó (para revenue por período y ciclo de venta).
--   lost_at       — cuándo se perdió (para análisis de pérdida).
--   lost_reason   — por qué se perdió (texto corto, opcional).
--
-- Moneda: única y global vía settings.report_currency (default 'CLP'); NO se
-- guarda por lead (multi-moneda queda fuera de Fase 4). Las probabilidades del
-- forecast ponderado son constantes en código (lib/reports.php), sin tabla.
--
-- Idempotente: cada ADD COLUMN / ADD INDEX se consulta antes en
-- information_schema y se ejecuta dinámicamente (mismo patrón que 018/020).
-- Los nombres de columna/índice son constantes (no vienen de input): seguro.

-- value_amount
SET @col := (SELECT COUNT(*) FROM information_schema.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'leads' AND COLUMN_NAME = 'value_amount');
SET @sql := IF(@col = 0,
    'ALTER TABLE leads ADD COLUMN value_amount DECIMAL(12,2) NULL AFTER status',
    'SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

-- won_at
SET @col := (SELECT COUNT(*) FROM information_schema.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'leads' AND COLUMN_NAME = 'won_at');
SET @sql := IF(@col = 0,
    'ALTER TABLE leads ADD COLUMN won_at DATETIME NULL AFTER value_amount',
    'SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

-- lost_at
SET @col := (SELECT COUNT(*) FROM information_schema.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'leads' AND COLUMN_NAME = 'lost_at');
SET @sql := IF(@col = 0,
    'ALTER TABLE leads ADD COLUMN lost_at DATETIME NULL AFTER won_at',
    'SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

-- lost_reason
SET @col := (SELECT COUNT(*) FROM information_schema.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'leads' AND COLUMN_NAME = 'lost_reason');
SET @sql := IF(@col = 0,
    'ALTER TABLE leads ADD COLUMN lost_reason VARCHAR(160) NULL AFTER lost_at',
    'SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

-- Índice para revenue por período: filtra/ordena por won_at en leads ganados.
SET @idx := (SELECT COUNT(*) FROM information_schema.STATISTICS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'leads' AND INDEX_NAME = 'idx_leads_won_at');
SET @sql := IF(@idx = 0,
    'ALTER TABLE leads ADD INDEX idx_leads_won_at (won_at)',
    'SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

-- Moneda global de reportes. Default CLP (decisión Fase 4). Idempotente.
INSERT INTO settings (setting_key, setting_value) VALUES
    ('report_currency', 'CLP')
ON DUPLICATE KEY UPDATE setting_key = setting_key;
