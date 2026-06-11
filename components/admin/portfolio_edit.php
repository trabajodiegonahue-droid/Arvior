<?php
/** Requiere: $projectRec (array|null), $projectError (string) */
$pj    = $projectRec ?? [];
$isNew = empty($pj['id']);
$cats  = portfolioCategories();
$gallery = portfolioGallery($pj['gallery'] ?? null);
// 4 slots de galería: rellenamos con los existentes y dejamos vacíos los demás.
$gallerySlots = array_pad(array_slice($gallery, 0, 4), 4, '');
?>
<header class="admin-header">
    <div>
        <div style="margin-bottom:.3rem;"><a href="/admin/?view=portfolio" class="text-muted" style="font-size:.88rem;">← Volver al portafolio</a></div>
        <h1><?= $isNew ? 'Nuevo proyecto' : 'Editar proyecto' ?></h1>
        <?php if (!$isNew): ?>
            <div class="admin-header__sub">/proyectos/<?= htmlspecialchars($pj['slug'] ?? '') ?></div>
        <?php endif; ?>
    </div>
</header>

<?php if ($projectError): ?>
    <div class="auth-alert auth-alert--error"><span><?= htmlspecialchars($projectError) ?></span></div>
<?php endif; ?>

<form method="post">
    <input type="hidden" name="action" value="save_project">
    <input type="hidden" name="csrf" value="<?= csrfToken() ?>">
    <input type="hidden" name="id" value="<?= (int) ($pj['id'] ?? 0) ?>">

    <div class="card">
        <h3 class="card__title">Lo principal</h3>

        <p class="form__field"><label>Título del proyecto
            <input name="title" value="<?= htmlspecialchars($pj['title'] ?? '') ?>" required placeholder="Sitio web para Clínica Dental Sonríe">
        </label></p>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
            <p class="form__field"><label>Rubro / tipo
                <select name="category">
                    <?php foreach ($cats as $key => $label): ?>
                        <option value="<?= htmlspecialchars($key) ?>" <?= ($pj['category'] ?? 'corporativo') === $key ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
                    <?php endforeach; ?>
                </select>
            </label></p>

            <p class="form__field"><label>Slug <small class="text-muted">(URL: /proyectos/slug · vacío = automático)</small>
                <input name="slug" value="<?= htmlspecialchars($pj['slug'] ?? '') ?>" pattern="[a-z0-9-]*" placeholder="clinica-sonrie">
            </label></p>
        </div>

        <p class="form__field"><label>Cliente <small class="text-muted">(opcional)</small>
            <input name="client_name" value="<?= htmlspecialchars($pj['client_name'] ?? '') ?>" placeholder="Clínica Dental Sonríe">
        </label></p>

        <p class="form__field" style="margin:0;"><label>Bajada corta <small class="text-muted">(aparece en la tarjeta · ~1 línea)</small>
            <input name="summary" maxlength="300" value="<?= htmlspecialchars($pj['summary'] ?? '') ?>" placeholder="Sitio corporativo con agenda de horas y formulario de contacto.">
        </label></p>
    </div>

    <div class="card">
        <h3 class="card__title">Imágenes</h3>

        <div style="margin-bottom:1.2rem;">
            <?php
            $sifName  = 'cover_image';
            $sifValue = $pj['cover_image'] ?? '';
            $sifLabel = 'Imagen de portada (captura del sitio)';
            require __DIR__ . '/_single_image_field.php';
            ?>
        </div>

        <label class="sif__label">Galería <small class="text-muted">(opcional · hasta 4 imágenes adicionales)</small></label>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
            <?php foreach ($gallerySlots as $gi => $gval): ?>
                <?php
                $sifName  = 'gallery[]';
                $sifValue = $gval;
                $sifLabel = '';
                $sifId    = 'gallery_' . $gi;
                require __DIR__ . '/_single_image_field.php';
                ?>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="card">
        <h3 class="card__title">Detalle (página del proyecto)</h3>

        <p class="form__field"><label>Descripción <small class="text-muted">(qué pedía el cliente y qué hicimos · saltos de línea se respetan)</small>
            <textarea name="description" rows="8"><?= htmlspecialchars($pj['description'] ?? '') ?></textarea>
        </label></p>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
            <p class="form__field"><label>Resultado destacado <small class="text-muted">(opcional)</small>
                <input name="result" maxlength="255" value="<?= htmlspecialchars($pj['result'] ?? '') ?>" placeholder="Más consultas desde el celular">
            </label></p>

            <p class="form__field"><label>Link al sitio en vivo <small class="text-muted">(opcional)</small>
                <input name="live_url" type="url" maxlength="500" value="<?= htmlspecialchars($pj['live_url'] ?? '') ?>" placeholder="https://clinicasonrie.cl">
            </label></p>
        </div>
    </div>

    <div class="card">
        <h3 class="card__title">Publicación</h3>

        <p class="form__field"><label>Orden <small class="text-muted">(menor aparece primero)</small>
            <input name="sort_order" type="number" value="<?= (int) ($pj['sort_order'] ?? 0) ?>" style="max-width:120px;">
        </label></p>

        <p class="form__field">
            <label style="display:flex;align-items:center;gap:.5rem;">
                <input type="checkbox" name="is_featured" value="1" style="width:auto;" <?= !empty($pj['is_featured']) ? 'checked' : '' ?>>
                <span>Destacado</span>
            </label>
        </p>

        <p class="form__field" style="margin:0;">
            <label style="display:flex;align-items:center;gap:.5rem;">
                <input type="checkbox" name="is_published" value="1" style="width:auto;" <?= !empty($pj['is_published']) || $isNew ? 'checked' : '' ?>>
                <span>Publicado (visible en /proyectos)</span>
            </label>
        </p>
    </div>

    <div style="margin-top:1.5rem;display:flex;gap:.5rem;justify-content:space-between;align-items:center;flex-wrap:wrap;">
        <button type="submit" class="btn">Guardar</button>
        <?php if (!$isNew): ?>
            <button type="submit" name="action" value="delete_project" class="btn btn--danger"
                onclick="return confirm('¿Eliminar este proyecto?')">Eliminar proyecto</button>
        <?php endif; ?>
    </div>
</form>
