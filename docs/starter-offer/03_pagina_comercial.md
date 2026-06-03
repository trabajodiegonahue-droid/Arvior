# Página comercial del Starter — lista para publicar

> Activo comercial · Fase 5
> **No requiere tocar el Core.** Se publica con el sistema de Páginas ya existente
> (Admin → Páginas → Nueva). El `<h1>` lo genera el sistema a partir del título;
> por eso el cuerpo de abajo **empieza en `<h2>`** (no repetir el título).

---

## 1. Cómo publicarla (2 min)

1. Entrar a **Admin → Páginas → Nueva página**.
2. Completar:
   - **Título:** `ARVIOR Revenue System — Starter`
   - **Slug:** `starter` → la URL queda `tudominio.com/starter`
   - **Meta description:** *Capturá, ordená y medí cada lead de tu negocio. Lo construimos, lo operamos y te mostramos cuánto te deja. Sistema listo en días.*
   - **Cuerpo:** pegar el HTML del bloque §2.
   - **Publicada:** sí.
3. Guardar. Revisar en `tudominio.com/starter`.
4. Ajustar el enlace del botón (`href`) al ancla real de tu formulario de contacto
   (por defecto apunta a `/#contacto`).

> El cuerpo usa solo clases que ya existen en el sitio (`.btn`, `.section`) más
> estilos en línea mínimos, para que se vea bien sin tocar CSS.

---

## 2. Cuerpo HTML (copiar/pegar tal cual)

```html
<p style="font-size:1.15rem;max-width:60ch;">
  Tu marketing ya trae interesados. El problema es lo que pasa <strong>después del clic</strong>:
  leads que se enfrían en WhatsApp, planillas que nadie vuelve a mirar, y cero idea de
  cuánto cierra realmente. <strong>El Starter cierra ese hueco.</strong>
</p>

<p>
  <a class="btn" href="/#contacto">Quiero una demo</a>
  <a class="btn btn--ghost" href="/#contacto">Hablar con ARVIOR</a>
</p>

<h2>Qué es</h2>
<p>
  <strong>ARVIOR Revenue System — Starter</strong> es un sistema que captura cada lead de tu
  negocio, lo ordena en un embudo, te avisa al instante y te muestra en números cuánto te deja.
  Nosotros lo construimos, lo dejamos andando y <strong>lo operamos mes a mes</strong>. Vos recibís resultados,
  no otro software para administrar.
</p>

<h2>Qué incluye</h2>
<ul>
  <li><strong>Landing de captura</strong> pensada para convertir (no un folleto).</li>
  <li><strong>Formulario de leads</strong> con anti-spam y sin duplicados.</li>
  <li><strong>CRM / embudo</strong>: de "Nuevo" a "Ganado", todo en un panel.</li>
  <li><strong>Tareas y seguimiento</strong> que no se olvidan.</li>
  <li><strong>Aviso inmediato</strong> de cada lead + autorespuesta al prospecto.</li>
  <li><strong>Reportes de plata</strong>: leads, cierres, ticket promedio, pipeline y forecast.</li>
  <li><strong>Operación mensual por ARVIOR</strong> + reporte de resultados.</li>
</ul>

<h2>Antes y después</h2>
<table>
  <thead><tr><th>Hoy</th><th>Con el Starter</th></tr></thead>
  <tbody>
    <tr><td>Leads dispersos en WhatsApp y planillas</td><td>Todo en un panel, por etapa</td></tr>
    <tr><td>Respuesta en horas… o nunca</td><td>Aviso y respuesta en minutos</td></tr>
    <tr><td>"Creo que llegan consultas"</td><td>Números: cuántas, cuántas cerraste, cuánto valieron</td></tr>
    <tr><td>El marketing es un gasto a ciegas</td><td>El marketing se vuelve medible</td></tr>
  </tbody>
</table>

<h2>Cómo funciona</h2>
<ol>
  <li><strong>Construimos</strong> tu landing y tu sistema (días, no meses).</li>
  <li><strong>Conectamos</strong> tus canales y cargamos tus datos.</li>
  <li><strong>Operamos</strong> el sistema y te enviamos el reporte cada mes.</li>
</ol>

<div class="section" style="border:1px solid #e5e7eb;border-radius:14px;padding:1.5rem;margin-top:2rem;">
  <h2 style="margin-top:0;">Inversión</h2>
  <p style="font-size:1.1rem;">
    <strong>Puesta en marcha</strong> (una vez) + <strong>Operación mensual</strong>.
    El Starter se paga solo: si recuperás <strong>un solo cliente al mes</strong> de los que hoy
    se te pierden, la operación ya te quedó gratis.
  </p>
  <p><a class="btn" href="/#contacto">Pedir propuesta</a></p>
  <p style="font-size:.85rem;color:#6b7280;">Precio según tu negocio. Te lo mostramos en una llamada de 20 minutos, con tus números.</p>
</div>

<h2>Para quién es</h2>
<p>
  Negocios de servicios que <strong>ya invierten en atraer clientes</strong> — clínicas, estudios
  profesionales, inmobiliarias, retail, e-commerce con operación real — y que pierden ventas
  porque no tienen un sistema detrás del clic.
</p>

<h2>Qué NO es (todavía)</h2>
<p style="color:#6b7280;">
  El Starter no incluye bot de WhatsApp, IA, ni integraciones externas. Eso llega en planes
  superiores, <strong>cuando este sistema ya te esté generando retorno</strong>.
</p>

<p style="margin-top:2rem;">
  <a class="btn" href="/#contacto">Quiero empezar</a>
</p>
```

---

## 3. Variantes de copy (para A/B o redes)

- **Headline alternativo:** *"Dejá de perder los clientes que ya pagás por atraer."*
- **Subhead:** *"Capturamos, ordenamos y medimos cada lead. Lo construimos y lo operamos por vos."*
- **CTA corto para redes:** *"Tu marketing trae interesados; nosotros nos aseguramos de que no se caigan. Pedí tu demo."*

---

## 4. Nota de implementación

Si más adelante se quiere que esta página se **siembre automáticamente** en cada
instalación, eso sí sería un cambio de Core (una migración/seed) y queda fuera del
alcance de Fase 5. Por ahora se publica manualmente con el editor de Páginas.
