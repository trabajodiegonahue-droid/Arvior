# ARVIOR — Business Model

> El documento que convierte "empresa tecnológica que construye sistemas" de
> aspiración en modelo. Resuelve las contradicciones detectadas en la revisión
> crítica: define a quién, con qué entramos, cómo cobramos y por qué no nos copian.
> Última revisión: 2026-06-02 · Estado: foundation
>
> Estratégico de identidad: [`ARVIOR_MASTERPLAN.md`](ARVIOR_MASTERPLAN.md) ·
> Marca: [`ARVIOR_BRAND_SYSTEM.md`](ARVIOR_BRAND_SYSTEM.md)

---

## 0. La tesis en una frase

> **ARVIOR entra vendiendo un sistema que genera ingresos para el cliente
> (servicio, alto margen, paga la operación), y se queda cobrando por operar ese
> sistema (recurrente, escalable, dueño de la relación). El proyecto es el costo de
> adquisición; lo recurrente es el negocio.**

Eso resuelve la contradicción raíz: no dejamos de hacer proyectos porque sean malos
— los usamos como **puerta de entrada financiada por el propio cliente** hacia una
relación recurrente. La agencia cobra el proyecto y se va. ARVIOR cobra el proyecto
y **se queda corriendo el sistema**.

> ⚠️ **Sobre los números.** Las cifras de este documento son *supuestos de
> arranque* basados en tu capacidad real, no datos históricos. Están para
> dimensionar el modelo y para validarse/corregirse con los primeros 10 clientes.
> Cada tabla marca qué hay que medir para confirmarla.

---

## 1. ICP — A quién le construimos

La regla anti-agencia: **un ICP nítido, no "negocios modernos".** Empezamos
angostos a propósito; la amplitud llega con el producto, no con el servicio.

### 1.1 ICP principal — PYME de servicios en crecimiento (LatAm, hispanohablante)

| Dimensión | Definición |
|---|---|
| **Quién** | Negocios de servicios establecidos y en crecimiento: clínicas (dental/estética/salud), despachos profesionales (legal/contable), inmobiliarias, retail multi-sucursal, e-commerce con operación real, franquicias locales |
| **Tamaño** | 10–50 empleados · facturación aprox. USD 300K–5M/año |
| **Geografía** | LatAm hispanohablante primero (coherente con admin/WhatsApp en español) |
| **Quién decide** | Dueño/a o gerente general. Ciclo de venta corto, decisión emocional + ROI |
| **Dolor central** | "Crecimos más rápido que nuestras herramientas": web genérica que no convierte, leads que se pierden en WhatsApp/planillas, procesos manuales, equipo saturado |
| **Señal de oportunidad** | **Ya gastan en marketing** (ads, redes) pero pierden lo que captan por falta de sistema detrás. Tienen presupuesto y dolor, no madurez técnica |
| **Por qué ARVIOR** | No tienen ni quieren equipo técnico interno; necesitan un socio que construya *y opere* |

**Por qué este ICP y no otro:** combina tus cinco fortalezas (web + marketing +
automatización + IA + sistemas) en un solo cliente que **siente el dolor en
dinero** (leads perdidos = ingresos perdidos) y **tiene con qué pagar lo
recurrente** (ya tiene presupuesto de marketing del cual ARVIOR se vuelve el
multiplicador, no un costo nuevo).

### 1.2 ICP secundario — Founder/startup digital sin equipo técnico

| Dimensión | Definición |
|---|---|
| **Quién** | Fundadores no-técnicos o startups tempranas (LatAm o US-hispano) que necesitan construir y operar un producto sin montar equipo aún |
| **Tamaño** | 1–10 personas, pre-seed/seed o bootstrapped |
| **Dolor** | Tienen visión de producto y tracción incipiente, pero no pueden contratar CTO + equipo todavía |
| **Por qué ARVIOR** | Somos su capa de ingeniería tercerizada con criterio de producto: MVP, automatización y features de IA reales |
| **Rol estratégico** | ACV más alto, proyectos más complejos, semillero de **casos de estudio** y de futuros productos co-construidos (modelo venture studio del Masterplan §8) |

**Por qué secundario y no principal:** ciclo más largo, menos predecible, mayor
riesgo de mortalidad del cliente. Excelente para reputación y aprendizaje de
producto, pero no es la base sobre la que se construye el ARR temprano.

### 1.3 A quién NO le vendemos (disciplina de foco)

- Quien quiere "solo una web barata" → ese es el cliente de la plantilla, no de ARVIOR.
- Corporativos con TI interno y compras por licitación → ciclo largo, somos chicos.
- Negocios sin presupuesto de marketing ni dolor operativo → no hay ROI recurrente que justifique el MRR.

---

## 2. Wedge de entrada — Cómo entramos

El error de agencia es entrar por "¿qué necesitas?". El wedge de empresa de
producto es **una sola cosa, específica, con ROI medible y rápido**, que además es
la puerta natural a todo lo demás.

### 2.1 El wedge: **Revenue System** (no "una web")

No vendemos "websites". Vendemos un **sistema de captación y conversión** del cual
la web es solo la cara visible:

```
Sitio premium que convierte
        +
Captura de leads (form + WhatsApp, anti-spam, dedupe)   ← ya existe en el repo
        +
Calificación y enrutamiento automático del lead
        +
Seguimiento con IA (respuesta inmediata, agenda, nurture)
        +
Panel/CRM donde el dueño ve todo el embudo
```

**Por qué este wedge es el correcto para ARVIOR:**

1. **Usa tus cinco fortalezas en un solo entregable** (web + marketing + automatización + IA + sistemas).
2. **El ROI es obvio y medible:** más leads capturados y convertidos = más ingresos. Eso justifica cobrar mensual.
3. **Ataca dinero que el cliente ya gasta:** se conecta a su presupuesto de marketing existente y lo multiplica → no es un costo nuevo, es un upgrade de ROI.
4. **Ya tienes el 60% construido:** el repo actual (CMS, leads, anti-spam, mailing, páginas) es el esqueleto del Revenue System. El wedge no es teórico; es tu código.
5. **Es la puerta a la cuenta completa:** quien te deja correr su motor de leads te deja después correr su operación (land & expand, §4).

### 2.2 Resolución de la contradicción "Websites vs Systems"

La línea "Websites" del Masterplan **no se vende sola jamás.** Es el front-end de un
Revenue System. Un sitio sin sistema detrás es un folleto; ARVIOR no vende folletos.
Esto alinea el discurso ("el sistema es la entrega") con la oferta real.

---

## 3. Oferta inicial y modelo de ingresos

### 3.1 Estructura de precios (tres capas)

| Capa | Qué es | Tipo | Función económica |
|---|---|---|---|
| **1. Build (Setup)** | Diseño + construcción del Revenue System | One-time | Genera caja y margen inmediato; **financia el CAC** |
| **2. Operate (Core)** | Hosting + mantenimiento + automatizaciones e IA corriendo + soporte + optimización | **Recurrente mensual** | **El negocio.** MRR → ARR |
| **3. Grow (Expansión)** | Nuevos módulos, más automatización, agentes de IA, operaciones, retainer de crecimiento | Recurrente / proyecto | **NRR > 100%**: el cliente crece dentro de ARVIOR |

> **Regla de oro comercial:** nunca vender Build sin Operate. Si un cliente solo
> quiere el one-time, es cliente de agencia, no de ARVIOR (o se cobra el Build a
> precio de "no estratégico", premium, para desincentivarlo).

### 3.2 Cifras ilustrativas de arranque (LatAm PYME — a validar)

| Métrica | Rango de arranque | A validar con |
|---|---|---|
| Build (setup) | USD 1,500 – 6,000 | Primeros 5 cierres |
| Operate (MRR) | USD 200 – 1,200 / mes | Disposición a pagar mensual |
| Grow (upsell) | +USD 150 – 800 / mes | Expansión a los 3–6 meses |
| **ACV recurrente** | **USD 3,000 – 18,000 / año** | Mezcla de la cartera |
| Margen bruto recurrente | 75 – 85% | Costo real de operar (infra + tu tiempo + tokens IA) |

El margen alto del recurrente es posible **gracias a** la decisión de infra simple
del Masterplan (PHP plano, hosting eficiente, sin stack pesado). Aquí esa decisión
deja de ser "valor de marca" y se convierte en lo que realmente es: **una ventaja
de margen** — su rol correcto.

### 3.3 Por qué esto no es facturación de agencia

| Agencia | ARVIOR |
|---|---|
| Cobra el proyecto, busca el siguiente | Cobra el proyecto, **retiene la operación** |
| Ingreso impredecible, empieza en cero cada mes | **MRR predecible que se acumula** |
| Crece contratando manos | Crece **subiendo MRR por cuenta + bajando costo marginal** |
| El cliente puede irse sin costo | El cliente **opera sobre ARVIOR**: irse duele |

---

## 4. Camino de servicios a producto (land & expand → productización)

Esta es la columna vertebral. Tres fases que conviven, no se reemplazan.

### 4.1 Fase Servicio (hoy) — *cash y aprendizaje*

Construcción a medida del Revenue System por cliente. Margen de servicio, caja para
operar, y —clave— **cada build enseña qué se repite.** Documentar lo repetible es la
materia prima del producto.

### 4.2 Fase Plataforma interna — *ARVIOR Core*

Lo que se repite entre clientes se factoriza en una **base reutilizable propia**
(el repo actual es su semilla: CMS + leads + páginas + mailing + automatización).
Cada nuevo build se monta sobre Core:

- **Costo marginal por cliente baja** (no se reconstruye desde cero) → margen sube.
- **Velocidad de entrega sube** → más cuentas con el mismo equipo.
- **Calidad se estandariza** → la marca premium se vuelve sistemática, no artesanal.

> Este es el punto de inflexión donde ARVIOR deja de escalar con headcount. El 80%
> repetible lo da Core; el 20% bespoke es el margen y la diferenciación.

### 4.3 Fase Producto — *de Core a SaaS*

Cuando Core opera decenas de cuentas del mismo vertical, se convierte en **producto
multi-tenant** para un sub-segmento: self-serve parcial, suscripción, onboarding
estandarizado. Las automatizaciones e IA recurrentes se vuelven módulos vendibles.

```
Servicio bespoke  →  Plataforma interna (Core)  →  Producto (SaaS vertical)
  margen alto          costo marginal ↓             ingreso escalable sin headcount
  no escala            empieza a escalar            escala de verdad
```

**La transición es gradual y financiada por sí misma:** el servicio paga la
construcción de Core; Core paga la construcción del producto. No requiere capital
externo para empezar (sí puede acelerarse con él más adelante).

---

## 5. Unit economics (modelo, no promesa)

### 5.1 Definiciones aplicadas a ARVIOR

| Métrica | Cómo se calcula en ARVIOR | Objetivo de arranque |
|---|---|---|
| **MRR** | Σ Operate + Grow de todas las cuentas | Crecer mes a mes |
| **ARR** | MRR × 12 | Métrica de board, reemplaza "leads/semana" |
| **CAC** | Costo de adquirir una cuenta (tu tiempo + ads + contenido). Parcialmente **cubierto por el fee de Build** | < USD 1,500; idealmente neto ≤ 0 vía Build |
| **LTV** | MRR × margen bruto × vida media (meses) + margen del Build | > 4× CAC |
| **Vida media** | 1 / churn mensual | > 30 meses |
| **Churn lógico** | % cuentas que cancelan el recurrente / mes | < 2–3% mensual |
| **NRR** | Expansión (Grow) − contracción − churn, sobre base | > 110% |
| **Payback CAC** | Meses de MRR para recuperar CAC | < 3 meses (o inmediato vía Build) |

### 5.2 Ejemplo trabajado (una cuenta tipo, a validar)

```
Build (one-time):           USD 3,000   margen ~70% → 2,100
Operate (MRR):              USD   500   margen ~80%
Vida media estimada:        36 meses
CAC estimado:               USD 1,000   (− financiado parcial por Build)

LTV recurrente  = 500 × 0.80 × 36          = USD 14,400
LTV total       = 14,400 + 2,100 (Build)   = USD 16,500
LTV : CAC       = 16,500 / 1,000           ≈ 16:1   (objetivo > 4:1 ✔, holgado)
Payback         ≈ inmediato (Build cubre CAC) ✔
```

> El número clave a vigilar no es el LTV optimista — es **el churn y la vida
> media**. Si las cuentas no se quedan, todo el modelo colapsa. Por eso la
> defensibilidad (§6) no es teoría: es la variable que sostiene el negocio.

### 5.3 Construcción de ARR (escenario base de arranque)

| Hito | Cuentas recurrentes | MRR prom. | ARR aprox. |
|---|---|---|---|
| 10 cuentas | 10 | USD 500 | USD 60K |
| 25 cuentas | 25 | USD 600 (expansión) | USD 180K |
| 60 cuentas | 60 | USD 700 | USD 500K |

Con costo marginal decreciente vía Core, el margen mejora a medida que sube la
cuenta — lo contrario a una agencia, donde el margen se erosiona con la escala.

---

## 6. Defensibilidad, ventajas competitivas y foso económico

"Premium aesthetic" **no es un foso** (revisión crítica §3). Estos sí:

### 6.1 Foso 1 — Switching costs (el más fuerte hoy)

ARVIOR no entrega un archivo; **opera la infraestructura que corre el negocio del
cliente.** Su web, sus leads, sus automatizaciones, sus agentes de IA viven en
ARVIOR. Cancelar no es "cambiar de proveedor", es **detener su operación**. Cuanto
más profundo se integra (Grow), más alto el costo de salida. Esto es lo que
convierte el churn bajo de §5 en algo estructural, no en suerte.

### 6.2 Foso 2 — Plataforma propia (ARVIOR Core)

Costo marginal decreciente por cliente: cada build futuro es más rápido y barato que
el de un competidor que empieza de cero. Ventaja de **margen + velocidad** que se
agranda con cada cliente. Un competidor-agencia no la tiene porque cada proyecto le
cuesta lo mismo siempre.

### 6.3 Foso 3 — Datos y playbooks (el foso de 5–10 años)

Operar muchas cuentas del mismo vertical genera **datos propietarios**: qué
conversiones funcionan, qué automatizaciones rinden, qué seguimiento de IA cierra
más. Eso alimenta un **flywheel**: cada cliente mejora el sistema → el sistema
mejora a cada cliente nuevo → ARVIOR rinde más que cualquiera que empiece de cero.
Es el foso que la estética nunca dará.

### 6.4 Foso 4 — Foco por problema + reputación

ARVIOR no se ancla a un vertical: se ancla a un **problema replicable** (captar,
calificar, seguir, convertir) que se repite en muchos rubros. Dominar ese problema
crea **lenguaje, casos y referidos** que un generalista no puede igualar, sin
encerrar a ARVIOR en una sola industria (ver
[`ARVIOR_REVENUE_SYSTEM.md`](ARVIOR_REVENUE_SYSTEM.md) §1). La marca premium (Brand
System) aquí sí aporta: es el *envoltorio* del foso, no el foso.

### 6.5 Resumen del foso económico

| Foso | Tipo | Madurez | Fuerza |
|---|---|---|---|
| Switching costs | Estructural | Inmediato | Alta |
| Plataforma (Core) | Costo marginal | Mediano plazo | Alta y creciente |
| Datos / playbooks | Flywheel | Largo plazo | Decisiva |
| Vertical + reputación | Marca/red | Mediano plazo | Media-alta |

El foso económico real = **ingreso recurrente + costo marginal decreciente +
expansión dentro de la cuenta + datos que mejoran el producto.** Cuatro fuerzas que
una agencia, por definición de su modelo, no puede acumular.

---

## 7. Roadmap de empresa (1 / 3 / 5 / 10 años)

No roadmap de features — **roadmap de negocio.** Cada hito define la pregunta que
hay que responder antes de avanzar.

### Año 1 — *Validar el wedge y nacer el recurrente*
- **Producto:** Revenue System estandarizado sobre el repo actual; primeros módulos de automatización + IA en producción.
- **Negocio:** 10–25 cuentas recurrentes. **ARR USD 60–180K.** Build financia el CAC.
- **Foco:** dominar **un problema replicable** (el Revenue System) en un conjunto de rubros con el mismo dolor — no un solo vertical (ver [`ARVIOR_REVENUE_SYSTEM.md`](ARVIOR_REVENUE_SYSTEM.md)). Reemplazar portfolio placeholder por 3–5 casos reales con métricas.
- **Pregunta a responder:** ¿el cliente paga mensual y se queda? (churn < 3%).

### Año 3 — *Plataforma interna y dominio de vertical*
- **Producto:** ARVIOR Core maduro; cada build se monta sobre él. Self-serve parcial para onboarding.
- **Negocio:** 60–150 cuentas. **ARR USD 0.5–2M.** Equipo 6–15 personas. NRR > 110%.
- **Foco:** 1–2 verticales dominados; flywheel de datos encendido.
- **Pregunta a responder:** ¿el costo marginal por cuenta baja con la escala? (margen sube, no baja).

### Año 5 — *Producto que escala sin headcount*
- **Producto:** SaaS vertical(es) derivado(s) de Core; ingreso de producto > ingreso de servicio.
- **Negocio:** **ARR USD 10M+.** NRR > 120%. Rentable o con opción de levantar capital para acelerar.
- **Foco:** multi-vertical; datos propietarios como ventaja defendible y vendible.
- **Pregunta a responder:** ¿ARVIOR crece sin que el fundador esté en cada cuenta?

### Año 10 — *Plataforma de categoría*
- **Producto:** "sistema operativo" de la PYME en el segmento ARVIOR — web + operación + automatización + IA en una plataforma.
- **Negocio:** **ARR USD 50–100M+**, ingreso mayoritariamente de producto recurrente.
- **Foco:** categoría propia, posible M&A o salida estratégica; venture studio lanzando producto propio (Masterplan §8 H3).
- **Pregunta a responder:** ¿ARVIOR es dueña de una categoría, o sigue compitiendo por proyectos?

> El hilo conductor: **cada año, más % del ingreso es recurrente y de producto,
> menos % es servicio bespoke.** Si esa proporción no se mueve año a año, ARVIOR se
> está estancando como agencia, sin importar cuánto facture.

---

## 8. Contradicciones resueltas (cierre de la revisión crítica)

| # | Contradicción detectada | Resolución en este modelo |
|---|---|---|
| **2** | Visión "dejar de venderse proyecto por proyecto" vs todo optimizado a leads | El proyecto (Build) es el **CAC financiado**; el negocio es el recurrente (Operate + Grow). No se elimina el proyecto, se le da su rol correcto (§0, §3) |
| **6** | Copy en inglés vs operación en español → mercado indefinido | **Mercado primario: LatAm hispanohablante** (ICP §1.1). Español primero; inglés se reserva para el ICP secundario internacional/US-hispano. Decisión tomada, no ambigua |
| **1** | "No vendemos páginas" vs línea #1 = Websites | "Websites" nunca se vende solo: es el front-end del Revenue System (§2.2) |
| **3** | "Premium" vs infra de bajo costo | La infra simple se recategoriza como **ventaja de margen** del recurrente, no como valor de marca (§3.2) |
| **4** | "Confiabilidad sobre novedad" vs IA destacada | La IA se despliega como **agentes acotados y confiables** sobre rieles probados (Operate), no como features experimentales sueltas |

---

## 9. Qué medir desde el día 1 (reemplaza los KPIs de agencia)

| Métrica vieja (agencia) | Métrica nueva (empresa de software) |
|---|---|
| Leads cualificados / semana | **MRR / ARR y su crecimiento** |
| Proyectos cerrados | **Cuentas recurrentes netas (altas − bajas)** |
| LCP / tiempo de deploy | Siguen, pero como higiene, no como KPI de negocio |
| — | **Churn lógico, NRR, LTV:CAC, payback, % ingreso recurrente** |

Los KPIs técnicos del Masterplan §10 quedan como métricas de calidad. **El tablero
de negocio de ARVIOR es ARR, churn, NRR y % de ingreso recurrente** — ahí se decide
si ARVIOR es una empresa de tecnología o una agencia con buen diseño.

---

## 10. Documentos relacionados

- [`ARVIOR_REVENUE_SYSTEM.md`](ARVIOR_REVENUE_SYSTEM.md) — la oferta inicial concreta: beachhead por problema, tres escenarios de Build y planes de Operate.
- [`ARVIOR_MASTERPLAN.md`](ARVIOR_MASTERPLAN.md) — identidad, líneas, expansión (§8 Horizontes).
- [`ARVIOR_BRAND_SYSTEM.md`](ARVIOR_BRAND_SYSTEM.md) — el envoltorio premium del foso.
- [`ARVIOR_HOMEPAGE_ARCHITECTURE.md`](ARVIOR_HOMEPAGE_ARCHITECTURE.md) — la home como motor de adquisición (Build/CAC).
