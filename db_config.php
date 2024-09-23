<?php
// Database connection settings
$host = "localhost";   // Database host, usually 'localhost' for local development
$dbname = "forum_db";  // The name of the database you're connecting to
$username = "root";    // Database username, 'root' is the default for XAMPP
$password = "";        // Database password, empty by default for XAMPP

try {
    // Create a new PDO instance and set error mode to exception
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle connection error
    die("Connection failed: " . $e->getMessage());
}
?>
