# ARVIOR — Onboarding System (de la firma al sistema funcionando)

> Lo que pasa **desde el segundo en que el cliente paga** hasta que su Revenue System
> está capturando, convirtiendo y demostrando valor. El onboarding no es trámite: es
> el momento donde se gana o se pierde la retención de los próximos 30 meses.
> Última revisión: 2026-06-02 · Estado: operativo / vivo
>
> Viene de: [`ARVIOR_SALES_SYSTEM.md`](ARVIOR_SALES_SYSTEM.md) §7 (handoff) ·
> Lleva a: [`ARVIOR_RETENTION_SYSTEM.md`](ARVIOR_RETENTION_SYSTEM.md) ·
> Qué se entregó: [`ARVIOR_OFFER.md`](ARVIOR_OFFER.md)

---

## 0. El principio del onboarding

> **El churn se decide en los primeros 30 días, no en el mes 12. Un cliente que ve su
> sistema funcionando y entiende lo que pasó, se queda años. Un cliente que paga y
> queda en silencio dos semanas, ya está pensando en irse.** El onboarding existe para
> entregar una **primera victoria visible rápido** y para que el cliente sienta que
> contrató a un socio, no a un proveedor.

Tres reglas que lo gobiernan:

1. **Velocidad al primer valor (Time-to-First-Lead).** El objetivo número uno es que el
   sistema capture su **primer lead real** lo antes posible — esa es la prueba viva de
   que funciona.
2. **El cliente nunca está en silencio.** Comunicación proactiva en cada hito; el
   cliente siempre sabe qué pasó, qué sigue y cuándo.
3. **Operate se cobra desde el día 1** y la **implementación arranca con Build +
   primer mes de Operate pagados** ([Offer §12, regla 3](ARVIOR_OFFER.md)). No se
   trabaja gratis "hasta que funcione": el primer mes de Operate ya está pagado cuando
   empieza el build.

---

## 1. Handoff: de ventas a entrega (día 0)

El mismo día del cierre, Comercial entrega a Entrega un **paquete de handoff** para que
el cliente nunca tenga que repetir lo que ya contó en la venta.

### 1.1 Paquete de handoff (qué pasa de Comercial a Entrega)

| Campo | Contenido |
|---|---|
| **Diagnóstico** | El embudo actual y la **pérdida cuantificada** que se usó en la venta |
| **Dolor principal** | La frase exacta del cliente sobre qué le duele |
| **Expectativa de éxito** | Cómo se ve "esto funcionó" para *este* cliente (su métrica) |
| **Alcance acordado** | Escenario (Entrada/Profesional/Premium) + lo que SÍ incluye |
| **Exclusiones acordadas** | Lo que NO incluye, dicho en la venta ([Offer §5](ARVIOR_OFFER.md)) — clave anti scope creep |
| **Plan de Operate** | Core / Growth / Intelligence contratado |
| **Decisor y usuarios** | Quién decide, quién va a usar el panel, quién responde leads |
| **Sensibilidades** | Plazos prometidos, fechas críticas (campaña que arranca, etc.) |

### 1.2 Acciones automáticas del día 0

- Crear la cuenta en el sistema de ARVIOR (cliente, proyecto, plan de Operate activo).
- **Confirmar pago de Build + primer mes de Operate** (es lo que habilita el arranque
  de la implementación); activar facturación recurrente **desde el día 1**.
- Agendar el **kickoff** (≤ 5 días hábiles desde el pago).
- Enviar correo de bienvenida con: qué sigue, qué necesitamos de él, fecha de kickoff y
  un punto de contacto con nombre y cara (no un "soporte@").
- Pedir el **primer referido** (entusiasmo en su punto máximo).

---

## 2. Reunión de kickoff (≤ 5 días hábiles)

La primera reunión de la relación. **No es técnica**: es para alinear, dar confianza y
fijar el plan. 45–60 min.

| Bloque | Tiempo | Objetivo |
|---|:---:|---|
| **1. Bienvenida y equipo** | 5 min | Presentar quién opera su cuenta (caras, no roles abstractos) |
| **2. Reconfirmar el resultado** | 10 min | "Esto es lo que vamos a lograr y cómo se verá" — repetir su métrica de éxito |
| **3. El plan y el cronograma** | 10 min | Mostrar las fases, hitos y fechas (§4). Qué hará ARVIOR, qué necesita de él |
| **4. Qué necesitamos de ti** | 15 min | Recolección de información y accesos (§3). Asignar responsables y fechas |
| **5. Cómo trabajamos** | 5 min | Canal de comunicación, frecuencia de updates, cómo pedir cosas |
| **6. Cierre y próximo hito** | 5 min | Confirmar la primera fecha concreta y qué pasa esta semana |

> **Tono del kickoff:** transmitir control y calma. El cliente acaba de gastar dinero;
> el kickoff es donde confirma que hizo bien. Sale sabiendo exactamente qué pasa y
> cuándo ve el primer resultado.

---

## 3. Recolección de información y accesos

Se pide **una sola vez, estructurado**, con un checklist. Nada de ir pidiendo cosas a
goteo (mata la confianza y atrasa).

### 3.1 Información del negocio

- Propuesta de valor, servicios y precios (para el copy que convierte).
- Tipos de lead y cómo los clasifican hoy (alimenta calificación/enrutamiento).
- Preguntas frecuentes y objeciones de sus clientes (alimenta autorespuesta / IA).
- Horarios de atención y reglas de derivación (quién atiende qué).
- Material de marca existente (logo, fotos, textos) — dentro del alcance acordado.

### 3.2 Accesos (con principio de mínimo privilegio)

| Acceso | Para qué | Cuándo |
|---|---|---|
| Dominio / DNS | Publicar el sitio/landing | Antes de salir a producción |
| WhatsApp Business **API** (Oficial, vía BSP) | Captura y respuesta automática por WhatsApp — estándar de ARVIOR | Fase de captura |
| Plataforma de ads (lectura) | Conectar y medir el embudo | Si hay integración con ads |
| Calendario | Agendamiento integrado | Profesional/Premium |
| CRM/herramienta actual | Integrar o migrar datos | Si aplica |
| Redes/Google Business | Si entran en alcance | Solo si contratado |

> **Regla de accesos:** pedir solo lo necesario para la fase, documentar quién dio qué
> y cuándo, y dejar claro en el contrato que los datos son del cliente
> ([Offer §13](ARVIOR_OFFER.md)). Si el cliente tarda en dar un acceso, **ese atraso es
> suyo y se comunica** — no se come el plazo de ARVIOR en silencio.

### 3.3 Responsables (RACI mínimo)

| Rol | Lado ARVIOR | Lado cliente |
|---|---|---|
| **Responsable de cuenta** | Punto único de contacto | Decisor / dueño |
| **Quién entrega info y accesos** | — | Persona designada (puede ser asistente) |
| **Quién responde leads (humano)** | Opera lo automático | Equipo de atención/ventas del cliente |
| **Quién aprueba (copy, diseño, salida a producción)** | Propone | Decisor |

> Si no hay una persona clara del lado del cliente que entregue info y apruebe, el
> proyecto se atrasa. Se nombra en el kickoff, con nombre y compromiso.

---

## 4. Cronograma y entregables

Plazos según escenario ([Offer §6–§8](ARVIOR_OFFER.md)): Entrada ~2–3 sem, Profesional
~4–6 sem, Premium ~6–10 sem. El cronograma se presenta en el kickoff con **fechas
reales**, no rangos vagos.

### 4.1 Fases de entrega (genéricas; se ajustan al escenario)

| Fase | Qué se construye | Entregable visible | Hito |
|---|---|---|---|
| **F1. Captura** | Sitio/landing + form + WhatsApp + anti-spam + dedupe + panel base + notificación de lead | Sistema **capturando leads reales** | 🎯 **Primer lead capturado** (la primera victoria) |
| **F2. Calificación + seguimiento** | Reglas de calificación/enrutamiento + secuencias de seguimiento + autorespuesta (+ IA en Premium) | Leads clasificados y con seguimiento automático | Primer lead **seguido sin intervención humana** |
| **F3. Conversión + agenda** | Agendamiento + handoff a humano + panel de pipeline | Cliente **agenda/cierra desde el panel** | Primera conversión registrada en el sistema |
| **F4. Integraciones + inteligencia** | Integraciones (ads/CRM/calendario) + scoring/insights (Premium) | Embudo conectado punta a punta | Reporte de embudo completo funcionando |
| **F5. Capacitación + salida** | Capacitación del equipo + entrega del panel + documentación simple | Cliente **operando su panel con confianza** | ✅ **Go-live / aceptación** |

> Entrada llega hasta F1 + capacitación; Profesional cubre F1–F3 + F5; Premium cubre
> todas. El **hito que más importa para retención es el de F1**: cuanto antes el cliente
> ve un lead real entrar, antes cree en el sistema.

### 4.2 Reglas de cronograma

- **Cada fase cierra con algo visible**, no con trabajo invisible. El cliente ve avance.
- **Los atrasos del cliente (accesos, aprobaciones, info) se comunican y mueven la fecha
  explícitamente** — no se absorben en silencio (protege el plazo y la relación).
- **El alcance está cerrado** ([Offer §5](ARVIOR_OFFER.md)). Lo que pida de más entra
  como expansión ([Sales System §8](ARVIOR_SALES_SYSTEM.md)), no como "ya que estamos".

---

## 5. La primera semana

El objetivo de la semana 1 es **confianza + arranque real**, no terminar el sistema.

| Día | ARVIOR | Cliente |
|---|---|---|
| **Día 1 (cierre/pago)** | Crear cuenta, activar Operate, enviar bienvenida, agendar kickoff | Recibe bienvenida; sabe qué sigue |
| **Días 2–4** | Preparar kickoff, checklist de info/accesos | Designa responsable, junta accesos |
| **Día ≤5 (kickoff)** | Alinear plan, cronograma, responsables | Entrega info inicial, aprueba el plan |
| **Días 5–7** | Arrancar F1 (captura): montar el esqueleto sobre el repo base | Da accesos pendientes (dominio, WhatsApp) |

**Salida de la semana 1:** cliente con plan claro, fechas en el calendario, sabe quién
es su contacto, y la construcción de la captura ya arrancó. **Cero silencio.**

---

## 6. El primer mes

El primer mes termina con el sistema **capturando de verdad** y el cliente **viendo el
valor** — la base de toda la retención posterior.

| Semana | Foco | Resultado esperado |
|---|---|---|
| **Sem 1** | Kickoff + arranque de captura | Plan claro, F1 en marcha (§5) |
| **Sem 2** | Captura funcionando | 🎯 **Primer lead real capturado y notificado** |
| **Sem 3** | Calificación + seguimiento (según plan) | Leads clasificados y con seguimiento automático |
| **Sem 4** | Conversión/agenda + capacitación | Cliente opera su panel; primeros leads en pipeline |

### 6.1 Primer reporte de valor (cierre del mes 1)

Al final del primer mes (o del go-live), se entrega el **primer reporte de ROI** en una
breve reunión — no por mail. Es el primer eslabón de la retención
([Retention §3](ARVIOR_RETENTION_SYSTEM.md)):

- Cuántos leads capturó el sistema (vs. "no sé cuántas consultas tengo" del antes).
- Cuántos se respondieron automáticamente y en cuánto tiempo (< 5 min).
- Qué pasó en el pipeline (agendados, en seguimiento, convertidos si los hay).
- **El antes/después concreto:** "antes no sabías cuántas consultas tenías; ahora son
  estos [N], todas respondidas, ninguna perdida."

> Este reporte convierte el gasto en inversión a los ojos del cliente y **abre la
> conversación de permanencia** desde el valor, no desde el contrato. Aquí empieza la
> retención.

### 6.2 Transición a Operate (fin del onboarding)

Al cerrar el primer mes / go-live, la cuenta pasa formalmente a **modo Operate**:

- Se fija la cadencia de reportes y revisión mensual ([Retention §3](ARVIOR_RETENTION_SYSTEM.md)).
- Se define la próxima revisión y los gatillos de expansión a vigilar
  ([Sales System §8.2](ARVIOR_SALES_SYSTEM.md)).
- La cuenta entra al dashboard de salud de clientes ([Revenue Operations §4](ARVIOR_REVENUE_OPERATIONS.md)).

---

## 7. Checklist maestro de onboarding (uso operativo)

```
DÍA 0 — HANDOFF Y ACTIVACIÓN
[ ] Paquete de handoff de Comercial completo (§1.1)
[ ] Cuenta creada + Operate activado + facturación recurrente (desde día 1)
[ ] Pago de Build + primer mes de Operate confirmado (habilita el arranque)
[ ] Correo de bienvenida enviado (contacto con nombre)
[ ] Kickoff agendado (≤5 días hábiles)
[ ] Primer referido solicitado

KICKOFF (≤ DÍA 5)
[ ] Resultado y métrica de éxito reconfirmados
[ ] Cronograma con fechas reales presentado
[ ] Responsables nombrados (ARVIOR y cliente)
[ ] Checklist de info y accesos entregado al cliente
[ ] Canal y cadencia de comunicación acordados

ENTREGA (POR FASE)
[ ] F1 Captura → 🎯 primer lead real capturado
[ ] F2 Calificación + seguimiento (según plan)
[ ] F3 Conversión + agenda (según plan)
[ ] F4 Integraciones + inteligencia (Premium)
[ ] F5 Capacitación + aceptación / go-live

CIERRE DEL MES 1
[ ] Primer reporte de ROI presentado en vivo
[ ] Cadencia mensual de reportes fijada
[ ] Gatillos de expansión identificados
[ ] Cuenta en el dashboard de salud de clientes
[ ] Segundo pedido de referido (con resultado en mano)
```

---

## 8. Errores de onboarding que generan churn (evitarlos)

| Error | Consecuencia | Cómo se evita |
|---|---|---|
| Silencio tras el pago | El cliente duda de su decisión | Comunicación proactiva en cada hito (§0, regla 2) |
| Pedir accesos a goteo | Atraso + sensación de desorden | Checklist único en el kickoff (§3) |
| Absorber atrasos del cliente en silencio | Se incumple el plazo y la culpa cae en ARVIOR | Comunicar y mover fecha explícitamente (§4.2) |
| Terminar sin un primer resultado visible | El cliente no "siente" el valor | Priorizar Time-to-First-Lead (§0, regla 1; F1) |
| Cerrar el proyecto sin reporte de ROI | Se pierde el ancla de permanencia | Reporte del mes 1 obligatorio (§6.1) |
| No nombrar responsable del lado cliente | Aprobaciones trabadas, proyecto detenido | RACI en el kickoff (§3.3) |
| Aceptar pedidos fuera de alcance "gratis" | Erosión de margen y plazo | Alcance cerrado → expansión, no regalo (§4.2) |

---

## 9. Documentos relacionados

- [`ARVIOR_SALES_SYSTEM.md`](ARVIOR_SALES_SYSTEM.md) — el handoff que inicia el onboarding (§7).
- [`ARVIOR_OFFER.md`](ARVIOR_OFFER.md) — alcance, exclusiones y garantías que se entregan.
- [`ARVIOR_RETENTION_SYSTEM.md`](ARVIOR_RETENTION_SYSTEM.md) — qué pasa después del mes 1.
- [`ARVIOR_REVENUE_OPERATIONS.md`](ARVIOR_REVENUE_OPERATIONS.md) — métricas y salud de la cuenta.
