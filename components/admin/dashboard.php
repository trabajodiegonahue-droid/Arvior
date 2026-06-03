<?php
/** Requiere: $stats, $leads, $search, $statusFilter, $accountFilter, $pendingFilter, $accounts, $accountsMap, $page, $totalPages, $totalLeads, $LEAD_STATUSES, $paginationUrl, $taskStats, $leadsStale */
$accountFilter = $accountFilter ?? 0;
$taskStats  = $taskStats  ?? ['overdue' => 0, 'today' => 0, 'pending' => 0, 'completed' => 0];
$leadsStale = $leadsStale ?? 0;
$pendingFilter = $pendingFilter ?? false;
$accounts      = $accounts ?? [];
$accountsMap   = $accountsMap ?? [];
$activeAccountName = $accountFilter > 0 ? ($accountsMap[$accountFilter] ?? ('#' . $accountFilter)) : '';
$flashOk  = flashGet('lead_success');
$flashErr = flashGet('lead_error');

// URL base de filtros (para los accesos rápidos del pipeline).
$filterUrl = function (array $extra = []) use ($accountFilter, $search): string {
    $params = array_filter(array_merge([
        'account' => $accountFilter ?: '', 'search' => $search,
    ], $extra), fn($v) => $v !== '' && $v !== null);
    return '/admin/?' . http_build_query($params);
};

// Formatea un datetime de próxima acción con marca de vencido.
$fmtNextAction = function (?string $at, ?string $note): string {
    if (empty($at) && empty($note)) return '<span class="text-muted">—</span>';
    $overdue = !empty($at) && strtotime($at) !== false && strtotime($at) <= time();
    $out = '';
    if (!empty($at)) {
        $cls = $overdue ? ' style="color:var(--color-danger);font-weight:600;"' : '';
        $out .= '<span' . $cls . '>' . htmlspecialchars(date('Y-m-d H:i', strtotime($at))) . '</span>';
    }
    if (!empty($note)) {
        $out .= ($out ? '<br>' : '') . '<span class="text-muted" style="font-size:.82rem;">' . htmlspecialchars($note) . '</span>';
    }
    return $out;
};
?>
<header class="admin-header">
    <div>
        <h1>Leads<?php if ($activeAccountName !== ''): ?> · <span class="text-muted"><?= htmlspecialchars($activeAccountName) ?></span><?php endif; ?></h1>
        <div class="admin-header__sub">Pipeline CRM<?= $activeAccountName !== '' ? ' de esta cuenta.' : ' (todas las cuentas).' ?></div>
    </div>
    <div class="admin-header__actions">
        <a class="btn btn--secondary" href="/admin/?action=export_csv&amp;<?= http_build_query(array_filter(['account' => $accountFilter ?: '', 'search' => $search, 'status_filter' => $statusFilter, 'pending' => $pendingFilter ? '1' : ''])) ?>">
            Exportar CSV
        </a>
    </div>
</header>

<?php if ($flashOk): ?><p class="alert alert--success"><?= htmlspecialchars($flashOk) ?></p><?php endif; ?>
<?php if ($flashErr): ?><p class="alert alert--error"><?= htmlspecialchars($flashErr) ?></p><?php endif; ?>

<div class="stats">
    <a class="stat" href="<?= htmlspecialchars($filterUrl()) ?>"><div class="stat__label">Total</div><div class="stat__value"><?= $stats['total'] ?></div></a>
    <a class="stat" href="<?= htmlspecialchars($filterUrl(['status_filter' => 'new'])) ?>"><div class="stat__label">Nuevos</div><div class="stat__value"><?= $stats['new'] ?></div></a>
    <a class="stat" href="<?= htmlspecialchars($filterUrl(['status_filter' => 'contacted'])) ?>"><div class="stat__label">Contactados</div><div class="stat__value"><?= $stats['contacted'] ?></div></a>
    <a class="stat" href="<?= htmlspecialchars($filterUrl(['status_filter' => 'meeting_scheduled'])) ?>"><div class="stat__label">Reuniones</div><div class="stat__value"><?= $stats['meeting_scheduled'] ?></div></a>
    <a class="stat" href="<?= htmlspecialchars($filterUrl(['status_filter' => 'proposal_sent'])) ?>"><div class="stat__label">Propuestas</div><div class="stat__value"><?= $stats['proposal_sent'] ?></div></a>
    <a class="stat" href="<?= htmlspecialchars($filterUrl(['status_filter' => 'won'])) ?>"><div class="stat__label">Ganados</div><div class="stat__value"><?= $stats['won'] ?></div></a>
    <a class="stat" href="<?= htmlspecialchars($filterUrl(['status_filter' => 'lost'])) ?>"><div class="stat__label">Perdidos</div><div class="stat__value"><?= $stats['lost'] ?></div></a>
    <a class="stat" href="<?= htmlspecialchars($filterUrl(['pending' => '1'])) ?>"><div class="stat__label">Acciones vencidas</div><div class="stat__value" style="<?= $stats['na_overdue'] > 0 ? 'color:var(--color-danger);' : '' ?>"><?= $stats['na_overdue'] ?></div></a>
    <div class="stat"><div class="stat__label">Acciones de hoy</div><div class="stat__value"><?= $stats['na_today'] ?></div></div>
</div>

<?php
// Enlaces a la vista de tareas conservando el filtro de cuenta.
$tasksLink = function (array $extra = []) use ($accountFilter): string {
    $params = array_filter(array_merge(['view' => 'tasks', 'account' => $accountFilter ?: ''], $extra), fn($v) => $v !== '' && $v !== null);
    return '/admin/?' . http_build_query($params);
};
?>
<h2 class="admin-section__title" style="margin:1.6rem 0 .6rem;font-size:1rem;">Operación del día</h2>
<div class="stats">
    <a class="stat" href="<?= htmlspecialchars($tasksLink(['bucket' => 'overdue'])) ?>"><div class="stat__label">Tareas vencidas</div><div class="stat__value" style="<?= $taskStats['overdue'] > 0 ? 'color:var(--color-danger);' : '' ?>"><?= (int) $taskStats['overdue'] ?></div></a>
    <a class="stat" href="<?= htmlspecialchars($tasksLink(['bucket' => 'today'])) ?>"><div class="stat__label">Tareas de hoy</div><div class="stat__value"><?= (int) $taskStats['today'] ?></div></a>
    <a class="stat" href="<?= htmlspecialchars($tasksLink(['task_status' => 'pending'])) ?>"><div class="stat__label">Tareas pendientes</div><div class="stat__value"><?= (int) $taskStats['pending'] ?></div></a>
    <a class="stat" href="<?= htmlspecialchars($tasksLink(['task_status' => 'completed'])) ?>"><div class="stat__label">Tareas completadas</div><div class="stat__value"><?= (int) $taskStats['completed'] ?></div></a>
    <a class="stat" href="<?= htmlspecialchars($filterUrl(['pending' => '1'])) ?>"><div class="stat__label">Leads sin actividad (<?= LEAD_STALE_DAYS ?>d)</div><div class="stat__value" style="<?= $leadsStale > 0 ? 'color:var(--color-warn);' : '' ?>"><?= (int) $leadsStale ?></div></a>
</div>

<form method="get" class="filters">
    <?php if (!empty($accounts)): ?>
    <div class="filters__group">
        <label for="account">Cuenta</label>
        <select id="account" name="account">
            <option value="">Todas</option>
            <?php foreach ($accounts as $acc): ?>
                <option value="<?= (int) $acc['id'] ?>" <?= $accountFilter === (int) $acc['id'] ? 'selected' : '' ?>><?= htmlspecialchars($acc['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php endif; ?>
    <div class="filters__group">
        <label for="search">Buscar</label>
        <input type="search" id="search" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Nombre, email o teléfono">
    </div>
    <div class="filters__group">
        <label for="status_filter">Estado</label>
        <select id="status_filter" name="status_filter">
            <option value="">Todos</option>
            <?php foreach ($LEAD_STATUSES as $s): ?>
                <option value="<?= $s ?>" <?= $statusFilter === $s ? 'selected' : '' ?>><?= htmlspecialchars(leadStatusLabel($s)) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="filters__group filters__group--actions">
        <label class="filters__check"><input type="checkbox" name="pending" value="1" <?= $pendingFilter ? 'checked' : '' ?>> Solo pendientes</label>
        <button type="submit" class="btn">Filtrar</button>
        <?php if ($search !== '' || $statusFilter !== '' || $accountFilter > 0 || $pendingFilter): ?>
            <a href="/admin/" class="btn btn--ghost">Limpiar</a>
        <?php endif; ?>
    </div>
</form>

<?php if (empty($leads)): ?>
    <div class="empty">
        <h3>No hay leads<?= ($search !== '' || $statusFilter !== '' || $pendingFilter) ? ' con esos filtros' : ' todavía' ?></h3>
        <p>Cuando alguien complete el formulario del sitio, va a aparecer acá.</p>
    </div>
<?php else: ?>
    <table class="table">
        <thead>
            <tr>
                <th style="width:54px;">ID</th>
                <th>Fecha</th>
                <?php if ($activeAccountName === ''): ?><th>Cuenta</th><?php endif; ?>
                <th>Nombre</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Estado</th>
                <th>Próxima acción</th>
                <th style="width:54px;"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($leads as $l): ?>
                <tr>
                    <td class="text-muted text-tabular">#<?= (int) $l['id'] ?></td>
                    <td class="text-muted text-tabular"><?= htmlspecialchars($l['created_at']) ?></td>
                    <?php if ($activeAccountName === ''): ?>
                        <td class="text-muted"><?= htmlspecialchars($accountsMap[(int) ($l['account_id'] ?? 0)] ?? '—') ?></td>
                    <?php endif; ?>
                    <td><strong><?= htmlspecialchars($l['name']) ?></strong></td>
                    <td><?= htmlspecialchars($l['email']) ?></td>
                    <td class="text-muted"><?= htmlspecialchars($l['phone'] ?? '') ?: '—' ?></td>
                    <td><span class="badge badge--<?= htmlspecialchars($l['status']) ?>"><?= htmlspecialchars(leadStatusLabel($l['status'])) ?></span></td>
                    <td><?= $fmtNextAction($l['next_action_at'] ?? null, $l['next_action_note'] ?? null) ?></td>
                    <td><a href="/admin/?id=<?= (int) $l['id'] ?>">Ver →</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <nav class="pagination">
        <span class="pagination__info">Mostrando <?= count($leads) ?> de <?= $totalLeads ?></span>
        <?php if ($totalPages > 1): ?>
            <?php if ($page > 1): ?><a href="<?= $paginationUrl($page - 1) ?>" class="btn btn--secondary">← Anterior</a><?php endif; ?>
            <span class="text-muted" style="font-size:.88rem;">Página <?= $page ?> de <?= $totalPages ?></span>
            <?php if ($page < $totalPages): ?><a href="<?= $paginationUrl($page + 1) ?>" class="btn btn--secondary">Siguiente →</a><?php endif; ?>
        <?php endif; ?>
    </nav>
<?php endif; ?>
