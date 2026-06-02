# ARVIOR — Revenue System (oferta inicial)

> El documento que define **qué vendemos primero** sin anclar a ARVIOR a un único
> nicho. ARVIOR sigue siendo una empresa tecnológica que construye sistemas
> ([`ARVIOR_MASTERPLAN.md`](ARVIOR_MASTERPLAN.md) §0). Este doc define el primer
> **Revenue System** sobre el cual concentrar ventas, casos de éxito y procesos.
> Última revisión: 2026-06-02 · Estado: foundation
>
> Modelo económico: [`ARVIOR_BUSINESS_MODEL.md`](ARVIOR_BUSINESS_MODEL.md) ·
> Identidad: [`ARVIOR_MASTERPLAN.md`](ARVIOR_MASTERPLAN.md)

---

## 0. La decisión, en una frase

> **ARVIOR no se ancla a un vertical. Se ancla a un *problema* que se repite
> idéntico en muchos verticales: captar, calificar, hacer seguimiento y convertir
> oportunidades. El primer Revenue System ataca ese problema, y por eso es
> replicable sin convertir a ARVIOR en una agencia de nicho.**

La diferencia es estratégica, no semántica:

| Agencia de nicho | ARVIOR (sistema replicable) |
|---|---|
| Se define por la industria del cliente ("agencia para clínicas") | Se define por el problema ("sistema de captación y conversión") |
| Aprende un mercado, queda atrapada en él | Aprende **un sistema**, lo replica en cada mercado con el mismo dolor |
| Cada nicho nuevo = empezar de cero | Cada nicho nuevo = el mismo motor, distinta piel |
| El conocimiento vive en personas | El conocimiento vive en **producto y playbook** ([Core](ARVIOR_BUSINESS_MODEL.md#42-fase-plataforma-interna--arvior-core)) |

Concentramos ventas y casos en este Revenue System **no para encerrarnos**, sino
para acumular foco, prueba social y proceso repetible más rápido. El sistema es el
producto; el nicho es solo dónde lo desplegamos primero.

---

## 1. El beachhead: por problema, no por industria

### 1.1 El problema que atacamos

Empresas de servicios cuyo crecimiento **depende de generar y dar seguimiento a
leads**, y que pierden dinero porque el sistema detrás del marketing no existe o es
manual: leads que se enfrían en WhatsApp, planillas, formularios sin respuesta,
seguimiento que depende de que alguien se acuerde.

### 1.2 El segmento inicial (mismo dolor, distintos rubros)

No es "un nicho". Es un **conjunto de rubros que comparten el mismo problema**:

| Rubro | Por qué califica (mismo dolor) |
|---|---|
| **Clínicas** (dental, estética, salud) | Invierten en ads, reciben consultas, las pierden por seguimiento lento; cada lead = ingreso alto |
| **Centros médicos** | Alto volumen de consultas, agenda como cuello de botella, sin sistema de conversión |
| **Abogados / despachos** | Caso = ticket alto, decisión lenta; ganan o pierden por velocidad y nurture del prospecto |
| **Empresas de servicios especializados** | Venden confianza y expertise; el lead mal seguido se va a la competencia |
| **Empresas B2B que captan prospectos** | Ciclo largo, muchos puntos de contacto; necesitan calificar y nutrir, no solo capturar |

**El criterio de admisión no es el rubro, es el patrón:**

1. Ya **gastan en captar** (ads, redes, referidos) → hay dinero en juego.
2. El lead **vale mucho** (ticket alto o LTV alto) → un punto de conversión paga el sistema.
3. **Pierden lo que captan** por falta de seguimiento sistemático → el ROI es obvio y medible.
4. **No tienen ni quieren equipo técnico interno** → necesitan que alguien lo opere.

Si un negocio cumple los cuatro, es candidato — sea del rubro que sea. Eso mantiene
a ARVIOR como empresa de sistemas y al Revenue System como producto horizontal.

### 1.3 A quién NO (disciplina de foco)

- Negocios sin gasto de captación ni dolor de seguimiento → no hay ROI recurrente.
- Quien quiere "solo una web" → cliente de plantilla, no de ARVIOR (ver §4).
- Ticket bajo + volumen masivo + decisión impulsiva → el seguimiento no mueve la aguja.

---

## 2. El sistema replicable (el producto)

Un solo motor, cuatro etapas. Esto es lo que se replica idéntico entre rubros; solo
cambia la configuración, no la arquitectura.

```
   CAPTAR              CALIFICAR            SEGUIR               CONVERTIR
   ───────             ─────────            ──────               ─────────
 Sitio/landing      Scoring + reglas    Respuesta < 5 min     Agenda / cierre
 que convierte      Enrutamiento        Nurture multicanal    Handoff a humano
 Form + WhatsApp    automático          (email/WhatsApp)      Panel de pipeline
 Anti-spam/dedupe   por tipo de lead    Recordatorios         Reportes de ROI
       │                  │                   │                     │
       └──────────────────┴───────────────────┴─────────────────────┘
                                  │
                    Panel / CRM donde el dueño ve TODO el embudo
```

Cada pieza de la etapa CAPTAR **ya existe en el repo** (form + WhatsApp + anti-spam +
dedupe + CMS + leads + mailing — ver [`ARVIOR_MASTERPLAN.md`](ARVIOR_MASTERPLAN.md)
§7.3). El Revenue System no es teórico: es ese esqueleto + las etapas de calificación,
seguimiento y conversión montadas encima. Ese es el 80% repetible que se vuelve
[Core](ARVIOR_BUSINESS_MODEL.md#42-fase-plataforma-interna--arvior-core).

---

## 3. Build — tres escenarios (Chile y LatAm)

El **Build** es la construcción del Revenue System. Su rol económico es financiar la
adquisición (CAC), **no ser el negocio** — nunca se vende sin Operate (§4, y
[`ARVIOR_BUSINESS_MODEL.md`](ARVIOR_BUSINESS_MODEL.md) §3.1, regla de oro).

> ⚠️ **Cifras de arranque, a validar con los primeros 5–10 cierres.** Referencia CLP
> a ~USD 1 = CLP 950 (2026). Ajustar por país. No son lista de precios pública: son el
> ancla interna de negociación.

### 3.1 Qué incluye cada escenario

| Capacidad | Entrada | Profesional | Premium |
|---|:---:|:---:|:---:|
| Sitio/landing premium que convierte | ✅ 1 landing | ✅ sitio multi-página | ✅ sitio + landings por campaña |
| Captura: form + WhatsApp, anti-spam, dedupe | ✅ | ✅ | ✅ |
| Panel/CRM con embudo visible | ✅ básico | ✅ con etapas | ✅ avanzado + métricas |
| Notificación inmediata de lead | ✅ email | ✅ email + WhatsApp | ✅ multicanal |
| Calificación y enrutamiento automático | — | ✅ por reglas | ✅ scoring + reglas |
| Secuencias de seguimiento automatizadas | — | ✅ email/WhatsApp | ✅ multicanal + nurture largo |
| Respuesta inmediata al lead | — | ✅ autorespuesta | ✅ **agente IA conversacional** |
| Agendamiento integrado | — | ✅ básico | ✅ calendario + recordatorios |
| Integraciones (ads, calendario, CRM externo) | — | 1 integración | ✅ a medida |
| Inteligencia del sistema (scoring predictivo, insights) | — | — | ✅ |

### 3.2 Rangos de precio (Build, one-time)

| Escenario | CLP (Chile) | USD (LatAm) | Para quién |
|---|---|---|---|
| **Entrada** | $790.000 – $1.300.000 | 850 – 1.400 | Negocio que empieza a captar en serio; valida el modelo con bajo riesgo |
| **Profesional** | $1.900.000 – $3.200.000 | 2.000 – 3.400 | El estándar. Negocio con gasto de ads y dolor real de seguimiento |
| **Premium** | $4.200.000 – $7.000.000 | 4.400 – 7.400 | Alto volumen / ticket alto; quiere IA y operación sofisticada |

> El Entrada existe para bajar la fricción de entrada, **no** para vender barato. Su
> precio de Build cubre el CAC; el negocio está en que ese cliente entra a Operate y
> sube de escalón. Un Build sin Operate se cotiza a precio "no estratégico" (premium)
> para desincentivarlo.

---

## 4. Operate — el núcleo del negocio

> **No vendemos sitios web. Vendemos que el sistema *funcione, mejore y convierta*
> mes a mes.** El Build entrega el motor; Operate es ARVIOR manteniéndolo encendido,
> afinándolo y haciéndolo rendir más. Ahí vive el negocio (MRR → ARR).

### 4.1 Qué se vende en el recurrente (no es "hosting + soporte")

El recurrente se vende como **cuatro promesas de valor continuo**, no como una lista
de mantenimiento:

| Pilar del recurrente | Qué hace ARVIOR cada mes | Por qué el cliente lo paga |
|---|---|---|
| **Operación** | Mantiene el sistema corriendo: infra, uptime, captura, seguimiento, agenda | Si para, **para su flujo de leads** — no es opcional |
| **Optimización** | Mejora continua de conversión: copy, landings, formularios, A/B | El sistema **rinde más con el tiempo**, no se estanca |
| **Seguimiento** | Afina y opera las secuencias y la respuesta a leads (humano + IA) | Menos leads perdidos = más ingresos recuperados |
| **Automatización + Inteligencia** | Nuevas automatizaciones, agentes de IA, scoring, insights del embudo | El cliente **ve y entiende** su negocio; decide con datos |

### 4.2 Planes de Operate (recurrente mensual)

Cada escenario de Build tiene su plan de Operate natural; el cliente puede subir de
plan sin rehacer el Build (eso es expansión, §4.4).

| Plan Operate | CLP/mes | USD/mes | Incluye |
|---|---|---|---|
| **Core** (post-Entrada) | $90.000 – $160.000 | 95 – 170 | Operación + uptime + captura + reporte mensual + optimización ligera |
| **Growth** (post-Profesional) | $260.000 – $480.000 | 280 – 500 | Core + optimización activa (A/B, landings) + operación de seguimiento + reporte de embudo |
| **Intelligence** (post-Premium) | $650.000 – $1.250.000 | 680 – 1.300 | Growth + operación del agente IA + automatizaciones nuevas + scoring/insights + revisión estratégica mensual |

> Estos rangos son coherentes con el modelo: USD 100–1.300 MRR, margen recurrente
> 75–85% gracias a la infra simple ([`ARVIOR_BUSINESS_MODEL.md`](ARVIOR_BUSINESS_MODEL.md)
> §3.2). **El número a vigilar no es el precio: es el churn** (objetivo < 3% mensual).

### 4.3 Diseño para maximizar permanencia (por qué no se van)

El recurrente está deliberadamente diseñado para que quedarse sea obvio e irse duela:

1. **Switching cost real.** Sus leads, su pipeline, su seguimiento y sus agentes
   viven en ARVIOR. Cancelar no es cambiar de proveedor: es **apagar su operación**.
2. **El valor sube con el tiempo, no baja.** La optimización mensual mejora la
   conversión; el sistema que opera ARVIOR el mes 12 rinde más que el del mes 1.
3. **Datos que solo existen dentro.** El histórico de leads, scoring y embudo es del
   cliente *dentro* de ARVIOR; perderlo es perder su memoria comercial.
4. **Reporte de ROI mensual.** Cada mes el cliente ve cuántos leads capturó, cuántos
   convirtió y cuánto valió — el recurrente se justifica solo, con números.
5. **Relación, no transacción.** La revisión estratégica (Intelligence) convierte a
   ARVIOR en asesor de crecimiento, no en proveedor de hosting.

### 4.4 Expansión: la recurrencia que crece (NRR > 100%)

El cliente no solo se queda: **gasta más dentro de ARVIOR** sin que cueste adquirirlo
de nuevo. Caminos de expansión:

- Subir de plan (Core → Growth → Intelligence) a medida que crece.
- Nuevas automatizaciones y agentes de IA a pedido.
- Nuevas landings/campañas (el Build vuelve, pero ahora sobre un cliente fiel).
- Nuevas sucursales / líneas de servicio sobre el mismo sistema.

Esto es lo que hace que la cartera **valga más cada mes** aunque no entren clientes
nuevos — lo contrario de una agencia.

---

## 5. Cómo esto alimenta a ARVIOR-empresa (no la encierra)

| Lo que el Revenue System acumula | En qué se convierte para ARVIOR |
|---|---|
| Casos de éxito con métricas | Prueba social vendible en cualquier rubro |
| Proceso repetible de venta y entrega | Onboarding estandarizado, menos dependencia del fundador |
| El 80% repetible del sistema | **ARVIOR Core**, plataforma propia ([Business Model §4.2](ARVIOR_BUSINESS_MODEL.md#42-fase-plataforma-interna--arvior-core)) |
| Datos de qué convierte por rubro | Playbooks y scoring propietarios (foso de datos) |
| MRR recurrente predecible | ARR, la métrica de empresa de software |

El Revenue System es **la primera aplicación** del sistema de ARVIOR, no su techo.
Cuando esté dominado, el mismo motor se despliega en el siguiente conjunto de rubros
con el mismo dolor — sin renombrar, sin pivotar, acumulando (Masterplan §8).

---

## 6. Qué medir

| Métrica | Objetivo de arranque |
|---|---|
| Cuentas en Operate (altas − bajas) | Crecer mes a mes |
| MRR / ARR | Métrica principal de negocio |
| Churn lógico | < 3% mensual |
| NRR (expansión − contracción − churn) | > 110% |
| % de cuentas que suben de plan a 6 meses | Señal de que el valor recurrente es real |
| ROI demostrado al cliente (leads → conversiones) | El número que sostiene la permanencia |

---

## 7. Documentos relacionados

- [`ARVIOR_BUSINESS_MODEL.md`](ARVIOR_BUSINESS_MODEL.md) — modelo económico, unit economics, fosos.
- [`ARVIOR_MASTERPLAN.md`](ARVIOR_MASTERPLAN.md) — identidad y horizontes de expansión.
- [`ARVIOR_HOMEPAGE_ARCHITECTURE.md`](ARVIOR_HOMEPAGE_ARCHITECTURE.md) — la home como motor de adquisición (Build/CAC).
