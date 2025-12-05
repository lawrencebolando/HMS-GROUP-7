<?php
/**
 * DEBUG PAGE - Access at: http://localhost:8080/HMS-ITE311-G7/public/debug.php
 * This will show you exactly what's wrong
 */

echo "<h1>🔍 DEBUG INFORMATION</h1>";
echo "<style>body{font-family:Arial;padding:20px;} .ok{color:green;} .error{color:red;} .warning{color:orange;}</style>";

// Test 1: Basic PHP
echo "<h2>1. PHP Status</h2>";
echo "<p class='ok'>✅ PHP is working! Version: " . PHP_VERSION . "</p>";

// Test 2: Server Info
echo "<h2>2. Server Information</h2>";
echo "<pre>";
echo "Server Software: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "\n";
echo "Document Root: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'Unknown') . "\n";
echo "Request URI: " . ($_SERVER['REQUEST_URI'] ?? 'Unknown') . "\n";
echo "HTTP Host: " . ($_SERVER['HTTP_HOST'] ?? 'Unknown') . "\n";
echo "Server Port: " . ($_SERVER['SERVER_PORT'] ?? 'Unknown') . "\n";
echo "</pre>";

// Test 3: CodeIgniter
echo "<h2>3. CodeIgniter Status</h2>";
try {
    require __DIR__ . '/../vendor/autoload.php';
    $pathsConfig = require __DIR__ . '/../app/Config/Paths.php';
    $paths = new \Config\Paths();
    require $paths->systemDirectory . '/Boot.php';
    \CodeIgniter\Boot::bootWeb($paths);
    
    $config = new \Config\App();
    echo "<p class='ok'>✅ CodeIgniter loaded!</p>";
    echo "<p><strong>Base URL:</strong> " . htmlspecialchars($config->baseURL) . "</p>";
    echo "<p><strong>Index Page:</strong> " . htmlspecialchars($config->indexPage) . "</p>";
    
    // Test URL generation
    echo "<h3>Generated URLs:</h3>";
    echo "<p><strong>site_url('auth/authenticate'):</strong> " . site_url('auth/authenticate') . "</p>";
    echo "<p><strong>base_url('auth/authenticate'):</strong> " . base_url('auth/authenticate') . "</p>";
    
} catch (\Exception $e) {
    echo "<p class='error'>❌ CodeIgniter failed: " . htmlspecialchars($e->getMessage()) . "</p>";
}

// Test 4: Database
echo "<h2>4. Database Status</h2>";
try {
    $db = \Config\Database::connect();
    echo "<p class='ok'>✅ Database connected!</p>";
    
    if ($db->tableExists('users')) {
        echo "<p class='ok'>✅ Users table exists!</p>";
        
        $userModel = new \App\Models\UserModel();
        $admin = $userModel->where('email', 'admin@globalhospitals.com')->first();
        if ($admin) {
            echo "<p class='ok'>✅ Admin account exists!</p>";
        } else {
            echo "<p class='warning'>⚠️ Admin account NOT found. Run: php spark db:seed AdminSeeder</p>";
        }
    } else {
        echo "<p class='warning'>⚠️ Users table does NOT exist. Run: php spark migrate</p>";
    }
} catch (\Exception $e) {
    echo "<p class='error'>❌ Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
}

// Test 5: Form Action Test
echo "<h2>5. Form Action Test</h2>";
$testAction = base_url('auth/authenticate');
echo "<p><strong>Form action would be:</strong> <code>" . htmlspecialchars($testAction) . "</code></p>";
echo "<p><strong>Try accessing this URL directly:</strong> <a href='" . htmlspecialchars($testAction) . "' target='_blank'>" . htmlspecialchars($testAction) . "</a></p>";
echo "<p class='warning'>⚠️ Note: This will show an error (method not allowed) because it's a POST route, but it confirms the URL works.</p>";

// Test 6: Routes
echo "<h2>6. Route Test</h2>";
echo "<p><a href='" . base_url('login') . "'>Test Login Page</a></p>";
echo "<p><a href='" . base_url('dashboard') . "'>Test Dashboard (will redirect if not logged in)</a></p>";

// Test 7: .env File
echo "<h2>7. Configuration Files</h2>";
$envPath = __DIR__ . '/../.env';
if (file_exists($envPath)) {
    echo "<p class='ok'>✅ .env file exists!</p>";
    $envContent = file_get_contents($envPath);
    if (strpos($envContent, 'app.baseURL') !== false) {
        echo "<p class='ok'>✅ app.baseURL is set in .env</p>";
    } else {
        echo "<p class='warning'>⚠️ app.baseURL not found in .env</p>";
    }
} else {
    echo "<p class='error'>❌ .env file does NOT exist! Create it from the 'env' file.</p>";
}

echo "<hr>";
echo "<h2>📋 SUMMARY</h2>";
echo "<p>Copy this information and share it to get help:</p>";
echo "<ul>";
echo "<li>PHP Version: " . PHP_VERSION . "</li>";
echo "<li>Server Port: " . ($_SERVER['SERVER_PORT'] ?? 'Unknown') . "</li>";
echo "<li>HTTP Host: " . ($_SERVER['HTTP_HOST'] ?? 'Unknown') . "</li>";
echo "<li>Base URL: " . (isset($config) ? $config->baseURL : 'Not loaded') . "</li>";
echo "<li>.env exists: " . (file_exists($envPath) ? 'Yes' : 'No') . "</li>";
echo "</ul>";

echo "<hr>";
echo "<h2>🔧 QUICK FIXES</h2>";
echo "<ol>";
echo "<li>If .env doesn't exist: Copy 'env' file and rename to '.env'</li>";
echo "<li>If database error: Run 'php spark migrate' then 'php spark db:seed AdminSeeder'</li>";
echo "<li>If URLs are wrong: Check the baseURL in app/Config/App.php or .env file</li>";
echo "</ol>";

