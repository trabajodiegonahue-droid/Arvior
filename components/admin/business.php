<?php
/** Requiere: $settings (BUSINESS_KEYS), $branches, $branchEdit (?array) */
$branches   = $branches   ?? [];
$branchEdit = $branchEdit ?? null;
$g = fn(string $k) => htmlspecialchars((string) ($settings[$k] ?? ''));
?>
<header class="admin-header">
    <div>
        <h1>Información del negocio</h1>
        <div class="admin-header__sub">Datos de contacto, dirección, sucursales y redes — se usan en el front, en mailing y en el SEO local.</div>
    </div>
</header>

<?php if ($msg = flashGet('business_success')): ?>
    <div class="auth-alert auth-alert--success"><span><?= htmlspecialchars($msg) ?></span></div>
<?php endif; ?>
<?php if ($msg = flashGet('business_error')): ?>
    <div class="auth-alert auth-alert--error"><span><?= htmlspecialchars($msg) ?></span></div>
<?php endif; ?>

<form method="post">
    <input type="hidden" name="action" value="save_business">
    <input type="hidden" name="csrf" value="<?= csrfToken() ?>">

    <div class="card">
        <h3 class="card__title">Identidad</h3>
        <p class="form__field"><label>Razón social / nombre legal
            <input type="text" name="b[business_legal_name]" value="<?= $g('business_legal_name') ?>" placeholder="Acme S.A.">
        </label></p>
        <p class="form__field"><label>Tagline / slogan
            <input type="text" name="b[business_tagline]" value="<?= $g('business_tagline') ?>" placeholder="Soluciones que rinden">
        </label></p>
        <p class="form__field" style="margin:0;"><label>Descripción corta
            <textarea name="b[business_description]" rows="3" placeholder="1-2 líneas sobre el negocio (también usadas para SEO)."><?= $g('business_description') ?></textarea>
        </label></p>
    </div>

    <div class="card">
        <h3 class="card__title">Contacto</h3>
        <p class="form__field"><label>Email
            <input type="email" name="b[business_email]" value="<?= $g('business_email') ?>" placeholder="contacto@tudominio.com">
        </label></p>
        <p class="form__field"><label>Teléfono
            <input type="text" name="b[business_phone]" value="<?= $g('business_phone') ?>" placeholder="+54 11 5555-5555">
        </label></p>
        <p class="form__field"><label>WhatsApp <span class="text-muted" style="font-weight:400;">(número en formato internacional, ej. 5491155555555)</span>
            <input type="text" name="b[business_whatsapp]" value="<?= $g('business_whatsapp') ?>" placeholder="5491155555555">
        </label></p>
        <p class="form__field" style="margin:0;"><label>Texto pre-cargado del WhatsApp
            <input type="text" name="b[business_whatsapp_text]" value="<?= $g('business_whatsapp_text') ?>" placeholder="Hola, vengo desde la web.">
        </label></p>
    </div>

    <div class="card">
        <h3 class="card__title">Dirección principal</h3>
        <p class="form__field"><label>Dirección
            <input type="text" name="b[business_address]" value="<?= $g('business_address') ?>" placeholder="Av. Siempreviva 742">
        </label></p>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:.8rem;">
            <p class="form__field"><label>Ciudad
                <input type="text" name="b[business_city]" value="<?= $g('business_city') ?>">
            </label></p>
            <p class="form__field"><label>Provincia / Región
                <input type="text" name="b[business_region]" value="<?= $g('business_region') ?>">
            </label></p>
            <p class="form__field"><label>País
                <input type="text" name="b[business_country]" value="<?= $g('business_country') ?>">
            </label></p>
            <p class="form__field"><label>Código postal
                <input type="text" name="b[business_postal_code]" value="<?= $g('business_postal_code') ?>">
            </label></p>
        </div>
        <p class="form__field"><label>URL de Google Maps
            <input type="url" name="b[business_maps_url]" value="<?= $g('business_maps_url') ?>" placeholder="https://maps.app.goo.gl/...">
        </label></p>
        <p class="form__field" style="margin:0;"><label>Horarios de atención
            <input type="text" name="b[business_hours]" value="<?= $g('business_hours') ?>" placeholder="Lun-Vie 9-18h · Sáb 10-13h">
        </label></p>
    </div>

    <div class="card">
        <h3 class="card__title">Redes sociales</h3>
        <p class="text-muted" style="margin:0 0 1rem;font-size:.88rem;">Pega la URL completa de cada perfil. Las que dejes vacías no se mostrarán.</p>
        <?php foreach (BUSINESS_SOCIAL_KEYS as $key => $label): ?>
            <p class="form__field"><label><?= htmlspecialchars($label) ?>
                <input type="url" name="b[<?= $key ?>]" value="<?= $g($key) ?>" placeholder="https://...">
            </label></p>
        <?php endforeach; ?>
    </div>

    <div class="card">
        <h3 class="card__title">SEO</h3>
        <p class="form__field" style="margin:0;">
            <label style="display:flex;gap:.5rem;align-items:center;">
                <input type="checkbox" name="b[business_seo_jsonld]" value="1" style="width:auto;" <?= ($settings['business_seo_jsonld'] ?? '1') === '1' ? 'checked' : '' ?>>
                <span>Inyectar marcado <code>LocalBusiness</code> (JSON-LD) en el sitio público</span>
            </label>
        </p>
    </div>

    <p style="margin-top:1.5rem;"><button type="submit" class="btn">Guardar información</button></p>
</form>

<div class="card">
    <h3 class="card__title">Sucursales</h3>
    <p class="text-muted" style="margin:0 0 1rem;font-size:.88rem;">Locales o puntos de atención adicionales. Se listan en orden por "Orden".</p>

    <?php if ($branches): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Ciudad</th>
                    <th>Teléfono</th>
                    <th style="width:90px;">Estado</th>
                    <th style="width:70px;">Orden</th>
                    <th style="width:180px;"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($branches as $b): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($b['name']) ?></strong>
                            <?php if (!empty($b['address'])): ?><div class="text-muted" style="font-size:.8rem;"><?= htmlspecialchars($b['address']) ?></div><?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars((string) ($b['city'] ?? '')) ?></td>
                        <td class="text-tabular"><?= htmlspecialchars((string) ($b['phone'] ?? '—')) ?></td>
                        <td>
                            <?php if ((int) $b['is_active']): ?>
                                <span class="badge badge--qualified">activa</span>
                            <?php else: ?>
                                <span class="badge badge--closed">oculta</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-tabular"><?= (int) $b['sort_order'] ?></td>
                        <td style="display:flex;gap:.3rem;flex-wrap:wrap;">
                            <a href="/admin/?view=business&amp;branch=<?= (int) $b['id'] ?>#branch-form" class="btn btn--ghost" style="padding:.3rem .6rem;font-size:.8rem;">Editar</a>
                            <form method="post" style="margin:0;">
                                <input type="hidden" name="action" value="branch_toggle">
                                <input type="hidden" name="csrf" value="<?= csrfToken() ?>">
                                <input type="hidden" name="id" value="<?= (int) $b['id'] ?>">
                                <button type="submit" class="btn btn--ghost" style="padding:.3rem .6rem;font-size:.8rem;">
                                    <?= (int) $b['is_active'] ? 'Ocultar' : 'Mostrar' ?>
                                </button>
                            </form>
                            <form method="post" style="margin:0;" onsubmit="return confirm('¿Eliminar sucursal?')">
                                <input type="hidden" name="action" value="branch_delete">
                                <input type="hidden" name="csrf" value="<?= csrfToken() ?>">
                                <input type="hidden" name="id" value="<?= (int) $b['id'] ?>">
                                <button type="submit" class="btn btn--ghost" style="padding:.3rem .6rem;font-size:.8rem;color:#991b1b;">Borrar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-muted" style="margin:0 0 1rem;">Todavía no cargaste sucursales.</p>
    <?php endif; ?>

    <h4 id="branch-form" style="margin:1.5rem 0 .5rem;font-size:1rem;">
        <?= $branchEdit ? 'Editar sucursal: ' . htmlspecialchars($branchEdit['name']) : 'Nueva sucursal' ?>
    </h4>
    <?php if ($branchEdit): ?>
        <p style="margin:0 0 .8rem;"><a href="/admin/?view=business#branch-form" class="text-muted" style="font-size:.86rem;">+ Crear nueva en vez de editar</a></p>
    <?php endif; ?>
    <form method="post">
        <input type="hidden" name="action" value="branch_save">
        <input type="hidden" name="csrf" value="<?= csrfToken() ?>">
        <input type="hidden" name="id" value="<?= (int) ($branchEdit['id'] ?? 0) ?>">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:.8rem;">
            <p class="form__field" style="grid-column:1/-1;"><label>Nombre *
                <input type="text" name="br[name]" required value="<?= htmlspecialchars((string) ($branchEdit['name'] ?? '')) ?>" placeholder="Sucursal Centro">
            </label></p>
            <p class="form__field" style="grid-column:1/-1;"><label>Dirección
                <input type="text" name="br[address]" value="<?= htmlspecialchars((string) ($branchEdit['address'] ?? '')) ?>">
            </label></p>
            <p class="form__field"><label>Ciudad
                <input type="text" name="br[city]" value="<?= htmlspecialchars((string) ($branchEdit['city'] ?? '')) ?>">
            </label></p>
            <p class="form__field"><label>Provincia / Región
                <input type="text" name="br[region]" value="<?= htmlspecialchars((string) ($branchEdit['region'] ?? '')) ?>">
            </label></p>
            <p class="form__field"><label>País
                <input type="text" name="br[country]" value="<?= htmlspecialchars((string) ($branchEdit['country'] ?? '')) ?>">
            </label></p>
            <p class="form__field"><label>Código postal
                <input type="text" name="br[postal_code]" value="<?= htmlspecialchars((string) ($branchEdit['postal_code'] ?? '')) ?>">
            </label></p>
            <p class="form__field"><label>Teléfono
                <input type="text" name="br[phone]" value="<?= htmlspecialchars((string) ($branchEdit['phone'] ?? '')) ?>">
            </label></p>
            <p class="form__field"><label>WhatsApp
                <input type="text" name="br[whatsapp]" value="<?= htmlspecialchars((string) ($branchEdit['whatsapp'] ?? '')) ?>">
            </label></p>
            <p class="form__field"><label>Email
                <input type="email" name="br[email]" value="<?= htmlspecialchars((string) ($branchEdit['email'] ?? '')) ?>">
            </label></p>
            <p class="form__field"><label>Horarios
                <input type="text" name="br[hours]" value="<?= htmlspecialchars((string) ($branchEdit['hours'] ?? '')) ?>" placeholder="Lun-Vie 9-18h">
            </label></p>
            <p class="form__field" style="grid-column:1/-1;"><label>URL de Google Maps
                <input type="url" name="br[maps_url]" value="<?= htmlspecialchars((string) ($branchEdit['maps_url'] ?? '')) ?>">
            </label></p>
            <p class="form__field"><label>Orden
                <input type="number" name="br[sort_order]" value="<?= (int) ($branchEdit['sort_order'] ?? 0) ?>">
            </label></p>
            <p class="form__field" style="display:flex;align-items:end;">
                <label style="display:flex;gap:.5rem;align-items:center;margin:0;">
                    <input type="checkbox" name="br[is_active]" value="1" <?= (!$branchEdit || (int) $branchEdit['is_active']) ? 'checked' : '' ?>>
                    <span>Sucursal activa (visible en el sitio)</span>
                </label>
            </p>
        </div>
        <p style="margin:.8rem 0 0;">
            <button type="submit" class="btn"><?= $branchEdit ? 'Guardar cambios' : 'Crear sucursal' ?></button>
        </p>
    </form>
</div>
