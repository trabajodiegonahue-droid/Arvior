<?php
/** Requiere: $accounts (array de cuentas) */
$flashOk  = flashGet('account_success');
$flashErr = flashGet('account_error');
?>
<header class="admin-header">
    <div>
        <h1>Cuentas</h1>
        <div class="admin-header__sub">Clientes cuyas landings capturan leads hacia ARVIOR.</div>
    </div>
</header>

<?php if ($flashOk): ?><p class="alert alert--success"><?= htmlspecialchars($flashOk) ?></p><?php endif; ?>
<?php if ($flashErr): ?><p class="alert alert--error"><?= htmlspecialchars($flashErr) ?></p><?php endif; ?>

<section class="admin-section">
    <h2>Nueva cuenta</h2>
    <form method="post" class="inline-form" style="margin:0;">
        <input type="hidden" name="action" value="account_create">
        <input type="hidden" name="csrf" value="<?= csrfToken() ?>">
        <input type="text" name="name" placeholder="Nombre del cliente" required>
        <input type="text" name="plan" placeholder="Plan (opcional)">
        <button type="submit" class="btn">Crear cuenta</button>
    </form>
</section>

<?php if (empty($accounts)): ?>
    <div class="empty">
        <h3>No hay cuentas todavía</h3>
        <p>Crea la primera cuenta de cliente para empezar a recibir sus leads.</p>
    </div>
<?php else: ?>
    <table class="table">
        <thead>
            <tr>
                <th style="width:60px;">ID</th>
                <th>Nombre</th>
                <th>Slug</th>
                <th>Estado</th>
                <th>Plan</th>
                <th style="width:120px;"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($accounts as $a): ?>
                <tr>
                    <td class="text-muted text-tabular">#<?= (int) $a['id'] ?></td>
                    <td><strong><?= htmlspecialchars($a['name']) ?></strong></td>
                    <td class="text-muted"><?= htmlspecialchars($a['slug']) ?></td>
                    <td><span class="badge badge--<?= htmlspecialchars($a['status']) ?>"><?= htmlspecialchars($a['status']) ?></span></td>
                    <td class="text-muted"><?= htmlspecialchars($a['plan'] ?? '') ?: '—' ?></td>
                    <td>
                        <a href="/admin/?account=<?= (int) $a['id'] ?>">Ver leads</a>
                        &nbsp;·&nbsp;
                        <a href="/admin/?view=account_edit&id=<?= (int) $a['id'] ?>">Editar →</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
