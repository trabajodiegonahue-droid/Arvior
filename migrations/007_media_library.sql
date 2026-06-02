-- Central de Medios global: organizada por carpetas, reutilizable en cualquier lado.
-- Tablas + carpetas seed neutras (General, Marca).

CREATE TABLE IF NOT EXISTS media_folders (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    parent_id   INT DEFAULT NULL,
    name        VARCHAR(120) NOT NULL,
    slug        VARCHAR(140) NOT NULL,
    sort_order  INT NOT NULL DEFAULT 0,
    created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_folders_parent (parent_id, sort_order),
    UNIQUE KEY uniq_folder_slug_parent (parent_id, slug),
    CONSTRAINT fk_folder_parent FOREIGN KEY (parent_id) REFERENCES media_folders(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS media_library (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    folder_id   INT DEFAULT NULL,
    file_path   VARCHAR(255) NOT NULL,
    thumb_path  VARCHAR(255) DEFAULT NULL,
    path_md     VARCHAR(255) DEFAULT NULL,
    path_sm     VARCHAR(255) DEFAULT NULL,
    title       VARCHAR(200) DEFAULT NULL,
    alt         VARCHAR(255) NOT NULL DEFAULT '',
    seo_name    VARCHAR(180) DEFAULT NULL,
    mime        VARCHAR(80)  DEFAULT NULL,
    width       INT  DEFAULT NULL,
    height      INT  DEFAULT NULL,
    bytes       INT  DEFAULT NULL,
    sort_order  INT  NOT NULL DEFAULT 0,
    created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_media_folder (folder_id, sort_order),
    CONSTRAINT fk_media_folder FOREIGN KEY (folder_id) REFERENCES media_folders(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO media_folders (parent_id, name, slug, sort_order) VALUES
    (NULL, 'General', 'general', 10),
    (NULL, 'Marca',   'marca',   20)
ON DUPLICATE KEY UPDATE slug = slug;
