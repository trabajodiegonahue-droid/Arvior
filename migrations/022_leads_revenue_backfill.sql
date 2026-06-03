-- ARVIOR Core · Fase 4 — Backfill de won_at / lost_at desde el historial.
--
-- Los leads ya cerrados (status won/lost) antes de Fase 4 no tienen won_at/lost_at.
-- Se reconstruye desde lead_activities: el momento del último status_change que
-- llevó el lead a 'won' (o 'lost'). Así el ciclo de venta y el revenue por
-- período funcionan sobre datos históricos sin tocar el estado del lead.
--
-- Idempotente: solo rellena filas donde won_at/lost_at es NULL y el lead está
-- en ese estado terminal. Re-ejecutar no cambia nada (ya no hay NULLs que tocar).
-- Si un lead cerrado no tuviera ningún status_change registrado, queda NULL
-- (se excluye del ciclo de venta) — decisión consciente para no inventar fechas.

UPDATE leads l
   SET l.won_at = (
        SELECT MAX(a.created_at) FROM lead_activities a
         WHERE a.lead_id = l.id AND a.type = 'status_change' AND a.to_status = 'won'
   )
 WHERE l.status = 'won' AND l.won_at IS NULL;

UPDATE leads l
   SET l.lost_at = (
        SELECT MAX(a.created_at) FROM lead_activities a
         WHERE a.lead_id = l.id AND a.type = 'status_change' AND a.to_status = 'lost'
   )
 WHERE l.status = 'lost' AND l.lost_at IS NULL;
