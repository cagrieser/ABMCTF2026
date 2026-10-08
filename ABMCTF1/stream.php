<?php
/**
 * Stream Delivery Endpoint
 * Serves media content from the streaming infrastructure.
 *
 * TWO MODES:
 *   1) Channel mode: ?source=X&ch=Y&stream=live  (used by frontend)
 *   2) File mode:    ?source=X&file=Y             (internal/admin use)
 *
 * VULNERABILITY: The origin IP (204.236.245.151) is whitelisted
 * as a trusted node. Combined with the hidden "file" parameter,
 * an attacker can read arbitrary files like flag.txt.
 */

// =============================================
// Embedded flag (no longer read from filesystem)
// =============================================
$EMBEDDED_FLAG = base64_decode('YWJtY3RmezByMWcxbl8xcF9s' . 'MzRrM2RfYnlwNHNzX3c0Zl9nNG0zXzB2M3J9');

// =============================================
// Channel-to-file mapping (internal routing)
// =============================================
$CHANNEL_MAP = [
    '1' => 'spor.mp4',   // TUR vs GEO
    '2' => 'spor.mp4',   // BRA vs SRB (same file, different "channel")
    '3' => 'spor.mp4',   // GER vs JPN
];

// Whitelisted/trusted sources
$TRUSTED_SOURCES = [
    'internal-cdn',
    'cdn-backup-01',
    'cdn-backup-02',
    '204.236.245.151', // Origin server IP - whitelisted for internal use
];

// Blocked patterns (basic SSRF protection)
$BLOCKED_PATTERNS = [
    '/^10\./',
    '/^172\.(1[6-9]|2[0-9]|3[01])\./',
    '/^192\.168\./',
    '/^127\./',
    '/^0\./',
    '/localhost/i',
    '/^::1$/',
    '/\.internal$/i',
    '/\.local$/i',
];

$source = isset($_GET['source']) ? trim($_GET['source']) : '';
$ch     = isset($_GET['ch'])     ? trim($_GET['ch'])     : '';
$stream = isset($_GET['stream']) ? trim($_GET['stream']) : '';
$file   = isset($_GET['file'])   ? trim($_GET['file'])   : '';

// =============================================
// Determine mode: channel vs file
// =============================================
$mode = '';
$targetFile = '';

if (!empty($ch) && !empty($stream)) {
    $mode = 'channel';
} elseif (!empty($file)) {
    $mode = 'file';
}

// Must have node + a valid mode
if (empty($source) || empty($mode)) {
    header('HTTP/1.1 400 Bad Request');
    header('Content-Type: application/json');
    echo json_encode([
        'error'   => 'ERR_MISSING_PARAMS',
        'message' => 'StreamCore API: Invalid request parameters',
        'hint'    => 'Required: source, ch, stream',
        'waf'     => 'CloudShield ACTIVE'
    ], JSON_PRETTY_PRINT);
    exit;
}

// =============================================
// Node trust verification
// =============================================
if (!in_array($source, $TRUSTED_SOURCES, true)) {
    // Check blocked patterns
    foreach ($BLOCKED_PATTERNS as $pattern) {
        if (preg_match($pattern, $source)) {
            header('HTTP/1.1 403 Forbidden');
            header('Content-Type: application/json');
            echo json_encode([
                'error'        => 'ERR_WAF_BLOCKED',
                'message'      => 'CloudShield WAF: Request blocked - suspicious origin detected',
                'threat_level' => 'HIGH',
                'incident_id'  => 'CS-' . strtoupper(substr(md5(time()), 0, 8))
            ], JSON_PRETTY_PRINT);
            exit;
        }
    }

    // Unknown untrusted node
    header('HTTP/1.1 403 Forbidden');
    header('Content-Type: application/json');
    echo json_encode([
        'error'            => 'ERR_UNTRUSTED_SOURCE',
        'message'          => 'CloudShield WAF: Untrusted distribution source',
        'source_requested' => $source,
        'waf_action'     => 'DENY'
    ], JSON_PRETTY_PRINT);
    exit;
}

// =============================================
// Resolve target file based on mode
// =============================================
if ($mode === 'channel') {
    // Channel mode: map channel ID to actual file
    if (!isset($CHANNEL_MAP[$ch])) {
        header('HTTP/1.1 404 Not Found');
        header('Content-Type: application/json');
        echo json_encode([
            'error'   => 'ERR_CHANNEL_OFFLINE',
            'message' => 'Requested channel is not currently broadcasting',
            'channel' => $ch
        ], JSON_PRETTY_PRINT);
        exit;
    }
    $targetFile = $CHANNEL_MAP[$ch];

} elseif ($mode === 'file') {
    // File mode: direct file access
    // CDN nodes can only serve media files
    $file = basename($file); // prevent directory traversal

    if ($source === 'internal-cdn' || $source === 'cdn-backup-01' || $source === 'cdn-backup-02') {
        $MEDIA_EXTENSIONS = ['mp4', 'webm', 'mkv', 'm3u8', 'ts', 'jpg', 'png'];
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if (!in_array($ext, $MEDIA_EXTENSIONS)) {
            header('HTTP/1.1 403 Forbidden');
            header('Content-Type: application/json');
            echo json_encode([
                'error'   => 'ERR_INVALID_FILETYPE',
                'message' => 'Only media files can be served from CDN nodes',
                'allowed' => $MEDIA_EXTENSIONS
            ], JSON_PRETTY_PRINT);
            exit;
        }
    }
    // Origin IP node (204.236.245.151) has NO file type restriction
    $targetFile = $file;
}

// =============================================
// Serve the file
// =============================================

// If flag.txt is requested, serve from embedded value (not filesystem)
if ($targetFile === 'flag.txt') {
    $flagContent = $EMBEDDED_FLAG . "\n";
    header('Content-Type: text/plain');
    header('Content-Length: ' . strlen($flagContent));
    header('X-Served-By: ' . $source);
    header('X-Stream-Source: active');
    header('X-Channel: ' . ($ch ?: 'direct'));
    echo $flagContent;
    exit;
}

$filePath = __DIR__ . DIRECTORY_SEPARATOR . $targetFile;

if (!file_exists($filePath)) {
    header('HTTP/1.1 404 Not Found');
    header('Content-Type: application/json');
    echo json_encode([
        'error'   => 'ERR_FILE_NOT_FOUND',
        'message' => 'Requested resource not available on this node',
        'file'    => $targetFile
    ], JSON_PRETTY_PRINT);
    exit;
}

// Determine content type
$ext = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
$contentTypes = [
    'mp4'  => 'video/mp4',
    'webm' => 'video/webm',
    'mkv'  => 'video/x-matroska',
    'm3u8' => 'application/vnd.apple.mpegurl',
    'ts'   => 'video/mp2t',
    'jpg'  => 'image/jpeg',
    'png'  => 'image/png',
    'txt'  => 'text/plain',
    'html' => 'text/html',
    'json' => 'application/json',
];

$contentType = isset($contentTypes[$ext]) ? $contentTypes[$ext] : 'application/octet-stream';

header('Content-Type: ' . $contentType);
header('Content-Length: ' . filesize($filePath));
header('X-Served-By: ' . $source);
header('X-Stream-Source: active');
header('X-Channel: ' . ($ch ?: 'direct'));

readfile($filePath);
exit;
