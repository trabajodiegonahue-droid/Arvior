<?php
/**
 * Central de Medios global. Carpetas + items reutilizables en cualquier lugar
 * del sitio (hero, OG image, secciones, etc.).
 */

require_once __DIR__ . '/image_pipeline.php';

/* ========== Carpetas ========== */

function mediaFoldersAll(): array {
    return getDB()->query(
        'SELECT f.id, f.parent_id, f.name, f.slug, f.sort_order,
                (SELECT COUNT(*) FROM media_library m WHERE m.folder_id = f.id) AS items_count
         FROM media_folders f
         ORDER BY parent_id IS NULL DESC, parent_id ASC, sort_order ASC, name ASC'
    )->fetchAll();
}

function mediaFoldersTree(): array {
    $rows = mediaFoldersAll();
    $byParent = [];
    foreach ($rows as $r) $byParent[(int) ($r['parent_id'] ?? 0)][] = $r;
    $build = function ($pid) use (&$build, $byParent) {
        $out = [];
        foreach ($byParent[$pid] ?? [] as $r) {
            $r['children'] = $build((int) $r['id']);
            $out[] = $r;
        }
        return $out;
    };
    return $build(0);
}

function mediaFolderGet(int $id): ?array {
    if ($id <= 0) return null;
    $st = getDB()->prepare('SELECT * FROM media_folders WHERE id = ?');
    $st->execute([$id]);
    return $st->fetch() ?: null;
}

function mediaFolderCreate(string $name, ?int $parentId = null): ?int {
    $name = trim($name);
    if ($name === '') return null;
    $slug = imageSlugify($name) ?: 'carpeta-' . substr(md5($name . microtime()), 0, 6);
    try {
        $st = getDB()->prepare('INSERT INTO media_folders (parent_id, name, slug) VALUES (?, ?, ?)');
        $st->execute([$parentId ?: null, mb_substr($name, 0, 120), mb_substr($slug, 0, 140)]);
        return (int) getDB()->lastInsertId();
    } catch (PDOException $e) {
        return null;
    }
}

function mediaFolderRename(int $id, string $name): bool {
    $name = trim($name);
    if ($id <= 0 || $name === '') return false;
    $st = getDB()->prepare('UPDATE media_folders SET name = ? WHERE id = ?');
    return $st->execute([mb_substr($name, 0, 120), $id]);
}

function mediaFolderDelete(int $id): bool {
    if ($id <= 0) return false;
    // Items quedan con folder_id NULL. Subcarpetas se borran por CASCADE.
    $st = getDB()->prepare('DELETE FROM media_folders WHERE id = ?');
    return $st->execute([$id]);
}

/* ========== Items ========== */

function mediaLibraryList(?int $folderId = null, int $limit = 200): array {
    if ($folderId === null) {
        $st = getDB()->prepare('SELECT * FROM media_library WHERE folder_id IS NULL ORDER BY sort_order, id DESC LIMIT ?');
        $st->bindValue(1, $limit, PDO::PARAM_INT);
    } else {
        $st = getDB()->prepare('SELECT * FROM media_library WHERE folder_id = ? ORDER BY sort_order, id DESC LIMIT ?');
        $st->bindValue(1, $folderId, PDO::PARAM_INT);
        $st->bindValue(2, $limit, PDO::PARAM_INT);
    }
    $st->execute();
    return $st->fetchAll();
}

function mediaLibraryAll(int $limit = 1000): array {
    $st = getDB()->prepare(
        'SELECT m.*, f.name AS folder_name FROM media_library m
         LEFT JOIN media_folders f ON f.id = m.folder_id
         ORDER BY m.created_at DESC LIMIT ?'
    );
    $st->bindValue(1, $limit, PDO::PARAM_INT);
    $st->execute();
    return $st->fetchAll();
}

function mediaLibraryGet(int $id): ?array {
    $st = getDB()->prepare('SELECT * FROM media_library WHERE id = ?');
    $st->execute([$id]);
    return $st->fetch() ?: null;
}

function mediaLibraryUpload(array $file, ?int $folderId = null, string $seoName = ''): array {
    $folder = $folderId ? mediaFolderGet($folderId) : null;
    $sub = $folder ? $folder['slug'] : 'general';
    $destAbs    = __DIR__ . '/../uploads/library/' . $sub;
    $destPublic = '/uploads/library/' . $sub;

    $res = imagePipelineProcess($file, $destAbs, $destPublic, 'm', $seoName);
    if (!$res['ok']) return $res;

    $st = getDB()->prepare(
        'INSERT INTO media_library (folder_id, file_path, thumb_path, path_md, path_sm, seo_name, mime, width, height, bytes, sort_order)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
    );
    $sort = (int) (getDB()->query('SELECT COALESCE(MAX(sort_order),0) + 1 FROM media_library')->fetchColumn() ?: 1);
    $st->execute([$folderId ?: null, $res['path'], $res['thumb'], $res['path_md'] ?? null, $res['path_sm'] ?? null,
                  $seoName ?: null, 'image/webp', $res['w'], $res['h'], $res['bytes'], $sort]);

    return ['ok' => true, 'id' => (int) getDB()->lastInsertId(), 'path' => $res['path']];
}

function mediaLibraryDelete(int $id): bool {
    $row = mediaLibraryGet($id);
    if (!$row) return false;
    foreach (['file_path', 'thumb_path', 'path_md', 'path_sm'] as $col) {
        if (!empty($row[$col])) imagePipelineDelete((string) $row[$col]);
    }
    return getDB()->prepare('DELETE FROM media_library WHERE id = ?')->execute([$id]);
}

function mediaLibraryUpdateAlt(int $id, string $alt, ?string $title = null): void {
    if ($title !== null) {
        $st = getDB()->prepare('UPDATE media_library SET alt = ?, title = ? WHERE id = ?');
        $st->execute([mb_substr($alt, 0, 240), mb_substr($title, 0, 200), $id]);
    } else {
        getDB()->prepare('UPDATE media_library SET alt = ? WHERE id = ?')->execute([mb_substr($alt, 0, 240), $id]);
    }
}

function mediaLibraryMove(array $ids, ?int $folderId): int {
    $ids = array_filter(array_map('intval', $ids));
    if (!$ids) return 0;
    $place = implode(',', array_fill(0, count($ids), '?'));
    $st = getDB()->prepare("UPDATE media_library SET folder_id = ? WHERE id IN ($place)");
    $params = [$folderId ?: null];
    foreach ($ids as $id) $params[] = $id;
    $st->execute($params);
    return $st->rowCount();
}

function mediaLibraryHealthStats(): array {
    return [
        'total'  => (int) getDB()->query('SELECT COUNT(*) FROM media_library')->fetchColumn(),
        'no_alt' => (int) getDB()->query("SELECT COUNT(*) FROM media_library WHERE alt = '' OR alt IS NULL")->fetchColumn(),
        'no_seo' => (int) getDB()->query("SELECT COUNT(*) FROM media_library WHERE seo_name IS NULL OR seo_name = ''")->fetchColumn(),
    ];
}
