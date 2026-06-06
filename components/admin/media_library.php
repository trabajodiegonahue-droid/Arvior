<?php
/** Central de Medios: carpetas + grid de imágenes. */
$folders   = mediaFoldersAll();
$folderId  = isset($_GET['folder']) && $_GET['folder'] !== '' ? (int) $_GET['folder'] : null;
$current   = $folderId ? mediaFolderGet($folderId) : null;
$search    = trim($_GET['q'] ?? '');

$itemsRaw = mediaLibraryList($folderId, 500);
if ($search !== '') {
    $needle = mb_strtolower($search);
    $itemsRaw = array_filter($itemsRaw, function ($m) use ($needle) {
        return str_contains(mb_strtolower($m['alt'] ?: ''), $needle)
            || str_contains(mb_strtolower($m['file_path']), $needle)
            || str_contains(mb_strtolower($m['title'] ?: ''), $needle);
    });
}
$items     = array_values($itemsRaw);
$totalAll  = (int) getDB()->query('SELECT COUNT(*) FROM media_library')->fetchColumn();
$totalSize = (int) getDB()->query('SELECT COALESCE(SUM(bytes),0) FROM media_library')->fetchColumn();
$health    = mediaLibraryHealthStats();

$folderTree = mediaFoldersTree();
$rootCount  = (int) getDB()->query('SELECT COUNT(*) FROM media_library WHERE folder_id IS NULL')->fetchColumn();

$renderTree = function ($nodes, int $depth = 0) use (&$renderTree, $folderId) {
    foreach ($nodes as $f):
        $isActive = $folderId === (int) $f['id'];
        $count = (int) ($f['items_count'] ?? 0);
        ?>
        <li>
            <a href="/admin/?view=media&folder=<?= (int) $f['id'] ?>" class="ml-folder<?= $isActive ? ' is-active' : '' ?>" style="padding-left:<?= 0.85 + $depth * 0.9 ?>rem;">
                <svg class="ml-folder__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                <span class="ml-folder__name"><?= htmlspecialchars($f['name']) ?></span>
                <?php if ($count > 0): ?><span class="ml-folder__count"><?= $count ?></span><?php endif; ?>
            </a>
            <?php if (!empty($f['children'])): ?>
                <ul class="ml-folders"><?php $renderTree($f['children'], $depth + 1); ?></ul>
            <?php endif; ?>
        </li>
    <?php endforeach;
};

$kb = fn(int $b) => $b < 1024 * 1024 ? round($b / 1024) . ' KB' : round($b / 1024 / 1024, 1) . ' MB';
?>

<header class="ml-hero">
    <div class="ml-hero__title">
        <h1>Central de Medios</h1>
        <p>Biblioteca global de imágenes — organizá, etiquetá y reusá en cualquier página del sitio.</p>
    </div>
    <div class="ml-hero__stats">
        <div class="ml-stat"><span class="ml-stat__num"><?= number_format($totalAll) ?></span><span class="ml-stat__lbl">imágenes</span></div>
        <div class="ml-stat"><span class="ml-stat__num"><?= count($folders) ?></span><span class="ml-stat__lbl">carpetas</span></div>
        <div class="ml-stat"><span class="ml-stat__num"><?= htmlspecialchars($kb($totalSize)) ?></span><span class="ml-stat__lbl">peso total</span></div>
        <?php if (($health['no_alt'] ?? 0) > 0): ?>
            <div class="ml-stat ml-stat--warn">
                <span class="ml-stat__num"><?= number_format($health['no_alt']) ?></span>
                <span class="ml-stat__lbl">sin alt SEO</span>
            </div>
        <?php endif; ?>
    </div>
</header>

<?php if ($msg = flashGet('media_msg')): ?>
    <div class="ml-toast ml-toast--ok">✓ <?= htmlspecialchars($msg) ?></div>
<?php endif; ?>
<?php if ($err = flashGet('media_err')): ?>
    <div class="ml-toast ml-toast--err">⚠ <?= htmlspecialchars($err) ?></div>
<?php endif; ?>

<div class="ml-shell">
    <aside class="ml-sidebar">
        <div class="ml-sidebar__head">
            <h3>Carpetas</h3>
            <button type="button" class="ml-iconbtn" id="ml-newfolder-btn" title="Nueva carpeta">
                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 5v14M5 12h14"/></svg>
            </button>
        </div>

        <form method="post" id="ml-newfolder" hidden class="ml-newfolder">
            <input type="hidden" name="action" value="media_folder_create">
            <input type="hidden" name="csrf"   value="<?= csrfToken() ?>">
            <input type="hidden" name="parent_id" value="<?= (int) ($folderId ?? 0) ?>">
            <input type="text" name="name" placeholder="Nombre de carpeta" required autocomplete="off">
            <div class="ml-newfolder__actions">
                <button type="button" class="btn btn--ghost" id="ml-newfolder-cancel">Cancelar</button>
                <button type="submit" class="btn">Crear</button>
            </div>
        </form>

        <ul class="ml-folders">
            <li>
                <a href="/admin/?view=media" class="ml-folder<?= $folderId === null ? ' is-active' : '' ?>">
                    <svg class="ml-folder__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h7a2 2 0 012 2z"/></svg>
                    <span class="ml-folder__name">Sin carpeta</span>
                    <?php if ($rootCount): ?><span class="ml-folder__count"><?= $rootCount ?></span><?php endif; ?>
                </a>
            </li>
            <?php $renderTree($folderTree); ?>
        </ul>

        <?php if ($current): ?>
            <div class="ml-sidebar__footer">
                <form method="post" onsubmit="return confirm('¿Eliminar la carpeta «<?= htmlspecialchars($current['name'], ENT_QUOTES) ?>»? Las imágenes quedan en «Sin carpeta».')">
                    <input type="hidden" name="action" value="media_folder_delete">
                    <input type="hidden" name="csrf"   value="<?= csrfToken() ?>">
                    <input type="hidden" name="id"     value="<?= (int) $current['id'] ?>">
                    <button type="submit" class="ml-deletebtn">
                        <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2m3 0v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6"/></svg>
                        Eliminar carpeta
                    </button>
                </form>
            </div>
        <?php endif; ?>
    </aside>

    <div class="ml-main">
        <div class="ml-toolbar">
            <div class="ml-breadcrumb">
                <a href="/admin/?view=media">Medios</a>
                <span class="ml-breadcrumb__sep">/</span>
                <strong><?= htmlspecialchars($current['name'] ?? 'Sin carpeta') ?></strong>
                <span class="ml-breadcrumb__count"><?= count($items) ?> ítem(s)</span>
            </div>
            <div class="ml-toolbar__right">
                <form method="get" class="ml-search">
                    <input type="hidden" name="view" value="media">
                    <?php if ($folderId): ?><input type="hidden" name="folder" value="<?= (int) $folderId ?>"><?php endif; ?>
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
                    <input type="search" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Buscar por nombre o alt…">
                </form>
            </div>
        </div>

        <form method="post" enctype="multipart/form-data" id="ml-upload-form" class="ml-dropzone">
            <input type="hidden" name="action" value="media_upload">
            <input type="hidden" name="csrf"   value="<?= csrfToken() ?>">
            <input type="hidden" name="folder_id" value="<?= (int) ($folderId ?? 0) ?>">
            <input type="file" name="files[]" id="ml-file-input" accept="image/jpeg,image/png,image/webp" multiple hidden>

            <div class="ml-dropzone__icon">
                <svg viewBox="0 0 24 24" width="44" height="44" fill="none" stroke="currentColor" stroke-width="1.4">
                    <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                    <polyline points="17 8 12 3 7 8"/>
                    <line x1="12" y1="3" x2="12" y2="15"/>
                </svg>
            </div>
            <div class="ml-dropzone__text">
                <strong>Arrastrá imágenes aquí</strong>
                <span>o <button type="button" class="ml-link" onclick="document.getElementById('ml-file-input').click()">elige desde tu equipo</button></span>
                <small>JPG · PNG · WebP · hasta 10MB cada una · se convierten a WebP optimizado<?= $current ? ' · destino: <strong>' . htmlspecialchars($current['name']) . '</strong>' : '' ?></small>
            </div>
            <div class="ml-dropzone__queue" id="ml-queue" hidden></div>
            <button type="submit" class="btn ml-dropzone__submit" id="ml-submit-btn" hidden>Subir <span id="ml-queue-count">0</span> imagen(es)</button>
        </form>

        <?php if (empty($items)): ?>
            <div class="ml-empty">
                <svg viewBox="0 0 24 24" width="56" height="56" fill="none" stroke="currentColor" stroke-width="1.2" opacity=".4"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                <h3><?= $search ? 'Sin resultados' : 'Carpeta vacía' ?></h3>
                <p><?= $search ? 'Prueba con otra búsqueda o limpia los filtros.' : 'Sube tu primera imagen arrastrándola al área de arriba.' ?></p>
                <?php if ($search): ?><a href="/admin/?view=media<?= $folderId ? '&folder=' . $folderId : '' ?>" class="btn btn--ghost">Limpiar búsqueda</a><?php endif; ?>
            </div>
        <?php else: ?>
            <div class="ml-grid">
                <?php foreach ($items as $m):
                    $missingAlt = empty($m['alt']);
                ?>
                    <article class="ml-card<?= $missingAlt ? ' ml-card--warn' : '' ?>">
                        <a class="ml-card__media" href="<?= htmlspecialchars($m['file_path']) ?>" target="_blank">
                            <img src="<?= htmlspecialchars($m['thumb_path'] ?: $m['file_path']) ?>" alt="<?= htmlspecialchars($m['alt']) ?>" loading="lazy">
                            <div class="ml-card__overlay">
                                <span><?= (int) $m['width'] ?>×<?= (int) $m['height'] ?></span>
                                <span><?= htmlspecialchars($kb((int) $m['bytes'])) ?></span>
                            </div>
                            <?php if ($missingAlt): ?>
                                <span class="ml-card__badge" title="Falta texto alternativo (importante para SEO)">!</span>
                            <?php endif; ?>
                        </a>

                        <div class="ml-card__body">
                            <form method="post" class="ml-card__alt">
                                <input type="hidden" name="action" value="media_update">
                                <input type="hidden" name="csrf"   value="<?= csrfToken() ?>">
                                <input type="hidden" name="id"     value="<?= (int) $m['id'] ?>">
                                <input type="text" name="alt" value="<?= htmlspecialchars($m['alt']) ?>" placeholder="Texto alternativo (SEO)" maxlength="240">
                            </form>

                            <div class="ml-card__url">
                                <input type="text" value="<?= htmlspecialchars($m['file_path']) ?>" readonly onfocus="this.select()">
                                <button type="button" class="ml-iconbtn" title="Copiar URL"
                                    onclick="navigator.clipboard.writeText(this.previousElementSibling.value);this.classList.add('is-ok');setTimeout(()=>this.classList.remove('is-ok'),1200)">
                                    <svg class="ml-iconbtn__copy" viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                                    <svg class="ml-iconbtn__check" viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.4"><polyline points="20 6 9 17 4 12"/></svg>
                                </button>
                            </div>

                            <div class="ml-card__actions">
                                <form method="post" class="ml-card__move">
                                    <input type="hidden" name="action" value="media_move">
                                    <input type="hidden" name="csrf"   value="<?= csrfToken() ?>">
                                    <input type="hidden" name="ids[]"  value="<?= (int) $m['id'] ?>">
                                    <select name="folder_id" onchange="this.form.submit()">
                                        <option value="">Mover a…</option>
                                        <option value="0">Sin carpeta</option>
                                        <?php foreach ($folders as $fo): if ((int) $fo['id'] === (int) $m['folder_id']) continue; ?>
                                            <option value="<?= (int) $fo['id'] ?>"><?= htmlspecialchars($fo['name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </form>
                                <button type="button" class="ml-card__save" onclick="this.closest('.ml-card').querySelector('.ml-card__alt').requestSubmit()">Guardar</button>
                                <form method="post" onsubmit="return confirm('¿Eliminar esta imagen?')" style="display:inline;">
                                    <input type="hidden" name="action" value="media_delete">
                                    <input type="hidden" name="csrf"   value="<?= csrfToken() ?>">
                                    <input type="hidden" name="id"     value="<?= (int) $m['id'] ?>">
                                    <button type="submit" class="ml-card__delete" title="Eliminar">
                                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2m3 0v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.ml-hero { display:flex; align-items:flex-end; justify-content:space-between; gap:2rem; padding:1.4rem 1.6rem; background:#fff; border:1px solid #e5e7eb; border-radius:12px; margin-bottom:1.4rem; flex-wrap:wrap; }
.ml-hero__title h1 { margin:0 0 .25rem; font-size:1.5rem; font-weight:600; }
.ml-hero__title p  { margin:0; color:#6b7280; font-size:.9rem; max-width:520px; }
.ml-hero__stats { display:flex; gap:1.5rem; }
.ml-stat { text-align:right; }
.ml-stat__num { display:block; font-size:1.5rem; font-weight:600; color:#111827; line-height:1.1; }
.ml-stat__lbl { font-size:.72rem; color:#6b7280; text-transform:uppercase; letter-spacing:.07em; }
.ml-stat--warn .ml-stat__num { color:#dc2626; }
.ml-stat--warn .ml-stat__lbl { color:#dc2626; font-weight:600; }

.ml-card--warn { border-color:#fecaca; }
.ml-card__badge { position:absolute; top:.5rem; right:.5rem; width:24px; height:24px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:.95rem; font-weight:700; color:#fff; background:#dc2626; box-shadow:0 2px 6px rgba(0,0,0,.2); cursor:help; }

.ml-toast { padding:.7rem 1rem; border-radius:8px; margin-bottom:1rem; font-size:.9rem; }
.ml-toast--ok  { background:#dcfce7; color:#166534; border:1px solid #bbf7d0; }
.ml-toast--err { background:#fee2e2; color:#991b1b; border:1px solid #fecaca; }

.ml-shell { display:grid; grid-template-columns:260px 1fr; gap:1.4rem; align-items:start; }
@media (max-width: 900px) { .ml-shell { grid-template-columns:1fr; } }

.ml-sidebar { background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:1.1rem .9rem 1rem; position:sticky; top:1rem; max-height:calc(100vh - 2rem); overflow-y:auto; display:flex; flex-direction:column; }
.ml-sidebar__head { display:flex; justify-content:space-between; align-items:center; margin-bottom:.9rem; }
.ml-sidebar__head h3 { margin:0; font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:#6b7280; }

.ml-iconbtn { width:28px; height:28px; padding:0; border:1px solid #e5e7eb; background:#fff; border-radius:8px; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; color:#374151; transition:background .15s, border-color .15s; }
.ml-iconbtn:hover { background:#f3f4f6; border-color:#d1d5db; }
.ml-iconbtn.is-ok { background:#dcfce7; border-color:#86efac; color:#166534; }
.ml-iconbtn.is-ok .ml-iconbtn__copy { display:none; }
.ml-iconbtn .ml-iconbtn__check { display:none; }
.ml-iconbtn.is-ok .ml-iconbtn__check { display:block; }

.ml-newfolder { padding:.7rem .65rem; background:#f9fafb; border:1px solid #e5e7eb; border-radius:8px; margin-bottom:.8rem; }
.ml-newfolder input[type=text] { width:100%; padding:.5rem .65rem; border:1px solid #d1d5db; border-radius:6px; font-size:.86rem; background:#fff; }
.ml-newfolder input[type=text]:focus { outline:none; border-color:var(--color-text); }
.ml-newfolder__actions { display:flex; gap:.4rem; margin-top:.5rem; justify-content:flex-end; }

.ml-folders { list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:2px; }
.ml-folders .ml-folders { margin:2px 0 4px .9rem; padding-left:.5rem; border-left:1px solid #e5e7eb; }
.ml-folder { display:flex; align-items:center; gap:.55rem; padding:.55rem .7rem; border-radius:7px; text-decoration:none; color:#111827; font-size:.88rem; transition:background .15s; position:relative; }
.ml-folder:hover { background:#f3f4f6; }
.ml-folder.is-active { background:var(--color-bg-hover); color:var(--color-text); font-weight:600; }
.ml-folder.is-active::before { content:''; position:absolute; left:0; top:6px; bottom:6px; width:3px; border-radius:2px; background:var(--color-text); }
.ml-folder__icon { flex:0 0 auto; width:16px; height:16px; color:#6b7280; opacity:.7; }
.ml-folder.is-active .ml-folder__icon { color:var(--color-text); opacity:1; }
.ml-folder__name { flex:1; min-width:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
.ml-folder__count { flex:0 0 auto; font-size:.7rem; font-weight:600; padding:.12rem .45rem; background:#e5e7eb; color:#374151; border-radius:99px; min-width:1.6rem; text-align:center; }
.ml-folder.is-active .ml-folder__count { background:var(--color-text); color:var(--color-primary-text); }

.ml-sidebar__footer { margin-top:auto; padding-top:.9rem; border-top:1px solid #e5e7eb; }
.ml-deletebtn { display:flex; align-items:center; justify-content:center; gap:.4rem; width:100%; padding:.55rem .7rem; background:#fff; border:1px solid #fecaca; color:#991b1b; font-size:.78rem; font-weight:500; cursor:pointer; border-radius:7px; }
.ml-deletebtn:hover { background:#fef2f2; border-color:#f87171; }

.ml-main { min-width:0; }
.ml-toolbar { display:flex; align-items:center; justify-content:space-between; gap:1rem; margin-bottom:1rem; flex-wrap:wrap; }
.ml-breadcrumb { display:flex; align-items:center; gap:.5rem; font-size:.92rem; flex-wrap:wrap; }
.ml-breadcrumb a { color:#6b7280; text-decoration:none; }
.ml-breadcrumb__sep { color:#9ca3af; }
.ml-breadcrumb__count { font-size:.75rem; color:#6b7280; padding:.15rem .55rem; background:#f3f4f6; border-radius:99px; }
.ml-search { display:flex; align-items:center; gap:.4rem; padding:.4rem .7rem; background:#fff; border:1px solid #e5e7eb; border-radius:8px; min-width:240px; }
.ml-search svg { color:#6b7280; }
.ml-search input { border:none; outline:none; width:100%; font-size:.86rem; background:transparent; }

.ml-dropzone { display:flex; flex-direction:column; align-items:center; justify-content:center; gap:.7rem; padding:2rem 1.5rem; border:2px dashed #d1d5db; border-radius:12px; background:#fff; transition:border-color .2s, background .2s; cursor:pointer; text-align:center; margin-bottom:1.4rem; }
.ml-dropzone:hover, .ml-dropzone.is-dragover { border-color:var(--color-text); background:var(--color-bg-alt); }
.ml-dropzone.is-dragover { border-style:solid; }
.ml-dropzone__icon { color:var(--color-text); opacity:.85; }
.ml-dropzone__text strong { display:block; font-size:1rem; color:#111827; margin-bottom:.2rem; }
.ml-dropzone__text span { font-size:.88rem; color:#6b7280; }
.ml-dropzone__text small { display:block; margin-top:.4rem; font-size:.76rem; color:#6b7280; }
.ml-link { background:none; border:none; color:var(--color-text); cursor:pointer; font:inherit; padding:0; text-decoration:underline; }
.ml-dropzone__queue { width:100%; max-width:560px; display:flex; flex-direction:column; gap:.3rem; margin-top:.6rem; }
.ml-queue-item { display:flex; align-items:center; gap:.5rem; padding:.4rem .6rem; background:#f3f4f6; border-radius:5px; font-size:.82rem; }
.ml-queue-item__name { flex:1; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
.ml-queue-item__size { color:#6b7280; font-size:.74rem; }

.ml-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(220px, 1fr)); gap:1rem; }
.ml-card { background:#fff; border:1px solid #e5e7eb; border-radius:10px; overflow:hidden; display:flex; flex-direction:column; transition:box-shadow .15s; }
.ml-card:hover { box-shadow:0 8px 20px rgba(0,0,0,.06); }
.ml-card__media { position:relative; display:block; aspect-ratio:4/3; background:#f3f4f6; overflow:hidden; }
.ml-card__media img { width:100%; height:100%; object-fit:cover; display:block; }
.ml-card__overlay { position:absolute; bottom:0; left:0; right:0; display:flex; justify-content:space-between; padding:.4rem .6rem; background:linear-gradient(180deg, transparent, rgba(0,0,0,.6)); color:#fff; font-size:.7rem; opacity:0; transition:opacity .2s; }
.ml-card:hover .ml-card__overlay { opacity:1; }
.ml-card__body { padding:.7rem .8rem .8rem; display:flex; flex-direction:column; gap:.45rem; flex:1; }
.ml-card__alt input { width:100%; padding:.4rem .55rem; border:1px solid #e5e7eb; border-radius:5px; font-size:.8rem; background:#fafafa; }
.ml-card__alt input:focus { background:#fff; border-color:var(--color-text); outline:none; }
.ml-card__url { display:flex; gap:.3rem; }
.ml-card__url input { flex:1; min-width:0; padding:.3rem .45rem; border:1px solid #e5e7eb; border-radius:4px; font-size:.7rem; font-family:monospace; background:#f9fafb; color:#6b7280; }
.ml-card__actions { display:flex; gap:.3rem; align-items:center; margin-top:.2rem; }
.ml-card__move { flex:1; }
.ml-card__move select { width:100%; padding:.35rem .4rem; border:1px solid #e5e7eb; border-radius:5px; font-size:.74rem; background:#fff; cursor:pointer; }
.ml-card__save { padding:.35rem .65rem; border:1px solid #e5e7eb; background:#fff; border-radius:5px; font-size:.74rem; cursor:pointer; }
.ml-card__save:hover { background:#f3f4f6; }
.ml-card__delete { width:28px; height:28px; padding:0; border:1px solid #fecaca; background:#fff; border-radius:5px; cursor:pointer; color:#991b1b; display:inline-flex; align-items:center; justify-content:center; }
.ml-card__delete:hover { background:#fee2e2; }

.ml-empty { padding:4rem 1.5rem; text-align:center; background:#fff; border:1px dashed #e5e7eb; border-radius:12px; color:#6b7280; }
.ml-empty h3 { margin:0 0 .3rem; font-size:1.05rem; color:#111827; font-weight:500; }
.ml-empty p { margin:0 0 1rem; font-size:.88rem; }
</style>

<script>
(function(){
    var btn = document.getElementById('ml-newfolder-btn');
    var form = document.getElementById('ml-newfolder');
    var cancel = document.getElementById('ml-newfolder-cancel');
    btn?.addEventListener('click', () => {
        form.hidden = !form.hidden;
        if (!form.hidden) form.querySelector('input[name=name]').focus();
    });
    cancel?.addEventListener('click', () => { form.hidden = true; });

    var dz = document.getElementById('ml-upload-form');
    var input = document.getElementById('ml-file-input');
    var queue = document.getElementById('ml-queue');
    var submitBtn = document.getElementById('ml-submit-btn');
    var counter = document.getElementById('ml-queue-count');
    if (!dz || !input) return;

    function fmtSize(b) { return b < 1024*1024 ? Math.round(b/1024)+' KB' : (b/1024/1024).toFixed(1)+' MB'; }
    function renderQueue() {
        if (!input.files.length) { queue.hidden = true; submitBtn.hidden = true; return; }
        queue.innerHTML = '';
        Array.from(input.files).forEach(f => {
            var div = document.createElement('div');
            div.className = 'ml-queue-item';
            div.innerHTML = '<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>'
                + '<span class="ml-queue-item__name">' + f.name + '</span>'
                + '<span class="ml-queue-item__size">' + fmtSize(f.size) + '</span>';
            queue.appendChild(div);
        });
        queue.hidden = false;
        submitBtn.hidden = false;
        counter.textContent = input.files.length;
    }

    ['dragenter','dragover'].forEach(ev => dz.addEventListener(ev, e => { e.preventDefault(); dz.classList.add('is-dragover'); }));
    ['dragleave','drop'].forEach(ev => dz.addEventListener(ev, e => { e.preventDefault(); dz.classList.remove('is-dragover'); }));
    dz.addEventListener('drop', e => {
        if (e.dataTransfer && e.dataTransfer.files) { input.files = e.dataTransfer.files; renderQueue(); }
    });
    dz.addEventListener('click', e => {
        if (e.target === dz || e.target.closest('.ml-dropzone__icon, .ml-dropzone__text strong')) input.click();
    });
    input.addEventListener('change', renderQueue);
})();
</script>
