-- ARVIOR Core · Fase 1 — Log de actividad por lead (generaliza lead_notes).
--
-- Cada evento del lead es una fila: creación, cambio de estado, nota.
-- type:
--   'created'        — el lead entró (form propio o intake de cuenta)
--   'status_change'  — el operador movió el lead (from_status → to_status)
--   'note'           — nota manual del operador
--   (Fase 2+ agregará 'message_sent', 'replied', etc. sin migrar de nuevo.)
--
-- user_id NULL = acción del sistema (ej. 'created' desde el intake público).
-- `meta` JSON queda disponible para payloads futuros (Fase 2) sin re-migrar.
--
-- Las notas históricas de lead_notes se copian aquí (type='note'). NO se
-- dropea lead_notes todavía (transición segura — riesgo R4 del plan).

CREATE TABLE IF NOT EXISTS lead_activities (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    lead_id     INT NOT NULL,
    account_id  INT NULL,
    user_id     INT NULL,
    type        VARCHAR(40) NOT NULL DEFAULT 'note',
    from_status VARCHAR(40) NULL,
    to_status   VARCHAR(40) NULL,
    body        TEXT NULL,
    meta        JSON NULL,
    created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_activity_lead (lead_id, created_at),
    INDEX idx_activity_account_type (account_id, type, created_at),
    CONSTRAINT fk_activity_lead    FOREIGN KEY (lead_id)    REFERENCES leads(id)    ON DELETE CASCADE,
    CONSTRAINT fk_activity_account FOREIGN KEY (account_id) REFERENCES accounts(id) ON DELETE SET NULL,
    CONSTRAINT fk_activity_user    FOREIGN KEY (user_id)    REFERENCES users(id)    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Migrar notas históricas. Idempotente: solo copia las que aún no estén en
-- lead_activities (mismo lead, mismo body, mismo timestamp).
INSERT INTO lead_activities (lead_id, account_id, user_id, type, body, created_at)
SELECT n.lead_id, l.account_id, n.user_id, 'note', n.body, n.created_at
  FROM lead_notes n
  JOIN leads l ON l.id = n.lead_id
 WHERE NOT EXISTS (
       SELECT 1 FROM lead_activities a
        WHERE a.lead_id = n.lead_id
          AND a.type = 'note'
          AND a.body = n.body
          AND a.created_at = n.created_at
 );
