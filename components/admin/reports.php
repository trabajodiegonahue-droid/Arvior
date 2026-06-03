<?php
/**
 * Vista de Reportes comerciales (Fase 4). Requiere:
 *   $accounts, $accountFilter, $reportRange, $reportCur,
 *   $reportKpis, $reportFunnel, $reportBySource, $reportTrend
 */
$accounts       = $accounts ?? [];
$accountFilter  = $accountFilter ?? 0;
$reportCur      = $reportCur ?? 'CLP';
$reportRange    = $reportRange ?? ['from' => '', 'to' => '', 'key' => '30d', 'label' => 'Últimos 30 días'];
$k              = $reportKpis ?? [];
$funnel         = $reportFunnel ?? [];
$bySource       = $reportBySource ?? [];
$trend          = $reportTrend ?? [];

$money = fn($v) => htmlspecialchars($reportCur . ' ' . reportFormatMoney($v));

// URL que conserva el rango + cuenta (para export y enlaces).
$qs = http_build_query(array_filter([
    'range' => $reportRange['key'], 'from' => $reportRange['from'], 'to' => $reportRange['to'],
    'account' => $accountFilter ?: '',
], fn($v) => $v !== '' && $v !== null));

$presets = ['today' => 'Hoy', '7d' => 'Últimos 7 días', '30d' => 'Últimos 30 días', 'month' => 'Mes actual', 'quarter' => 'Últimos 90 días', 'custom' => 'Personalizado'];

// Máximos para escalar las barras (evita división por cero).
$funnelMax = 0; foreach ($funnel as $f) $funnelMax = max($funnelMax, (int) $f['count']);
$trendMax  = 0; foreach ($trend as $tr) $trendMax = max($trendMax, (int) $tr['leads'], (int) $tr['won']);
?>
<header class="admin-header">
    <div>
        <h1>Reportes</h1>
        <div class="admin-header__sub">Métricas comerciales · <?= htmlspecialchars($reportRange['label']) ?> · moneda <?= htmlspecialchars($reportCur) ?></div>
    </div>
    <div class="admin-header__actions">
        <a class="btn btn--secondary" href="/admin/?action=export_reports_csv&amp;<?= htmlspecialchars($qs) ?>">Exportar CSV</a>
    </div>
</header>

<form method="get" class="filters">
    <input type="hidden" name="view" value="reports">
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
        <label for="range">Rango</label>
        <select id="range" name="range">
            <?php foreach ($presets as $val => $lbl): ?>
                <option value="<?= $val ?>" <?= $reportRange['key'] === $val ? 'selected' : '' ?>><?= htmlspecialchars($lbl) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="filters__group">
        <label for="from">Desde</label>
        <input type="date" id="from" name="from" value="<?= htmlspecialchars($reportRange['from']) ?>">
    </div>
    <div class="filters__group">
        <label for="to">Hasta</label>
        <input type="date" id="to" name="to" value="<?= htmlspecialchars($reportRange['to']) ?>">
    </div>
    <div class="filters__group filters__group--actions">
        <button type="submit" class="btn">Aplicar</button>
        <?php if ($accountFilter > 0 || $reportRange['key'] !== '30d'): ?>
            <a href="/admin/?view=reports" class="btn btn--ghost">Limpiar</a>
        <?php endif; ?>
    </div>
</form>

<h2 class="admin-section__title" style="margin:1.4rem 0 .6rem;font-size:1rem;">Indicadores del período</h2>
<div class="stats">
    <div class="stat"><div class="stat__label">Leads</div><div class="stat__value"><?= (int) ($k['leads_in_period'] ?? 0) ?></div></div>
    <div class="stat"><div class="stat__label">Ganados</div><div class="stat__value" style="color:var(--color-success);"><?= (int) ($k['won_count'] ?? 0) ?></div></div>
    <div class="stat"><div class="stat__label">Perdidos</div><div class="stat__value" style="<?= ($k['lost_count'] ?? 0) > 0 ? 'color:var(--color-danger);' : '' ?>"><?= (int) ($k['lost_count'] ?? 0) ?></div></div>
    <div class="stat"><div class="stat__label">Win rate</div><div class="stat__value"><?= number_format((float) ($k['win_rate'] ?? 0), 1) ?>%</div></div>
    <div class="stat"><div class="stat__label">Revenue ganado</div><div class="stat__value" style="font-size:1.1rem;color:var(--color-success);"><?= $money($k['won_revenue'] ?? 0) ?></div></div>
    <div class="stat"><div class="stat__label">Ticket promedio</div><div class="stat__value" style="font-size:1.1rem;"><?= $money($k['avg_deal'] ?? 0) ?></div></div>
</div>

<h2 class="admin-section__title" style="margin:1.4rem 0 .6rem;font-size:1rem;">Pipeline y forecast (abiertos, snapshot)</h2>
<div class="stats">
    <div class="stat"><div class="stat__label">Leads abiertos</div><div class="stat__value"><?= (int) ($k['pipeline_count'] ?? 0) ?></div></div>
    <div class="stat"><div class="stat__label">Pipeline valor</div><div class="stat__value" style="font-size:1.1rem;"><?= $money($k['pipeline_value'] ?? 0) ?></div></div>
    <div class="stat"><div class="stat__label">Forecast ponderado</div><div class="stat__value" style="font-size:1.1rem;color:var(--color-warn);"><?= $money($k['forecast_weighted'] ?? 0) ?></div></div>
    <div class="stat"><div class="stat__label">Valor perdido</div><div class="stat__value" style="font-size:1.1rem;<?= ($k['lost_value'] ?? 0) > 0 ? 'color:var(--color-danger);' : '' ?>"><?= $money($k['lost_value'] ?? 0) ?></div></div>
</div>

<section class="admin-section">
    <h2>Embudo de conversión</h2>
    <p class="text-muted" style="margin:0 0 1rem;font-size:.85rem;">Leads creados en el período que alcanzaron al menos cada etapa.</p>
    <?php foreach ($funnel as $f):
        $w = $funnelMax > 0 ? max(2, round((int) $f['count'] / $funnelMax * 100)) : 2; ?>
        <div style="margin:0 0 .55rem;">
            <div style="display:flex;justify-content:space-between;font-size:.85rem;margin-bottom:.2rem;">
                <span><strong><?= htmlspecialchars(leadStatusLabel($f['stage'])) ?></strong></span>
                <span class="text-muted"><?= (int) $f['count'] ?><?php if ($f['conv'] > 0): ?> · <?= number_format((float) $f['conv'], 1) ?>%<?php endif; ?></span>
            </div>
            <div style="background:var(--color-bg-alt);border-radius:6px;overflow:hidden;height:18px;">
                <div style="width:<?= $w ?>%;height:100%;background:var(--color-success);opacity:.78;"></div>
            </div>
        </div>
    <?php endforeach; ?>
</section>

<section class="admin-section">
    <h2>Performance por fuente</h2>
    <?php if (empty($bySource)): ?>
        <p class="text-muted" style="margin:0;">Sin datos en el período.</p>
    <?php else: ?>
        <table class="table">
            <thead><tr><th>Fuente</th><th>Leads</th><th>Ganados</th><th>Revenue ganado</th><th>Win rate</th></tr></thead>
            <tbody>
            <?php foreach ($bySource as $s): ?>
                <tr>
                    <td><strong><?= htmlspecialchars((string) $s['source']) ?></strong></td>
                    <td class="text-tabular"><?= (int) $s['leads'] ?></td>
                    <td class="text-tabular"><?= (int) $s['won'] ?></td>
                    <td class="text-tabular"><?= $money($s['won_revenue']) ?></td>
                    <td class="text-tabular"><?= number_format((float) $s['win_rate'], 1) ?>%</td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>

<section class="admin-section">
    <h2>Tendencia (últimos 6 meses)</h2>
    <p class="text-muted" style="margin:0 0 1rem;font-size:.85rem;">Leads creados (azul) y ganados (verde) por mes. Independiente del rango seleccionado.</p>
    <div style="display:flex;align-items:flex-end;gap:.8rem;min-height:140px;">
        <?php foreach ($trend as $tr):
            $lh = $trendMax > 0 ? round((int) $tr['leads'] / $trendMax * 110) : 0;
            $wh = $trendMax > 0 ? round((int) $tr['won']   / $trendMax * 110) : 0; ?>
            <div style="flex:1;text-align:center;">
                <div style="display:flex;align-items:flex-end;justify-content:center;gap:3px;height:120px;">
                    <div title="Leads: <?= (int) $tr['leads'] ?>" style="width:14px;height:<?= $lh ?>px;background:#1d4ed8;opacity:.8;border-radius:3px 3px 0 0;"></div>
                    <div title="Ganados: <?= (int) $tr['won'] ?>" style="width:14px;height:<?= $wh ?>px;background:var(--color-success);border-radius:3px 3px 0 0;"></div>
                </div>
                <div class="text-muted" style="font-size:.72rem;margin-top:.3rem;"><?= htmlspecialchars(substr((string) $tr['month'], 2)) ?></div>
                <div style="font-size:.72rem;"><?= (int) $tr['leads'] ?>/<?= (int) $tr['won'] ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
