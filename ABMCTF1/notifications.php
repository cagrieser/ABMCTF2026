<?php
/**
 * Notification API - Get user notifications
 */
header('Content-Type: application/json; charset=utf-8');

$notifications = [
    ['id' => 'n1', 'type' => 'match_start', 'title' => '🇹🇷 Türkiye - Gürcistan 🇬🇪 başladı!', 'time' => '67 dk önce', 'read' => true],
    ['id' => 'n2', 'type' => 'goal', 'title' => '⚽ GOL! Türkiye 1 - 0 Gürcistan', 'time' => '42 dk önce', 'read' => true],
    ['id' => 'n3', 'type' => 'server', 'title' => '🔧 Avrupa #1 sunucusu bakıma alındı', 'time' => '15 dk önce', 'read' => false],
    ['id' => 'n4', 'type' => 'match_start', 'title' => '🇧🇷 Brezilya - Sırbistan 🇷🇸 başladı!', 'time' => '42 dk önce', 'read' => true],
    ['id' => 'n5', 'type' => 'system', 'title' => '⚡ StreamCore v3.8.2 güncellendi', 'time' => '2 saat önce', 'read' => true],
    ['id' => 'n6', 'type' => 'match_upcoming', 'title' => '🇦🇷 Arjantin - Meksika 🇲🇽 yakında!', 'time' => '21:00', 'read' => false],
    ['id' => 'n7', 'type' => 'promotion', 'title' => '🎁 VIP üyelik %50 indirimli!', 'time' => '3 saat önce', 'read' => true],
];

$unread = isset($_GET['unread']) && $_GET['unread'] === 'true';
if ($unread) {
    $notifications = array_values(array_filter($notifications, function($n) { return !$n['read']; }));
}

echo json_encode([
    'notifications' => $notifications,
    'unread_count' => count(array_filter($notifications, function($n) { return !$n['read']; })),
    'total' => count($notifications),
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
