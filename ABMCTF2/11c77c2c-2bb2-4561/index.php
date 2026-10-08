<?php
/**
 * ICS/SCADA Login Page
 * Stage 3: Hidden endpoint discovered from CCTV video
 */
require_once dirname(__DIR__) . '/auth.php';
requireCCTVLogin();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';
    
    if ($user === 'bilgehan.425d-52bb62-4551@icscontrolsystems.local' && $pass === 'ics528c8bn91a') {
        $_SESSION['ics_logged_in'] = true;
        $_SESSION['ics_2fa_verified'] = false;
        $_SESSION['play_success_sound'] = true;
        header('Location: ./verify.php');
        exit;
    } else {
        $error = 'Invalid credentials. Access denied.';
    }
}

// If already fully authenticated, go to panel
if (isset($_SESSION['ics_2fa_verified']) && $_SESSION['ics_2fa_verified'] === true) {
    header('Location: ./panel.php');
    exit;
}

// If ICS logged in but not 2FA'd, go to verify
if (isset($_SESSION['ics_logged_in']) && $_SESSION['ics_logged_in'] === true && 
    (!isset($_SESSION['ics_2fa_verified']) || $_SESSION['ics_2fa_verified'] !== true)) {
    header('Location: ./verify.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ICS Control Systems - Authentication</title>
    <link rel="stylesheet" href="../css/ics.css">
    <link href="https://fonts.googleapis.com/css2?family=Share+Tech+Mono&family=Rajdhani:wght@300;400;500;600;700&family=Orbitron:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="grid-bg"></div>
    <div class="particles" id="particles"></div>
    
    <div class="ics-login-container">
        <div class="login-panel">
            <div class="panel-glow"></div>
            
            <!-- Header -->
            <div class="panel-header">
                <div class="hex-icon">
                    <svg viewBox="0 0 120 120" width="70" height="70">
                        <polygon points="60,5 110,30 110,90 60,115 10,90 10,30" fill="none" stroke="#00d4ff" stroke-width="2" opacity="0.3"/>
                        <polygon points="60,20 95,38 95,82 60,100 25,82 25,38" fill="none" stroke="#00d4ff" stroke-width="1.5" opacity="0.5"/>
                        <circle cx="60" cy="60" r="15" fill="none" stroke="#00d4ff" stroke-width="2"/>
                        <circle cx="60" cy="60" r="5" fill="#00d4ff"/>
                        <!-- Connections -->
                        <line x1="60" y1="45" x2="60" y2="20" stroke="#00d4ff" stroke-width="0.5" opacity="0.4"/>
                        <line x1="60" y1="75" x2="60" y2="100" stroke="#00d4ff" stroke-width="0.5" opacity="0.4"/>
                        <line x1="45" y1="60" x2="25" y2="60" stroke="#00d4ff" stroke-width="0.5" opacity="0.4"/>
                        <line x1="75" y1="60" x2="95" y2="60" stroke="#00d4ff" stroke-width="0.5" opacity="0.4"/>
                    </svg>
                </div>
                <h1 class="panel-title">ICS CONTROL SYSTEMS</h1>
                <p class="panel-sub">INDUSTRIAL CONTROL NETWORK AUTHENTICATION</p>
                <div class="security-badge">
                    <span class="badge-dot"></span>
                    SECURE CHANNEL ESTABLISHED
                </div>
            </div>
            
            <!-- Login Form -->
            <form method="POST" class="ics-form" autocomplete="off">
                <div class="form-field">
                    <label for="username">OPERATOR ID</label>
                    <div class="field-wrapper">
                        <span class="field-icon">👤</span>
                        <input type="text" id="username" name="username" placeholder="operator@domain.local" required>
                    </div>
                </div>
                
                <div class="form-field">
                    <label for="password">X-KEY AUTHENTICATION</label>
                    <div class="field-wrapper">
                        <span class="field-icon">🔑</span>
                        <input type="password" id="password" name="password" placeholder="Enter X-KEY" required>
                    </div>
                </div>
                
                <?php if ($error): ?>
                <div class="ics-error">
                    <span>⛔</span> <?= htmlspecialchars($error) ?>
                </div>
                <?php endif; ?>
                
                <button type="submit" class="ics-submit">
                    <span class="submit-text">AUTHENTICATE</span>
                    <div class="submit-loader"></div>
                </button>
            </form>
            
            <!-- Footer -->
            <div class="panel-footer">
                <span>PROTOCOL: ICS-SEC-7</span>
                <span>ENCRYPTION: AES-256</span>
                <span>NODE: PRIMARY</span>
            </div>
        </div>
    </div>

    <script>
        // Floating particles
        const container = document.getElementById('particles');
        for (let i = 0; i < 30; i++) {
            const p = document.createElement('div');
            p.className = 'particle';
            p.style.left = Math.random() * 100 + '%';
            p.style.top = Math.random() * 100 + '%';
            p.style.animationDelay = Math.random() * 5 + 's';
            p.style.animationDuration = (3 + Math.random() * 4) + 's';
            container.appendChild(p);
        }
    </script>
    <?php if ($error): ?>
    <script>
        new Audio('../Invalid-Login.mp3').play().catch(e => {});
    </script>
    <?php endif; ?>
</body>
</html>
