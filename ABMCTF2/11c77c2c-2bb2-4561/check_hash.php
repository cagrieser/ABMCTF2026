<?php
/**
 * Hash Verification Endpoint
 * Checks uploaded file hash against expected value via XHR
 */
require_once dirname(__DIR__) . '/auth.php';

header('Content-Type: application/json');

// Must be logged in to CCTV and ICS
if (!isset($_SESSION['cctv_logged_in']) || $_SESSION['cctv_logged_in'] !== true ||
    !isset($_SESSION['ics_logged_in']) || $_SESSION['ics_logged_in'] !== true) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['keyfile'])) {
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed or file missing']);
    exit;
}

// Expected hash value provided by the user
$expectedHash = 'bc7e2596737fccf9f22697a55112b321';
$fileHash = md5_file($_FILES['keyfile']['tmp_name']);

if (strtolower($fileHash) === strtolower($expectedHash)) {
    $_SESSION['ics_2fa_verified'] = true;
    echo json_encode(['status' => 'success', 'message' => 'Authorization verified']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Hash mismatch']);
}
?>
