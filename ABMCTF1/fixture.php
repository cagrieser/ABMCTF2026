<?php
/**
 * World Cup Fixture API
 * Returns match schedule data for the World Cup groups.
 * 
 * NOTE: This is a decoy endpoint - no vulnerability here.
 * Players may waste time analyzing this for injection points.
 */

header('Content-Type: application/json; charset=utf-8');
header('X-Powered-By: StreamGuard/2.1');
header('Cache-Control: public, max-age=300');

$group = isset($_GET['group']) ? strtoupper(trim($_GET['group'])) : 'ALL';
$round = isset($_GET['round']) ? intval($_GET['round']) : 0;

// Sanitize inputs (looks like it might be vulnerable but isn't)
$group = preg_replace('/[^A-Z]/', '', $group);

$fixtures = [
    'A' => [
        ['id' => 'A1', 'home' => 'Katar', 'away' => 'Ekvador', 'home_flag' => '🇶🇦', 'away_flag' => '🇪🇨', 'score' => '0 - 2', 'status' => 'finished', 'date' => '2026-06-14', 'time' => '19:00', 'stadium' => 'Al Bayt Stadium', 'round' => 1],
        ['id' => 'A2', 'home' => 'Senegal', 'away' => 'Hollanda', 'home_flag' => '🇸🇳', 'away_flag' => '🇳🇱', 'score' => '0 - 2', 'status' => 'finished', 'date' => '2026-06-14', 'time' => '21:00', 'stadium' => 'Al Thumama Stadium', 'round' => 1],
        ['id' => 'A3', 'home' => 'Katar', 'away' => 'Senegal', 'home_flag' => '🇶🇦', 'away_flag' => '🇸🇳', 'score' => '1 - 3', 'status' => 'finished', 'date' => '2026-06-19', 'time' => '16:00', 'stadium' => 'Al Thumama Stadium', 'round' => 2],
        ['id' => 'A4', 'home' => 'Hollanda', 'away' => 'Ekvador', 'home_flag' => '🇳🇱', 'away_flag' => '🇪🇨', 'score' => '1 - 1', 'status' => 'finished', 'date' => '2026-06-19', 'time' => '19:00', 'stadium' => 'Khalifa International', 'round' => 2],
    ],
    'B' => [
        ['id' => 'B1', 'home' => 'İngiltere', 'away' => 'İran', 'home_flag' => '🏴󠁧󠁢󠁥󠁮󠁧󠁿', 'away_flag' => '🇮🇷', 'score' => '6 - 2', 'status' => 'finished', 'date' => '2026-06-14', 'time' => '16:00', 'stadium' => 'Khalifa International', 'round' => 1],
        ['id' => 'B2', 'home' => 'ABD', 'away' => 'Galler', 'home_flag' => '🇺🇸', 'away_flag' => '🏴󠁧󠁢󠁷󠁬󠁳󠁿', 'score' => '1 - 1', 'status' => 'finished', 'date' => '2026-06-14', 'time' => '22:00', 'stadium' => 'Ahmad Bin Ali Stadium', 'round' => 1],
    ],
    'F' => [
        ['id' => 'F1', 'home' => 'Türkiye', 'away' => 'Gürcistan', 'home_flag' => '🇹🇷', 'away_flag' => '🇬🇪', 'score' => '1 - 0', 'status' => 'live', 'minute' => 67, 'date' => '2026-06-18', 'time' => '19:00', 'stadium' => 'Lusail Stadium', 'round' => 1],
        ['id' => 'F2', 'home' => 'Portekiz', 'away' => 'Gana', 'home_flag' => '🇵🇹', 'away_flag' => '🇬🇭', 'score' => '—', 'status' => 'upcoming', 'date' => '2026-06-18', 'time' => '22:00', 'stadium' => 'Stadium 974', 'round' => 1],
        ['id' => 'F3', 'home' => 'Türkiye', 'away' => 'Portekiz', 'home_flag' => '🇹🇷', 'away_flag' => '🇵🇹', 'score' => '—', 'status' => 'upcoming', 'date' => '2026-06-22', 'time' => '19:00', 'stadium' => 'Education City Stadium', 'round' => 2],
        ['id' => 'F4', 'home' => 'Gürcistan', 'away' => 'Gana', 'home_flag' => '🇬🇪', 'away_flag' => '🇬🇭', 'score' => '—', 'status' => 'upcoming', 'date' => '2026-06-22', 'time' => '19:00', 'stadium' => 'Al Janoub Stadium', 'round' => 2],
    ],
    'C' => [
        ['id' => 'C1', 'home' => 'Arjantin', 'away' => 'Suudi Arabistan', 'home_flag' => '🇦🇷', 'away_flag' => '🇸🇦', 'score' => '1 - 2', 'status' => 'finished', 'date' => '2026-06-15', 'time' => '13:00', 'stadium' => 'Lusail Stadium', 'round' => 1],
        ['id' => 'C2', 'home' => 'Meksika', 'away' => 'Polonya', 'home_flag' => '🇲🇽', 'away_flag' => '🇵🇱', 'score' => '0 - 0', 'status' => 'finished', 'date' => '2026-06-15', 'time' => '19:00', 'stadium' => 'Stadium 974', 'round' => 1],
    ],
    'E' => [
        ['id' => 'E1', 'home' => 'Almanya', 'away' => 'Japonya', 'home_flag' => '🇩🇪', 'away_flag' => '🇯🇵', 'score' => '0 - 1', 'status' => 'live', 'minute' => 18, 'date' => '2026-06-18', 'time' => '16:00', 'stadium' => 'Khalifa International', 'round' => 1],
        ['id' => 'E2', 'home' => 'İspanya', 'away' => 'Kosta Rika', 'home_flag' => '🇪🇸', 'away_flag' => '🇨🇷', 'score' => '—', 'status' => 'upcoming', 'date' => '2026-06-18', 'time' => '22:00', 'stadium' => 'Al Thumama Stadium', 'round' => 1],
    ],
    'G' => [
        ['id' => 'G1', 'home' => 'Brezilya', 'away' => 'Sırbistan', 'home_flag' => '🇧🇷', 'away_flag' => '🇷🇸', 'score' => '1 - 0', 'status' => 'live', 'minute' => 42, 'date' => '2026-06-18', 'time' => '18:00', 'stadium' => 'Lusail Stadium', 'round' => 1],
        ['id' => 'G2', 'home' => 'İsviçre', 'away' => 'Kamerun', 'home_flag' => '🇨🇭', 'away_flag' => '🇨🇲', 'score' => '1 - 0', 'status' => 'finished', 'date' => '2026-06-17', 'time' => '13:00', 'stadium' => 'Al Janoub Stadium', 'round' => 1],
    ],
    'H' => [
        ['id' => 'H1', 'home' => 'Fransa', 'away' => 'Avustralya', 'home_flag' => '🇫🇷', 'away_flag' => '🇦🇺', 'score' => '—', 'status' => 'upcoming', 'date' => '2026-06-18', 'time' => '00:00', 'stadium' => 'Al Janoub Stadium', 'round' => 1],
        ['id' => 'H2', 'home' => 'Danimarka', 'away' => 'Tunus', 'home_flag' => '🇩🇰', 'away_flag' => '🇹🇳', 'score' => '0 - 0', 'status' => 'finished', 'date' => '2026-06-17', 'time' => '16:00', 'stadium' => 'Education City Stadium', 'round' => 1],
    ],
];

$result = [];
if ($group === 'ALL') {
    $result = $fixtures;
} elseif (isset($fixtures[$group])) {
    $result = [$group => $fixtures[$group]];
} else {
    header('HTTP/1.1 404 Not Found');
    echo json_encode(['error' => 'ERR_GROUP_NOT_FOUND', 'message' => 'Invalid group: ' . $group, 'valid_groups' => array_keys($fixtures)], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// Filter by round if specified
if ($round > 0) {
    foreach ($result as $g => &$matches) {
        $matches = array_values(array_filter($matches, function($m) use ($round) {
            return $m['round'] === $round;
        }));
    }
}

echo json_encode([
    'tournament' => 'FIFA Dünya Kupası 2026',
    'total_matches' => array_sum(array_map('count', $result)),
    'fixtures' => $result,
    'last_updated' => date('Y-m-d H:i:s'),
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
