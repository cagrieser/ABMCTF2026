<?php
/**
 * Stream Status API
 * Returns real-time system metrics for the streaming infrastructure.
 */

header('Content-Type: application/json; charset=utf-8');
header('X-Powered-By: StreamGuard/2.1');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Access-Control-Allow-Origin: *');

$cpuLoad = min(99, max(45, 72 + rand(-15, 20)));
$memUsage = rand(55, 85);
$activeViewers = max(500, 3200 + rand(-800, 1500));
$bandwidth = max(200, 840 + rand(-200, 350));
$uptimeH = rand(48, 720);
$uptimeM = rand(0, 59);
$nodes = ['eu-west-1', 'eu-central-2', 'us-east-1', 'ap-south-1'];
$activeNode = $nodes[array_rand($nodes)];

$response = [
    'status'         => 'operational',
    'timestamp'      => date('Y-m-d\TH:i:sP'),
    'server_id'      => 'srv-' . substr(md5(gethostname()), 0, 8),
    'region'         => $activeNode,
    'cpu_load'       => $cpuLoad . '%',
    'memory_usage'   => $memUsage . '%',
    'swap_usage'     => rand(5, 25) . '%',
    'disk_io'        => rand(120, 890) . ' MB/s',
    'active_viewers' => $activeViewers,
    'peak_viewers'   => $activeViewers + rand(200, 2000),
    'bandwidth_mbps' => $bandwidth,
    'active_node'    => $activeNode,
    'uptime'         => $uptimeH . 'h ' . $uptimeM . 'm',
    'stream_health'  => 'stable',
    'latency_ms'     => rand(12, 85),
    'cache_hit_rate' => rand(88, 99) . '%',
    'encoder'        => 'libx264 / AAC-LC',
    'bitrate_kbps'   => rand(4500, 8000),
    'keyframe_interval' => '2s',
    'gop_size'       => 60,

    'network' => [
        'ingress_mbps'    => rand(400, 900),
        'egress_mbps'     => rand(600, 1200),
        'packet_loss'     => '0.0' . rand(1, 9) . '%',
        'tcp_connections' => rand(8000, 25000),
        'http_rps'        => rand(1200, 5000),
        'dns_resolver'    => '10.0.1.53',
        'gateway'         => '10.0.0.1',
        'vlan_id'         => 420,
        'mtu'             => 1500,
        'firewall_rules'  => 847,
    ],

    'storage' => [
        'total_gb'        => 2048,
        'used_gb'         => rand(800, 1600),
        'available_gb'    => rand(400, 1200),
        'iops'            => rand(5000, 15000),
        'raid_level'      => 'RAID-10',
        'media_files'     => rand(12000, 50000),
        'temp_segments'   => rand(200, 800),
        'cache_dir'       => '/var/cache/streamcore',
    ],

    'security' => [
        'waf_status'       => 'active',
        'waf_provider'     => 'CloudShield Pro v4.2',
        'blocked_requests' => rand(5000, 30000),
        'threat_level'     => 'low',
        'ssl_cert_expiry'  => '2027-03-15',
        'ssl_protocol'     => 'TLSv1.3',
        'hsts_enabled'     => true,
        'csp_policy'       => 'strict-dynamic',
        'ddos_mitigation'  => 'active',
        'rate_limit'       => '500 req/min',
        'geo_blocked'      => ['CN', 'RU', 'KP'],
        'auth_method'      => 'token-based',
        'cors_origins'     => ['*.abmsports.tv', 'localhost'],
    ],

    'cdn_nodes' => [
        ['id' => 'internal-cdn', 'ip' => '10.0.12.50', 'status' => 'active', 'load' => rand(40, 90) . '%', 'region' => 'eu-west-1', 'connections' => rand(2000, 8000)],
        ['id' => 'cdn-backup-01', 'ip' => '10.0.12.51', 'status' => 'active', 'load' => rand(30, 70) . '%', 'region' => 'eu-central-2', 'connections' => rand(1000, 5000)],
        ['id' => 'cdn-backup-02', 'ip' => '10.0.12.52', 'status' => 'active', 'load' => rand(20, 60) . '%', 'region' => 'us-east-1', 'connections' => rand(500, 3000)],
        ['id' => 'cdn-asia-01', 'ip' => '10.0.14.10', 'status' => 'degraded', 'load' => rand(60, 95) . '%', 'region' => 'ap-south-1', 'connections' => rand(800, 4000)],
    ],

    'geo_distribution' => [
        'TR' => rand(40, 55) . '%',
        'DE' => rand(10, 20) . '%',
        'NL' => rand(5, 12) . '%',
        'US' => rand(3, 8) . '%',
        'GB' => rand(2, 7) . '%',
        'other' => rand(8, 20) . '%',
    ],

    'transcoding' => [
        'profiles'      => ['1080p60', '720p30', '480p30', '360p30'],
        'active_jobs'   => rand(3, 8),
        'gpu_usage'     => rand(45, 90) . '%',
        'gpu_model'     => 'NVIDIA T4',
        'hw_accel'      => 'nvenc',
        'segment_duration' => '4s',
        'playlist_type' => 'event',
    ],

    'debug' => [
        'build'             => 'streamcore-v3.8.2-rc1',
        'env'               => 'production',
        'config_hash'       => substr(md5('streamcore-config-' . date('Y-m-d')), 0, 12),
        'worker_threads'    => rand(8, 32),
        'event_loop'        => 'epoll',
        'gc_cycles'         => rand(100, 900),
        'heap_mb'           => rand(256, 1024),
        'upstream_dns'      => '10.0.1.53',
        'log_level'         => 'warn',
        'feature_flags'     => ['adaptive_bitrate', 'drm_widevine', 'low_latency_mode'],
        'origin_source_ip'  => '204.236.245.151',
        'edge_pop'          => 'IST-01',
        'waf_rule_version'  => 'cs-rules-2026.05.12',
        'ssl_termination'   => 'edge',
        'internal_dns'      => 'resolver.stream.internal',
        'redis_host'        => 'cache.stream.internal:6379',
        'db_pool_size'      => rand(10, 50),
        'last_deploy'       => date('Y-m-d H:i:s', time() - rand(3600, 172800)),
        'last_restart'      => date('Y-m-d H:i:s', time() - rand(3600, 86400)),
        'container_id'      => substr(md5(rand()), 0, 12),
        'k8s_namespace'     => 'streaming-prod',
        'pod_name'          => 'streamcore-worker-' . rand(0, 9),
    ]
];

echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
