<?php
/**
 * CCTV Dashboard - 4 Camera Grid View
 * Stage 2: Camera monitoring with controls
 */
require_once __DIR__ . '/auth.php';
requireCCTVLogin();

$cameras = [
    ['id' => 'live-1', 'file' => 'video-536326.mp4', 'label' => 'CAM-01'],
    ['id' => 'live-2', 'file' => 'video-784522.mp4', 'label' => 'CAM-02'],
    ['id' => 'live-3', 'file' => 'video-127896.mp4', 'label' => 'CAM-03'],
    ['id' => 'live-4', 'file' => 'video-125789.mp4', 'label' => 'CAM-04'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CCTV System - Live Monitoring</title>
    <link rel="stylesheet" href="./css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Share+Tech+Mono&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="scanline-overlay"></div>
    
    <!-- Top bar -->
    <header class="top-bar">
        <div class="top-left">
            <div class="rec-indicator">
                <span class="rec-dot"></span>
                <span>REC</span>
            </div>
            <span class="sep">|</span>
            <span class="top-label">SURVEILLANCE MONITORING SYSTEM</span>
        </div>
        <div class="top-center">
            <span class="device-mac">MAC: 38:AF:29:11:22:33</span>
        </div>
        <div class="top-right">
            <span class="system-time" id="systemTime"></span>
            <span class="sep">|</span>
            <a href="./logout.php" class="logout-btn">LOGOUT</a>
        </div>
    </header>

    <!-- Camera Grid -->
    <main class="camera-grid" id="cameraGrid">
        <?php foreach ($cameras as $cam): ?>
        <div class="camera-cell" data-cam="<?= $cam['id'] ?>" id="cell-<?= $cam['id'] ?>">
            <div class="cam-header">
                <span class="cam-label"><?= $cam['label'] ?></span>
                <span class="cam-id"><?= $cam['id'] ?></span>
                <span class="cam-status">● LIVE</span>
            </div>
            
            <div class="cam-video-container">
                <video 
                    class="cam-video" 
                    id="video-<?= $cam['id'] ?>" 
                    src="./<?= $cam['file'] ?>" 
                    muted 
                    autoplay 
                    loop 
                    playsinline
                ></video>
                
                <!-- Camera overlay -->
                <div class="cam-overlay">
                    <div class="crosshair"></div>
                </div>
                
                <!-- Filter overlay -->
                <div class="filter-overlay" id="filter-<?= $cam['id'] ?>"></div>
            </div>
            
            <!-- Camera Controls -->
            <div class="cam-controls">
                <div class="control-group">
                    <button class="ctrl-btn" onclick="zoomIn('<?= $cam['id'] ?>')" title="Zoom In">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/>
                        </svg>
                    </button>
                    <button class="ctrl-btn" onclick="zoomOut('<?= $cam['id'] ?>')" title="Zoom Out">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="8" y1="11" x2="14" y2="11"/>
                        </svg>
                    </button>
                </div>
                
                <div class="control-group ptz-group">
                    <button class="ctrl-btn ptz" onclick="panCamera('<?= $cam['id'] ?>','up')" title="Pan Up">▲</button>
                    <div class="ptz-row">
                        <button class="ctrl-btn ptz" onclick="panCamera('<?= $cam['id'] ?>','left')" title="Pan Left">◄</button>
                        <button class="ctrl-btn ptz center" onclick="resetCamera('<?= $cam['id'] ?>')" title="Reset">⊙</button>
                        <button class="ctrl-btn ptz" onclick="panCamera('<?= $cam['id'] ?>','right')" title="Pan Right">►</button>
                    </div>
                    <button class="ctrl-btn ptz" onclick="panCamera('<?= $cam['id'] ?>','down')" title="Pan Down">▼</button>
                </div>
                
                <div class="control-group filter-group">
                    <button class="ctrl-btn filter-btn" onclick="setFilter('<?= $cam['id'] ?>','none')" title="Normal">
                        <span class="filter-icon">☀</span>
                    </button>
                    <button class="ctrl-btn filter-btn" onclick="setFilter('<?= $cam['id'] ?>','invert')" title="Inverse">
                        <span class="filter-icon">◑</span>
                    </button>
                    <button class="ctrl-btn filter-btn" onclick="setFilter('<?= $cam['id'] ?>','green')" title="Night Vision">
                        <span class="filter-icon" style="color:#00ff00">◉</span>
                    </button>
                    <button class="ctrl-btn filter-btn" onclick="setFilter('<?= $cam['id'] ?>','gray')" title="Grayscale">
                        <span class="filter-icon" style="color:#888">◉</span>
                    </button>
                </div>
                
                <button class="ctrl-btn fullscreen-btn" onclick="toggleFullscreen('<?= $cam['id'] ?>')" title="Fullscreen">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/>
                        <line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/>
                    </svg>
                </button>
            </div>
            
            <div class="cam-footer">
                <span class="cam-date" id="date-<?= $cam['id'] ?>"></span>
                <span class="cam-res">1920x1080</span>
            </div>
        </div>
        <?php endforeach; ?>
    </main>

    <!-- Fullscreen Modal -->
    <div class="fullscreen-modal" id="fullscreenModal">
        <div class="fs-header">
            <span class="fs-label" id="fsLabel"></span>
            <div class="fs-controls-top">
                <div class="control-group filter-group">
                    <button class="ctrl-btn filter-btn" onclick="setFilter(currentFullscreenCam,'none')" title="Normal">☀</button>
                    <button class="ctrl-btn filter-btn" onclick="setFilter(currentFullscreenCam,'invert')" title="Inverse">◑</button>
                    <button class="ctrl-btn filter-btn" onclick="setFilter(currentFullscreenCam,'green')" title="Night Vision" style="color:#0f0">◉</button>
                    <button class="ctrl-btn filter-btn" onclick="setFilter(currentFullscreenCam,'gray')" title="Grayscale" style="color:#888">◉</button>
                </div>
                <button class="fs-close" onclick="closeFullscreen()">✕</button>
            </div>
        </div>
        <div class="fs-video-container">
            <video class="fs-video" id="fsVideo" muted autoplay loop playsinline></video>
            <div class="cam-overlay"><div class="crosshair"></div></div>
            <div class="filter-overlay" id="filter-fullscreen"></div>
        </div>
        <div class="fs-bottom-controls">
            <div class="control-group">
                <button class="ctrl-btn" onclick="zoomIn(currentFullscreenCam)" title="Zoom In">🔍+</button>
                <button class="ctrl-btn" onclick="zoomOut(currentFullscreenCam)" title="Zoom Out">🔍−</button>
            </div>
            <div class="control-group ptz-group ptz-horizontal">
                <button class="ctrl-btn ptz" onclick="panFullscreen('left')">◄</button>
                <button class="ctrl-btn ptz" onclick="panFullscreen('up')">▲</button>
                <button class="ctrl-btn ptz center" onclick="resetFullscreen()">⊙</button>
                <button class="ctrl-btn ptz" onclick="panFullscreen('down')">▼</button>
                <button class="ctrl-btn ptz" onclick="panFullscreen('right')">►</button>
            </div>
        </div>
    </div>

    <script src="./js/dashboard.js"></script>
    <?php if (isset($_SESSION['play_success_sound'])): unset($_SESSION['play_success_sound']); ?>
    <script>
        new Audio('./LoggedIn.mp3').play().catch(e => {});
    </script>
    <?php endif; ?>
</body>
</html>
