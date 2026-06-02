# ARVIOR — Sales System (sistema comercial operativo)

> El motor de ventas de ARVIOR: cómo convertimos un desconocido en una cuenta de
> Operate que se queda y crece. **No es teoría de ventas.** Es el proceso que se
> ejecuta, se mide y se repite, lead por lead.
> Última revisión: 2026-06-02 · Estado: operativo / vivo
>
> Qué vendemos: [`ARVIOR_OFFER.md`](ARVIOR_OFFER.md) · Por qué:
> [`ARVIOR_REVENUE_SYSTEM.md`](ARVIOR_REVENUE_SYSTEM.md) ·
> [`ARVIOR_BUSINESS_MODEL.md`](ARVIOR_BUSINESS_MODEL.md)
> Después del cierre: [`ARVIOR_ONBOARDING_SYSTEM.md`](ARVIOR_ONBOARDING_SYSTEM.md) ·
> [`ARVIOR_RETENTION_SYSTEM.md`](ARVIOR_RETENTION_SYSTEM.md) ·
> Cómo se gobierna: [`ARVIOR_REVENUE_OPERATIONS.md`](ARVIOR_REVENUE_OPERATIONS.md)

---

## 0. La regla que ordena todo el sistema

> **No vendemos. Diagnosticamos. La venta es la consecuencia de un diagnóstico
> honesto. El proyecto (Build) es el CAC; la cuenta de Operate es el negocio. Por eso
> el objetivo de cada interacción comercial no es "cerrar el Build" — es **abrir una
> relación recurrente que rinda y se quede.**

Tres consecuencias que cambian cómo se vende:

1. **Calificar es más importante que cerrar.** Un cliente que entra sin tráfico ni
   dolor de seguimiento se va en 3 meses y se lleva tu margen. Mejor un "no" temprano
   que un churn caro (ver §2.2, criterio de admisión).
2. **El diagnóstico viene antes que la propuesta.** Nunca se cotiza en la primera
   reunión sin entender el embudo actual del cliente (§4).
3. **Todo cierre es el inicio de un onboarding,** no el final de una venta. El
   handoff a entrega empieza el mismo día que se firma (§7 → Onboarding System).

---

## 1. El sistema comercial completo (de lead a renovación)

Nueve etapas, una sola línea. Cada etapa tiene un **objetivo único** y una **salida
clara** (avanza o se descarta). Nada queda "en el aire".

```
 LEAD → REUNIÓN → DIAGNÓSTICO → PROPUESTA → CIERRE → ONBOARDING → OPERATE → UPSELL → RENOVACIÓN
  │        │           │            │          │          │           │         │          │
 Captar  Agendar   Entender el   Mostrar el  Firmar +  Poner en   Operar y  Crecer la  Sostener
 y       una       embudo y el   puente y    cobrar    marcha y   demostrar  cuenta     la cuenta
 calif.  conversa- dolor real    el precio   Build +   activar    ROI cada   (NRR)      (anti-
 ligero  ción                                Operate   Operate    mes                   churn)
```

| # | Etapa | Objetivo único | Salida (gate para avanzar) | Dueño |
|---|---|---|---|---|
| 1 | **Lead** | Capturar y calificar ligero | Cumple ≥3 de 4 criterios de admisión (§2.2) | Marketing / inbound |
| 2 | **Reunión** | Conseguir 30–45 min con quien decide | Reunión agendada con decisor presente | Comercial |
| 3 | **Diagnóstico** | Entender el embudo y cuantificar el hueco | Dolor nombrado + número de pérdida estimado | Comercial |
| 4 | **Propuesta** | Mostrar el puente y el precio (3 opciones) | Propuesta enviada y presentada en vivo | Comercial |
| 5 | **Cierre** | Firmar Build + Operate y cobrar | Contrato firmado + primer pago / OC | Comercial |
| 6 | **Onboarding** | Poner en marcha y activar Operate | Sistema capturando + cliente activado | Entrega |
| 7 | **Operate** | Operar y demostrar ROI mensual | Reporte mensual entregado, cliente paga | Operaciones |
| 8 | **Upsell** | Subir de plan / añadir módulos | Expansión cotizada y aceptada | Comercial + Ops |
| 9 | **Renovación** | Sostener la cuenta más allá del período | Renovación confirmada, churn evitado | Ops + Comercial |

> Las etapas 1–5 son **adquisición** (este documento). Las 6–9 son **el negocio** y
> viven en Onboarding + Retention, pero arrancan aquí: la calidad del cierre define la
> calidad de la cuenta.

---

## 2. Etapa 1 — Lead: capturar y calificar ligero

### 2.1 De dónde vienen los leads (canales de arranque)

| Canal | Tipo | Prioridad arranque | Nota |
|---|---|:---:|---|
| Referidos / red directa del fundador | Outbound cálido | **Alta** | El canal #1 al inicio. Pide referidos en cada cierre y cada reporte de ROI bueno. |
| Inbound desde la propia web de ARVIOR | Inbound | Alta | La home es el motor de adquisición ([Homepage Architecture](ARVIOR_HOMEPAGE_ARCHITECTURE.md)). Coherencia: ARVIOR usa su propio Revenue System. |
| Outbound dirigido (rubros del beachhead) | Outbound frío | Media | Solo a negocios que cumplen el patrón de admisión (§2.2). Mensaje por dolor, no por feature. |
| Casos de éxito / prueba social | Inbound | Crece con el tiempo | Cada cuenta que rinde es munición de venta para su rubro. |

> **Principio:** ARVIOR debe ser su propio caso #0. Si la web no captura, califica y
> hace seguimiento de los leads de ARVIOR, no podemos venderlo. El pipeline comercial
> de ARVIOR corre sobre el mismo sistema que vendemos.

### 2.2 Criterio de admisión (el filtro que protege el margen)

Un lead califica si cumple **al menos 3 de 4** (los mismos del [Revenue System §1.2](ARVIOR_REVENUE_SYSTEM.md)):

1. **Ya gasta en captar** (ads, redes, referidos) → hay dinero en juego.
2. **El lead vale mucho** (ticket o LTV alto) → un cierre paga el sistema.
3. **Pierde lo que capta** por seguimiento manual o inexistente → ROI obvio.
4. **No tiene ni quiere equipo técnico interno** → necesita que alguien lo opere.

**Descartar rápido y sin culpa** (ver [Offer §5 y Revenue System §1.3](ARVIOR_OFFER.md)):
- Sin gasto de captación ni dolor de seguimiento → no hay ROI recurrente.
- "Solo quiero una web" y nada más → cliente de plantilla, no de ARVIOR.
- Ticket bajo + volumen masivo + decisión impulsiva → el seguimiento no mueve la aguja.

> Un lead descartado limpio y rápido **es una victoria del sistema**, no una venta
> perdida. El costo de un mal cliente es el churn, no el "no".

### 2.3 Lead scoring (semáforo simple, no fórmula)

| Score | Definición | Acción |
|---|---|---|
| 🟢 **Caliente** | Cumple 4/4 + tiene urgencia o evento gatillante (campaña que arranca, sucursal nueva) | Reunión esta semana. Prioridad máxima. |
| 🟡 **Tibio** | Cumple 3/4, sin urgencia clara | Reunión agendada + nurture si pospone. |
| 🔴 **Frío** | Cumple ≤2/4 | No agendar venta. Nurture educativo o descarte. |

---

## 3. Pipeline comercial (etapas, estados, reglas, métricas, probabilidad)

Este es el pipeline que vive en el CRM. **Una oportunidad está siempre en una y solo
una etapa**, con un estado y una próxima acción con fecha. Si no tiene próxima acción
con fecha, está mal gestionada.

### 3.1 Etapas del pipeline y probabilidad de cierre

| # | Etapa pipeline | Definición de entrada | Prob. de cierre | Criterio para avanzar |
|---|---|---|:---:|---|
| 0 | **Lead calificado** | Cumple admisión (§2.2), aún sin reunión | 10% | Reunión agendada |
| 1 | **Reunión agendada** | Hay fecha con el decisor | 20% | Reunión realizada con diagnóstico hecho |
| 2 | **Diagnóstico hecho** | Conocemos su embudo y cuantificamos el hueco | 35% | Cliente acepta recibir propuesta |
| 3 | **Propuesta enviada** | Propuesta presentada en vivo | 55% | Cliente pide ajuste, plazo o negocia (señal de compra) |
| 4 | **En negociación / cierre** | Discutiendo alcance/precio/plazos | 75% | Acuerdo verbal + contrato enviado |
| 5 | **Cerrado–Ganado** | Contrato firmado + primer pago | 100% | → Onboarding |
| — | **Cerrado–Perdido** | No avanza | 0% | Con **razón de pérdida** obligatoria |
| — | **Nurture / dormido** | Cualifica pero no ahora | — | Reactivar con gatillo o contenido |

> Las probabilidades sirven para **proyectar MRR futuro** (pipeline ponderado, ver
> [Revenue Operations §3](ARVIOR_REVENUE_OPERATIONS.md)), no para adivinar. Se calibran
> con los primeros 10–15 cierres reales.

### 3.2 Reglas del pipeline (higiene innegociable)

1. **Toda oportunidad tiene próxima acción con fecha.** Sin ella, se marca "dormida".
2. **Una etapa, un objetivo.** No se salta de Reunión a Propuesta sin Diagnóstico.
3. **Se cotiza siempre el sistema completo: Build + Operate.** Nunca Build solo a
   precio estándar (regla de oro, [Offer §12](ARVIOR_OFFER.md)).
4. **Toda pérdida lleva razón** (precio, timing, no calificaba, competencia, sin
   respuesta). Sin razón, no se cierra como perdida. La razón alimenta el aprendizaje.
5. **SLA de seguimiento:** lead caliente contactado < 24 h; propuesta seguida ≤ 48 h
   tras enviarla; oportunidad sin movimiento 14 días → revisión o nurture.
6. **El decisor está en la reunión o no hay propuesta.** Cotizar a quien no decide es
   quemar pipeline.

### 3.3 Estados transversales (etiquetas, no etapas)

`Nuevo` · `En proceso` · `Esperando al cliente` · `En riesgo` (sin respuesta >14d) ·
`Dormido` · `Reactivado`. Sirven para filtrar el pipeline sin mover de etapa.

### 3.4 Métricas del pipeline

| Métrica | Qué dice | Objetivo de arranque |
|---|---|---|
| Conversión por etapa | Dónde se cae el embudo comercial | Identificar el cuello, no un número fijo |
| Conversión global Lead→Ganado | Salud del motor | Calibrar; mejorar mes a mes |
| Ciclo de venta (días Lead→Ganado) | Velocidad del dinero | < 30–45 días (servicios PYME) |
| Valor del pipeline ponderado | MRR + Build proyectado | Cubrir meta del trimestre ×3 |
| Tasa de descarte en admisión | Disciplina de foco | Sano que exista; si es 0%, no estás filtrando |

---

## 4. Etapa de Reunión + Diagnóstico (diagnosticar antes que vender)

> Esto **expande** el guion de [Offer §18](ARVIOR_OFFER.md). El principio: **el cliente
> que describe su propio dolor en voz alta ya se está vendiendo solo.** Hablamos 30%,
> escuchamos 70%.

### 4.1 Estructura de la reunión comercial (30–45 min)

| Bloque | Tiempo | Objetivo | No hacer |
|---|:---:|---|---|
| **1. Apertura / encuadre** | 3 min | Acordar agenda y permiso para preguntar | Lanzar el pitch |
| **2. Descubrimiento** | 12–15 min | Mapear su embudo actual con preguntas | Hablar de ARVIOR |
| **3. Diagnóstico** | 5 min | Nombrar el hueco y **cuantificar la pérdida** | Suavizar el problema |
| **4. El puente** | 5 min | Mostrar el resultado (antes/después), no features | Listar funciones |
| **5. Encaje y siguiente paso** | 5 min | Confirmar fit y acordar propuesta | Improvisar precio sin diagnóstico |
| **6. Cierre de reunión** | 2 min | Próximo paso con fecha | Dejar el "te aviso" abierto |

> **No se cotiza en la primera reunión sin diagnóstico.** Si surge "¿cuánto cuesta?",
> se ancla rango y se devuelve al diagnóstico: *"Depende de tu situación — por eso
> quiero entender bien tu embudo antes de tirarte un número que no signifique nada.
> Va entre X e Y; déjame ver dónde caes."*

### 4.2 Preguntas de descubrimiento (el corazón del diagnóstico)

Ordenadas para que el cliente vea el hueco solo. Adaptar el rubro.

**Cómo entran hoy los clientes:**
- "Cuéntame cómo te llega hoy un cliente nuevo, desde que te conoce hasta que paga."
- "¿Por dónde te escriben? (WhatsApp, formulario, llamada, DM)"
- "¿Cuánto gastas al mes en atraer gente — ads, redes, lo que sea?"

**Dónde se cae (el hueco):**
- "Cuando alguien te escribe un sábado a las 9 pm, ¿qué pasa?"
- "¿Cuánto demoras normalmente en responder una consulta nueva?"
- "¿Quién hace el seguimiento si no contestan a la primera? ¿Con qué sistema?"
- "¿Cuántas consultas tuviste el mes pasado? ¿Cuántas se convirtieron en cliente?"
- *(Si no sabe el número — y casi nunca lo sabe — ese silencio es el diagnóstico.)*

**Cuantificar el dolor (clave para justificar precio):**
- "¿Cuánto vale para ti un cliente nuevo? ¿Y a lo largo del tiempo?"
- "Si de cada 10 que escriben se te enfrían 4 por seguimiento lento, ¿cuánto es eso al mes?"
- "¿Qué te costaría contratar a alguien solo para responder y perseguir leads todo el día?"

**Estado actual y decisión:**
- "¿Qué tienes hoy montado? (web, CRM, planillas, nada)"
- "Si decidiéramos avanzar, ¿la decisión es tuya o hay alguien más?"
- "¿Qué tendría que pasar para que esto sea prioridad este mes?"

### 4.3 El diagnóstico (cómo se nombra el hueco)

Tras descubrir, se devuelve el problema **en su lenguaje y con su número**:

> *"Por lo que me cuentas: traes alrededor de [N] consultas al mes pagando [$X] en
> ads, pero entre que respondes en horas y que nadie hace seguimiento sistemático, se
> te enfrían cerca de [M]. A [$valor por cliente], eso es del orden de [$pérdida] que
> se cae por un hueco cada mes. No tienes un problema de marketing — tienes un problema
> de que lo que tu marketing trae, se cae. Eso es exactamente lo que arreglamos."*

Ese número de pérdida es la **ancla de valor**: hace que cualquier precio de Build +
Operate se lea como inversión con retorno, no como gasto.

### 4.4 Salida de la reunión

- ✅ **Fit + dolor cuantificado** → "Te preparo una propuesta con 3 opciones y te la
  presento [fecha cercana]. ¿Te va [día]?" → etapa Propuesta.
- ⚠️ **Fit pero sin urgencia** → nurture + gatillo. No forzar.
- ❌ **No califica** → cerrar honesto: *"Siendo recto, hoy no es para ti porque [razón].
  Cuando [condición], conversamos."* Protege la marca y libera pipeline.

---

## 5. Sistema de propuestas

> La propuesta **no vende** — confirma una decisión ya construida en el diagnóstico.
> Es corta, visual y orientada a resultado. Precios y escenarios salen de
> [Offer §6–§12](ARVIOR_OFFER.md); aquí está el **cómo** se arma y presenta.

### 5.1 Qué DEBE contener (estructura de 1 página + anexo)

| Sección | Contenido | Por qué |
|---|---|---|
| **1. Tu situación hoy** | El embudo y la **pérdida cuantificada** del diagnóstico, en sus palabras | Demuestra que escuchamos; ancla el valor |
| **2. El resultado** | Tabla antes/después ([Offer §3](ARVIOR_OFFER.md)) | Vende resultado, no tecnología |
| **3. El sistema** | Captura → califica → seguimiento → convierte (4 pasos, 1 imagen) | Simple, memorable |
| **4. Las 3 opciones** | Entrada / Profesional / Premium, con Build + Operate juntos | Ancla en Profesional (§5.3) |
| **5. Qué incluye / qué no** | Alcance y exclusiones claras ([Offer §5](ARVIOR_OFFER.md)) | Protege margen, evita scope creep |
| **6. Garantías** | Puesta en marcha, <5 min, datos del cliente ([Offer §13](ARVIOR_OFFER.md)) | Baja el riesgo percibido a casi cero |
| **7. Siguiente paso** | Cómo empezar + plazo de entrega + validez de la propuesta | Crea acción y leve urgencia |

### 5.2 Qué mostrar y qué NO mostrar

**Mostrar:**
- El número de su pérdida actual (lo más importante de toda la propuesta).
- El resultado y la experiencia ("abrir un panel el lunes y ver 40 consultas, 12 agendadas").
- Build y Operate **siempre juntos**, con el total claro: una vez + mensual.
- Garantías y plazo de entrega.
- Validez de la propuesta (ej. 14 días) → urgencia honesta.

**No mostrar:**
- ❌ Desglose interno de costos, márgenes o herramientas que usamos (compran resultado, no insumos).
- ❌ Lista larga de features técnicas (genera comparación por checklist con la competencia barata).
- ❌ Stack tecnológico detallado salvo que lo pidan (y aun así, a alto nivel).
- ❌ Descuentos sin contrapartida (erosiona valor y margen; ver §5.4).
- ❌ Más de 3 opciones (parálisis de decisión).

### 5.3 Cómo presentar (siempre en vivo, nunca solo por mail)

1. **Se presenta en reunión**, compartiendo pantalla. Enviar PDF "para que lo revise"
   sin presentar = propuesta muerta.
2. **Orden:** situación → pérdida → resultado → sistema → opciones → garantías → cierre.
   El precio va al final, después del valor.
3. **Anclar en Profesional explícitamente:** *"Para un negocio como el tuyo, lo
   correcto es Profesional."* Entrada se ve como compromiso mínimo; Premium como techo.
4. **Cerrar con pregunta de avance, no de permiso:** *"¿Arrancamos con Profesional?
   Puedo tener tu sistema capturando en 4 semanas."*
5. Si duda → objeciones (§6). Si pide plazo → *"¿Qué te falta para decidir: el alcance
   o la inversión?"* (separa la objeción real).

### 5.4 Cómo justificar el precio (y cómo separar Build y Operate)

**Justificar el precio = volver siempre a la pérdida cuantificada del diagnóstico:**

> *"El sistema completo es [Build] una vez más [Operate] al mes. Hoy estás perdiendo
> del orden de [$pérdida] cada mes por el hueco. Con recuperar uno o dos clientes al
> mes, el sistema ya se paga solo — el resto es ganancia. Lo caro no es esto; lo caro
> es seguir perdiendo lo que ya pagas por atraer."*

**Separar Build y Operate (el encuadre que sostiene el negocio):**

| | Build (una vez) | Operate (mensual) |
|---|---|---|
| **Qué es** | Construir el motor | Que el motor funcione, mejore y convierta mes a mes |
| **Rol** | Tu inversión de arranque (cubre el CAC de ARVIOR) | **El negocio**: donde vive el valor continuo |
| **Cómo se explica** | "Lo que se construye una vez" | "Lo que hace que rinda más con el tiempo en vez de quedarse viejo" |
| **Regla de oro** | Nunca se vende solo a precio estándar | Se cobra desde el día 1, no "cuando esté listo" |

> Si el cliente quiere **solo el Build**, se cotiza a precio disuasivo ("no estratégico")
> y sin garantías ([Offer §12, regla 1](ARVIOR_OFFER.md)). ARVIOR no vende folletos.

---

## 6. Sistema de objeciones

> Una objeción es **una pregunta de seguridad, no un rechazo.** Técnica base:
> **Reconocer → Reencuadrar → Devolver pregunta.** Nunca discutir; siempre reencuadrar
> hacia el costo de no actuar. Amplía [Offer §17](ARVIOR_OFFER.md).

| Objeción | Qué hay debajo | Respuesta (reconocer → reencuadrar → devolver) |
|---|---|---|
| **"Es caro"** | No ve el retorno aún | "Te entiendo. ¿Cuántos clientes nuevos al mes necesitas para que se pague solo? Normalmente uno o dos — el resto es ganancia. Lo caro es seguir perdiendo los [N] que ya atraes cada mes. ¿Contra qué lo estás comparando?" |
| **"Lo voy a pensar"** | Falta claridad o no es el decisor | "Me parece. Para ayudarte a pensarlo bien: ¿qué te genera dudas, el alcance o la inversión? Mientras lo piensas, cada semana se enfrían leads que ya pagaste por atraer." |
| **"Ya tengo página web"** | Confunde web con sistema | "Perfecto, esa es la cara. Lo que te falta no es una web — es el sistema que responde y persigue a cada interesado en 5 minutos. ¿Quién hace ese seguimiento hoy en tu web?" |
| **"Ya tengo agencia / alguien que me la hace"** | Lealtad o miedo a duplicar | "Buenísimo para lo que ellos hacen (tráfico, diseño). Nosotros no competimos con eso: operamos el sistema que convierte lo que ellos traen. De hecho los potenciamos. ¿Hoy quién opera tu seguimiento?" |
| **"No tengo presupuesto"** | Prioridad, no plata | "Lo respeto. La pregunta real no es si hay presupuesto, es si lo que pierdes por el hueco es mayor a la inversión — y por lo que vimos, lo es. ¿Empezamos por Entrada para validar con bajo riesgo y subimos cuando veas el retorno?" |
| **"Envíame información"** | Cortés "no" o falta de urgencia | "Te la mando, pero un PDF no te va a mostrar tu hueco — eso lo vimos hoy con tus números. ¿Te parece si en vez de info genérica te preparo la propuesta con las 3 opciones para tu caso y la vemos 20 min?" |
| **"No necesito IA"** | Cree que vendemos tecnología | "Y tienes razón en no necesitar 'IA por moda'. No vendemos IA — vendemos que dejes de perder clientes. La IA es solo una de las formas de responder rápido; en tu caso quizá ni la usamos. Lo que necesitas es que nada se caiga, ¿de acuerdo?" |
| **"Prefiero pagar una sola vez"** | No ve el valor del recurrente | "Lo entiendo, suena más cómodo. Pero un sistema que se paga una vez es un folleto que se desactualiza: el valor está en que funcione, mejore y convierta **cada mes**. Por eso no vendemos el Build solo — sería venderte algo que sabemos que se va a quedar viejo. ¿Qué te preocupa del mensual: el monto o el compromiso?" |

**Reglas al manejar objeciones:**
1. **Nunca bajar el precio para resolver una objeción** sin quitar alcance a cambio.
2. **Aislar la objeción real:** "Si resolviéramos eso, ¿avanzamos?" (descubre si hay otra detrás).
3. **La objeción de "solo una vez" es estructural**, no de precio: se responde con el
   modelo (el valor está en Operate), no con descuento.
4. Si tras reencuadrar sigue el "no" → cerrar limpio y a nurture. No rogar.

---

## 7. Cierre y handoff (puente a entrega)

### 7.1 Señales de cierre y cómo pedir la firma

- **Señales de compra:** pregunta por plazos, por el "cómo empezamos", negocia
  alcance, mete a otra persona, dice "cuando lo tengamos…". → **Pedir el cierre ya.**
- **Cierre directo:** *"Entonces arrancamos con Profesional. Te mando el contrato hoy,
  con el primer pago confirmamos fecha de inicio y en 4 semanas estás capturando."*

### 7.2 Qué se firma

- Contrato/acuerdo: Build (alcance + precio + plazo) **y** Operate (plan + mensualidad
  + permanencia sugerida 12 meses + garantías de [Offer §13](ARVIOR_OFFER.md)).
- Condiciones de pago: Build (anticipo % + saldo contra hito) y Operate (mensual desde
  puesta en marcha, [Offer §12, regla 3](ARVIOR_OFFER.md)).

### 7.3 Handoff inmediato (el cierre no es el final)

El mismo día del cierre se dispara el **Onboarding** (no esperar a "tener tiempo"):
- Se crea la cuenta y se agenda la reunión de kickoff (≤ 5 días hábiles).
- Comercial entrega a Entrega el contexto del diagnóstico (dolor, números, expectativas,
  alcance, exclusiones acordadas) → ver [`ARVIOR_ONBOARDING_SYSTEM.md`](ARVIOR_ONBOARDING_SYSTEM.md) §1.
- Se pide el **primer referido** mientras el entusiasmo está alto.

---

## 8. Sistema de expansión (Upsell — NRR > 110%)

> La expansión es **venta a un cliente que ya confía y ya rinde** — el CAC ya está
> pagado. Es la palanca de mayor margen del negocio. Pero **no se vende: se gana** con
> resultados demostrados. Coordina con [`ARVIOR_RETENTION_SYSTEM.md`](ARVIOR_RETENTION_SYSTEM.md) §5.

### 8.1 Qué se ofrece (caminos de expansión)

| Camino | Qué es | Tipo |
|---|---|---|
| **Subir de plan** (Core→Growth→Intelligence) | Más optimización, seguimiento, inteligencia | + MRR |
| **Growth / retainer de crecimiento** | Optimización activa de conversión, más campañas | + MRR |
| **Intelligence / scoring + insights** | El cliente decide con datos del embudo | + MRR |
| **Nuevos módulos** | Agenda avanzada, portal, encuestas, reseñas | + MRR / proyecto |
| **Nuevas integraciones** | CRM externo, ERP, pasarela, ads | proyecto + MRR |
| **Automatizaciones avanzadas** | Flujos nuevos según su operación | + MRR |
| **IA conversacional** | Agente que responde/califica/agenda 24/7 | + MRR (salto a Intelligence) |
| **Nueva landing / campaña / sucursal** | El Build vuelve, ahora sobre cliente fiel | Build + más MRR |

### 8.2 Cuándo ofrecer (gatillos, no calendario)

La expansión se dispara por **señal**, no por agenda de ventas:

| Gatillo observado en la cuenta | Expansión natural a proponer |
|---|---|
| El reporte muestra **más volumen** del que el plan maneja cómodo | Subir de plan |
| El cliente responde leads a mano y se le acumulan | IA conversacional / nurture (Growth→Intelligence) |
| Abre **sucursal o nueva línea** de servicio | Nuevo Build/landing + más MRR |
| Pide ver "por qué unos leads cierran y otros no" | Scoring + insights (Intelligence) |
| Lanza **campaña nueva** de ads | Landing por campaña + optimización (Growth) |
| Lleva ≥3 meses con ROI positivo demostrado | Revisión estratégica → proponer el siguiente escalón |
| Usa otra herramienta a mano (CRM, ERP) | Integración |

> **Regla:** nunca proponer expansión en un mes de ROI flojo o con un ticket de soporte
> abierto. Primero resolver y demostrar valor; la expansión se ofrece desde la fuerza.

### 8.3 Cómo ofrecer (la mecánica)

1. **Anclada en su propio dato:** *"Este mes capturaste 30% más que en abril y tu plan
   actual lo maneja, pero estás respondiendo a mano. Con [IA/Growth] eso lo opera el
   sistema y dejas de perder los de la noche."*
2. **En la revisión mensual de resultados**, no en un mail suelto (ver
   [Retention §3](ARVIOR_RETENTION_SYSTEM.md)). El reporte de ROI es el contexto natural.
3. **Como evolución, no como venta nueva:** "subes de plan", no "te vendo otra cosa".
4. **Cuantificar el retorno del salto**, igual que en la venta inicial.

---

## 9. Dashboard comercial (qué mira el fundador cada semana)

> Detalle completo de definiciones, fórmulas y cadencia de gobierno en
> [`ARVIOR_REVENUE_OPERATIONS.md`](ARVIOR_REVENUE_OPERATIONS.md). Aquí, la vista mínima
> que el fundador revisa **cada semana** sin excusa.

### 9.1 Las 12 métricas del tablero semanal

| Bloque | Métrica | Pregunta que responde |
|---|---|---|
| **Top of funnel** | Leads calificados nuevos | ¿Entra suficiente al embudo? |
| | Reuniones realizadas | ¿Estamos consiguiendo conversaciones? |
| | Propuestas presentadas | ¿Convertimos reuniones en oportunidades reales? |
| **Cierre** | Cierres (cuentas ganadas) | ¿Convertimos oportunidades en clientes? |
| | Conversión por etapa | ¿Dónde se cae el embudo? |
| | Ciclo de venta (días) | ¿Cuánto tarda el dinero en entrar? |
| **Negocio (el que importa)** | **MRR** (y nuevo MRR del mes) | ¿Crece el negocio recurrente? |
| | **Churn** (lógico, mensual) | ¿Se nos van? (objetivo < 3%) |
| | **NRR** | ¿La cartera vale más cada mes? (objetivo > 110%) |
| | **LTV** | ¿Cuánto vale una cuenta en su vida? |
| | **CAC** | ¿Cuánto cuesta adquirir una cuenta? (idealmente cubierto por Build) |
| | **LTV : CAC** | ¿El negocio es sano? (> 4:1) |

### 9.2 Cómo leer el tablero (jerarquía de atención)

1. **Primero el churn.** Una baja en Operate duele más que una venta nueva — el negocio
   es el recurrente. Si sube el churn, todo lo demás espera.
2. **Después MRR y NRR.** ¿Crece la base y crece dentro de cada cuenta?
3. **Luego el embudo de adquisición** (leads→reuniones→propuestas→cierres) para
   encontrar el cuello de esta semana.
4. **El ciclo de venta** como termómetro de eficiencia comercial.

> El número que reemplaza "leads/semana" como métrica de empresa es **ARR**
> ([Business Model §6](ARVIOR_BUSINESS_MODEL.md)). El tablero semanal es el panel de
> instrumentos; el destino es ARR creciente con churn bajo.

---

## 10. Resumen ejecutable (el sistema en una pantalla)

1. **Captura y califica** por patrón (3/4 criterios), no por rubro. Descarta rápido.
2. **Diagnostica antes de vender:** 70% escuchar, cuantificar la pérdida, nombrar el hueco.
3. **Propón 3 opciones, ancla en Profesional,** Build + Operate siempre juntos, en vivo.
4. **Maneja objeciones reencuadrando** hacia el costo de no actuar; nunca regales precio.
5. **Cierra y haz handoff el mismo día:** el cierre abre un onboarding, no termina una venta.
6. **Opera, demuestra ROI, expande por gatillo** (NRR), retén con valor (anti-churn).
7. **Mira el tablero cada semana:** churn primero, MRR/NRR después, embudo al final.

---

## 11. Documentos relacionados

- [`ARVIOR_OFFER.md`](ARVIOR_OFFER.md) — qué se vende, precios, guion base de reunión y objeciones.
- [`ARVIOR_REVENUE_SYSTEM.md`](ARVIOR_REVENUE_SYSTEM.md) — el producto y el segmento.
- [`ARVIOR_BUSINESS_MODEL.md`](ARVIOR_BUSINESS_MODEL.md) — unit economics y por qué el recurrente es el negocio.
- [`ARVIOR_ONBOARDING_SYSTEM.md`](ARVIOR_ONBOARDING_SYSTEM.md) — qué pasa al cerrar.
- [`ARVIOR_RETENTION_SYSTEM.md`](ARVIOR_RETENTION_SYSTEM.md) — cómo evitamos churn y crecemos la cuenta.
- [`ARVIOR_REVENUE_OPERATIONS.md`](ARVIOR_REVENUE_OPERATIONS.md) — métricas, fórmulas y cadencia de gobierno.
