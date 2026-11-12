<?php
declare(strict_types=1);
/* Слияние дубликатов cap_stock по (finam_market, finam_code).
   Keeper — id с наибольшим числом баров. Прогнозы перепривязываются,
   бары переносятся (дубликаты дат игнорируются), дубль выключается.
   Запуск: php scripts/merge_sf.php          — dry-run (только показать план)
           php scripts/merge_sf.php --go     — выключить дубли
           php scripts/merge_sf.php --delete — удалить дубли outright */

$cfg = require __DIR__ . '/../config/db.php';
preg_match('/host=([^;]+)/', $cfg['dsn'], $mH);
preg_match('/dbname=([^;]+)/', $cfg['dsn'], $mD);
$pdo = new PDO('mysql:host=' . $mH[1] . ';dbname=' . $mD[1] . ';charset=utf8mb4',
    $cfg['username'], $cfg['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$mode = in_array('--delete', $argv, true) ? 'delete' : (in_array('--go', $argv, true) ? 'go' : 'dry');
if ($mode === 'dry') echo "== DRY RUN, применить: php scripts/merge_sf.php --go ==\n";

$groups = $pdo->query("SELECT finam_market, finam_code FROM cap_stock
    WHERE finam_code IS NOT NULL AND finam_code <> ''
    GROUP BY finam_market, finam_code HAVING COUNT(*) > 1")->fetchAll(PDO::FETCH_ASSOC);

foreach ($groups as $g) {
    $q = $pdo->prepare("SELECT s.id,
        (SELECT COUNT(*) FROM cap_stock_kurs k WHERE k.stock_id = s.id) AS bars
        FROM cap_stock s WHERE s.finam_market = ? AND s.finam_code = ? ORDER BY bars DESC, s.id");
    $q->execute([$g['finam_market'], $g['finam_code']]);
    $cands = $q->fetchAll(PDO::FETCH_ASSOC);
    if (count($cands) < 2) continue;

    $keeper = (int)$cands[0]['id'];
    echo "{$g['finam_code']} (mkt {$g['finam_market']}): keeper id=$keeper ({$cands[0]['bars']} баров)\n";

    foreach (array_slice($cands, 1) as $c) {
        $loser = (int)$c['id'];
        echo "  merged id=$loser ({$c['bars']} баров)\n";
        if ($mode === 'dry') continue;

        // 1. Прогнозы: перепривязка stock_id (колонки kurs там нет и не было)
        foreach (['cap_stock_prognosis_red', 'cap_stock_prognosis_blue'] as $tbl) {
            $pdo->prepare("UPDATE $tbl SET stock_id = ? WHERE stock_id = ?")->execute([$keeper, $loser]);
        }

        // 2. Бары: перенос только дат, которых у keeper ещё нет
        $pdo->prepare("INSERT INTO cap_stock_kurs (stock_id, date, kurs, open, high, low, close, volume)
            SELECT ?, date, kurs, open, high, low, close, volume FROM cap_stock_kurs WHERE stock_id = ?
            AND date NOT IN (SELECT t.date FROM (SELECT date FROM cap_stock_kurs WHERE stock_id = ?) t)")
            ->execute([$keeper, $loser, $keeper]);
        $pdo->prepare("DELETE FROM cap_stock_kurs WHERE stock_id = ?")->execute([$loser]);

        // 3. Каталог: выключить или удалить дубль
        if ($mode === 'delete') {
            $pdo->prepare("DELETE FROM cap_stock WHERE id = ?")->execute([$loser]);
        } else {
            try {
                $pdo->prepare("UPDATE cap_stock SET is_enabled = 0 WHERE id = ?")->execute([$loser]);
            } catch (Throwable $e) {
                echo "  [warn] is_enabled: " . $e->getMessage() . "\n";
            }
        }
    }
}
echo "Готово.\n";
