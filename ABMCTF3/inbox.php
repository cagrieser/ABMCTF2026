<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}
if (!isset($_SESSION['login_time'])) {
    $_SESSION['login_time'] = time();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inbox - FlightCore MailSuite</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="public/assets/css/style.css">
</head>
<body class="app-body">
    <div class="app-container">
        <aside class="sidebar">
            <div class="brand">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/></svg>
                <h2>FlightCore</h2>
            </div>
            <nav class="nav-menu" id="nav-menu">
                <a href="#" class="active" data-folder="inbox">
                    <span class="nav-icon">📥</span> 
                    <span class="nav-text">Inbox</span> 
                    <span class="nav-count" id="count-inbox"></span>
                </a>
                <a href="#" data-folder="sent">
                    <span class="nav-icon">🛫</span> 
                    <span class="nav-text">Sent</span> 
                    <span class="nav-count" id="count-sent"></span>
                </a>
                <a href="#" data-folder="spam">
                    <span class="nav-icon">⚠️</span> 
                    <span class="nav-text">Spam</span> 
                    <span class="nav-count" id="count-spam"></span>
                </a>
                <a href="#" data-folder="trash">
                    <span class="nav-icon">🗑️</span> 
                    <span class="nav-text">Trash</span> 
                    <span class="nav-count" id="count-trash"></span>
                </a>
            </nav>
            <div class="user-profile">
                <div class="avatar">BD</div>
                <div class="user-info">
                    <span class="name">Bilge Demir</span>
                    <span class="role">Staff Engineer</span>
                </div>
                <a href="logout.php" class="logout-btn" title="Secure Logout">
                    <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                </a>
            </div>
        </aside>
        
        <main class="main-content">
            <div class="mail-list-panel">
                <header class="panel-header">
                    <h3>Secure Inbox</h3>
                    <div class="search-box">
                        <input type="text" id="search-input" placeholder="Search mail...">
                    </div>
                </header>
                <div id="mail-list" class="mail-list">
                    <div class="empty-state">Decrypting Inbox...</div>
                </div>
            </div>
            
            <div class="mail-view-panel">
                <header class="panel-header">
                    <h3>Message Viewer</h3>
                </header>
                <div id="mail-content" class="mail-content">
                    <div class="empty-state">
                        <p>Select an email to view its secure contents.</p>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script src="public/assets/js/mail.js"></script>
</body>
</html>
