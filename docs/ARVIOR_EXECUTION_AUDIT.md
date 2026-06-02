# ARVIOR — Execution Audit (auditoría crítica de ejecutabilidad)

> Auditoría operativa de los 10 documentos aprobados. **No propone estrategia
> nueva ni modifica nada**: detecta dónde la ejecución se puede romper antes de
> que ocurra. Es el insumo del sistema operativo
> ([`ARVIOR_OPERATING_SYSTEM.md`](ARVIOR_OPERATING_SYSTEM.md)).
> Última revisión: 2026-06-02 · Estado: auditoría / no normativo
>
> Documentos auditados: Masterplan · Brand System · Homepage Architecture ·
> Business Model · Revenue System · Offer · Sales System · Onboarding System ·
> Retention System · Revenue Operations.

---

## 0. Cómo leer esta auditoría

La estrategia de ARVIOR es **coherente y madura**. El cuerpo de documentos ya
resolvió las contradicciones de identidad (agencia vs empresa, web vs sistema,
inglés vs español). Esta auditoría no las re-abre.

Lo que audita es otra cosa: **el salto de "documento aprobado" a "empresa que se
opera un lunes a las 9 am con una sola persona".** Ahí aparecen los problemas
reales, y son de tres tipos:

| Severidad | Qué significa | Cuántos |
|---|---|---|
| 🔴 **Bloqueante** | Rompe la ejecución o el margen si no se resuelve antes de escalar | 5 |
| 🟡 **Tensión** | Inconsistencia real entre documentos; hay que elegir una versión | 7 |
| 🟢 **Brecha** | Falta un mecanismo operativo; no es contradicción, es ausencia | 6 |

Cada hallazgo cita el documento y la sección exactos, describe el riesgo concreto
y propone una **dirección de resolución** (no una decisión: la decisión es del
fundador). Las resoluciones se materializan en los otros tres documentos de este
sistema.

---

## 1. Hallazgos bloqueantes (🔴)

### 1.1 🔴 El fundador es el único recurso, y todo lo crítico lo requiere a él

**Evidencia.** El Sales System §1 asigna dueños por etapa: *Marketing/inbound,
Comercial, Entrega, Operaciones, Ops+Comercial*. Hoy, según el brief, sólo existen
**fundador + colaboradores externos.** Es decir: los seis roles son la misma
persona. Además, lo más difícil de delegar está deliberadamente atado al fundador:

- **Venta:** el diagnóstico en vivo (Sales §4), la propuesta presentada en vivo
  (Sales §5.3 "nunca solo por mail"), el manejo de objeciones (Sales §6).
- **Retención:** el reporte de ROI mensual **en vivo** por cuenta (Retention §3.2),
  el plan de rescate de cuentas en rojo (Retention §2.3), la revisión estratégica
  (Retention §3.3).
- **Onboarding:** el kickoff (Onboarding §2), el primer reporte de ROI en vivo
  (Onboarding §6.1).

**El riesgo concreto.** El modelo (Business Model §5.3) proyecta 10 → 25 → 60
cuentas. Una reunión de ROI **en vivo mensual** por cuenta es: 25 cuentas × ~45
min = ~19 h/mes sólo en reportes, antes de vender, construir o rescatar. A 60
cuentas el modelo es físicamente imposible para una persona. **El negocio choca
con el límite del fundador mucho antes del hito de "Tracción".**

**Dirección de resolución.** No es contratar ya; es **diseñar el orden de
delegación y de automatización antes de necesitarlo** → ver
[`ARVIOR_OPERATING_SYSTEM.md`](ARVIOR_OPERATING_SYSTEM.md) §6 (cuellos de botella)
y §1 (estructura por fases). El reporte de ROI "en vivo" debe degradarse con
elegancia (en vivo para cuentas premium / en riesgo; reporte estandarizado
auto-generado + opcional para verdes) antes de las 25 cuentas, o el churn lo
provocará el propio fundador por falta de tiempo.

---

### 1.2 🔴 Contradicción de cobro: "Operate desde el día 1" vs garantía de puesta en marcha

**Evidencia — dos reglas que chocan:**

- Offer §12 (regla 3) y Onboarding §0 (regla 3): *"El Operate se cobra desde el
  día 1, no 'cuando esté listo'."*
- Offer §13 (garantía): *"si en 90 días el sistema no está capturando y haciendo
  seguimiento de leads como se prometió, **seguimos trabajando sin cobrar Operate
  hasta que funcione**."*

**El riesgo concreto.** Son operativamente incompatibles tal como están escritas:
no se puede a la vez cobrar Operate desde el día 1 **y** no cobrarlo hasta que
funcione. En una disputa de cobro, el cliente citará la garantía; ARVIOR citará la
regla. Sin una definición clara de **qué activa el reloj del cobro** (¿la firma?
¿el primer lead capturado? ¿el go-live?), el primer cliente difícil genera fricción
de caja y de relación.

**Dirección de resolución.** Definir el **gatillo de facturación de Operate** de
forma única y escribirlo en el contrato: lo más defendible es *"Operate se factura
desde el go-live / primer lead capturado (F1), no desde la firma"*, lo que alinea
las dos reglas (el cobro empieza cuando hay valor demostrable, dentro de los 90
días de la garantía). Esto debe quedar en el SOP de cierre y en el contrato → ver
[`ARVIOR_SOPS_MAP.md`](ARVIOR_SOPS_MAP.md) (SOP-05 Cierre/Contrato).

---

### 1.3 🔴 Brecha de arquitectura: la operación que se vende no corre sobre la infra que se describe

**Evidencia.** El Masterplan §7 define la infra como principio no negociable:
*"PHP plano + MySQL, sin frameworks, sin Node en producción, sin build, sin cache
de salida, cada request renderiza."* Pero la oferta recurrente vende, sobre esa
misma base:

- **Respuesta automática al lead en < 5 minutos** (Offer §13, garantía) — requiere
  un proceso disparado por evento, siempre activo.
- **Agente de IA conversacional 24/7** que responde, califica y agenda (Offer §8).
- **Secuencias de seguimiento multicanal** (email + WhatsApp) con recordatorios
  (Revenue System §2) — requieren cola de trabajos y scheduler.

**El riesgo concreto.** "PHP plano en hosting compartido, sin cache, cada request
renderiza" **no describe un runtime para automatización 24/7.** Responder en < 5
min, correr agentes de IA y disparar secuencias necesita: webhooks entrantes,
cron/colas, workers persistentes, gestión de tokens de IA, y reintentos. Nada de
eso está en la arquitectura aprobada. **La garantía central de la oferta (< 5 min)
depende de infraestructura que el Masterplan explícitamente excluye.** Si se vende
antes de resolverlo, ARVIOR incumple su propia garantía en producción.

**Dirección de resolución.** Esto **no contradice** "simplicidad como ventaja",
pero exige reconocer que la *capa de automatización* es un sistema aparte del
*sitio público*. Hay que definir el stack mínimo de automatización (cron del
cPanel + cola en MySQL + worker, o un servicio externo de automatización/colas) y
declararlo como parte de "ARVIOR Core" → ver
[`ARVIOR_OPERATING_SYSTEM.md`](ARVIOR_OPERATING_SYSTEM.md) §4 (sistema de gestión)
y §6 (qué automatizar primero). Es la decisión técnica más urgente del negocio.

---

### 1.4 🔴 Dependencia de WhatsApp sin plan operativo ni de cumplimiento

**Evidencia.** WhatsApp es central en captura y en la promesa de respuesta:
Revenue System §2 (captura form + WhatsApp), Offer §4 y §13 (respuesta < 5 min),
Onboarding §3.2 (acceso a *WhatsApp Business* como insumo de la fase de captura).

**El riesgo concreto.** La WhatsApp Business **API** (la única que permite
automatizar respuestas) tiene restricciones que ningún documento aborda: ventana
de 24 h para mensajes libres, **plantillas que Meta debe pre-aprobar**, costo por
conversación, y prohibición de ciertos usos. Automatizar "respuesta < 5 min" y
"nurture multicanal" por WhatsApp **no es trivial ni gratis**, y un bloqueo de Meta
deja sin operar la promesa central a todas las cuentas a la vez (riesgo
correlacionado). El modelo de margen 75–85% (Business Model §3.2) no incluye el
costo por conversación de WhatsApp.

**Dirección de resolución.** Decidir explícitamente el camino de WhatsApp (API
oficial vía proveedor vs. respuesta humana asistida al inicio), incorporar su costo
al margen del recurrente, y tener un canal de respaldo (email/SMS) para que la
garantía de < 5 min no dependa de un solo proveedor → ver
[`ARVIOR_OPERATING_SYSTEM.md`](ARVIOR_OPERATING_SYSTEM.md) §4 y la entrada de riesgo
en §6.

---

### 1.5 🔴 El Build de Entrada no cubre el CAC, pero todo el modelo asume que sí

**Evidencia — los números no cierran en el escalón de entrada:**

- CAC objetivo: *< USD 1.500; idealmente neto ≤ 0 vía Build* (Business Model §5.1,
  RevOps §1.3).
- Build **Entrada**: USD 850 – 1.400 (Offer §6, Revenue System §3.2).
- Tesis repetida en todos los docs: *"el Build financia / cubre el CAC"*
  (Business Model §0, §3.1; Revenue System §3; Sales §0).

**El riesgo concreto.** Para el escalón Entrada, **Build (850–1.400) < CAC (hasta
1.500).** El one-time **no** cubre la adquisición; ARVIOR entra en negativo y
recupera vía un MRR Core de apenas USD 95–170/mes — payback de varios meses, no
"inmediato". Como Entrada existe para "bajar la fricción" (Revenue System §3.2),
es probable que sea un volumen relevante de clientes. **La afirmación "Build cubre
CAC" es cierta para Profesional/Premium, falsa para Entrada**, y el modelo la
trata como universal.

**Dirección de resolución.** Aceptar que Entrada es un **producto de adquisición de
bajo/negativo margen one-time** cuyo retorno es 100% recurrente, y por tanto: (a)
limitar el CAC pagado en Entrada (sólo referidos / inbound barato, nunca ads
costosos), o (b) subir el piso de Entrada. La métrica a vigilar para Entrada no es
"payback inmediato" sino **payback < 6 meses**. Esto se gobierna en el dashboard →
ver [`ARVIOR_FOUNDER_DASHBOARD.md`](ARVIOR_FOUNDER_DASHBOARD.md) (segmentar CAC y
payback por escalón).

---

## 2. Tensiones e inconsistencias entre documentos (🟡)

### 2.1 🟡 Rangos de precios incoherentes entre Business Model y Offer/Revenue System

**Evidencia.** El Business Model y la Oferta dan rangos distintos para lo mismo:

| Concepto | Business Model §3.2 | Offer / Revenue System |
|---|---|---|
| **Build (one-time)** | USD 1.500 – 6.000 | USD 850 – 7.400 (Entrada 850–1.400 … Premium 4.400–7.400) |
| **Operate (MRR)** | USD 200 – 1.200 / mes | USD 95 – 1.300 / mes (Core 95–170 … Intelligence 680–1.300) |

**El riesgo.** Los pisos y techos no coinciden: el Business Model omite el escalón
Entrada por abajo (1.500 vs 850) y se queda corto por arriba (6.000 vs 7.400; 1.200
vs 1.300). Quien cite el Business Model para un board dirá un número; quien cite la
Oferta para un cliente dirá otro. **No es grave conceptualmente, pero es exactamente
el tipo de inconsistencia que erosiona la confianza interna en los números.**

**Dirección.** La Oferta y el Revenue System son la fuente de verdad de precios
(son los documentos "vivos/comerciales"); el Business Model debería citar esos
rangos, no unos propios. Nota para una futura revisión del Business Model (fuera del
alcance de este sistema, que no modifica documentos existentes).

---

### 2.2 🟡 "ACV recurrente" del Business Model no reconcilia con la grilla de planes

**Evidencia.** Business Model §3.2 da *ACV recurrente USD 3.000 – 18.000 / año*.
Con la grilla de la Oferta: Core (95–170/mes) → 1.140–2.040/año; Intelligence
(680–1.300/mes) → 8.160–15.600/año. El piso real del recurrente (Core ≈ 1.140/año)
está **por debajo** del piso del ACV declarado (3.000), y el techo (Intelligence +
Grow) puede superar 18.000.

**El riesgo.** El ACV es la base de las proyecciones de ARR (Business Model §5.3:
10 cuentas × USD 500 MRR = USD 60K). Pero USD 500 MRR es Growth, no el promedio de
una cartera que incluye muchos Core (95–170). **Las proyecciones de ARR asumen un
mix de cartera más rico del que el embudo probablemente produzca al inicio**, donde
Entrada/Core es el escalón de menor fricción.

**Dirección.** Modelar el ARR con un **mix realista de cartera** (p. ej. 50%
Entrada/Core, 35% Profesional/Growth, 15% Premium/Intelligence) en el dashboard, en
vez de un MRR promedio plano → ver
[`ARVIOR_FOUNDER_DASHBOARD.md`](ARVIOR_FOUNDER_DASHBOARD.md).

---

### 2.3 🟡 NRR: ">100%" vs ">110%" vs ">120%" usados de forma intercambiable

**Evidencia.** Objetivo de NRR aparece como: *> 100%* (Business Model §3.1 título
"NRR > 100%"; Revenue System §4.4), *> 110%* (Business Model §5.1 y §6; Revenue
System §6; Sales §9.1; RevOps §1.2; Retention §0), y *> 120%* (Business Model §7,
Año 5).

**El riesgo.** Menor, pero real: el objetivo operativo de arranque debe ser **uno**.
Mezclar 100/110 en la misma etapa hace que el tablero no tenga una línea roja clara.

**Dirección.** Fijar **NRR > 110% como objetivo de arranque** (es el más citado) y
reservar 120% para la etapa de Escala. Se consolida en el dashboard.

---

### 2.4 🟡 El cronograma de onboarding promete el "primer lead" antes de lo que la entrega permite

**Evidencia.** Onboarding §6 (tabla del primer mes) sitúa *"Primer lead real
capturado y notificado"* en la **Semana 2**. Pero el plazo de entrega de
Profesional es ~4–6 semanas (Offer §7) y la fase F1 (captura) depende de que el
cliente entregue dominio, DNS y WhatsApp (Onboarding §3.2), accesos cuyo atraso "es
del cliente" (§3.3).

**El riesgo.** "Primer lead en semana 2" es la promesa de retención más importante
(Time-to-First-Lead, Onboarding §0). Si se incumple sistemáticamente porque la
realidad es 4–6 semanas, **el mecanismo central de retención temprana falla en cada
cuenta.** Es una promesa interna optimista que choca con el propio plazo comercial.

**Dirección.** Separar dos hitos: **"captura técnicamente lista"** (lo que ARVIOR
controla) y **"primer lead real"** (depende del tráfico del cliente). Comprometer
sólo el primero por fecha; tratar el segundo como evento esperado, no prometido.
Reflejar en el SOP de onboarding → ver
[`ARVIOR_SOPS_MAP.md`](ARVIOR_SOPS_MAP.md) (SOP-07).

---

### 2.5 🟡 "Permanencia sugerida 12 meses" vs "sin permanencia forzada" — ¿qué se firma?

**Evidencia.** Offer §9–§11: *"Permanencia sugerida 12 meses, luego mensual"*.
Offer §13: *"Sin permanencia forzada tras el período inicial"*. Sales §7.2: el
contrato incluye *"permanencia sugerida 12 meses"*.

**El riesgo.** "Sugerida" y "período inicial" son ambiguos para un contrato. ¿El
cliente puede cancelar en el mes 2? Si sí, el churn temprano (riesgo conocido,
Offer §14) no tiene fricción contractual alguna; si no, "sin permanencia forzada"
es falso. **El contrato real necesita una respuesta binaria que los documentos no
dan.**

**Dirección.** Decidir el término exacto: lo más coherente con "permanencia se gana,
no se amarra" (Retention §1) es **mes a mes con aviso de 30 días desde el inicio**,
y usar el reporte de ROI —no la cláusula— como retención. Pero debe **decidirse y
escribirse** en la plantilla de contrato (SOP-05).

---

### 2.6 🟡 Las "12 métricas del fundador" se definen en dos documentos; pueden divergir

**Evidencia.** Las 12 métricas del tablero están en Sales §9.1 **y** en RevOps §3.4,
y el health score en Retention §2.1 **y** RevOps §4. Hoy coinciden.

**El riesgo.** Definición duplicada = futura divergencia garantizada. Cuando alguien
ajuste una fórmula en RevOps y no en Sales (o viceversa), el tablero tendrá dos
verdades. No es un problema hoy; es deuda estructural.

**Dirección.** Declarar **RevOps como fuente de verdad única** de métricas y health
score; los demás documentos sólo lo citan. Es una convención, se fija en el
Operating System §4 (documentación) y se respeta de aquí en adelante.

---

### 2.7 🟡 Mercado "LatAm hispanohablante" vs precios en CLP vs copy del sitio en inglés

**Evidencia.** Business Model §1.1 y §8: *mercado primario LatAm hispanohablante*.
Revenue System §3 y Offer: precios **en CLP (Chile)** con referencia USD. Masterplan
§2.4: one-liners **en inglés**. Homepage (Masterplan) en inglés.

**El riesgo.** La contradicción de *identidad* (idioma) ya se declaró resuelta
(Business Model §8, contradicción 6). Pero queda una **brecha operativa**: si el
mercado de arranque es Chile (precios CLP), la operación real necesita facturación,
impuestos (IVA chileno, boleta/factura), y pasarela de pago **chilenos**, mientras
el sitio público comunica en inglés a "LatAm". Operar cobros en 1 país es distinto
a operar en "LatAm". Ningún documento define el **rail de cobro y facturación por
país**.

**Dirección.** Declarar **Chile como mercado operativo de arranque** (donde está la
facturación y la pasarela), con LatAm como expansión, y definir el stack de cobro →
ver [`ARVIOR_OPERATING_SYSTEM.md`](ARVIOR_OPERATING_SYSTEM.md) §4.

---

## 3. Brechas operativas (🟢 — falta el mecanismo, no hay contradicción)

| # | Brecha | Evidencia / por qué importa | Dónde se resuelve |
|---|---|---|---|
| 3.1 🟢 | **No existe estructura organizacional ni plan de delegación** | Sales §1 nombra 5 dueños; hoy todos son el fundador. No hay definición de qué se contrata/terceriza primero ni cuándo | Operating System §1, §6 |
| 3.2 🟢 | **No hay sistema de gestión definido (CRM/Notion/Drive/GitHub)** | RevOps §0 dice "una sola fuente de verdad … el propio sistema de ARVIOR", pero ese sistema (CRM) **aún no existe construido**; el repo tiene leads, no pipeline de 5 etapas | Operating System §4 |
| 3.3 🟢 | **Ningún SOP escrito todavía** | Los documentos describen procesos (venta, onboarding, reporte) pero no hay checklists ejecutables paso a paso versionados | SOPs Map (todos) |
| 3.4 🟢 | **No hay definición de "Definition of Done" por entregable** | Onboarding §4 lista fases con "entregable visible", pero no criterios de aceptación objetivos → riesgo de scope creep y disputas de go-live | Operating System §2; SOP-07/08 |
| 3.5 🟢 | **No hay plan de continuidad / bus factor** | Toda la operación, accesos y conocimiento viven en una persona. Sin documentación de accesos, contraseñas, ni respaldo, una indisponibilidad del fundador detiene 100% del negocio | Operating System §6; SOP-12 |
| 3.6 🟢 | **El "caso #0" (ARVIOR usa su propio sistema) aún no está construido** | Sales §2.1 y RevOps §0 lo afirman como principio, pero el pipeline de 5 etapas, el scoring y el reporte de ROI no existen en el repo todavía. Vender lo que uno no usa contradice "el sistema es la entrega" (Masterplan §5.1) | Operating System §6 (qué construir primero); Founder Dashboard |

---

## 4. Riesgos por categoría (vista de gestión)

> Consolidación de lo anterior por tipo de riesgo, para el tablero del fundador.
> Probabilidad e impacto son cualitativos (arranque).

### 4.1 Riesgos operativos

| Riesgo | Prob. | Impacto | Mitigación primaria |
|---|:---:|:---:|---|
| Fundador como único cuello de botella (§1.1) | Alta | Crítico | Orden de delegación + automatización (OS §6) |
| Infra no soporta automatización 24/7 (§1.3) | Alta | Crítico | Definir stack de Core antes de vender Premium |
| Bloqueo / costo de WhatsApp (§1.4) | Media | Alto | Proveedor API + canal de respaldo |
| Conocimiento y accesos sin respaldo (§3.5) | Media | Crítico | Bóveda de accesos + documentación |
| Scope creep por falta de DoD (§3.4) | Alta | Medio | Alcance cerrado + expansión, no regalo |

### 4.2 Riesgos financieros

| Riesgo | Prob. | Impacto | Mitigación primaria |
|---|:---:|:---:|---|
| Entrada con CAC > Build (§1.5) | Alta | Medio | Limitar CAC pagado en Entrada; payback < 6m |
| Margen recurrente erosionado por tiempo del fundador en cuentas Core (§1.1) | Alta | Alto | Estandarizar/automatizar reporte; degradar "en vivo" |
| Costo de IA/WhatsApp no contemplado en margen (§1.4) | Media | Medio | Incluir costo variable en pricing de Intelligence |
| Proyección de ARR con mix optimista (§2.2) | Media | Medio | Modelar mix realista de cartera |

### 4.3 Riesgos comerciales

| Riesgo | Prob. | Impacto | Mitigación primaria |
|---|:---:|:---:|---|
| Churn temprano sin fricción contractual (§2.5) | Media | Alto | Definir término de contrato + retención por valor |
| Promesa de "primer lead semana 2" incumplida (§2.4) | Alta | Alto | Separar hito técnico de hito dependiente de tráfico |
| Dependencia de referidos del fundador (canal #1 finito, Sales §2.1) | Alta | Alto | Encender inbound (caso #0) y outbound disciplinado |
| Garantía < 5 min incumplible sin infra (§1.3) | Alta | Crítico | No vender la garantía hasta que el runtime exista |

---

## 5. Procesos que se solapan (duplicidad de responsabilidad)

| Solapamiento | Documentos | Por qué importa |
|---|---|---|
| **Gatillos y mecánica de expansión** | Sales §8 (mecánica) y Retention §5.3 (regla) describen lo mismo desde dos lados | Riesgo de doble dueño o de nadie: ¿la expansión la dispara Comercial o el Responsable de cuenta? Hoy ambos = fundador, pero al delegar hay que definir el dueño único |
| **Reporte de ROI / health score** | Onboarding §6.1, Retention §3, RevOps §4 lo tocan | Tres documentos, un solo entregable mensual. Debe haber **un** SOP dueño (SOP-09) que los demás citen |
| **Pedido de referidos** | Onboarding §1.2 (día 0) y §7 (mes 1) y Sales §7.3 (al cierre) | Tres momentos distintos de pedir referido sin un dueño ni un tope; riesgo de fatigar al cliente o de que nadie lo pida |
| **Criterio de admisión (3/4)** | Revenue System §1.2, Business Model §1, Sales §2.2, Offer §14 | Coherente, pero definido 4 veces. Fuente de verdad: Revenue System; los demás citan |

---

## 6. Lo que está bien (para no romper lo que funciona)

Una auditoría honesta también marca las fortalezas, para protegerlas al operar:

- **La cadena lógica es sólida y sin huecos conceptuales:** lead → venta →
  onboarding → operate → retención → expansión está completa y bien cruzada entre
  documentos.
- **La jerarquía de métricas es correcta:** churn primero, MRR/NRR después, embudo
  al final (Sales §9.2, RevOps §2.1). No hay que tocarla.
- **La disciplina de foco (criterio de admisión 3/4) es real y repetida** — el
  riesgo es relajarla bajo presión de caja, no que falte.
- **La cadencia de gobierno (semanal/mensual/trimestral/anual) ya está definida**
  (RevOps §2) — sólo falta ejecutarla con un tablero real.
- **El foso (switching cost + Core + datos) es coherente con el modelo de cobro.**

> La conclusión de la auditoría: **la estrategia no necesita más pensamiento;
> necesita un sistema operativo, cuatro o cinco decisiones binarias pendientes
> (cobro de Operate, contrato, infra de automatización, WhatsApp, CAC de Entrada) y
> los SOPs que conviertan los documentos en checklists.** Eso es lo que construyen
> los otros tres documentos de este sistema.

---

## 7. Decisiones binarias que el fundador debe tomar (resumen accionable)

Estas son las decisiones que ningún documento toma y que **bloquean la operación**
hasta resolverse. No las decide esta auditoría; las pone sobre la mesa.

| # | Decisión pendiente | Opción por defecto sugerida | Bloquea |
|---|---|---|---|
| D1 | ¿Cuándo se factura Operate? (firma / F1 / go-live) | **Go-live / primer lead (F1)** | Contrato, SOP-05 |
| D2 | ¿Término del contrato? (12m forzado / mes a mes 30d) | **Mes a mes, aviso 30 días** | Contrato, churn |
| D3 | ¿Stack de automatización? (cron+cola propia / servicio externo) | **Cron cPanel + cola MySQL + worker** para arrancar | Garantía < 5 min, Premium |
| D4 | ¿Camino de WhatsApp? (API vía proveedor / humano asistido) | **Humano asistido al inicio; API al escalar** | Captura, margen |
| D5 | ¿CAC máximo pagado en Entrada? | **Sólo referido/inbound; cero ads pagas** | Unit economics |
| D6 | ¿Mercado operativo de cobro de arranque? | **Chile (CLP) primero; LatAm expansión** | Facturación, pasarela |

> Estas seis decisiones, tomadas, desbloquean ~80% de la ejecución. Están diseñadas
> para resolverse en una sola sesión del fundador.

---

## 8. Documentos relacionados

- [`ARVIOR_OPERATING_SYSTEM.md`](ARVIOR_OPERATING_SYSTEM.md) — el sistema operativo que resuelve las brechas de §3.
- [`ARVIOR_SOPS_MAP.md`](ARVIOR_SOPS_MAP.md) — los SOPs que faltan (§3.3).
- [`ARVIOR_FOUNDER_DASHBOARD.md`](ARVIOR_FOUNDER_DASHBOARD.md) — el tablero que gobierna los riesgos de §4.
- Documentos auditados: [`ARVIOR_MASTERPLAN.md`](ARVIOR_MASTERPLAN.md) · [`ARVIOR_BUSINESS_MODEL.md`](ARVIOR_BUSINESS_MODEL.md) · [`ARVIOR_REVENUE_SYSTEM.md`](ARVIOR_REVENUE_SYSTEM.md) · [`ARVIOR_OFFER.md`](ARVIOR_OFFER.md) · [`ARVIOR_SALES_SYSTEM.md`](ARVIOR_SALES_SYSTEM.md) · [`ARVIOR_ONBOARDING_SYSTEM.md`](ARVIOR_ONBOARDING_SYSTEM.md) · [`ARVIOR_RETENTION_SYSTEM.md`](ARVIOR_RETENTION_SYSTEM.md) · [`ARVIOR_REVENUE_OPERATIONS.md`](ARVIOR_REVENUE_OPERATIONS.md).
</content>
</invoke>
