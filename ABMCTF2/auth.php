<?php
/**
 * Auth Guard - Session Management for CTF Challenge
 * Handles per-user session isolation despite shared credentials
 */

session_start();

// Regenerate session ID periodically to prevent fixation
if (!isset($_SESSION['created'])) {
    $_SESSION['created'] = time();
} elseif (time() - $_SESSION['created'] > 1800) {
    session_regenerate_id(true);
    $_SESSION['created'] = time();
}

/**
 * Initialize default sensor values for a new session
 * Some values are intentionally out of safe range
 */
function initSensorValues() {
    return [
        // Temperature sensors
        'temp/tank-internal'    => ['value' => 42.3,  'unit' => '°C'],
        'temp/heat-exchanger'   => ['value' => 68.5,  'unit' => '°C'],
        'temp/pipe-line'        => ['value' => 33.1,  'unit' => '°C'],
        'temp/tank-wall'        => ['value' => 58.7,  'unit' => '°C'],     // DANGEROUS: safe 10-45
        'temp/load-line'        => ['value' => 28.4,  'unit' => '°C'],

        // Pressure sensors
        'pressure/tank-internal'  => ['value' => 0.25,  'unit' => 'bar'],  // DANGEROUS: safe 0.01-0.10
        'pressure/n2-blanketing'  => ['value' => 0.12,  'unit' => 'bar'],  // DANGEROUS: safe 0.02-0.05
        'pressure/pipeline'       => ['value' => 8.5,   'unit' => 'bar'],  // DANGEROUS: safe 2.0-6.0
        'pressure/pump-outlet'    => ['value' => 14.2,  'unit' => 'bar'],  // DANGEROUS: safe 4.0-10.0

        // Level sensors
        'level/radar'       => ['value' => 62.4,  'unit' => '%'],
        'level/ultrasonic'  => ['value' => 61.8,  'unit' => '%'],
        'level/float'       => ['value' => 58.2,  'unit' => '%'],
        'level/overflow'    => ['value' => 0,      'unit' => 'Binary'],

        // Gas sensors
        'gas/voc'  => ['value' => 22.1,   'unit' => 'ppm'],
        'gas/h2s'  => ['value' => 14.3,   'unit' => 'ppm'],              // DANGEROUS: safe 0-5
        'gas/o2'   => ['value' => 20.6,   'unit' => '%'],
        'gas/lel'  => ['value' => 4.2,    'unit' => '% LEL'],

        // Flow sensors
        'flow/transfer'       => ['value' => 128.5,  'unit' => 'm³/h'],
        'flow/recirculation'  => ['value' => 45.3,   'unit' => 'm³/h'],
        'flow/cooling'        => ['value' => 32.1,   'unit' => 'm³/h'],
        'flow/loading'        => ['value' => 210.8,  'unit' => 'm³/h'],

        // Chemical sensors
        'chem/ph'            => ['value' => 7.2,    'unit' => 'pH'],
        'chem/conductivity'  => ['value' => 842.0,  'unit' => 'µS/cm'],
        'chem/salinity'      => ['value' => 48.6,   'unit' => 'ppt'],    // DANGEROUS: safe 0-35
        'chem/byproduct-co'  => ['value' => 67.4,   'unit' => 'ppm'],    // DANGEROUS: safe 0-25

        // Weight sensors
        'weight/loadcell'  => ['value' => 2847.0,  'unit' => 'ton'],
        'weight/ballast'   => ['value' => 55.2,    'unit' => '%'],
        'weight/heel'      => ['value' => 0.8,     'unit' => '°'],
        'weight/trim'      => ['value' => 1.2,     'unit' => 'm'],

        // Fire sensors
        'fire/flame'              => ['value' => 0,    'unit' => 'Binary'],
        'fire/heat'               => ['value' => 0,    'unit' => 'Binary'],
        'fire/spark'              => ['value' => 0,    'unit' => 'Binary'],
        'fire/foam-line-pressure' => ['value' => 8.2,  'unit' => 'bar'],

        // Motor sensors
        'motor/rpm'        => ['value' => 1420,   'unit' => 'rpm'],
        'motor/temp'       => ['value' => 102.3,  'unit' => '°C'],      // DANGEROUS: safe 40-85
        'motor/current'    => ['value' => 52.8,   'unit' => 'A'],
        'motor/vibration'  => ['value' => 2.1,    'unit' => 'mm/s'],

        // Ambient sensors
        'ambient/air-temp'   => ['value' => 28.4,  'unit' => '°C'],
        'ambient/humidity'   => ['value' => 65.2,  'unit' => '%'],
        'ambient/deck-temp'  => ['value' => 35.1,  'unit' => '°C'],
        'ambient/sea-temp'   => ['value' => 22.8,  'unit' => '°C'],
    ];
}

/**
 * Get reference ranges for all sensors
 */
function getSensorRanges() {
    return [
        'temp/tank-internal'      => ['min' => 15,    'max' => 55],
        'temp/heat-exchanger'     => ['min' => 40,    'max' => 85],
        'temp/pipe-line'          => ['min' => 15,    'max' => 50],
        'temp/tank-wall'          => ['min' => 10,    'max' => 45],
        'temp/load-line'          => ['min' => 15,    'max' => 50],
        'pressure/tank-internal'  => ['min' => 0.01,  'max' => 0.10],
        'pressure/n2-blanketing'  => ['min' => 0.02,  'max' => 0.05],
        'pressure/pipeline'       => ['min' => 2.0,   'max' => 6.0],
        'pressure/pump-outlet'    => ['min' => 4.0,   'max' => 10.0],
        'level/radar'             => ['min' => 5,     'max' => 95],
        'level/ultrasonic'        => ['min' => 5,     'max' => 95],
        'level/float'             => ['min' => 10,    'max' => 90],
        'level/overflow'          => ['min' => 0,     'max' => 0],
        'gas/voc'                 => ['min' => 0,     'max' => 50],
        'gas/h2s'                 => ['min' => 0,     'max' => 5],
        'gas/o2'                  => ['min' => 19.5,  'max' => 21.0],
        'gas/lel'                 => ['min' => 0,     'max' => 10],
        'flow/transfer'           => ['min' => 0,     'max' => 250],
        'flow/recirculation'      => ['min' => 0,     'max' => 100],
        'flow/cooling'            => ['min' => 10,    'max' => 50],
        'flow/loading'            => ['min' => 0,     'max' => 500],
        'chem/ph'                 => ['min' => 6.5,   'max' => 8.5],
        'chem/conductivity'       => ['min' => 100,   'max' => 1500],
        'chem/salinity'           => ['min' => 0,     'max' => 35],
        'chem/byproduct-co'       => ['min' => 0,     'max' => 25],
        'weight/loadcell'         => ['min' => 0,     'max' => 5000],
        'weight/ballast'          => ['min' => 0,     'max' => 100],
        'weight/heel'             => ['min' => -3.0,  'max' => 3.0],
        'weight/trim'             => ['min' => 0.0,   'max' => 2.0],
        'fire/flame'              => ['min' => 0,     'max' => 0],
        'fire/heat'               => ['min' => 0,     'max' => 0],
        'fire/spark'              => ['min' => 0,     'max' => 0],
        'fire/foam-line-pressure' => ['min' => 6.0,   'max' => 10.0],
        'motor/rpm'               => ['min' => 0,     'max' => 2900],
        'motor/temp'              => ['min' => 40,    'max' => 85],
        'motor/current'           => ['min' => 0,     'max' => 80],
        'motor/vibration'         => ['min' => 0.0,   'max' => 4.5],
        'ambient/air-temp'        => ['min' => -20,   'max' => 45],
        'ambient/humidity'        => ['min' => 20,    'max' => 90],
        'ambient/deck-temp'       => ['min' => -10,   'max' => 60],
        'ambient/sea-temp'        => ['min' => 0,     'max' => 32],
    ];
}

/**
 * Check if all sensor values are within safe ranges
 */
function allSensorsSafe() {
    if (!isset($_SESSION['sensor_values'])) return false;
    
    $sensors = $_SESSION['sensor_values'];
    $ranges = getSensorRanges();
    
    foreach ($sensors as $key => $data) {
        if (isset($ranges[$key])) {
            $val = $data['value'];
            $min = $ranges[$key]['min'];
            $max = $ranges[$key]['max'];
            if ($val < $min || $val > $max) {
                return false;
            }
        }
    }
    return true;
}

/**
 * Require CCTV login
 */
function requireCCTVLogin() {
    if (!isset($_SESSION['cctv_logged_in']) || $_SESSION['cctv_logged_in'] !== true) {
        $prefix = (basename(dirname($_SERVER['SCRIPT_NAME'])) === '11c77c2c-2bb2-4561') ? '../' : './';
        header('Location: ' . $prefix . 'index.php');
        exit;
    }
}

/**
 * Require ICS login
 */
function requireICSLogin() {
    if (!isset($_SESSION['ics_logged_in']) || $_SESSION['ics_logged_in'] !== true) {
        header('HTTP/1.1 403 Forbidden');
        echo '<!DOCTYPE html><html><body style="background:#000;color:#f00;font-family:monospace;display:flex;align-items:center;justify-content:center;height:100vh;margin:0"><h1>ACCESS DENIED</h1></body></html>';
        exit;
    }
}

/**
 * Require 2FA verification
 */
function require2FA() {
    requireICSLogin();
    if (!isset($_SESSION['ics_2fa_verified']) || $_SESSION['ics_2fa_verified'] !== true) {
        $prefix = (basename(dirname($_SERVER['SCRIPT_NAME'])) === '11c77c2c-2bb2-4561') ? './' : './11c77c2c-2bb2-4561/';
        header('Location: ' . $prefix . 'verify.php');
        exit;
    }
}

// Initialize sensor values if not set
if (!isset($_SESSION['sensor_values'])) {
    $_SESSION['sensor_values'] = initSensorValues();
}
?>
