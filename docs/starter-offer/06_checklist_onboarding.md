# Checklist de onboarding — primer cliente Starter

> Activo comercial/operativo · Fase 5
> Objetivo: del **"avancemos"** al **sistema operando** sin improvisar. Pensado para
> ejecutarse en {{5–10}} días hábiles. Marcar `[x]` a medida que se completa.

---

## Fase 0 — Cierre y kickoff (Día 0)

- [ ] Propuesta firmada / "avancemos" por escrito.
- [ ] **Primer pago** recibido (50% Build).
- [ ] Crear carpeta del cliente (contrato, accesos, notas, assets).
- [ ] Agendar **kickoff** (30 min) y la **sesión de capacitación** (al entregar).
- [ ] Enviar **formulario de intake del cliente** (datos que necesitamos, abajo §A).

## Fase 1 — Recolección de insumos (Día 0–2)

- [ ] Datos del negocio: razón social, dirección, teléfono, horarios, redes.
- [ ] Logo (vectorial/PNG), colores, favicon.
- [ ] Textos base por página (o material para redactarlos).
- [ ] Email de notificación de leads + email "from" para autorespuesta.
- [ ] Listado actual de leads/clientes a importar (1 CSV simple).
- [ ] Dominio: ¿tiene? ¿acceso DNS? ¿o usamos subdominio nuestro al inicio?
- [ ] **Ticket promedio** y meta de leads/mes (para configurar reportes/relato).

## Fase 2 — Provisión técnica (Día 1–3)

- [ ] Hosting Hostinger/cPanel listo (PHP 8 + MySQL).
- [ ] Base de datos creada + `config.php` cargado.
- [ ] Deploy del Core (rama `main`).
- [ ] Primera visita a `/admin/` → **migraciones 001–022 aplicadas** automáticamente.
- [ ] Crear **usuario admin** del cliente (y uno propio de ARVIOR).
- [ ] HTTPS activo; verificar cookies seguras.

## Fase 3 — Configuración del sistema (Día 2–5)

- [ ] Branding aplicado (logo, favicon, colores, datos de negocio).
- [ ] **Moneda = CLP** confirmada (Reportes).
- [ ] Páginas publicadas (inicio + hasta 5) incluida la **página comercial** si aplica.
- [ ] Formulario de leads probado (anti-spam ok, llega notificación).
- [ ] Autorespuesta al prospecto configurada y testeada.
- [ ] Cuenta del cliente creada; token público de la landing configurado.
- [ ] **Importar el CSV** de leads existentes (1 carga).
- [ ] Cargar valor/etapa a algunos leads importados para que el reporte tenga sentido.

## Fase 4 — QA de extremo a extremo (Día 4–6)

- [ ] Crear un lead de prueba real desde la landing → aparece en el panel.
- [ ] Verificar aviso por email + autorespuesta.
- [ ] Mover el lead por el embudo; completar una tarea; cargar valor; marcar ganado.
- [ ] Abrir **Reportes**: KPIs, embudo, forecast y export CSV correctos.
- [ ] Borrar los datos de prueba (dejar solo los reales importados).
- [ ] Revisar en móvil (landing + panel).

## Fase 5 — Entrega y capacitación (Día 5–8)

- [ ] **Segundo pago** (50% Build) al entregar.
- [ ] Sesión de capacitación en vivo (≤60 min) + grabación enviada.
- [ ] Mini-guía de uso entregada (cómo ver leads, mover etapas, leer el reporte).
- [ ] Confirmar accesos del cliente y canal de soporte (email/WhatsApp + SLA).
- [ ] Dejar agendado el **1er reporte mensual** (fecha fija cada mes).

## Fase 6 — Inicio de Operate (Mes 1)

- [ ] Cobro de **Operate** del mes 1 (o anual bonificado).
- [ ] Monitoreo activo: captura entrando, tareas/leads sin actividad bajo control.
- [ ] Respaldo de base de datos programado.
- [ ] **Reporte mensual #1** entregado + llamada/lectura de 15 min.
- [ ] Detectar y anotar señales de **upsell** (WhatsApp/IA/2ª cuenta).

---

## §A. Formulario de intake del cliente (lo que pedimos el Día 0)

1. Nombre del negocio y cómo querés que aparezca.
2. Logo + colores (o "elíjanlos ustedes").
3. ¿Qué vendés y cuál es tu cliente ideal?
4. ¿Cómo te llegan hoy los clientes? (canales)
5. Ticket promedio aproximado y meta de consultas/mes.
6. Email donde querés recibir el aviso de cada lead.
7. ¿Tenés dominio? ¿Acceso para configurarlo?
8. Listado de clientes/leads actuales (Excel/CSV), si lo tenés.

## §B. Definición de "cliente activado" (éxito del onboarding)

El onboarding se considera exitoso cuando, en el **mes 1**:
- el cliente recibió **leads reales** por el sistema, y
- recibió y entendió su **primer reporte mensual**, y
- no hubo incidentes de captura abiertos.

> Si esos tres se cumplen, la probabilidad de renovación de Operate sube fuerte.
> Es la métrica a vigilar en los primeros 10 clientes.
