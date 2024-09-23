<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$host = "localhost"; 
$dbname = "forum_db";
$username = "root"; 
$password = ""; 

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . htmlspecialchars($e->getMessage()));
}

// Check if thread_id is set
if (isset($_POST['thread_id'])) {
    $thread_id = $_POST['thread_id'];

    // Check if the current user is the owner of the thread
    $stmt = $conn->prepare("SELECT user_id FROM threads WHERE id = :thread_id");
    $stmt->bindParam(':thread_id', $thread_id);
    $stmt->execute();
    $thread = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($thread && $thread['user_id'] == $_SESSION['user_id']) {
        // Delete the thread
        $delete_stmt = $conn->prepare("DELETE FROM threads WHERE id = :thread_id");
        $delete_stmt->bindParam(':thread_id', $thread_id);
        $delete_stmt->execute();

        header('Location: view_threads.php'); // Redirect after deletion
        exit();
    } else {
        echo "You are not allowed to delete this thread.";
    }
}
?>
