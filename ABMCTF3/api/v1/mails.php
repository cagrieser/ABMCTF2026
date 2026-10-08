<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}
if (!isset($_SESSION['login_time'])) {
    $_SESSION['login_time'] = time();
}

require_once '../../includes/MailGeneratorService.php';

$folder = $_GET['folder'] ?? 'inbox';
$counts = MailGeneratorService::getCounts();
echo json_encode(['status' => 'success', 'data' => MailGeneratorService::generate($folder), 'counts' => $counts]);
