# ARVIOR — Founder Dashboard (el tablero del fundador)

> Qué mira el fundador **cada día, cada semana, cada mes y cada trimestre** para
> operar ARVIOR con números, no con intuición. Es la instrumentación del negocio:
> una sola pantalla por ritmo, con la métrica, su umbral y la acción que dispara.
> Última revisión: 2026-06-02 · Estado: operativo / vivo
>
> Métricas y fórmulas: [`ARVIOR_REVENUE_OPERATIONS.md`](ARVIOR_REVENUE_OPERATIONS.md) ·
> Marco operativo: [`ARVIOR_OPERATING_SYSTEM.md`](ARVIOR_OPERATING_SYSTEM.md) §5 ·
> Riesgos que gobierna: [`ARVIOR_EXECUTION_AUDIT.md`](ARVIOR_EXECUTION_AUDIT.md) §4

---

## 0. El principio del tablero

> **El tablero existe para que el fundador sepa, en menos de un minuto, si el negocio
> está sano y qué se está quemando. No es un reporte para mirar: es un instrumento
> para actuar. Cada número tiene un umbral y una acción. Un número sin umbral es
> decoración.**

Cuatro reglas:

1. **El recurrente se lee antes que la adquisición.** MRR, churn y NRR primero; el
   embudo después (RevOps §2.1, Sales §9.2). El negocio es el recurrente.
2. **Rojo manda.** Si hay una cuenta en rojo o un cobro caído, eso se atiende antes
   que cualquier venta nueva (Retention §0).
3. **Una fuente de verdad.** El tablero **no tiene datos propios**: lee del CRM. Si un
   número no se puede trazar a un dato del CRM, el número está mal (RevOps §0).
4. **RevOps es dueño de las definiciones.** Toda métrica de este tablero se define en
   [`ARVIOR_REVENUE_OPERATIONS.md`](ARVIOR_REVENUE_OPERATIONS.md) §1. Aquí se
   **muestra y se acciona**, no se redefine (resuelve Audit §2.6).

---

## 1. Vista diaria (5 minutos — "¿algo se quema hoy?")

> No es para analizar el negocio: es para que nada urgente pase desapercibido. Tres
> cosas, nada más.

```
 ☀️  DIARIO — 5 min
 ─────────────────────────────────────────────
 🔥 Leads calientes sin tocar (>24h):     ___   → contactar HOY (SLA, SOP-01)
 🔴 Cuentas que pasaron a rojo:            ___   → plan de rescate <48h (SOP-11)
 💸 Cobros caídos / pagos en disputa:      ___   → gestionar (SOP-15, alimenta health)
 ─────────────────────────────────────────────
 Si las tres están en 0 → el día está limpio.
```

| Señal | Umbral de alarma | Acción inmediata |
|---|---|---|
| Lead caliente sin contactar | > 24 h (SLA, Sales §3.2) | Contactar hoy; si no se puede, reasignar |
| Cuenta nueva en rojo | ≥ 1 | Disparar plan de rescate < 48 h (SOP-11) |
| Cobro caído | ≥ 1 | Gestionar; marca el health score de la cuenta |

> Si esto vive como tres alertas automáticas (Operating System §6.3, automatización
> #6), el "diario" se reduce a abrir y confirmar 0/0/0. Ese es el objetivo.

---

## 2. Vista semanal (30 minutos — "¿dónde está el cuello?")

> El ritual del fundador, mismo día cada semana. Orden de lectura fijo (RevOps §2.1):
> **rojos → recurrente → embudo → acciones atascadas.**

```
 📅  SEMANAL — 30 min                          orden: ① rojos ② recurrente ③ embudo ④ higiene
 ════════════════════════════════════════════════════════════════════
 ① SALUD DE CARTERA
    🟢 ___   🟡 ___   🔴 ___        Rojos con rescate activo: ___/___
 ────────────────────────────────────────────────────────────────────
 ② NEGOCIO (recurrente)
    MRR actual:   $______  (▲/▼ semana)     Churn del mes en curso:  __%
    Nuevo MRR mes: $______                  NRR mes en curso:        __%
 ────────────────────────────────────────────────────────────────────
 ③ EMBUDO DE LA SEMANA
    Leads calif. → Reuniones → Propuestas → Cierres
        ___          ___          ___         ___
    Ciclo de venta promedio: ___ días   (obj. < 30–45)
    Pipeline ponderado:      $______    (Σ valor × probabilidad)
 ────────────────────────────────────────────────────────────────────
 ④ HIGIENE
    Oportunidades sin próxima acción con fecha: ___   → corregir (Sales §3.2)
```

**Cómo se lee (jerarquía de atención):**

| Orden | Bloque | Pregunta | Si está mal → |
|:---:|---|---|---|
| 1 | Salud de cartera | ¿Hay rojos sin rescate? | Rescate antes que vender (SOP-11) |
| 2 | Recurrente | ¿MRR sube y churn bajo? | Si churn sube, todo lo demás espera |
| 3 | Embudo | ¿Dónde se cae esta semana? | Trabajar esa etapa (guion/propuesta/seguimiento) |
| 4 | Higiene | ¿Hay oportunidades "en el aire"? | Asignar próxima acción con fecha |

---

## 3. Vista mensual (60 minutos — "¿el negocio recurrente está sano?")

> El cierre de mes. La vista principal es el **negocio**, no la adquisición. Sale del
> SOP-15 (cierre de mes).

### 3.1 Vista 1 — Negocio (la principal)

```
 💰  MENSUAL — Negocio                         (RevOps §3.1)
 ════════════════════════════════════════════════
 MRR actual:        $______   (▲/▼ vs mes anterior)
 ARR proyectado:    MRR × 12 = $______
 ────────────────────────────────────────────────
 + Nuevo MRR:       $______   (cuentas nuevas)
 + Expansión MRR:   $______   (upsell, SOP-12)
 − Contracción:     $______   (bajas de plan)
 − Churn MRR:       $______   (cancelaciones)
 = MRR neto del mes:$______
 ────────────────────────────────────────────────
 Churn lógico:      __%    (🎯 < 3%)
 NRR:               __%    (🎯 > 110%)
 Cuentas activas:   ___    (altas − bajas)
```

### 3.2 Vista 2 — Salud de cartera (retención)

```
 🩺  MENSUAL — Salud
 ════════════════════════════════════════════════
 Health mix:          🟢 ___   🟡 ___   🔴 ___
 Rojos atendidos <48h: ___/___
 ROI demostrado:       ___/___ cuentas
 % subieron de plan (6m): __%
 Cuentas llegando a fin del mínimo (mes 5–6): ___
```

### 3.3 Vista 3 — Mix de cartera (resuelve Audit §2.2)

> El ARR no se proyecta con un MRR promedio plano (Audit §2.2). Se lee el **mix real**
> de escalones para no engañarse con proyecciones optimistas.

```
 📊  MENSUAL — Mix de cartera
 ════════════════════════════════════════════════
 Escalón          Cuentas   MRR/cuenta   MRR total
 Entrada/Core       ___      $95–170      $______
 Profesional/Growth ___      $280–500     $______
 Premium/Intellig.  ___      $680–1.300   $______
 ────────────────────────────────────────────────
 MRR total: $______    MRR promedio real: $______
```

> Si la cartera se concentra en Entrada/Core, el ARR proyectado con "USD 500
> promedio" (Business Model §5.3) está inflado. El mix real es el dato honesto.

### 3.4 Acciones del cierre mensual (de SOP-15)

- [ ] Consolidar MRR neto (nuevo + expansión − contracción − churn).
- [ ] Calcular churn lógico y NRR; compararlos con sus umbrales.
- [ ] Revisar health score de **todas** las cuentas (no sólo rojas).
- [ ] Registrar razones de churn del mes (taxonomía RevOps §5.1).
- [ ] Marcar gatillos de expansión activos (Sales §8.2 → SOP-12).
- [ ] Verificar facturación completa por país (D6, SOP-15).

---

## 4. Vista trimestral (90 minutos — "¿la empresa es sana estructuralmente?")

> Tendencia y unit economics. Aquí se valida que el modelo del Business Model se
> cumple en la realidad, no en la planilla.

```
 🧭  TRIMESTRAL — Unit economics y tendencia    (RevOps §1.3, §6)
 ═══════════════════════════════════════════════════════════════
 ARR:               $______    (tendencia 3 trim.)
 LTV:               $______
 CAC (global):      $______    (🎯 < USD 1.500)
 LTV : CAC:         ___:1       (🎯 > 4:1)
 Payback de CAC:    ___ meses   (🎯 < 3, o inmediato vía Build)
 Vida media:        ___ meses   (🎯 > 30 = churn < 3%)
 ───────────────────────────────────────────────────────────────
 CAC y payback POR ESCALÓN  (resuelve Audit §1.5)
   Entrada:      CAC $___  · payback ___ m   (🎯 payback < 6 m)
   Profesional:  CAC $___  · payback ___ m   (🎯 ~inmediato)
   Premium:      CAC $___  · payback ___ m   (🎯 ~inmediato)
 ───────────────────────────────────────────────────────────────
 Conversión global Lead→Ganado: __%
 Razones de churn del trimestre (top 3): ___________
 Razones de pérdida pre-venta (top 3):   ___________
```

> **El desglose de CAC/payback por escalón es obligatorio** (Audit §1.5): la tesis
> "el Build cubre el CAC" es falsa para Entrada. El trimestre confirma si Entrada se
> paga en < 6 meses o si hay que limitar su CAC (D5) o subir su piso.

---

## 5. Vista anual (estrategia — "¿vamos hacia empresa o seguimos como agencia?")

> Una vez al año, contra los hitos del Business Model §7 y RevOps §6. No es un número:
> es una pregunta.

| Eje | Métrica de avance | Pregunta del año |
|---|---|---|
| **Negocio** | ARR vs hito (Validación 60–180K / Tracción 0.5–2M) | ¿Crecemos al ritmo del modelo? |
| **Calidad del recurrente** | Churn anual, NRR anual | ¿El cliente se queda y crece? |
| **Composición del ingreso** | % ingreso recurrente vs % servicio bespoke | ¿Sube el recurrente año a año? (Business Model §7) |
| **Dependencia del fundador** | % de cuentas que el fundador opera personalmente | ¿ARVIOR crece sin el fundador en cada cuenta? |
| **Plataforma** | % de cada build hecho sobre Core (no desde cero) | ¿Baja el costo marginal por cuenta? |

> **El hilo conductor (Business Model §7):** cada año, más % del ingreso es recurrente
> y de producto, menos es servicio bespoke. Si esa proporción no se mueve, ARVIOR se
> estanca como agencia, sin importar cuánto facture.

---

## 6. Las 12 métricas mínimas (la tarjeta de bolsillo)

> Si sólo se pudieran mirar 12 (Sales §9.1, RevOps §3.4 — **fuente única: RevOps**),
> son estas. Cada una con su umbral.

| # | Métrica | Bloque | Umbral / objetivo |
|---|---|---|---|
| 1 | Leads calificados | Embudo | Crecer mes a mes |
| 2 | Reuniones realizadas | Embudo | — (conversión) |
| 3 | Propuestas presentadas | Embudo | — (conversión) |
| 4 | Cierres (cuentas ganadas) | Embudo | Crecer |
| 5 | Conversión por etapa | Embudo | Encontrar el cuello |
| 6 | Ciclo de venta | Embudo | < 30–45 días |
| 7 | **MRR** (y nuevo MRR) | **Negocio** | Crecer mes a mes |
| 8 | **Churn lógico** | **Negocio** | **< 3% mensual** |
| 9 | **NRR** | **Negocio** | **> 110%** |
| 10 | **LTV** | Unidad | > 4× CAC |
| 11 | **CAC** | Unidad | < USD 1.500 (por escalón) |
| 12 | **LTV : CAC** | Unidad | **> 4:1** |

> Las cuatro en negrita (MRR, churn, NRR, LTV:CAC) son las que deciden si ARVIOR es
> una empresa de tecnología o una agencia con buen diseño (Business Model §9).

---

## 7. Las dos preguntas que mandan en la etapa actual (Validación)

> En la etapa de arranque (10–25 cuentas), todo el tablero existe para responder dos
> preguntas (RevOps §6). El resto es secundario hasta confirmarlas.

```
 ┌──────────────────────────────────────────────────────┐
 │  ①  ¿El churn se mantiene < 3% mensual?               │
 │      → si no, el LTV colapsa y el modelo no cierra.   │
 │                                                       │
 │  ②  ¿El Build cubre el CAC (por escalón)?             │
 │      → si no en Entrada, payback < 6 m o limitar CAC. │
 └──────────────────────────────────────────────────────┘
```

Si ambas respuestas son sí de forma sostenida, ARVIOR pasó de freelance con buenos
documentos a empresa con MRR. Esa es la línea que cruza el negocio.

---

## 8. Cómo se construye este tablero (de aquí a real)

> El tablero hoy es esta especificación; aún no es una pantalla. Su construcción es
> parte de los 90 días (Operating System §7.1).

| Paso | Qué se hace | Cuándo |
|---|---|---|
| 1 | CRM de 5 etapas operativo (los datos existen) | Sem 3–4 (OS §7.1) |
| 2 | Definir cada métrica como consulta sobre el CRM (RevOps §1) | Sem 5–6 |
| 3 | Vista semanal y mensual antes que la diaria/trimestral | Sem 7–9 |
| 4 | Alertas automáticas de la vista diaria (rojos, cobros, leads) | Al madurar Core (OS §6.3) |

> **Regla:** el tablero se lee aunque sea a mano al inicio (planilla sobre datos del
> CRM). No se espera a automatizarlo para empezar a gobernar con números. Primero el
> ritual, después la automatización.

---

## 9. Resumen ejecutable

1. **Cuatro ritmos:** diario (5 min, ¿algo se quema?), semanal (30 min, ¿dónde el
   cuello?), mensual (60 min, ¿recurrente sano?), trimestral (90 min, ¿sano
   estructuralmente?).
2. **Orden de lectura fijo:** rojos → recurrente → embudo → higiene.
3. **Mix de cartera real**, no MRR promedio plano (resuelve la proyección optimista).
4. **CAC y payback por escalón** — la tesis "Build cubre CAC" se verifica, no se
   asume.
5. **12 métricas mínimas**, cada una con umbral; cuatro deciden el carácter de la
   empresa.
6. **Dos preguntas mandan en Validación:** ¿churn < 3%? ¿Build cubre CAC?
7. **Una fuente de verdad (CRM); RevOps define, el tablero acciona.**

---

## 10. Documentos relacionados

- [`ARVIOR_REVENUE_OPERATIONS.md`](ARVIOR_REVENUE_OPERATIONS.md) — definiciones y fórmulas de cada métrica (fuente única).
- [`ARVIOR_OPERATING_SYSTEM.md`](ARVIOR_OPERATING_SYSTEM.md) — la cadencia y los cuellos que el tablero vigila.
- [`ARVIOR_EXECUTION_AUDIT.md`](ARVIOR_EXECUTION_AUDIT.md) — los riesgos financieros que las vistas gobiernan (§4, §1.5, §2.2).
- [`ARVIOR_SOPS_MAP.md`](ARVIOR_SOPS_MAP.md) — los procedimientos que alimentan el tablero con datos (SOP-09, 11, 15).
- [`ARVIOR_SALES_SYSTEM.md`](ARVIOR_SALES_SYSTEM.md) · [`ARVIOR_RETENTION_SYSTEM.md`](ARVIOR_RETENTION_SYSTEM.md) — el embudo y la salud que se miden.
</content>
