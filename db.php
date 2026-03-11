<?php
// Database configuration
$servername = "127.0.0.1"; // Using IP instead of localhost for Windows compatibility
$username = "root";      // Default XAMPP username
$password = "Manan.01";          // If this fails, try "root" or your custom password
$dbname = "realestate_db";

// In PHP 8.1+, mysqli throws exceptions by default. 
// We use try-catch to provide a helpful message to the user.
try {
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Set charset
    $conn->set_charset("utf8mb4");

} catch (mysqli_sql_exception $e) {
    if ($e->getCode() == 1045) {
        die("<div style='font-family:sans-serif; padding:20px; border:2px solid red;'>
            <h2 style='color:red;'>Access Denied to MySQL</h2>
            <p>Your MySQL 'root' user <strong>requires a password</strong>.</p>
            <ol>
                <li>Open <code>db.php</code></li>
                <li>Change <code>\$password = '';</code> to <code>\$password = 'your_password';</code></li>
            </ol>
            <p><em>Note: If you are using WAMP, the password might be empty. If you are using MAMP, it is usually 'root'.</em></p>
        </div>");
    }
    if ($e->getCode() == 1049) {
        die("<h2 style='color:red;'>Database 'realestate_db' not found</h2>
             <p>Please go to phpMyAdmin and create the database, then import <code>database.sql</code>.</p>");
    }
    die("Connection failed: " . $e->getMessage());
}
?>