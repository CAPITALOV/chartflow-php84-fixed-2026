cd /var/www/chartflow.ru/data/www/chartflow.ru
cat > scripts/verify_sf.php <<'EOF'
<?php
declare(strict_types=1);
/* Верификация прогнозов по методике StockForecast v2.2.0:
   red — пересчёт delta в цену через cumprod, Правило 50%, Hit-rate, Pearson, MAPE;
   blue — та же проверка против SMA-5 сглаженного факта (RPO-усреднение).
   Запуск: php scripts/verify_sf.php [red|blue] [limit] [window] */
$cfg = @include __DIR__ . '/../config/db.php';
if (!is_array($cfg) || empty($cfg['dsn'])) { fwrite(STDERR, "config/db.php не найден\n"); exit(1); }
preg_match('/host=([^;]+)/', $cfg['dsn'], $m); $host = $m[1] ?? '127.0.0.1';
preg_match('/dbname=([^;]+)/', $cfg['dsn'], $m); $db = $m[1];
$pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4",
    $cfg['username'], $cfg['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
echo "База: $db\n";

$line  = ($argv[1] ?? 'red') === 'blue' ? 'blue' : 'red';
$limit = max(1, (int)($argv[2] ?? 15));
$table = 'cap_stock_prognosis_' . $line;

$rows = $pdo->query("SELECT s.id, s.name, s.finam_code, COUNT(*) c
    FROM $table p JOIN cap_stock s ON s.id = p.stock_id
    WHERE s.finam_code IS NOT NULL AND s.finam_code <> ''
    GROUP BY s.id ORDER BY c DESC LIMIT $limit")->fetchAll(PDO::FETCH_ASSOC);

function pearson(array $a, array $b): float {
    $n = count($a); if ($n < 3) return 0.0;
    $ma = array_sum($a) / $n; $mb = array_sum($b) / $n;
    $sa = $sb = $sab = 0.0;
    for ($i = 0; $i < $n; $i++) {
        $da = $a[$i] - $ma; $db = $b[$i] - $mb;
        $sa += $da * $da; $sb += $db * $db; $sab += $da * $db;
    }
    return ($sa > 0 && $sb > 0) ? $sab / sqrt($sa * $sb) : 0.0;
}

$tot = ['ok' => 0, 'part' => 0, 'fail' => 0];
foreach ($rows as $r) {
    $sid = (int)$r['id'];
    $bars = $pdo->prepare("SELECT date, close FROM cap_stock_kurs
        WHERE stock_id = ? AND date <> '0000-00-00' ORDER BY date");
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
    $tDates = array_keys($target);

    $p = $pdo->prepare("SELECT date, delta FROM $table
        WHERE stock_id = ? AND date <> '0000-00-00' ORDER BY date");
    $p->execute([$sid]);
    $fc = $p->fetchAll(PDO::FETCH_ASSOC);
    if (!$fc) continue;

    $p0 = null;
    foreach ($tDates as $d) if ($d < $fc[0]['date']) $p0 = $target[$d];
    if ($p0 === null) $p0 = reset($target);

    $cum = 1.0; $fPrice = [];
    foreach ($fc as $row) {
        $cum *= (1 + (float)$row['delta'] / 100);
        $fPrice[$row['date']] = $p0 * $cum;
    }

    $okc = $pc = $fl = 0; $fArr = $aArr = []; $mapeS = 0.0; $mapeN = 0;
    foreach ($fc as $row) {
        $d = $row['date'];
        $idx = array_search($d, $tDates, true);
        if ($idx === false || $idx === 0) continue;
        $prev = $target[$tDates[$idx - 1]];
        if ($prev <= 0) continue;
        $ad = ($target[$d] / $prev - 1) * 100;
        $fd = (float)$row['delta'];
        $dirOk = ($fd > 0 && $ad > 0) || ($fd < 0 && $ad < 0) || ($fd == 0 && $ad == 0);
        $ampOk = abs($ad) >= abs($fd) * 0.5;
        if ($dirOk && $ampOk)      { $okc++; $tot['ok']++; }
        elseif ($dirOk)            { $pc++;  $tot['part']++; }
        else                       { $fl++;  $tot['fail']++; }
        $fArr[] = $fPrice[$d]; $aArr[] = $target[$d];
        if ($target[$d] != 0) { $mapeS += abs($fPrice[$d] - $target[$d]) / $target[$d] * 100; $mapeN++; }
    }
    $n = $okc + $pc + $fl;
    if ($n == 0) { echo "[skip] {$r['finam_code']}: пересечений с фактом нет\n"; continue; }
    printf("[%s] %-12s (id=%d) точек=%d hit=%.1f%% partial=%.1f%% fail=%.1f%% pearson=%.3f MAPE=%.2f%%\n",
        $line, $r['finam_code'], $sid, $n,
        $okc / $n * 100, $pc / $n * 100, $fl / $n * 100,
        pearson($fArr, $aArr), $mapeN ? $mapeS / $mapeN : 0.0);
}
printf("ИТОГО (%s): сбылось=%d частично=%d промах=%d hit=%.1f%%\n",
    $line, $tot['ok'], $tot['part'], $tot['fail'],
    ($tot['ok'] + $tot['part']) > 0 ? $tot['ok'] / ($tot['ok'] + $tot['part']) * 100 : 0.0);
EOF
php -l scripts/verify_sf.php
php scripts/verify_sf.php red 15
php scripts/verify_sf.php blue 15
