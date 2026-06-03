# ARVIOR Core — Fase 1 · Plan técnico de implementación

> **Alcance:** únicamente la **Fase 1** del Core MVP — *sistema de registro multi-cuenta*.
> No incluye respuesta < 5 min (Fase 2), seguimiento (Fase 3) ni reporte (Fase 4).
> Este documento es **solo plan**: no se escribe código, no se crean migraciones ni
> tablas hasta su aprobación.
>
> Fuente de verdad del diseño: [`ARVIOR_CORE_MVP.md`](ARVIOR_CORE_MVP.md) §3 (Fase 1).
> Anclado en inspección real del repo en el commit `dcee331` (no en supuestos).
> Última revisión: 2026-06-02 · Estado: plan / pendiente de aprobación.

---

## 0. Resumen de la Fase 1

**Objetivo (del MVP §3):** que los leads de varios clientes entren y se gestionen, sin
mezclarse.

**DoD del MVP §3 para esta fase:** un lead de la landing de la Cuenta A y otro de la
Cuenta B entran, se ven separados en el admin, y al cambiar de estado se registra la
actividad.

Lo que la Fase 1 entrega, en una frase: **multi-cuenta por columna `account_id`**, un
**endpoint de intake público con `account_key`**, un **log de actividad automático**
(`lead_activities`) y un **admin que filtra por cuenta y registra cada cambio de estado**.
Nada de n8n, nada de WhatsApp, nada de outbox — eso es Fase 2.

---

## 1. Qué YA EXISTE (inspeccionado, reutilizable)

> Verificado leyendo los archivos, no la documentación. Referencias con `archivo:línea`.

### 1.1 Esquema de base de datos (`migrations/`)

| Tabla | Archivo | Relevante para Fase 1 |
|---|---|---|
| `users` (+ `name`, `is_active`, `must_change_password`, `last_login_at`) | `migrations/001`,`009`,`010` | El operador ARVIOR; FK de autoría en actividades |
| `settings` (key/value, cache global por request) | `migrations/001` | Config; patrón a reutilizar, **no** para datos por-cuenta |
| `leads` (id, name, email, phone, message, source, **status ENUM**, notes, ip, ua, created_at) | `migrations/002` | **Se extiende** con `account_id` y campos de embudo |
| `login_attempts` | `migrations/003` | Rate-limit de login; intacto |
| `lead_notes` (lead_id, user_id, body, created_at, FKs CASCADE/SET NULL) | `migrations/004` | **Modelo a generalizar** hacia `lead_activities` |
| `pages`, `media_folders`, `media_library`, `branches` | `006`,`007`,`012` | No tocar en Fase 1 |
| `migrations` (registro idempotente) | `lib/migrate.php` | El runner que aplicará las nuevas migraciones |

**Estado actual de `leads` (`migrations/002_leads.sql`):**
- `status ENUM('new','contacted','qualified','closed','discarded') DEFAULT 'new'`
- Índices: `idx_status`, `idx_created`, `idx_email`.
- **Single-tenant**: no hay noción de cuenta. Es el cambio central de la fase.

### 1.2 Captura de leads (web) — `index.php:41-98`

- Handler `action=submit_lead` con **anti-spam ya resuelto**:
  - honeypot (`website`), timing (`form_started`, < 2s = bot) → `index.php:43-49`
  - CSRF (`csrfCheck()`) → `index.php:51`
  - validación nombre + email → `index.php:58`
  - **dedup por email en ventana de 5 min** → `index.php:62-70`
- `INSERT INTO leads (...)` con `clientIp()` y user-agent → `index.php:72-81`
- Dispara `notifyLeadCreated()` y `sendLeadAutoReply()` en try/catch que no rompe el flujo
  → `index.php:92-94`
- Form reutilizable con parámetro `$leadSource` → `components/lead_form.php`

> **Reutilizable casi tal cual.** El intake multi-cuenta es una **generalización** de este
> handler: mismas defensas anti-spam, mismo `INSERT`, + `account_id` resuelto desde
> `account_key` + salida JSON cuando es API.

### 1.3 Auth, sesión, CSRF — `lib/auth.php`, `lib/csrf.php`, `lib/bootstrap.php`

- Login con rate-limit, sesiones endurecidas (httponly, samesite, strict, idle timeout),
  `currentUser()`, `must_change_password`. Intacto en Fase 1.
- `csrfToken()` / `csrfCheck()` → se reutiliza en el form web del intake.

### 1.4 Panel admin — `admin/index.php` + `components/admin/`

- Router por `action` (POST) + `view` (GET) → `admin/index.php:22`
- **`runMigrations()` se ejecuta solo para usuarios autenticados** al entrar a `/admin/`
  → `admin/index.php:48`. ⚠️ Ver Riesgo R1 (el intake es público).
- Acciones de lead existentes:
  - `update_lead_status` → `admin/index.php:80-87` (UPDATE directo, **sin log**)
  - `add_note` → `admin/index.php:89-100` (escribe `lead_notes`)
  - `delete_lead` → `admin/index.php:102-108`
  - `export_csv` con filtros search/status → `admin/index.php:188-220`
- Listado + stats + paginación de leads → `admin/index.php:582-617`
- Vistas: `dashboard.php`, `lead_detail.php` (lista notas, cambia estado).
- Patrón consistente: `csrfCheck()` → mutación → `flashSet()` → `redirect()`.

### 1.5 Infraestructura — `lib/`

| Pieza | Archivo | Uso en Fase 1 |
|---|---|---|
| `getDB()` PDO singleton (excepciones, prepares reales) | `lib/db.php:1-14` | Todas las queries |
| `getSetting()` / `setSetting()` (cache por request) | `lib/db.php:18-49` | Config; **no** para `account_key` (ver §2) |
| `runMigrations()` idempotente, ignora `_*.sql` | `lib/migrate.php` | Aplica las migraciones nuevas |
| `clientIp()`, `slugify()`, `redirect()`, `flashSet/Get()` | `lib/helpers.php` | Intake + admin |
| `notifyLeadCreated()` / `sendLeadAutoReply()` (Resend/mail) | `lib/mail.php` | Se invocan igual; **no** son Fase 1 pero conviven |
| Deploy: cPanel + `git pull`; migraciones al visitar `/admin/` | `DEPLOY.md` | Restricción operativa real (R1, R5) |

### 1.6 Conclusión de la inspección

Lo que la Fase 1 reutiliza **sin reescribir**: anti-spam, CSRF, auth, PDO, settings,
runner de migraciones, patrón de acciones admin, `lead_notes` como modelo, `lib/mail.php`.
Lo único que se **generaliza** (no se reescribe desde cero): el `INSERT` de leads y la
acción `update_lead_status`.

---

## 2. Qué DEBEMOS CONSTRUIR (solo Fase 1)

> Mapeo a los módulos obligatorios del MVP §2.1: **O1** (cuentas), **O2** (intake),
> **O6** (log de actividad), **O7** (CRM con cambio de estado). **O3–O5, O8 son Fase 2–4
> y quedan fuera.**

### 2.1 Migraciones nuevas (3 archivos, en orden)

**`migrations/014_accounts.sql`** — tabla `accounts` (módulo O1)
- Campos propuestos: `id`, `name`, `slug`, **`account_key` (único, indexado)**,
  `status ENUM('active','paused','archived') DEFAULT 'active'`, `plan VARCHAR`,
  `created_at`.
- `account_key`: token opaco e impredecible (no el slug) que la landing del cliente usa en
  el intake. Se genera con `bin2hex(random_bytes(...))` al crear la cuenta.
- Seed: una cuenta `ARVIOR` interna para no romper el flujo del sitio propio.

**`migrations/015_leads_account_and_funnel.sql`** — multi-cuenta + embudo (O1)
- `ALTER TABLE leads ADD COLUMN account_id INT NULL AFTER id` + índice
  `idx_account_status (account_id, status)` + FK a `accounts(id)`.
  - `NULL` transitorio para filas viejas; se backfillea a la cuenta interna en la misma
    migración (`UPDATE leads SET account_id = <interna> WHERE account_id IS NULL`).
- `ADD COLUMN next_action_at DATETIME NULL` (lo consume Fase 3; se crea ya para no
  re-migrar `leads` dos veces).
- **Decisión de embudo (D1, ver §5):** el `status` actual es ENUM fijo. El MVP pide
  "pipeline configurable". Para Fase 1 se propone **mapear**, no rehacer: mantener el ENUM
  y documentar el mapeo a etapas del cliente; la configurabilidad real se evalúa cuando un
  cliente lo exija (MVP §1.4). *Requiere tu aprobación — ver §5 D1.*

**`migrations/016_lead_activities.sql`** — log de actividad (O6)
- Tabla `lead_activities`: `id`, `lead_id` (FK CASCADE), `account_id`, `user_id`
  (FK SET NULL, NULL = sistema), **`type`** (`status_change`,`note`,`created`,…),
  `from_status`, `to_status`, `body TEXT`, `meta JSON NULL`, `created_at`.
  Índice `(lead_id, created_at)` y `(account_id, type, created_at)`.
- **Generaliza `lead_notes`**: una nota es `type='note'`. Se migran las filas de
  `lead_notes` a `lead_activities` (sin borrar `lead_notes` todavía; ver §3 paso 7 y R4).

### 2.2 Endpoint de intake nuevo (módulo O2)

**`intake.php`** (público, en la raíz) — recibe leads de la landing de cada cliente.
- Resuelve `account_id` desde `account_key` (query o body); si la cuenta no existe o está
  `paused/archived` → rechazo silencioso (no filtrar existencia de cuentas).
- **Reutiliza el anti-spam existente** (honeypot + timing + CSRF para el form;
  para la API por `account_key` el CSRF no aplica, ver D2 §5) y el **dedup por email/teléfono
  dentro de la cuenta** (extiende la query de `index.php:62-70` con `AND account_id = ?`).
- Dos modos de respuesta:
  - `form` → redirect a `/gracias` (igual que hoy).
  - `api`/`fetch` → `Content-Type: application/json` (patrón ya usado en
    `media_upload_inline`, `admin/index.php:394-409`).
- Escribe en `leads` con `account_id` y registra `lead_activities(type='created')`.
- **No** dispara n8n ni outbox (eso es Fase 2). Sí puede seguir disparando el mail
  existente, sin bloquear.

> El handler `submit_lead` de `index.php` (sitio propio de ARVIOR) se mantiene, pero pasa a
> asignar `account_id` = cuenta interna. Idealmente se refactoriza la lógica común
> (validación + dedup + insert + activity) a una función `leadCreate()` en una lib nueva
> (`lib/leads.php`) que **ambos** (intake.php e index.php) invoquen — evita duplicar el
> anti-spam. *Decisión menor, dentro del alcance.*

### 2.3 Cambios en el admin (módulo O7)

- **Filtro por cuenta**: selector de cuenta en el listado + `WHERE account_id = ?` en
  todas las queries de leads (`admin/index.php:582-617`, stats, export CSV, detalle).
  Por defecto: todas las cuentas del operador, o una seleccionada.
- **`update_lead_status` escribe actividad**: reemplazar el `UPDATE` suelto
  (`admin/index.php:80-87`) por: leer estado anterior → UPDATE → `INSERT lead_activities
  (type='status_change', from_status, to_status, user_id)`. **Este es el corazón del DoD.**
- **`add_note`** pasa a escribir `lead_activities(type='note')` en vez de `lead_notes`
  (o ambos durante la transición — ver R4).
- **`lead_detail.php`**: la sección de notas pasa a ser un **timeline de actividad**
  (notas + cambios de estado + creación), ordenado por fecha.
- **Vista nueva `view=accounts`**: CRUD mínimo de cuentas (alta, ver `account_key`,
  pausar/archivar). Reutiliza el patrón de `branches`/`users` (`business.php`,
  `users_list.php`).
- Componente nuevo: `components/admin/accounts_list.php` (+ edición inline o `account_edit.php`).
- `admin_nav.php`: agregar enlace "Cuentas".

### 2.4 Lib nueva

- `lib/accounts.php`: `accountResolveByKey()`, `accountCreate()` (genera `account_key`),
  `accountsAll()`, `accountSetStatus()`. Cargada desde `lib/bootstrap.php`.
- `lib/leads.php` (recomendado): `leadCreate()` + `leadLogActivity()` compartidos por
  intake y admin. Evita duplicar el anti-spam/insert.

### 2.5 Lo que **NO** se construye en Fase 1 (queda para fases siguientes)

`outbox` · webhook/cron n8n · WhatsApp API · email de respuesta < 5 min · secuencias de
seguimiento · inbound · reporte/ROI · cron jobs · portal de cliente.
**Cron jobs e integración n8n/WhatsApp del pedido original pertenecen a Fase 2–3, no a
Fase 1** (MVP §3). Se mencionan aquí solo para dejar explícito que están fuera de alcance.

---

## 3. Qué NO DEBEMOS TOCAR

> Código estable, crítico o sin relación con la Fase 1. Modificarlo es introducir riesgo
> sin beneficio.

| No tocar | Por qué |
|---|---|
| `lib/auth.php`, `lib/csrf.php`, `login_attempts` | Seguridad de sesión probada; fuera de alcance |
| `lib/db.php` (PDO + settings cache) | Base de todo; estable. Solo **usarlo**, no modificarlo |
| `lib/migrate.php` | El runner funciona; las migraciones nuevas son archivos, no cambios al runner |
| `migrations/001`–`013` (ya aplicadas) | **Inmutables.** Toda evolución va en `014+`. Editar una migración aplicada no re-corre |
| `lib/media_library.php`, `image_pipeline.php`, vistas de media | Sin relación con leads |
| `lib/business.php`, `branches`, `pages`, vistas de páginas/negocio | Sin relación con Fase 1 |
| `components/whatsapp_float.php` (link `wa.me`) | Es UI del sitio, **no** es la WhatsApp API (esa es Fase 2) |
| `install/index.php`, `DEPLOY.md`, `.htaccess` | Flujo de instalación/deploy estable |
| Anti-spam de `index.php` (honeypot/timing/CSRF) | **Reutilizar la lógica, no debilitarla.** El intake hereda estas defensas |

> Regla: la Fase 1 es **aditiva**. Migraciones nuevas (`014+`), archivos nuevos
> (`intake.php`, `lib/accounts.php`, `lib/leads.php`, componentes admin de cuentas) y
> **ediciones quirúrgicas** en `index.php` (insert con `account_id`) y `admin/index.php`
> (status con log + filtro de cuenta). Nada más.

---

## 4. Roadmap técnico (orden exacto, con dependencias)

> Cada paso es prerrequisito del siguiente. El orden minimiza el tiempo en que el esquema
> queda a medias y permite verificar incrementalmente.

```
 1 ─► 2 ─► 3 ─► 4 ─► 5 ─► 6 ─► 7 ─► 8
 │                        │         │
 │ (validación temprana   │         └─ limpieza/transición de lead_notes
 │  de R1/R5 en paralelo) │
 └────────────────────────┘
```

1. **Validar restricciones de hosting (R1, R5) — ANTES de migrar.**
   Confirmar que `intake.php` público puede aplicar migraciones o que se aplican vía
   `/admin/`; confirmar conectividad. *Bloquea diseño del intake.* (Sin código.)
2. **`migrations/014_accounts.sql`** — tabla `accounts` + cuenta interna seed.
   *Depende de:* nada. *Bloquea:* todo lo demás.
3. **`migrations/015_leads_account_and_funnel.sql`** — `account_id` + backfill + `next_action_at`.
   *Depende de:* 014 (FK). *Bloquea:* intake y admin filtrado.
4. **`migrations/016_lead_activities.sql`** — log + migración de `lead_notes`.
   *Depende de:* 014, 015. *Bloquea:* status-con-log y timeline.
5. **`lib/accounts.php` + `lib/leads.php`** — resolución de cuenta, `leadCreate()`,
   `leadLogActivity()`. Registrar en `bootstrap.php`.
   *Depende de:* migraciones 014–016 aplicadas.
6. **`intake.php`** (público, multi-cuenta) + refactor de `index.php` para usar `leadCreate()`
   con la cuenta interna.
   *Depende de:* 5. *Verifica:* DoD criterio "lead entra con su `account_id`".
7. **Admin: filtro por cuenta + `update_lead_status` con log + vista `accounts` + timeline.**
   *Depende de:* 5, 6. *Verifica:* DoD "separados en el admin" + "cambio de estado registra actividad".
8. **Transición de `lead_notes` → `lead_activities`** y limpieza:
   dejar de escribir en `lead_notes`, leer todo desde `lead_activities`. (No se dropea
   `lead_notes` aún — R4.)
   *Depende de:* 7.

**Verificación en paralelo (no bloquea Fase 1):** iniciar **ya** la verificación de
WhatsApp Business API + plantilla (MVP §4) — su tiempo de espera es externo y es el cuello
de botella de la **Fase 2**, no de esta.

---

## 5. Riesgos (lo que puede romper o retrasar)

### 🔴 Críticos / requieren validación temprana

- **R1 — El intake es público pero las migraciones solo corren en `/admin/`.**
  `runMigrations()` se invoca en `admin/index.php:48` solo para usuarios autenticados. Una
  landing de cliente que postee a `intake.php` antes de que un admin entre al panel puede
  encontrar un esquema sin las tablas nuevas. *Mitigación:* el intake debe tolerar esquema
  viejo (try/catch como en `index.php:14`), **o** correr migraciones al deploy, **o**
  garantizar que un admin entre tras cada deploy. **Decidir antes de programar.**
- **R2 — Backfill de `account_id` en `leads` existentes.** Si quedan filas con
  `account_id IS NULL` y luego se pone la columna `NOT NULL`/FK estricta, la migración
  falla. *Mitigación:* backfill a la cuenta interna **dentro de la misma migración 015**,
  antes de endurecer la FK.
- **R5 — Verificar capacidades del hosting (cPanel).** `DEPLOY.md` confirma cPanel con
  firewall que bloquea entrantes de datacenters. Aunque el cron/n8n son Fase 2, conviene
  **verificar ya** (en paralelo) granularidad de cron y salida HTTP — son los riesgos
  críticos del MVP §5 y no quiero descubrir el bloqueo recién en Fase 2.

### 🟡 Importantes

- **R3 — Mezcla de datos entre cuentas (la falla más grave del DoD).** Si una sola query de
  leads en el admin olvida el `WHERE account_id = ?`, un cliente ve datos de otro. *Hay
  varias* (`admin/index.php:582-617`, stats, export CSV, detalle, timeline). *Mitigación:*
  centralizar el filtro; checklist de que **todas** las lecturas de `leads` están scopeadas;
  test de las dos cuentas que no se ven entre sí (DoD criterio 8 del MVP §6).
- **R4 — Transición `lead_notes` → `lead_activities`.** Migrar datos y cambiar el código de
  lectura/escritura a la vez puede perder notas o duplicarlas. *Mitigación:* fase de
  convivencia (escribir en ambas o migrar y leer solo de la nueva), **no dropear
  `lead_notes`** hasta verificar; idempotencia en la migración de datos.
- **D2 — Intake API sin CSRF.** El form web usa CSRF, pero la landing de un cliente en
  **otro dominio** no puede tener el token de sesión de ARVIOR. La API por `account_key`
  necesita otra defensa (el `account_key` como secreto + anti-spam por timing/honeypot +
  rate-limit por IP/cuenta). *Decisión de diseño a confirmar.*

### 🟢 Menores / decisiones a aprobar

- **D1 — Embudo: ENUM mapeado vs. pipeline configurable.** El MVP §1.3 pide "pipeline
  configurable", pero §1.4 posterga lo no esencial. *Propuesta:* Fase 1 mantiene el ENUM y
  documenta el mapeo; configurabilidad real cuando un cliente la exija. **Necesito tu
  decisión.**
- **R6 — Secreto `account_key`.** Debe ser opaco e impredecible (no el slug), regenerable,
  y nunca expuesto en logs. Trivial pero hay que hacerlo bien desde el inicio.
- Notificación al operador de lead nuevo: deseable, no bloquea (el panel ya lo muestra).

---

## 6. Definition of Done — Fase 1 (criterios objetivos)

> Fase 1 está terminada cuando **todos** estos criterios son verdes y verificados con datos
> reales. Derivados del DoD del MVP §3 y de los criterios 1, 5 y 8 del MVP §6.

| # | Criterio objetivo | Cómo se verifica |
|:--:|---|---|
| 1 | Existe la tabla `accounts` y se pueden crear cuentas con un `account_key` único | Crear Cuenta A y Cuenta B en el admin; cada una con su `account_key` |
| 2 | Un lead enviado a `intake.php?account_key=A` se guarda con `account_id` = A | Postear a la cuenta A → fila en `leads` con el `account_id` correcto |
| 3 | Leads de la Cuenta A y de la Cuenta B entran y se ven **separados** en el admin | Postear a A y a B; filtrar por cuenta en el panel → cada lista muestra solo lo suyo |
| 4 | **Las cuentas no ven datos de la otra** en ninguna vista (lista, stats, detalle, export) | Filtrar por A → 0 filas de B en lista, conteos y CSV |
| 5 | Cambiar el estado de un lead **registra una actividad** (`status_change`, con from/to y autor) | Cambiar estado en el detalle → fila nueva en `lead_activities`; visible en el timeline |
| 6 | Una nota queda como actividad en el mismo timeline que los cambios de estado | Agregar nota → aparece en el timeline ordenado por fecha |
| 7 | El intake **conserva el anti-spam** (honeypot, timing, dedup por cuenta) | Bot/duplicado en ventana corta → no crea lead; lead legítimo sí |
| 8 | Los leads históricos quedaron asignados a la cuenta interna (sin `account_id` huérfano) | `SELECT COUNT(*) FROM leads WHERE account_id IS NULL` = 0 |
| 9 | El sitio propio de ARVIOR (`index.php`) sigue capturando leads, ahora con `account_id` interno | Enviar el form del home → lead en la cuenta interna, sin regresiones |

**Fuera del DoD de Fase 1 (no se exige aquí):** respuesta < 5 min, outbox, n8n, WhatsApp,
seguimiento, reporte. Eso es el DoD de las Fases 2–4 (MVP §6, criterios 2–4, 6, 7).

> **Gate de avance:** no se empieza la Fase 2 hasta que los 9 criterios estén verdes,
> con énfasis en el **#4 (aislamiento por cuenta)** — es el que hace honesto operar varios
> clientes.

---

## 7. Decisiones que necesito que apruebes antes de programar

1. **D1 — Embudo:** ¿mantener el ENUM actual mapeado (propuesta) o construir pipeline
   configurable por cuenta ya en Fase 1?
2. **D2 — Auth del intake API:** ¿`account_key` + anti-spam + rate-limit como única defensa
   para la API cross-domain (propuesta), o algún esquema de firma/HMAC adicional?
3. **R1 — Migraciones del endpoint público:** ¿hacemos el intake tolerante a esquema viejo,
   movemos las migraciones al deploy, o garantizamos visita a `/admin/` post-deploy?
4. **Refactor `leadCreate()`:** ¿centralizamos la creación de leads en `lib/leads.php` para
   que intake e `index.php` compartan el anti-spam (propuesta), o duplicamos lo mínimo?

Con esas cuatro respuestas, el siguiente PR ya es **implementación** (migraciones 014–016,
`lib/accounts.php`, `lib/leads.php`, `intake.php` y los cambios de admin), en el orden del §4.

---

## 8. Documentos relacionados

- [`ARVIOR_CORE_MVP.md`](ARVIOR_CORE_MVP.md) — diseño del motor; §3 define las 4 fases.
- [`ARVIOR_REVENUE_SYSTEM.md`](ARVIOR_REVENUE_SYSTEM.md) — qué debe cumplir el motor.
- [`ARVIOR_CRM_SETUP.md`](ARVIOR_CRM_SETUP.md) — pipeline y campos del CRM (insumo de D1).
- `DEPLOY.md` — restricciones reales de hosting (insumo de R1, R5).
</content>
</invoke>
