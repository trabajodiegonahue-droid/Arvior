# ARVIOR — SOPs Map (mapa de procedimientos operativos)

> El catálogo de **procedimientos ejecutables** de ARVIOR. Cada SOP es un checklist
> con disparador, dueño, pasos y criterio de hecho (Definition of Done). No describe
> *por qué* (eso está en los documentos de estrategia); describe *cómo se hace, paso
> a paso*, para que cualquiera lo ejecute igual.
> Última revisión: 2026-06-02 · Estado: operativo / vivo
>
> Marco: [`ARVIOR_OPERATING_SYSTEM.md`](ARVIOR_OPERATING_SYSTEM.md) §3 ·
> Auditoría origen: [`ARVIOR_EXECUTION_AUDIT.md`](ARVIOR_EXECUTION_AUDIT.md) §3.3

---

## 0. Cómo funcionan los SOPs en ARVIOR

> **Un SOP existe para que el resultado no dependa de quién lo ejecuta. Si una tarea
> se hace dos veces, se convierte en SOP. Si un SOP no se puede seguir sin preguntar,
> está mal escrito.**

**Anatomía de un SOP (todos siguen este molde):**

| Campo | Qué define |
|---|---|
| **Código** | Identificador estable (SOP-NN) |
| **Disparador** | El evento que lo inicia (no "cuando haya tiempo") |
| **Dueño** | La función responsable (Operating System §1.1) |
| **Entrada** | Lo que debe existir antes de empezar |
| **Pasos** | La secuencia ejecutable |
| **DoD** | Cómo se sabe que terminó (criterio objetivo) |
| **Fuente** | El documento de estrategia que lo respalda |

**Reglas de gobierno de los SOPs:**

1. **Viven en Notion** (editables, versionados), espejando este índice canónico de
   GitHub (Operating System §4.1).
2. **Se escribe el SOP la segunda vez** que se ejecuta la tarea, no la primera.
3. **Prioridad:** los **P0 se escriben antes de cerrar el segundo cliente**; P1 en el
   primer trimestre; P2 cuando el volumen lo pida.
4. **Cada SOP cita su fuente** — si la estrategia cambia, el SOP se actualiza, nunca
   al revés.

**Estado de escritura (al 2026-06-02): todos los SOPs están _especificados_ aquí
(disparador, pasos, DoD) pero aún no _instanciados_ como checklist vivo en Notion.**
Instanciarlos es parte de los 90 días (Operating System §7.1).

---

## 1. Índice maestro de SOPs

| SOP | Nombre | Estación (flujo) | Dueño | Prioridad |
|---|---|---|---|:---:|
| **SOP-01** | Calificación y registro de lead | Lead | RevOps/Comercial | **P0** |
| **SOP-02** | Agenda y preparación de reunión | Venta | Comercial | P1 |
| **SOP-03** | Diagnóstico comercial | Venta | Comercial | **P0** |
| **SOP-04** | Armado y presentación de propuesta | Venta | Comercial | **P0** |
| **SOP-05** | Cierre, contrato y cobro | Venta | Comercial/RevOps | **P0** |
| **SOP-06** | Handoff comercial → entrega | Onboarding | Comercial→Entrega | **P0** |
| **SOP-07** | Kickoff y recolección de accesos | Onboarding | Entrega | P1 |
| **SOP-08** | Implementación por fases y go-live | Implementación | Entrega | **P0** |
| **SOP-09** | Reporte de ROI mensual | Operate | Operaciones | **P0** |
| **SOP-10** | Operación y optimización mensual | Operate | Operaciones | P1 |
| **SOP-11** | Health score y rescate de cuenta | Retención | Soporte/Éxito | **P0** |
| **SOP-12** | Expansión / upsell | Expansión | Soporte→Comercial | P1 |
| **SOP-13** | Pedido de referidos | Transversal | Comercial | P2 |
| **SOP-14** | Gestión de accesos y continuidad (bus factor) | Transversal | RevOps | **P0** |
| **SOP-15** | Cierre de mes (facturación + métricas) | RevOps | RevOps | P1 |

> **9 SOPs son P0** — son el esqueleto mínimo para operar sin improvisar. Si sólo se
> escriben estos, ARVIOR ya es operable.

---

## 2. SOPs de adquisición (Lead → Venta)

### SOP-01 · Calificación y registro de lead `P0`

- **Disparador:** llega un lead (web, WhatsApp, referido, outbound).
- **Dueño:** RevOps/Comercial. **Fuente:** Sales §2.2–2.3.
- **Pasos:**
  1. Registrar el lead en el CRM en **etapa 0 (Lead calificado)** con origen del canal.
  2. Evaluar criterio de admisión **3/4** (gasta en captar · lead vale mucho · pierde
     lo que capta · sin equipo técnico).
  3. Asignar **score semáforo:** 🟢 4/4 + urgencia · 🟡 3/4 · 🔴 ≤2/4.
  4. Definir **próxima acción con fecha** (sin esto, el lead está mal gestionado).
  5. 🟢 → SLA contacto < 24 h. 🟡 → agendar/nurture. 🔴 → nurture educativo o descarte
     **con razón registrada**.
- **DoD:** lead en CRM con origen, score, próxima acción con fecha y dueño. Descartes
  con razón de la taxonomía (RevOps §5.1).

### SOP-02 · Agenda y preparación de reunión `P1`

- **Disparador:** lead 🟢/🟡 acepta reunión.
- **Dueño:** Comercial. **Fuente:** Sales §4.1, Offer §18.
- **Pasos:**
  1. Confirmar que **el decisor estará presente** (si no, no se agenda propuesta; Sales §3.2 regla 6).
  2. Agendar 30–45 min con recordatorio automático.
  3. Pre-investigar: rubro, web actual, presencia de ads, ticket estimado.
  4. Preparar 2–3 preguntas de descubrimiento específicas del rubro (Sales §4.2).
- **DoD:** reunión agendada con decisor confirmado + ficha de preparación lista. CRM
  → **etapa 1 (Reunión agendada)**.

### SOP-03 · Diagnóstico comercial `P0`

- **Disparador:** reunión realizada.
- **Dueño:** Comercial. **Fuente:** Sales §4 (corazón del sistema comercial).
- **Pasos:**
  1. Encuadre (3 min): acordar agenda y permiso para preguntar. **No pitchear.**
  2. Descubrimiento (12–15 min): mapear el embudo actual con las preguntas de Sales
     §4.2. **Hablar 30%, escuchar 70%.**
  3. Cuantificar el hueco: leads/mes × gasto en ads × % que se enfría × valor por
     cliente = **pérdida mensual estimada**.
  4. Nombrar el hueco en su lenguaje y con su número (Sales §4.3).
  5. **No cotizar sin diagnóstico.** Si preguntan precio, anclar rango y volver al
     embudo.
  6. Confirmar fit y acordar fecha de propuesta.
- **DoD:** dolor nombrado + **número de pérdida cuantificado** + fit confirmado. CRM →
  **etapa 2 (Diagnóstico hecho)**. Si no califica → cierre honesto con razón.

### SOP-04 · Armado y presentación de propuesta `P0`

- **Disparador:** diagnóstico hecho + fit + dolor cuantificado.
- **Dueño:** Comercial. **Fuente:** Sales §5, Offer §6–§12.
- **Pasos:**
  1. Armar la propuesta de 1 página (Sales §5.1): situación+pérdida → resultado →
     sistema 4 pasos → 3 opciones → alcance/exclusiones → garantías → siguiente paso.
  2. **Build + Operate siempre juntos**; nunca Build solo a precio estándar.
  3. **Presentar en vivo** (pantalla compartida), nunca sólo por mail.
  4. Orden: situación → pérdida → resultado → sistema → opciones → garantías → cierre.
     **Precio al final.**
  5. Anclar en **Profesional** explícitamente.
  6. Cerrar con pregunta de avance: *"¿Arrancamos con Profesional?"*
  7. Si objeción → SOP-04 usa el banco de objeciones (Sales §6). Si pide plazo →
     aislar: *"¿el alcance o la inversión?"*
- **DoD:** propuesta presentada en vivo. CRM → **etapa 3 (Propuesta enviada)** o
  **etapa 4 (Negociación)** si negocia.

### SOP-05 · Cierre, contrato y cobro `P0`

- **Disparador:** acuerdo verbal / señal de compra.
- **Dueño:** Comercial + RevOps. **Fuente:** Sales §7, Offer §12–§13. **Resuelve
  Audit §1.2, §2.5 (D1, D2).**
- **Pasos:**
  1. Emitir contrato: Build (alcance + precio + plazo) **y** Operate (plan +
     mensualidad + garantías).
  2. **Aplicar las decisiones binarias del fundador (Audit §7):**
     - **D1 — Gatillo de cobro de Operate:** Operate se factura desde **go-live /
       primer lead (F1)**, no desde la firma (resuelve la contradicción Audit §1.2).
     - **D2 — Término:** mes a mes con aviso de 30 días (o el término que el fundador
       fije), escrito explícito — no "permanencia sugerida" ambigua.
  3. Condiciones de pago del Build: anticipo % + saldo contra hito.
  4. Cobrar el anticipo / primer pago. **Sin pago, no hay cierre** (etapa 5 requiere
     primer pago).
  5. Guardar contrato firmado en la carpeta Drive del cliente.
  6. Disparar **SOP-06 (handoff) el mismo día** y **SOP-13 (referido)**.
- **DoD:** contrato firmado + primer pago confirmado + en Drive. CRM → **etapa 5
  (Cerrado–Ganado)**. Handoff disparado.

---

## 3. SOPs de entrega (Onboarding → Implementación)

### SOP-06 · Handoff comercial → entrega `P0`

- **Disparador:** cierre (SOP-05) — **el mismo día**.
- **Dueño:** Comercial → Entrega. **Fuente:** Onboarding §1, Sales §7.3.
- **Pasos:**
  1. Completar el **paquete de handoff** (Onboarding §1.1): diagnóstico, dolor
     textual, expectativa de éxito (métrica del cliente), alcance, **exclusiones
     acordadas**, plan de Operate, decisor/usuarios, sensibilidades de plazo.
  2. Crear la cuenta en el CRM (cliente, proyecto, plan de Operate activo) y la
     **carpeta Drive**.
  3. Activar facturación recurrente con gatillo en go-live (D1).
  4. Agendar kickoff ≤ 5 días hábiles.
  5. Enviar correo de bienvenida con **contacto con nombre y cara** (no "soporte@").
- **DoD:** paquete de handoff completo en el CRM + cuenta creada + kickoff agendado +
  bienvenida enviada. El cliente nunca repite lo que ya contó.

### SOP-07 · Kickoff y recolección de accesos `P1`

- **Disparador:** handoff hecho.
- **Dueño:** Entrega. **Fuente:** Onboarding §2–§3.
- **Pasos:**
  1. Kickoff 45–60 min (no técnico): bienvenida → reconfirmar resultado → plan y
     cronograma con **fechas reales** → qué necesitamos de ti → cómo trabajamos →
     próximo hito.
  2. Entregar **un solo checklist** de info y accesos (nada a goteo).
  3. Recolectar accesos con **mínimo privilegio**; cargarlos en la **bóveda**
     (SOP-14), registrando quién dio qué y cuándo.
  4. Nombrar responsables (RACI mínimo, Onboarding §3.3): quién aprueba, quién entrega
     info, quién responde leads.
  5. Dejar claro: **atrasos de accesos del cliente mueven la fecha explícitamente**
     (no se absorben).
- **DoD:** kickoff hecho, accesos en bóveda, responsables nombrados, cronograma con
  fechas aceptado.

### SOP-08 · Implementación por fases y go-live `P0`

- **Disparador:** kickoff aprobado + accesos mínimos de F1.
- **Dueño:** Entrega. **Fuente:** Onboarding §4. **Atiende Audit §2.4, §3.4.**
- **Pasos:**
  1. Construir por fases sobre Core: **F1 Captura** → F2 Calificación+seguimiento →
     F3 Conversión+agenda → F4 Integraciones+inteligencia → F5 Capacitación+salida
     (según escenario).
  2. **Cada fase cierra con un entregable visible** y un **DoD objetivo** (criterio de
     aceptación escrito, no impresión — resuelve Audit §3.4).
  3. **Separar dos hitos (resuelve Audit §2.4):**
     - *"Captura técnicamente lista"* → comprometido por fecha (ARVIOR lo controla).
     - *"Primer lead real"* → evento esperado, **no prometido por fecha** (depende del
       tráfico del cliente).
  4. Verificar la **automatización #1**: respuesta < 5 min funcionando antes del
     go-live (sin esto, no hay go-live; Audit §1.3).
  5. Capacitar al equipo del cliente y entregar el panel.
  6. Aceptación / go-live firmado → activa cobro de Operate (D1) y dispara Operate
     (SOP-09/10).
- **DoD:** go-live aceptado con DoD por fase cumplido + respuesta < 5 min verificada +
  cliente capacitado. Cuenta pasa a modo Operate.

---

## 4. SOPs de operación y retención (Operate → Retención → Expansión)

### SOP-09 · Reporte de ROI mensual `P0`

- **Disparador:** cierre de cada mes calendario por cuenta activa.
- **Dueño:** Operaciones. **Fuente:** Retention §3, Onboarding §6.1. **Dueño único del
  reporte (resuelve solapamiento Audit §5).**
- **Pasos:**
  1. Generar el reporte (datos automáticos desde Core): leads capturados · tiempo de
     respuesta (< 5 min) · embudo capturado→convertido · valor recuperado en $ ·
     comparativa vs mes anterior/antes · qué se optimizó · qué viene.
  2. **Entregar en vivo** para cuentas premium / en riesgo. Para cuentas verdes
     estables: reporte estandarizado auto-enviado + revisión en vivo opcional
     (degradación elegante que resuelve la trampa de tiempo, Audit §1.1).
  3. Si el mes fue flojo: explicarlo con honestidad (ej. cayó su tráfico) y qué se
     hará. **Separar "sistema" de "tráfico".**
  4. Si hay gatillo de expansión → registrarlo para SOP-12 (no vender en el mismo
     acto si el mes fue flojo).
- **DoD:** reporte entregado, ROI demostrado registrado en el health score, próxima
  revisión agendada.

### SOP-10 · Operación y optimización mensual `P1`

- **Disparador:** cuenta en modo Operate.
- **Dueño:** Operaciones. **Fuente:** Revenue System §4.1, Retention §4.
- **Pasos:**
  1. **Operación:** verificar uptime, captura, secuencias, agenda; resolver tickets.
  2. **Optimización (según plan):** A/B de landings/copy/formularios; afinar
     secuencias de seguimiento y respuesta (humano + IA).
  3. Registrar **qué se optimizó** (alimenta el reporte SOP-09 — "esto es lo que hago
     por ti").
  4. Vigilar costos variables (tokens IA, conversaciones WhatsApp) contra el margen.
- **DoD:** sistema operando sin incidentes abiertos + al menos una mejora registrada
  en el mes + costos dentro de margen.

### SOP-11 · Health score y rescate de cuenta `P0`

- **Disparador:** evaluación mensual de salud + cualquier señal temprana de churn.
- **Dueño:** Soporte/Éxito. **Fuente:** Retention §2, RevOps §4.
- **Pasos:**
  1. Calcular el **health score** mensual (semáforo, Retention §2.1): uso del panel ·
     leads capturados · ROI · pagos · relación · tickets.
  2. **🟢** → mantener cadencia + buscar gatillo de expansión (SOP-12).
     **🟡** → intervención proactiva en la semana: llamar, entender, re-demostrar valor.
     **🔴** → **plan de rescate < 48 h** (humano, no automatizado).
  3. **Plan de rescate (rojo):** contacto directo en 48 h → escuchar primero →
     re-demostrar ROI con sus números → resolver el dolor concreto → **ofrecer bajar
     de plan antes que perder la cuenta** → registrar razón de churn si igual se va.
  4. Pre-renovación (mes 10–11): reunión de balance del año + plan del próximo.
- **DoD:** toda cuenta con color asignado; rojos con plan de rescate activo < 48 h;
  razón de churn registrada si aplica (taxonomía RevOps §5.1).

### SOP-12 · Expansión / upsell `P1`

- **Disparador:** gatillo de expansión observado en cuenta **verde, sin tickets
  abiertos, ROI demostrado**.
- **Dueño:** Soporte detecta → Comercial cotiza/cierra (resuelve doble dueño Audit §5).
- **Fuente:** Sales §8, Retention §5.3.
- **Pasos:**
  1. Confirmar que se expande **desde la fuerza** (nunca un mes flojo ni con ticket
     abierto).
  2. Identificar el camino natural según el gatillo (Sales §8.2): subir de plan, IA
     conversacional, nueva landing/sucursal, integración, etc.
  3. **Anclar la propuesta en su propio dato** del reporte mensual.
  4. Presentar como **evolución** ("subes de plan"), no como venta nueva; cuantificar
     el retorno del salto.
  5. Cotizar y cerrar (Comercial); actualizar MRR en el CRM.
- **DoD:** expansión cotizada y aceptada (+MRR) o registrada como "no ahora" con
  gatillo a re-evaluar. CRM refleja el nuevo MRR.

---

## 5. SOPs transversales

### SOP-13 · Pedido de referidos `P2`

- **Disparador:** tres momentos (con tope para no fatigar — resuelve Audit §5):
  **(a)** al cierre (SOP-05), **(b)** con el primer reporte de ROI (SOP-09 del mes 1),
  **(c)** en cada hito positivo del cliente.
- **Dueño:** Comercial. **Fuente:** Onboarding §1.2/§7, Sales §7.3.
- **Pasos:**
  1. Pedir el referido **con resultado en mano** cuando sea posible (más efectivo).
  2. Máximo **un pedido por momento**; no repetir en el mismo contacto.
  3. Registrar el referido como lead nuevo → SOP-01.
- **DoD:** pedido hecho en el momento correcto, referido registrado en CRM si lo hay.

### SOP-14 · Gestión de accesos y continuidad (bus factor) `P0`

- **Disparador:** cualquier acceso nuevo (propio o de cliente) + revisión trimestral.
- **Dueño:** RevOps. **Fuente:** Onboarding §3.2. **Resuelve Audit §3.5 (riesgo crítico
  de continuidad).**
- **Pasos:**
  1. **Ningún secreto en texto plano jamás.** Todo acceso va a la **bóveda** (gestor
     de contraseñas), nunca a Notion/Drive/mail.
  2. Registrar por cada acceso: qué es, de qué cuenta, quién lo cargó, cuándo, nivel
     de privilegio (mínimo necesario para la fase).
  3. Mantener un **inventario de accesos críticos del propio ARVIOR** (dominio,
     hosting, pasarela, repos, proveedores de IA/WhatsApp).
  4. Revisión trimestral: revocar accesos que ya no se usan; verificar respaldo.
- **DoD:** todo acceso en bóveda con metadatos; inventario propio actualizado; cero
  secretos en texto plano. Otra persona podría operar con esta información.

### SOP-15 · Cierre de mes (facturación + métricas) `P1`

- **Disparador:** fin de mes calendario.
- **Dueño:** RevOps. **Fuente:** RevOps §2.2, §3. **Atiende D6 (cobro por país).**
- **Pasos:**
  1. **Facturar** todas las cuentas en Operate (recurrente desde go-live, D1), por el
     rail del país (Chile/CLP primero, D6). Emitir boleta/factura según corresponda.
  2. Conciliar cobros; marcar atrasos (entra al health score, SOP-11).
  3. Consolidar **MRR neto** = nuevo + expansión − contracción − churn.
  4. Calcular **churn lógico y NRR** del mes.
  5. Actualizar el **dashboard del fundador** (no tiene datos propios; lee del CRM).
  6. Registrar razones de churn del mes (taxonomía RevOps §5.1).
- **DoD:** todas facturadas, MRR/churn/NRR calculados, dashboard actualizado y
  trazable al CRM, razones de churn registradas.

---

## 6. Mapa de cobertura (cada estación del flujo tiene su SOP)

> Verificación de que no queda ninguna estación del flujo (Operating System §2) sin
> procedimiento.

| Estación del flujo | SOPs que la cubren | ¿Cubierta? |
|---|---|:---:|
| Lead | SOP-01 | ✅ |
| Venta | SOP-02, 03, 04, 05 | ✅ |
| Onboarding | SOP-06, 07 | ✅ |
| Implementación | SOP-08 | ✅ |
| Operate | SOP-09, 10 | ✅ |
| Retención | SOP-11 | ✅ |
| Expansión | SOP-12 | ✅ |
| Transversal (referidos, accesos, cierre de mes) | SOP-13, 14, 15 | ✅ |

> **Cobertura completa.** Ninguna estación opera sin un SOP que la describa. El orden
> de escritura (P0 → P1 → P2) prioriza el esqueleto mínimo operable.

---

## 7. Resumen ejecutable

1. **Un SOP por tarea repetible**, con disparador, dueño, pasos y DoD objetivo.
2. **9 SOPs P0** son el esqueleto mínimo: se escriben **antes del segundo cliente**.
3. Los SOPs **incorporan las 6 decisiones binarias** del fundador (Audit §7):
   cobro de Operate (SOP-05), término (SOP-05), runtime (SOP-08), WhatsApp (SOP-10),
   CAC de Entrada (SOP-01), cobro por país (SOP-15).
4. **Cada SOP cita su fuente de estrategia** — la estrategia manda, el SOP ejecuta.
5. **Cobertura completa del flujo** — ninguna estación queda sin procedimiento (§6).
6. **Viven en Notion**, espejando este índice canónico de GitHub.

---

## 8. Documentos relacionados

- [`ARVIOR_OPERATING_SYSTEM.md`](ARVIOR_OPERATING_SYSTEM.md) — el flujo y las estaciones que estos SOPs ejecutan.
- [`ARVIOR_EXECUTION_AUDIT.md`](ARVIOR_EXECUTION_AUDIT.md) — las brechas y decisiones que los SOPs resuelven.
- [`ARVIOR_FOUNDER_DASHBOARD.md`](ARVIOR_FOUNDER_DASHBOARD.md) — lo que los SOPs alimentan con datos.
- [`ARVIOR_SALES_SYSTEM.md`](ARVIOR_SALES_SYSTEM.md) · [`ARVIOR_ONBOARDING_SYSTEM.md`](ARVIOR_ONBOARDING_SYSTEM.md) · [`ARVIOR_RETENTION_SYSTEM.md`](ARVIOR_RETENTION_SYSTEM.md) · [`ARVIOR_REVENUE_OPERATIONS.md`](ARVIOR_REVENUE_OPERATIONS.md) — la estrategia que cada SOP cita.
</content>
