-- Portafolio: proyectos reales (trabajos de clientes) organizados por rubro/tipo.
-- Single-tenant como `pages` (no se scopea por cuenta): es el portafolio del estudio.
-- Cada proyecto se ve público en /proyectos (filtrable por categoría) y en su
-- propio detalle /proyectos/{slug}. Se administra desde /admin/?view=portfolio.

CREATE TABLE IF NOT EXISTS portfolio_projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(120) NOT NULL UNIQUE,
    title VARCHAR(255) NOT NULL,
    -- categoría = tipo de servicio: landing | ecommerce | corporativo | mantencion | otro
    category VARCHAR(40) NOT NULL DEFAULT 'corporativo',
    client_name VARCHAR(160) NULL,
    summary VARCHAR(300) NULL,          -- bajada corta para la tarjeta
    description MEDIUMTEXT NULL,         -- descripción larga para el detalle
    cover_image VARCHAR(500) NULL,       -- imagen principal (captura del sitio)
    gallery TEXT NULL,                   -- JSON: paths de imágenes adicionales
    live_url VARCHAR(500) NULL,          -- link al sitio en vivo (opcional)
    result VARCHAR(255) NULL,            -- resultado/logro destacado (opcional)
    sort_order INT NOT NULL DEFAULT 0,   -- orden manual (asc); empate → más reciente
    is_featured TINYINT(1) NOT NULL DEFAULT 0,
    is_published TINYINT(1) NOT NULL DEFAULT 1,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_pub_cat (is_published, category, sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
