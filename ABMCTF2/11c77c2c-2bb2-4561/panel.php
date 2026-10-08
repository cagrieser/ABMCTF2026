<?php
/**
 * Chemical Operations Management OS - Main Panel
 * Stage 5: ICS/SCADA Dashboard with sensor monitoring
 */
require_once dirname(__DIR__) . '/auth.php';
requireCCTVLogin();
require2FA();

$sensors = $_SESSION['sensor_values'];
$ranges = getSensorRanges();
$allSafe = allSensorsSafe();

// Count alarms
$alarmCount = 0;
foreach ($sensors as $key => $data) {
    if (isset($ranges[$key])) {
        if ($data['value'] < $ranges[$key]['min'] || $data['value'] > $ranges[$key]['max']) {
            $alarmCount++;
        }
    }
}

// The FLAG
$flag = 'ABMCTF{1ndu5tr14l_c0ntr0l_5y5t3m_53cur3d}';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chemical Operations Management OS</title>
    <link rel="stylesheet" href="../css/panel.css">
    <link href="https://fonts.googleapis.com/css2?family=Share+Tech+Mono&family=Rajdhani:wght@300;400;500;600;700&family=Orbitron:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body class="<?= $allSafe ? 'system-safe' : 'system-alarm' ?>">
    <div class="grid-bg"></div>
    
    <!-- Top Navigation Bar -->
    <header class="nav-bar">
        <div class="nav-left">
            <div class="system-logo">
                <svg viewBox="0 0 40 40" width="28" height="28">
                    <polygon points="20,2 38,12 38,28 20,38 2,28 2,12" fill="none" stroke="currentColor" stroke-width="1.5"/>
                    <circle cx="20" cy="20" r="6" fill="none" stroke="currentColor" stroke-width="1.5"/>
                    <circle cx="20" cy="20" r="2" fill="currentColor"/>
                </svg>
            </div>
            <div class="system-info">
                <span class="sys-name">CHEMICAL OPERATIONS MANAGEMENT OS</span>
                <span class="sys-version">v4.7.2 | NODE: TANK-FARM-01</span>
            </div>
        </div>
        
        <div class="nav-center">
            <div class="alarm-indicator <?= $allSafe ? 'safe' : 'danger' ?>">
                <span class="alarm-dot"></span>
                <span class="alarm-text"><?= $allSafe ? 'ALL SYSTEMS NOMINAL' : "⚠ {$alarmCount} ALARM(S) ACTIVE" ?></span>
            </div>
        </div>
        
        <div class="nav-right">
            <a href="./docs.php" class="nav-btn" id="docsBtn">📄 API DOCS</a>
            <a href="./reference.php" class="nav-btn" id="refBtn">📊 REFERENCE</a>
            <span class="nav-time" id="navTime"></span>
            <a href="../logout.php" class="nav-btn logout">⏻ LOGOUT</a>
        </div>
    </header>

    <!-- Flag Banner (shown only when all safe) -->
    <?php if ($allSafe): ?>
    <div class="flag-banner" id="flagBanner">
        <div class="flag-content">
            <span class="flag-icon">🏁</span>
            <span class="flag-label">CONGRATULATIONS — SYSTEM SECURED</span>
            <span class="flag-value"><?= $flag ?></span>
        </div>
    </div>
    <?php endif; ?>

    <!-- Main Dashboard -->
    <main class="dashboard">
        <!-- System Status Overview -->
        <section class="status-overview">
            <div class="status-card <?= $allSafe ? 'safe' : 'danger' ?>">
                <div class="status-icon"><?= $allSafe ? '✅' : '🚨' ?></div>
                <div class="status-info">
                    <span class="status-label">SYSTEM STATUS</span>
                    <span class="status-value"><?= $allSafe ? 'NOMINAL' : 'CRITICAL ALARM' ?></span>
                </div>
            </div>
            <div class="status-card">
                <div class="status-icon">🌡️</div>
                <div class="status-info">
                    <span class="status-label">ACTIVE SENSORS</span>
                    <span class="status-value"><?= count($sensors) ?></span>
                </div>
            </div>
            <div class="status-card <?= $alarmCount > 0 ? 'warning' : 'safe' ?>">
                <div class="status-icon">⚠️</div>
                <div class="status-info">
                    <span class="status-label">ALARMS</span>
                    <span class="status-value"><?= $alarmCount ?></span>
                </div>
            </div>
            <div class="status-card">
                <div class="status-icon">🔗</div>
                <div class="status-info">
                    <span class="status-label">API ENDPOINT</span>
                    <span class="status-value" style="font-size:10px">/api/sensors/*</span>
                </div>
            </div>
        </section>

        <!-- Sensor Groups -->
        <section class="sensor-groups">
            <?php
            $groups = [
                'TEMPERATURE' => ['icon' => '🌡️', 'prefix' => 'temp/', 'color' => '#ff6b35'],
                'PRESSURE' => ['icon' => '⏲️', 'prefix' => 'pressure/', 'color' => '#ff3333'],
                'LEVEL' => ['icon' => '📊', 'prefix' => 'level/', 'color' => '#00d4ff'],
                'GAS DETECTION' => ['icon' => '💨', 'prefix' => 'gas/', 'color' => '#ffcc00'],
                'FLOW' => ['icon' => '🌊', 'prefix' => 'flow/', 'color' => '#00ff88'],
                'CHEMICAL' => ['icon' => '⚗️', 'prefix' => 'chem/', 'color' => '#cc66ff'],
                'WEIGHT' => ['icon' => '⚖️', 'prefix' => 'weight/', 'color' => '#66ccff'],
                'FIRE' => ['icon' => '🔥', 'prefix' => 'fire/', 'color' => '#ff4444'],
                'MOTOR' => ['icon' => '⚙️', 'prefix' => 'motor/', 'color' => '#ff9900'],
                'AMBIENT' => ['icon' => '🌤️', 'prefix' => 'ambient/', 'color' => '#88ccaa'],
            ];
            
            foreach ($groups as $groupName => $groupInfo):
                $groupSensors = array_filter($sensors, function($key) use ($groupInfo) {
                    return strpos($key, $groupInfo['prefix']) === 0;
                }, ARRAY_FILTER_USE_KEY);
                
                if (empty($groupSensors)) continue;
                
                $hasAlarm = false;
                foreach ($groupSensors as $key => $data) {
                    if (isset($ranges[$key]) && ($data['value'] < $ranges[$key]['min'] || $data['value'] > $ranges[$key]['max'])) {
                        $hasAlarm = true;
                        break;
                    }
                }
            ?>
            <div class="sensor-group <?= $hasAlarm ? 'has-alarm' : '' ?>">
                <div class="group-header" style="border-left-color: <?= $groupInfo['color'] ?>">
                    <span class="group-icon"><?= $groupInfo['icon'] ?></span>
                    <span class="group-name"><?= $groupName ?></span>
                    <?php if ($hasAlarm): ?>
                    <span class="group-alarm">ALARM</span>
                    <?php endif; ?>
                </div>
                <div class="group-sensors">
                    <?php foreach ($groupSensors as $sensorKey => $sensorData):
                        $inRange = true;
                        if (isset($ranges[$sensorKey])) {
                            $inRange = $sensorData['value'] >= $ranges[$sensorKey]['min'] && $sensorData['value'] <= $ranges[$sensorKey]['max'];
                        }
                        $sensorName = str_replace($groupInfo['prefix'], '', $sensorKey);
                    ?>
                    <div class="sensor-card <?= $inRange ? 'safe' : 'alarm' ?>" data-sensor="<?= $sensorKey ?>">
                        <div class="sensor-header">
                            <span class="sensor-name"><?= strtoupper(str_replace('-', ' ', $sensorName)) ?></span>
                            <span class="sensor-status <?= $inRange ? '' : 'blink' ?>"><?= $inRange ? '●' : '⚠' ?></span>
                        </div>
                        <div class="sensor-value">
                            <span class="value-num" id="val-<?= str_replace('/', '-', $sensorKey) ?>"><?= $sensorData['value'] ?></span>
                            <span class="value-unit"><?= $sensorData['unit'] ?></span>
                        </div>
                        <?php if (isset($ranges[$sensorKey]) && !$inRange): ?>
                        <div class="sensor-alert">OUT OF RANGE</div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </section>
    </main>

    <script src="../js/panel.js"></script>
</body>
</html>
