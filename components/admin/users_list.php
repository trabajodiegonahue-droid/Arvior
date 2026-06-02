<?php
/** Requiere: $users, $user, $userFormError, $userFormEmail, $userFormName, $userSearch */
$selfId = (int) $user['id'];
$userFormError = $userFormError ?? '';
$userFormEmail = $userFormEmail ?? '';
$userFormName  = $userFormName  ?? '';
$userSearch    = $userSearch    ?? '';
?>
<header class="admin-header">
    <div>
        <h1>Usuarios</h1>
        <div class="admin-header__sub">Quién puede entrar al panel admin. Cualquier usuario logueado es administrador.</div>
    </div>
</header>

<?php if ($msg = flashGet('user_success')): ?>
    <div class="auth-alert auth-alert--success"><span><?= htmlspecialchars($msg) ?></span></div>
<?php endif; ?>
<?php if ($msg = flashGet('user_error')): ?>
    <div class="auth-alert auth-alert--error"><span><?= htmlspecialchars($msg) ?></span></div>
<?php endif; ?>

<div class="card" style="max-width:560px;">
    <h3 class="card__title">Nuevo usuario</h3>
    <?php if ($userFormError): ?>
        <div class="auth-alert auth-alert--error"><span><?= htmlspecialchars($userFormError) ?></span></div>
    <?php endif; ?>
    <form method="post" data-user-form>
        <input type="hidden" name="action" value="create_user">
        <input type="hidden" name="csrf" value="<?= csrfToken() ?>">
        <p class="form__field"><label>Nombre <span class="text-muted" style="font-weight:400;">(opcional)</span>
            <input type="text" name="name" maxlength="120" value="<?= htmlspecialchars($userFormName) ?>" placeholder="Nombre y apellido">
        </label></p>
        <p class="form__field"><label>Email
            <input type="email" name="email" required value="<?= htmlspecialchars($userFormEmail) ?>" placeholder="otro@dominio.com">
        </label></p>
        <p class="form__field"><label>Contraseña inicial
            <span style="display:flex;gap:.4rem;align-items:stretch;">
                <input type="text" name="password" required minlength="8" placeholder="Mínimo 8 caracteres" autocomplete="off" data-pw-input style="flex:1;">
                <button type="button" class="btn btn--ghost" data-pw-generate title="Generar contraseña fuerte">Generar</button>
            </span>
            <small class="text-muted" style="font-size:.78rem;">Comunicásela al usuario; podrá cambiarla en Mi cuenta.</small>
        </label></p>
        <p class="form__field" style="display:flex;gap:.5rem;align-items:center;">
            <label style="display:flex;gap:.4rem;align-items:center;margin:0;">
                <input type="checkbox" name="must_change_password" value="1" checked>
                <span>Pedirle que cambie la contraseña al iniciar sesión</span>
            </label>
        </p>
        <p style="margin:0;"><button type="submit" class="btn">Crear usuario</button></p>
    </form>
</div>

<form method="get" style="margin:1.5rem 0 .5rem;display:flex;gap:.4rem;max-width:420px;">
    <input type="hidden" name="view" value="users">
    <input type="search" name="q" value="<?= htmlspecialchars($userSearch) ?>" placeholder="Buscar por nombre o email" style="flex:1;">
    <button type="submit" class="btn btn--ghost">Buscar</button>
    <?php if ($userSearch !== ''): ?>
        <a href="/admin/?view=users" class="btn btn--ghost">Limpiar</a>
    <?php endif; ?>
</form>

<table class="table">
    <thead>
        <tr>
            <th>Usuario</th>
            <th style="width:130px;">Estado</th>
            <th style="width:170px;">Último login</th>
            <th style="width:170px;">Creado</th>
            <th style="width:90px;"></th>
        </tr>
    </thead>
    <tbody>
        <?php if (!$users): ?>
            <tr><td colspan="5" class="text-muted" style="text-align:center;padding:1.2rem;">Sin resultados.</td></tr>
        <?php endif; ?>
        <?php foreach ($users as $u): $isSelf = (int) $u['id'] === $selfId; ?>
            <tr>
                <td>
                    <strong><?= htmlspecialchars(userDisplayName($u)) ?></strong>
                    <?php if ($isSelf): ?> <span class="text-muted" style="font-size:.78rem;">(vos)</span><?php endif; ?>
                    <?php if (!empty($u['name'])): ?>
                        <div class="text-muted" style="font-size:.8rem;"><?= htmlspecialchars($u['email']) ?></div>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ((int) $u['is_active']): ?>
                        <span class="badge badge--qualified">activo</span>
                    <?php else: ?>
                        <span class="badge badge--closed">inactivo</span>
                    <?php endif; ?>
                    <?php if (!empty($u['must_change_password'])): ?>
                        <div style="margin-top:.3rem;"><span class="badge badge--new" title="Debe cambiar la contraseña al iniciar sesión">debe cambiar pw</span></div>
                    <?php endif; ?>
                </td>
                <td class="text-muted text-tabular" style="font-size:.86rem;">
                    <?= !empty($u['last_login_at']) ? htmlspecialchars($u['last_login_at']) : '—' ?>
                </td>
                <td class="text-muted text-tabular" style="font-size:.86rem;">
                    <?= htmlspecialchars($u['created_at']) ?>
                </td>
                <td><a href="/admin/?view=user&amp;id=<?= (int) $u['id'] ?>">Editar</a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
(function(){
    function rand(len){
        var a = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz23456789';
        var out = '';
        var arr = new Uint32Array(len);
        crypto.getRandomValues(arr);
        for (var i=0; i<len; i++) out += a[arr[i] % a.length];
        return out;
    }
    document.querySelectorAll('[data-pw-generate]').forEach(function(btn){
        btn.addEventListener('click', function(){
            var input = btn.closest('label, p, form').querySelector('[data-pw-input]');
            if (input) { input.value = rand(14); input.focus(); input.select(); }
        });
    });
})();
</script>
