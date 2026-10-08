<?php
session_start();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($email === 'bilge.demir@flightcore.com' && $password === '@bilge15123.1u65ET€₺#$.!') {
        $_SESSION['user'] = $email;
        $_SESSION['login_time'] = time();
        header('Location: inbox.php');
        exit;
    } else {
        $error = "Invalid credentials. Incident has been logged.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FlightCore MailSuite Enterprise</title>
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="public/assets/css/style.css">
</head>
<body class="login-body">
    <div class="login-container">
        <div class="logo-box">
            <svg class="flight-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/></svg>
            <h1>FlightCore</h1>
            <p>MailSuite v2.4 Enterprise</p>
        </div>
        
        <?php if ($error): ?>
            <div class="error-box"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="index.php" class="login-form">
            <div class="input-group">
                <label for="email">Corporate Identity (Email)</label>
                <input type="text" id="email" name="email" placeholder="bilge.demir@flightcore.com" required>
            </div>
            <div class="input-group">
                <label for="password">Security Passphrase</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn-primary">Secure Login</button>
        </form>
    </div>
</body>
</html>
