<?php
/** Requiere: $lead, $notes (timeline de lead_activities), $LEAD_STATUSES, $leadTasks */
$flashOk  = flashGet('lead_success');
$flashErr = flashGet('lead_error');
$taskOk   = flashGet('task_success');
$taskErr  = flashGet('task_error');
$leadTasks = $leadTasks ?? [];

// Etiqueta legible por tipo de actividad.
$activityLabel = function (array $n): string {
    switch ($n['type'] ?? 'note') {
        case 'created':              return 'Lead capturado';
        case 'status_change':        return 'Estado: ' . leadStatusLabel((string) ($n['from_status'] ?? '?')) . ' → ' . leadStatusLabel((string) ($n['to_status'] ?? '?'));
        case 'note':                 return 'Nota';
        case 'next_action':          return 'Próxima acción';
        case 'next_action_cleared':  return 'Próxima acción eliminada';
        case 'task_created':         return 'Tarea creada';
        case 'task_completed':       return 'Tarea completada';
        case 'task_cancelled':       return 'Tarea cancelada';
        case 'task_reopened':        return 'Tarea reabierta';
        default:                     return (string) ($n['type'] ?? 'Actividad');
    }
};
// Clase de badge por tipo de actividad (reusa colores del pipeline).
$activityBadge = function (string $type): string {
    return [
        'created'             => 'new',
        'note'                => 'meeting_scheduled',
        'next_action'         => 'proposal_sent',
        'next_action_cleared' => 'lost',
        'status_change'       => 'contacted',
        'task_created'        => 'new',
        'task_completed'      => 'completed',
        'task_cancelled'      => 'cancelled',
        'task_reopened'       => 'pending',
    ][$type] ?? 'contacted';
};

// Estado actual + lista para el select (incluye el actual si fuese legacy).
$curStatus    = (string) $lead['status'];
$statusChoices = $LEAD_STATUSES;
if (!in_array($curStatus, $statusChoices, true)) {
    array_unshift($statusChoices, $curStatus);
}

// next_action_at en formato datetime-local ('Y-m-d\TH:i') para el input.
$naAtRaw   = $lead['next_action_at'] ?? null;
$naAtLocal = ($naAtRaw && strtotime($naAtRaw) !== false) ? date('Y-m-d\TH:i', strtotime($naAtRaw)) : '';
$naNote    = (string) ($lead['next_action_note'] ?? '');
$naOverdue = $naAtRaw && strtotime($naAtRaw) !== false && strtotime($naAtRaw) <= time()
             && !in_array($curStatus, ['won','lost','closed','discarded'], true);
?>
<header class="admin-header">
    <div>
        <div style="margin-bottom:.3rem;"><a href="/admin/" class="text-muted" style="font-size:.88rem;">← Volver a leads</a></div>
        <h1><?= htmlspecialchars($lead['name']) ?> <span class="badge badge--<?= htmlspecialchars($curStatus) ?>" style="font-size:.7rem;vertical-align:middle;"><?= htmlspecialchars(leadStatusLabel($curStatus)) ?></span></h1>
        <div class="admin-header__sub">Lead #<?= (int) $lead['id'] ?> · <?= htmlspecialchars($lead['created_at']) ?><?php if (!empty($lead['account_name'])): ?> · Cuenta: <strong><?= htmlspecialchars($lead['account_name']) ?></strong><?php endif; ?></div>
    </div>
    <div class="admin-header__actions">
        <a class="btn btn--secondary" href="mailto:<?= htmlspecialchars($lead['email']) ?>">Responder por email</a>
    </div>
</header>

<?php if ($flashOk): ?><p class="alert alert--success"><?= htmlspecialchars($flashOk) ?></p><?php endif; ?>
<?php if ($flashErr): ?><p class="alert alert--error"><?= htmlspecialchars($flashErr) ?></p><?php endif; ?>

<div class="lead-detail">
    <dl>
        <dt>Email</dt>    <dd><a href="mailto:<?= htmlspecialchars($lead['email']) ?>"><?= htmlspecialchars($lead['email']) ?></a></dd>
        <dt>Teléfono</dt> <dd><?= htmlspecialchars($lead['phone'] ?? '') ?: '—' ?></dd>
        <dt>Mensaje</dt>  <dd><?= nl2br(htmlspecialchars($lead['message'] ?? '')) ?: '—' ?></dd>
        <dt>Source</dt>   <dd><?= htmlspecialchars($lead['source'] ?? 'website') ?></dd>
        <dt>IP</dt>       <dd class="text-muted"><?= htmlspecialchars($lead['ip_address'] ?? '—') ?></dd>
    </dl>
</div>

<section class="admin-section">
    <h2>Cambiar estado</h2>
    <form method="post" class="inline-form" style="margin:0;">
        <input type="hidden" name="action" value="update_lead_status">
        <input type="hidden" name="csrf" value="<?= csrfToken() ?>">
        <input type="hidden" name="id" value="<?= (int) $lead['id'] ?>">
        <select name="status">
            <?php foreach ($statusChoices as $s): ?>
                <option value="<?= htmlspecialchars($s) ?>" <?= $curStatus === $s ? 'selected' : '' ?>><?= htmlspecialchars(leadStatusLabel($s)) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn">Guardar estado</button>
    </form>
</section>

<section class="admin-section">
    <h2>Próxima acción <?php if ($naOverdue): ?><span class="badge badge--lost" style="font-size:.62rem;">Vencida</span><?php endif; ?></h2>
    <?php if ($naAtLocal !== '' || $naNote !== ''): ?>
        <p class="text-muted" style="margin:0 0 .8rem;font-size:.9rem;">
            Programada:
            <strong<?= $naOverdue ? ' style="color:var(--color-danger);"' : '' ?>><?= $naAtRaw ? htmlspecialchars(date('Y-m-d H:i', strtotime($naAtRaw))) : 'sin fecha' ?></strong>
            <?php if ($naNote !== ''): ?> · <?= htmlspecialchars($naNote) ?><?php endif; ?>
        </p>
    <?php else: ?>
        <p class="text-muted" style="margin:0 0 .8rem;font-size:.9rem;">No hay una próxima acción programada.</p>
    <?php endif; ?>
    <form method="post" class="inline-form" style="margin:0;flex-wrap:wrap;gap:.6rem;align-items:flex-end;">
        <input type="hidden" name="action" value="update_next_action">
        <input type="hidden" name="csrf" value="<?= csrfToken() ?>">
        <input type="hidden" name="id" value="<?= (int) $lead['id'] ?>">
        <label style="font-size:.82rem;">Fecha y hora<br><input type="datetime-local" name="next_action_at" value="<?= htmlspecialchars($naAtLocal) ?>"></label>
        <label style="font-size:.82rem;flex:1 1 240px;">Nota<br><input type="text" name="next_action_note" value="<?= htmlspecialchars($naNote) ?>" placeholder="Ej: llamar para confirmar reunión" maxlength="500" style="width:100%;"></label>
        <button type="submit" class="btn">Guardar próxima acción</button>
    </form>
    <?php if ($naAtLocal !== '' || $naNote !== ''): ?>
        <form method="post" style="margin:.6rem 0 0;">
            <input type="hidden" name="action" value="update_next_action">
            <input type="hidden" name="csrf" value="<?= csrfToken() ?>">
            <input type="hidden" name="id" value="<?= (int) $lead['id'] ?>">
            <input type="hidden" name="next_action_at" value="">
            <input type="hidden" name="next_action_note" value="">
            <button type="submit" class="btn btn--ghost">Limpiar próxima acción</button>
        </form>
    <?php endif; ?>
</section>

<section class="admin-section">
    <h2>Tareas</h2>
    <?php if ($taskOk): ?><p class="alert alert--success"><?= htmlspecialchars($taskOk) ?></p><?php endif; ?>
    <?php if ($taskErr): ?><p class="alert alert--error"><?= htmlspecialchars($taskErr) ?></p><?php endif; ?>

    <?php if (empty($leadTasks)): ?>
        <p class="text-muted" style="margin:0 0 1rem;">Este lead no tiene tareas.</p>
    <?php else: ?>
        <table class="table" style="margin-bottom:1rem;">
            <thead><tr><th>Tarea</th><th>Vence</th><th>Estado</th><th style="width:200px;"></th></tr></thead>
            <tbody>
            <?php foreach ($leadTasks as $t):
                $tStatus = (string) ($t['status'] ?? 'pending');
                $dueRaw  = $t['due_at'] ?? null;
                $overdue = $tStatus === 'pending' && $dueRaw && strtotime((string) $dueRaw) !== false && strtotime((string) $dueRaw) < time();
                $badge   = $overdue ? 'overdue' : $tStatus;
                $badgeTx = $overdue ? 'Vencida' : taskStatusLabel($tStatus);
            ?>
                <tr>
                    <td><strong><?= htmlspecialchars((string) $t['title']) ?></strong>
                        <?php if (!empty($t['description'])): ?><br><span class="text-muted" style="font-size:.82rem;"><?= nl2br(htmlspecialchars((string) $t['description'])) ?></span><?php endif; ?>
                    </td>
                    <td class="text-tabular"<?= $overdue ? ' style="color:var(--color-danger);font-weight:600;"' : '' ?>><?= $dueRaw ? htmlspecialchars(date('Y-m-d H:i', strtotime((string) $dueRaw))) : '—' ?></td>
                    <td><span class="badge badge--<?= htmlspecialchars($badge) ?>"><?= htmlspecialchars($badgeTx) ?></span></td>
                    <td>
                        <?php if ($tStatus === 'pending'): ?>
                            <form method="post" class="inline-form" style="display:inline;margin:0 .25rem 0 0;">
                                <input type="hidden" name="action" value="task_complete">
                                <input type="hidden" name="csrf" value="<?= csrfToken() ?>">
                                <input type="hidden" name="id" value="<?= (int) $t['id'] ?>">
                                <input type="hidden" name="lead_id" value="<?= (int) $lead['id'] ?>">
                                <input type="hidden" name="return_to" value="lead">
                                <button type="submit" class="btn" style="padding:.3rem .7rem;font-size:.8rem;">Completar</button>
                            </form>
                            <form method="post" class="inline-form" style="display:inline;margin:0;">
                                <input type="hidden" name="action" value="task_cancel">
                                <input type="hidden" name="csrf" value="<?= csrfToken() ?>">
                                <input type="hidden" name="id" value="<?= (int) $t['id'] ?>">
                                <input type="hidden" name="lead_id" value="<?= (int) $lead['id'] ?>">
                                <input type="hidden" name="return_to" value="lead">
                                <button type="submit" class="btn btn--ghost" style="padding:.3rem .7rem;font-size:.8rem;">Cancelar</button>
                            </form>
                        <?php else: ?>
                            <form method="post" class="inline-form" style="display:inline;margin:0;">
                                <input type="hidden" name="action" value="task_reopen">
                                <input type="hidden" name="csrf" value="<?= csrfToken() ?>">
                                <input type="hidden" name="id" value="<?= (int) $t['id'] ?>">
                                <input type="hidden" name="lead_id" value="<?= (int) $lead['id'] ?>">
                                <input type="hidden" name="return_to" value="lead">
                                <button type="submit" class="btn btn--ghost" style="padding:.3rem .7rem;font-size:.8rem;">Reabrir</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <form method="post" class="inline-form" style="margin:0;flex-wrap:wrap;gap:.6rem;align-items:flex-end;">
        <input type="hidden" name="action" value="task_create">
        <input type="hidden" name="csrf" value="<?= csrfToken() ?>">
        <input type="hidden" name="lead_id" value="<?= (int) $lead['id'] ?>">
        <input type="hidden" name="return_to" value="lead">
        <label style="font-size:.82rem;flex:1 1 240px;">Título<br><input type="text" name="title" required maxlength="200" placeholder="Ej: Enviar propuesta" style="width:100%;"></label>
        <label style="font-size:.82rem;">Vence<br><input type="datetime-local" name="due_at"></label>
        <label style="font-size:.82rem;flex:1 1 100%;">Descripción (opcional)<br><input type="text" name="description" maxlength="1000" style="width:100%;"></label>
        <button type="submit" class="btn">Crear tarea</button>
    </form>
</section>

<section class="admin-section">
    <h2>Actividad</h2>
    <?php if (!empty($lead['notes'])): ?>
        <div class="alert" style="margin-bottom:1rem;">
            <strong>Notas legacy:</strong>
            <pre style="white-space:pre-wrap;margin:.4rem 0 0;background:transparent;border:0;padding:0;"><?= htmlspecialchars($lead['notes']) ?></pre>
        </div>
    <?php endif; ?>

    <?php if (empty($notes)): ?>
        <p class="text-muted" style="margin:0 0 1rem;">Todavía no hay actividad.</p>
    <?php else: ?>
        <ul class="notes-list">
            <?php foreach ($notes as $n): ?>
                <li>
                    <div class="note__meta">
                        <span class="badge badge--<?= $activityBadge((string) ($n['type'] ?? 'note')) ?>" style="font-size:.62rem;"><?= htmlspecialchars($activityLabel($n)) ?></span>
                        <span class="dot"></span><span><?= htmlspecialchars($n['created_at']) ?></span>
                        <?php if (!empty($n['author_email'])): ?>
                            <span class="dot"></span><span><?= htmlspecialchars($n['author_email']) ?></span>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($n['body'])): ?><div><?= nl2br(htmlspecialchars($n['body'])) ?></div><?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="post" style="margin-top:1rem;">
        <input type="hidden" name="action" value="add_note">
        <input type="hidden" name="csrf" value="<?= csrfToken() ?>">
        <input type="hidden" name="id" value="<?= (int) $lead['id'] ?>">
        <p class="form__field"><textarea name="note" rows="3" placeholder="Nueva nota..." required></textarea></p>
        <p class="form__submit" style="margin:0;"><button type="submit" class="btn">Agregar nota</button></p>
    </form>
</section>

<section class="admin-section" style="border-color:#fecaca;background:#fef9f9;">
    <h2 style="color:#991b1b;">Zona peligrosa</h2>
    <p class="text-muted" style="margin-bottom:.8rem;font-size:.88rem;">Eliminar un lead es permanente. También borra las notas asociadas.</p>
    <form method="post" onsubmit="return confirm('¿Eliminar este lead? Esta acción no se puede deshacer.')" style="margin:0;">
        <input type="hidden" name="action" value="delete_lead">
        <input type="hidden" name="csrf" value="<?= csrfToken() ?>">
        <input type="hidden" name="id" value="<?= (int) $lead['id'] ?>">
        <button type="submit" class="btn btn--danger">Eliminar lead</button>
    </form>
</section>
