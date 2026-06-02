# ARVIOR — Day One (lo mínimo que debe existir para vender mañana)

> El recorte brutal: de los 14 documentos de estrategia y los 15 SOPs, **qué hay que
> tener listo para poder cerrar la primera venta el Día 1.** No es el plan de los 90
> días ([Operating System §7.1](ARVIOR_OPERATING_SYSTEM.md)) — es el subconjunto del
> 20% que genera el 80% del resultado. Si no está en este documento, **no es del Día 1.**
> Última revisión: 2026-06-02 · Estado: ejecución / vivo
>
> El plan completo de arranque: [`ARVIOR_OPERATING_SYSTEM.md`](ARVIOR_OPERATING_SYSTEM.md) §7.1 ·
> A quién se le vende primero: [`ARVIOR_FIRST_10_CLIENTS.md`](ARVIOR_FIRST_10_CLIENTS.md)

---

## 0. El principio del Día 1: vender antes que perfeccionar

> **No se necesita la empresa terminada para cerrar la primera venta. Se necesita: una
> oferta clara, un lugar donde registrar la venta, un contrato que firmar, una forma de
> cobrar, y un sistema que entregue lo prometido. Todo lo demás —los 15 SOPs, las
> automatizaciones avanzadas, el caso #0 maduro— se construye operando con clientes
> reales, no antes de tener el primero.**

El error que mata el arranque es invertirlo: pasar tres meses "montando la empresa" sin
una sola venta. La regla del Día 1 es la contraria:

1. **Lo mínimo para cobrar, listo. Lo demás, mientras operas.** Un SOP se escribe la
   segunda vez que se ejecuta ([SOPs Map §0](ARVIOR_SOPS_MAP.md)), no antes del primer
   cliente.
2. **Lo que toca al cliente, impecable. Lo interno, suficiente.** La propuesta y el
   contrato se ven profesionales; el CRM puede ser simple.
3. **Las decisiones binarias primero.** Sin las 6 decisiones del fundador (Audit §7)
   escritas, no se puede cotizar ni cobrar (§1).

---

## 1. Bloqueante absoluto: las 6 decisiones binarias (Sem 0)

> Sin estas decisiones escritas, **no se puede cotizar ni cobrar.** Son de
> [`ARVIOR_EXECUTION_AUDIT.md`](ARVIOR_EXECUTION_AUDIT.md) §7. Decidirlas toma una tarde;
> no decidirlas bloquea todo. **Esto es lo primero, antes que cualquier herramienta.**

| Decisión | Qué define | Default sugerido (si hay que decidir ya) |
|:---:|---|---|
| **D1** | ¿Desde cuándo se cobra Operate? | Desde **go-live / primer lead**, no desde la firma |
| **D2** | ¿Término del contrato? | **Mes a mes con aviso de 30 días**, escrito explícito |
| **D3** | ¿Dónde corren las automatizaciones? | **Cron cPanel + cola MySQL + worker** para arrancar |
| **D4** | ¿WhatsApp? | **Humano asistido** al inicio; API al escalar |
| **D5** | ¿El Build de Entrada cubre el CAC? | Precio de Entrada cubre el costo de adquisición; no se regala |
| **D6** | ¿Cómo se cobra? | **Chile / CLP primero** (pasarela + boleta); LatAm después |

> **DoD:** las 6 decisiones escritas y datadas en la Wiki de Notion ([Notion Architecture §6](ARVIOR_NOTION_ARCHITECTURE.md)).
> Sin esto, el resto del Día 1 es arena.

---

## 2. El recorte: qué SÍ y qué NO es del Día 1

> De todo el sistema, esto es lo que se prioriza. La columna derecha es lo que **se
> resiste la tentación** de hacer antes de vender.

| Sí es del Día 1 (el 20%) | NO es del Día 1 (espera) |
|---|---|
| Las 6 decisiones binarias escritas (§1) | Los 15 SOPs escritos (solo los 9 P0, y como checklist) |
| Pipeline de 5 etapas en un CRM (aunque sea externo) | ARVIOR Core completo / caso #0 maduro |
| Plantilla de propuesta lista para usar | Automatizaciones avanzadas (scoring, IA) |
| Contrato (Build + Operate) listo para firmar | Reporte de ROI bonito y automatizado |
| Forma de cobrar funcionando (D6) | Integraciones a medida |
| Respuesta < 5 min al lead funcionando | Health score automatizado con alertas |
| Carpeta Drive por cliente + bóveda de accesos | Organigrama, segundas contrataciones |
| Lista de los primeros prospectos ([First 10](ARVIOR_FIRST_10_CLIENTS.md)) | Optimización de la home, A/B testing |

> **Regla del recorte:** todo lo de la izquierda toca la capacidad de **cerrar y
> entregar la primera venta**. Todo lo de la derecha mejora un negocio que ya vende.
> Primero vende, luego mejora.

---

## 3. El checklist del Día 1 (lo que debe existir, agrupado)

> Cinco bloques. Cada uno con su DoD. Si los cinco están verdes, **ARVIOR puede cerrar
> y cobrar una venta.** El tiempo total estimado es ~2–3 días de trabajo enfocado.

### Bloque A — Decidir (medio día) `BLOQUEANTE`

- [ ] Las **6 decisiones binarias** (§1) escritas y datadas en la Wiki.
- **DoD:** un tercero podría cotizar y cobrar siguiendo estas reglas sin preguntar.

### Bloque B — Dónde vive la venta (medio día)

- [ ] **CRM** con las 5 etapas + estados de salida montado ([CRM Setup §8](ARVIOR_CRM_SETUP.md)).
- [ ] Captura de la web → entra a etapa 0 con autorespuesta.
- [ ] **Notion** mínimo: base de Cuentas + su plantilla, 9 SOPs P0, vista "Hoy"
      ([Notion Architecture §7](ARVIOR_NOTION_ARCHITECTURE.md)).
- [ ] **Carpeta Drive** modelo por cliente + **bóveda de accesos** creada (SOP-14).
- **DoD:** una oportunidad se registra completa y una cuenta nueva se crea en un clic.

### Bloque C — Con qué se cierra (medio día) `LO QUE TOCA AL CLIENTE`

- [ ] **Plantilla de propuesta** de 1 página lista ([Sales §5.1](ARVIOR_SALES_SYSTEM.md)):
      situación+pérdida → resultado → sistema → 3 opciones → alcance → garantías → siguiente paso.
- [ ] **Contrato** Build + Operate listo para firmar, con D1 (cobro desde go-live) y D2
      (término) escritos explícitos (SOP-05).
- [ ] **Guion de diagnóstico** y **banco de objeciones** a mano en Notion (espacio Comercial).
- **DoD:** se puede entrar a una reunión, diagnosticar, presentar propuesta y mandar el
  contrato el mismo día, sin improvisar.

### Bloque D — Con qué se cobra (unas horas)

- [ ] **Pasarela / forma de cobro** de Chile (CLP) funcionando — D6.
- [ ] **Boleta/factura** lista para emitir.
- [ ] Condiciones de pago definidas: Build (anticipo % + saldo) y Operate (desde go-live).
- **DoD:** se puede emitir y cobrar el anticipo del Build el día que el cliente firma.

### Bloque E — Con qué se entrega lo prometido (la promesa central)

- [ ] **Respuesta < 5 min al lead** funcionando (automatización #1, no negociable —
      [Audit §1.3](ARVIOR_EXECUTION_AUDIT.md)). Requiere el runtime de D3.
- [ ] El **Build de Entrada/Profesional** se puede construir sobre una base existente
      (no desde cero cada vez).
- **DoD:** lo que se promete en la garantía (respuesta < 5 min) se puede cumplir el
  primer go-live. Sin esto, no se vende lo que no se puede entregar.

---

## 4. Lo que explícitamente NO se hace el Día 1 (y por qué)

> Decir qué **no** se hace es tan importante como qué sí. Esto es lo que parece urgente
> y no lo es:

| Tentación | Por qué esperar |
|---|---|
| Escribir los 15 SOPs antes de vender | Un SOP se escribe la 2ª vez que se ejecuta; sin clientes no hay 2ª vez |
| Construir ARVIOR Core completo | Se vende con un CRM externo; Core madura operando ([CRM Setup §7](ARVIOR_CRM_SETUP.md)) |
| Automatizar el reporte de ROI | El primer reporte se hace a mano; se automatiza cuando duela (Operating System §6.3) |
| Perfeccionar la home / A/B testing | La home ya convierte lo suficiente; la primera venta sale de la red, no del SEO ([First 10](ARVIOR_FIRST_10_CLIENTS.md)) |
| Contratar / armar organigrama | No se delega lo que aún no satura al fundador (Operating System §1.3) |
| IA conversacional, scoring predictivo | Es Premium/Intelligence; los primeros clientes no lo necesitan para cerrar |

> Cada hora puesta en esta columna es una hora **no** puesta en conseguir el primer
> cliente. El Día 1 se mide en ventas, no en infraestructura.

---

## 5. La secuencia del Día 1 (orden exacto)

> Si todo lo anterior se hiciera en serie, este es el orden. Lo bloqueante primero;
> lo que toca al cliente, antes de la primera reunión.

```
 1. DECIDIR (Bloque A) ──────────► sin esto no se puede cotizar
        │
 2. COBRAR (Bloque D) ───────────► sin esto no se puede ingresar dinero
        │
 3. CERRAR (Bloque C) ───────────► propuesta + contrato listos para la 1ª reunión
        │
 4. REGISTRAR (Bloque B) ────────► CRM + Notion + Drive + bóveda
        │
 5. ENTREGAR (Bloque E) ─────────► respuesta < 5 min lista para el 1er go-live
        │
        ▼
   ► Ejecutar ARVIOR_FIRST_10_CLIENTS.md  (conseguir las reuniones)
```

> **Bloques A→D→C se pueden hacer en 1–2 días.** B y E pueden avanzar en paralelo
> mientras ya se agendan las primeras reuniones (no hay que esperar a E para
> diagnosticar y cerrar; sí para hacer go-live).

---

## 6. DoD del Día 1 (el criterio de "estamos listos para vender")

ARVIOR está listo para su primera venta cuando, sin improvisar, puede:

1. ✅ **Cotizar** según reglas escritas (las 6 decisiones, §1).
2. ✅ **Diagnosticar y presentar** una propuesta profesional en una reunión.
3. ✅ **Firmar** un contrato Build + Operate con cobro y término claros.
4. ✅ **Cobrar** el anticipo el mismo día (D6).
5. ✅ **Registrar** todo en el CRM y crear la cuenta en Notion en un clic.
6. ✅ **Entregar** la promesa central (respuesta < 5 min) en el primer go-live.

> Si los 6 son verdes, ya no estás montando una empresa: estás vendiendo. El siguiente
> documento, [`ARVIOR_FIRST_10_CLIENTS.md`](ARVIOR_FIRST_10_CLIENTS.md), es cómo
> consigues a quién venderle.

---

## 7. Documentos relacionados

- [`ARVIOR_FIRST_10_CLIENTS.md`](ARVIOR_FIRST_10_CLIENTS.md) — cómo se consiguen las primeras 10 cuentas.
- [`ARVIOR_CRM_SETUP.md`](ARVIOR_CRM_SETUP.md) — el CRM del Bloque B, paso a paso.
- [`ARVIOR_NOTION_ARCHITECTURE.md`](ARVIOR_NOTION_ARCHITECTURE.md) — el Notion del Bloque B, paso a paso.
- [`ARVIOR_OPERATING_SYSTEM.md`](ARVIOR_OPERATING_SYSTEM.md) §7.1 — el plan completo de 90 días donde encaja el Día 1.
- [`ARVIOR_EXECUTION_AUDIT.md`](ARVIOR_EXECUTION_AUDIT.md) §7 — las 6 decisiones binarias del Bloque A.
- [`ARVIOR_OFFER.md`](ARVIOR_OFFER.md) · [`ARVIOR_SALES_SYSTEM.md`](ARVIOR_SALES_SYSTEM.md) — la oferta y el guion del Bloque C.
</content>
