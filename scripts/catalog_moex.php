<?php
declare(strict_types=1);
/* Каталог тикеров MOEX (board TQBR) через ISS history-securities. */
$cfg = require __DIR__ . '/../config/db.php';
preg_match('/host=([^;]+)/', $cfg['dsn'], $mH);
preg_match('/dbname=([^;]+)/', $cfg['dsn'], $mD);
$pdo = new PDO('mysql:host=' . $mH[1] . ';dbname=' . $mD[1] . ';charset=utf8mb4',
    $cfg['username'], $cfg['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

function httpGet(string $url): string {
    $code = 0;
    for ($i = 0; $i < 3; $i++) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true, CURLOPT_USERAGENT => 'Mozilla/5.0']);
        $body = curl_exec($ch); $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($body !== false && $code == 200) return $body;
        usleep(1000000);
    }
    throw new RuntimeException("HTTP failed ($code): $url");
}

$check = $pdo->prepare("SELECT id FROM cap_stock WHERE finam_market = 1 AND finam_code = ?");
$upd   = $pdo->prepare("UPDATE cap_stock SET is_enabled = 1, name = ? WHERE id = ?");
$ins   = $pdo->prepare("INSERT INTO cap_stock (name, finam_market, finam_code, status, is_kurs, is_enabled) VALUES (?, 1, ?, 2, 1, 1)");

$n = 0;
for ($start = 0; $start < 50000; $start += 100) {
    $url = "https://iss.moex.com/iss/engines/stock/markets/shares/boards/TQBR/securities.json"
         . "?limit=100&start={$start}&iss.meta=off&iss.only=securities";
    $raw = json_decode(httpGet($url), true);
    if (empty($raw['securities']['data'])) break;
    $c = array_flip($raw['securities']['columns']);
    if (!isset($c['SECID'])) { fwrite(STDERR, "Неожиданный формат ответа MOEX, страница $start\n"); break; }
    foreach ($raw['securities']['data'] as $r) {
        $code = $r[$c['SECID']];
        if (!is_string($code) || !preg_match('/^[A-Z0-9\-]{1,10}$/', $code)) continue;
        $name = $r[$c['SHORTNAME']] ?? $code;
        $title = "RU. {$name} - {$code}";
        $check->execute([$code]);
        $id = $check->fetchColumn();
        if ($id) $upd->execute([$title, $id]); else $ins->execute([$title, $code]);
        $n++;
    }
    if (count($raw['securities']['data']) < 100) break;
    usleep(300000);
}
if ($n === 0) fwrite(STDERR, "Пусто: проверьте ответ MOEX вручную\n");
echo "MOEX каталог: $n тикеров\n";
