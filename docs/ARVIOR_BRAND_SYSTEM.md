# ARVIOR — Brand System

> Sistema de marca: esencia, arquetipo aplicado, sistema visual, tokens, tipografía,
> color, motion, voz y sistema de componentes. Fuente de verdad para todo lo visual.
> Última revisión: 2026-06-02

Los valores documentados reflejan el código real en
[`assets/css/base.css`](../assets/css/base.css) y
[`assets/css/site.css`](../assets/css/site.css). Si cambia el código, se actualiza
este documento — y viceversa. La estrategia detrás de estas decisiones vive en
[`ARVIOR_MASTERPLAN.md`](ARVIOR_MASTERPLAN.md).

---

## 1. Esencia de marca

**ARVIOR es una empresa tecnológica que construye sistemas.** La marca debe hacer
sentir *ingeniería seria* antes de que se lea una palabra. El color, el espaciado y
el motion cargan ese mensaje.

| Atributo | Es | No es |
|---|---|---|
| Tono | Confiado, sobrio, técnico | Hype, ruidoso, "growth-hacky" |
| Estética | Dark premium, minimalismo con profundidad | Plano y aburrido / recargado |
| Sensación | Solvencia, calma, control | Agencia improvisada |
| Identidad | Empresa de producto / sistemas | Agencia que vende entregables |
| Referencias | Linear, Vercel, Stripe, Evervault, EqtyLab, Layer9 | Plantillas de marketplace |

### 1.1 Arquetipo aplicado al diseño

El arquetipo de marca (Masterplan §3) es **El Creador + El Arquitecto**. Se traduce
a decisiones visuales concretas:

| Arquetipo dice | El diseño hace |
|---|---|
| "Construyo cosas que perduran" | Estructura clara, grilla firme, nada decorativo sin función |
| "Demuestro, no grito" | Color sobrio; el acento aparece poco y con intención |
| "Precisión" | Tracking negativo en títulos, alineación estricta, espaciado consistente |
| "Profundidad técnica" | Glows, gradientes sutiles y motion que sugieren un sistema vivo, no adornos |
| "Confianza serena" | Mucho aire negativo; el contenido respira, no se amontona |

**Test de arquetipo:** si una pantalla se siente como "agencia vendiendo", está
fuera. Si se siente como "empresa de producto seria", está dentro.

---

## 2. Logotipo y nombre

- **Wordmark:** `ARVIOR`, siempre en mayúsculas.
- En el hero se usa como **kicker** con tracking amplio (`letter-spacing: 0.5em`)
  sobre el título principal — establece marca sin competir con el H1.
- Logo e ícono se gestionan desde el panel (`logo_image`, `favicon_image` en
  settings, migración `008_brand_settings.sql`). Sin logo cargado, el header cae al
  `site_name` en texto.
- **Clear space:** mínimo la altura de una letra del wordmark alrededor.
- **No hacer:** estirar, rotar, aplicar sombras duras, ponerlo sobre fondos claros
  de bajo contraste, ni recolorearlo fuera de la paleta.

---

## 3. Sistema visual

El sistema visual de ARVIOR es **dark premium con profundidad**: negro casi puro,
superficies sutilmente elevadas por gradiente, y un único acento violeta que
aparece como luz, no como relleno. La firma es el **eclipse** — un arco/orbe de luz
violeta-azul sobre el horizonte oscuro (ver referencias Anker, EqtyLab en `Guias/`).

### 3.1 Principios del sistema visual

1. **La oscuridad es el lienzo, la luz es el acento.** El negro domina; el color se
   gana su lugar. Profundidad por gradiente y glow, nunca por superficies saturadas.
2. **Aire = estatus.** El espacio negativo generoso comunica solvencia. Si dudas
   entre apretar o airear, aireas.
3. **Una sola fuente de luz por vista.** El eclipse del hero es el momento
   lumínico; el resto de la página baja la intensidad. No competir glows.
4. **Estructura visible.** Bordes sutiles, grillas alineadas, números de proceso:
   se debe *ver* que hay un sistema detrás.
5. **Tokens, no valores sueltos.** Todo lo visual sale de tokens `--arvior-*`. El
   sistema es reutilizable (clave para la expansión del Masterplan §8).

---

## 4. Color

### 4.1 Paleta ARVIOR (tema dark del sitio público)

Definida en `base.css` como tokens `--arvior-*`; el tema oscuro **solo** se activa
bajo `.site-body`. El admin y el login permanecen en el tema claro neutro.

| Token | Valor | Uso |
|---|---|---|
| `--arvior-bg` | `#050505` | Fondo base, casi negro |
| `--arvior-surface` | `#0B0B0F` | Superficie de cards (base del gradiente) |
| `--arvior-surface-2` | `#111118` | Superficie elevada (tope del gradiente) |
| `--arvior-text` | `#F5F5F5` | Texto principal |
| `--arvior-muted` | `#8B8B93` | Texto secundario, leads, labels |
| `--arvior-border` | `rgba(255,255,255,.10)` | Bordes estándar |
| `--arvior-border-2` | `rgba(255,255,255,.06)` | Bordes sutiles |
| `--arvior-accent` | `#8B5CF6` | Acento primario (violeta) |
| `--arvior-accent-2` | `#7C3AED` | Acento de hover / fin de gradiente |
| `--arvior-glow` | `#A855F7` | Glow violeta (eclipse, dots) |
| `--arvior-glow-2` | `#3B82F6` | Glow azul secundario (acentos fríos) |

### 4.2 Reglas de uso del color

- **El violeta es acento, no fondo.** Aparece en botones primarios, bordes en
  hover, eyebrows, glows. Nunca como gran superficie plana.
- **Profundidad por gradiente, no por color saturado.** Las cards usan
  `linear-gradient(180deg, surface-2 → surface)`; el "premium" viene del contraste
  sutil + glow radial en hover, no de colores fuertes.
- **El azul (`glow-2`) es de apoyo.** Varía la temperatura en glows de portfolio;
  no compite con el violeta.
- **Texto:** blanco puro (`#fff`) solo para títulos y énfasis; cuerpo en
  `--arvior-text`; secundario en `--arvior-muted`.

### 4.3 Gradientes firma

- **Texto degradado** (`.text-gradient`):
  `linear-gradient(100deg, #fff 0%, #c9b8ff 55%, #A855F7 100%)` — para una palabra
  clave dentro de un título ("systems", "valuable").
- **Botón primario:** `linear-gradient(180deg, #8B5CF6, #7C3AED)` + glow
  `box-shadow: 0 8px 30px -8px rgba(139,92,246,.6)`.
- **Eclipse del hero:** orbe radial violeta/azul detrás del título, con anillo
  iluminado — la pieza visual más distintiva del sitio (`.hero__glow`).

### 4.4 Estados (heredados de base.css)

Sobre dark se reasignan suaves: error `rgba(185,28,28,.12)` con texto `#fca5a5`;
success `rgba(21,128,61,.12)` con texto `#86efac`.

---

## 5. Tipografía

Dos familias, cargadas desde Google Fonts en el `<head>` (`layout.php`):

| Rol | Familia | Pesos | Uso |
|---|---|---|---|
| **Display** | `Space Grotesk` | 500, 600, 700 | H1–H3 del sitio, números de proceso/stats, kicker de marca |
| **Body / UI** | `Inter` | 400, 500, 600, 700 | Cuerpo, párrafos, botones, formularios, eyebrows |
| Mono | system mono stack | — | Código (`code`, `pre`) |

Token: `--font-display: "Space Grotesk", var(--font-family)` (solo bajo
`.site-body`). En el resto de la app, los headings usan Inter.

### 5.1 Escala tipográfica (sitio público)

| Elemento | Tamaño | Notas |
|---|---|---|
| Hero `h1` | `clamp(2.6rem, 7vw, 5rem)` | line-height 1.04, `letter-spacing -0.03em`, max 16ch |
| Section title | `clamp(1.7rem, 3.4vw, 2.6rem)` | line-height 1.1 |
| CTA title | `clamp(1.9rem, 4.4vw, 3.2rem)` | max 18ch |
| Lead / sub | `1.05–1.18rem` | line-height 1.7, color muted |
| Eyebrow | `0.72rem` | uppercase, `letter-spacing 0.18em`, prefijo de línea-degradado |
| Body base | `15px` | Inter, line-height 1.6, features `cv11 ss01 ss03` |

**Regla:** los títulos van en Space Grotesk con tracking negativo (apretado); los
labels/eyebrows en Inter con tracking positivo amplio (aireado). Ese contraste de
tracking es parte de la firma — y refleja el arquetipo: títulos precisos, labels
con calma.

---

## 6. Espaciado, radios y elevación

Tokens en `base.css`:

- **Espaciado:** `--space-xs`(0.25rem) → `--space-2xl`(4rem). Secciones del sitio
  usan padding vertical fluido `clamp(4rem, 9vw, 8rem)` (`--section--tight` lo reduce).
- **Container:** `1040px` por defecto; el sitio público lo ensancha a `1200px` para
  la estética premium.
- **Radios:** del sistema base 6–16px; el sitio público usa radios más generosos:
  cards `18px`, paneles `22px`, CTA `28px`, botones `999px` (pill).
- **Elevación:** sombras suaves `--shadow-xs/sm/md/lg`. Sobre dark, la "elevación"
  se expresa con glows de color, no con sombras grises.

---

## 7. Motion

- **Transiciones:** `--transition` 150ms, `--transition-slow` 250ms, easing
  `cubic-bezier(0.4,0,0.2,1)`. Para reveals, `--ease-out-quart`.
- **Reveal on scroll:** elementos con `.reveal` entran con `opacity 0→1` +
  `translateY(22px)→0` en 0.7s vía IntersectionObserver (JS inline en `index.php`,
  sin librerías).
- **Glow que sigue el cursor:** las `.s-card` exponen `--mx/--my` en `pointermove`
  para un resplandor radial que persigue el puntero.
- **Hover de cards:** `translateY(-4px)` + borde violeta + sombra-glow.
- **Accesibilidad:** todo respeta `prefers-reduced-motion: reduce` (animaciones
  desactivadas y reveals visibles sin transición).

**Principio de motion:** el movimiento sugiere *un sistema vivo y responsivo*, no
adorno. Sutil, corto, intencional — coherente con "demuestro, no grito".

---

## 8. Voz y tono

### 8.1 Idioma

- **Copy de cara al público: inglés.** Hero, secciones y CTAs en inglés ("Building
  systems for modern businesses", "Let's build something valuable").
- **Panel admin y datos de negocio: español.** Es la lengua del operador.
- **Comentarios de código: español.** Consistente con el equipo.

### 8.2 Principios de copy

1. **Afirmativo y concreto.** "We design and build the technology that scales with
   you" — no "podríamos ayudarte a tal vez mejorar".
2. **El sistema como héroe.** Hablamos de sistemas, infraestructura, leverage — no
   de "páginas bonitas". Nunca usamos "agencia" para describirnos.
3. **Frases cortas, una idea por línea.** Densidad baja, aire alto.
4. **Una palabra degradada por título.** El `.text-gradient` se reserva para el
   concepto clave de cada heading.
5. **CTA de bajo compromiso.** "Book a Meeting", "Start a conversation",
   "No commitment — just a clear next step."

### 8.3 Léxico

| Preferir | Evitar |
|---|---|
| systems, infrastructure, build, ship, scale, engineered | "agency", "solutions™" vagas, "synergy" |
| premium, precision, reliable, leverage, real | "cheap", "easy", "guru", "growth-hack" |
| platform, product, automation | superlativos vacíos ("the best ever") |

---

## 9. Iconografía

- **Estilo:** SVG inline, line-icons, `stroke-width 1.6`,
  `stroke-linecap/linejoin: round`, `fill: none`, `currentColor`. Sin librerías
  externas.
- Definidos como closures PHP en los componentes que los usan (ver `$icon` en
  `index.php`, `$socialIcon` en `site_header.php`).
- Contenedor de ícono de servicio: caja `44px`, radio `12px`, fondo
  `rgba(139,92,246,.12)`, borde violeta suave.

---

## 10. Sistema de componentes

El front se construye con un set acotado de componentes, todos scoped a
`.site-body` y derivados de los tokens `--arvior-*`. Esta es la biblioteca: nada se
inventa por fuera de ella sin agregarlo aquí.

### 10.1 Inventario de componentes

| Componente | Clase | Archivo | Anatomía / intención |
|---|---|---|---|
| Botón primario | `.btn` | `site.css` | Pill + gradiente violeta + glow. Acción principal. Uno por vista de decisión. |
| Botón secundario | `.btn--secondary` | `site.css` | Glass/borde sutil. Acción alterna sin robar foco. |
| Eyebrow de sección | `.section__eyebrow` | `site.css` | Label uppercase con prefijo de línea-degradado. Ancla cada sección. |
| Card de servicio | `.s-card` | `site.css` | Ícono + título + texto. Glow que sigue cursor + lift en hover. Las 4 líneas. |
| Paso de proceso | `.process__step` | `site.css` | Número acento + label + conector horizontal. Método en 4 pasos. |
| Card de portfolio | `.proj` | `site.css` | Caso con glow de color por posición. Prueba. |
| Pill de tecnología | `.tech-pill` | `site.css` | Token de stack; se ilumina en hover. Credibilidad. |
| Bloque CTA | `.cta` | `site.css` | Full-width, glow superior, radio 28px. Momento emocional de cierre. |
| Panel de contacto | `.contact__panel` | `site.css` | Contenedor del form. Conversión. |
| Badge del hero | `.hero__badge` | `site.css` | Pill "Technology · Automation · AI". Posiciona en 1 línea. |
| Eclipse del hero | `.hero__glow` | `site.css` | Orbe radial + anillo. Firma visual; única fuente de luz mayor. |

### 10.2 Jerarquía y reglas de composición

- **Un acento por momento de decisión.** Por sección de conversión, un solo `.btn`
  primario; el resto en secundario.
- **Las cards comparten ADN.** `.s-card` y `.proj` usan la misma base (gradiente de
  superficie + borde + glow en hover); cambian contenido y temperatura de glow, no
  estructura.
- **El eyebrow abre, el título afirma, el lead explica.** Toda sección sigue ese
  orden de tres niveles.
- **Los componentes son los ladrillos de la expansión.** Las páginas de servicio y
  el portal de cliente (Masterplan §8) se construyen recombinando esta biblioteca,
  no inventando estilos nuevos.

### 10.3 Arquitectura de CSS (capas, en orden de carga)

```
base.css         Tokens, reset, tipografía  ← cargar siempre primero
layout.css       Grid, container, utilidades de layout
components.css   Botones, formularios, alerts (compartidos admin + sitio)
site.css         Tema dark ARVIOR + componentes del front (scoped .site-body)
site_header.css  Header público (solo si !hide_chrome)
admin.css        Panel (NO usa tokens --arvior-*)
auth.css         Login
```

**Regla de oro:** todo lo dark vive bajo `.site-body`. Nunca tocar tokens base
globales para "arreglar" el sitio público — eso rompería el admin.

---

## 11. Checklist de marca (antes de publicar cualquier pantalla nueva)

- [ ] ¿Comunica "empresa tecnológica que construye sistemas", no "agencia"?
- [ ] ¿Usa los tokens `--arvior-*`, no hex sueltos?
- [ ] ¿Reusa la biblioteca de componentes (§10) en lugar de inventar estilos?
- [ ] ¿Títulos en Space Grotesk, labels/eyebrows en Inter con tracking amplio?
- [ ] ¿El violeta es acento, no fondo plano? ¿Una sola fuente de luz mayor por vista?
- [ ] ¿Hay aire suficiente (padding de sección fluido)?
- [ ] ¿Los elementos clave tienen `.reveal`? ¿Respeta `prefers-reduced-motion`?
- [ ] ¿El copy es afirmativo, corto, con una sola palabra degradada por título?
- [ ] ¿Funciona el scope: el cambio no afecta admin/login?
