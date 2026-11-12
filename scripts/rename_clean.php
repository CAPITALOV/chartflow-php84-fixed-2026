<?php
declare(strict_types=1);
/* Нормализация названий cap_stock из финамовского формата:
   "US. Meiwu Technology Company Limited - Ordinary Shares - WNW"
   -> "Meiwu Technology Company Limited (WNW)"
   Запуск: php scripts/rename_clean.php          — dry-run (20 примеров + счётчики)
           php scripts/rename_clean.php --go     — применить
           php scripts/rename_clean.php --go --keep-class — оставить класс акций */

$cfg = require __DIR__ . '/../config/db.php';
preg_match('/host=([^;]+)/', $cfg['dsn'], $mH);
preg_match('/dbname=([^;]+)/', $cfg['dsn'], $mD);
$pdo = new PDO('mysql:host=' . $mH[1] . ';dbname=' . $mD[1] . ';charset=utf8mb4',
    $cfg['username'], $cfg['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

$go        = in_array('--go', $argv, true);
$keepClass = in_array('--keep-class', $argv, true);

$rows = $pdo->query("SELECT id, name FROM cap_stock
    WHERE name LIKE '% - %'")->fetchAll(PDO::FETCH_ASSOC);

$upd   = $pdo->prepare("UPDATE cap_stock SET name = ? WHERE id = ?");
$done  = 0; $skip = 0; $samples = [];

foreach ($rows as $r) {
    $name  = (string)$r['name'];
    $parts = explode(' - ', $name);

    // Формат: "US. Компания - класс - КОД" (или короче)
    if (count($parts) < 2 || !preg_match('/^[A-Z]{2,4}\.$/u', $parts[0])) { $skip++; continue; }

    $company = $parts[1];
    $class   = count($parts) > 2 ? implode(' - ', array_slice($parts, 2, -1)) : '';
    $code    = end($parts);

    if ($company === '') { $skip++; continue; }
    $new = $keepClass && $class !== ''
        ? $company . ' (' . $class . ') (' . $code . ')'
        : $company . ' (' . $code . ')';

    if ($new === $name) { $skip++; continue; }

    if (!$go && count($samples) < 20) {
        $samples[] = $r['id'] . ": $name\n      -> $new";
    }
    if ($go) $upd->execute([$new, (int)$r['id']]);
    $done++;
}

if (!$go) {
    echo "== DRY RUN, применить: php scripts/rename_clean.php --go ==\n\n";
    echo implode("\n", $samples), "\n\n";
}
echo "К изменению: $done, пропущено (не подходит формат): $skip\n";
