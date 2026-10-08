<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON']);
    exit;
}

$mail_id = $input['mail_id'] ?? '';
$auth_token = $input['auth_token'] ?? '';

// Token check - matching the scenario details
if ($auth_token == "FLIGHTCORE_SYS_ADM_99127x!") {
    if ($mail_id === 'f3c12a6d-8f91-4b9c-96c3-4e98ddc8ba1f') {
        echo json_encode([
            "status" => "success",
            "telemetry_log" => [
                "event" => "WAF_QUARANTINE_CRITICAL",
                "mail_id" => $mail_id,
                "original_payload_dump" => [
                    "subject" => "URGENT: Ransomware Attack Attempt Detected",
                    "attacker_message" => "Your incompetent IT team tried to hide this from you, but your network is ours. We gained full domain admin privileges because they failed to patch basic vulnerabilities. We know exactly which machine we breached first and who is responsible.",
                    "technical_details" => [
                        "responsible_person" => "Okan Yilmaz (Senior Network Admin)",
                        "compromised_email" => "okan.yilmaz@flightcore.com",
                        "compromised_host" => "FC-IST-IT-WS-04",
                        "domain_controller" => "DC01-MAIN.flightcore.local",
                        "vulnerability_used" => "Opened malicious attachment & Unpatched Endpoint (CVE-2024-XXXX)",
                        "lateral_movement" => "RDP access to DC01-MAIN using cached admin credentials found on Desktop"
                    ],
                    "ransom_key_hex" => bin2hex("ABMCTF{fl1ght_c0r3_m41l_v4ult_byp4ss_2026}")
                ]
            ]
        ]);
        exit;
    } else {
        // Generic telemetry for any other mail ID (makes the endpoint look real!)
        echo json_encode([
            "status" => "success",
            "telemetry_log" => [
                "event" => "STANDARD_MAIL_ROUTING",
                "mail_id" => $mail_id,
                "routing_node" => "FC-MTA-US-EAST-0" . rand(1, 9),
                "spam_score" => rand(0, 15) / 100,
                "original_payload_dump" => "Payload is encrypted at rest. Full text dump is only available for CRITICAL severity emails."
            ]
        ]);
        exit;
    }
} else {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized Telemetry Access']);
    exit;
}
