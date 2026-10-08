<?php
/**
 * CCTV Login Page
 * Stage 1: Camera system login with MAC address display
 */
require_once __DIR__ . '/auth.php';

// Handle login
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';
    
    if ($user === '666666' && $pass === '666666') {
        $_SESSION['cctv_logged_in'] = true;
        $_SESSION['sensor_values'] = initSensorValues(); // Fresh start per login
        $_SESSION['ics_logged_in'] = false;
        $_SESSION['ics_2fa_verified'] = false;
        $_SESSION['play_success_sound'] = true;
        header('Location: ./dashboard.php');
        exit;
    } else {
        $error = 'Authentication Failed';
    }
}

// If already logged in, redirect
if (isset($_SESSION['cctv_logged_in']) && $_SESSION['cctv_logged_in'] === true) {
    header('Location: ./dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CCTV System - Login</title>
    <link rel="stylesheet" href="./css/login.css">
    <link href="https://fonts.googleapis.com/css2?family=Share+Tech+Mono&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="scanline-overlay"></div>
    <div class="static-noise"></div>
    
    <div class="login-container">
        <div class="camera-frame">
            <div class="frame-corner top-left"></div>
            <div class="frame-corner top-right"></div>
            <div class="frame-corner bottom-left"></div>
            <div class="frame-corner bottom-right"></div>
            
            <div class="system-header">
                <div class="rec-indicator">
                    <span class="rec-dot"></span>
                    <span>REC</span>
                </div>
                <div class="system-time" id="systemTime"></div>
            </div>
            
            <div class="login-content">
                <div class="camera-icon">
                    <svg viewBox="0 0 100 100" width="80" height="80">
                        <circle cx="50" cy="50" r="45" fill="none" stroke="#00ff88" stroke-width="2" opacity="0.3"/>
                        <circle cx="50" cy="50" r="30" fill="none" stroke="#00ff88" stroke-width="1.5" opacity="0.5"/>
                        <circle cx="50" cy="50" r="15" fill="none" stroke="#00ff88" stroke-width="2"/>
                        <circle cx="50" cy="50" r="5" fill="#00ff88"/>
                        <line x1="50" y1="5" x2="50" y2="15" stroke="#00ff88" stroke-width="1" opacity="0.5"/>
                        <line x1="50" y1="85" x2="50" y2="95" stroke="#00ff88" stroke-width="1" opacity="0.5"/>
                        <line x1="5" y1="50" x2="15" y2="50" stroke="#00ff88" stroke-width="1" opacity="0.5"/>
                        <line x1="85" y1="50" x2="95" y2="50" stroke="#00ff88" stroke-width="1" opacity="0.5"/>
                    </svg>
                </div>
                
                <h1 class="login-title">SURVEILLANCE SYSTEM</h1>
                <p class="login-subtitle">AUTHORIZED ACCESS ONLY</p>
                
                <div class="mac-display">
                    <span class="mac-label">DEVICE MAC</span>
                    <span class="mac-value">38:AF:29:11:22:33</span>
                </div>

                <form method="POST" class="login-form" autocomplete="off">
                    <div class="input-group">
                        <label for="username">USER ID</label>
                        <input type="text" id="username" name="username" placeholder="Enter User ID" required>
                        <div class="input-line"></div>
                    </div>
                    
                    <div class="input-group">
                        <label for="password">ACCESS CODE</label>
                        <input type="password" id="password" name="password" placeholder="Enter Access Code" required>
                        <div class="input-line"></div>
                    </div>
                    
                    <?php if ($error): ?>
                    <div class="error-msg">
                        <span class="error-icon">⚠</span>
                        <?= htmlspecialchars($error) ?>
                    </div>
                    <?php endif; ?>
                    
                    <button type="submit" class="login-btn">
                        <span class="btn-text">AUTHENTICATE</span>
                        <span class="btn-arrow">→</span>
                    </button>
                </form>
            </div>
            
            <div class="system-footer">
                <span class="footer-item">CH: 01</span>
                <span class="footer-item">IP: 192.168.1.108</span>
                <span class="footer-item">FW: v3.2.1</span>
                <span class="footer-item status-online">● ONLINE</span>
            </div>
        </div>
    </div>

    <script>
        // System clock
        function updateTime() {
            const now = new Date();
            const formatted = now.getFullYear() + '/' + 
                String(now.getMonth()+1).padStart(2,'0') + '/' + 
                String(now.getDate()).padStart(2,'0') + ' ' + 
                String(now.getHours()).padStart(2,'0') + ':' + 
                String(now.getMinutes()).padStart(2,'0') + ':' + 
                String(now.getSeconds()).padStart(2,'0');
            document.getElementById('systemTime').textContent = formatted;
        }
        updateTime();
        setInterval(updateTime, 1000);
    </script>
    <?php if ($error): ?>
    <script>
        new Audio('./Invalid-Login.mp3').play().catch(e => {});
    </script>
    <?php endif; ?>
</body>
</html>
