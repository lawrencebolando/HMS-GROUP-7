<?php
/**
 * Test Session - Access at: http://localhost:8080/HMS-ITE311-G7/public/test_session.php
 * This will show your current session data
 */

require __DIR__ . '/../vendor/autoload.php';
$pathsConfig = require __DIR__ . '/../app/Config/Paths.php';
$paths = new \Config\Paths();
require $paths->systemDirectory . '/Boot.php';
\CodeIgniter\Boot::bootWeb($paths);

$session = session();

echo "<h1>Session Test</h1>";
echo "<style>body{font-family:Arial;padding:20px;} table{border-collapse:collapse;width:100%;} th,td{border:1px solid #ddd;padding:8px;text-align:left;} th{background-color:#4CAF50;color:white;}</style>";

echo "<h2>Current Session Data:</h2>";
echo "<table>";
echo "<tr><th>Key</th><th>Value</th></tr>";

$sessionData = [
    'user_id' => $session->get('user_id'),
    'user_name' => $session->get('user_name'),
    'user_email' => $session->get('user_email'),
    'user_role' => $session->get('user_role'),
    'is_logged_in' => $session->get('is_logged_in'),
];

foreach ($sessionData as $key => $value) {
    $displayValue = $value === null ? '<em style="color:red;">NULL</em>' : htmlspecialchars($value);
    $color = $value === null ? 'background-color:#ffcccc;' : '';
    echo "<tr style='$color'><td><strong>$key</strong></td><td>$displayValue</td></tr>";
}

echo "</table>";

echo "<h2>Status:</h2>";
if ($session->get('is_logged_in')) {
    echo "<p style='color:green;font-size:18px;'>✅ You are LOGGED IN</p>";
    echo "<p>User: " . htmlspecialchars($session->get('user_name') ?? 'Unknown') . "</p>";
    echo "<p>Role: " . htmlspecialchars($session->get('user_role') ?? 'Unknown') . "</p>";
    
    if ($session->get('user_role') === 'admin') {
        echo "<p style='color:green;'>✅ You have ADMIN access</p>";
        echo "<p><a href='" . site_url('dashboard') . "'>Go to Dashboard</a></p>";
    } else {
        echo "<p style='color:orange;'>⚠️ You do NOT have admin access</p>";
    }
} else {
    echo "<p style='color:red;font-size:18px;'>❌ You are NOT logged in</p>";
    echo "<p><a href='" . site_url('login') . "'>Go to Login</a></p>";
}

echo "<hr>";
echo "<h2>All Session Data (Raw):</h2>";
echo "<pre>";
print_r($_SESSION ?? 'No session data');
echo "</pre>";

echo "<hr>";
echo "<p><a href='" . site_url('login') . "'>Login Page</a> | <a href='" . site_url('dashboard') . "'>Dashboard</a></p>";

