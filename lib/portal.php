<?php

/**
 * Portal Comercial — ARVIOR.
 *
 * Fuente ÚNICA de contenido del sitio comercial: servicios, planes, proceso de
 * trabajo, propuestas de valor, FAQ y opciones del formulario. Vive en código
 * (no en BD) para mantener simple la operación y no duplicar módulos: editar el
 * sitio = editar este archivo. Las páginas siguen pudiendo crearse con el CMS de
 * Páginas existente; esto solo alimenta las vistas del portal.
 *
 * No introduce esquema nuevo ni toca el CRM: los formularios del portal usan
 * leadCreate() (la misma función del sitio e intake) y todo termina en el
 * pipeline ya construido.
 *
 * Fase comercial actual: ARVIOR es un estudio de DESARROLLO WEB y vende
 * únicamente Sitios Corporativos, Landing Pages, Tiendas Online (Ecommerce) y
 * Mantención. El catálogo refleja exactamente eso (no CRM/IA/software a medida).
 * Voz: español de Chile, trato "tú".
 */

/** Íconos SVG inline (sin dependencias). Trazo coherente con el resto del sitio. */
function portalIcon(string $key): string {
    $a = 'viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"';
    $icons = [
        'web'     => "<rect x='2' y='3' width='20' height='14' rx='2'/><path d='M2 9h20M8 21h8M12 17v4'/>",
        'landing' => "<rect x='4' y='2' width='16' height='20' rx='2'/><path d='M8 6h8M8 10h8M8 14h5'/><circle cx='12' cy='18.5' r='1'/>",
        'shop'    => "<path d='M3 3h2l2 13h11l2-9H6'/><circle cx='9' cy='20' r='1.4'/><circle cx='17' cy='20' r='1.4'/>",
        'wrench'  => "<path d='M14.7 6.3a4 4 0 0 0-5.4 5.2L3 18l3 3 6.5-6.3a4 4 0 0 0 5.2-5.4l-2.4 2.4-2.3-.6-.6-2.3z'/>",
        'consult' => "<path d='M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z'/><path d='M8 9h8M8 13h5'/>",
        'check'   => "<polyline points='20 6 9 17 4 12'/>",
        'x'       => "<line x1='18' y1='6' x2='6' y2='18'/><line x1='6' y1='6' x2='18' y2='18'/>",
        'target'  => "<circle cx='12' cy='12' r='9'/><circle cx='12' cy='12' r='5'/><circle cx='12' cy='12' r='1.4'/>",
        'shield'  => "<path d='M12 3l8 3v5c0 5-3.5 8-8 10-4.5-2-8-5-8-10V6z'/>",
        'clock'   => "<circle cx='12' cy='12' r='9'/><polyline points='12 7 12 12 15 14'/>",
        'lock'    => "<rect x='4' y='10' width='16' height='11' rx='2'/><path d='M8 10V7a4 4 0 0 1 8 0v3'/>",
        'message' => "<path d='M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z'/>",
        'compass' => "<circle cx='12' cy='12' r='9'/><polygon points='16 8 14 14 8 16 10 10 16 8'/>",
        'layers'  => "<polygon points='12 3 21 8 12 13 3 8 12 3'/><polyline points='3 13 12 18 21 13'/>",
        'spark'   => "<path d='M12 3v4M12 17v4M3 12h4M17 12h4M6 6l2.5 2.5M15.5 15.5 18 18M18 6l-2.5 2.5M8.5 15.5 6 18'/><circle cx='12' cy='12' r='2.4'/>",
        'phone'   => "<path d='M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.4 1.8.7 2.7a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.7.7a2 2 0 0 1 1.8 2z'/>",
        'handshake' => "<path d='M11 17l2 2a1.5 1.5 0 0 0 2.1-2.1'/><path d='M14 16l1.8 1.8a1.5 1.5 0 0 0 2.1-2.1L13 11'/><path d='M3 8l3-3 5 5-1.5 1.5a1.5 1.5 0 0 1-2.1 0L6 11'/><path d='M21 8l-3-3-4 4'/>",
    ];
    $p = $icons[$key] ?? $icons['web'];
    return "<svg $a>$p</svg>";
}

/**
 * Catálogo de servicios (los 4 que ARVIOR vende hoy). Cada uno:
 *   slug (URL), title, tagline, icon, summary, price (referencial "desde"),
 *   timeline, problem (qué problema resuelve), gain (qué obtienes),
 *   when (cuándo te conviene), description (párrafo), includes[] (qué incluye).
 * Orden = orden de aparición.
 * @return array<int,array>
 */
function portalServices(): array {
    return [
        [
            'slug' => 'sitios-web', 'icon' => 'web',
            'title' => 'Sitios Web Corporativos',
            'tagline' => 'La cara de tu empresa, hecha para generar confianza y contactos.',
            'summary' => 'Un sitio profesional que convierte visitas en consultas.',
            'price' => 'Desde $990.000',
            'timeline' => 'Listo en 3 semanas',
            'problem' => 'Tu empresa se ve menos profesional de lo que es y las visitas no te escriben.',
            'gain' => 'Un sitio claro y rápido que genera confianza y deja cada consulta lista para atender.',
            'when' => 'Si ya tienes clientes pero tu web no representa el nivel de tu empresa.',
            'description' => 'Sitios corporativos a medida, con foco en claridad, velocidad y confianza. Cada página, una herramienta comercial: estructura ordenada y un camino claro al contacto.',
            'includes' => [
                'Hasta 6 secciones diseñadas a medida',
                'Optimizado para celular y carga rápida',
                'Formulario de contacto que te llega ordenado',
                'Buenas prácticas de SEO técnico y velocidad',
                'Textos guiados junto a ti',
                'Capacitación para que lo puedas administrar',
            ],
        ],
        [
            'slug' => 'landing-pages', 'icon' => 'landing',
            'title' => 'Landing Pages',
            'tagline' => 'Una página con un solo objetivo: que te contacten.',
            'summary' => 'Una página enfocada en captar contactos, ideal para campañas.',
            'price' => 'Desde $450.000',
            'timeline' => 'Lista en 1 semana',
            'problem' => 'Inviertes en publicidad, pero el tráfico llega a un lugar que no convierte.',
            'gain' => 'Una página directa, sin distracciones, hecha para que dejen sus datos.',
            'when' => 'Si vas a hacer campañas (Instagram, Google) y necesitas un destino que convierta.',
            'description' => 'Landing pages de alto rendimiento para campañas y lanzamientos: una propuesta, un objetivo y un formulario que te avisa cada vez que alguien se interesa.',
            'includes' => [
                'Una página enfocada en una sola acción',
                'Mensaje y llamada a la acción claros',
                'Diseñada para campañas de publicidad',
                'Formulario que te llega al instante',
                'Lista para medir resultados',
                'Carga rápida en celular',
            ],
        ],
        [
            'slug' => 'tiendas-online', 'icon' => 'shop',
            'title' => 'Tiendas Online',
            'tagline' => 'Vende en línea con una operación ordenada, no solo con un catálogo bonito.',
            'summary' => 'Ecommerce con catálogo y medios de pago, listo para vender.',
            'price' => 'Desde $1.800.000',
            'timeline' => 'Plazo según catálogo',
            'problem' => 'Quieres vender por internet, pero armar la tienda te parece complejo.',
            'gain' => 'Una tienda lista para recibir pedidos y pagos, fácil de administrar.',
            'when' => 'Si vendes productos y hoy dependes de mensajes manuales para cada venta.',
            'description' => 'Tiendas online con catálogo, carrito, medios de pago y una operación clara. Vendes en línea con procesos ordenados y una compra simple para tus clientes.',
            'includes' => [
                'Catálogo y gestión de productos',
                'Integración con medios de pago',
                'Experiencia de compra simple y confiable',
                'Optimizada para celular',
                'Panel para administrar tus ventas',
                'Capacitación para operarla tú mismo',
            ],
        ],
        [
            'slug' => 'mantencion', 'icon' => 'wrench',
            'title' => 'Mantención y Soporte',
            'tagline' => 'Tu sitio cuidado, actualizado y respaldado, mes a mes.',
            'summary' => 'Hosting, soporte y cambios mensuales para tu sitio.',
            'price' => 'Desde $35.000 / mes',
            'timeline' => 'Plan mensual continuo',
            'problem' => 'Lanzaste tu sitio y nadie lo cuida: queda lento, desactualizado o caído.',
            'gain' => 'Tranquilidad: alguien se hace cargo de que tu sitio esté arriba, seguro y al día.',
            'when' => 'Si ya tienes un sitio (con nosotros o no) y no quieres preocuparte de lo técnico.',
            'description' => 'Cuidamos la salud de tu sitio: hosting, respaldos, seguridad, monitoreo y los cambios del día a día. Tú te enfocas en tu negocio; nosotros en que siempre esté disponible.',
            'includes' => [
                'Hosting y dominio gestionados',
                'Respaldos y actualizaciones de seguridad',
                'Monitoreo de que el sitio esté arriba',
                'Cambios y ajustes de contenido',
                'Soporte directo cuando lo necesites',
                'Reporte simple de lo realizado',
            ],
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
function portalFeaturedServices(int $n = 4): array {
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
        '$500.000 – $1.000.000',
        '$1.000.000 – $2.000.000',
        '$2.000.000 – $4.000.000',
        'Más de $4.000.000',
        'A definir',
    ];
}

/**
 * Planes / paquetes con precio referencial. Transparencia = menos fricción.
 * El precio real y cerrado se confirma en la propuesta tras un breve diagnóstico.
 * `featured` marca el plan recomendado (ancla visual).
 */
function portalPackages(): array {
    return [
        [
            'name' => 'Landing Page', 'icon' => 'landing',
            'price' => '$450.000', 'unit' => 'proyecto', 'timeline' => '1 semana',
            'tagline' => 'Una página enfocada en captar contactos.',
            'features' => ['Una página a medida', 'Pensada para campañas', 'Formulario conectado', 'Lista en 1 semana'],
            'cta' => 'Cotizar landing', 'service' => 'Landing Pages', 'featured' => false,
        ],
        [
            'name' => 'Sitio Corporativo', 'icon' => 'web',
            'price' => '$990.000', 'unit' => 'proyecto', 'timeline' => '3 semanas',
            'tagline' => 'La opción ideal para la mayoría de las empresas.',
            'features' => ['Hasta 6 secciones', 'Diseño profesional a medida', 'SEO técnico y velocidad', 'Capacitación incluida'],
            'cta' => 'Cotizar mi sitio', 'service' => 'Sitios Web Corporativos', 'featured' => true,
        ],
        [
            'name' => 'Tienda Online', 'icon' => 'shop',
            'price' => '$1.800.000', 'unit' => 'desde', 'timeline' => 'según catálogo',
            'tagline' => 'Para vender tus productos por internet.',
            'features' => ['Catálogo y carrito', 'Medios de pago', 'Panel de administración', 'Experiencia optimizada'],
            'cta' => 'Cotizar mi tienda', 'service' => 'Tiendas Online', 'featured' => false,
        ],
    ];
}

/** Plan de mantención mensual (se muestra como nota bajo los paquetes). */
function portalMaintenance(): array {
    return [
        'title' => 'Plan de Mantención',
        'price' => 'Desde $35.000 / mes',
        'text'  => 'Hosting, respaldos, seguridad y cambios mensuales para mantener tu sitio al día.',
    ];
}

/** Proceso de trabajo (5 etapas). Transparencia = menos riesgo percibido. */
function portalProcess(): array {
    return [
        ['num' => '01', 'icon' => 'compass',   'title' => 'Diagnóstico',          'text' => 'Entendemos tu negocio y qué necesitas lograr. Sin tecnicismos.'],
        ['num' => '02', 'icon' => 'message',    'title' => 'Propuesta clara',      'text' => 'Alcance, precio y fecha cerrados antes de empezar.'],
        ['num' => '03', 'icon' => 'layers',     'title' => 'Diseño',               'text' => 'Lo apruebas antes de que escribamos una línea de código.'],
        ['num' => '04', 'icon' => 'spark',      'title' => 'Desarrollo y entrega', 'text' => 'Construimos, publicamos y te explicamos cómo usarlo.'],
        ['num' => '05', 'icon' => 'handshake',  'title' => 'Soporte',              'text' => 'Seguimos disponibles. Con el plan de mantención, cuidado mes a mes.'],
    ];
}

/**
 * Por qué ARVIOR — pilares de confianza (sustituyen prueba social inexistente:
 * sin clientes/testimonios/métricas inventadas, la confianza se construye con
 * claridad, especificidad y transparencia).
 */
function portalBenefits(): array {
    return [
        ['icon' => 'target',    'title' => 'Pensado para captar',     'text' => 'Diseñamos para que tus visitas se conviertan en consultas.'],
        ['icon' => 'clock',     'title' => 'Precio y fecha cerrados', 'text' => 'Sabes cuánto y cuándo antes de partir. Sin sorpresas.'],
        ['icon' => 'phone',     'title' => 'Atención directa',        'text' => 'Hablas con quien construye tu sitio, no con un intermediario.'],
        ['icon' => 'shield',    'title' => 'Soporte después',         'text' => 'No desaparecemos al entregar. Cuidamos tu sitio.'],
    ];
}

/** Franja de confianza del hero (reemplaza logos de clientes inexistentes). */
function portalTrust(): array {
    return [
        ['icon' => 'clock',  'text' => 'Precio y fecha cerrados'],
        ['icon' => 'check',  'text' => 'Listo en 1 a 3 semanas'],
        ['icon' => 'shield', 'text' => 'Soporte y mantención'],
        ['icon' => 'phone',  'text' => 'Atención directa'],
    ];
}

/** Preguntas frecuentes (resuelven objeciones de venta antes del formulario). */
function portalFaqs(): array {
    return [
        ['q' => '¿Cuánto se demora?', 'a' => 'Una landing en ~1 semana y un sitio corporativo en ~3. La fecha exacta queda cerrada en la propuesta.'],
        ['q' => '¿Cuánto cuesta?', 'a' => 'Landing desde $450.000, sitio desde $990.000 y tienda desde $1.800.000. El precio final lo confirmamos según tu proyecto.'],
        ['q' => '¿Qué necesitan de mí?', 'a' => 'Tus textos, imágenes y logo si los tienes. Si no, te guiamos para armarlos.'],
        ['q' => '¿Cómo es el pago?', 'a' => '50% al inicio y 50% contra entrega. Todo por escrito antes de partir.'],
        ['q' => '¿Y después de entregar?', 'a' => 'Seguimos disponibles. Puedes sumar el plan de mantención para mantenerlo al día.'],
        ['q' => 'Ya tengo un sitio, ¿lo mejoran?', 'a' => 'Sí. Lo revisamos y te decimos con honestidad si conviene mejorarlo o rehacerlo.'],
    ];
}

/**
 * Tipos de proyecto que construimos. NO son casos de clientes con métricas
 * reales: describen la CAPACIDAD que entrega cada tipo de proyecto (qué obtienes),
 * sin porcentajes ni estadísticas inventadas. Cuando haya casos reales con
 * resultados verificados, se reemplaza este arreglo.
 */
function portalProjects(): array {
    return [
        ['tag' => 'Sitio Corporativo', 'title' => 'Sitio institucional para empresa de servicios', 'text' => 'Estructura clara, presentación de servicios y un formulario que ordena cada consulta para que el equipo la atienda.', 'result' => 'Consultas ordenadas y listas para responder'],
        ['tag' => 'Landing Page', 'title' => 'Landing para campaña de captación', 'text' => 'Una sola página, una sola acción: el destino ideal para una campaña de Instagram o Google.', 'result' => 'Tráfico que se convierte en contactos'],
        ['tag' => 'Tienda Online', 'title' => 'Tienda con catálogo y pagos', 'text' => 'Catálogo de productos, carrito y medios de pago en una experiencia simple para el cliente.', 'result' => 'Ventas en línea con operación ordenada'],
        ['tag' => 'Sitio Corporativo', 'title' => 'Sitio para profesional independiente', 'text' => 'Una web sobria que transmite confianza y agenda contactos para abogados, médicos o consultores.', 'result' => 'Presencia profesional que genera confianza'],
        ['tag' => 'Mantención', 'title' => 'Cuidado continuo del sitio', 'text' => 'Hosting, respaldos, seguridad y cambios mensuales para que el sitio siempre funcione bien.', 'result' => 'Tranquilidad técnica mes a mes'],
        ['tag' => 'Landing Page', 'title' => 'Página de lanzamiento de producto', 'text' => 'Presentación enfocada de un nuevo producto o servicio, lista para difundir y medir.', 'result' => 'Lanzamiento con foco y medición'],
    ];
}

/** Navegación principal del portal (orden del menú). */
function portalNav(): array {
    return [
        ['path' => '/',           'label' => 'Inicio',    'slug' => ''],
        ['path' => '/servicios',  'label' => 'Servicios', 'slug' => 'servicios'],
        ['path' => '/proceso',    'label' => 'Proceso',   'slug' => 'proceso'],
        ['path' => '/contacto',   'label' => 'Contacto',  'slug' => 'contacto'],
    ];
}

/** Rutas que maneja el portal (para el router de index.php). */
function portalRoutes(): array {
    return ['servicios', 'proyectos', 'proceso', 'cotizacion', 'contacto'];
}
