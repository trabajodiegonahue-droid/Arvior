-- Rebrand ARVIOR -> KOVA.
--
-- Cambia el nombre de marca en la base de datos: el setting `site_name`
-- (que alimenta header, footer, <title>, og:site_name y el JSON-LD del
-- negocio) y cualquier otro setting o página del CMS que contenga el texto
-- "ARVIOR" (razón social, descripciones, páginas legales seedeadas en 023).
--
-- Seguro e idempotente: usa REPLACE sobre el texto, así que tras la primera
-- corrida no quedan ocurrencias y volver a correrla no tiene efecto. Solo
-- toca filas que aún contengan "ARVIOR" (no pisa nada que ya hayas renombrado).

UPDATE settings
   SET setting_value = REPLACE(setting_value, 'ARVIOR', 'KOVA')
 WHERE setting_value LIKE '%ARVIOR%';

UPDATE pages
   SET title            = REPLACE(title, 'ARVIOR', 'KOVA'),
       body             = REPLACE(body, 'ARVIOR', 'KOVA'),
       meta_description = REPLACE(COALESCE(meta_description, ''), 'ARVIOR', 'KOVA')
 WHERE title LIKE '%ARVIOR%'
    OR body LIKE '%ARVIOR%'
    OR meta_description LIKE '%ARVIOR%';
