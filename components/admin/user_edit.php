<?php
/** Requiere: $userRec, $user, $userPwError */
$selfId = (int) $user['id'];
$isSelf = (int) ($userRec['id'] ?? 0) === $selfId;
$userPwError = $userPwError ?? '';
$displayName = userDisplayName($userRec);
?>
<header class="admin-header">
    <div>
        <div style="margin-bottom:.3rem;"><a href="/admin/?view=users" class="text-muted" style="font-size:.88rem;">← Volver a usuarios</a></div>
        <h1><?= htmlspecialchars($displayName) ?></h1>
        <div class="admin-header__sub">
            <?php if ((int) $userRec['is_active']): ?>
                <span class="badge badge--qualified">activo</span>
            <?php else: ?>
                <span class="badge badge--closed">inactivo</span>
            <?php endif; ?>
            <?php if (!empty($userRec['must_change_password'])): ?>
                <span class="badge badge--new" title="Debe cambiar la contraseña al iniciar sesión">debe cambiar pw</span>
            <?php endif; ?>
            <?php if ($isSelf): ?>
                · <span class="text-muted">esta cuenta</span>
            <?php endif; ?>
        </div>
    </div>
</header>

<?php if ($msg = flashGet('user_success')): ?>
    <div class="auth-alert auth-alert--success"><span><?= htmlspecialchars($msg) ?></span></div>
<?php endif; ?>
<?php if ($msg = flashGet('user_error')): ?>
    <div class="auth-alert auth-alert--error"><span><?= htmlspecialchars($msg) ?></span></div>
<?php endif; ?>

<div class="card" style="max-width:480px;">
    <h3 class="card__title">Perfil</h3>
    <form method="post">
        <input type="hidden" name="action" value="update_user_profile">
        <input type="hidden" name="csrf" value="<?= csrfToken() ?>">
        <input type="hidden" name="id" value="<?= (int) $userRec['id'] ?>">
        <p class="form__field"><label>Nombre
            <input type="text" name="name" maxlength="120" value="<?= htmlspecialchars((string) ($userRec['name'] ?? '')) ?>" placeholder="Nombre y apellido">
        </label></p>
        <p class="form__field"><label>Email
            <input type="email" name="email" required value="<?= htmlspecialchars($userRec['email']) ?>">
        </label></p>
        <p style="margin:0;"><button type="submit" class="btn">Guardar cambios</button></p>
    </form>
</div>

<div class="card" style="max-width:480px;">
    <h3 class="card__title">Resetear contraseña</h3>
    <p class="text-muted" style="margin:0 0 1rem;font-size:.88rem;">
        <?php if ($isSelf): ?>
            Para cambiar tu propia contraseña con verificación, andá a <a href="/admin/?view=account">Mi cuenta</a>.
        <?php else: ?>
            Definí una nueva contraseña. Se le pedirá que la cambie al iniciar sesión.
        <?php endif; ?>
    </p>

    <?php if ($userPwError): ?>
        <div class="auth-alert auth-alert--error"><span><?= htmlspecialchars($userPwError) ?></span></div>
    <?php endif; ?>

    <?php if (!$isSelf): ?>
    <form method="post" data-user-form>
        <input type="hidden" name="action" value="reset_user_password">
        <input type="hidden" name="csrf" value="<?= csrfToken() ?>">
        <input type="hidden" name="id" value="<?= (int) $userRec['id'] ?>">
        <p class="form__field"><label>Nueva contraseña
            <span style="display:flex;gap:.4rem;align-items:stretch;">
                <input type="text" name="new_password" required minlength="8" placeholder="Mínimo 8 caracteres" autocomplete="off" data-pw-input style="flex:1;">
                <button type="button" class="btn btn--ghost" data-pw-generate>Generar</button>
            </span>
        </label></p>
        <p style="margin:0;"><button type="submit" class="btn">Asignar contraseña</button></p>
    </form>
    <?php endif; ?>
</div>

<?php if (!$isSelf): ?>
    <div class="card" style="max-width:480px;">
        <h3 class="card__title">Acceso</h3>
        <p class="text-muted" style="margin:0 0 1rem;font-size:.88rem;">
            <?php if ((int) $userRec['is_active']): ?>
                Desactivar el usuario impide que pueda iniciar sesión. Los datos se mantienen.
            <?php else: ?>
                Reactivá al usuario para que pueda volver a entrar al panel.
            <?php endif; ?>
        </p>
        <form method="post" style="margin:0;">
            <input type="hidden" name="action" value="toggle_user_active">
            <input type="hidden" name="csrf" value="<?= csrfToken() ?>">
            <input type="hidden" name="id" value="<?= (int) $userRec['id'] ?>">
            <button type="submit" class="btn btn--ghost">
                <?= (int) $userRec['is_active'] ? 'Desactivar usuario' : 'Reactivar usuario' ?>
            </button>
        </form>
    </div>

    <div class="card" style="max-width:480px;border-color:#fecaca;">
        <h3 class="card__title" style="color:#991b1b;">Eliminar</h3>
        <p class="text-muted" style="margin:0 0 1rem;font-size:.88rem;">
            Borra el usuario de forma permanente. No se puede deshacer.
        </p>
        <form method="post" style="margin:0;" onsubmit="return confirm('¿Eliminar definitivamente a <?= htmlspecialchars($userRec['email'], ENT_QUOTES) ?>?')">
            <input type="hidden" name="action" value="delete_user">
            <input type="hidden" name="csrf" value="<?= csrfToken() ?>">
            <input type="hidden" name="id" value="<?= (int) $userRec['id'] ?>">
            <button type="submit" class="btn btn--danger">Eliminar usuario</button>
        </form>
    </div>
<?php endif; ?>

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
