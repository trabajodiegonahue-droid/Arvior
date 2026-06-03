<?php

/**
 * Reportes y métricas comerciales — ARVIOR Core Fase 4.
 *
 * Convierte el pipeline en un sistema medible: KPIs, embudo, revenue (ganado y
 * en pipeline), forecast ponderado y performance por fuente. Todo se deriva de
 * `leads` + `lead_activities` ya existentes, más el bloque monetario agregado en
 * la migración 021 (value_amount, won_at, lost_at, lost_reason).
 *
 * Sin dependencias externas: agregaciones en SQL, formato en PHP. Moneda única
 * y global (settings.report_currency, default CLP) — no se mezclan monedas.
 *
 * Todas las funciones son defensivas: si falta el esquema monetario, devuelven
 * estructuras con ceros en vez de lanzar. Reciben filtros uniformes:
 *   $accountId  int  (0 = todas las cuentas)
 *   $from,$to   string 'Y-m-d' (rango de fechas; bordes inclusivos)
 *
 * Nota TZ: created_at se guarda con NOW() de MySQL; won_at/lost_at con la TZ de
 * la app. El rango se aplica con los mismos bordes a ambos: para reportes por
 * rango de fechas es predecible; puede haber un leve desfase si la TZ de MySQL
 * difiere de APP_TIMEZONE (riesgo conocido, aceptado en Fase 4).
 */

// Probabilidades fijas por etapa para el forecast ponderado (Fase 4: en código,
// sin tabla). Incluye el mapeo de los estados legacy para no romper el cálculo.
const REPORT_STAGE_PROBABILITY = [
    'new'               => 0.05,
    'contacted'         => 0.15,
    'meeting_scheduled' => 0.30,
    'proposal_sent'     => 0.50,
    'negotiation'       => 0.70,
    'won'               => 1.00,
    'lost'              => 0.00,
    // Legacy (Fase 1).
    'qualified'         => 0.15,
    'closed'            => 1.00,
    'discarded'         => 0.00,
];

// Estados "abiertos": siguen en el pipeline (ni ganados ni perdidos/cerrados).
// Son los que componen pipeline value y forecast.
const REPORT_OPEN_STATUSES = ['new', 'contacted', 'meeting_scheduled', 'proposal_sent', 'negotiation', 'qualified'];

// Orden del embudo y rango de cada estado, para contar "alcanzó al menos" esta
// etapa. Los terminales perdidos/descartados quedan en rango 0 (solo cuentan en
// el tope del embudo): conservador, ya que el estado actual no dice hasta dónde
// avanzaron antes de caer.
const REPORT_FUNNEL_STAGES = ['new', 'contacted', 'meeting_scheduled', 'proposal_sent', 'negotiation', 'won'];

/** Moneda global de reportes (código ISO simple, ej. 'CLP'). */
function reportCurrency(): string {
    $c = trim((string) getSetting('report_currency', 'CLP'));
    return $c !== '' ? $c : 'CLP';
}

/** Formatea un monto entero agrupado (1.500.000). Sin decimales (CLP). */
function reportFormatMoney($amount): string {
    return number_format((float) $amount, 0, ',', '.');
}

/** Rango de un estado en el embudo (ver REPORT_FUNNEL_STAGES). */
function reportStageRank(string $status): int {
    static $rank = [
        'new' => 0, 'contacted' => 1, 'qualified' => 1, 'meeting_scheduled' => 2,
        'proposal_sent' => 3, 'negotiation' => 4, 'won' => 5, 'closed' => 5,
        'lost' => 0, 'discarded' => 0,
    ];
    return $rank[$status] ?? 0;
}

/** ¿Está disponible el esquema monetario (migración 021 aplicada)? */
function reportsSchemaReady(): bool {
    static $ready = null;
    if ($ready !== null) return $ready;
    try {
        $ready = (bool) getDB()->query("SHOW COLUMNS FROM leads LIKE 'value_amount'")->fetch();
    } catch (Throwable $e) {
        $ready = false;
    }
    return $ready;
}

/**
 * Normaliza un rango de fechas a bordes de datetime inclusivos.
 * @return array{0:string,1:string} [fromDateTime, toDateTime]
 */
function reportRangeBounds(?string $from, ?string $to): array {
    $from = trim((string) $from);
    $to   = trim((string) $to);
    $fromTs = $from !== '' ? strtotime($from) : strtotime('-29 days');
    $toTs   = $to   !== '' ? strtotime($to)   : time();
    if ($fromTs === false) $fromTs = strtotime('-29 days');
    if ($toTs === false)   $toTs   = time();
    return [date('Y-m-d 00:00:00', $fromTs), date('Y-m-d 23:59:59', $toTs)];
}

/** Cláusula de scope por cuenta (interpolación segura: entero ya casteado). */
function reportAccountScope(int $accountId, string $col = 'account_id'): string {
    return $accountId > 0 ? " AND $col = " . $accountId : '';
}

/**
 * Resuelve el rango de fechas desde el request: un preset ('today','7d','30d',
 * 'month','quarter') o fechas explícitas from/to ('custom'). Devuelve los bordes
 * en 'Y-m-d' más una etiqueta legible. Calculado con la TZ de la app.
 *
 * @return array{from:string,to:string,key:string,label:string}
 */
function reportResolveRange(?string $range, ?string $from, ?string $to): array {
    $range = trim((string) $range);
    $from  = trim((string) $from);
    $to    = trim((string) $to);
    $today = date('Y-m-d');

    // Fechas explícitas tienen prioridad si vienen ambas.
    if ($range === 'custom' || ($from !== '' && $to !== '')) {
        $f = $from !== '' ? $from : $today;
        $t = $to   !== '' ? $to   : $today;
        return ['from' => $f, 'to' => $t, 'key' => 'custom', 'label' => "$f a $t"];
    }
    switch ($range) {
        case 'today':   return ['from' => $today, 'to' => $today, 'key' => 'today', 'label' => 'Hoy'];
        case '7d':      return ['from' => date('Y-m-d', strtotime('-6 days')), 'to' => $today, 'key' => '7d', 'label' => 'Últimos 7 días'];
        case 'month':   return ['from' => date('Y-m-01'), 'to' => $today, 'key' => 'month', 'label' => 'Mes actual'];
        case 'quarter': return ['from' => date('Y-m-d', strtotime('-89 days')), 'to' => $today, 'key' => 'quarter', 'label' => 'Últimos 90 días'];
        case '30d':
        default:        return ['from' => date('Y-m-d', strtotime('-29 days')), 'to' => $today, 'key' => '30d', 'label' => 'Últimos 30 días'];
    }
}

/**
 * KPIs principales del período + snapshot de pipeline.
 *
 * @return array{
 *   leads_in_period:int, won_count:int, lost_count:int, win_rate:float,
 *   won_revenue:float, avg_deal:float, lost_value:float,
 *   pipeline_count:int, pipeline_value:float, forecast_weighted:float
 * }
 */
function reportKpis(int $accountId, ?string $from, ?string $to): array {
    $out = [
        'leads_in_period' => 0, 'won_count' => 0, 'lost_count' => 0, 'win_rate' => 0.0,
        'won_revenue' => 0.0, 'avg_deal' => 0.0, 'lost_value' => 0.0,
        'pipeline_count' => 0, 'pipeline_value' => 0.0, 'forecast_weighted' => 0.0,
    ];
    if (!reportsSchemaReady()) return $out;

    [$f, $t] = reportRangeBounds($from, $to);
    $scope = reportAccountScope($accountId);
    $db = getDB();

    try {
        // Leads creados en el período.
        $st = $db->prepare("SELECT COUNT(*) FROM leads WHERE created_at BETWEEN ? AND ?$scope");
        $st->execute([$f, $t]);
        $out['leads_in_period'] = (int) $st->fetchColumn();

        // Ganados / perdidos en el período (por fecha de cierre).
        $st = $db->prepare("SELECT COUNT(*), COALESCE(SUM(value_amount),0) FROM leads
                            WHERE status='won' AND won_at BETWEEN ? AND ?$scope");
        $st->execute([$f, $t]);
        $rw = $st->fetch(PDO::FETCH_NUM);
        $out['won_count']   = (int) $rw[0];
        $out['won_revenue'] = (float) $rw[1];

        $st = $db->prepare("SELECT COUNT(*), COALESCE(SUM(value_amount),0) FROM leads
                            WHERE status='lost' AND lost_at BETWEEN ? AND ?$scope");
        $st->execute([$f, $t]);
        $rl = $st->fetch(PDO::FETCH_NUM);
        $out['lost_count']  = (int) $rl[0];
        $out['lost_value']  = (float) $rl[1];

        $decided = $out['won_count'] + $out['lost_count'];
        $out['win_rate'] = $decided > 0 ? round($out['won_count'] / $decided * 100, 1) : 0.0;
        $out['avg_deal'] = $out['won_count'] > 0 ? round($out['won_revenue'] / $out['won_count']) : 0.0;

        // Pipeline abierto (snapshot, no acotado por fecha): valor y forecast.
        $open = "'" . implode("','", REPORT_OPEN_STATUSES) . "'";
        $st = $db->query("SELECT status, COUNT(*) c, COALESCE(SUM(value_amount),0) v
                            FROM leads WHERE status IN ($open)" . reportAccountScope($accountId) . " GROUP BY status");
        foreach ($st as $row) {
            $out['pipeline_count'] += (int) $row['c'];
            $out['pipeline_value'] += (float) $row['v'];
            $prob = REPORT_STAGE_PROBABILITY[$row['status']] ?? 0.0;
            $out['forecast_weighted'] += (float) $row['v'] * $prob;
        }
        $out['forecast_weighted'] = round($out['forecast_weighted']);
    } catch (Throwable $e) {
        error_log('reportKpis: ' . $e->getMessage());
    }
    return $out;
}

/**
 * Embudo: por cada etapa, cuántos leads (creados en el período) alcanzaron al
 * menos esa etapa, y la conversión respecto de la etapa anterior.
 *
 * @return array<int,array{stage:string,count:int,conv:float}>
 */
function reportFunnel(int $accountId, ?string $from, ?string $to): array {
    $stages = REPORT_FUNNEL_STAGES;
    $rows = [];
    if (!reportsSchemaReady()) {
        foreach ($stages as $s) $rows[] = ['stage' => $s, 'count' => 0, 'conv' => 0.0];
        return $rows;
    }

    [$f, $t] = reportRangeBounds($from, $to);
    $scope = reportAccountScope($accountId);
    $byStatus = [];
    try {
        $st = getDB()->prepare("SELECT status, COUNT(*) c FROM leads
                                 WHERE created_at BETWEEN ? AND ?$scope GROUP BY status");
        $st->execute([$f, $t]);
        foreach ($st as $r) $byStatus[$r['status']] = (int) $r['c'];
    } catch (Throwable $e) {
        error_log('reportFunnel: ' . $e->getMessage());
    }

    // Para cada etapa: suma de leads cuyo rango actual >= rango de la etapa.
    $prev = null;
    foreach ($stages as $stage) {
        $rk = reportStageRank($stage);
        $count = 0;
        foreach ($byStatus as $status => $c) {
            if (reportStageRank((string) $status) >= $rk) $count += $c;
        }
        $conv = ($prev !== null && $prev > 0) ? round($count / $prev * 100, 1) : 0.0;
        $rows[] = ['stage' => $stage, 'count' => $count, 'conv' => $conv];
        $prev = $count;
    }
    return $rows;
}

/**
 * Performance por fuente: leads del período, ganados y revenue ganado, win rate.
 *
 * @return array<int,array{source:string,leads:int,won:int,won_revenue:float,win_rate:float}>
 */
function reportBySource(int $accountId, ?string $from, ?string $to): array {
    if (!reportsSchemaReady()) return [];
    [$f, $t] = reportRangeBounds($from, $to);
    $scope = reportAccountScope($accountId);

    try {
        $db = getDB();
        // Leads por fuente (creados en el período).
        $st = $db->prepare("SELECT COALESCE(NULLIF(source,''),'(sin fuente)') src, COUNT(*) c
                              FROM leads WHERE created_at BETWEEN ? AND ?$scope GROUP BY src");
        $st->execute([$f, $t]);
        $agg = [];
        foreach ($st as $r) $agg[$r['src']] = ['source' => $r['src'], 'leads' => (int) $r['c'], 'won' => 0, 'won_revenue' => 0.0];

        // Ganados por fuente (cerrados en el período).
        $st = $db->prepare("SELECT COALESCE(NULLIF(source,''),'(sin fuente)') src, COUNT(*) c, COALESCE(SUM(value_amount),0) v
                              FROM leads WHERE status='won' AND won_at BETWEEN ? AND ?$scope GROUP BY src");
        $st->execute([$f, $t]);
        foreach ($st as $r) {
            if (!isset($agg[$r['src']])) $agg[$r['src']] = ['source' => $r['src'], 'leads' => 0, 'won' => 0, 'won_revenue' => 0.0];
            $agg[$r['src']]['won'] = (int) $r['c'];
            $agg[$r['src']]['won_revenue'] = (float) $r['v'];
        }

        foreach ($agg as &$row) {
            $row['win_rate'] = $row['leads'] > 0 ? round($row['won'] / $row['leads'] * 100, 1) : 0.0;
        }
        unset($row);
        usort($agg, fn($a, $b) => $b['leads'] <=> $a['leads']);
        return array_values($agg);
    } catch (Throwable $e) {
        error_log('reportBySource: ' . $e->getMessage());
        return [];
    }
}

/**
 * Tendencia de los últimos N meses (independiente del filtro de fecha, para el
 * gráfico de barras): leads creados, ganados y revenue ganado por mes.
 *
 * @return array<int,array{month:string,leads:int,won:int,won_revenue:float}>
 */
function reportMonthlyTrend(int $accountId, int $months = 6): array {
    $months = max(1, min(24, $months));
    // Inicializa los meses (más antiguo → más reciente) en cero.
    $series = [];
    for ($i = $months - 1; $i >= 0; $i--) {
        $m = date('Y-m', strtotime("first day of -$i month"));
        $series[$m] = ['month' => $m, 'leads' => 0, 'won' => 0, 'won_revenue' => 0.0];
    }
    if (!reportsSchemaReady()) return array_values($series);

    $scope = reportAccountScope($accountId);
    $since = date('Y-m-01 00:00:00', strtotime('first day of -' . ($months - 1) . ' month'));
    try {
        $db = getDB();
        $st = $db->prepare("SELECT DATE_FORMAT(created_at,'%Y-%m') m, COUNT(*) c
                              FROM leads WHERE created_at >= ?$scope GROUP BY m");
        $st->execute([$since]);
        foreach ($st as $r) if (isset($series[$r['m']])) $series[$r['m']]['leads'] = (int) $r['c'];

        $st = $db->prepare("SELECT DATE_FORMAT(won_at,'%Y-%m') m, COUNT(*) c, COALESCE(SUM(value_amount),0) v
                              FROM leads WHERE status='won' AND won_at >= ?$scope GROUP BY m");
        $st->execute([$since]);
        foreach ($st as $r) if (isset($series[$r['m']])) {
            $series[$r['m']]['won'] = (int) $r['c'];
            $series[$r['m']]['won_revenue'] = (float) $r['v'];
        }
    } catch (Throwable $e) {
        error_log('reportMonthlyTrend: ' . $e->getMessage());
    }
    return array_values($series);
}
