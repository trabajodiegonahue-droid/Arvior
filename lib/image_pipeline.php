<?php
/**
 * Pipeline de optimización de imágenes para uploads del panel.
 *
 * - Acepta JPG/PNG/WebP de hasta IMG_MAX_BYTES.
 * - Reescala si el lado mayor supera IMG_MAX_DIM.
 * - Convierte todo a WebP (calidad IMG_QUALITY).
 * - Genera 3 tamaños: lg (1920), md (1024), sm (480) para srcset responsive.
 *
 * Requiere extensión GD con soporte WebP.
 */

const IMG_MAX_BYTES = 10 * 1024 * 1024; // 10MB de entrada
const IMG_MAX_DIM   = 1920;             // lg
const IMG_MD_DIM    = 1024;             // md
const IMG_SM_DIM    = 480;              // sm (también usado como thumb del panel)
const IMG_QUALITY   = 82;

/**
 * Procesa una imagen subida. Genera 3 tamaños WebP (sm/md/lg).
 *
 * @param array  $file          $_FILES[...]
 * @param string $destDirAbs    filesystem path donde escribir
 * @param string $destDirPublic ruta pública (ej: /uploads/library/general)
 * @param string $namePrefix    prefijo del archivo (si no hay slug SEO)
 * @param string $seoSlug       opcional: slug derivado del alt/título
 * @return array ['ok','path','thumb','path_md','path_sm','w','h','bytes','error']
 */
function imagePipelineProcess(array $file, string $destDirAbs, string $destDirPublic, string $namePrefix, string $seoSlug = ''): array {
    if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['ok' => false, 'error' => 'Subida inválida.'];
    }
    if (($file['size'] ?? 0) > IMG_MAX_BYTES) {
        return ['ok' => false, 'error' => 'Imagen supera 10MB.'];
    }
    if (!extension_loaded('gd')) {
        return ['ok' => false, 'error' => 'Servidor sin extensión GD.'];
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime  = $finfo->file($file['tmp_name']) ?: '';
    $loaders = [
        'image/jpeg' => fn($p) => @imagecreatefromjpeg($p),
        'image/png'  => fn($p) => @imagecreatefrompng($p),
        'image/webp' => fn($p) => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($p) : false,
    ];
    if (!isset($loaders[$mime])) {
        return ['ok' => false, 'error' => 'Formato no permitido (usá JPG, PNG o WebP).'];
    }

    $src = $loaders[$mime]($file['tmp_name']);
    if (!$src) {
        return ['ok' => false, 'error' => 'No se pudo leer la imagen.'];
    }

    if (!is_dir($destDirAbs)) {
        @mkdir($destDirAbs, 0755, true);
    }

    $slugClean = imageSlugify($seoSlug);
    $hash = substr(bin2hex(random_bytes(3)), 0, 6);
    $base = $slugClean !== ''
        ? $slugClean . '-' . $hash
        : $namePrefix . '-' . bin2hex(random_bytes(5));

    $lgRel = $destDirPublic . '/' . $base . '.webp';
    $lgAbs = $destDirAbs    . '/' . $base . '.webp';
    $mdRel = $destDirPublic . '/' . $base . '-1024.webp';
    $mdAbs = $destDirAbs    . '/' . $base . '-1024.webp';
    $smRel = $destDirPublic . '/' . $base . '-480.webp';
    $smAbs = $destDirAbs    . '/' . $base . '-480.webp';

    [$mainW, $mainH] = imagePipelineResize($src, IMG_MAX_DIM, $lgAbs);
    imagePipelineResize($src, IMG_MD_DIM, $mdAbs);
    imagePipelineResize($src, IMG_SM_DIM, $smAbs);

    imagedestroy($src);

    if (!file_exists($lgAbs)) {
        return ['ok' => false, 'error' => 'No se pudo guardar la imagen procesada.'];
    }

    return [
        'ok'      => true,
        'path'    => $lgRel,
        'path_md' => file_exists($mdAbs) ? $mdRel : $lgRel,
        'path_sm' => file_exists($smAbs) ? $smRel : $lgRel,
        'thumb'   => file_exists($smAbs) ? $smRel : $lgRel,
        'w'       => $mainW,
        'h'       => $mainH,
        'bytes'   => filesize($lgAbs) ?: null,
    ];
}

/**
 * Convierte un texto en slug ASCII URL-safe (sin acentos, lowercase, guiones).
 */
function imageSlugify(string $text, int $maxLen = 80): string {
    $text = trim($text);
    if ($text === '') return '';
    if (function_exists('iconv')) {
        $tr = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
        if ($tr !== false) $text = $tr;
    }
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    $text = trim($text, '-');
    return mb_substr($text, 0, $maxLen);
}

/**
 * Reescala la imagen si supera $maxDim en su lado mayor y la guarda como WebP.
 * Devuelve [width, height] del resultado.
 */
function imagePipelineResize($src, int $maxDim, string $outAbs): array {
    $w = imagesx($src);
    $h = imagesy($src);
    $scale = min(1.0, $maxDim / max($w, $h));
    $nw = (int) round($w * $scale);
    $nh = (int) round($h * $scale);

    if ($scale < 1.0) {
        $dst = imagecreatetruecolor($nw, $nh);
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
        imagefilledrectangle($dst, 0, 0, $nw, $nh, $transparent);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);
    } else {
        $dst = $src;
    }

    if (function_exists('imagewebp')) {
        @imagewebp($dst, $outAbs, IMG_QUALITY);
    } else {
        @imagejpeg($dst, $outAbs, IMG_QUALITY);
    }

    if ($dst !== $src) imagedestroy($dst);

    return [$nw, $nh];
}

function imagePipelineDelete(string $publicPath): void {
    if ($publicPath === '' || !str_starts_with($publicPath, '/uploads/')) return;
    $abs  = realpath(__DIR__ . '/..' . $publicPath);
    $root = realpath(__DIR__ . '/../uploads');
    if ($abs && $root && str_starts_with($abs, $root)) {
        @unlink($abs);
    }
}

/**
 * Helper para renderizar <picture> responsive en el front.
 * $img: array con keys file_path, path_md, path_sm, alt, width, height.
 */
function renderResponsiveImg(array $img, string $sizes = '(max-width: 600px) 100vw, (max-width: 1024px) 100vw, 1200px', bool $eager = false): string {
    $lg = (string) ($img['file_path'] ?? '');
    $md = (string) ($img['path_md']   ?? $lg);
    $sm = (string) ($img['path_sm']   ?? $lg);
    if ($lg === '') return '';

    $alt     = (string) ($img['alt'] ?? '');
    $title   = (string) ($img['title'] ?? '');
    $w       = (int)    ($img['width']  ?? 0);
    $h       = (int)    ($img['height'] ?? 0);
    $loading = $eager ? 'eager' : 'lazy';
    $fetch   = $eager ? 'high' : 'auto';

    $h_ = fn($s) => htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
    $srcset = trim(
        ($sm ? $h_($sm) . ' 480w, ' : '') .
        ($md ? $h_($md) . ' 1024w, ' : '') .
        $h_($lg) . ' 1920w'
    );

    $imgTag = '<img src="' . $h_($lg) . '" srcset="' . $srcset . '" sizes="' . $h_($sizes) . '"'
            . ' alt="' . $h_($alt) . '"'
            . ($title ? ' title="' . $h_($title) . '"' : '')
            . ($w && $h ? ' width="' . $w . '" height="' . $h . '"' : '')
            . ' loading="' . $loading . '" fetchpriority="' . $fetch . '" decoding="async">';

    return '<picture>' . $imgTag . '</picture>';
}
