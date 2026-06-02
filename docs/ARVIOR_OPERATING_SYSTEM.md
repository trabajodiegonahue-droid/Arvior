# ARVIOR — Operating System (el sistema operativo de la empresa)

> Cómo ARVIOR **se opera**, no qué vende. Convierte la estrategia aprobada en una
> máquina: quién hace qué, en qué orden, con qué herramientas, y qué se delega y
> automatiza primero. Resuelve las brechas de
> [`ARVIOR_EXECUTION_AUDIT.md`](ARVIOR_EXECUTION_AUDIT.md) §3.
> Última revisión: 2026-06-02 · Estado: operativo / vivo
>
> Auditoría: [`ARVIOR_EXECUTION_AUDIT.md`](ARVIOR_EXECUTION_AUDIT.md) ·
> Procedimientos: [`ARVIOR_SOPS_MAP.md`](ARVIOR_SOPS_MAP.md) ·
> Tablero: [`ARVIOR_FOUNDER_DASHBOARD.md`](ARVIOR_FOUNDER_DASHBOARD.md)

---

## 0. El principio operativo

> **ARVIOR se opera como un sistema, igual que el producto que vende. Cada función
> del negocio tiene un dueño, una entrada, una salida y una herramienta. Si una
> tarea no tiene dueño, no existe; si no tiene SOP, no escala; si depende sólo de la
> memoria del fundador, es un riesgo, no un proceso.**

Tres reglas que gobiernan la operación:

1. **Un dueño por función, aunque hoy sean la misma persona.** Hoy el fundador es
   los cinco roles; los nombramos igual, para que delegar sea reasignar, no
   reinventar.
2. **El sistema antes que el esfuerzo.** Antes de trabajar más horas, se pregunta:
   ¿esto se puede convertir en SOP, plantilla o automatización?
3. **El fundador trabaja *sobre* el negocio, no sólo *en* el negocio.** Cada semana
   recupera tiempo del operar para dedicarlo a sistematizar (la transición de §6).

---

## 1. Estructura organizacional (hoy → mañana)

### 1.1 Las cinco funciones del negocio

Todo ARVIOR cabe en cinco funciones. Son las mismas que el Sales System §1 ya nombra
como "dueños"; aquí se definen como **cargos**, con responsabilidad y métrica.

| Función | Misión (una frase) | Responsabilidad central | Métrica que posee |
|---|---|---|---|
| **Comercial (Ventas)** | Convertir desconocidos en cuentas de Operate | Lead → diagnóstico → propuesta → cierre | MRR nuevo, ciclo de venta, conversión |
| **Entrega (Implementación)** | Poner el sistema a capturar y demostrar la primera victoria | Onboarding → build → go-live | Time-to-go-live, primer lead |
| **Operaciones (Operate)** | Mantener el sistema corriendo y rindiendo cada mes | Operar, optimizar, reporte de ROI | Uptime, ROI demostrado, % cuentas verdes |
| **Soporte / Éxito de cliente** | Que el cliente esté sano y se quede | Health score, anti-churn, rescates, renovación | Churn, NRR, health mix |
| **RevOps / Administración** | Que el negocio se mida y se cobre bien | Métricas, facturación, contratos, accesos | Exactitud del tablero, cobranza |

> Comercial y Soporte/Éxito comparten la **expansión** (Audit §5): la regla es
> **Soporte la detecta y la propone; Comercial la cotiza y cierra.** Un solo dueño
> de la decisión por etapa.

### 1.2 Hoy — el fundador-operador (fase 0)

```
                        ┌─────────────┐
                        │   FUNDADOR   │  ← las 5 funciones a la vez
                        └──────┬──────┘
        ┌──────────────┬───────┼───────┬──────────────┐
   Comercial       Entrega   Operaciones  Soporte    RevOps
        │              │          │          │           │
        └──── apoyado por colaboradores externos ────────┘
              (diseño, desarrollo puntual, contenido)
```

**Regla de la fase 0:** el fundador hace lo que **sólo él puede hacer** (vender,
decidir, relación con cuentas premium) y terceriza/automatiza el resto **lo antes
que el margen lo permita.** Los colaboradores externos son capacidad, no estructura:
entran por proyecto, no son dueños de función.

### 1.3 Mañana — el orden de incorporación (fases 1–2)

El orden no es opinión: se deriva de **dónde el fundador es más cuello de botella y
menos reemplazable** (ver §6). Se incorpora cuando el MRR lo financia, no antes.

| Orden | Rol a incorporar | Gatillo de incorporación | Por qué este orden | Descarga al fundador de |
|:---:|---|---|---|---|
| **1º** | **Implementador / Dev de entrega** (part-time → full) | ≥ 4–5 builds activos en paralelo o cola > 3 semanas | La entrega es delegable con SOP y no requiere el criterio comercial del fundador | Construir; libera tiempo para vender |
| **2º** | **Operador de cuentas / Customer Success** | ≥ 12–15 cuentas en Operate | El reporte mensual en vivo no escala (Audit §1.1); es la 2ª trampa de tiempo | Operar y reportar las cuentas verdes |
| **3º** | **Asistente comercial / SDR** | Pipeline > capacidad de seguimiento del fundador | Calificar y agendar es delegable; cerrar todavía no | Prospección, agenda, higiene de CRM |
| **4º** | **Administración / RevOps part-time** | > 25 cuentas facturando | Cobranza y métricas consumen tiempo de bajo criterio | Facturar, conciliar, mantener el tablero |

> **Lo que el fundador NO delega en fases 1–2:** el cierre comercial, la relación con
> cuentas premium/en riesgo, y las decisiones de arquitectura de Core. Eso es su
> trabajo irreemplazable hasta la fase 3.

### 1.4 Organigrama objetivo (fase 3 — escala, referencia)

```
                          FUNDADOR / CEO
                                │
        ┌───────────────┬───────┴────────┬──────────────┐
   Lead Comercial   Lead Entrega    Lead Operaciones   RevOps
        │               │                 │
     SDRs           Devs/Impl.     Operadores de cuenta
```

Coherente con Business Model §7 (equipo 6–15 personas en Año 3). No se construye
ahora; se nombra para que cada incorporación de §1.3 tenga su casillero.

---

## 2. Flujo operativo completo (de lead a expansión)

> Una sola línea de valor, siete estaciones. Cada estación: **dueño · entrada ·
> salida · herramienta · SOP.** Es la versión operable del embudo de Sales §1 y la
> cadena Onboarding→Retention.

```
 LEAD → VENTA → ONBOARDING → IMPLEMENTACIÓN → OPERATE → RETENCIÓN → EXPANSIÓN
   │      │         │              │            │          │            │
  RevOps Comercial Entrega      Entrega    Operaciones  Soporte   Soporte→Comercial
```

### 2.1 La tabla maestra del flujo

| # | Estación | Dueño | Entrada (trigger) | Salida (Definition of Done) | Herramienta | SOP |
|---|---|---|---|---|---|---|
| 1 | **Lead** | RevOps / inbound | Lead capturado por la web/WhatsApp/referido | Calificado ≥3/4, en CRM con score y próxima acción | CRM (pipeline etapa 0) | SOP-01 |
| 2 | **Venta** | Comercial | Lead calificado caliente/tibio | Contrato firmado + 1er pago + handoff entregado | CRM + plantilla propuesta + contrato | SOP-02..05 |
| 3 | **Onboarding** | Entrega | Cierre + paquete de handoff | Kickoff hecho, accesos recibidos, plan con fechas | Checklist onboarding + bóveda de accesos | SOP-06, SOP-07 |
| 4 | **Implementación** | Entrega | Kickoff aprobado | Go-live aceptado (DoD por fase), primer lead capturado | Repo/Core + checklist de fases | SOP-08 |
| 5 | **Operate** | Operaciones | Go-live | Sistema corriendo + reporte de ROI mensual entregado | Core (automatización) + plantilla de reporte | SOP-09, SOP-10 |
| 6 | **Retención** | Soporte/Éxito | Cuenta en Operate | Health verde sostenido, renovación construida | Health score + cadencia de cuenta | SOP-11 |
| 7 | **Expansión** | Soporte → Comercial | Gatillo de expansión observado | Upsell cotizado y aceptado (más MRR) | CRM (oportunidad de expansión) | SOP-12 |

> **Regla de oro del flujo:** **ninguna estación se cierra sin disparar la
> siguiente.** El cierre dispara onboarding el mismo día (Sales §7.3); el go-live
> dispara la cadencia de Operate (Onboarding §6.2); el health verde dispara la
> búsqueda de gatillo de expansión (Retention §2.1). Las transiciones son
> automáticas, no "cuando haya tiempo".

### 2.2 Los puntos de handoff (donde se pierde la información)

Los handoffs son donde un negocio de una persona se rompe al crecer. Cada uno tiene
un **artefacto obligatorio** que viaja con la cuenta:

| Handoff | De → A | Artefacto que viaja | Riesgo si falta |
|---|---|---|---|
| **Cierre → Onboarding** | Comercial → Entrega | Paquete de handoff (Onboarding §1.1) | El cliente repite lo que ya contó; se enfría |
| **Go-live → Operate** | Entrega → Operaciones | Ficha de cuenta: alcance, accesos, métrica de éxito | Operate no sabe qué prometió la venta |
| **Operate → Retención** | Operaciones → Soporte | Health score + histórico de ROI | El churn se ve cuando ya pasó |
| **Retención → Expansión** | Soporte → Comercial | Gatillo + dato que lo justifica | Se ofrece upsell sin contexto, desde la debilidad |

> Hoy todos los handoffs ocurren "dentro de la cabeza del fundador". El objetivo del
> sistema operativo es **sacarlos a un artefacto** para que el día que haya dos
> personas, la información ya viaje sola.

---

## 3. Los SOPs que la empresa necesita (índice)

> El mapa completo, con disparador, pasos y DoD de cada uno, vive en
> [`ARVIOR_SOPS_MAP.md`](ARVIOR_SOPS_MAP.md). Aquí, sólo el **índice priorizado**:
> qué SOP existe y en qué orden se escribe.

| SOP | Nombre | Estación | Prioridad |
|---|---|---|:---:|
| SOP-01 | Calificación y registro de lead | Lead | P0 |
| SOP-02 | Agenda y preparación de reunión | Venta | P1 |
| SOP-03 | Diagnóstico comercial | Venta | P0 |
| SOP-04 | Armado y presentación de propuesta | Venta | P0 |
| SOP-05 | Cierre, contrato y cobro | Venta | P0 |
| SOP-06 | Handoff comercial → entrega | Onboarding | P0 |
| SOP-07 | Kickoff y recolección de accesos | Onboarding | P1 |
| SOP-08 | Implementación por fases y go-live | Implementación | P0 |
| SOP-09 | Reporte de ROI mensual | Operate | P0 |
| SOP-10 | Operación y optimización mensual | Operate | P1 |
| SOP-11 | Health score y rescate de cuenta | Retención | P0 |
| SOP-12 | Expansión / upsell | Expansión | P1 |
| SOP-13 | Pedido de referidos | Transversal | P2 |
| SOP-14 | Gestión de accesos y continuidad (bus factor) | Transversal | P0 |
| SOP-15 | Cierre de mes (facturación + métricas) | RevOps | P1 |

> **Regla:** se escribe el SOP la **segunda vez** que se hace una tarea, no la
> primera (la primera enseña, la segunda sistematiza). Los **P0 se escriben antes de
> cerrar el segundo cliente.**

---

## 4. Sistema de gestión (cómo se conectan las herramientas)

> El error que mata a un negocio de servicios es la información dispersa: el lead en
> WhatsApp, el contrato en el mail, el proyecto en la cabeza, las métricas en
> ninguna parte. ARVIOR define **una sola fuente de verdad por tipo de información**
> (RevOps §0, principio 1) y conecta las herramientas con reglas claras de qué vive
> dónde.

### 4.1 Qué herramienta es dueña de qué (single source of truth)

| Información | Fuente de verdad | NO vive en | Por qué |
|---|---|---|---|
| **Pipeline comercial y cuentas** | **CRM** (el propio sistema de ARVIOR / Core) | Mails, WhatsApp, cabeza | Una oportunidad, una etapa, una próxima acción (Sales §3) |
| **Métricas y tablero** | **RevOps / dashboard** (sobre datos del CRM) | Planillas sueltas | Métrica definida una vez (Audit §2.6) |
| **Documentación viva (estrategia, SOPs, playbooks)** | **Notion** | Drive, mails | Texto colaborativo, versionado, buscable |
| **Código y documentos canónicos (estos .md)** | **GitHub** (`/docs`) | Notion | Versionado real, fuente canónica; Notion los espeja |
| **Activos del cliente (assets, contratos firmados, accesos doc.)** | **Drive** (carpeta por cliente) | Mail | Archivos binarios, compartibles, respaldados |
| **Secretos y accesos (claves, tokens)** | **Gestor de contraseñas** (bóveda) | Notion, Drive, texto plano | Seguridad + continuidad (Audit §3.5) |

### 4.2 Cómo se conectan (el mapa)

```
                         ┌──────────────────────────┐
                         │   GitHub /docs (canónico) │  ← estrategia + SOPs versionados
                         └────────────┬─────────────┘
                                      │ espeja (lectura)
                                      ▼
   ┌──────────────┐         ┌──────────────────┐         ┌────────────────┐
   │   NOTION     │◄───────►│   CRM / Core     │────────►│  DASHBOARD      │
   │ SOPs vivos,  │  enlaza │ pipeline, cuentas │ alimenta│ (RevOps)        │
   │ playbooks,   │         │ leads, Operate   │ métricas│ MRR, churn, NRR │
   │ wiki interna │         └────────┬─────────┘         └────────────────┘
   └──────────────┘                  │
          │ enlaza por cliente        │ carpeta por cliente
          ▼                           ▼
   ┌──────────────┐         ┌──────────────────┐
   │   DRIVE      │         │  BÓVEDA ACCESOS  │
   │ assets,      │         │ (password mgr)   │
   │ contratos    │         │ claves, tokens   │
   └──────────────┘         └──────────────────┘
```

**Reglas de conexión (innegociables):**

1. **GitHub es canónico para los documentos de sistema** (estos .md). Notion los
   espeja para lectura cómoda, pero el original se edita por PR (como este).
2. **El CRM es el único lugar donde existe una cuenta.** Si una cuenta no está en el
   CRM, no existe para el negocio (no se factura, no se mide, no se reporta).
3. **Una carpeta de Drive por cliente**, con nombre estándar, enlazada desde su
   ficha en el CRM. Contratos firmados y assets viven ahí, nunca en el mail.
4. **Ningún secreto en texto plano jamás.** Accesos del cliente → bóveda, con
   registro de quién lo cargó y cuándo (Onboarding §3.2).
5. **El dashboard no tiene datos propios:** lee del CRM. Si un número del tablero no
   se puede trazar a un dato del CRM, el número está mal.

### 4.3 Decisiones de stack pendientes (de Audit §7)

El sistema de gestión se apoya en tres decisiones del fundador ya tomadas (Audit §7),
porque condicionan qué se construye:

- **D3 — Runtime de automatización: `n8n` autoalojado** como estándar. Es donde corren
  las secuencias, la respuesta < 5 min y los agentes de IA. **Se prioriza no depender
  de plataformas externas** cuando n8n lo resuelve. **Es parte de Core, no del sitio
  público** ([Masterplan §7.5](ARVIOR_MASTERPLAN.md)).
- **D4 — WhatsApp: API Oficial** (vía BSP) como estándar para clientes; soluciones no
  oficiales solo para pruebas internas/experimentales.
- **D6 — Cobro:** pasarela y facturación de **Chile primero (CLP)**, LatAm como
  expansión.

> Hasta que el CRM de 5 etapas y el runtime de automatización existan en Core, el
> "caso #0" (ARVIOR usa su propio sistema) no es real (Audit §3.6). **Construirlos es
> la prioridad técnica número uno** (ver §6.3).

---

## 5. Dashboard del fundador (resumen — detalle en su documento)

> El tablero completo, con cada métrica, su fórmula y su umbral, vive en
> [`ARVIOR_FOUNDER_DASHBOARD.md`](ARVIOR_FOUNDER_DASHBOARD.md). Aquí, la cadencia en
> una línea (coherente con RevOps §2):

| Ritmo | El fundador revisa | Tiempo | Pregunta que responde |
|---|---|---|---|
| **Diario** | Leads nuevos sin tocar · cuentas en rojo nuevas · cobros caídos | 5 min | ¿Hay algo que se quema hoy? |
| **Semanal** | Embudo de la semana + churn en curso + acciones atascadas | 30 min | ¿Dónde está el cuello esta semana? |
| **Mensual** | MRR neto · churn · NRR · health de toda la cartera | 60 min | ¿El negocio recurrente está sano? |
| **Trimestral** | ARR · LTV:CAC · payback · razones de churn | 90 min | ¿La empresa es sana estructuralmente? |

> La regla de lectura no cambia (RevOps §2.1): **rojos primero, recurrente después,
> embudo al final.**

---

## 6. Cuellos de botella (qué depende del fundador, qué se delega y automatiza primero)

> Este es el corazón operativo. Convertir ARVIOR en máquina = **sacar al fundador del
> camino crítico, en el orden correcto.** Tres listas, derivadas del Audit §1.1.

### 6.1 Lo que hoy depende 100% del fundador

| Atado al fundador | Frecuencia | ¿Delegable? | ¿Automatizable? |
|---|---|---|---|
| Cierre comercial (diagnóstico, propuesta, objeciones) | Por cada venta | No (todavía) | No |
| Reporte de ROI mensual en vivo | Por cada cuenta, cada mes | Parcial (cuentas verdes) | Parcial (generación del reporte) |
| Construcción/implementación del sistema | Por cada build | **Sí** | Parcial (Core reduce el 80%) |
| Rescate de cuentas en rojo | Por evento | No (premium/rojo) | No |
| Calificación y seguimiento de leads | Continuo | **Sí** | **Sí** (scoring + recordatorios) |
| Facturación y cobranza | Mensual | **Sí** | **Sí** |
| Respuesta < 5 min a leads del cliente | 24/7 por cuenta | No (es del sistema) | **Sí (obligatorio)** |

### 6.2 Qué se delega primero (orden de personas — §1.3)

1. **Implementación** → primer rol a incorporar. Es la mayor carga de horas y la más
   delegable con SOP (SOP-08). Libera al fundador para vender.
2. **Operación de cuentas verdes y su reporte** → segundo rol. Quita la trampa de
   tiempo que crece con cada cuenta.
3. **Calificación/seguimiento de leads (SDR)** → tercero. Cerrar sigue siendo del
   fundador; prospectar no.
4. **Administración/cobranza** → cuarto. Bajo criterio, alto consumo de tiempo.

### 6.3 Qué se automatiza primero (orden de sistemas)

> La automatización va **antes** que la contratación donde es posible: una
> automatización no tiene sueldo ni se va.

1. **Respuesta < 5 min al lead** (la garantía central). Sin esto, la oferta no se
   cumple. **Automatización #1, no negociable** (Audit §1.3). Requiere el runtime de
   D3.
2. **Captura → CRM → scoring → próxima acción.** El pipeline de 5 etapas en Core. Es
   el "caso #0" que hace creíble la venta (Audit §3.6).
3. **Generación del reporte de ROI** (datos automáticos; la conversación sigue
   siendo humana). Ataca la trampa de tiempo del §6.1.
4. **Secuencias de seguimiento/nurture** sobre n8n (email primero, WhatsApp API después por D4).
5. **Facturación recurrente** (cobro automático **desde el día 1**, D1).
6. **Alertas de health score** (que el rojo avise solo, no que se descubra tarde).

> **La secuencia 6.2 + 6.3 es el plan de descompresión del fundador.** Cada elemento
> resuelto le devuelve horas que reinvierte en vender y en sistematizar el siguiente.
> Esa es, literalmente, la transición de fundador-operador a empresa.

### 6.4 Riesgo de continuidad (bus factor) — el cuello invisible

Hoy, **toda la operación, los accesos y el conocimiento viven en una persona**
(Audit §3.5). Antes que cualquier escala, se mitiga con tres cosas baratas:

- **Bóveda de accesos** con todos los secretos (propios y de clientes) — SOP-14.
- **SOPs escritos** de los P0 — para que otra persona pueda ejecutar.
- **Documentación de cuentas en el CRM** — para que ninguna cuenta viva sólo en la
  memoria.

---

## 7. Roadmap operativo (90 días / 1 año / 3 años)

> Roadmap de **capacidad operativa**, no de features. Cada hito responde: ¿qué hace
> a ARVIOR más máquina y menos dependiente del fundador? Coherente con los hitos de
> negocio (Business Model §7, RevOps §6).

### 7.1 Primeros 90 días — *de documentos a máquina mínima operable*

**Objetivo:** que el flujo §2 corra de punta a punta para 1–3 cuentas reales, con
herramientas conectadas y los SOPs P0 escritos. Es la fase de **instalar el sistema
operativo.**

| Quincena | Foco | Entregable operativo |
|---|---|---|
| **Sem 1–2** | Decisiones binarias (Audit §7) | D1–D6 decididas y escritas. Sin esto, todo lo demás es arena |
| **Sem 3–4** | Sistema de gestión (§4) | CRM de 5 etapas operativo, carpetas Drive, bóveda de accesos, Notion espejando docs |
| **Sem 5–6** | Caso #0 + runtime | Pipeline de ARVIOR sobre su propio sistema; respuesta < 5 min funcionando (automatización #1) |
| **Sem 7–9** | SOPs P0 | SOP-01,03,04,05,06,08,09,11,14 escritos y usados con un cliente real |
| **Sem 10–13** | Primer ciclo completo | 1–3 cuentas recorriendo lead→go-live→primer reporte de ROI, medidas en el dashboard |

**Criterio de salida de los 90 días:** una venta nueva puede recorrer todo el flujo
**sin que el fundador improvise**, porque cada paso tiene SOP y herramienta. El
churn, el MRR y el embudo se leen en un tablero real, no en la intuición.

### 7.2 Primer año — *validar el recurrente y empezar a delegar*

Coherente con Business Model §7 (Año 1) y RevOps §6 (Validación): 10–25 cuentas, ARR
USD 60–180K, churn < 3%.

| Trimestre | Foco operativo | Hito |
|---|---|---|
| **T1** | Máquina mínima (los 90 días de §7.1) | Flujo operable + 1ª cuentas |
| **T2** | Repetibilidad | 5–8 cuentas; todos los SOP escritos; primer **Implementador** incorporado (gatillo §1.3) |
| **T3** | Descompresión del fundador | Reporte de ROI semi-automatizado; **Operador de cuentas** evaluado al acercarse a 12–15 cuentas |
| **T4** | Cartera sana | 10–25 cuentas, churn < 3% demostrado, NRR > 110%, caso #0 maduro = munición de venta |

**Pregunta que el año debe responder** (RevOps §6): *¿el cliente paga mensual y se
queda?* Si sí, ARVIOR dejó de ser un freelance con buenos documentos y es una
empresa con MRR.

### 7.3 Tres años — *que la empresa funcione sin el fundador en cada cuenta*

Coherente con Business Model §7 (Año 3, Tracción): 60–150 cuentas, ARR USD 0.5–2M,
equipo 6–15, NRR > 110%.

| Eje | De (hoy) | A (año 3) |
|---|---|---|
| **Estructura** | Fundador = 5 funciones | Organigrama §1.4 con leads por función |
| **Entrega** | Bespoke por cliente | 80% sobre ARVIOR Core; bespoke sólo el 20% que diferencia |
| **Operate** | Reporte en vivo manual | Reporte estandarizado + revisión en vivo sólo premium/riesgo |
| **Rol del fundador** | En el camino crítico de todo | Fuera del operar; en estrategia, cuentas clave y producto |
| **Conocimiento** | En la cabeza del fundador | En SOPs, Core y datos (foso del Business Model §6) |

**Pregunta que los 3 años deben responder** (Business Model §7): *¿ARVIOR crece sin
que el fundador esté en cada cuenta?* Cuando la respuesta es sí, el sistema operativo
cumplió su función y ARVIOR es una empresa, no una persona muy ocupada.

---

## 8. Resumen ejecutable

1. **Cinco funciones, un dueño cada una** — hoy el fundador, nombradas para delegar
   sin reinventar (§1).
2. **Un flujo de siete estaciones**, cada una con dueño, entrada, salida, herramienta
   y SOP; ninguna se cierra sin disparar la siguiente (§2).
3. **Una fuente de verdad por tipo de dato:** CRM para cuentas, GitHub para docs,
   Notion para SOPs, Drive para assets, bóveda para secretos (§4).
4. **Delega en orden** (implementación → operación → SDR → admin) y **automatiza en
   orden** (respuesta <5min → CRM/caso#0 → reporte → nurture → cobro → alertas) (§6).
5. **Mitiga el bus factor ya:** bóveda de accesos + SOPs P0 + cuentas en el CRM (§6.4).
6. **90 días para instalar el sistema; 1 año para validar y delegar; 3 años para
   salir del camino crítico** (§7).

> El sistema operativo de ARVIOR no es burocracia: es la diferencia entre una persona
> que vende servicios y una empresa que opera un sistema. Lo segundo es lo que el
> Masterplan prometió ser.

---

## 9. Documentos relacionados

- [`ARVIOR_EXECUTION_AUDIT.md`](ARVIOR_EXECUTION_AUDIT.md) — las brechas que este sistema resuelve.
- [`ARVIOR_SOPS_MAP.md`](ARVIOR_SOPS_MAP.md) — el detalle de cada SOP del §3.
- [`ARVIOR_FOUNDER_DASHBOARD.md`](ARVIOR_FOUNDER_DASHBOARD.md) — el tablero del §5.
- [`ARVIOR_REVENUE_OPERATIONS.md`](ARVIOR_REVENUE_OPERATIONS.md) — métricas y cadencia de gobierno.
- [`ARVIOR_SALES_SYSTEM.md`](ARVIOR_SALES_SYSTEM.md) — el embudo que alimenta el flujo.
- [`ARVIOR_MASTERPLAN.md`](ARVIOR_MASTERPLAN.md) — la identidad que la operación hace real.
</content>
