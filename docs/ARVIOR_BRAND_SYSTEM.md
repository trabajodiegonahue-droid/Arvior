# ARVIOR — Brand System

> Sistema de marca: identidad, tokens de diseño, tipografía, color, voz y
> componentes. Fuente de verdad para todo lo visual.
> Última revisión: 2026-06-02

Los valores aquí documentados reflejan el código real en
[`assets/css/base.css`](../assets/css/base.css) y
[`assets/css/site.css`](../assets/css/site.css). Si cambia el código, se
actualiza este documento — y viceversa.

---

## 1. Esencia de marca

**ARVIOR = precisión de venture studio.**

| Atributo | Es | No es |
|---|---|---|
| Tono | Confiado, sobrio, técnico | Hype, ruidoso, "growth-hacky" |
| Estética | Dark premium, minimalismo con profundidad | Plano y aburrido / recargado |
| Sensación | Solvencia, calma, control | Startup improvisada |
| Referencias | Linear, Vercel, Stripe, Evervault | Templates genéricos de marketplace |

La marca debe hacer sentir, antes de leer una palabra de copy, que detrás hay
ingeniería seria. El color, el espaciado y el motion cargan ese mensaje.

---

## 2. Logotipo y nombre

- **Wordmark:** `ARVIOR`, siempre en mayúsculas.
- En el hero se usa como **kicker** con tracking amplio (`letter-spacing: 0.5em`)
  sobre el título principal — establece marca sin competir con el H1.
- Logo e ícono se gestionan desde el panel (`logo_image`, `favicon_image` en
  settings, migración `008_brand_settings.sql`). Si no hay logo cargado, el
  header cae al `site_name` en texto.
- **Clear space:** mínimo la altura de una letra del wordmark alrededor.
- **No hacer:** estirar, rotar, aplicar sombras duras, ponerlo sobre fondos
  claros de bajo contraste, ni recolorearlo fuera de la paleta.

---

## 3. Color

### 3.1 Paleta ARVIOR (tema dark del sitio público)

Definida en `base.css` como tokens `--arvior-*`; el tema oscuro **solo** se
activa bajo `.site-body`. El admin y el login permanecen en el tema claro neutro.

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

### 3.2 Reglas de uso del color

- **El violeta es acento, no fondo.** Aparece en botones primarios, bordes en
  hover, eyebrows, glows. Nunca como gran superficie plana.
- **Profundidad por gradiente, no por color saturado.** Las cards usan
  `linear-gradient(180deg, surface-2 → surface)`; el "premium" viene del
  contraste sutil + glow radial en hover, no de colores fuertes.
- **El azul (`glow-2`) es de apoyo.** Se usa en glows de portfolio para variar la
  temperatura, no compite con el violeta.
- **Texto:** blanco puro (`#fff`) solo para títulos y énfasis; cuerpo en
  `--arvior-text`; secundario en `--arvior-muted`.

### 3.3 Gradientes firma

- **Texto degradado** (`.text-gradient`):
  `linear-gradient(100deg, #fff 0%, #c9b8ff 55%, #A855F7 100%)` — para una
  palabra clave dentro de un título ("systems", "valuable").
- **Botón primario:** `linear-gradient(180deg, #8B5CF6, #7C3AED)` + glow
  `box-shadow: 0 8px 30px -8px rgba(139,92,246,.6)`.
- **Eclipse del hero:** orbe radial violeta/azul detrás del título, con un anillo
  iluminado — la pieza visual más distintiva del sitio (`.hero__glow`).

### 3.4 Estados (heredados de base.css)

Sobre dark se reasignan suaves: error `rgba(185,28,28,.12)` con texto `#fca5a5`;
success `rgba(21,128,61,.12)` con texto `#86efac`.

---

## 4. Tipografía

Dos familias, cargadas desde Google Fonts en el `<head>` (`layout.php`):

| Rol | Familia | Pesos | Uso |
|---|---|---|---|
| **Display** | `Space Grotesk` | 500, 600, 700 | H1–H3 del sitio, números de proceso/stats, kicker de marca |
| **Body / UI** | `Inter` | 400, 500, 600, 700 | Cuerpo, párrafos, botones, formularios, eyebrows |
| Mono | system mono stack | — | Código (`code`, `pre`) |

Token: `--font-display: "Space Grotesk", var(--font-family)` (solo bajo
`.site-body`). En el resto de la app, los headings usan Inter.

### 4.1 Escala tipográfica (sitio público)

| Elemento | Tamaño | Notas |
|---|---|---|
| Hero `h1` | `clamp(2.6rem, 7vw, 5rem)` | line-height 1.04, `letter-spacing -0.03em`, max 16ch |
| Section title | `clamp(1.7rem, 3.4vw, 2.6rem)` | line-height 1.1 |
| CTA title | `clamp(1.9rem, 4.4vw, 3.2rem)` | max 18ch |
| Lead / sub | `1.05–1.18rem` | line-height 1.7, color muted |
| Eyebrow | `0.72rem` | uppercase, `letter-spacing 0.18em`, prefijo de línea-degradado |
| Body base | `15px` | Inter, line-height 1.6, features `cv11 ss01 ss03` |

**Regla:** los títulos van en Space Grotesk con tracking negativo (apretado); los
labels/eyebrows en Inter con tracking positivo amplio (aireado). Ese contraste
de tracking es parte de la firma.

---

## 5. Espaciado, radios y elevación

Tokens en `base.css`:

- **Espaciado:** `--space-xs`(0.25rem) → `--space-2xl`(4rem). Secciones del sitio
  usan padding vertical fluido `clamp(4rem, 9vw, 8rem)` (`--section--tight` lo reduce).
- **Container:** `1040px` por defecto; el sitio público lo ensancha a `1200px`
  para la estética premium.
- **Radios:** del sistema base 6–16px, pero el sitio público usa radios más
  generosos: cards `18px`, paneles `22px`, CTA `28px`, botones `999px` (pill).
- **Elevación:** sombras suaves `--shadow-xs/sm/md/lg`. Sobre dark, la
  "elevación" se expresa con glows de color, no con sombras grises.

---

## 6. Motion

- **Transiciones:** `--transition` 150ms, `--transition-slow` 250ms, easing
  `cubic-bezier(0.4,0,0.2,1)`. Para reveals, `--ease-out-quart`.
- **Reveal on scroll:** elementos con `.reveal` entran con
  `opacity 0→1` + `translateY(22px)→0` en 0.7s vía IntersectionObserver
  (JS inline en `index.php`, sin librerías).
- **Glow que sigue el cursor:** las `.s-card` exponen `--mx/--my` en `pointermove`
  para un resplandor radial que persigue el puntero.
- **Hover de cards:** `translateY(-4px)` + borde violeta + sombra-glow.
- **Accesibilidad:** todo respeta `prefers-reduced-motion: reduce` (animaciones
  desactivadas globalmente y reveals visibles sin transición).

---

## 7. Voz y tono

### 7.1 Idioma

- **Copy de cara al público: inglés.** El hero, secciones y CTAs están en inglés
  ("Building systems for modern businesses", "Let's build something valuable").
- **Panel admin y datos de negocio: español.** Es la lengua del operador.
- **Comentarios de código: español.** Consistente con el equipo.

### 7.2 Principios de copy

1. **Afirmativo y concreto.** "We design and build the technology that scales
   with you" — no "podríamos ayudarte a tal vez mejorar".
2. **El sistema como héroe.** Hablamos de sistemas, infraestructura, leverage —
   no de "páginas bonitas".
3. **Frases cortas, una idea por línea.** Densidad baja, aire alto.
4. **Una palabra degradada por título.** El `.text-gradient` se reserva para el
   concepto clave de cada heading.
5. **CTA de bajo compromiso.** "Book a Meeting", "Start a conversation",
   "No commitment — just a clear next step."

### 7.3 Léxico

| Preferir | Evitar |
|---|---|
| systems, infrastructure, build, ship, scale | solutions™ vagas, "synergy", buzzwords |
| premium, engineered, precision | "cheap", "easy", "guru" |
| automation, leverage, real | superlativos vacíos ("the best ever") |

---

## 8. Iconografía

- **Estilo:** SVG inline, line-icons, `stroke-width 1.6`, `stroke-linecap/linejoin:
  round`, `fill: none`, `currentColor`. Sin librerías externas.
- Definidos como closures PHP en los componentes que los usan (ver `$icon` en
  `index.php`, `$socialIcon` en `site_header.php`).
- Contenedor de ícono de servicio: caja `44px`, radio `12px`, fondo
  `rgba(139,92,246,.12)`, borde violeta suave.

---

## 9. Componentes de marca (referencia rápida CSS)

| Componente | Clase | Archivo |
|---|---|---|
| Botón primario (pill + glow) | `.btn` | `site.css` |
| Botón secundario (glass) | `.btn--secondary` | `site.css` |
| Eyebrow de sección | `.section__eyebrow` | `site.css` |
| Card de servicio | `.s-card` | `site.css` |
| Paso de proceso | `.process__step` | `site.css` |
| Card de portfolio | `.proj` | `site.css` |
| Pill de tecnología | `.tech-pill` | `site.css` |
| Bloque CTA | `.cta` | `site.css` |
| Panel de contacto | `.contact__panel` | `site.css` |
| Badge del hero | `.hero__badge` | `site.css` |

### 9.1 Arquitectura de CSS (capas, en orden de carga)

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

## 10. Checklist de marca (antes de publicar cualquier pantalla nueva)

- [ ] ¿Usa los tokens `--arvior-*`, no hex sueltos?
- [ ] ¿Títulos en Space Grotesk, labels/eyebrows en Inter con tracking amplio?
- [ ] ¿El violeta es acento, no fondo plano?
- [ ] ¿Hay aire suficiente (padding de sección fluido)?
- [ ] ¿Los elementos clave tienen `.reveal`?
- [ ] ¿Respeta `prefers-reduced-motion`?
- [ ] ¿El copy es afirmativo, corto, con una sola palabra degradada por título?
- [ ] ¿Funciona el scope: el cambio no afecta admin/login?
