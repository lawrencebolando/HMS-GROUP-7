<?php
/**
 * Update Admin Name Script
 * 
 * This script updates the admin account name to "St. Elizabeth Hospital, Inc."
 * 
 * Access via browser: http://localhost/HMS-ITE311-G7/update_admin_name.php
 */

// Get the database config
$dbConfig = require __DIR__ . '/app/Config/Database.php';

// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'ite-hms-g7';

// Connect to database
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<h2>Updating Admin Name</h2>";

// Update the admin name
$newName = "St. Elizabeth Hospital, Inc.";
$email = "admin@globalhospitals.com";

// First, get the current name
$sql = "SELECT name FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $oldName = $row['name'];
    
    // Update the name
    $updateSql = "UPDATE users SET name = ? WHERE email = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("ss", $newName, $email);
    
    if ($updateStmt->execute()) {
        echo "<p style='color: green;'>✓ Admin name updated successfully!</p>";
        echo "<p>Old name: <strong>" . htmlspecialchars($oldName) . "</strong></p>";
        echo "<p>New name: <strong>" . htmlspecialchars($newName) . "</strong></p>";
        echo "<p><a href='public/login'>Go to Login Page</a></p>";
    } else {
        echo "<p style='color: red;'>✗ Error updating name: " . $conn->error . "</p>";
    }
    
    $updateStmt->close();
} else {
    echo "<p style='color: red;'>✗ Admin account not found!</p>";
}

$stmt->close();
$conn->close();

echo "<hr>";
echo "<p><strong>Note:</strong> You may need to logout and login again to see the changes.</p>";
