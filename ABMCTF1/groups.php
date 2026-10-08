<?php
/**
 * Group Standings API
 * Returns World Cup group standings with team statistics.
 * 
 * NOTE: Decoy endpoint - no vulnerability here.
 */

header('Content-Type: application/json; charset=utf-8');
header('X-Powered-By: StreamGuard/2.1');

$group = isset($_GET['group']) ? strtoupper(trim($_GET['group'])) : 'ALL';
$group = preg_replace('/[^A-Z]/', '', $group);

$standings = [
    'F' => [
        'name' => 'Grup F',
        'teams' => [
            ['pos' => 1, 'team' => 'Türkiye', 'flag' => '🇹🇷', 'played' => 1, 'won' => 1, 'drawn' => 0, 'lost' => 0, 'gf' => 1, 'ga' => 0, 'gd' => '+1', 'points' => 3],
            ['pos' => 2, 'team' => 'Portekiz', 'flag' => '🇵🇹', 'played' => 0, 'won' => 0, 'drawn' => 0, 'lost' => 0, 'gf' => 0, 'ga' => 0, 'gd' => '0', 'points' => 0],
            ['pos' => 3, 'team' => 'Gana', 'flag' => '🇬🇭', 'played' => 0, 'won' => 0, 'drawn' => 0, 'lost' => 0, 'gf' => 0, 'ga' => 0, 'gd' => '0', 'points' => 0],
            ['pos' => 4, 'team' => 'Gürcistan', 'flag' => '🇬🇪', 'played' => 1, 'won' => 0, 'drawn' => 0, 'lost' => 1, 'gf' => 0, 'ga' => 1, 'gd' => '-1', 'points' => 0],
        ]
    ],
    'A' => [
        'name' => 'Grup A',
        'teams' => [
            ['pos' => 1, 'team' => 'Hollanda', 'flag' => '🇳🇱', 'played' => 2, 'won' => 1, 'drawn' => 1, 'lost' => 0, 'gf' => 3, 'ga' => 1, 'gd' => '+2', 'points' => 4],
            ['pos' => 2, 'team' => 'Ekvador', 'flag' => '🇪🇨', 'played' => 2, 'won' => 1, 'drawn' => 1, 'lost' => 0, 'gf' => 3, 'ga' => 1, 'gd' => '+2', 'points' => 4],
            ['pos' => 3, 'team' => 'Senegal', 'flag' => '🇸🇳', 'played' => 2, 'won' => 1, 'drawn' => 0, 'lost' => 1, 'gf' => 3, 'ga' => 3, 'gd' => '0', 'points' => 3],
            ['pos' => 4, 'team' => 'Katar', 'flag' => '🇶🇦', 'played' => 2, 'won' => 0, 'drawn' => 0, 'lost' => 2, 'gf' => 1, 'ga' => 5, 'gd' => '-4', 'points' => 0],
        ]
    ],
    'E' => [
        'name' => 'Grup E',
        'teams' => [
            ['pos' => 1, 'team' => 'Japonya', 'flag' => '🇯🇵', 'played' => 1, 'won' => 1, 'drawn' => 0, 'lost' => 0, 'gf' => 1, 'ga' => 0, 'gd' => '+1', 'points' => 3],
            ['pos' => 2, 'team' => 'İspanya', 'flag' => '🇪🇸', 'played' => 0, 'won' => 0, 'drawn' => 0, 'lost' => 0, 'gf' => 0, 'ga' => 0, 'gd' => '0', 'points' => 0],
            ['pos' => 3, 'team' => 'Kosta Rika', 'flag' => '🇨🇷', 'played' => 0, 'won' => 0, 'drawn' => 0, 'lost' => 0, 'gf' => 0, 'ga' => 0, 'gd' => '0', 'points' => 0],
            ['pos' => 4, 'team' => 'Almanya', 'flag' => '🇩🇪', 'played' => 1, 'won' => 0, 'drawn' => 0, 'lost' => 1, 'gf' => 0, 'ga' => 1, 'gd' => '-1', 'points' => 0],
        ]
    ],
    'G' => [
        'name' => 'Grup G',
        'teams' => [
            ['pos' => 1, 'team' => 'Brezilya', 'flag' => '🇧🇷', 'played' => 1, 'won' => 1, 'drawn' => 0, 'lost' => 0, 'gf' => 1, 'ga' => 0, 'gd' => '+1', 'points' => 3],
            ['pos' => 2, 'team' => 'İsviçre', 'flag' => '🇨🇭', 'played' => 1, 'won' => 1, 'drawn' => 0, 'lost' => 0, 'gf' => 1, 'ga' => 0, 'gd' => '+1', 'points' => 3],
            ['pos' => 3, 'team' => 'Sırbistan', 'flag' => '🇷🇸', 'played' => 1, 'won' => 0, 'drawn' => 0, 'lost' => 1, 'gf' => 0, 'ga' => 1, 'gd' => '-1', 'points' => 0],
            ['pos' => 4, 'team' => 'Kamerun', 'flag' => '🇨🇲', 'played' => 1, 'won' => 0, 'drawn' => 0, 'lost' => 1, 'gf' => 0, 'ga' => 1, 'gd' => '-1', 'points' => 0],
        ]
    ],
];

$result = [];
if ($group === 'ALL') {
    $result = $standings;
} elseif (isset($standings[$group])) {
    $result = [$group => $standings[$group]];
} else {
    header('HTTP/1.1 404 Not Found');
    echo json_encode(['error' => 'ERR_GROUP_NOT_FOUND', 'message' => 'Geçersiz grup', 'available' => array_keys($standings)], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

echo json_encode([
    'tournament' => 'FIFA Dünya Kupası 2026',
    'standings' => $result,
    'updated_at' => date('Y-m-d H:i:s'),
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
