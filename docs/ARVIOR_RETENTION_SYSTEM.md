# ARVIOR — Retention System (cómo el cliente se queda y crece)

> El negocio de ARVIOR no es vender Builds: es que las cuentas de Operate **se queden
> pagando y valgan más cada mes**. Este documento define cómo evitamos churn,
> aumentamos el valor percibido y convertimos clientes en cuentas que crecen (NRR).
> Última revisión: 2026-06-02 · Estado: operativo / vivo
>
> Viene de: [`ARVIOR_ONBOARDING_SYSTEM.md`](ARVIOR_ONBOARDING_SYSTEM.md) ·
> Por qué importa: [`ARVIOR_BUSINESS_MODEL.md`](ARVIOR_BUSINESS_MODEL.md) §5 ·
> Expansión: [`ARVIOR_SALES_SYSTEM.md`](ARVIOR_SALES_SYSTEM.md) §8 ·
> Métricas: [`ARVIOR_REVENUE_OPERATIONS.md`](ARVIOR_REVENUE_OPERATIONS.md)

---

## 0. Por qué la retención ES el negocio

> **Una agencia vive de vender el próximo proyecto. ARVIOR vive de que el sistema que
> ya construyó siga corriendo. El Build cubre el CAC; cada mes de Operate es margen
> puro acumulado** ([Business Model §0](ARVIOR_BUSINESS_MODEL.md)). Por eso **el churn
> es la métrica número uno** — más que las ventas nuevas.

La aritmética que ordena las prioridades:

- Vida media de una cuenta = **1 / churn mensual**. Con churn 3% → ~33 meses de vida.
  Con churn 6% → ~17 meses: el LTV se parte por la mitad sin vender un peso menos.
- Retener una cuenta cuesta una fracción de adquirir una nueva (el CAC ya está pagado).
- **Objetivo de arranque: churn < 3% mensual, NRR > 110%**
  ([Revenue System §6](ARVIOR_REVENUE_SYSTEM.md), [Business Model §6](ARVIOR_BUSINESS_MODEL.md)).

> Regla mental: **una baja en Operate duele más que una venta nueva.** Si esta semana
> hay que elegir entre cerrar un lead o salvar una cuenta en riesgo, se salva la cuenta.

---

## 1. Los cinco pilares de la permanencia

El recurrente está diseñado para que **quedarse sea obvio e irse duela** — pero por
valor, no por encierro ([Revenue System §4.3](ARVIOR_REVENUE_SYSTEM.md)). Cinco palancas:

| Pilar | Qué hace que el cliente se quede | Cómo lo activamos |
|---|---|---|
| **1. ROI demostrado** | Ve, en números, cuánto le deja el sistema | Reporte mensual de resultados (§3) |
| **2. Valor creciente** | El sistema rinde **más** con el tiempo, no se estanca | Optimización mensual (§4) |
| **3. Switching cost real** | Sus leads, pipeline y seguimiento viven en ARVIOR; irse = apagar su operación | Operación integral + datos dentro |
| **4. Relación, no transacción** | ARVIOR es asesor de crecimiento, no proveedor de hosting | Revisión estratégica (§3.3) |
| **5. Memoria comercial** | El histórico de leads, scoring e insights solo existe dentro | Datos acumulados de la cuenta |

> Importante: **la permanencia se gana, no se amarra.** Los datos son del cliente y se
> exportan si se va ([Offer §13](ARVIOR_OFFER.md)). El foso es el valor, no la cláusula.

---

## 2. Cómo evitamos el churn (sistema anti-churn)

El churn casi nunca es repentino: **da señales**. El sistema anti-churn las detecta
temprano y actúa antes de que el cliente decida irse.

### 2.1 Health score de la cuenta (semáforo mensual)

Cada cuenta se evalúa cada mes en un semáforo simple:

| Señal | 🟢 Verde | 🟡 Amarillo | 🔴 Rojo |
|---|---|---|---|
| **Uso del panel** | Entra regularmente | Esporádico | No entra hace >30 días |
| **Leads capturados** | Estable o subiendo | Bajando | Cayó fuerte / cero |
| **ROI demostrado** | Positivo y visible | Plano | Negativo o no demostrable |
| **Pagos** | Al día | Atraso ocasional | Atraso recurrente / disputa |
| **Relación** | Responde, conversa | Frío, lacónico | Evita reuniones, no responde |
| **Tickets/quejas** | Pocos, resueltos | Repetidos | Abiertos / sin resolver |

**Acción según color:**
- 🟢 → mantener cadencia + buscar gatillo de expansión ([Sales System §8](ARVIOR_SALES_SYSTEM.md)).
- 🟡 → intervención proactiva esta semana: llamar, entender, re-demostrar valor.
- 🔴 → **plan de rescate inmediato** (§2.3). Prioridad sobre ventas nuevas.

### 2.2 Señales tempranas de churn (y qué significan)

| Señal | Qué suele significar | Respuesta |
|---|---|---|
| Deja de abrir el panel | No siente el valor / delegó y se olvidó | Reactivar con el reporte: "mira lo que pasó este mes" |
| "Este mes ando justo de plata" | Cuestiona el ROI, no la plata | Re-demostrar retorno con sus números |
| Baja de leads capturados | Su tráfico/marketing cayó → culpará al sistema | Diagnosticar origen; separar "sistema" de "tráfico" |
| Pide pausar el mensual | Ve Operate como costo, no como valor | Reencuadrar: pausar = apagar su flujo de leads |
| Pregunta "¿esto lo puedo manejar yo?" | Cree que ya no nos necesita | Mostrar lo que opera el sistema que él no ve |
| Cambió el decisor (nuevo gerente) | El nuevo no vivió la venta ni el dolor | Re-onboarding express: re-vender el valor al nuevo |

### 2.3 Plan de rescate (cuenta en rojo)

1. **Contacto directo del responsable de cuenta en 48 h** — humano, no automatizado.
2. **Escuchar primero.** Entender la razón real (rara vez es el precio).
3. **Re-demostrar el ROI** con sus propios números (lo que capturó, respondió, convirtió).
4. **Resolver el dolor concreto** (un ticket abierto, una expectativa desalineada).
5. **Ofrecer un ajuste antes que perder la cuenta:** bajar de plan > perder al cliente.
   Una cuenta en Core viva vale más que una cuenta perdida.
6. **Registrar la razón de churn** si igual se va — alimenta el aprendizaje
   ([Revenue Operations §5](ARVIOR_REVENUE_OPERATIONS.md)).

> **Bajar de plan no es fracaso: es retención.** Es preferible un cliente en Core que
> sigue acumulando vida media y puede volver a subir, que un churn que reinicia el CAC.

---

## 3. Cómo reportamos resultados y demostramos retorno

El reporte mensual es **el producto que sostiene la permanencia.** No es un PDF que se
manda: es la conversación recurrente que mantiene vivo el ROI en la cabeza del cliente.

### 3.1 El reporte mensual de ROI (qué contiene)

| Sección | Qué muestra | Por qué |
|---|---|---|
| **El número grande** | Leads capturados este mes | Lo tangible: "antes no sabías cuántos tenías" |
| **Velocidad** | Tiempo de respuesta promedio (< 5 min) | Prueba de la promesa central de la oferta |
| **Embudo** | Capturado → calificado → en seguimiento → convertido | Muestra el sistema trabajando completo |
| **Valor recuperado** | Leads que antes se habrían perdido, en $ | Conecta el sistema con dinero real |
| **Comparativa** | Este mes vs. anterior / vs. el "antes" | El valor sube con el tiempo (pilar 2) |
| **Qué optimizamos** | Las mejoras que hizo ARVIOR este mes | Justifica el mensual: "esto es lo que hago por ti" |
| **Próximo mes** | Qué vamos a mejorar / probar | Mantiene la relación mirando hacia adelante |

### 3.2 Cómo se entrega

- **En vivo, no por mail** (al menos mensual al inicio; el reporte por escrito complementa).
- Foco en **resultado y dinero**, no en tareas técnicas.
- Honesto cuando un mes fue flojo: explicar por qué (ej. cayó su tráfico) y qué se hará.
  La honestidad construye más permanencia que esconder.
- **Es el contexto natural de la expansión** ([Sales System §8.3](ARVIOR_SALES_SYSTEM.md)):
  si el dato lo justifica, se propone el siguiente escalón **desde el resultado**.

### 3.3 Revisión estratégica (plan Intelligence / cuentas premium)

Para cuentas Intelligence, además del reporte, una **revisión estratégica mensual**
donde ARVIOR actúa como asesor de crecimiento ([Offer §11](ARVIOR_OFFER.md)):

- Lectura de insights y scoring del embudo: dónde gana y dónde pierde el cliente.
- Recomendaciones accionables sobre su negocio, no solo sobre el sistema.
- Esto convierte a ARVIOR de proveedor en **socio** — el vínculo más difícil de romper.

---

## 4. Cómo aumentamos el valor percibido (que rinda más cada mes)

El cliente debe **sentir** que el mes 12 vale más que el mes 1. Cómo:

| Palanca | Acción mensual de ARVIOR | Qué percibe el cliente |
|---|---|---|
| **Optimización de conversión** | A/B de landings, copy, formularios | "Mi sistema convierte mejor que antes" |
| **Afinamiento del seguimiento** | Mejora de secuencias y respuesta (humano + IA) | "Pierdo menos leads cada mes" |
| **Mejora visible en el reporte** | Mostrar la curva de mejora mes a mes | "Esto sube, no se estanca" |
| **Novedades proactivas** | Proponer una mejora antes de que la pida | "Se preocupan por mi negocio" |
| **Educación** | Explicar qué significan sus números | "Entiendo mi negocio mejor gracias a ellos" |

> El antídoto contra "¿para qué sigo pagando?" no es un argumento — es **una curva de
> mejora visible en el reporte.** El dato hace el trabajo.

---

## 5. Cómo logramos que el cliente se quede pagando Operate (y suba)

La retención y la expansión son el mismo movimiento: una cuenta sana **crece**. Aquí la
retención se conecta con la expansión del [Sales System §8](ARVIOR_SALES_SYSTEM.md).

### 5.1 Ritmo de la relación (cadencia que sostiene)

| Momento | Acción | Objetivo |
|---|---|---|
| **Mensual** | Reporte de ROI en vivo (§3) | Mantener el valor presente |
| **Trimestral** | Revisión de objetivos + propuesta de siguiente escalón | Detectar gatillos de expansión |
| **Pre-renovación (mes 10–11)** | Reunión de balance del año + plan del próximo | Renovar desde el valor, no desde el contrato |
| **Cada hito del cliente** | Felicitar / capitalizar (sucursal, premio, campaña) | Profundizar la relación |

### 5.2 La renovación (mes 12 → mensual)

La permanencia sugerida es 12 meses, luego mensual sin amarre
([Offer §9–§11](ARVIOR_OFFER.md)). La renovación **no se pide: se construye todo el año**.

- Si el health score fue verde y el ROI se demostró cada mes, la renovación es trámite.
- En la reunión de balance: mostrar el **acumulado del año** (todos los leads
  capturados, todo lo recuperado) — el número grande del año entero.
- Plantear el próximo año como **crecimiento**, no como continuidad: "este año capturaste
  X; el próximo, con [expansión], apuntamos a Y".

### 5.3 Expansión como retención (NRR > 110%)

Un cliente que crece dentro de ARVIOR es un cliente que **no se va**: cada módulo o plan
adicional sube su switching cost y su valor percibido. Los **gatillos y la mecánica de
expansión** viven en [`ARVIOR_SALES_SYSTEM.md`](ARVIOR_SALES_SYSTEM.md) §8. Desde
retención, la regla es:

- **Solo expandir desde la fuerza:** cuenta verde, ROI demostrado, sin tickets abiertos.
- **Anclar la propuesta en su propio dato** del reporte mensual.
- **Nunca expandir un mes flojo** — primero resolver y demostrar valor.

---

## 6. Qué medir (retención)

| Métrica | Qué dice | Objetivo de arranque |
|---|---|---|
| **Churn lógico mensual** | % de cuentas que se van | < 3% |
| **Vida media** (1 / churn) | Meses que dura una cuenta | > 30 meses |
| **NRR** | Cuánto crece la base existente (expansión − contracción − churn) | > 110% |
| **% cuentas que suben de plan a 6 meses** | Si el valor recurrente es real | Señal clave de salud |
| **Health score (mix verde/amarillo/rojo)** | Salud de la cartera | Mayoría verde; rojos atendidos < 48h |
| **ROI demostrado por cuenta** | El número que sostiene la permanencia | Positivo y visible cada mes |

> Detalle de fórmulas y cadencia de gobierno en
> [`ARVIOR_REVENUE_OPERATIONS.md`](ARVIOR_REVENUE_OPERATIONS.md).

---

## 7. Resumen ejecutable

1. **El churn es la métrica #1.** Una baja duele más que una venta nueva.
2. **Mide la salud de cada cuenta cada mes** (semáforo). Atiende los rojos en 48 h.
3. **El reporte mensual de ROI en vivo es el producto de la retención** — no un PDF.
4. **Haz que el valor suba con el tiempo:** optimización visible mes a mes.
5. **Bajar de plan > perder la cuenta.** Retener es preferible a reiniciar el CAC.
6. **Renueva construyendo todo el año,** no pidiendo en el mes 12.
7. **La cuenta sana crece:** expande desde la fuerza, anclado en su propio dato.

---

## 8. Documentos relacionados

- [`ARVIOR_ONBOARDING_SYSTEM.md`](ARVIOR_ONBOARDING_SYSTEM.md) — la retención empieza en el primer mes.
- [`ARVIOR_SALES_SYSTEM.md`](ARVIOR_SALES_SYSTEM.md) — gatillos y mecánica de expansión (§8).
- [`ARVIOR_BUSINESS_MODEL.md`](ARVIOR_BUSINESS_MODEL.md) — por qué el recurrente y el churn mandan.
- [`ARVIOR_REVENUE_OPERATIONS.md`](ARVIOR_REVENUE_OPERATIONS.md) — métricas, fórmulas y cadencia.
- [`ARVIOR_OFFER.md`](ARVIOR_OFFER.md) — planes de Operate y garantías.
