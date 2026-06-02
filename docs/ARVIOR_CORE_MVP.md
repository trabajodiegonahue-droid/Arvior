# ARVIOR Core — MVP (diseño técnico del motor mínimo viable)

> El plano técnico del motor mínimo para **vender y entregar los primeros Revenue
> Systems** — no el producto final, no enterprise, no multi-tenant avanzado, no 1.000
> clientes. Solo lo necesario para que un puñado de clientes capture leads, reciba
> respuesta < 5 min, tenga seguimiento y un reporte. Escrito como CTO fundador, anclado
> en lo que **ya existe en el repo**, no en lo ideal.
> Última revisión: 2026-06-02 · Estado: construcción / vivo
>
> Qué debe cumplir el motor: [`ARVIOR_REVENUE_SYSTEM.md`](ARVIOR_REVENUE_SYSTEM.md) ·
> El CRM conceptual: [`ARVIOR_CRM_SETUP.md`](ARVIOR_CRM_SETUP.md) ·
> Las decisiones de stack pendientes: [`ARVIOR_EXECUTION_AUDIT.md`](ARVIOR_EXECUTION_AUDIT.md) §7

---

## 0. Principio de diseño del MVP

> **Core MVP no es un producto nuevo: es la mínima extensión del CMS PHP que ya existe,
> más n8n como motor de automatización, para cumplir las 7 capacidades. Se reutiliza
> todo lo que ya funciona (captura, anti-spam, auth, admin, correo). Se construye solo
> lo que falta para multi-cuenta, respuesta < 5 min, seguimiento y reporte. PHP es el
> sistema de registro (la verdad); n8n es el trabajador (la automatización). Si una
> capacidad no está en las 7, no se construye.**

Tres reglas de CTO para esta etapa:

1. **El dato vive en MySQL; la automatización vive en n8n.** PHP nunca "espera" a un
   envío de WhatsApp: lo encola y n8n lo ejecuta. Así un fallo de n8n no pierde el lead.
2. **Multi-cuenta por columna, no por aislamiento.** Una columna `account_id` separa los
   clientes. Sin esquemas por tenant, sin sharding. Suficiente para los primeros 10–20.
3. **Verificar la promesa de punta a punta antes de vender.** La respuesta < 5 min es la
   garantía central; se prueba con un lead real, incluido el camino de fallo, antes de
   prometerla a un cliente.

---

## 1. Arquitectura MVP

### 1.1 El mapa (qué habla con qué)

```
   Landing del cliente (PHP)          WhatsApp del cliente
   formulario + anti-spam                    │ (inbound)
            │ POST /intake?account_key               ▼
            ▼                              ┌──────────────────┐
   ┌─────────────────────────────┐        │      n8n         │
   │   ARVIOR Core (PHP/MySQL)    │        │  (Hostinger/VPS) │
   │                             │        │                  │
   │  • intake endpoint          │──webhook──►• <5 min resp.  │
   │  • leads (con account_id)   │◄─callback──│• secuencias    │
   │  • lead_activities (log)    │        │  • WhatsApp API  │──► WhatsApp
   │  • outbox (cola a n8n)      │        │    (oficial)     │     Oficial
   │  • admin CRM (pipeline)     │        │  • email (Resend)│──► Email
   │  • report (ROI básico)      │        └──────────────────┘
   └─────────────────────────────┘
            ▲
            │ operador ARVIOR (admin existente)
```

**Flujo de la respuesta < 5 min (la promesa central):**

```
 1. Lead entra (form o WhatsApp) ─► PHP valida anti-spam y escribe en `leads`  [durable]
 2. PHP escribe fila en `outbox` (type=first_response, status=pending)         [durable]
 3. PHP hace POST best-effort al webhook de n8n                                [instantáneo]
 4. Si el POST falla → n8n corre un cron cada 1 min que lee `outbox` pendientes [fallback]
 5. n8n envía plantilla de WhatsApp (API oficial) + email                       [< 5 min]
 6. n8n llama de vuelta a Core → escribe `lead_activities` (message_sent)       [registro]
```

> El paso 2 (escribir en `outbox` antes de llamar a n8n) es lo que hace la promesa
> **cumplible**: aunque n8n esté caído o el POST falle, el lead queda encolado y el cron
> lo recoge. Sin esa cola durable, la garantía < 5 min depende de que nada falle —
> inaceptable.

### 1.2 Qué EXISTE hoy en el repo (no se reconstruye)

| Pieza | Estado | Archivo / tabla |
|---|---|---|
| Captura web con **anti-spam** (honeypot + timing + CSRF) | ✅ Funciona | `components/lead_form.php`, handler en `index.php` |
| Tabla `leads` con estados y origen | ✅ Existe (single-site) | `migrations/002_leads.sql` |
| Notas por lead (actividad manual) | ✅ Existe | `migrations/004_lead_notes.sql` |
| Auth, sesiones, CSRF, intentos de login | ✅ Funciona | `lib/auth.php`, `lib/csrf.php` |
| Panel admin | ✅ Base | `admin/index.php`, `components/admin/` |
| Envío de correo (Resend) | ✅ Funciona | `lib/mail.php` |
| Migraciones + bootstrap + DB | ✅ Funciona | `lib/migrate.php`, `lib/db.php`, `lib/bootstrap.php` |
| Info de negocio, sucursales, link WhatsApp (`wa.me`) | ✅ Existe | `lib/business.php`, `components/whatsapp_float.php` |

### 1.3 Qué NO EXISTE (esto es lo que se construye)

| Falta | Por qué es necesario | Capacidad que habilita |
|---|---|---|
| **Concepto de cuenta** (`accounts` + `account_id` en `leads`) | Hoy todo es un solo sitio; hay que operar varios clientes | (7) Operar primeros clientes |
| **Pipeline configurable de estados** (el enum actual ≠ embudo del cliente) | Mover el lead por etapas del cliente | (3) Cambiar estado |
| **Log de actividad automático** (`lead_activities`, no solo notas a mano) | Sin eventos no hay reporte ni trazabilidad | (5) Registrar actividad |
| **Cola de salida** (`outbox`) hacia n8n | Garantía < 5 min cumplible ante fallos | (4) Seguimiento automático |
| **Integración n8n** (webhook salida + callback entrada) | El runtime de automatización (resuelve D3) | (4) Seguimiento |
| **WhatsApp API oficial** (envío plantilla + inbound) | Hoy solo hay un link `wa.me`, no API | (1)(4) Captura y respuesta |
| **Endpoint de intake multi-cuenta** (`account_key`) | Que la landing de cada cliente postee a su cuenta | (1) Capturar |
| **Reporte básico por cuenta** (leads, tiempo de respuesta, embudo) | La prueba de ROI mensual (SOP-09) | (6) Reporte |

### 1.4 Qué se POSTERGA (explícitamente fuera del MVP)

Aislamiento real multi-tenant · scoring/IA · agente conversacional · A/B testing ·
calendario/agenda integrada · facturación automática dentro de Core · login de cliente
al panel · roles y permisos finos · integraciones a CRM externos · alta disponibilidad /
colas externas · cualquier cosa pensada para > 20 cuentas.

> **Regla:** lo postergado no se diseña ahora. Cuando una de las 7 capacidades exija una
> de estas piezas para un cliente real, se evalúa — no antes.

---

## 2. Componentes obligatorios (clasificados)

> "Obligatorio" = sin esto **no se puede vender ni entregar honestamente** el primer
> Revenue System. "Importante" = se entrega sin él a un cliente, pero la operación duele
> rápido. "Futuro" = postergado (§1.4).

### 2.1 Obligatorio (sin esto no hay Revenue System)

| # | Módulo | Qué hace | Apoya capacidad |
|:---:|---|---|---|
| O1 | **Cuentas + scoping** (`accounts`, `account_id`) | Separa los leads de cada cliente | (2)(7) |
| O2 | **Intake multi-cuenta** | Recibe leads de la landing de cada cliente (form + API) | (1) |
| O3 | **Cola de salida (`outbox`)** | Encola la acción durable antes de automatizar | (4) |
| O4 | **n8n conectado** (webhook + cron fallback) | Ejecuta la respuesta < 5 min y secuencias | (4) |
| O5 | **WhatsApp API oficial** (plantilla de 1ª respuesta) | El canal de la garantía < 5 min | (1)(4) |
| O6 | **Log de actividad** (`lead_activities`) | Registra captura, estado, envíos, respuestas | (5) |
| O7 | **CRM admin con cambio de estado** | El operador mueve el lead por el embudo | (2)(3) |
| O8 | **Reporte básico por cuenta** | Leads, tiempo de respuesta, embudo, conversión | (6) |

### 2.2 Importante (se entrega sin él, pero duele pronto)

| Módulo | Por qué importa | Por qué no bloquea el primer cierre |
|---|---|---|
| **Email de respaldo** (Resend) en paralelo a WhatsApp | Si WhatsApp falla/no hay plantilla, el lead igual recibe respuesta | Ya existe `lib/mail.php`; es activarlo en el flujo |
| **Dedup de leads** (mismo teléfono/email en ventana corta) | Evita doble mensaje al mismo lead (form + WhatsApp) | Con 1 cliente y bajo volumen es manejable a mano al inicio |
| **Inbound de WhatsApp → actividad** | Registrar que el lead respondió | El operador puede ver la conversación en WhatsApp directo al inicio |
| **Notificación al operador** de lead nuevo | Que ARVIOR sepa que entró | El reporte y el panel ya lo muestran |

### 2.3 Futuro (postergado — §1.4)

Scoring/IA · agente conversacional · agenda integrada · portal del cliente · roles finos
· integraciones externas · facturación en Core · multi-tenant aislado · alta disponibilidad.

---

## 3. Roadmap de construcción (4 fases, orden exacto)

> El orden no es negociable: cada fase es prerrequisito de la siguiente. **La Fase 2 es
> la que habilita vender honestamente** (la garantía). La verificación de WhatsApp API
> (externa, de días/semanas) se inicia **en paralelo a la Fase 1**, porque su tiempo de
> espera no depende de nosotros (§4, §5).

### Fase 1 — Sistema de registro multi-cuenta `(base de todo)`

**Objetivo:** que los leads de varios clientes entren y se gestionen, sin mezclar.

1. Migración: tabla `accounts` (id, nombre, estado, plan, created_at).
2. Migración: `account_id` en `leads` + `lead_activities` (reemplaza/extiende `lead_notes`)
   + `next_action_at`. Renombrar/mapear el enum de estado al embudo del cliente.
3. Endpoint de intake con `account_key` (la landing de cada cliente postea a su cuenta);
   reutiliza el anti-spam existente.
4. Admin: filtro por cuenta + cambio de estado que **escribe en `lead_activities`**.

**DoD Fase 1:** un lead de la landing de la Cuenta A y otro de la Cuenta B entran, se ven
separados en el admin, y al cambiar de estado se registra la actividad.

### Fase 2 — Respuesta < 5 min `(la promesa — habilita vender)`

**Objetivo:** cumplir la garantía central, de forma durable ante fallos.

1. Migración: tabla `outbox` (id, account_id, lead_id, type, payload, status, attempts,
   created_at, sent_at).
2. Al crear un lead: escribir `outbox` (first_response, pending) **y** hacer POST
   best-effort al webhook de n8n.
3. n8n: flujo que recibe el webhook **y** un cron cada 1 min que procesa `outbox`
   pendientes (fallback).
4. n8n envía la **plantilla de WhatsApp** (API oficial) + email de respaldo, y hace
   callback a Core → `lead_activities` (message_sent) + `outbox.status=sent`.

**DoD Fase 2:** un lead real recibe respuesta automática en < 5 min, **verificado también
cuando el POST instantáneo falla** (probar apagando n8n y dejando que el cron lo recoja).

### Fase 3 — Seguimiento automático + actividad completa

**Objetivo:** que el lead que no responde reciba seguimiento, y que se registre todo.

1. n8n: secuencia de seguimiento (ej. recordatorio a las X horas si no hay respuesta),
   leyendo estado del lead desde Core.
2. Inbound de WhatsApp → webhook n8n → Core (`lead_activities`: replied) → frena la
   secuencia.
3. Dedup básico de leads (mismo teléfono/email en ventana corta).

**DoD Fase 3:** un lead sin respuesta recibe al menos un seguimiento automático; cuando
responde, la secuencia se detiene y queda registrado.

### Fase 4 — Reporte básico (la prueba de ROI)

**Objetivo:** poder entregar el reporte mensual (SOP-09) con datos reales.

1. Consulta/página de reporte por cuenta y período: leads capturados · **tiempo medio de
   primera respuesta** · embudo por estado · conversión.
2. Exportable/presentable (PDF simple o pantalla compartible).

**DoD Fase 4:** se genera, para una cuenta, el reporte de un período con datos reales del
CRM — sin cálculos a mano.

---

## 4. Dependencias (qué depende de qué, qué bloquea qué)

### 4.1 Cadena de dependencias

```
 [EXTERNO] Verificación WhatsApp API oficial + aprobación de plantilla
     │  (días/semanas — se inicia YA, en paralelo a Fase 1)
     ▼
 Fase 1 (accounts + account_id + activity log + intake)   ◄── base de todo
     │
     ▼
 Fase 2 (<5 min: outbox + n8n + WhatsApp) ──── depende de Fase 1 Y de WhatsApp aprobado
     │
     ▼
 Fase 3 (seguimiento + inbound) ──── depende de los canales de Fase 2 + activity log
     │
     ▼
 Fase 4 (reporte) ──── depende del activity log (Fase 1) y de los eventos (Fases 2–3)
```

### 4.2 Tabla de bloqueos

| Esto… | …bloquea… | Por qué |
|---|---|---|
| `accounts` + `account_id` (Fase 1) | Todo lo demás | Sin cuenta no se puede separar ni reportar nada |
| `lead_activities` (Fase 1) | Reporte (Fase 4) y seguimiento (Fase 3) | Sin eventos no hay qué reportar ni con qué frenar secuencias |
| **Verificación WhatsApp API + plantilla** | Respuesta < 5 min (Fase 2) | Sin plantilla aprobada no se puede enviar el 1er mensaje saliente |
| `outbox` + n8n (Fase 2) | La garantía central y, por tanto, **vender honestamente** | La promesa < 5 min no es cumplible sin la cola durable |
| Respuesta < 5 min (Fase 2) | El cierre honesto del primer Revenue System | Es la garantía que se firma (§6) |

> **La dependencia más peligrosa es externa:** la verificación de WhatsApp Business API
> y la aprobación de plantillas dependen de Meta/proveedor, con tiempos de días a semanas.
> **Se inicia el día 1, antes de tener una sola línea de la Fase 2 lista**, o se vuelve el
> cuello de botella del lanzamiento.

---

## 5. Riesgos (solo los reales que impiden cumplir la promesa)

> Riesgos que provocarían vender un Revenue System y **no poder entregarlo**. No mejoras,
> no ideas.

### 🔴 Críticos

- **🔴 WhatsApp Business API no aprobada / sin plantilla a tiempo.** La respuesta < 5 min
  saliente a un lead nuevo exige una **plantilla aprobada** (no se puede mandar texto
  libre fuera de la ventana de 24 h). La verificación de negocio + aprobación de plantilla
  tiene tiempo de espera externo. Si se vende antes de tenerla, no se cumple la garantía.
- **🔴 Cron de Hostinger con granularidad mayor a 5 min.** El fallback de la cola depende
  de un cron de **1 minuto**. En hosting compartido el intervalo mínimo puede ser de 5–15
  min, lo que rompe la garantía < 5 min en el camino de fallo. **Hay que verificar el
  intervalo real del plan antes de prometer.** Si no llega a 1 min, n8n debe correr en un
  VPS/contenedor con su propio scheduler.
- **🔴 PHP en Hostinger sin salida HTTP confiable.** Si `curl`/`allow_url_fopen` están
  restringidos o el firewall de salida bloquea el webhook a n8n, el disparo instantáneo no
  ocurre y todo recae en el cron. **Verificar conectividad saliente del hosting** antes de
  comprometer tiempos.
- **🔴 Sin cola durable (`outbox`), la garantía depende de que nada falle.** Si se
  implementa la respuesta < 5 min llamando a n8n directo sin persistir primero, una caída
  de n8n o un timeout pierde el lead silenciosamente. La `outbox` es obligatoria, no
  opcional.

### 🟡 Importantes

- **🟡 Tabla `leads` single-tenant hoy.** Sin `account_id` los leads de varios clientes
  se mezclan; un reporte podría filtrar datos de otro cliente. Bloquea operar > 1 cuenta.
- **🟡 No hay log de actividad automático.** Sin `lead_activities` no hay tiempo de
  respuesta medible → el reporte de ROI (la prueba de valor que retiene) no tiene datos.
- **🟡 Sin dedup, riesgo de doble mensaje.** Un lead que llega por form y por WhatsApp
  recibe dos respuestas automáticas → se ve poco serio ante el cliente.
- **🟡 Secretos de WhatsApp/n8n en `config.php`.** Está fuera de git (`.gitignore`), pero
  debe ir además a la bóveda (SOP-14); si solo viven en el servidor, hay riesgo de
  continuidad (bus factor).
- **🟡 Sin idempotencia en el callback de n8n.** Si n8n reintenta, puede duplicar
  actividades o envíos. El `outbox` con estado + `attempts` debe controlar reintentos.

### 🟢 Menores

- **🟢 El enum de estado actual** (`new/contacted/qualified/closed/discarded`) no es el
  embudo del cliente; hay que mapearlo. Trivial.
- **🟢 Notificación al operador** de lead nuevo es deseable pero el panel ya lo muestra.
- **🟢 Reporte como pantalla vs. PDF** — formato, no función.

---

## 6. Definición de "listo para vender" (criterios objetivos)

> ARVIOR puede vender **honestamente** el primer Revenue System cuando **todos** estos
> criterios son verdes y verificados con un lead/cuenta reales. No "casi listo": listo.

| # | Criterio objetivo | Cómo se verifica |
|:---:|---|---|
| 1 | Un lead de una landing de cliente se guarda con su `account_id` | Enviar el form → aparece en `leads` con la cuenta correcta |
| 2 | **Respuesta automática entregada en < 5 min** | Lead de prueba → llega WhatsApp+email; medir timestamp |
| 3 | La promesa se cumple **también si el disparo instantáneo falla** | Apagar n8n/webhook → el cron de 1 min entrega igual en < 5 min |
| 4 | WhatsApp API oficial: negocio verificado + **plantilla aprobada** + envío real confirmado | Mensaje recibido en un teléfono real |
| 5 | El operador cambia el estado de un lead y queda registrado | Cambiar estado en admin → fila en `lead_activities` |
| 6 | Un lead sin respuesta recibe **al menos un seguimiento automático** | Lead de prueba sin responder → llega el recordatorio |
| 7 | El reporte por cuenta muestra leads, tiempo de respuesta y embudo con **datos reales** | Generar el reporte de un período de una cuenta |
| 8 | Dos cuentas **no ven los datos de la otra** | Revisar admin/reporte filtrado por `account_id` |
| 9 | Secretos en `config.php` (fuera de git) **y** en la bóveda | Revisión SOP-14 |

**Gating real:** los criterios **2, 3 y 4** son los que separan "vendible" de "no
vendible". Son los que hacen cumplible la garantía firmada. Si esos tres no están verdes,
**no se firma la garantía de respuesta < 5 min** — se vende, si acaso, con go-live por
fases (captura primero, respuesta automática cuando esté), nunca prometiendo lo que aún
no se entrega.

> **Equivalencia con las fases:** listo-para-vender = **Fase 1 + Fase 2 completas y
> verificadas** (criterios 1–5, 8, 9). Las Fases 3 y 4 (criterios 6, 7) se pueden cerrar
> con el primer cliente operando, pero deben estar antes del **primer reporte mensual**,
> o la retención queda sin prueba de ROI.

---

## 7. Resumen ejecutable

1. **Core MVP = el CMS PHP que ya existe + `accounts`/`account_id` + `outbox` + n8n +
   WhatsApp API + log de actividad + reporte.** Nada más (§1).
2. **8 módulos obligatorios** (O1–O8); el resto es importante o futuro (§2).
3. **4 fases en orden:** registro multi-cuenta → respuesta < 5 min → seguimiento →
   reporte. La Fase 2 habilita vender (§3).
4. **La dependencia crítica es externa:** verificación de WhatsApp API + plantilla; se
   inicia el día 1 en paralelo a la Fase 1 (§4).
5. **El riesgo que mata la promesa** es prometer < 5 min sin `outbox` durable, sin cron
   de 1 min verificado, o sin plantilla de WhatsApp aprobada (§5).
6. **Listo para vender = Fases 1+2 verdes y verificadas**, con la garantía < 5 min
   probada de punta a punta, incluido el camino de fallo (§6).

---

## 8. Documentos relacionados

- [`ARVIOR_REVENUE_SYSTEM.md`](ARVIOR_REVENUE_SYSTEM.md) — qué debe hacer el motor que este MVP construye.
- [`ARVIOR_CRM_SETUP.md`](ARVIOR_CRM_SETUP.md) — el pipeline y los campos que el CRM admin instancia.
- [`ARVIOR_DAY_ONE.md`](ARVIOR_DAY_ONE.md) — el Bloque E (entregar la promesa) que este MVP hace real.
- [`ARVIOR_EXECUTION_AUDIT.md`](ARVIOR_EXECUTION_AUDIT.md) §7 — D3 (runtime = n8n) y D4 (WhatsApp) que este diseño resuelve.
- [`ARVIOR_SOPS_MAP.md`](ARVIOR_SOPS_MAP.md) — SOP-08 (go-live), SOP-09 (reporte) que dependen de este motor.
</content>
