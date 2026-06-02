# ARVIOR — Homepage Architecture

> Anatomía del home: estructura, intención de cada sección, copy actual, lógica de
> conversión y referencias al código.
> Última revisión: 2026-06-02

El home vive en [`index.php`](../index.php). El layout (`<head>`, header, footer)
lo provee [`lib/layout.php`](../lib/layout.php). Los estilos del front están en
[`assets/css/site.css`](../assets/css/site.css).

---

## 1. Objetivo de la página

Una sola landing one-page con un objetivo: **convertir visitas en leads
cualificados** a través del formulario `#contact` (o WhatsApp / "Book a Meeting").

El recorrido narrativo está diseñado como un embudo descendente:

```
Hero (promesa) → What we build (capacidades) → Process (cómo trabajamos)
→ Selected work (prueba) → Technologies (credibilidad técnica)
→ CTA + Contact (acción)
```

Cada sección responde una pregunta del visitante en el orden en que la haría.

---

## 2. Capa técnica del documento

Antes del HTML del home, `index.php` resuelve dos responsabilidades:

1. **Router mínimo (CMS).** Si el path no es `/` y coincide con un slug
   publicado en `pages`, renderiza esa página vía `layoutStart/layoutEnd` y hace
   `exit`. Permite páginas adicionales sin tocar el código.
2. **Manejo de leads (POST).** Si llega `action=submit_lead`:
   - Anti-spam: honeypot (`website`) + timing (`form_started`, < 2s = bot) →
     *fake success* silencioso.
   - `csrfCheck()`, validación de nombre + email.
   - Dedupe: mismo email en los últimos 5 min → fake success.
   - `INSERT` en `leads`, luego `notifyLeadCreated()` + `sendLeadAutoReply()`
     (envueltos en try/catch para no romper el flujo).
   - Redirige a `/gracias`.

Luego `layoutStart([...])` con la meta description global de ARVIOR, y se define
el closure `$icon()` con los SVG inline de las cuatro líneas de servicio.

---

## 3. Secciones del home (en orden)

### 3.1 Hero — `.hero`

| | |
|---|---|
| **Pregunta que responde** | "¿Qué es esto y vale mi atención?" |
| **Intención** | Promesa grande + estética premium inmediata |
| **Elementos** | Eclipse glow de fondo, badge "Technology · Automation · AI", kicker `ARVIOR`, H1 con palabra degradada, subtítulo, dos CTAs |

Copy actual:
- Badge: *Technology · Automation · AI*
- H1: **Building [systems] for modern businesses.** ("systems" en `.text-gradient`)
- Sub: *Technology, automation, software and AI solutions designed to help
  businesses grow — engineered with the precision of a venture studio.*
- CTAs: **Explore Solutions** (primario → `#solutions`) · **Book a Meeting**
  (secundario → `#contact`)

Notas de diseño: el `.hero__glow` (orbe + anillo de eclipse violeta/azul) es la
firma visual. El hero está centrado, con `isolation: isolate` para el z-index del
glow. Es la sección con mayor peso vertical (`clamp(5rem,12vw,9rem)` superior).

---

### 3.2 What we build — `.section#solutions`

| | |
|---|---|
| **Pregunta** | "¿Qué hacen exactamente?" |
| **Intención** | Traducir la promesa en 4 capacidades concretas |
| **Layout** | `.card-grid` de 4 columnas (`.s-card`) |

Las cuatro cards = las cuatro líneas de servicio del Masterplan, cada una con su
ícono (`web`, `sys`, `auto`, `ai`):

1. **Websites** — sitios premium que convierten y representan la marca.
2. **Systems** — plataformas internas, dashboards y software a medida.
3. **Automation** — workflows e integraciones que eliminan trabajo repetitivo.
4. **AI** — asistentes, agentes y features inteligentes integrados.

Eyebrow: *What we build* · Título: **Digital systems, end to end.**

Interacción: glow radial que sigue el cursor (`--mx/--my`) + lift en hover.

> **Gancho de Fase 1:** cada card debería linkear a su página de servicio dedicada
> cuando existan (ver Masterplan §5).

---

### 3.3 Process — `.section.section--tight`

| | |
|---|---|
| **Pregunta** | "¿Cómo es trabajar con ustedes?" |
| **Intención** | Reducir el riesgo percibido mostrando un método claro |
| **Layout** | `.process` de 4 pasos numerados |

Eyebrow: *Our process* · Título: **A clear path from idea to scale.**

Pasos: **01 Strategy · 02 Design · 03 Build · 04 Scale** (ver tabla en Masterplan
§3). Cada paso es un `.process__step` con número en color acento y un conector
horizontal sutil entre pasos (oculto en mobile).

`section--tight` reduce el padding: es una sección de transición, no un momento de
peso.

---

### 3.4 Selected work — `.section`

| | |
|---|---|
| **Pregunta** | "¿Han hecho esto de verdad?" |
| **Intención** | Prueba (hoy placeholder) |
| **Layout** | `.portfolio` de 3 columnas (`.proj`) |

Eyebrow: *Selected work* · Título: **Systems we've shipped.**

Tres proyectos, uno por categoría (refuerza las líneas de servicio):
- **Platform** — Operations Hub (unifica ventas, inventario, reporting).
- **Automation** — Lead Engine (captura, califica y rutea leads en tiempo real).
- **AI** — Support Agent (asistente que resuelve requests integrado a las tools).

Cada `.proj` tiene un glow radial de color distinto por posición (azul/violeta).

> ⚠️ **Contenido placeholder.** Reemplazar por casos de estudio reales con
> métricas es prioridad de Fase 1. Es la sección de menor credibilidad hoy.

---

### 3.5 Technologies — `.section.section--tight`

| | |
|---|---|
| **Pregunta** | "¿Con qué construyen? ¿Son serios técnicamente?" |
| **Intención** | Credibilidad técnica de bajo costo cognitivo |
| **Layout** | `.tech-row` de `.tech-pill` centradas |

Eyebrow centrado: *Technologies* · Título: **Built on a modern stack.**

Stack mostrado (array PHP, fácil de editar):
`PHP · MySQL · JavaScript · Python · Node · React · OpenAI · REST APIs ·
Automation · Cloud · Docker · Linux`.

Las pills se iluminan en hover (borde violeta). Sección ligera, de respiro antes
del cierre.

---

### 3.6 CTA + Contact — `.section#contact`

| | |
|---|---|
| **Pregunta** | "Ok, ¿cómo empiezo?" |
| **Intención** | Conversión |
| **Layout** | Bloque `.cta` (full-width, glow) + grid `.contact` (texto + panel con form) |

Dos momentos:

1. **CTA emocional** (`.cta`): **Let's build something [valuable].** ("valuable"
   degradado) + sub *"Tell us where you want to go. We'll design the systems to
   get you there."* Caja con glow radial superior y borde redondeado 28px.

2. **Bloque de contacto** (`.contact`, 2 columnas):
   - Izquierda: eyebrow *Get in touch*, título **Start a conversation.**, lead de
     bajo compromiso (*"No commitment — just a clear next step."*).
   - Derecha: `.contact__panel` con el formulario
     ([`components/lead_form.php`](../components/lead_form.php)) y, si hubo error
     de validación, una `.alert--error`.

`#contact` tiene `scroll-margin-top: 90px` para compensar el header sticky cuando
se llega por ancla.

---

## 4. Chrome global (header / footer / WhatsApp)

Provistos por `layout.php` salvo que la página use `hide_chrome` (landings):

- **Header** ([`site_header.php`](../components/site_header.php)): topbar de
  contacto (teléfono, email, horarios, redes) + barra principal con logo, menú de
  páginas publicadas y CTA de WhatsApp; drawer hamburguesa en mobile.
- **Footer** ([`site_footer.php`](../components/site_footer.php)).
- **WhatsApp flotante** ([`whatsapp_float.php`](../components/whatsapp_float.php)),
  activable por setting `whatsapp_float`.

---

## 5. Interactividad (JS inline, sin librerías)

Todo el JS del home está al final de `index.php` en una IIFE:

1. **Reveal on scroll** — `IntersectionObserver` añade `.is-in` a los `.reveal`
   (threshold 0.12, `rootMargin -8%`). Fallback: si no hay IO, se muestran todos.
2. **Cursor glow en cards** — `pointermove` setea `--mx/--my` en cada `.s-card`.

Cero dependencias, cero build. Coherente con la filosofía del Masterplan §4.

---

## 6. SEO y metadatos del home

Inyectados por `layoutStart()`:

- `<title>`: `site_name` (sin prefijo, porque el home pasa `title: ''`).
- `description`: *"ARVIOR — Technology, automation, software and AI solutions
  designed to help modern businesses grow."*
- Open Graph + Twitter Card completos (con `og:image` si hay `seo_default_image`).
- `<link rel="canonical">` absoluto.
- **JSON-LD** de negocio automático (`businessJsonLd()`) si
  `business_seo_jsonld = 1`.
- Favicon con cache-busting por `filemtime`.
- GA y Meta Pixel opcionales por setting (`ga_id`, `pixel_id`).

---

## 7. Responsive

Breakpoints en `site.css §11`:

| Viewport | Cambios |
|---|---|
| `≤ 980px` | card-grid → 2 col, process → 2 col (sin conectores), portfolio → 2 col, stats → 2 col, contact → 1 col |
| `≤ 600px` | todas las grillas → 1 col, CTAs del hero a ancho completo en columna |

---

## 8. Mapa de conversión (qué optimizar)

| Sección | Rol en el embudo | Palanca de optimización |
|---|---|---|
| Hero | Captar atención + 1ª CTA | Claridad del H1, fuerza del eclipse |
| What we build | Calificar interés | Linkear a páginas de servicio (Fase 1) |
| Process | Reducir fricción/riesgo | Mantener corto y concreto |
| Selected work | Prueba | **Reemplazar placeholders por casos reales** |
| Technologies | Credibilidad | Mantener actualizado el stack |
| CTA + Contact | Conversión | Reducir campos del form, multiplicar vías (WhatsApp/meeting) |

**Próximos experimentos sugeridos (Fase 1+):**
- A/B del H1 del hero.
- Mover una micro-prueba social (logo/testimonio) sobre el fold.
- Medir scroll-depth hasta `#contact` y abandono del formulario.

---

## 9. Documentos relacionados

- [`ARVIOR_MASTERPLAN.md`](ARVIOR_MASTERPLAN.md) — estrategia, roadmap, arquitectura.
- [`ARVIOR_BRAND_SYSTEM.md`](ARVIOR_BRAND_SYSTEM.md) — tokens, tipografía, color, voz.
