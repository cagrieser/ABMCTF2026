<?php
/**
 * Reference Values Documentation
 * Shows safe reference ranges for all sensors
 */
require_once dirname(__DIR__) . '/auth.php';
requireCCTVLogin();
require2FA();

$refData = [
    'TEMPERATURE' => [
        ['temp/tank-internal', 'Tank içi sıcaklık (PT100)', '15 - 55', '°C'],
        ['temp/heat-exchanger', 'Isı eşanjörü sıcaklığı', '40 - 85', '°C'],
        ['temp/pipe-line', 'Boru hattı sıcaklığı', '15 - 50', '°C'],
        ['temp/tank-wall', 'Tank duvar yüzeyi sıcaklığı', '10 - 45', '°C'],
        ['temp/load-line', 'Yükleme/boşaltma hattı sıcaklığı', '15 - 50', '°C'],
    ],
    'PRESSURE' => [
        ['pressure/tank-internal', 'Tank iç basıncı', '0.01 - 0.10 (Hafif Pozitif)', 'bar'],
        ['pressure/n2-blanketing', 'N2 yastık basıncı', '0.02 - 0.05', 'bar'],
        ['pressure/pipeline', 'Boru hattı basıncı', '2.0 - 6.0', 'bar'],
        ['pressure/pump-outlet', 'Pompa çıkış basıncı', '4.0 - 10.0', 'bar'],
    ],
    'LEVEL' => [
        ['level/radar', 'Radar seviye ölçümü', '5 - 95', '%'],
        ['level/ultrasonic', 'Ultrasonik seviye sensörü', '5 - 95', '%'],
        ['level/float', 'Mekanik float sensör', '10 - 90', '%'],
        ['level/overflow', 'Taşma koruma sensörü', '0 (98 ve üzeri → Alarm: 1)', 'Binary / %'],
    ],
    'GAS DETECTION' => [
        ['gas/voc', 'VOC seviyesi', '0 - 50 (Alarm > 100)', 'ppm'],
        ['gas/h2s', 'H₂S sensörü', '0 - 5 (Alarm > 10)', 'ppm'],
        ['gas/o2', 'O₂ depletion sensörü', '19.5 - 21.0', '%'],
        ['gas/lel', 'Yanıcı gaz %LEL ölçümü', '0 - 10 (Alarm > 20)', '% LEL'],
    ],
    'FLOW' => [
        ['flow/transfer', 'Transfer akış sensörü', '0 - 250', 'm³/h'],
        ['flow/recirculation', 'Recirculation flow', '0 - 100', 'm³/h'],
        ['flow/cooling', 'Cooling line flow rate', '10 - 50', 'm³/h'],
        ['flow/loading', 'Yükleme/boşaltma pompa akışı', '0 - 500', 'm³/h'],
    ],
    'CHEMICAL' => [
        ['chem/ph', 'pH değeri', '6.5 - 8.5 (Nötre yakın)', 'pH'],
        ['chem/conductivity', 'İletkenlik sensörü', '100 - 1500', 'µS/cm'],
        ['chem/salinity', 'Tuzluluk ölçümü', '0 - 35', 'ppt'],
        ['chem/byproduct-co', 'Kimyasal reaksiyon CO ölçümü', '0 - 25 (Alarm > 50)', 'ppm'],
    ],
    'WEIGHT' => [
        ['weight/loadcell', 'Tank loadcell ağırlık ölçümü', '0 - 5000', 'ton'],
        ['weight/ballast', 'Balast tank seviye sensörü', '0 - 100', '%'],
        ['weight/heel', 'Heel ölçümü (Yalpa / Yan yatma)', '-3.0 ile +3.0 arası', 'derece (°)'],
        ['weight/trim', 'Trim ölçümü (Öne/Arkaya eğim)', '0.0 - 2.0', 'metre (m)'],
    ],
    'FIRE DETECTION' => [
        ['fire/flame', 'IR/UV flame detector', '0 (Alev Var → 1)', 'Binary'],
        ['fire/heat', 'Heat detector panel', '0 (Yüksek Isı → 1)', 'Binary'],
        ['fire/spark', 'Kıvılcım detektörü', '0 (Kıvılcım → 1)', 'Binary'],
        ['fire/foam-line-pressure', 'Köpük sistemi hat basıncı', '6.0 - 10.0 (Aktifken)', 'bar'],
    ],
    'MOTOR' => [
        ['motor/rpm', 'Pompa RPM', '0 - 1450 / 2900', 'rpm'],
        ['motor/temp', 'Motor sıcaklığı', '40 - 85 (Alarm > 95)', '°C'],
        ['motor/current', 'Motor akım çekişi', '0 - 80', 'Amper (A)'],
        ['motor/vibration', 'Titreşim sensörü', '0.0 - 4.5', 'mm/s'],
    ],
    'AMBIENT' => [
        ['ambient/air-temp', 'Dış hava sıcaklığı', '-20 ile +45 arası', '°C'],
        ['ambient/humidity', 'Dış ortam nem', '20 - 90', '%'],
        ['ambient/deck-temp', 'Tanker güverte sıcaklığı', '-10 ile +60 arası', '°C'],
        ['ambient/sea-temp', 'Deniz suyu sıcaklığı', '0 ile +32 arası', '°C'],
    ],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COMS - Reference Values</title>
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
                <span class="sys-name">COMS — REFERENCE VALUES</span>
                <span class="sys-version">Safe Operating Parameters</span>
            </div>
        </div>
        <div class="nav-right">
            <a href="./panel.php" class="nav-btn">◄ DASHBOARD</a>
            <a href="./docs.php" class="nav-btn">📄 API DOCS</a>
        </div>
    </header>

    <div class="docs-container">
        <div class="docs-header">
            <h1 class="docs-title">SENSOR REFERENCE VALUES</h1>
            <p class="docs-sub">SAFE OPERATING PARAMETERS FOR ALL SENSOR ENDPOINTS</p>
        </div>

        <?php foreach ($refData as $groupName => $sensors): ?>
        <div class="api-section">
            <div class="api-section-header">
                <span class="api-section-title"><?= $groupName ?></span>
            </div>
            <div style="padding:15px;overflow-x:auto">
                <table class="ref-table">
                    <thead>
                        <tr>
                            <th>ENDPOINT</th>
                            <th>DESCRIPTION</th>
                            <th>SAFE RANGE</th>
                            <th>UNIT</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sensors as $s): ?>
                        <tr>
                            <td class="endpoint-col">/api/sensors/<?= $s[0] ?></td>
                            <td><?= $s[1] ?></td>
                            <td class="range-col"><?= $s[2] ?></td>
                            <td class="unit-col"><?= $s[3] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
