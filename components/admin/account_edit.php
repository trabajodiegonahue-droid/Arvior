<?php
/** Requiere: $accountRec (cuenta) */
$flashOk  = flashGet('account_success');
$flashErr = flashGet('account_error');
$a = $accountRec;
$proto  = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host   = $_SERVER['HTTP_HOST'] ?? '';
$intakeUrl = $host ? ($proto . '://' . $host . '/intake.php') : '/intake.php';
$STATUSES = ['active' => 'Activa', 'paused' => 'Pausada', 'archived' => 'Archivada'];
?>
<header class="admin-header">
    <div>
        <div style="margin-bottom:.3rem;"><a href="/admin/?view=accounts" class="text-muted" style="font-size:.88rem;">← Volver a cuentas</a></div>
        <h1><?= htmlspecialchars($a['name']) ?> <span class="badge badge--<?= htmlspecialchars($a['status']) ?>" style="font-size:.7rem;vertical-align:middle;"><?= htmlspecialchars($a['status']) ?></span></h1>
        <div class="admin-header__sub">Cuenta #<?= (int) $a['id'] ?> · slug <?= htmlspecialchars($a['slug']) ?></div>
    </div>
    <div class="admin-header__actions">
        <a class="btn btn--secondary" href="/admin/?account=<?= (int) $a['id'] ?>">Ver sus leads</a>
    </div>
</header>

<?php if ($flashOk): ?><p class="alert alert--success"><?= htmlspecialchars($flashOk) ?></p><?php endif; ?>
<?php if ($flashErr): ?><p class="alert alert--error"><?= htmlspecialchars($flashErr) ?></p><?php endif; ?>

<section class="admin-section">
    <h2>Datos</h2>
    <form method="post">
        <input type="hidden" name="action" value="account_update">
        <input type="hidden" name="csrf" value="<?= csrfToken() ?>">
        <input type="hidden" name="id" value="<?= (int) $a['id'] ?>">
        <p class="form__field"><label>Nombre<br><input type="text" name="name" value="<?= htmlspecialchars($a['name']) ?>" required></label></p>
        <p class="form__field"><label>Plan<br><input type="text" name="plan" value="<?= htmlspecialchars($a['plan'] ?? '') ?>" placeholder="opcional"></label></p>
        <p class="form__submit" style="margin:0;"><button type="submit" class="btn">Guardar</button></p>
    </form>
</section>

<section class="admin-section">
    <h2>Token público (intake)</h2>
    <p class="text-muted" style="margin:0 0 .8rem;font-size:.88rem;">
        El formulario de la landing del cliente envía este token a <code><?= htmlspecialchars($intakeUrl) ?></code>.
        Es un secreto: no lo publiques fuera del formulario.
    </p>
    <p class="form__field">
        <input type="text" readonly value="<?= htmlspecialchars($a['public_token']) ?>" onclick="this.select()" style="width:100%;font-family:monospace;">
    </p>

    <details style="margin:.6rem 0 1rem;">
        <summary style="cursor:pointer;">Ver snippet de formulario HTML</summary>
        <pre style="white-space:pre-wrap;background:#0f172a;color:#e2e8f0;padding:1rem;border-radius:8px;overflow:auto;font-size:.82rem;">&lt;form method="post" action="<?= htmlspecialchars($intakeUrl) ?>"&gt;
  &lt;input type="hidden" name="public_token" value="<?= htmlspecialchars($a['public_token']) ?>"&gt;
  &lt;input type="hidden" name="form_started" value="TIMESTAMP_UNIX"&gt;
  &lt;input type="text" name="website" style="display:none" tabindex="-1" autocomplete="off"&gt;
  &lt;input name="name" required placeholder="Nombre"&gt;
  &lt;input name="email" type="email" required placeholder="Email"&gt;
  &lt;input name="phone" placeholder="Teléfono"&gt;
  &lt;textarea name="message" placeholder="Mensaje"&gt;&lt;/textarea&gt;
  &lt;button type="submit"&gt;Enviar&lt;/button&gt;
&lt;/form&gt;</pre>
        <p class="text-muted" style="font-size:.82rem;">Para integración por <code>fetch</code> cross-domain, agregá <code>format=json</code> y leé la respuesta JSON <code>{ ok, message }</code>.</p>
    </details>

    <form method="post" onsubmit="return confirm('¿Regenerar el token? La landing actual del cliente dejará de funcionar hasta que actualices el token allí.')" style="margin:0;">
        <input type="hidden" name="action" value="account_regenerate_token">
        <input type="hidden" name="csrf" value="<?= csrfToken() ?>">
        <input type="hidden" name="id" value="<?= (int) $a['id'] ?>">
        <button type="submit" class="btn btn--secondary">Regenerar token</button>
    </form>
</section>

<section class="admin-section">
    <h2>Estado</h2>
    <form method="post" class="inline-form" style="margin:0;">
        <input type="hidden" name="action" value="account_set_status">
        <input type="hidden" name="csrf" value="<?= csrfToken() ?>">
        <input type="hidden" name="id" value="<?= (int) $a['id'] ?>">
        <select name="status">
            <?php foreach ($STATUSES as $val => $label): ?>
                <option value="<?= $val ?>" <?= $a['status'] === $val ? 'selected' : '' ?>><?= $label ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn">Actualizar estado</button>
    </form>
    <p class="text-muted" style="margin:.6rem 0 0;font-size:.82rem;">Solo las cuentas <strong>activas</strong> aceptan leads en el intake.</p>
</section>
