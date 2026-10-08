<?php
session_start();
$id = $_GET['id'] ?? '';
if ($id) {
    if (!isset($_SESSION['read_mails'])) {
        $_SESSION['read_mails'] = [];
    }
    if (!in_array($id, $_SESSION['read_mails'])) {
        $_SESSION['read_mails'][] = $id;
    }
}
echo json_encode(['status'=>'success']);
