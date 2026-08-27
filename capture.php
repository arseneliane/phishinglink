<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');

function reply(int $status, string $message): void {
    http_response_code($status);
    echo json_encode(['status' => $status < 400 ? 'success' : 'error', 'message' => $message]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    reply(405, 'Use POST.');
}

$raw = file_get_contents('php://input', false, null, 0, 4097);
if ($raw === false || strlen($raw) > 4096) {
    reply(413, 'Request too large.');
}
$data = json_decode($raw, true);
if (!is_array($data) || ($data['consent'] ?? false) !== true) {
    reply(400, 'Explicit consent is required.');
}

$lat = $data['latitude'] ?? $data['lat'] ?? null;
$lng = $data['longitude'] ?? $data['lng'] ?? null;
$accuracy = $data['accuracy'] ?? null;
foreach ([$lat, $lng, $accuracy] as $value) {
    if ((!is_int($value) && !is_float($value)) || !is_finite((float) $value)) {
        reply(400, 'Invalid coordinates or accuracy.');
    }
}
if ($lat < -90 || $lat > 90 || $lng < -180 || $lng > 180 || $accuracy < 0) {
    reply(400, 'Invalid coordinates or accuracy.');
}

$entry = [
    'event' => 'CONSENTED_LOCATION_SUBMISSION',
    'receivedAt' => gmdate('c'),
    'latitude' => round($lat, 5),
    'longitude' => round($lng, 5),
    'accuracy' => round($accuracy),
];
$logEntry = "========================================\n";
$logEntry .= "Time (UTC): " . $entry['receivedAt'] . "\n";
$logEntry .= "Latitude: " . $entry['latitude'] . "\n";
$logEntry .= "Longitude: " . $entry['longitude'] . "\n";
$logEntry .= "Accuracy: " . $entry['accuracy'] . " meters\n";
$logEntry .= "----------------------------------------\n\n";

// This directory is outside Apache's public document root.
if (file_put_contents('/var/lib/location-demo/logs.txt', $logEntry, FILE_APPEND | LOCK_EX) === false) {
    reply(500, 'Unable to save submission.');
}
// Keep the account-protected Render Logs retrieval workflow available.
error_log(json_encode($entry, JSON_UNESCAPED_SLASHES));
reply(201, 'Location logged with your consent.');
