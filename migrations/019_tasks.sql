-- ARVIOR Core · Fase 3 — Tareas comerciales internas.
--
-- Una tarea representa algo que el equipo tiene que hacer. Puede estar asociada
-- a un lead (seguimiento concreto) o ser general de una cuenta (operación). Es
-- el ladrillo de la automatización interna: nuevos leads, seguimientos de
-- propuestas y alertas del dashboard se apoyan en esta tabla.
--
-- Relación con `next_action_at` del lead (Fase 1/2): conviven sin duplicar. La
-- próxima acción sigue siendo el "qué sigue" rápido del lead; las tareas son la
-- lista operativa con estado (pendiente/completada/cancelada) y vencimiento.
-- El dashboard muestra ambas, cada una con su propia tarjeta, sin solaparse.
--
-- Idempotente: CREATE TABLE IF NOT EXISTS. Se aplica vía runMigrations() al
-- entrar a /admin/ (nunca desde el endpoint público — misma regla R1).
--
-- ON DELETE CASCADE en lead/account: si se borra el lead o la cuenta, sus
-- tareas se van con él (no quedan tareas huérfanas apuntando a nada).
-- created_by ON DELETE SET NULL: la tarea sobrevive aunque se borre el usuario.

CREATE TABLE IF NOT EXISTS tasks (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    account_id   INT NULL,
    lead_id      INT NULL,
    title        VARCHAR(200) NOT NULL,
    description  TEXT NULL,
    status       ENUM('pending','completed','cancelled') NOT NULL DEFAULT 'pending',
    due_at       DATETIME NULL,
    completed_at DATETIME NULL,
    created_by   INT NULL,
    created_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_task_account_status (account_id, status),
    INDEX idx_task_lead_status (lead_id, status),
    INDEX idx_task_status_due (status, due_at),
    CONSTRAINT fk_task_account FOREIGN KEY (account_id) REFERENCES accounts(id) ON DELETE CASCADE,
    CONSTRAINT fk_task_lead    FOREIGN KEY (lead_id)    REFERENCES leads(id)    ON DELETE CASCADE,
    CONSTRAINT fk_task_user    FOREIGN KEY (created_by) REFERENCES users(id)    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
