# ARVIOR — CRM Setup (cómo se monta el CRM, campo por campo)

> El plano exacto del CRM de ARVIOR: las etapas, las propiedades, las automatizaciones
> y las reglas de higiene que lo hacen operable el Día 1. **No es teoría de CRM.** Es lo
> que se configura para que ninguna oportunidad se pierda y todo número del tablero sea
> trazable. El CRM es la **única** fuente de verdad de los datos comerciales.
> Última revisión: 2026-06-02 · Estado: implementación / vivo
>
> El pipeline conceptual: [`ARVIOR_SALES_SYSTEM.md`](ARVIOR_SALES_SYSTEM.md) §3 ·
> Lo que alimenta: [`ARVIOR_FOUNDER_DASHBOARD.md`](ARVIOR_FOUNDER_DASHBOARD.md) ·
> Dónde encaja: [`ARVIOR_OPERATING_SYSTEM.md`](ARVIOR_OPERATING_SYSTEM.md) §4

---

## 0. El principio: el CRM es la realidad del negocio

> **Si una cuenta no está en el CRM, no existe: no se factura, no se mide, no se
> reporta. Una oportunidad está siempre en una y solo una etapa, con un estado y una
> próxima acción con fecha. El dashboard no tiene datos propios — lee del CRM. Si un
> número del tablero no se puede trazar a un dato del CRM, el número está mal.**

Tres consecuencias de [Operating System §4.1](ARVIOR_OPERATING_SYSTEM.md) y
[Sales §3](ARVIOR_SALES_SYSTEM.md):

1. **El CRM no compite con Notion.** Notion guarda el *cómo* (playbooks, checklists); el
   CRM guarda el *quién, en qué etapa y cuánto vale*. Notion enlaza al CRM, nunca lo copia.
2. **La higiene no es opcional.** Una oportunidad sin próxima acción con fecha está mal
   gestionada (§4). El sistema que vendemos exige disciplina; lo practicamos primero.
3. **ARVIOR corre su propio sistema (caso #0).** El CRM ideal es **ARVIOR Core** —el
   mismo motor que vendemos ([Sales §2.1](ARVIOR_SALES_SYSTEM.md)). Hasta que Core tenga
   el pipeline de 5 etapas, se arranca en un CRM externo simple (§7) y se migra. Lo que
   importa el Día 1 es **tener el pipeline**, no en qué herramienta.

---

## 1. Las dos entidades del CRM (Oportunidad y Cuenta)

> El error común es mezclar la **venta** (un proceso que se gana o se pierde) con la
> **cuenta** (una relación que paga cada mes). ARVIOR las separa porque su negocio es el
> recurrente ([Business Model](ARVIOR_BUSINESS_MODEL.md)): el Build se cierra una vez; el
> Operate vive para siempre.

| Entidad | Qué representa | Ciclo de vida | Métrica que posee |
|---|---|---|---|
| **Oportunidad** | Una venta en curso (Build + Operate) | Lead → Cerrado-Ganado/Perdido | MRR nuevo, ciclo de venta, conversión |
| **Cuenta** | Un cliente que paga Operate | Operate → Renovación / Churn | MRR, churn, NRR, health |

> Una **Oportunidad** ganada **crea** una **Cuenta**. La cuenta es lo que se opera
> (espejada en Notion, §3 de [Notion Architecture](ARVIOR_NOTION_ARCHITECTURE.md)) y lo
> que se factura cada mes (SOP-15).

---

## 2. Pipeline: las 5 etapas + estados de salida

> Estas son las etapas exactas de [Sales §3.1](ARVIOR_SALES_SYSTEM.md), instanciadas como
> columnas del CRM. La **probabilidad** sirve para proyectar MRR futuro (pipeline
> ponderado), no para adivinar — se calibra con los primeros 10–15 cierres.

| # | Etapa (columna del CRM) | Entrada | Prob. | Criterio para avanzar | SOP |
|---|---|---|:---:|---|---|
| 0 | **Lead calificado** | Cumple admisión ≥3/4, sin reunión | 10% | Reunión agendada | SOP-01 |
| 1 | **Reunión agendada** | Hay fecha con el decisor | 20% | Reunión hecha + diagnóstico | SOP-02 |
| 2 | **Diagnóstico hecho** | Conocemos el embudo, hueco cuantificado | 35% | Cliente acepta propuesta | SOP-03 |
| 3 | **Propuesta enviada** | Propuesta presentada en vivo | 55% | Negocia / pide ajuste (señal de compra) | SOP-04 |
| 4 | **Negociación / cierre** | Discutiendo alcance/precio/plazo | 75% | Acuerdo verbal + contrato | SOP-05 |
| 5 | **Cerrado–Ganado** | Contrato firmado + 1er pago | 100% | → crea Cuenta, dispara Onboarding | SOP-06 |
| — | **Cerrado–Perdido** | No avanza | 0% | **Razón de pérdida obligatoria** | — |
| — | **Nurture / dormido** | Califica pero no ahora | — | Reactivar con gatillo | SOP-13 |

> **El Día 1 se crea exactamente este tablero (Kanban).** Ni una etapa más. Saltarse
> "Diagnóstico" para ir directo a "Propuesta" está prohibido por regla (§4, regla 2).

---

## 3. Propiedades de la Oportunidad (los campos que se crean)

> Cada campo existe por una razón operativa. No se agregan campos "por si acaso": cada
> campo que no se llena ensucia el CRM.

| Propiedad | Tipo | Opciones / nota | Por qué existe |
|---|---|---|---|
| **Nombre** | Texto | `[Negocio] — [Rubro]` | Identificar de un vistazo |
| **Etapa** | Select | Las 8 de §2 | El pipeline es el CRM |
| **Origen** | Select | `Referido` · `Inbound web` · `Outbound` · `Caso de éxito` | Saber qué canal funciona (Sales §2.1) |
| **Score** | Select | 🟢 `Caliente` · 🟡 `Tibio` · 🔴 `Frío` | Priorizar el seguimiento (Sales §2.3) |
| **Admisión (3/4)** | Multi-select | `Gasta en captar` · `Lead vale mucho` · `Pierde lo que capta` · `Sin equipo técnico` | El filtro que protege el margen (Sales §2.2) |
| **Plan propuesto** | Select | `Entrada+Core` · `Profesional+Growth` · `Premium+Intelligence` | Anclar en Profesional |
| **Build (CLP)** | Número | Monto una vez | Proyectar ingreso de proyecto |
| **MRR (CLP)** | Número | Mensualidad de Operate | **El número que importa** |
| **Pérdida cuantificada** | Número | El hueco del diagnóstico, en $/mes | El ancla de valor (Sales §4.3) |
| **Próxima acción** | Texto | Qué sigue | Sin esto, está mal gestionada |
| **Fecha próxima acción** | Fecha | Cuándo | **Innegociable** (regla 1, §4) |
| **Decisor presente** | Checkbox | ¿El que decide está en la reunión? | No se cotiza a quien no decide (regla 6) |
| **Estado transversal** | Select | `Nuevo` · `En proceso` · `Esperando cliente` · `En riesgo` · `Dormido` · `Reactivado` | Filtrar sin mover de etapa (Sales §3.3) |
| **Razón de pérdida** | Select | `Precio` · `Timing` · `No calificaba` · `Competencia` · `Sin respuesta` | Obligatoria al perder (regla 4) — alimenta aprendizaje |
| **Dueño** | Persona | Comercial responsable | Toda oportunidad tiene dueño |

---

## 4. Reglas de higiene del pipeline (innegociables)

> Copiadas de [Sales §3.2](ARVIOR_SALES_SYSTEM.md). Estas reglas son lo que separa un
> CRM vivo de un cementerio de leads. Se aplican siempre, sin excepción.

1. **Toda oportunidad tiene próxima acción con fecha.** Sin ella → estado `Dormido`.
2. **Una etapa, un objetivo.** No se salta de Reunión a Propuesta sin Diagnóstico.
3. **Se cotiza siempre Build + Operate juntos.** Nunca Build solo a precio estándar
   ([Offer §12](ARVIOR_OFFER.md), regla de oro).
4. **Toda pérdida lleva razón** (taxonomía del campo). Sin razón, no se cierra como
   perdida.
5. **SLA de seguimiento:** caliente contactado < 24 h · propuesta seguida ≤ 48 h ·
   sin movimiento 14 días → `En riesgo` → revisión o nurture.
6. **El decisor está en la reunión o no hay propuesta.** Cotizar a quien no decide quema
   pipeline.

> **Cómo se hace cumplir sin disciplina heroica:** con las automatizaciones del §6. La
> regla "próxima acción con fecha" no se vigila a mano —el sistema marca `Dormido` solo.

---

## 5. Propiedades de la Cuenta (post-venta, el negocio real)

> Cuando una Oportunidad llega a **Cerrado–Ganado**, se crea una **Cuenta**. Aquí vive
> el recurrente —lo que se factura (SOP-15) y se cuida contra el churn (SOP-11).

| Propiedad | Tipo | Opciones / nota |
|---|---|---|
| **Cliente** | Texto | Nombre del negocio |
| **Plan Operate** | Select | `Core` · `Growth` · `Intelligence` |
| **MRR (CLP)** | Número | La mensualidad activa |
| **Estado de cuenta** | Select | `Onboarding` · `Operate` · `En riesgo` · `Churned` |
| **Health** | Select | 🟢 · 🟡 · 🔴 (SOP-11, Retention §2.1) |
| **Go-live** | Fecha | **Dispara el cobro de Operate (D1)** — no la firma |
| **Inicio facturación** | Fecha | = Go-live (D1); base del cobro recurrente |
| **Último reporte ROI** | Fecha | Vigila que SOP-09 se cumpla cada mes |
| **Razón de churn** | Select | Taxonomía RevOps §5.1 (si aplica) |
| **URL Notion** | URL | Ficha operativa de la cuenta |
| **Carpeta Drive** | URL | Contratos firmados + assets |
| **Dueño de cuenta** | Persona | Quién la opera |

> **Las decisiones binarias del fundador viven aquí (Audit §7):**
> **D1** (Operate se factura desde go-live, no desde firma) está en los campos `Go-live`
> e `Inicio facturación`. **D2** (término mes a mes, aviso 30 días) en el contrato del
> Drive. **D6** (cobro Chile/CLP primero) en que el MRR se lleva en CLP.

---

## 6. Automatizaciones del CRM (lo que el sistema hace solo)

> El orden sigue [Operating System §6.3](ARVIOR_OPERATING_SYSTEM.md): se automatiza lo
> que mantiene la promesa central primero. Estas viven en **ARVIOR Core**; en un CRM
> externo de arranque, las que se pueda y el resto como recordatorio manual.

| # | Automatización | Qué hace | Prioridad | Depende de |
|:---:|---|---|:---:|---|
| 1 | **Respuesta < 5 min al lead** | Autorespuesta al lead nuevo (la garantía central) | **Día 1, no negociable** | Runtime D3 (Audit §7) |
| 2 | **Captura → etapa 0 + score** | Lead entra al CRM en `Lead calificado` con origen | Día 1 | Core / formulario web |
| 3 | **Sin próxima acción → `Dormido`** | Marca solo las oportunidades mal gestionadas | Semana 1 | Campo fecha |
| 4 | **SLA vencido → `En riesgo`** | Sin movimiento 14 días salta a alerta | Semana 1 | Campo fecha |
| 5 | **Cerrado–Ganado → crea Cuenta + Onboarding** | El cierre dispara el handoff el mismo día | Mes 1 | SOP-06 |
| 6 | **Go-live → activa facturación recurrente** | Cobro de Operate arranca solo (D1) | Mes 1 | SOP-15 |
| 7 | **Recordatorio de reporte ROI mensual** | Avisa antes de fin de mes por cuenta | Mes 1 | SOP-09 |
| 8 | **Alerta de health en rojo** | El rojo avisa solo, no se descubre tarde | Mes 2 | SOP-11 |

> **La automatización #1 es la línea que no se cruza:** sin respuesta < 5 min, la oferta
> no se cumple ([Audit §1.3](ARVIOR_EXECUTION_AUDIT.md)). Antes del primer go-live se
> verifica que funciona (SOP-08).

---

## 7. Decisión de herramienta: Core vs. CRM externo de arranque

> El Día 1 importa **tener el pipeline funcionando**, no la herramienta perfecta. ARVIOR
> Core es el destino (caso #0); un CRM externo simple es el puente hasta que Core tenga
> las 5 etapas.

| Opción | Cuándo | Pro | Contra |
|---|---|---|---|
| **ARVIOR Core (propio)** | Cuando exista el pipeline de 5 etapas + runtime D3 | Es el caso #0 real: vendemos lo que usamos | Aún por construir (prioridad técnica nº 1, [Operating System §4.3](ARVIOR_OPERATING_SYSTEM.md)) |
| **CRM externo simple** (gratuito/bajo costo) | Para arrancar **hoy** mientras Core madura | Operable en horas; cero deuda técnica | No es el caso #0; se migra después |
| **Notion como CRM provisional** | Solo si no hay nada más, < 10 oportunidades | Ya está montado (Notion Architecture) | Mezcla datos y operación; rompe "una fuente de verdad" si se queda |

> **Recomendación de arranque:** montar el pipeline en un CRM externo simple **hoy** y
> migrar a Core cuando tenga las 5 etapas y el runtime. La migración es trivial si desde
> el Día 1 se respetan estas etapas y campos exactos. **Lo que NO se hace:** esperar a
> Core para empezar a vender.

---

## 8. Orden de construcción del Día 1 (~1 hora)

| Paso | Qué se hace | Tiempo | DoD |
|:---:|---|:---:|---|
| 1 | Crear el pipeline con las **8 columnas** de §2 (Kanban) | 10 min | El tablero existe, exacto |
| 2 | Crear las **propiedades de Oportunidad** (§3) | 20 min | Toda oportunidad se puede llenar completa |
| 3 | Crear las **propiedades de Cuenta** (§5) | 15 min | Una cuenta ganada tiene dónde vivir |
| 4 | Conectar la **captura de la web** → etapa 0 + autorespuesta (#1, #2) | 15 min | Un lead de prueba entra solo y recibe respuesta |
| 5 | Cargar las oportunidades reales que ya existen (red del fundador) | — | El pipeline arranca con datos reales, no vacío |

**DoD del CRM Día 1:** (a) las 8 etapas existen exactas, (b) toda oportunidad se registra
con origen, score, plan, MRR y próxima acción con fecha, (c) un lead de la web entra solo
y recibe respuesta < 5 min, y (d) el dashboard puede leer MRR y conversión desde aquí.

---

## 9. Cómo el CRM alimenta el dashboard del fundador

> El CRM es el origen de **todos** los números del [Founder Dashboard](ARVIOR_FOUNDER_DASHBOARD.md).
> El tablero no calcula nada que no salga de aquí (regla del §0).

| Métrica del dashboard | De dónde sale en el CRM |
|---|---|
| **Leads nuevos sin tocar** (diario) | Oportunidades en etapa 0 sin próxima acción |
| **Cuentas en rojo nuevas** (diario) | Cuentas con Health = 🔴 |
| **Cobros caídos** (diario) | Cuentas con facturación vencida (SOP-15) |
| **Embudo de la semana** | Conversión entre etapas 0→5 |
| **MRR neto** (mensual) | Σ MRR de cuentas activas (nuevo + expansión − contracción − churn) |
| **Churn** (mensual) | Cuentas que pasaron a `Churned` + su razón |
| **NRR** (mensual) | MRR de la cartera vs. mes anterior |
| **Ciclo de venta** | Días entre etapa 0 y etapa 5 |

> Si el dashboard muestra un número que no se puede señalar en una oportunidad o cuenta
> del CRM, **el número está mal** —no el CRM.

---

## 10. Documentos relacionados

- [`ARVIOR_SALES_SYSTEM.md`](ARVIOR_SALES_SYSTEM.md) §3 — el pipeline conceptual que este CRM instancia.
- [`ARVIOR_NOTION_ARCHITECTURE.md`](ARVIOR_NOTION_ARCHITECTURE.md) — la operación que espeja el CRM sin duplicarlo.
- [`ARVIOR_FOUNDER_DASHBOARD.md`](ARVIOR_FOUNDER_DASHBOARD.md) — lo que el CRM alimenta con datos.
- [`ARVIOR_SOPS_MAP.md`](ARVIOR_SOPS_MAP.md) — los SOPs que mueven las oportunidades de etapa.
- [`ARVIOR_OPERATING_SYSTEM.md`](ARVIOR_OPERATING_SYSTEM.md) §4 — por qué el CRM es la única fuente de verdad comercial.
</content>
