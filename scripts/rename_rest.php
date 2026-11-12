<?php
declare(strict_types=1);
/* Добивка после rename_exchange.php:
   1) рынки не 1/2: "US. Компания - класс - КОД" -> "Компания (КОД)" + описание
   2) мусорные описания вида "CODE 20200323_..._CLOSE_..." -> нормальное описание
   Запуск: php scripts/rename_rest.php        — dry-run
           php scripts/rename_rest.php --go   — применить */

$cfg = require __DIR__ . '/../config/db.php';
preg_match('/host=([^;]+)/', $cfg['dsn'], $mH);
preg_match('/dbname=([^;]+)/', $cfg['dsn'], $mD);
$pdo = new PDO('mysql:host=' . $mH[1] . ';dbname=' . $mD[1] . ';charset=utf8mb4',
    $cfg['username'], $cfg['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$go = in_array('--go', $argv, true);

$periods = $pdo->query("SELECT s.id, MIN(k.date) d1, MAX(k.date) d2
    FROM cap_stock s JOIN cap_stock_kurs k ON k.stock_id = s.id
    GROUP BY s.id")->fetchAll(PDO::FETCH_UNIQUE);

$upd = $pdo->prepare("UPDATE cap_stock SET name = ?, description = ? WHERE id = ?");
$updDesc = $pdo->prepare("UPDATE cap_stock SET description = ? WHERE id = ?");
$done = 0; $descFixed = 0; $skip = 0; $samples = [];

function withPeriod(array $p, int|string $id, string $base): string {
    $d = isset($p[$id]['d1']) ? $base . '. Котировки: '
        . substr($p[$id]['d1'], 0, 4) . '–' . substr($p[$id]['d2'], 0, 4) . '.' : $base;
    return mb_strlen($d) > 255 ? mb_substr($d, 0, 252) . '...' : $d;
}

// --- 1. Финамовские названия на рынках не 1/2 ---
$rows = $pdo->query("SELECT id, finam_code, name FROM cap_stock
    WHERE finam_market NOT IN (1,2) AND finam_code IS NOT NULL AND finam_code <> ''
    AND name LIKE '% - %'")->fetchAll(PDO::FETCH_ASSOC);

foreach ($rows as $r) {
    $parts = explode(' - ', (string)$r['name']);
    if (count($parts) < 2) { $skip++; continue; }
    // первый сегмент должен начинаться со странового префикса "US. ", "RU. " и т.п.
    if (!preg_match('/^[A-Z]{2,4}\.\s*\S/u', $parts[0])) { $skip++; continue; }
    // последний сегмент обязан совпадать с кодом — иначе не наш формат
    if (trim(end($parts)) !== trim($r['finam_code'])) { $skip++; continue; }

    $company = trim(preg_replace('/^[A-Z]{2,4}\.\s*/u', '', array_shift($parts)));
    array_pop($parts); // код
    if ($company === '') { $skip++; continue; }

    $code = strtoupper($r['finam_code']);
    $new  = $company . ' (' . $code . ')';
    $desc = withPeriod($periods, $r['id'], $company . ' (' . $code . ')');

    if ($new === $r['name']) { $skip++; continue; }
    if (!$go && count($samples) < 25)
        $samples[] = $r['id'] . ": {$r['name']}\n      -> $new\n      desc: $desc";
    if ($go) $upd->execute([$new, $desc, (int)$r['id']]);
    $done++;
}

// --- 2. Мусорные описания (слипшийся финамовский код) ---
$garbage = $pdo->query("SELECT id, name FROM cap_stock
    WHERE description REGEXP '^[A-Z0-9]+ [0-9]{8}_.+CLOSE_.+'")->fetchAll(PDO::FETCH_ASSOC);
foreach ($garbage as $g) {
    if (trim((string)$g['name']) === '') continue;
    $desc = withPeriod($periods, $g['id'], trim($g['name']));
    if (!$go && count($samples) < 25)
        $samples[] = $g['id'] . ": [desc-фикс] -> $desc";
    if ($go) $updDesc->execute([$desc, (int)$g['id']]);
    $descFixed++;
}

if (!$go) { echo "== DRY RUN, применить: php scripts/rename_rest.php --go ==\n\n", implode("\n", $samples), "\n\n"; }
echo "Названий исправлено: $done, описаний почищено: $descFixed, пропущено: $skip\n";
