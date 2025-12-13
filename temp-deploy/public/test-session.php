<?php
session_start();

echo "<h2>Session Test</h2>";
echo "<hr>";

// Set session
$_SESSION['test'] = 'Session is working!';
$_SESSION['timestamp'] = time();
$_SESSION['counter'] = isset($_SESSION['counter']) ? $_SESSION['counter'] + 1 : 1;

echo "<h3>✅ Session Data:</h3>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

echo "<h3>Session ID:</h3>";
echo "<p>" . session_id() . "</p>";

echo "<h3>Session Save Path:</h3>";
echo "<p>" . session_save_path() . "</p>";

echo "<h3>Session Status:</h3>";
echo "<p>Status: " . (session_status() == PHP_SESSION_ACTIVE ? 'ACTIVE' : 'INACTIVE') . "</p>";

echo "<hr>";
echo "<p><strong>Instructions:</strong> Refresh this page. If 'counter' increases, session is working!</p>";
echo "<p><a href='/'>← Back to Home</a> | <a href='javascript:location.reload()'>🔄 Refresh</a></p>";
?>
