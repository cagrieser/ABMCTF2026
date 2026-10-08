<?php
/**
 * API Documentation Page
 * Shows available endpoints and usage examples
 */
require_once dirname(__DIR__) . '/auth.php';
requireCCTVLogin();
require2FA();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COMS - API Documentation</title>
    <link rel="stylesheet" href="../css/panel.css">
    <link href="https://fonts.googleapis.com/css2?family=Share+Tech+Mono&family=Rajdhani:wght@300;400;500;600;700&family=Orbitron:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="grid-bg"></div>
    
    <header class="nav-bar">
        <div class="nav-left">
            <div class="system-logo">
                <svg viewBox="0 0 40 40" width="28" height="28">
                    <polygon points="20,2 38,12 38,28 20,38 2,28 2,12" fill="none" stroke="#00d4ff" stroke-width="1.5"/>
                    <circle cx="20" cy="20" r="6" fill="none" stroke="#00d4ff" stroke-width="1.5"/>
                    <circle cx="20" cy="20" r="2" fill="#00d4ff"/>
                </svg>
            </div>
            <div class="system-info">
                <span class="sys-name">COMS — API DOCUMENTATION</span>
                <span class="sys-version">REST API v1.0</span>
            </div>
        </div>
        <div class="nav-right">
            <a href="./panel.php" class="nav-btn">◄ DASHBOARD</a>
            <a href="./reference.php" class="nav-btn">📊 REFERENCE</a>
        </div>
    </header>

    <div class="docs-container">
        <div class="docs-header">
            <h1 class="docs-title">API DOCUMENTATION</h1>
            <p class="docs-sub">CHEMICAL OPERATIONS MANAGEMENT OS — SENSOR API</p>
        </div>

        <!-- Base URL -->
        <div class="api-section">
            <div class="api-section-header">
                <span class="api-section-title">BASE URL</span>
            </div>
            <div class="code-block">
                <pre><span class="comment">// All API requests use the following base URL:</span>
<span class="string">/11c77c2c-2bb2-4561/api.php?path=</span></pre>
            </div>
        </div>

        <!-- GET All Sensors -->
        <div class="api-section">
            <div class="api-section-header">
                <span class="api-method get">GET</span>
                <span class="api-section-title">Get All Sensors</span>
            </div>
            <div class="code-block">
                <pre><span class="comment">// Request:</span>
<span class="key">GET</span> <span class="string">/11c77c2c-2bb2-4561/api.php</span>

<span class="comment">// Response:</span>
{
  <span class="key">"status"</span>: <span class="string">"ok"</span>,
  <span class="key">"total_sensors"</span>: 44,
  <span class="key">"alarm_count"</span>: 9,
  <span class="key">"data"</span>: [
    {
      <span class="key">"sensor"</span>: <span class="string">"temp/tank-internal"</span>,
      <span class="key">"endpoint"</span>: <span class="string">"/api/sensors/temp/tank-internal"</span>,
      <span class="key">"value"</span>: 42.3,
      <span class="key">"unit"</span>: <span class="string">"°C"</span>,
      <span class="key">"status"</span>: <span class="string">"NORMAL"</span>
    },
    ...
  ]
}</pre>
            </div>
        </div>

        <!-- GET Single Sensor -->
        <div class="api-section">
            <div class="api-section-header">
                <span class="api-method get">GET</span>
                <span class="api-section-title">Get Single Sensor</span>
            </div>
            <div class="api-endpoint-list">
                <h3 style="color:#00d4ff;font-size:12px;letter-spacing:2px;margin-bottom:15px;font-family:'Rajdhani',sans-serif">TEMPERATURE SENSORS</h3>
                <?php
                $endpoints = [
                    ['GET /api/sensors/temp/tank-internal', 'Tank içi sıcaklık (PT100)'],
                    ['GET /api/sensors/temp/heat-exchanger', 'Isı eşanjörü sıcaklığı'],
                    ['GET /api/sensors/temp/pipe-line', 'Boru hattı sıcaklığı'],
                    ['GET /api/sensors/temp/tank-wall', 'Tank duvar yüzeyi sıcaklığı'],
                    ['GET /api/sensors/temp/load-line', 'Yükleme/boşaltma hattı sıcaklığı'],
                ];
                foreach ($endpoints as $ep): ?>
                <div class="api-endpoint">
                    <span class="api-method get">GET</span>
                    <span class="api-path"><?= $ep[0] ?></span>
                    <span class="api-desc"><?= $ep[1] ?></span>
                </div>
                <?php endforeach; ?>

                <h3 style="color:#00d4ff;font-size:12px;letter-spacing:2px;margin:20px 0 15px;font-family:'Rajdhani',sans-serif">PRESSURE SENSORS</h3>
                <?php
                $endpoints = [
                    ['GET /api/sensors/pressure/tank-internal', 'Tank iç basıncı'],
                    ['GET /api/sensors/pressure/n2-blanketing', 'N2 yastık basıncı'],
                    ['GET /api/sensors/pressure/pipeline', 'Boru hattı basıncı'],
                    ['GET /api/sensors/pressure/pump-outlet', 'Pompa çıkış basıncı'],
                ];
                foreach ($endpoints as $ep): ?>
                <div class="api-endpoint">
                    <span class="api-method get">GET</span>
                    <span class="api-path"><?= $ep[0] ?></span>
                    <span class="api-desc"><?= $ep[1] ?></span>
                </div>
                <?php endforeach; ?>

                <h3 style="color:#00d4ff;font-size:12px;letter-spacing:2px;margin:20px 0 15px;font-family:'Rajdhani',sans-serif">LEVEL SENSORS</h3>
                <?php
                $endpoints = [
                    ['GET /api/sensors/level/radar', 'Radar seviye ölçümü'],
                    ['GET /api/sensors/level/ultrasonic', 'Ultrasonik seviye sensörü'],
                    ['GET /api/sensors/level/float', 'Mekanik float sensör'],
                    ['GET /api/sensors/level/overflow', 'Taşma koruma sensörü'],
                ];
                foreach ($endpoints as $ep): ?>
                <div class="api-endpoint">
                    <span class="api-method get">GET</span>
                    <span class="api-path"><?= $ep[0] ?></span>
                    <span class="api-desc"><?= $ep[1] ?></span>
                </div>
                <?php endforeach; ?>

                <h3 style="color:#00d4ff;font-size:12px;letter-spacing:2px;margin:20px 0 15px;font-family:'Rajdhani',sans-serif">GAS DETECTION SENSORS</h3>
                <?php
                $endpoints = [
                    ['GET /api/sensors/gas/voc', 'VOC seviyesi'],
                    ['GET /api/sensors/gas/h2s', 'H₂S sensörü'],
                    ['GET /api/sensors/gas/o2', 'O₂ depletion sensörü'],
                    ['GET /api/sensors/gas/lel', 'Yanıcı gaz %LEL ölçümü'],
                ];
                foreach ($endpoints as $ep): ?>
                <div class="api-endpoint">
                    <span class="api-method get">GET</span>
                    <span class="api-path"><?= $ep[0] ?></span>
                    <span class="api-desc"><?= $ep[1] ?></span>
                </div>
                <?php endforeach; ?>

                <h3 style="color:#00d4ff;font-size:12px;letter-spacing:2px;margin:20px 0 15px;font-family:'Rajdhani',sans-serif">FLOW SENSORS</h3>
                <?php
                $endpoints = [
                    ['GET /api/sensors/flow/transfer', 'Transfer akış sensörü'],
                    ['GET /api/sensors/flow/recirculation', 'Recirculation flow'],
                    ['GET /api/sensors/flow/cooling', 'Cooling line flow rate'],
                    ['GET /api/sensors/flow/loading', 'Yükleme/boşaltma pompa akışı'],
                ];
                foreach ($endpoints as $ep): ?>
                <div class="api-endpoint">
                    <span class="api-method get">GET</span>
                    <span class="api-path"><?= $ep[0] ?></span>
                    <span class="api-desc"><?= $ep[1] ?></span>
                </div>
                <?php endforeach; ?>

                <h3 style="color:#00d4ff;font-size:12px;letter-spacing:2px;margin:20px 0 15px;font-family:'Rajdhani',sans-serif">CHEMICAL SENSORS</h3>
                <?php
                $endpoints = [
                    ['GET /api/sensors/chem/ph', 'pH değeri'],
                    ['GET /api/sensors/chem/conductivity', 'İletkenlik sensörü'],
                    ['GET /api/sensors/chem/salinity', 'Tuzluluk ölçümü'],
                    ['GET /api/sensors/chem/byproduct-co', 'Kimyasal reaksiyon CO ölçümü'],
                ];
                foreach ($endpoints as $ep): ?>
                <div class="api-endpoint">
                    <span class="api-method get">GET</span>
                    <span class="api-path"><?= $ep[0] ?></span>
                    <span class="api-desc"><?= $ep[1] ?></span>
                </div>
                <?php endforeach; ?>

                <h3 style="color:#00d4ff;font-size:12px;letter-spacing:2px;margin:20px 0 15px;font-family:'Rajdhani',sans-serif">WEIGHT SENSORS</h3>
                <?php
                $endpoints = [
                    ['GET /api/sensors/weight/loadcell', 'Tank loadcell ağırlık ölçümü'],
                    ['GET /api/sensors/weight/ballast', 'Balast tank seviye sensörü'],
                    ['GET /api/sensors/weight/heel', 'Heel ölçümü (Yalpa / Yan yatma)'],
                    ['GET /api/sensors/weight/trim', 'Trim ölçümü (Öne/Arkaya eğim)'],
                ];
                foreach ($endpoints as $ep): ?>
                <div class="api-endpoint">
                    <span class="api-method get">GET</span>
                    <span class="api-path"><?= $ep[0] ?></span>
                    <span class="api-desc"><?= $ep[1] ?></span>
                </div>
                <?php endforeach; ?>

                <h3 style="color:#00d4ff;font-size:12px;letter-spacing:2px;margin:20px 0 15px;font-family:'Rajdhani',sans-serif">FIRE DETECTION</h3>
                <?php
                $endpoints = [
                    ['GET /api/sensors/fire/flame', 'IR/UV flame detector'],
                    ['GET /api/sensors/fire/heat', 'Heat detector panel'],
                    ['GET /api/sensors/fire/spark', 'Kıvılcım detektörü'],
                    ['GET /api/sensors/fire/foam-line-pressure', 'Köpük sistemi hat basıncı'],
                ];
                foreach ($endpoints as $ep): ?>
                <div class="api-endpoint">
                    <span class="api-method get">GET</span>
                    <span class="api-path"><?= $ep[0] ?></span>
                    <span class="api-desc"><?= $ep[1] ?></span>
                </div>
                <?php endforeach; ?>

                <h3 style="color:#00d4ff;font-size:12px;letter-spacing:2px;margin:20px 0 15px;font-family:'Rajdhani',sans-serif">MOTOR SENSORS</h3>
                <?php
                $endpoints = [
                    ['GET /api/sensors/motor/rpm', 'Pompa RPM'],
                    ['GET /api/sensors/motor/temp', 'Motor sıcaklığı'],
                    ['GET /api/sensors/motor/current', 'Motor akım çekişi'],
                    ['GET /api/sensors/motor/vibration', 'Titreşim sensörü'],
                ];
                foreach ($endpoints as $ep): ?>
                <div class="api-endpoint">
                    <span class="api-method get">GET</span>
                    <span class="api-path"><?= $ep[0] ?></span>
                    <span class="api-desc"><?= $ep[1] ?></span>
                </div>
                <?php endforeach; ?>

                <h3 style="color:#00d4ff;font-size:12px;letter-spacing:2px;margin:20px 0 15px;font-family:'Rajdhani',sans-serif">AMBIENT SENSORS</h3>
                <?php
                $endpoints = [
                    ['GET /api/sensors/ambient/air-temp', 'Dış hava sıcaklığı'],
                    ['GET /api/sensors/ambient/humidity', 'Dış ortam nem'],
                    ['GET /api/sensors/ambient/deck-temp', 'Tanker güverte sıcaklığı'],
                    ['GET /api/sensors/ambient/sea-temp', 'Deniz suyu sıcaklığı'],
                ];
                foreach ($endpoints as $ep): ?>
                <div class="api-endpoint">
                    <span class="api-method get">GET</span>
                    <span class="api-path"><?= $ep[0] ?></span>
                    <span class="api-desc"><?= $ep[1] ?></span>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="code-block">
                <pre><span class="comment">// Example: Get single sensor value</span>
<span class="key">GET</span> <span class="string">/11c77c2c-2bb2-4561/api.php?path=temp/tank-internal</span>

<span class="comment">// Response:</span>
{
  <span class="key">"status"</span>: <span class="string">"ok"</span>,
  <span class="key">"sensor"</span>: <span class="string">"temp/tank-internal"</span>,
  <span class="key">"endpoint"</span>: <span class="string">"/api/sensors/temp/tank-internal"</span>,
  <span class="key">"value"</span>: 42.3,
  <span class="key">"unit"</span>: <span class="string">"°C"</span>,
  <span class="key">"sensor_status"</span>: <span class="string">"NORMAL"</span>,
  <span class="key">"timestamp"</span>: <span class="string">"2026-05-18T21:00:00Z"</span>
}</pre>
            </div>
        </div>

        <!-- PUT Update Sensor -->
        <div class="api-section">
            <div class="api-section-header">
                <span class="api-method put">PUT</span>
                <span class="api-section-title">Update Sensor Value</span>
            </div>
            <div class="api-endpoint-list">
                <p style="color:rgba(200,214,229,0.5);font-size:12px;margin-bottom:10px">
                    Use PUT method to update any sensor value. Send JSON body with the new value.
                </p>
            </div>
            <div class="code-block">
                <pre><span class="comment">// Example: Update tank-internal temperature</span>
<span class="key">PUT</span> <span class="string">/11c77c2c-2bb2-4561/api.php?path=temp/tank-internal</span>
<span class="key">Content-Type:</span> <span class="string">application/json</span>

<span class="comment">// Request Body:</span>
{
  <span class="key">"value"</span>: 35.0
}

<span class="comment">// Response:</span>
{
  <span class="key">"status"</span>: <span class="string">"ok"</span>,
  <span class="key">"message"</span>: <span class="string">"Sensor 'temp/tank-internal' updated successfully"</span>,
  <span class="key">"sensor"</span>: <span class="string">"temp/tank-internal"</span>,
  <span class="key">"new_value"</span>: 35.0,
  <span class="key">"unit"</span>: <span class="string">"°C"</span>,
  <span class="key">"sensor_status"</span>: <span class="string">"NORMAL"</span>
}</pre>
            </div>
            <div class="code-block">
                <pre><span class="comment">// cURL example:</span>
curl -X PUT \
  <span class="string">"http://localhost/11c77c2c-2bb2-4561/api.php?path=pressure/pipeline"</span> \
  -H <span class="string">"Content-Type: application/json"</span> \
  -H <span class="string">"Cookie: PHPSESSID=your_session_id"</span> \
  -d <span class="string">'{"value": 4.5}'</span>

<span class="comment">// JavaScript fetch example:</span>
fetch(<span class="string">'/11c77c2c-2bb2-4561/api.php?path=pressure/pipeline'</span>, {
  method: <span class="string">'PUT'</span>,
  headers: { <span class="key">'Content-Type'</span>: <span class="string">'application/json'</span> },
  body: JSON.stringify({ <span class="key">value</span>: 4.5 })
});</pre>
            </div>
        </div>

        <!-- System Flag Info -->
        <div class="api-section">
            <div class="api-section-header">
                <span class="api-method post">INFO</span>
                <span class="api-section-title">System Status</span>
            </div>
            <div class="api-endpoint-list">
                <p style="color:rgba(200,214,229,0.5);font-size:12px;line-height:1.8">
                    When all sensor values are within their safe reference ranges, the system will transition from 
                    <span style="color:#ff3333">CRITICAL ALARM</span> to 
                    <span style="color:#00ff88">ALL SYSTEMS NOMINAL</span>.
                    The PUT response will include a special system status field when all values are corrected.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
