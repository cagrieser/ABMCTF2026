<?php
/**
 * REST API Handler for Sensor Data
 * Supports GET (read sensor) and PUT (update sensor value)
 */
require_once dirname(__DIR__) . '/auth.php';

header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');

// Must be fully authenticated
if (!isset($_SESSION['cctv_logged_in']) || $_SESSION['cctv_logged_in'] !== true ||
    !isset($_SESSION['ics_logged_in']) || $_SESSION['ics_logged_in'] !== true ||
    !isset($_SESSION['ics_2fa_verified']) || $_SESSION['ics_2fa_verified'] !== true) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized', 'message' => 'Full authentication required']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];

// Parse the sensor path from URI
// Expected: /11c77c2c-2bb2-4561/api.php/sensors/{category}/{sensor-name}
// Or via query: /11c77c2c-2bb2-4561/api.php?path=sensors/temp/tank-internal
$path = '';

// Try PATH_INFO first
if (isset($_SERVER['PATH_INFO'])) {
    $path = ltrim($_SERVER['PATH_INFO'], '/');
}

// Fallback to query parameter
if (empty($path) && isset($_GET['path'])) {
    $path = $_GET['path'];
}

// Remove 'sensors/' prefix if present
$path = preg_replace('/^sensors\//', '', $path);

// Sanitize path
$path = preg_replace('/[^a-zA-Z0-9\-\/]/', '', $path);

$sensors = &$_SESSION['sensor_values'];
$ranges = getSensorRanges();

switch ($method) {
    case 'GET':
        if (empty($path)) {
            // Return all sensors
            $result = [];
            foreach ($sensors as $key => $data) {
                $inRange = true;
                if (isset($ranges[$key])) {
                    $inRange = $data['value'] >= $ranges[$key]['min'] && $data['value'] <= $ranges[$key]['max'];
                }
                $result[] = [
                    'sensor' => $key,
                    'endpoint' => '/api/sensors/' . $key,
                    'value' => $data['value'],
                    'unit' => $data['unit'],
                    'status' => $inRange ? 'NORMAL' : 'ALARM',
                    'timestamp' => date('Y-m-d\TH:i:s\Z')
                ];
            }
            echo json_encode([
                'status' => 'ok',
                'total_sensors' => count($result),
                'alarm_count' => count(array_filter($result, fn($s) => $s['status'] === 'ALARM')),
                'data' => $result
            ], JSON_PRETTY_PRINT);
        } else {
            // Return specific sensor
            if (isset($sensors[$path])) {
                $data = $sensors[$path];
                $inRange = true;
                $refRange = null;
                if (isset($ranges[$path])) {
                    $inRange = $data['value'] >= $ranges[$path]['min'] && $data['value'] <= $ranges[$path]['max'];
                    $refRange = ['min' => $ranges[$path]['min'], 'max' => $ranges[$path]['max']];
                }
                echo json_encode([
                    'status' => 'ok',
                    'sensor' => $path,
                    'endpoint' => '/api/sensors/' . $path,
                    'value' => $data['value'],
                    'unit' => $data['unit'],
                    'sensor_status' => $inRange ? 'NORMAL' : 'ALARM',
                    'timestamp' => date('Y-m-d\TH:i:s\Z')
                ], JSON_PRETTY_PRINT);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Not Found', 'message' => "Sensor '{$path}' not found"]);
            }
        }
        break;

    case 'PUT':
        if (empty($path)) {
            http_response_code(400);
            echo json_encode(['error' => 'Bad Request', 'message' => 'Sensor path required']);
            break;
        }
        
        if (!isset($sensors[$path])) {
            http_response_code(404);
            echo json_encode(['error' => 'Not Found', 'message' => "Sensor '{$path}' not found"]);
            break;
        }
        
        // Read PUT body
        $input = file_get_contents('php://input');
        $body = json_decode($input, true);
        
        if ($body === null || !isset($body['value'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Bad Request', 'message' => 'JSON body with "value" field required']);
            break;
        }
        
        $newValue = floatval($body['value']);
        
        // Update sensor value in session
        $_SESSION['sensor_values'][$path]['value'] = $newValue;
        $sensors[$path]['value'] = $newValue;
        
        // Check if now in range
        $inRange = true;
        if (isset($ranges[$path])) {
            $inRange = $newValue >= $ranges[$path]['min'] && $newValue <= $ranges[$path]['max'];
        }
        
        // Check if all sensors are now safe
        $allSafe = allSensorsSafe();
        
        $response = [
            'status' => 'ok',
            'message' => "Sensor '{$path}' updated successfully",
            'sensor' => $path,
            'new_value' => $newValue,
            'unit' => $sensors[$path]['unit'],
            'sensor_status' => $inRange ? 'NORMAL' : 'ALARM',
            'timestamp' => date('Y-m-d\TH:i:s\Z')
        ];
        
        if ($allSafe) {
            $response['system_status'] = 'ALL_CLEAR';
            $response['flag'] = 'ABM{1ndu5tr14l_c0ntr0l_5y5t3m_53cur3d_2024}';
            $response['message'] = 'ALL SENSORS NOMINAL — SYSTEM SECURED';
        }
        
        echo json_encode($response, JSON_PRETTY_PRINT);
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method Not Allowed', 'message' => 'Only GET and PUT methods are supported']);
        break;
}
?>
