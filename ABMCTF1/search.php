<?php
/**
 * Search API - Search matches, teams, players
 * Looks like it might have SQLi but uses no DB.
 */
header('Content-Type: application/json; charset=utf-8');

$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$type = isset($_GET['type']) ? trim($_GET['type']) : 'all';

if (empty($q)) {
    echo json_encode(['error' => 'ERR_EMPTY_QUERY', 'message' => 'Arama terimi gerekli', 'usage' => 'search.php?q=turkiye&type=all']);
    exit;
}

$q_lower = mb_strtolower($q, 'UTF-8');
$allResults = [
    ['type' => 'team', 'name' => 'Türkiye', 'flag' => '🇹🇷', 'group' => 'F', 'rank' => 42],
    ['type' => 'team', 'name' => 'Gürcistan', 'flag' => '🇬🇪', 'group' => 'F', 'rank' => 78],
    ['type' => 'team', 'name' => 'Brezilya', 'flag' => '🇧🇷', 'group' => 'G', 'rank' => 1],
    ['type' => 'team', 'name' => 'Almanya', 'flag' => '🇩🇪', 'group' => 'E', 'rank' => 11],
    ['type' => 'team', 'name' => 'Arjantin', 'flag' => '🇦🇷', 'group' => 'C', 'rank' => 3],
    ['type' => 'team', 'name' => 'Fransa', 'flag' => '🇫🇷', 'group' => 'H', 'rank' => 4],
    ['type' => 'team', 'name' => 'Portekiz', 'flag' => '🇵🇹', 'group' => 'F', 'rank' => 9],
    ['type' => 'match', 'name' => 'Türkiye vs Gürcistan', 'status' => 'live', 'score' => '1-0', 'group' => 'F'],
    ['type' => 'match', 'name' => 'Brezilya vs Sırbistan', 'status' => 'live', 'score' => '1-0', 'group' => 'G'],
    ['type' => 'match', 'name' => 'Almanya vs Japonya', 'status' => 'live', 'score' => '0-1', 'group' => 'E'],
    ['type' => 'channel', 'name' => 'Kanal 1 - TUR vs GEO', 'status' => 'online', 'viewers' => rand(2000,5000)],
    ['type' => 'channel', 'name' => 'Kanal 2 - BRA vs SRB', 'status' => 'online', 'viewers' => rand(3000,8000)],
    ['type' => 'channel', 'name' => 'Kanal 3 - GER vs JPN', 'status' => 'online', 'viewers' => rand(1500,4000)],
];

$results = array_filter($allResults, function($item) use ($q_lower, $type) {
    $nameMatch = mb_strpos(mb_strtolower($item['name'], 'UTF-8'), $q_lower, 0, 'UTF-8') !== false;
    $typeMatch = ($type === 'all' || $item['type'] === $type);
    return $nameMatch && $typeMatch;
});

echo json_encode([
    'query' => htmlspecialchars($q),
    'type' => $type,
    'count' => count($results),
    'results' => array_values($results),
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
