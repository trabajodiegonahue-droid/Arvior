-- ARVIOR · Go Live — Páginas legales base (Privacidad y Términos).
--
-- Crea dos páginas en el CMS existente (tabla `pages`) con contenido base
-- profesional, para que los enlaces del footer (legal_privacy_url=/privacidad,
-- legal_terms_url=/terminos, definidos en la migración 013) dejen de dar 404 y
-- el sitio cumpla el mínimo legal para capturar datos personales por formulario.
--
-- El contenido es una BASE editable: revisalo y ajustá razón social, domicilio
-- legal y jurisdicción desde Admin → Páginas antes de promocionar el sitio.
--
-- Idempotente: si las páginas ya existen (slug único), NO se sobrescriben
-- (ON DUPLICATE KEY UPDATE slug = slug), para no pisar tus ediciones del panel.

INSERT INTO pages (slug, title, body, meta_description, is_published) VALUES
(
    'privacidad',
    'Política de Privacidad',
    '<p>En ARVIOR valoramos y respetamos tu privacidad. Esta Política de Privacidad explica qué datos personales recopilamos a través de este sitio, con qué finalidad los usamos y cuáles son tus derechos.</p>
<h2>1. Responsable del tratamiento</h2>
<p>El responsable del tratamiento de los datos es ARVIOR. Para cualquier consulta relacionada con tus datos personales podés escribirnos a través de los medios de contacto publicados en este sitio.</p>
<h2>2. Datos que recopilamos</h2>
<p>Cuando completás un formulario de contacto o de cotización podemos recopilar: nombre, correo electrónico, teléfono, empresa y la información que incluyas en el mensaje. Adicionalmente, por motivos de seguridad podemos registrar la dirección IP y datos técnicos de la solicitud.</p>
<h2>3. Finalidad del tratamiento</h2>
<p>Usamos estos datos exclusivamente para: responder tu consulta, elaborar y enviarte una propuesta o cotización, dar seguimiento comercial a tu solicitud y mantener el registro de la comunicación. No vendemos ni cedemos tus datos a terceros con fines publicitarios.</p>
<h2>4. Conservación</h2>
<p>Conservamos tus datos durante el tiempo necesario para gestionar tu solicitud y mantener nuestra relación comercial, o hasta que solicites su eliminación.</p>
<h2>5. Tus derechos</h2>
<p>Podés solicitar en cualquier momento el acceso, la rectificación o la eliminación de tus datos personales, así como oponerte a su tratamiento, escribiéndonos por los medios de contacto del sitio.</p>
<h2>6. Cookies y analítica</h2>
<p>Este sitio puede utilizar cookies y herramientas de analítica para entender cómo se usa y mejorar la experiencia. Podés configurar tu navegador para rechazar las cookies, aunque algunas funciones podrían verse afectadas.</p>
<h2>7. Cambios en esta política</h2>
<p>Podemos actualizar esta Política de Privacidad. La versión vigente será siempre la publicada en esta página.</p>',
    'Política de Privacidad de ARVIOR: qué datos personales recopilamos, con qué finalidad y cuáles son tus derechos.',
    1
),
(
    'terminos',
    'Términos y Condiciones',
    '<p>Estos Términos y Condiciones regulan el uso de este sitio web de ARVIOR. Al navegar y utilizar el sitio aceptás estos términos.</p>
<h2>1. Uso del sitio</h2>
<p>Este sitio tiene fines informativos y comerciales: presentar nuestros servicios y permitir que solicites información o una cotización. Te comprometés a usarlo de forma lícita y a proporcionar información veraz en los formularios.</p>
<h2>2. Servicios y cotizaciones</h2>
<p>La información publicada sobre servicios es de carácter general y no constituye una oferta vinculante. Toda cotización o propuesta se confirma de forma individual tras evaluar tu solicitud, y puede variar según el alcance del proyecto.</p>
<h2>3. Propiedad intelectual</h2>
<p>Los contenidos del sitio (textos, diseño, logotipos y elementos gráficos) pertenecen a ARVIOR o se utilizan con autorización, y no pueden reproducirse sin consentimiento previo.</p>
<h2>4. Responsabilidad</h2>
<p>Hacemos nuestro mejor esfuerzo para mantener el sitio disponible y la información actualizada, pero no garantizamos la ausencia total de errores o interrupciones. El uso del sitio es bajo tu propia responsabilidad.</p>
<h2>5. Enlaces a terceros</h2>
<p>El sitio puede incluir enlaces a sitios de terceros. No somos responsables del contenido ni de las prácticas de privacidad de esos sitios.</p>
<h2>6. Protección de datos</h2>
<p>El tratamiento de los datos personales que nos facilites se rige por nuestra <a href="/privacidad">Política de Privacidad</a>.</p>
<h2>7. Modificaciones</h2>
<p>Podemos modificar estos Términos en cualquier momento. La versión vigente será la publicada en esta página.</p>',
    'Términos y Condiciones de uso del sitio de ARVIOR: condiciones de uso, cotizaciones, propiedad intelectual y responsabilidad.',
    1
)
ON DUPLICATE KEY UPDATE slug = slug;
