<?php
/**
 * Stream Report API - Report quality issues or illegal content
 * Looks like it might accept file uploads but doesn't.
 */
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(['error' => 'ERR_METHOD', 'message' => 'POST only', 'fields' => ['channel', 'reason', 'description']]);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) $input = $_POST;

$channel = isset($input['channel']) ? htmlspecialchars(trim($input['channel'])) : '';
$reason = isset($input['reason']) ? htmlspecialchars(trim($input['reason'])) : '';
$desc = isset($input['description']) ? htmlspecialchars(trim($input['description'])) : '';

if (empty($channel) || empty($reason)) {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'ERR_MISSING_FIELDS', 'required' => ['channel', 'reason']]);
    exit;
}

$validReasons = ['buffering', 'quality', 'audio_sync', 'black_screen', 'wrong_match', 'other'];
if (!in_array($reason, $validReasons)) {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'ERR_INVALID_REASON', 'valid' => $validReasons]);
    exit;
}

echo json_encode([
    'status' => 'received',
    'ticket_id' => 'RPT-' . strtoupper(substr(md5(time() . rand()), 0, 10)),
    'channel' => $channel,
    'reason' => $reason,
    'message' => 'Bildiriminiz alındı. Teknik ekip inceleyecektir.',
    'eta' => rand(5, 30) . ' dakika',
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
