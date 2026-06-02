# ARVIOR — Masterplan

> Documento maestro de estrategia, producto y arquitectura.
> Última revisión: 2026-06-02 · Estado: foundation

---

## 1. Qué es ARVIOR

ARVIOR es un **estudio de tecnología** que diseña y construye sistemas digitales
para negocios modernos: sitios web de alto rendimiento, software a medida,
automatizaciones e inteligencia artificial aplicada.

El posicionamiento es deliberado: no nos presentamos como una "agencia web" más,
sino como un **venture studio de ingeniería** — precisión, criterio de producto y
una estética premium que comunica solvencia técnica antes de la primera reunión.

> **One-liner:** *Building systems for modern businesses.*

### 1.1 Propuesta de valor

| Para quién | Problema | Lo que ARVIOR entrega |
|---|---|---|
| Negocios que crecen | Procesos manuales, herramientas desconectadas | Infraestructura digital confiable que escala |
| Founders / PYMEs ambiciosas | Web genérica que no convierte ni representa la marca | Sitios premium orientados a conversión |
| Equipos operativos | Trabajo repetitivo que consume al equipo | Automatizaciones e integraciones end-to-end |
| Productos existentes | Falta de apalancamiento / diferenciación | Features de IA reales, integradas a la operación |

### 1.2 Las cuatro líneas de servicio

1. **Websites** — sitios y landing pages de alto rendimiento, pensadas para
   convertir y representar la marca al máximo nivel.
2. **Systems** — plataformas internas, dashboards y software a medida que
   convierten procesos manuales en infraestructura confiable.
3. **Automation** — workflows e integraciones que conectan herramientas y
   eliminan trabajo repetitivo.
4. **AI** — asistentes, agentes y features inteligentes integrados a productos
   y operaciones para crear apalancamiento real.

Estas cuatro líneas son el eje narrativo de toda la comunicación: aparecen en el
hero, en la sección "What we build" y en el portfolio (un caso por línea).

---

## 2. Principios

Estos principios guían tanto el producto que vendemos como el sitio que lo vende.

1. **Premium por defecto.** Cada superficie —tipografía, espaciado, motion—
   comunica calidad. Si algo se ve barato, se reescribe.
2. **Cero dependencias innecesarias.** El sitio corre en PHP plano sobre hosting
   compartido, sin build step, sin frameworks de front. La simplicidad es una
   ventaja operativa, no una limitación.
3. **Ship rápido, optimizar después.** Estrategia → Diseño → Build → Scale. Se
   entrega valor temprano y se mejora con datos.
4. **El sistema es la entrega.** No vendemos páginas, vendemos sistemas que
   corren la operación del cliente.
5. **Confiabilidad sobre novedad.** Tecnología moderna pero probada. Nada se
   despliega si no es operable por una persona sin contexto.

---

## 3. Proceso de trabajo (cara al cliente)

El proceso es a la vez metodología interna y mensaje de marketing (sección
"Our process" del home):

| # | Fase | Qué pasa |
|---|---|---|
| 01 | **Strategy** | Mapeamos metas, restricciones y oportunidades para definir qué vale la pena construir. |
| 02 | **Design** | Diseñamos experiencia y arquitectura — limpio, premium, hecho para durar. |
| 03 | **Build** | Ingeniería del producto con tecnología moderna y confiable; entrega rápida. |
| 04 | **Scale** | Optimizamos, automatizamos y hacemos crecer el sistema con el negocio. |

---

## 4. Arquitectura técnica del sitio

### 4.1 Filosofía

El sitio público de ARVIOR es la **primera demostración de producto**: si nuestra
propia web es rápida, limpia y mantenible sin un stack pesado, eso *es* el pitch.

- **Stack:** PHP plano + MySQL (PDO). Sin frameworks, sin Node en producción,
  sin build de assets. CSS plano servido directo.
- **Hosting:** cPanel tradicional (Hostinger). Deploy por `git pull` vía Terminal
  con deploy key SSH. Ver [`DEPLOY.md`](../DEPLOY.md).
- **Migraciones:** sistema propio idempotente (`lib/migrate.php`), se ejecuta al
  visitar `/admin/`. Los `.sql` viven en `migrations/` numerados.

### 4.2 Mapa del repositorio

```
index.php              Router mínimo + homepage + manejo de leads
404.php                Página de error
robots.txt.php         robots dinámico
sitemap.xml.php        sitemap dinámico
config.example.php     Plantilla de credenciales (config.php real es git-ignored)

lib/                   Núcleo PHP
  bootstrap.php          Arranque: sesión, helpers, DB
  db.php                 Conexión PDO
  layout.php             layoutStart()/layoutEnd(): <head>, SEO, OG, header/footer
  auth.php               Login admin, sesiones, rate limiting
  csrf.php               Tokens CSRF
  helpers.php            slugify, redirect, getSetting, etc.
  business.php           Datos de negocio + JSON-LD
  media_library.php      Biblioteca de imágenes
  image_pipeline.php     Procesamiento de imágenes subidas
  mail.php               Notificaciones de leads (Resend)
  migrate.php            Runner de migraciones idempotente

components/            Vistas reutilizables (PHP)
  site_header.php        Topbar contacto + nav + drawer mobile
  site_footer.php        Footer
  lead_form.php          Formulario de contacto (honeypot + CSRF)
  whatsapp_float.php     Botón flotante de WhatsApp
  admin/                 Pantallas del panel (dashboard, leads, pages, media, ...)
  auth/                  Login

admin/                 Entry point del panel autenticado
install/               Wizard de instalación (se borra post-install)

assets/css/            CSS plano en capas (ver BRAND_SYSTEM)
migrations/            Esquema versionado en .sql
uploads/               Contenido subido (git-ignored)
Guias/                 Referencias de diseño (inspiración, no se sirve)
```

### 4.3 Capacidades del CMS

El panel `/admin/` ya soporta, mediante migraciones:

- **Leads** — captura desde el form público con anti-spam (honeypot + timing +
  dedupe por email), notas por lead, notificación por email (Resend).
- **Pages** — CMS de páginas con slug, body HTML, SEO (meta description,
  OG image), layout y opción `hide_chrome` para landings.
- **Media library** — subida y gestión de imágenes con pipeline de procesado.
- **Business info** — contacto, dirección, redes, horarios, sucursales,
  JSON-LD automático para SEO local.
- **Brand settings** — logo y favicon configurables.
- **Users** — multiusuario con perfil, último login, rate limiting de auth.
- **Mailing** — integración con Resend.

### 4.4 Decisiones de arquitectura clave

- **Deploy outbound-only.** El firewall del hosting bloquea conexiones entrantes
  desde datacenters (descartó GitHub Actions FTP/SFTP). El `git pull` saliente
  desde la Terminal del cPanel es la única vía confiable. *No revertir a Actions.*
- **`config.php` y `/uploads/` nunca viajan por git.** Sobreviven entre deploys
  porque `git pull` no borra archivos no rastreados.
- **Tema dark acotado a `.site-body`.** El front es dark premium; el admin y el
  login quedan claros. Esto evita que el rebrand del sitio toque el panel.
- **Sin cache de salida.** Cada request renderiza. Simplifica el modelo mental;
  el tráfico esperado no lo justifica todavía.

---

## 5. Roadmap

### Fase 0 — Foundation ✅ (actual)
Sitio público dark premium en línea, CMS operativo, captura de leads, deploy
estable por `git pull`, documentación de marca y arquitectura (este conjunto de
docs).

### Fase 1 — Conversión y contenido
- Páginas de servicio dedicadas (una por línea: Websites / Systems / Automation / AI).
- Casos de estudio reales reemplazando el portfolio placeholder.
- Página "About" con la narrativa de venture studio.
- Optimización de Core Web Vitals y SEO on-page.

### Fase 2 — Prueba social y captación
- Testimonios / logos de clientes.
- Sección de pricing o "engagement models".
- Blog / insights técnicos para SEO de fondo.
- Lead scoring básico y secuencias de email.

### Fase 3 — Producto y escala
- Portal de cliente (estado de proyectos).
- Demos interactivas de capacidades de IA.
- Analítica de conversión por sección (ver HOMEPAGE_ARCHITECTURE).

---

## 6. Métricas que importan

| Objetivo | Métrica primaria | Dónde se mide |
|---|---|---|
| El sitio convierte | Leads cualificados / semana | Tabla `leads`, panel admin |
| El sitio carga premium | LCP < 2.0s, CLS < 0.1 | Lighthouse / CWV |
| El mensaje conecta | Scroll-depth hasta `#contact` | Analítica (GA) |
| Deploy sin fricción | Tiempo de `git pull` a producción | < 2s (ya alcanzado) |

---

## 7. Documentos relacionados

- [`ARVIOR_BRAND_SYSTEM.md`](ARVIOR_BRAND_SYSTEM.md) — identidad visual, tokens, voz.
- [`ARVIOR_HOMEPAGE_ARCHITECTURE.md`](ARVIOR_HOMEPAGE_ARCHITECTURE.md) — anatomía del home.
- [`DEPLOY.md`](../DEPLOY.md) — flujo de despliegue al hosting.
