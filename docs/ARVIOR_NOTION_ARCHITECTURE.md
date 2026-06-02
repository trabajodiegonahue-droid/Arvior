# ARVIOR — Notion Architecture (cómo se construye Notion, paso a paso)

> El plano exacto del workspace de Notion de ARVIOR: qué páginas, qué bases de datos,
> qué propiedades y qué vistas se crean —en este orden— para que el negocio sea
> operable. **No es teoría de Notion.** Es la lista de lo que se hace clic a clic el
> Día 1. Si abres Notion en blanco, este documento te dice qué crear primero.
> Última revisión: 2026-06-02 · Estado: implementación / vivo
>
> Marco: [`ARVIOR_OPERATING_SYSTEM.md`](ARVIOR_OPERATING_SYSTEM.md) §4 ·
> Procedimientos que viven aquí: [`ARVIOR_SOPS_MAP.md`](ARVIOR_SOPS_MAP.md) ·
> CRM hermano: [`ARVIOR_CRM_SETUP.md`](ARVIOR_CRM_SETUP.md)

---

## 0. El principio: qué vive en Notion y qué NO

> **Notion es la documentación viva y la operación diaria de ARVIOR: SOPs, playbooks,
> wiki interna, checklists de cuenta y el panel de trabajo. NO es la fuente de verdad
> de los datos comerciales (eso es el CRM) ni de los documentos canónicos de estrategia
> (eso es GitHub `/docs`). NO guarda secretos jamás (eso es la bóveda).**

La regla de oro de [Operating System §4.1](ARVIOR_OPERATING_SYSTEM.md), aplicada a Notion:

| Vive en Notion | NO vive en Notion |
|---|---|
| SOPs ejecutables (checklists vivos) | Documentos canónicos `.md` (viven en GitHub, Notion los espeja) |
| Playbooks de venta, objeciones, guiones | Pipeline y métricas comerciales (viven en el CRM) |
| Wiki interna (cómo trabajamos) | Claves, tokens, contraseñas (viven en la bóveda) |
| Checklist de onboarding por cliente | Contratos firmados y assets binarios (viven en Drive) |
| Tablero de tareas / trabajo de la semana | Cualquier número que deba ser trazable al CRM |

> **Por qué importa:** si los datos comerciales viven en Notion *y* en el CRM, hay dos
> verdades y ninguna sirve. Notion **enlaza** al CRM, al Drive y a la bóveda; no los
> reemplaza. Un enlace, no una copia.

---

## 1. Estructura del workspace (el árbol completo)

> Se construye **de arriba hacia abajo**, una página por sección. Toda la operación
> cabe en **6 espacios de nivel 1**. No crear más al inicio: la dispersión es el enemigo.

```
🏛️  ARVIOR — Workspace
│
├── 📌 START HERE (home del equipo)
│     · qué es cada espacio · enlaces a CRM, Drive, bóveda, GitHub · panel de hoy
│
├── 🧭 01 · ESTRATEGIA (espejo de GitHub /docs — solo lectura)
│     · enlaces a los 14 .md canónicos · "se edita por PR, no aquí"
│
├── ⚙️  02 · SOPs (los 15 procedimientos ejecutables)
│     · base de datos de SOPs · checklists vivos · estado de escritura
│
├── 📣 03 · COMERCIAL (playbooks, no datos)
│     · guion de diagnóstico · banco de objeciones · plantilla de propuesta · scripts
│
├── 🚀 04 · CUENTAS (operación por cliente)
│     · base de cuentas (espeja CRM) · checklist de onboarding · ficha por cliente
│
├── ✅ 05 · TAREAS (el trabajo de la semana)
│     · base de tareas · vistas Hoy / Semana / Por función
│
└── 📚 06 · WIKI (cómo trabajamos)
      · stack y herramientas · decisiones D1–D6 · contactos · plantillas
```

> **Regla de construcción:** crea los 6 espacios vacíos primero (5 minutos), luego
> rellena en el orden de §7. Ver la estructura completa antes de llenar evita rehacer.

---

## 2. Base de datos: SOPs (espacio 02)

> El catálogo de [`ARVIOR_SOPS_MAP.md`](ARVIOR_SOPS_MAP.md) deja de ser un `.md` y pasa
> a ser una base de datos **operable**: cada SOP es una página con su checklist marcable.

**Tipo:** Base de datos (Table). **Nombre:** `SOPs`.

| Propiedad | Tipo | Opciones / nota |
|---|---|---|
| **Código** | Title | `SOP-01` … `SOP-15` |
| **Nombre** | Text | El nombre del SOP |
| **Estación** | Select | `Lead` · `Venta` · `Onboarding` · `Implementación` · `Operate` · `Retención` · `Expansión` · `Transversal` |
| **Dueño** | Select | `Comercial` · `Entrega` · `Operaciones` · `Soporte/Éxito` · `RevOps` |
| **Prioridad** | Select | `P0` (rojo) · `P1` (amarillo) · `P2` (gris) |
| **Estado** | Select | `Especificado` · `En uso` · `Estable` |
| **Disparador** | Text | El evento que lo inicia |
| **Fuente** | Text | El doc de estrategia que lo respalda |

**Contenido de cada página SOP** (cuerpo, no propiedad): los pasos como **checklist
marcable** (`[ ]`) + el **DoD** como callout. Se copian textualmente de
[SOPs Map §2–§5](ARVIOR_SOPS_MAP.md). Así el operador marca pasos al ejecutar.

**Vistas a crear:**

| Vista | Tipo | Filtro / orden | Para qué |
|---|---|---|---|
| **Por prioridad** | Board (por Prioridad) | — | Ver primero los 9 P0 (el esqueleto mínimo) |
| **Por estación** | Board (por Estación) | — | Seguir el flujo lead→expansión |
| **Pendientes de escribir** | Table | Estado = `Especificado` | La cola de trabajo de los 90 días |
| **En uso hoy** | Table | Estado = `En uso` o `Estable` | Lo que ya opera |

> **Día 1 carga solo los 9 P0** (SOP-01, 03, 04, 05, 06, 08, 09, 11, 14) como páginas
> con checklist. Los P1/P2 entran como filas en estado `Especificado` para no perderlos.

---

## 3. Base de datos: Cuentas (espacio 04)

> Esta base **espeja** el CRM, no lo reemplaza. El CRM es la fuente de verdad de los
> datos comerciales ([Operating System §4.1](ARVIOR_OPERATING_SYSTEM.md)); Notion guarda
> la **operación** de la cuenta: su checklist de onboarding, su ficha de contexto, sus
> notas. Cada cuenta de Notion tiene un campo `URL CRM` y un campo `Carpeta Drive`.

**Tipo:** Base de datos (Table). **Nombre:** `Cuentas`.

| Propiedad | Tipo | Opciones / nota |
|---|---|---|
| **Cliente** | Title | Nombre del negocio |
| **Estado de cuenta** | Select | `Onboarding` · `Operate` · `En riesgo` · `Churned` |
| **Plan Operate** | Select | `Core` · `Growth` · `Intelligence` |
| **Health** | Select | 🟢 `Verde` · 🟡 `Amarillo` · 🔴 `Rojo` |
| **Dueño de cuenta** | Person | Quién la opera |
| **Go-live** | Date | Dispara cobro de Operate (D1) |
| **URL CRM** | URL | Enlace a la oportunidad/cuenta en el CRM |
| **Carpeta Drive** | URL | Enlace a la carpeta del cliente |
| **Métrica de éxito** | Text | La métrica del cliente (del paquete de handoff) |

**Contenido de cada página de cuenta** (cuerpo): se crea desde una **plantilla** (§3.1)
que incluye el checklist de onboarding y la ficha de handoff. Nada de claves aquí.

**Vistas:**

| Vista | Tipo | Filtro | Para qué |
|---|---|---|---|
| **En onboarding** | Board (por estado) | Estado = `Onboarding` | Lo que Entrega tiene en mano |
| **Cartera Operate** | Table | Estado = `Operate` | Las cuentas que pagan |
| **Salud (health mix)** | Board (por Health) | — | Rojos primero (regla de lectura) |
| **En riesgo** | Table | Health = 🔴 o Estado = `En riesgo` | Cola de rescate (SOP-11) |

### 3.1 Plantilla de página de cuenta (el corazón operativo de §4)

Cada cuenta nueva se crea con esta plantilla (botón "New from template"). Contiene:

1. **Ficha de handoff** (callout): diagnóstico, dolor textual del cliente, métrica de
   éxito, alcance, **exclusiones acordadas**, plan de Operate, decisor y usuarios,
   sensibilidades de plazo. Se llena en el handoff (SOP-06) y **no se vuelve a pedir** al
   cliente.
2. **Checklist de onboarding** (SOP-07) marcable: kickoff agendado · accesos en bóveda ·
   responsables nombrados · cronograma con fechas aceptado.
3. **Checklist de implementación por fases** (SOP-08): F1 Captura → F2 Calificación+
   seguimiento → F3 Conversión+agenda → F4 Integraciones → F5 Capacitación. Cada fase con
   su DoD marcable + la verificación obligatoria **respuesta < 5 min**.
4. **Bitácora de Operate**: qué se optimizó cada mes (alimenta el reporte de ROI, SOP-09).
5. **Enlaces:** URL CRM · Carpeta Drive · (nunca enlaces a secretos).

---

## 4. Base de datos: Tareas (espacio 05)

> El trabajo real de la semana. No es un gestor de proyectos complejo: es la lista de
> lo que hay que hacer, con dueño y fecha, conectada a las cuentas y SOPs.

**Tipo:** Base de datos (Table). **Nombre:** `Tareas`.

| Propiedad | Tipo | Opciones / nota |
|---|---|---|
| **Tarea** | Title | Qué hay que hacer |
| **Estado** | Select | `Por hacer` · `En curso` · `Esperando a otro` · `Hecho` |
| **Función** | Select | `Comercial` · `Entrega` · `Operaciones` · `Soporte` · `RevOps` |
| **Prioridad** | Select | `Hoy` · `Esta semana` · `Backlog` |
| **Fecha** | Date | Toda tarea tiene fecha o no existe |
| **Cuenta** | Relation → `Cuentas` | Si es de un cliente |
| **SOP** | Relation → `SOPs` | Si ejecuta un procedimiento |

**Vistas:**

| Vista | Tipo | Filtro | Para qué |
|---|---|---|---|
| **Hoy** | Table | Prioridad = `Hoy` o Fecha = hoy; ≠ `Hecho` | El foco del día (5 min de mañana) |
| **Esta semana** | Board (por Estado) | Fecha ≤ fin de semana; ≠ `Hecho` | El plan semanal |
| **Por función** | Board (por Función) | ≠ `Hecho` | Lo que toca a cada rol (futuro: a cada persona) |
| **Esperando** | Table | Estado = `Esperando a otro` | Lo que está bloqueado (no se olvida) |

> **Regla:** una tarea sin fecha y sin dueño no es una tarea, es un deseo. Se descarta o
> se completa. Espeja la disciplina del CRM (Sales §3.2: toda oportunidad tiene próxima
> acción con fecha).

---

## 5. Espacio Comercial (03) — playbooks, no datos

> Aquí vive el **cómo se vende**, no **a quién** (eso es el CRM). Son páginas de texto,
> no bases de datos. Se copian de la estrategia ya cerrada y se usan en vivo.

| Página | Contenido | Fuente |
|---|---|---|
| **Guion de diagnóstico** | Las preguntas de descubrimiento + cómo nombrar el hueco | [Sales §4.2–4.3](ARVIOR_SALES_SYSTEM.md) |
| **Banco de objeciones** | Tabla objeción → reencuadre → pregunta de devolución | [Sales §6](ARVIOR_SALES_SYSTEM.md) · [Offer §17](ARVIOR_OFFER.md) |
| **Plantilla de propuesta** | La estructura de 1 página + qué mostrar / qué no | [Sales §5](ARVIOR_SALES_SYSTEM.md) |
| **Estructura de reunión** | Los 6 bloques de 30–45 min | [Sales §4.1](ARVIOR_SALES_SYSTEM.md) · [Offer §18](ARVIOR_OFFER.md) |
| **Pitch y sub-líneas** | El pitch de una línea + por interlocutor | [Offer §1](ARVIOR_OFFER.md) |
| **Precios (referencia)** | Los 3 escenarios Build + Operate | [Offer §6–12](ARVIOR_OFFER.md) |

> **Por qué páginas y no base de datos:** un playbook se lee de corrido en una reunión,
> no se filtra. El CRM lleva los datos; Notion lleva el conocimiento.

---

## 6. Espacio Wiki (06) — cómo trabajamos

> La memoria de la empresa que **no** son datos de cliente. Lo que evita que el negocio
> viva en la cabeza del fundador (mitiga el bus factor, [Operating System §6.4](ARVIOR_OPERATING_SYSTEM.md)).

| Página | Contenido |
|---|---|
| **Stack y herramientas** | Qué herramienta es dueña de qué (la tabla de [Operating System §4.1](ARVIOR_OPERATING_SYSTEM.md)) + enlaces |
| **Decisiones D1–D6** | Las 6 decisiones binarias del fundador, escritas y datadas ([Audit §7](ARVIOR_EXECUTION_AUDIT.md)) |
| **Mapa de accesos (índice, NO claves)** | Qué accesos existen y **dónde** están en la bóveda — nunca el secreto |
| **Contactos y proveedores** | Colaboradores externos, proveedores (hosting, IA, WhatsApp, pasarela) |
| **Plantillas** | Correo de bienvenida, mensaje de referido, recordatorios |

> ⚠️ **El "Mapa de accesos" lista qué existe y remite a la bóveda. Nunca contiene la
> clave.** Ningún secreto en texto plano jamás (SOP-14).

---

## 7. Orden de construcción del Día 1 (clic a clic, ~2 horas)

> No se construye todo de una vez. Este es el **mínimo para operar la primera venta**.
> Lo demás se llena cuando el flujo lo pida (regla: se escribe el SOP la segunda vez).

| Paso | Qué se hace | Tiempo | Por qué primero |
|:---:|---|:---:|---|
| 1 | Crear los **6 espacios** vacíos + página `START HERE` con enlaces a CRM, Drive, bóveda, GitHub | 15 min | El esqueleto antes que la carne |
| 2 | Crear base **Cuentas** + su **plantilla de página** (§3.1) | 30 min | Sin esto no se puede operar un cliente |
| 3 | Crear base **SOPs** y cargar los **9 P0** como checklist | 40 min | El esqueleto mínimo operable |
| 4 | Crear base **Tareas** + vistas Hoy / Semana | 15 min | El trabajo diario necesita dónde vivir |
| 5 | Copiar los **playbooks comerciales** (§5) desde la estrategia | 20 min | Necesarios para la primera reunión |
| 6 | Crear **Wiki**: stack, D1–D6, mapa de accesos (índice) | 15 min | Mitiga el bus factor desde el día 1 |

**DoD del Notion Día 1:** existe un workspace donde (a) una cuenta nueva se crea con su
checklist completo en un clic, (b) los 9 SOPs P0 son checklists marcables, (c) el trabajo
de la semana tiene una vista "Hoy", y (d) todo enlaza al CRM/Drive/bóveda sin duplicar
datos. **Lo demás espera.**

---

## 8. Reglas de gobierno de Notion (para que no se ensucie)

1. **Una base de datos por concepto.** SOPs, Cuentas, Tareas. No crear bases nuevas sin
   preguntarse si una vista resuelve lo mismo.
2. **Notion enlaza, no copia.** Datos comerciales → CRM. Secretos → bóveda. Assets →
   Drive. Docs canónicos → GitHub. Notion guarda el enlace.
3. **Toda página de cuenta nace de la plantilla.** Nunca a mano: garantiza que el
   checklist y la ficha de handoff siempre estén.
4. **El espacio Estrategia es solo lectura.** Espeja GitHub; se edita por PR (como este
   documento), nunca directo en Notion.
5. **Si una base no se mira en una semana, sobra.** El workspace se poda, no se acumula.

---

## 9. Documentos relacionados

- [`ARVIOR_OPERATING_SYSTEM.md`](ARVIOR_OPERATING_SYSTEM.md) §4 — qué herramienta es dueña de qué.
- [`ARVIOR_CRM_SETUP.md`](ARVIOR_CRM_SETUP.md) — el CRM que esta arquitectura espeja, no duplica.
- [`ARVIOR_SOPS_MAP.md`](ARVIOR_SOPS_MAP.md) — los SOPs que se cargan como checklists.
- [`ARVIOR_DAY_ONE.md`](ARVIOR_DAY_ONE.md) — dónde encaja Notion en el arranque del Día 1.
- [`ARVIOR_FOUNDER_DASHBOARD.md`](ARVIOR_FOUNDER_DASHBOARD.md) — el tablero que lee del CRM, no de Notion.
</content>
</invoke>
