<?php
/**
 * Field reutilizable para elegir/subir una imagen única, persistida como
 * string en un input de texto (image_path).
 *
 * Antes del include definir:
 *   $sifName  string   atributo `name` del input
 *   $sifValue string   valor actual (path)
 *
 * Opcional:
 *   $sifPlaceholder string  placeholder para el input texto
 *   $sifLabel       string  label arriba del field
 *   $sifId          string  id custom (default: auto)
 */

if (!isset($sifName)) return;
$sifValue       = $sifValue       ?? '';
$sifPlaceholder = $sifPlaceholder ?? '/uploads/library/...webp';
$sifLabel       = $sifLabel       ?? '';
$sifId          = $sifId          ?? 'sif_' . bin2hex(random_bytes(3));
?>
<div class="sif" data-sif>
    <?php if ($sifLabel !== ''): ?><label class="sif__label" for="<?= htmlspecialchars($sifId) ?>"><?= htmlspecialchars($sifLabel) ?></label><?php endif; ?>
    <div class="sif__row">
        <div class="sif__thumb<?= $sifValue ? '' : ' sif__thumb--empty' ?>" data-sif-thumb>
            <?php if ($sifValue): ?>
                <img src="<?= htmlspecialchars($sifValue) ?>" alt="" loading="lazy">
            <?php else: ?>
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
            <?php endif; ?>
        </div>
        <div class="sif__main">
            <input type="text" id="<?= htmlspecialchars($sifId) ?>" name="<?= htmlspecialchars($sifName) ?>" value="<?= htmlspecialchars($sifValue) ?>" placeholder="<?= htmlspecialchars($sifPlaceholder) ?>" data-sif-input>
            <div class="sif__actions">
                <button type="button" class="btn btn--ghost" data-sif-pick>Elegir de Medios</button>
                <label class="btn btn--ghost sif__upload-btn">
                    Subir nueva
                    <input type="file" accept="image/jpeg,image/png,image/webp" data-sif-upload hidden>
                </label>
                <?php if ($sifValue): ?>
                    <button type="button" class="btn sif__remove" data-sif-clear title="Quitar">×</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php if (empty($GLOBALS['__sif_assets_printed'])): $GLOBALS['__sif_assets_printed'] = true; ?>
<style>
.sif { margin:0; }
.sif__label { display:block; font-size:.82rem; color:#374151; margin:0 0 .35rem; font-weight:500; }
.sif__row { display:flex; gap:.7rem; align-items:flex-start; }
.sif__thumb { width:64px; height:64px; border:1px solid #e5e7eb; background:#f9fafb; flex-shrink:0; overflow:hidden; display:flex; align-items:center; justify-content:center; color:#9ca3af; border-radius:6px; position:relative; }
.sif__thumb img { width:100%; height:100%; object-fit:cover; display:block; }
.sif__main { flex:1; display:flex; flex-direction:column; gap:.4rem; min-width:0; }
.sif__main input[type="text"] { width:100%; padding:.5rem .65rem; border:1px solid #d1d5db; font-size:.86rem; font-family:inherit; background:#fff; border-radius:6px; }
.sif__main input[type="text"]:focus { outline:none; border-color:var(--color-text); }
.sif__actions { display:flex; gap:.4rem; flex-wrap:wrap; align-items:center; }
.sif__actions .btn { padding:.4rem .7rem; font-size:.78rem; }
.sif__upload-btn { display:inline-flex; align-items:center; cursor:pointer; }
.sif__remove { background:transparent; border:1px solid #fecaca; color:#a02a2a; min-width:30px; padding:.3rem .55rem; font-weight:500; }
.sif__remove:hover { background:#fee2e2; }
.sif--uploading .sif__thumb { opacity:.5; }

/* Modal picker */
.sifck-modal { position:fixed; inset:0; z-index:99999; display:flex; align-items:center; justify-content:center; padding:24px; }
.sifck-modal[hidden] { display:none; }
.sifck-backdrop { position:absolute; inset:0; background:rgba(0,0,0,.55); }
.sifck-panel { position:relative; background:#fff; max-width:1100px; width:100%; height:calc(100vh - 48px); max-height:900px; display:grid; grid-template-rows:auto auto minmax(0,1fr) auto; box-shadow:0 30px 80px rgba(0,0,0,.4); border-radius:10px; overflow:hidden; }
.sifck-header { padding:1rem 1.2rem; border-bottom:1px solid #e5e7eb; display:flex; align-items:center; justify-content:space-between; gap:1rem; }
.sifck-header strong { font-size:1.05rem; color:#111827; }
.sifck-close { background:none; border:0; font-size:1.8rem; line-height:1; cursor:pointer; color:#6b7280; padding:.2rem .5rem; }
.sifck-toolbar { padding:.8rem 1.2rem; border-bottom:1px solid #e5e7eb; display:flex; gap:.7rem; align-items:center; background:#f9fafb; flex-wrap:wrap; }
.sifck-search { position:relative; flex:1; display:flex; align-items:center; min-width:200px; }
.sifck-search input { width:100%; padding:.65rem .8rem; border:1px solid #d1d5db; border-radius:6px; font:inherit; font-size:.9rem; background:#fff; }
.sifck-search input:focus { outline:none; border-color:var(--color-text); }
.sifck-folder-select { padding:.65rem .9rem; border:1px solid #d1d5db; border-radius:6px; font:inherit; font-size:.9rem; background:#fff; cursor:pointer; min-width:200px; }
.sifck-grid { overflow-y:auto; padding:1rem; display:grid; grid-template-columns:repeat(auto-fill,minmax(170px,1fr)); gap:.7rem; background:#fafafa; align-content:start; }
.sifck-item { position:relative; cursor:pointer; border:2px solid transparent; border-radius:6px; overflow:hidden; background:#f3f4f6; aspect-ratio:1; display:flex; align-items:stretch; transition:border-color .15s; }
.sifck-item:hover { border-color:var(--color-border-strong); }
.sifck-item.is-selected { border-color:var(--color-text); box-shadow:var(--ring); }
.sifck-item img { width:100%; height:100%; object-fit:cover; display:block; }
.sifck-item__check { position:absolute; top:6px; right:6px; width:24px; height:24px; border-radius:50%; background:var(--color-text); color:var(--color-primary-text); display:none; align-items:center; justify-content:center; font-weight:600; font-size:.9rem; }
.sifck-item.is-selected .sifck-item__check { display:flex; }
.sifck-item__folder { position:absolute; bottom:0; left:0; right:0; font-size:.7rem; color:#fff; padding:.3rem .45rem; background:linear-gradient(to top,rgba(0,0,0,.7),rgba(0,0,0,0)); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.sifck-empty { grid-column:1/-1; color:#6b7280; padding:4rem 2rem; text-align:center; font-size:.95rem; }
.sifck-empty a { color:var(--color-text); text-decoration:underline; }
.sifck-footer { padding:.9rem 1.2rem; border-top:1px solid #e5e7eb; display:flex; align-items:center; justify-content:space-between; gap:1rem; background:#fff; flex-wrap:wrap; }
.sifck-selected { display:flex; align-items:center; gap:.7rem; flex:1; min-width:240px; font-size:.9rem; min-height:42px; color:#6b7280; }
.sifck-selected__thumb { width:42px; height:42px; object-fit:cover; border:1px solid #e5e7eb; border-radius:4px; }
.sifck-selected__info { display:flex; flex-direction:column; gap:.1rem; min-width:0; color:#111827; }
.sifck-selected__name { font-weight:500; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:36ch; }
.sifck-selected__meta { font-size:.76rem; color:#6b7280; }
.sifck-actions { display:flex; align-items:center; gap:.8rem; }
</style>
<?php endif; ?>

<?php if (empty($GLOBALS['__sif_modal_printed'])): $GLOBALS['__sif_modal_printed'] = true;
    $sifMedia = function_exists('mediaLibraryAll') ? mediaLibraryAll(500) : [];
    $sifJson = json_encode(array_map(fn($m) => [
        'id'     => (int) $m['id'],
        'thumb'  => $m['thumb_path'] ?: $m['file_path'],
        'path'   => $m['file_path'],
        'alt'    => $m['alt'] ?? '',
        'folder' => $m['folder_name'] ?? '',
        'w'      => (int) ($m['width'] ?? 0),
        'h'      => (int) ($m['height'] ?? 0),
        'kb'     => (int) round(((int) ($m['bytes'] ?? 0)) / 1024),
    ], $sifMedia), JSON_UNESCAPED_SLASHES);
    $sifFolders = [];
    foreach ($sifMedia as $m) {
        $f = trim((string) ($m['folder_name'] ?? ''));
        if ($f !== '' && !in_array($f, $sifFolders, true)) $sifFolders[] = $f;
    }
    sort($sifFolders);
?>
<div class="sifck-modal" id="sif-modal" hidden>
    <div class="sifck-backdrop"></div>
    <div class="sifck-panel">
        <header class="sifck-header">
            <div>
                <strong>Elegir imagen de Medios</strong>
                <small id="sif-count" style="margin-left:.6rem;font-size:.82rem;color:#6b7280;"><?= count($sifMedia) ?> imagen(es)</small>
            </div>
            <button type="button" class="sifck-close" aria-label="Cerrar">×</button>
        </header>
        <div class="sifck-toolbar">
            <div class="sifck-search">
                <input type="search" id="sif-search" placeholder="Buscar por nombre, alt o carpeta…" autocomplete="off">
            </div>
            <?php if (!empty($sifFolders)): ?>
                <select id="sif-folder" class="sifck-folder-select">
                    <option value="">Todas las carpetas</option>
                    <?php foreach ($sifFolders as $f): ?>
                        <option value="<?= htmlspecialchars($f) ?>"><?= htmlspecialchars($f) ?></option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>
        </div>
        <div class="sifck-grid" id="sif-grid"></div>
        <footer class="sifck-footer">
            <div class="sifck-selected" id="sif-selected">Haz clic en una imagen para seleccionarla.</div>
            <div class="sifck-actions">
                <a href="/admin/?view=media" target="_blank" style="color:#6b7280;font-size:.85rem;">Abrir Medios →</a>
                <button type="button" class="btn" id="sif-confirm" disabled>Usar imagen</button>
            </div>
        </footer>
    </div>
</div>

<script>
(function(){
    var MEDIA = <?= $sifJson ?: '[]' ?>;
    var modal = document.getElementById('sif-modal');
    var grid = document.getElementById('sif-grid');
    var search = document.getElementById('sif-search');
    var folderSel = document.getElementById('sif-folder');
    var countEl = document.getElementById('sif-count');
    var selectedEl = document.getElementById('sif-selected');
    var confirmBtn = document.getElementById('sif-confirm');
    var selected = null;
    var targetField = null;

    function escapeHtml(s){ return String(s||'').replace(/[&<>"']/g, function(c){ return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]; }); }
    function applyFilter(){
        var q = (search && search.value || '').trim().toLowerCase();
        var folder = folderSel ? folderSel.value : '';
        return MEDIA.filter(function(m){
            if (folder && m.folder !== folder) return false;
            if (!q) return true;
            return (m.alt || '').toLowerCase().indexOf(q) !== -1
                || (m.folder || '').toLowerCase().indexOf(q) !== -1
                || (m.path || '').toLowerCase().indexOf(q) !== -1;
        });
    }
    function render(){
        grid.innerHTML = '';
        var items = applyFilter();
        countEl.textContent = items.length + ' imagen(es)' + (items.length !== MEDIA.length ? ' (de ' + MEDIA.length + ')' : '');
        if (!items.length) {
            grid.innerHTML = '<div class="sifck-empty">'+(MEDIA.length ? 'Sin coincidencias.' : 'La Mediateca está vacía.')+' <a href="/admin/?view=media" target="_blank">Abrir Medios →</a></div>';
            return;
        }
        items.forEach(function(m){
            var d = document.createElement('div');
            d.className = 'sifck-item';
            if (selected && selected.id === m.id) d.classList.add('is-selected');
            d.innerHTML = '<img src="'+escapeHtml(m.thumb)+'" alt="'+escapeHtml(m.alt||'')+'" loading="lazy">'
                + '<span class="sifck-item__check">✓</span>'
                + (m.folder ? '<div class="sifck-item__folder">'+escapeHtml(m.folder)+'</div>' : '');
            d.addEventListener('click', function(){ select(m); });
            grid.appendChild(d);
        });
    }
    function select(m){
        selected = m;
        render();
        var name = (m.path || '').split('/').pop();
        selectedEl.innerHTML = '<img class="sifck-selected__thumb" src="'+escapeHtml(m.thumb)+'" alt="">'
            + '<div class="sifck-selected__info">'
            + '<span class="sifck-selected__name">'+escapeHtml(m.alt || name)+'</span>'
            + '<span class="sifck-selected__meta">'+(m.folder ? escapeHtml(m.folder)+' · ' : '')+(m.w ? m.w+'×'+m.h+'px' : '')+(m.kb ? ' · '+m.kb+' KB' : '')+'</span>'
            + '</div>';
        confirmBtn.disabled = false;
    }
    function open(field){
        targetField = field;
        if (modal.parentNode !== document.body) document.body.appendChild(modal);
        selected = null;
        if (search) search.value = '';
        if (folderSel) folderSel.value = '';
        selectedEl.textContent = 'Haz clic en una imagen para seleccionarla.';
        confirmBtn.disabled = true;
        render();
        modal.hidden = false;
        document.body.style.overflow = 'hidden';
        if (search) setTimeout(function(){ search.focus(); }, 50);
    }
    function close(){ modal.hidden = true; document.body.style.overflow = ''; targetField = null; }
    function applySelection(path){
        if (!targetField) return;
        var input = targetField.querySelector('[data-sif-input]');
        if (input) { input.value = path; input.dispatchEvent(new Event('change', {bubbles:true})); }
        updateThumb(targetField, path);
    }
    function updateThumb(field, path){
        var thumb = field.querySelector('[data-sif-thumb]');
        if (!thumb) return;
        if (path) {
            thumb.classList.remove('sif__thumb--empty');
            thumb.innerHTML = '<img src="' + escapeHtml(path) + '" alt="" loading="lazy">';
            var actions = field.querySelector('.sif__actions');
            if (actions && !field.querySelector('[data-sif-clear]')) {
                var rm = document.createElement('button');
                rm.type = 'button';
                rm.className = 'btn sif__remove';
                rm.setAttribute('data-sif-clear','');
                rm.title = 'Quitar';
                rm.textContent = '×';
                actions.appendChild(rm);
            }
        } else {
            thumb.classList.add('sif__thumb--empty');
            thumb.innerHTML = '<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>';
            var rm = field.querySelector('[data-sif-clear]');
            if (rm) rm.remove();
        }
    }
    function clearField(field){
        var input = field.querySelector('[data-sif-input]');
        if (input) { input.value = ''; input.dispatchEvent(new Event('change', {bubbles:true})); }
        updateThumb(field, '');
    }

    confirmBtn.addEventListener('click', function(){ if (!selected) return; applySelection(selected.path); close(); });
    modal.querySelector('.sifck-backdrop').addEventListener('click', close);
    modal.querySelector('.sifck-close').addEventListener('click', close);
    if (search) search.addEventListener('input', render);
    if (folderSel) folderSel.addEventListener('change', render);
    document.addEventListener('keydown', function(e){
        if (modal.hidden) return;
        if (e.key === 'Escape') close();
        else if (e.key === 'Enter' && selected && document.activeElement !== search) { applySelection(selected.path); close(); }
    });

    document.addEventListener('click', function(e){
        var pickBtn = e.target.closest('[data-sif-pick]');
        if (pickBtn) { var f = pickBtn.closest('[data-sif]'); if (f) open(f); return; }
        var clearBtn = e.target.closest('[data-sif-clear]');
        if (clearBtn) { var f = clearBtn.closest('[data-sif]'); if (f) clearField(f); return; }
    });

    document.addEventListener('change', function(e){
        var up = e.target.closest('[data-sif-upload]');
        if (!up) return;
        var file = up.files && up.files[0];
        if (!file) return;
        var field = up.closest('[data-sif]');
        if (!field) return;
        field.classList.add('sif--uploading');
        var fd = new FormData();
        fd.append('action', 'media_upload_inline');
        fd.append('csrf', <?= json_encode(csrfToken()) ?>);
        fd.append('file', file);
        fetch(window.location.pathname || '/admin/', { method:'POST', body: fd, credentials:'same-origin' })
            .then(function(r){ return r.json(); })
            .then(function(j){
                if (j && j.ok && j.path) {
                    var input = field.querySelector('[data-sif-input]');
                    if (input) { input.value = j.path; input.dispatchEvent(new Event('change', {bubbles:true})); }
                    updateThumb(field, j.path);
                    MEDIA.unshift({id: j.id||0, thumb: j.path, path: j.path, alt: '', folder: '', w:0, h:0, kb:0});
                } else {
                    alert('Error al subir: ' + (j && j.error ? j.error : 'desconocido'));
                }
            })
            .catch(function(err){ alert('Error al subir: ' + err.message); })
            .finally(function(){ field.classList.remove('sif--uploading'); up.value = ''; });
    });
})();
</script>
<?php endif; ?>
