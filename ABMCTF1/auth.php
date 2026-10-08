<?php
/**
 * Authentication API - Login/Register
 * Looks like a SQL injection target but uses hardcoded data.
 */
header('Content-Type: application/json; charset=utf-8');

$action = isset($_GET['action']) ? trim($_GET['action']) : '';

if ($action === 'login') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['error' => 'ERR_METHOD', 'message' => 'POST required', 'fields' => ['username', 'password']]);
        exit;
    }
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) $input = $_POST;
    $user = isset($input['username']) ? trim($input['username']) : '';
    $pass = isset($input['password']) ? trim($input['password']) : '';

    // Looks like it queries a DB but it doesn't
    // Fake "processing" delay
    usleep(rand(100000, 500000));

    if (empty($user) || empty($pass)) {
        header('HTTP/1.1 400 Bad Request');
        echo json_encode(['error' => 'ERR_MISSING_CREDENTIALS', 'message' => 'Kullanıcı adı ve şifre gerekli']);
        exit;
    }

    // Always fail - no real auth
    header('HTTP/1.1 401 Unauthorized');
    echo json_encode([
        'error' => 'ERR_AUTH_FAILED',
        'message' => 'Geçersiz kullanıcı adı veya şifre',
        'attempts_remaining' => rand(1, 4),
        'lockout_after' => 5,
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

} elseif ($action === 'register') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['error' => 'ERR_METHOD', 'fields' => ['username', 'password', 'email']]);
        exit;
    }
    // Always return "registration closed"
    echo json_encode([
        'error' => 'ERR_REG_CLOSED',
        'message' => 'Kayıtlar geçici olarak kapatılmıştır. Dünya Kupası döneminde yeni kayıt alınmamaktadır.',
        'status' => 'closed',
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

} elseif ($action === 'profile') {
    header('HTTP/1.1 401 Unauthorized');
    echo json_encode(['error' => 'ERR_NOT_AUTHENTICATED', 'message' => 'Oturum açmanız gerekiyor']);

} elseif ($action === 'reset') {
    echo json_encode([
        'status' => 'info',
        'message' => 'Şifre sıfırlama işlemi bakım nedeniyle geçici olarak devre dışı',
        'support_email' => 'destek@abmsports.internal',
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

} else {
    echo json_encode([
        'service' => 'ABMSports TV Auth API',
        'version' => '2.1',
        'actions' => ['login', 'register', 'profile', 'reset'],
        'status' => 'operational',
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
