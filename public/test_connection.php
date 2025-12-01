<?php
/**
 * Quick Connection Test
 * Access this at: http://localhost:8080/HMS-ITE311-G7/public/test_connection.php
 */

echo "<h1>Connection Test</h1>";
echo "<p>If you can see this, Apache is running correctly!</p>";

echo "<h2>Server Information:</h2>";
echo "<pre>";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "Server Software: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "\n";
echo "Document Root: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'Unknown') . "\n";
echo "Request URI: " . ($_SERVER['REQUEST_URI'] ?? 'Unknown') . "\n";
echo "</pre>";

echo "<h2>CodeIgniter Test:</h2>";
try {
    require __DIR__ . '/../vendor/autoload.php';
    $pathsConfig = require __DIR__ . '/../app/Config/Paths.php';
    $paths = new \Config\Paths();
    require $paths->systemDirectory . '/Boot.php';
    \CodeIgniter\Boot::bootWeb($paths);
    
    $config = new \Config\App();
    echo "<p>✅ CodeIgniter loaded successfully!</p>";
    echo "<p>Base URL: " . $config->baseURL . "</p>";
    
    // Test database connection
    try {
        $db = \Config\Database::connect();
        echo "<p>✅ Database connection successful!</p>";
        
        // Check if users table exists
        if ($db->tableExists('users')) {
            echo "<p>✅ Users table exists!</p>";
            
            // Check for admin account
            $userModel = new \App\Models\UserModel();
            $admin = $userModel->where('email', 'admin@globalhospitals.com')->first();
            if ($admin) {
                echo "<p>✅ Admin account exists!</p>";
                echo "<p>Admin Name: " . htmlspecialchars($admin['name']) . "</p>";
            } else {
                echo "<p>⚠️ Admin account NOT found. Run: php spark db:seed AdminSeeder</p>";
            }
        } else {
            echo "<p>⚠️ Users table does NOT exist. Run: php spark migrate</p>";
        }
    } catch (\Exception $e) {
        echo "<p>❌ Database connection failed: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
    
} catch (\Exception $e) {
    echo "<p>❌ CodeIgniter failed to load: " . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "<hr>";
echo "<h2>Next Steps:</h2>";
echo "<ol>";
echo "<li>If you see this page, Apache is working</li>";
echo "<li>Check if CodeIgniter loaded (should show ✅)</li>";
echo "<li>Check if database connected (should show ✅)</li>";
echo "<li>Check if users table exists (should show ✅)</li>";
echo "<li>Check if admin account exists (should show ✅)</li>";
echo "</ol>";

echo "<p><a href='login'>Go to Login Page</a></p>";

