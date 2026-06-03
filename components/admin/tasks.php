<?php
/**
 * Vista de tareas (Fase 3). Requiere: $accounts, $accountFilter, $taskSearch,
 * $taskStatusF, $taskBucket, $taskCountsAll, y según el modo:
 *   - filtrado: $tasksFiltered
 *   - por defecto: $tasksOverdue, $tasksToday, $tasksUpcoming, $tasksCompleted
 */
$accounts      = $accounts ?? [];
$accountFilter = $accountFilter ?? 0;
$taskSearch    = $taskSearch ?? '';
$taskStatusF   = $taskStatusF ?? '';
$taskBucket    = $taskBucket ?? '';
$taskCountsAll = $taskCountsAll ?? ['overdue' => 0, 'today' => 0, 'upcoming' => 0, 'pending' => 0, 'completed' => 0];
$filtered      = ($taskStatusF !== '' || $taskBucket !== '');

$flashOk  = flashGet('task_success');
$flashErr = flashGet('task_error');

$accNameById = [];
foreach ($accounts as $a) $accNameById[(int) $a['id']] = $a['name'];

// URL de filtro conservando cuenta + búsqueda.
$taskUrl = function (array $extra = []) use ($accountFilter, $taskSearch): string {
    $params = array_filter(array_merge([
        'view' => 'tasks', 'account' => $accountFilter ?: '', 'search' => $taskSearch,
    ], $extra), fn($v) => $v !== '' && $v !== null);
    return '/admin/?' . http_build_query($params);
};

// Estado de vencimiento de una tarea pendiente.
$dueState = function (array $t): string {
    if (($t['status'] ?? '') !== 'pending' || empty($t['due_at'])) return '';
    $ts = strtotime((string) $t['due_at']);
    if ($ts === false) return '';
    if ($ts < time())                 return 'overdue';
    if (date('Y-m-d', $ts) === date('Y-m-d')) return 'today';
    return 'upcoming';
};

// Render de una tabla de tareas (reutilizada por cada bucket).
$renderTasks = function (array $rows) use ($accNameById, $dueState): void {
    if (empty($rows)) {
        echo '<p class="text-muted" style="margin:.2rem 0 1rem;">Sin tareas.</p>';
        return;
    }
    echo '<table class="table"><thead><tr>'
       . '<th>Tarea</th><th>Cuenta / Lead</th><th>Vence</th><th>Estado</th><th style="width:220px;"></th>'
       . '</tr></thead><tbody>';
    foreach ($rows as $t) {
        $ds      = $dueState($t);
        $dueRaw  = $t['due_at'] ?? null;
        $dueTxt  = $dueRaw ? date('Y-m-d H:i', strtotime((string) $dueRaw)) : '—';
        $dueCls  = $ds === 'overdue' ? ' style="color:var(--color-danger);font-weight:600;"' : '';
        $status  = (string) ($t['status'] ?? 'pending');
        $badge   = $ds === 'overdue' ? 'overdue' : $status;
        $badgeTx = $ds === 'overdue' ? 'Vencida' : taskStatusLabel($status);
        $leadId  = (int) ($t['lead_id'] ?? 0);
        $accName = $accNameById[(int) ($t['account_id'] ?? 0)] ?? ($t['account_name'] ?? '');
        $csrf    = csrfToken();
        $tid     = (int) $t['id'];

        echo '<tr>';
        echo '<td><strong>' . htmlspecialchars((string) $t['title']) . '</strong>';
        if (!empty($t['description'])) {
            echo '<br><span class="text-muted" style="font-size:.82rem;">' . nl2br(htmlspecialchars((string) $t['description'])) . '</span>';
        }
        echo '</td>';
        echo '<td class="text-muted" style="font-size:.85rem;">' . htmlspecialchars((string) $accName ?: '—');
        if ($leadId > 0) {
            echo '<br><a href="/admin/?id=' . $leadId . '">Lead: ' . htmlspecialchars((string) ($t['lead_name'] ?? ('#' . $leadId))) . '</a>';
        }
        echo '</td>';
        echo '<td class="text-tabular"' . $dueCls . '>' . htmlspecialchars($dueTxt) . '</td>';
        echo '<td><span class="badge badge--' . htmlspecialchars($badge) . '">' . htmlspecialchars($badgeTx) . '</span></td>';

        echo '<td>';
        if ($status === 'pending') {
            foreach ([['task_complete', 'Completar', 'btn'], ['task_cancel', 'Cancelar', 'btn btn--ghost']] as $a) {
                echo '<form method="post" class="inline-form" style="display:inline;margin:0 .25rem 0 0;">'
                   . '<input type="hidden" name="action" value="' . $a[0] . '">'
                   . '<input type="hidden" name="csrf" value="' . $csrf . '">'
                   . '<input type="hidden" name="id" value="' . $tid . '">'
                   . '<input type="hidden" name="return_to" value="tasks">'
                   . '<button type="submit" class="' . $a[2] . '" style="padding:.3rem .7rem;font-size:.8rem;">' . $a[1] . '</button>'
                   . '</form>';
            }
        } else {
            echo '<form method="post" class="inline-form" style="display:inline;margin:0;">'
               . '<input type="hidden" name="action" value="task_reopen">'
               . '<input type="hidden" name="csrf" value="' . $csrf . '">'
               . '<input type="hidden" name="id" value="' . $tid . '">'
               . '<input type="hidden" name="return_to" value="tasks">'
               . '<button type="submit" class="btn btn--ghost" style="padding:.3rem .7rem;font-size:.8rem;">Reabrir</button>'
               . '</form>';
        }
        echo '</td></tr>';
    }
    echo '</tbody></table>';
};
?>
<header class="admin-header">
    <div>
        <h1>Tareas</h1>
        <div class="admin-header__sub">Operación diaria: qué hacer hoy, qué venció y qué viene.</div>
    </div>
</header>

<?php if ($flashOk): ?><p class="alert alert--success"><?= htmlspecialchars($flashOk) ?></p><?php endif; ?>
<?php if ($flashErr): ?><p class="alert alert--error"><?= htmlspecialchars($flashErr) ?></p><?php endif; ?>

<div class="stats">
    <a class="stat" href="<?= htmlspecialchars($taskUrl(['bucket' => 'overdue'])) ?>"><div class="stat__label">Vencidas</div><div class="stat__value" style="<?= $taskCountsAll['overdue'] > 0 ? 'color:var(--color-danger);' : '' ?>"><?= (int) $taskCountsAll['overdue'] ?></div></a>
    <a class="stat" href="<?= htmlspecialchars($taskUrl(['bucket' => 'today'])) ?>"><div class="stat__label">Hoy</div><div class="stat__value"><?= (int) $taskCountsAll['today'] ?></div></a>
    <a class="stat" href="<?= htmlspecialchars($taskUrl(['bucket' => 'upcoming'])) ?>"><div class="stat__label">Próximas</div><div class="stat__value"><?= (int) $taskCountsAll['upcoming'] ?></div></a>
    <a class="stat" href="<?= htmlspecialchars($taskUrl(['task_status' => 'pending'])) ?>"><div class="stat__label">Pendientes</div><div class="stat__value"><?= (int) $taskCountsAll['pending'] ?></div></a>
    <a class="stat" href="<?= htmlspecialchars($taskUrl(['task_status' => 'completed'])) ?>"><div class="stat__label">Completadas</div><div class="stat__value"><?= (int) $taskCountsAll['completed'] ?></div></a>
</div>

<form method="get" class="filters">
    <input type="hidden" name="view" value="tasks">
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
        <input type="search" id="search" name="search" value="<?= htmlspecialchars($taskSearch) ?>" placeholder="Título o descripción">
    </div>
    <div class="filters__group">
        <label for="task_status">Estado</label>
        <select id="task_status" name="task_status">
            <option value="">Todos</option>
            <?php foreach (TASK_STATUSES as $s): ?>
                <option value="<?= $s ?>" <?= $taskStatusF === $s ? 'selected' : '' ?>><?= htmlspecialchars(taskStatusLabel($s)) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="filters__group">
        <label for="bucket">Vencimiento</label>
        <select id="bucket" name="bucket">
            <option value="">Todos</option>
            <option value="overdue"  <?= $taskBucket === 'overdue'  ? 'selected' : '' ?>>Vencidas</option>
            <option value="today"    <?= $taskBucket === 'today'    ? 'selected' : '' ?>>Hoy</option>
            <option value="upcoming" <?= $taskBucket === 'upcoming' ? 'selected' : '' ?>>Próximas</option>
        </select>
    </div>
    <div class="filters__group filters__group--actions">
        <button type="submit" class="btn">Filtrar</button>
        <?php if ($taskSearch !== '' || $taskStatusF !== '' || $taskBucket !== '' || $accountFilter > 0): ?>
            <a href="/admin/?view=tasks" class="btn btn--ghost">Limpiar</a>
        <?php endif; ?>
    </div>
</form>

<section class="admin-section">
    <h2>Nueva tarea</h2>
    <form method="post" class="inline-form" style="margin:0;flex-wrap:wrap;gap:.6rem;align-items:flex-end;">
        <input type="hidden" name="action" value="task_create">
        <input type="hidden" name="csrf" value="<?= csrfToken() ?>">
        <input type="hidden" name="return_to" value="tasks">
        <label style="font-size:.82rem;flex:1 1 260px;">Título<br><input type="text" name="title" required maxlength="200" placeholder="Ej: Llamar a cliente" style="width:100%;"></label>
        <label style="font-size:.82rem;">Cuenta<br>
            <select name="account_id">
                <option value="">— General —</option>
                <?php foreach ($accounts as $acc): ?>
                    <option value="<?= (int) $acc['id'] ?>" <?= $accountFilter === (int) $acc['id'] ? 'selected' : '' ?>><?= htmlspecialchars($acc['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label style="font-size:.82rem;">Vence<br><input type="datetime-local" name="due_at"></label>
        <label style="font-size:.82rem;flex:1 1 100%;">Descripción (opcional)<br><input type="text" name="description" maxlength="1000" placeholder="Detalle de la tarea" style="width:100%;"></label>
        <button type="submit" class="btn">Crear tarea</button>
    </form>
</section>

<?php if ($filtered): ?>
    <section class="admin-section">
        <h2>Resultados</h2>
        <?php $renderTasks($tasksFiltered ?? []); ?>
    </section>
<?php else: ?>
    <section class="admin-section">
        <h2>Vencidas <span class="badge badge--overdue" style="font-size:.62rem;"><?= count($tasksOverdue ?? []) ?></span></h2>
        <?php $renderTasks($tasksOverdue ?? []); ?>
    </section>
    <section class="admin-section">
        <h2>Hoy <span class="badge badge--contacted" style="font-size:.62rem;"><?= count($tasksToday ?? []) ?></span></h2>
        <?php $renderTasks($tasksToday ?? []); ?>
    </section>
    <section class="admin-section">
        <h2>Próximas <span class="badge badge--new" style="font-size:.62rem;"><?= count($tasksUpcoming ?? []) ?></span></h2>
        <?php $renderTasks($tasksUpcoming ?? []); ?>
    </section>
    <section class="admin-section">
        <h2>Completadas (últimas) <span class="badge badge--completed" style="font-size:.62rem;"><?= count($tasksCompleted ?? []) ?></span></h2>
        <?php $renderTasks($tasksCompleted ?? []); ?>
    </section>
<?php endif; ?>
