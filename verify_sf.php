<?php
declare(strict_types=1);
/* verify_sf v2 — метрики без самообмана:
   1) day-ahead: направление (в т.ч. с Правилом 50%) по каждой строке;
   2) window: путь на N дней от даты публикации — тренд-хит и MAPE внутри окна.
   Якорь прогноза — цена на дату публикации (последний факт <= даты).
   Запуск: php scripts/verify_sf.php [red|blue] [limit] [window] */
$cfg = @include __DIR__ . '/../config/db.php';
if (!is_array($cfg) || empty($cfg['dsn'])) { fwrite(STDERR, "config/db.php не найден\n"); exit(1); }
preg_match('/host=([^;]+)/', $cfg['dsn'], $m); $host = $m[1] ?? '127.0.0.1';
preg_match('/dbname=([^;]+)/', $cfg['dsn'], $m); $db = $m[1];
$pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4",
    $cfg['username'], $cfg['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
echo "База: $db\n";

$line   = ($argv[1] ?? 'red') === 'blue' ? 'blue' : 'red';
$limit  = max(1, (int)($argv[2] ?? 15));
$window = max(5, (int)($argv[3] ?? 90));
$table  = 'cap_stock_prognosis_' . $line;
echo "Линия: $line, окно: $window дн.\n";

$rows = $pdo->query("SELECT s.id, s.name, s.finam_code, COUNT(*) c
    FROM $table p JOIN cap_stock s ON s.id = p.stock_id
    WHERE s.finam_code IS NOT NULL AND s.finam_code <> ''
    GROUP BY s.id ORDER BY c DESC LIMIT $limit")->fetchAll(PDO::FETCH_ASSOC);

$G = ['dir_ok' => 0, 'dir_n' => 0, 'dir50_ok' => 0,
      'win_ok' => 0, 'win_n' => 0, 'mapes' => []];

foreach ($rows as $r) {
    $sid = (int)$r['id'];
    $bars = $pdo->prepare("SELECT date, close FROM cap_stock_kurs
        WHERE stock_id = ? AND date <> '0000-00-00' AND close > 0 ORDER BY date");
    $bars->execute([$sid]);
    $fact = [];
    foreach ($bars->fetchAll(PDO::FETCH_ASSOC) as $b) $fact[$b['date']] = (float)$b['close'];
    $dates = array_keys($fact);
    if (count($dates) < 10) { echo "[skip] {$r['finam_code']}: мало истории\n"; continue; }

    $smooth = [];
    for ($i = 0; $i < count($dates); $i++) {
        $seg = array_slice($fact, max(0, $i - 4), min(5, $i + 1));
        $smooth[$dates[$i]] = array_sum($seg) / count($seg);
    }
    $target = $line === 'blue' ? $smooth : $fact;
    $pos = array_flip(array_keys($target));

    $p = $pdo->prepare("SELECT date, delta FROM $table
        WHERE stock_id = ? AND date <> '0000-00-00' ORDER BY date");
    $p->execute([$sid]);
    $fc = $p->fetchAll(PDO::FETCH_ASSOC);
    if (!$fc) continue;

    $dir_ok = $dir_n = $dir50 = $win_ok = $win_n = 0;
    $mapes = [];

    foreach ($fc as $i => $row) {
        $d = $row['date']; $fd = (float)$row['delta'];
        $idx = $pos[$d] ?? null;
        if ($idx === null || $idx === 0) continue;
        $prev = $target[$dates[$idx - 1]];
        $cur  = $target[$d];
        if ($prev <= 0) continue;
        $ad = ($cur / $prev - 1) * 100;
        $dirOk = ($fd > 0 && $ad > 0) || ($fd < 0 && $ad < 0) || ($fd == 0 && $ad == 0);
        $dir_n++;
        if ($dirOk) { $dir_ok++; if (abs($ad) >= abs($fd) * 0.5) $dir50++; }

        // Окно от даты публикации
        $anchor = null;
        for ($j = $idx - 1; $j >= 0; $j--) if ($target[$dates[$j]] > 0) { $anchor = $target[$dates[$j]]; $aDate = $dates[$j]; break; }
        if ($anchor === null) continue;
        $path = $anchor; $k = 0; $sum = 0.0; $cnt = 0;
        $lastA = $anchor;
        for ($j = $i; $j < count($fc) && $k < $window; $j++) {
            $dj = $fc[$j]['date'];
            if ($dj <= $d) continue;
            if (isset($target[$dj]) && $target[$dj] > 0) {
                $path *= (1 + (float)$fc[$j]['delta'] / 100);
                $sum += abs($path - $target[$dj]) / $target[$dj] * 100;
                $lastA = $target[$dj]; $cnt++; $k++;
            }
        }
        if ($cnt < 10) continue;
        $fTrend = $path / $anchor - 1;
        $aTrend = $lastA / $anchor - 1;
        $win_n++;
        if (($fTrend > 0 && $aTrend > 0) || ($fTrend < 0 && $aTrend < 0) ||
            ($fTrend == 0 && $aTrend == 0)) $win_ok++;
        $mapes[] = $sum / $cnt;
    }

    if ($dir_n) { $G['dir_n'] += $dir_n; $G['dir_ok'] += $dir_ok; $G['dir50_ok'] += $dir50; }
    if ($win_n) { $G['win_n'] += $win_n; $G['win_ok'] += $win_ok; $G['mapes'] = array_merge($G['mapes'], $mapes); }
    sort($mapes);
    $med = $mapes ? $mapes[intdiv(count($mapes), 2)] : 0.0;
    printf("[%s] %-12s dir=%.1f%% (с50%%=%.1f%%) окно%d: тренд=%.1f%% MAPE(мед)=%.2f%% n=%d\n",
        $line, $r['finam_code'], $dir_n ? $dir_ok / $dir_n * 100 : 0,
        $dir_n ? $dir50 / $dir_n * 100 : 0, $window,
        $win_n ? $win_ok / $win_n * 100 : 0, $med, $win_n);
}

sort($G['mapes']);
$medG = $G['mapes'] ? $G['mapes'][intdiv(count($G['mapes']), 2)] : 0.0;
printf("ИТОГО (%s): направление=%.1f%% (из них с амплитудой 50%%: %.1f%%) | окно %d дн.: тренд=%.1f%%, MAPE(медиана)=%.2f%%\n",
    $line, $G['dir_n'] ? $G['dir_ok'] / $G['dir_n'] * 100 : 0,
    $G['dir_n'] ? $G['dir50_ok'] / $G['dir_n'] * 100 : 0,
    $window, $G['win_n'] ? $G['win_ok'] / $G['win_n'] * 100 : 0, $medG);
