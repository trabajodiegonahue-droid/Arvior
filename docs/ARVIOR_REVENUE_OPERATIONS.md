# ARVIOR — Revenue Operations (el sistema de gobierno del negocio)

> El sistema que mantiene honesto y medible todo lo demás. Define **qué se mide, cómo
> se calcula y cuándo se revisa** — para que ARVIOR se opere con números, no con
> intuición. Es el panel de instrumentos del fundador.
> Última revisión: 2026-06-02 · Estado: operativo / vivo
>
> Mide: [`ARVIOR_SALES_SYSTEM.md`](ARVIOR_SALES_SYSTEM.md) ·
> [`ARVIOR_RETENTION_SYSTEM.md`](ARVIOR_RETENTION_SYSTEM.md) ·
> Unit economics base: [`ARVIOR_BUSINESS_MODEL.md`](ARVIOR_BUSINESS_MODEL.md) §6

---

## 0. Para qué existe RevOps en ARVIOR

> **RevOps es el pegamento entre vender, entregar y retener. Su trabajo es que ninguna
> cuenta, ningún lead y ningún peso de MRR se gestione "de memoria".** Una sola fuente
> de verdad, métricas definidas igual para todos, y una cadencia fija de revisión.

En la etapa de fundador-operador, RevOps **no es un equipo**: es una disciplina y un
tablero. Pero las definiciones se fijan ahora para que escalen sin reinventarse.

Tres principios:

1. **Una sola fuente de verdad.** Pipeline, cuentas y MRR viven en un solo lugar (el
   propio sistema de ARVIOR — somos nuestro caso #0).
2. **Métricas definidas, no opinables.** Cada número de §1 tiene una fórmula. "Churn"
   significa lo mismo siempre.
3. **Cadencia fija** (§2). El negocio se revisa en ritmos, no cuando hay tiempo.

---

## 1. El diccionario de métricas (definiciones y fórmulas)

> Coherente con [`ARVIOR_BUSINESS_MODEL.md`](ARVIOR_BUSINESS_MODEL.md) §6 y los
> objetivos de [`ARVIOR_REVENUE_SYSTEM.md`](ARVIOR_REVENUE_SYSTEM.md) §6.

### 1.1 Métricas de adquisición (el embudo comercial)

| Métrica | Fórmula / definición | Objetivo arranque |
|---|---|---|
| **Leads calificados** | Leads nuevos que cumplen ≥3/4 criterios de admisión | Crecer mes a mes |
| **Reuniones realizadas** | Reuniones de diagnóstico hechas con decisor | — |
| **Propuestas presentadas** | Propuestas mostradas en vivo | — |
| **Cierres (cuentas ganadas)** | Contratos firmados + primer pago | — |
| **Conversión por etapa** | Oportunidades que avanzan / total en la etapa | Encontrar el cuello |
| **Conversión global** | Cuentas ganadas / leads calificados | Calibrar y mejorar |
| **Ciclo de venta** | Días promedio Lead calificado → Cerrado-Ganado | < 30–45 días |
| **Tasa de descarte en admisión** | Leads descartados / leads totales | > 0 (señal de foco) |

### 1.2 Métricas de negocio (el recurrente — las que mandan)

| Métrica | Fórmula | Objetivo arranque |
|---|---|---|
| **MRR** | Σ (Operate + Grow recurrente) de todas las cuentas activas | Crecer mes a mes |
| **Nuevo MRR** | MRR de cuentas nuevas del mes | — |
| **MRR de expansión** | Aumento de MRR de cuentas existentes (upsell) | Positivo (NRR) |
| **MRR de contracción** | Bajas de plan sin cancelar | Minimizar |
| **MRR perdido (churned)** | MRR de cuentas que cancelaron | Minimizar |
| **ARR** | MRR × 12 | Métrica de empresa (reemplaza "leads/semana") |
| **Churn lógico** | Cuentas que cancelan / cuentas activas al inicio del mes | **< 3% mensual** |
| **Churn de ingresos** | MRR perdido / MRR al inicio del mes | < 3% |
| **NRR** | (MRR inicio + expansión − contracción − churn) / MRR inicio | **> 110%** |
| **Vida media** | 1 / churn mensual | > 30 meses |

### 1.3 Métricas de unidad (salud estructural)

| Métrica | Fórmula | Objetivo arranque |
|---|---|---|
| **CAC** | (Costo de ventas + marketing del período) / cuentas ganadas. **Se mide por escalón** | **Entrada < USD 800** (solo referido/inbound) · **Profesional/Premium < USD 1.500**. En todos, el margen del Build cubre el CAC (neto ≤ 0) |
| **Margen del Build** | (Precio Build − costo de entrega) / precio Build | ~70% |
| **Margen del recurrente** | (MRR − costo de operación) / MRR | 75–85% |
| **LTV** | (MRR × margen recurrente × vida media) + margen del Build | — |
| **LTV : CAC** | LTV / CAC | **> 4:1** |
| **Payback de CAC** | Meses de MRR para recuperar CAC | < 3 meses (o inmediato vía Build) |

> **Ejemplo de referencia** ([Business Model §6](ARVIOR_BUSINESS_MODEL.md)): Build USD
> 3.000 (margen 70%), Operate USD 500/mes (margen 80%), CAC USD 1.000, vida media 36
> meses → LTV ≈ USD 16.500, LTV:CAC ≈ 16:1, payback inmediato vía Build. **El riesgo no
> está en el LTV optimista — está en el churn y la vida media.** Por eso se vigilan primero.

---

## 2. Cadencia de gobierno (cuándo se mira qué)

El negocio se revisa en cuatro ritmos. Cada uno tiene un foco distinto: no se mira todo
todo el tiempo.

| Ritmo | Foco | Métricas clave | Duración |
|---|---|---|---|
| **Semanal** | Pulso del embudo + cuentas en riesgo | Leads, reuniones, propuestas, cierres, health scores rojos | 30 min |
| **Mensual** | Salud del negocio recurrente | MRR, nuevo/expansión/churn MRR, NRR, churn, cierre de cuentas | 60 min |
| **Trimestral** | Tendencia y unit economics | ARR, LTV:CAC, payback, vida media, conversión global, razones de churn | 90 min |
| **Anual** | Estrategia y horizontes | Avance vs. hitos del Business Model §7, decisiones de Core/expansión | — |

### 2.1 Revisión semanal (el ritual del fundador)

Cada semana, mismo día, el fundador mira el tablero (§3) **en este orden de prioridad**
([Sales System §9.2](ARVIOR_SALES_SYSTEM.md)):

1. **Cuentas en rojo/amarillo** → ¿hay rescate pendiente? (la retención manda).
2. **MRR y churn del mes en curso** → ¿vamos en dirección correcta?
3. **Embudo de la semana** (leads → reuniones → propuestas → cierres) → ¿dónde está el cuello?
4. **Acciones atascadas** → oportunidades sin próxima acción con fecha (higiene, [Sales §3.2](ARVIOR_SALES_SYSTEM.md)).

### 2.2 Revisión mensual (cierre de mes)

- Consolidar MRR del mes: nuevo + expansión − contracción − churn = MRR neto.
- Calcular churn y NRR del mes.
- Revisar health score de **todas** las cuentas (no solo las rojas).
- Marcar gatillos de expansión activos ([Sales §8.2](ARVIOR_SALES_SYSTEM.md)).
- Registrar razones de churn del mes (§5).

---

## 3. El dashboard comercial (lo que ve el fundador)

Tres vistas. La regla: **el negocio (recurrente) se lee antes que la adquisición.**

### 3.1 Vista 1 — Negocio (la principal)

```
 MRR actual:        $______   (▲/▼ vs mes anterior)
 ARR proyectado:    MRR × 12
 ──────────────────────────────────────────────
 + Nuevo MRR:       $______   (cuentas nuevas)
 + Expansión MRR:   $______   (upsell)
 − Contracción:     $______   (bajas de plan)
 − Churn MRR:       $______   (cancelaciones)
 = MRR neto del mes:$______
 ──────────────────────────────────────────────
 Churn lógico:      __%        (objetivo < 3%)
 NRR:               __%        (objetivo > 110%)
 Cuentas activas:   ___        (altas − bajas)
```

### 3.2 Vista 2 — Adquisición (el embudo)

```
 Leads calificados   →  Reuniones  →  Propuestas  →  Cierres
      ___                  ___           ___           ___
   (conversión %)     (conversión %)  (conversión %)
 ──────────────────────────────────────────────────────────
 Ciclo de venta promedio:   ___ días   (objetivo < 30–45)
 Pipeline ponderado:        $______    (Σ valor × probabilidad)
 Tasa de descarte:          __%        (disciplina de foco)
```

### 3.3 Vista 3 — Salud de cartera (retención)

```
 Cuentas por health score:   🟢 ___   🟡 ___   🔴 ___
 Rojos con rescate activo:    ___ / ___
 ROI demostrado este mes:     ___ / ___ cuentas
 Cuentas que subieron de plan (6m): __%
 Cuentas llegando a fin del mínimo (mes 5-6): ___
```

### 3.4 Las 12 métricas mínimas del fundador

Si solo se pudieran mirar 12 (las del [Sales System §9.1](ARVIOR_SALES_SYSTEM.md)):
Leads · Reuniones · Propuestas · Cierres · Conversión por etapa · Ciclo de venta ·
**MRR · Churn · NRR · LTV · CAC · LTV:CAC.**

---

## 4. Salud de cuentas (el puente con retención)

RevOps mantiene el **health score** de cada cuenta ([Retention §2.1](ARVIOR_RETENTION_SYSTEM.md))
como dato operativo, no impresión. Cada mes, cada cuenta queda en verde/amarillo/rojo
según uso del panel, leads capturados, ROI, pagos, relación y tickets.

| Color | Regla operativa | Responsable |
|---|---|---|
| 🟢 | Mantener cadencia + evaluar gatillo de expansión | Ops + Comercial |
| 🟡 | Intervención proactiva en la semana | Responsable de cuenta |
| 🔴 | Plan de rescate < 48 h ([Retention §2.3](ARVIOR_RETENTION_SYSTEM.md)) | Responsable de cuenta + fundador |

> El health score conecta el dato (RevOps) con la acción (Retention). Sin él, el churn
> se ve cuando ya pasó; con él, se ve venir.

---

## 5. Aprendizaje del sistema (qué hacemos con los datos)

Los datos no solo reportan: **mejoran el sistema comercial.** Cada cierre y cada pérdida
deja una lección registrada.

### 5.1 Razones de pérdida y de churn (taxonomía fija)

Para que el análisis sea agregable, toda pérdida y todo churn se clasifica con una
etiqueta de una lista cerrada:

**Pérdidas (pre-venta):** `precio` · `timing/no es prioridad` · `no calificaba` ·
`eligió competencia` · `quería solo Build` · `sin respuesta/se enfrió` · `decisor no presente`.

**Churn (post-venta):** `no vio ROI` · `cayó su tráfico` · `cambió decisor` ·
`problema de servicio` · `cerró/cambió el negocio` · `precio/presupuesto` · `quiso operarlo solo`.

### 5.2 Qué se hace con eso (loop de mejora)

| Patrón detectado | Acción de mejora |
|---|---|
| Muchas pérdidas por `precio` | Revisar anclaje de valor en diagnóstico ([Sales §4.3](ARVIOR_SALES_SYSTEM.md)), no bajar precio |
| Muchas pérdidas por `no calificaba` | Endurecer el filtro de admisión / mejorar targeting de leads |
| Churn por `no vio ROI` | Reforzar el reporte mensual ([Retention §3](ARVIOR_RETENTION_SYSTEM.md)) |
| Churn por `cayó su tráfico` | Calificar mejor el tráfico mínimo en la venta (riesgo conocido, [Offer §14](ARVIOR_OFFER.md)) |
| Cuello en una etapa del pipeline | Trabajar esa etapa específica (guion, propuesta, seguimiento) |
| Qué convierte por rubro | Alimenta playbooks y scoring propietarios (foso de datos, [Revenue System §5](ARVIOR_REVENUE_SYSTEM.md)) |

> Este loop es lo que convierte el churn bajo en **estructural, no en suerte**
> ([Business Model §5](ARVIOR_BUSINESS_MODEL.md)) — y lo que con el tiempo se vuelve
> ARVIOR Core (el 80% repetible).

---

## 6. Hitos del negocio (contra qué medimos el avance)

Coherente con [`ARVIOR_BUSINESS_MODEL.md`](ARVIOR_BUSINESS_MODEL.md) §7:

| Hito | Cuentas recurrentes | ARR aprox. | Pregunta que responde |
|---|---|---|---|
| **Validación** | 10–25 | USD 60–180K | ¿El cliente paga mensual y se queda? (churn < 3%) |
| **Tracción** | 60–150 | USD 0.5–2M | ¿Baja el costo marginal por cuenta con la escala? |
| **Escala** | — | USD 10M+ | ¿NRR > 120%, rentable o listo para acelerar? |

> En la etapa actual (Validación), las dos preguntas que todo el tablero existe para
> responder son: **(1) ¿el churn se mantiene < 3%?** y **(2) ¿el Build cubre el CAC
> por escalón?** (Entrada < USD 800 vía referido/inbound; Profesional/Premium < USD
> 1.500). Por diseño de precios la respuesta a (2) es sí; el tablero lo **vigila** para
> que no se rompa al meter ads en Entrada. Todo lo demás es secundario hasta confirmar
> esas dos.

---

## 7. Resumen ejecutable

1. **Una fuente de verdad, métricas definidas, cadencia fija.** El negocio se opera con
   números, no de memoria.
2. **Lee el recurrente antes que la adquisición:** MRR, churn, NRR primero; embudo después.
3. **Ritual semanal (30 min):** rojos → MRR/churn → embudo → acciones atascadas.
4. **Cierre mensual:** consolidar MRR neto, churn, NRR, health de toda la cartera.
5. **Clasifica toda pérdida y todo churn** con etiqueta fija → loop de mejora.
6. **En Validación, dos preguntas mandan:** ¿churn < 3%? ¿Build cubre CAC?

---

## 8. Documentos relacionados

- [`ARVIOR_SALES_SYSTEM.md`](ARVIOR_SALES_SYSTEM.md) — el pipeline y el embudo que se mide.
- [`ARVIOR_RETENTION_SYSTEM.md`](ARVIOR_RETENTION_SYSTEM.md) — el health score y el anti-churn.
- [`ARVIOR_BUSINESS_MODEL.md`](ARVIOR_BUSINESS_MODEL.md) — unit economics e hitos de negocio.
- [`ARVIOR_REVENUE_SYSTEM.md`](ARVIOR_REVENUE_SYSTEM.md) — qué medir y objetivos de arranque.
- [`ARVIOR_ONBOARDING_SYSTEM.md`](ARVIOR_ONBOARDING_SYSTEM.md) — cuándo entra una cuenta al tablero.
