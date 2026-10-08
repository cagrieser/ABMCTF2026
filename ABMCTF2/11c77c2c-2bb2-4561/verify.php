<?php
/**
 * 2FA Verification Page
 * Stage 4: Upload JPEG file for hash verification
 */
require_once dirname(__DIR__) . '/auth.php';
requireCCTVLogin();
requireICSLogin();

// If already verified, go to panel
if (isset($_SESSION['ics_2fa_verified']) && $_SESSION['ics_2fa_verified'] === true) {
    header('Location: ./panel.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ICS Control Systems - 2FA Verification</title>
    <link rel="stylesheet" href="../css/ics.css">
    <link href="https://fonts.googleapis.com/css2?family=Share+Tech+Mono&family=Rajdhani:wght@300;400;500;600;700&family=Orbitron:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="grid-bg"></div>
    <div class="particles" id="particles"></div>
    
    <div class="verify-container">
        <div class="verify-panel">
            <div class="panel-glow"></div>
            
            <div class="verify-icon">🔐</div>
            <h1 class="verify-title">TWO-FACTOR AUTHENTICATION</h1>
            <p class="verify-sub">UPLOAD AUTHORIZATION KEY FILE TO PROCEED<br><br><font color="red">bc7e2596737fccf9f22697a55112b321</font></p>
			
            
            <div class="upload-zone" id="uploadZone">
                <p class="upload-text">DRAG & DROP OR CLICK TO SELECT</p>
                <p class="upload-hint">Accepted format: .jpg / .jpeg</p>
                <input type="file" id="fileInput" accept=".jpg,.jpeg,image/jpeg">
            </div>
            
            <div class="selected-file" id="selectedFile"></div>
            
            <button class="verify-btn" id="verifyBtn" onclick="verifyFile()">
                VERIFY AUTHORIZATION
            </button>
            
            <div class="verify-loading" id="verifyLoading">
                <div class="spinner"></div>
                <p class="loading-text">VERIFYING HASH SIGNATURE...</p>
            </div>
            
            <div class="verify-result" id="verifyResult"></div>
        </div>
    </div>

    <script src="../js/verify.js"></script>
    <?php if (isset($_SESSION['play_success_sound'])): unset($_SESSION['play_success_sound']); ?>
    <script>
        new Audio('../LoggedIn.mp3').play().catch(e => {});
    </script>
    <?php endif; ?>
    <script>
        // Particles
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
</body>
</html>
