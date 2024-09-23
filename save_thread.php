Save threads

<?php
// Database connection settings
$servername = "localhost";
$db_username = "root"; // Use 'root' if you're using XAMPP and haven't set a password
$db_password = ""; // Empty string for XAMPP default
$dbname = "hive_threads";

// Create a connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize the input data
    $username = htmlspecialchars($_POST['username']);
    $title = htmlspecialchars($_POST['title']);
    $content = htmlspecialchars($_POST['content']);

    // Prepare SQL to insert the thread into the database
    $stmt = $conn->prepare("INSERT INTO threads (username, title, content) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $title, $content);

    // Execute the query
    if ($stmt->execute()) {
        // Redirect to the view threads page
        header("Location: view_threads.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    // If the form wasn't submitted, redirect back to the thread creation page
    header("Location: create_thread.php");
}
?>