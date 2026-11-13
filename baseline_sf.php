<?php
declare(strict_types=1);
/* Baseline: доля фактических ростов/падений за окно N дней по всем тикерам.
   Сравнить с тренд-хитом прогнозов: если равны — навыка нет.
   Запуск: php scripts/baseline_sf.php [window] */
$cfg = @include __DIR__ . '/../config/db.php';
if (!is_array($cfg) || empty($cfg['dsn'])) { fwrite(STDERR, "config/db.php не найден\n"); exit(1); }
preg_match('/host=([^;]+)/', $cfg['dsn'], $m); $host = $m[1] ?? '127.0.0.1';
preg_match('/dbname=([^;]+)/', $cfg['dsn'], $m); $db = $m[1];
$pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4",
    $cfg['username'], $cfg['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$window = max(5, (int)($argv[1] ?? 90));

$rows = $pdo->query("SELECT s.id, s.finam_code FROM cap_stock s
    WHERE s.finam_code IS NOT NULL AND s.finam_code <> ''
    LIMIT 500")->fetchAll(PDO::FETCH_ASSOC);

$up = $down = 0; $t0 = time(); $done = 0;
foreach ($rows as $r) {
    $q = $pdo->prepare("SELECT close FROM cap_stock_kurs
        WHERE stock_id = ? AND date <> '0000-00-00' AND close > 0 ORDER BY date");
    $q->execute([$r['id']]);
    $c = $q->fetchAll(PDO::FETCH_COLUMN);
    $n = count($c);
    // шаг 30 баров, сравниваем close на концах окна
    for ($i = 0; $i + $window <= $n; $i += 30) {
        if ($c[$i + $window] > $c[$i]) $up++; else $down++;
    }
    $done++;
    if ($done % 50 == 0) echo "обработано $done тикеров...\n";
}
$tot = $up + $down;
printf("\nТикеров: %d, окон: %d\nФакт: рост=%d (%.1f%%), падение=%d (%.1f%%)\n",
    $done, $tot,
    $up, $tot ? $up / $tot * 100 : 0,
    $down, $tot ? $down / $tot * 100 : 0);
