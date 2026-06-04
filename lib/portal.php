<?php

/**
 * Portal Comercial — ARVIOR (Fase 6).
 *
 * Fuente ÚNICA de contenido del sitio comercial: servicios, proyectos demo,
 * proceso de trabajo, propuestas de valor y opciones del formulario. Vive en
 * código (no en BD) para mantener simple la fase y no duplicar módulos: editar
 * el sitio = editar este archivo. Las páginas siguen pudiendo crearse con el
 * CMS de Páginas existente; esto solo alimenta las vistas del portal.
 *
 * No introduce esquema nuevo ni toca el CRM: los formularios del portal usan
 * leadCreate() (la misma función del sitio e intake) y todo termina en el
 * pipeline ya construido.
 */

/** Íconos SVG inline (sin dependencias). Trazo coherente con el resto del sitio. */
function portalIcon(string $key): string {
    $a = 'viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"';
    $icons = [
        'web'     => "<rect x='2' y='3' width='20' height='14' rx='2'/><path d='M2 9h20M8 21h8M12 17v4'/>",
        'landing' => "<rect x='4' y='2' width='16' height='20' rx='2'/><path d='M8 6h8M8 10h8M8 14h5'/><circle cx='12' cy='18.5' r='1'/>",
        'shop'    => "<path d='M3 3h2l2 13h11l2-9H6'/><circle cx='9' cy='20' r='1.4'/><circle cx='17' cy='20' r='1.4'/>",
        'google'  => "<circle cx='11' cy='11' r='7'/><path d='M21 21l-4.3-4.3'/><path d='M11 8v6M8 11h6'/>",
        'meta'    => "<path d='M3 12c2-6 5-6 7 0s5 6 7 0'/><path d='M3 12c0 4 2 6 4 6M21 12c0 4-2 6-4 6'/>",
        'seo'     => "<path d='M4 18V9M9 18V5M14 18v-6M19 18v-9'/><path d='M3 21h18'/>",
        'auto'    => "<path d='M12 2v3M12 19v3M5 5l2 2M17 17l2 2M2 12h3M19 12h3M5 19l2-2M17 7l2-2'/><circle cx='12' cy='12' r='3.5'/>",
        'crm'     => "<path d='M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2'/><circle cx='9' cy='7' r='4'/><path d='M22 21v-2a4 4 0 0 0-3-3.87M16 3.1a4 4 0 0 1 0 7.75'/>",
        'software'=> "<polyline points='8 6 3 12 8 18'/><polyline points='16 6 21 12 16 18'/>",
        'ai'      => "<rect x='5' y='6' width='14' height='12' rx='3'/><path d='M9 2v4M15 2v4M9 11h.01M15 11h.01M9 15h6M2 12h3M19 12h3'/>",
        'consult' => "<path d='M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z'/><path d='M8 9h8M8 13h5'/>",
        'check'   => "<polyline points='20 6 9 17 4 12'/>",
        'rocket'  => "<path d='M5 13c-1.5 1-2 4-2 4s3-.5 4-2'/><path d='M14 4c3 0 6 3 6 6 0 4-5 9-9 11-1-1-2-2-3-3 2-4 7-9 11-9'/><circle cx='14.5' cy='9.5' r='1.5'/>",
        'shield'  => "<path d='M12 3l8 3v5c0 5-3.5 8-8 10-4.5-2-8-5-8-10V6z'/>",
        'chart'   => "<path d='M3 3v18h18'/><rect x='7' y='10' width='3' height='7'/><rect x='12' y='6' width='3' height='11'/><rect x='17' y='13' width='3' height='4'/>",
    ];
    $p = $icons[$key] ?? $icons['web'];
    return "<svg $a>$p</svg>";
}

/**
 * Catálogo de servicios. Cada uno: slug (URL), title, tagline, icon, summary,
 * description (párrafos), benefits[]. Orden = orden de aparición.
 * @return array<int,array>
 */
function portalServices(): array {
    return [
        [
            'slug' => 'desarrollo-web', 'icon' => 'web',
            'title' => 'Desarrollo Web',
            'tagline' => 'Sitios corporativos rápidos, seguros y pensados para vender.',
            'summary' => 'Sitios web profesionales, optimizados y orientados a conversión, no folletos digitales.',
            'description' => 'Diseñamos y construimos sitios corporativos a medida, con foco en velocidad, seguridad y conversión. Cada sitio se piensa como una herramienta comercial: estructura clara, llamados a la acción y captura de oportunidades conectada a tu CRM.',
            'benefits' => ['Diseño profesional y responsive', 'Carga rápida y buenas prácticas de SEO técnico', 'Formularios que capturan oportunidades reales', 'Administrable y escalable'],
        ],
        [
            'slug' => 'landing-pages', 'icon' => 'landing',
            'title' => 'Landing Pages',
            'tagline' => 'Páginas de aterrizaje que convierten tráfico en clientes.',
            'summary' => 'Landings enfocadas en una sola acción: que el visitante deje su solicitud.',
            'description' => 'Creamos landing pages de alto rendimiento para campañas y lanzamientos. Una sola propuesta, un solo objetivo y un formulario que alimenta directamente tu pipeline comercial.',
            'benefits' => ['Optimizadas para campañas de Ads', 'Mensaje claro y CTA único', 'A/B testing de copy y estructura', 'Integración directa con el CRM'],
        ],
        [
            'slug' => 'tiendas-online', 'icon' => 'shop',
            'title' => 'Tiendas Online',
            'tagline' => 'E-commerce listo para vender, no solo para mostrar.',
            'summary' => 'Tiendas online con catálogo, pagos y operación pensada para crecer.',
            'description' => 'Implementamos tiendas online con catálogo, carrito, medios de pago y una operación ordenada. Tu negocio vende en línea con procesos claros y reportes de lo que importa.',
            'benefits' => ['Catálogo y gestión de productos', 'Integración con medios de pago', 'Experiencia de compra optimizada', 'Reportes de ventas y conversión'],
        ],
        [
            'slug' => 'google-ads', 'icon' => 'google',
            'title' => 'Google Ads',
            'tagline' => 'Aparecé cuando tu cliente te está buscando.',
            'summary' => 'Campañas de búsqueda y display que traen demanda con intención de compra.',
            'description' => 'Gestionamos campañas en Google Ads orientadas a resultados: captamos a quienes ya están buscando lo que ofrecés y derivamos esa demanda a páginas que convierten y registran cada lead.',
            'benefits' => ['Captura de demanda con intención', 'Optimización por costo por lead', 'Landing + medición integradas', 'Reporte de retorno real'],
        ],
        [
            'slug' => 'meta-ads', 'icon' => 'meta',
            'title' => 'Meta Ads',
            'tagline' => 'Generá demanda en Instagram y Facebook.',
            'summary' => 'Campañas en Meta para dar a conocer, generar interés y captar oportunidades.',
            'description' => 'Diseñamos y operamos campañas en Instagram y Facebook para generar demanda nueva: creatividades, segmentación y un flujo de captura que lleva cada interesado al pipeline.',
            'benefits' => ['Segmentación por audiencia ideal', 'Creatividades orientadas a conversión', 'Captura conectada al CRM', 'Medición de costo por oportunidad'],
        ],
        [
            'slug' => 'seo', 'icon' => 'seo',
            'title' => 'SEO',
            'tagline' => 'Crecé en Google de forma sostenible.',
            'summary' => 'Posicionamiento orgánico para atraer tráfico calificado sin depender solo de Ads.',
            'description' => 'Trabajamos el posicionamiento orgánico de tu sitio: SEO técnico, contenido y estructura para que aparezcas por los términos que importan y atraigas tráfico calificado mes a mes.',
            'benefits' => ['Auditoría y SEO técnico', 'Estructura y contenido optimizable', 'Tráfico calificado sostenible', 'Menor dependencia de la pauta'],
        ],
        [
            'slug' => 'automatizacion', 'icon' => 'auto',
            'title' => 'Automatización',
            'tagline' => 'Eliminá tareas repetitivas y ganá tiempo.',
            'summary' => 'Automatizamos procesos internos para que tu equipo se enfoque en lo que importa.',
            'description' => 'Conectamos y automatizamos tus procesos comerciales y operativos: seguimiento de leads, tareas, notificaciones y flujos internos que hoy dependen de que alguien se acuerde.',
            'benefits' => ['Menos trabajo manual', 'Seguimiento que no se olvida', 'Procesos consistentes', 'Equipo enfocado en vender'],
        ],
        [
            'slug' => 'crm', 'icon' => 'crm',
            'title' => 'CRM',
            'tagline' => 'Ordená tu comercial en un solo lugar.',
            'summary' => 'Implementamos y operamos un CRM real: embudo, tareas, actividad y reportes.',
            'description' => 'Implementamos un CRM que captura, ordena y mide cada oportunidad: pipeline por etapas, tareas, actividad por lead y reportes de revenue. El mismo sistema que opera este sitio.',
            'benefits' => ['Pipeline comercial por etapas', 'Tareas y próximas acciones', 'Reportes de revenue y forecast', 'Todo en un panel, sin planillas'],
        ],
        [
            'slug' => 'desarrollo-de-software', 'icon' => 'software',
            'title' => 'Desarrollo de Software',
            'tagline' => 'Software a medida para tu operación.',
            'summary' => 'Plataformas y herramientas internas que se ajustan a cómo trabajás.',
            'description' => 'Construimos software a medida: plataformas internas, paneles y herramientas que transforman procesos manuales en infraestructura digital confiable y escalable.',
            'benefits' => ['Hecho a la medida de tu proceso', 'Tecnología moderna y mantenible', 'Escala con tu negocio', 'Soporte y evolución continua'],
        ],
        [
            'slug' => 'inteligencia-artificial', 'icon' => 'ai',
            'title' => 'Inteligencia Artificial',
            'tagline' => 'IA aplicada a resultados, no a la moda.',
            'summary' => 'Asistentes y funciones inteligentes integradas a tus productos y operación.',
            'description' => 'Incorporamos inteligencia artificial donde genera valor real: asistentes, clasificación y funciones inteligentes integradas a tus sistemas para crear apalancamiento, no humo.',
            'benefits' => ['Casos de uso con retorno claro', 'Integración a tus sistemas actuales', 'Automatización inteligente', 'Implementación responsable'],
        ],
        [
            'slug' => 'consultoria-digital', 'icon' => 'consult',
            'title' => 'Consultoría Digital',
            'tagline' => 'Estrategia clara antes de construir.',
            'summary' => 'Diagnóstico y hoja de ruta para invertir en lo que mueve la aguja.',
            'description' => 'Te ayudamos a definir qué construir y en qué orden: diagnóstico de tu presencia digital, oportunidades y una hoja de ruta priorizada por impacto comercial.',
            'benefits' => ['Diagnóstico honesto', 'Prioridades por impacto', 'Hoja de ruta accionable', 'Acompañamiento experto'],
        ],
    ];
}

/** Un servicio por slug, o null. */
function portalServiceBySlug(string $slug): ?array {
    foreach (portalServices() as $s) {
        if ($s['slug'] === $slug) return $s;
    }
    return null;
}

/** Servicios destacados para el home (primeros N). */
function portalFeaturedServices(int $n = 6): array {
    return array_slice(portalServices(), 0, $n);
}

/** Etiquetas de servicio para el <select> del formulario (incluye "Otro"). */
function portalServiceOptions(): array {
    $opts = array_map(fn($s) => $s['title'], portalServices());
    $opts[] = 'Otro / No estoy seguro';
    return $opts;
}

/** Opciones de presupuesto aproximado (CLP) para el formulario de cotización. */
function portalBudgetOptions(): array {
    return [
        'Menos de $500.000',
        '$500.000 – $1.500.000',
        '$1.500.000 – $3.000.000',
        '$3.000.000 – $6.000.000',
        'Más de $6.000.000',
        'A definir',
    ];
}

/** Proceso de trabajo (5 pasos pedidos). */
function portalProcess(): array {
    return [
        ['num' => '01', 'title' => 'Diagnóstico', 'text' => 'Entendemos tu negocio, tus objetivos y dónde se están perdiendo oportunidades hoy.'],
        ['num' => '02', 'title' => 'Propuesta', 'text' => 'Definimos qué construir, en qué orden y con qué resultado esperado. Sin humo.'],
        ['num' => '03', 'title' => 'Desarrollo', 'text' => 'Diseñamos y construimos la solución con tecnología moderna y foco en conversión.'],
        ['num' => '04', 'title' => 'Implementación', 'text' => 'Ponemos el sistema en marcha, lo conectamos a tu operación y capacitamos a tu equipo.'],
        ['num' => '05', 'title' => 'Seguimiento', 'text' => 'Medimos resultados, optimizamos y acompañamos el crecimiento mes a mes.'],
    ];
}

/** Propuestas de valor / beneficios del home. */
function portalBenefits(): array {
    return [
        ['icon' => 'rocket',  'title' => 'Orientado a resultados', 'text' => 'No vendemos sitios bonitos: construimos activos que generan oportunidades comerciales medibles.'],
        ['icon' => 'crm',     'title' => 'Conectado a tu comercial', 'text' => 'Cada solicitud entra directo a un CRM real con pipeline, seguimiento y reportes.'],
        ['icon' => 'shield',  'title' => 'Tecnología confiable', 'text' => 'Sistemas rápidos, seguros y mantenibles, listos para escalar con tu negocio.'],
        ['icon' => 'chart',   'title' => 'Todo medible', 'text' => 'Sabés cuántas oportunidades llegan, en qué etapa están y cuánto valen.'],
    ];
}

/**
 * Proyectos representativos por tipo de solución. NO son casos de clientes con
 * métricas reales: el campo `result` describe la CAPACIDAD que entrega cada
 * proyecto (qué obtiene el cliente), sin porcentajes ni estadísticas inventadas.
 * Cuando haya casos reales con resultados verificados, se reemplaza este arreglo.
 */
function portalProjects(): array {
    return [
        ['tag' => 'Desarrollo Web', 'title' => 'Sitio corporativo institucional', 'text' => 'Sitio profesional con captación de cotizaciones conectada directamente al CRM.', 'result' => 'Cotizaciones que entran directo al CRM'],
        ['tag' => 'Google Ads', 'title' => 'Campaña de búsqueda + landing', 'text' => 'Estrategia de búsqueda con landing dedicada para captar demanda con intención de compra.', 'result' => 'Demanda calificada y medible'],
        ['tag' => 'Tienda Online', 'title' => 'E-commerce con operación integrada', 'text' => 'Tienda online con catálogo, medios de pago y reportes de venta en un solo lugar.', 'result' => 'Catálogo, pagos y reportes integrados'],
        ['tag' => 'Automatización', 'title' => 'Motor de captación de leads', 'text' => 'Captura, asignación y seguimiento automático de cada oportunidad comercial.', 'result' => 'Seguimiento sin leads olvidados'],
        ['tag' => 'CRM', 'title' => 'Pipeline comercial por etapas', 'text' => 'Implementación de CRM con embudo, tareas, actividad por lead y reportes de revenue.', 'result' => 'Visibilidad completa del embudo'],
        ['tag' => 'IA', 'title' => 'Asistente con inteligencia artificial', 'text' => 'Asistente que clasifica y responde consultas frecuentes integrado a tu operación.', 'result' => 'Respuestas más rápidas al cliente'],
    ];
}

/** Navegación principal del portal (orden del menú). */
function portalNav(): array {
    return [
        ['path' => '/',           'label' => 'Inicio',    'slug' => ''],
        ['path' => '/servicios',  'label' => 'Servicios', 'slug' => 'servicios'],
        ['path' => '/proyectos',  'label' => 'Proyectos', 'slug' => 'proyectos'],
        ['path' => '/proceso',    'label' => 'Proceso',   'slug' => 'proceso'],
        ['path' => '/contacto',   'label' => 'Contacto',  'slug' => 'contacto'],
    ];
}

/** Rutas que maneja el portal (para el router de index.php). */
function portalRoutes(): array {
    return ['servicios', 'proyectos', 'proceso', 'cotizacion', 'contacto'];
}
