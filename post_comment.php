<?php
session_start();
include 'db_connection.php'; // Include your database connection setup

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content'], $_POST['thread_id']) && isset($_SESSION['user_id'])) {
    $content = trim($_POST['content']);
    $thread_id = $_POST['thread_id'];
    $user_id = $_SESSION['user_id']; // Retrieve the user_id from the session

    // Debugging: Output the values
    echo "User ID: " . $user_id . "<br>";
    echo "Thread ID: " . $thread_id . "<br>";

    // Check if the user_id exists in the users table
    $checkUserStmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE id = :user_id");
    $checkUserStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $checkUserStmt->execute();
    $userExists = $checkUserStmt->fetchColumn();

    if (!$userExists) {
        die("Error: The user ID does not exist in the users table.");
    }

    // Check if the thread_id exists in the threads table
    $checkThreadStmt = $conn->prepare("SELECT COUNT(*) FROM threads WHERE id = :thread_id");
    $checkThreadStmt->bindParam(':thread_id', $thread_id, PDO::PARAM_INT);
    $checkThreadStmt->execute();
    $threadExists = $checkThreadStmt->fetchColumn();

    if (!$threadExists) {
        die("Error: The thread ID does not exist in the threads table.");
    }

    // Insert the comment if both user_id and thread_id are valid
    $stmt = $conn->prepare("INSERT INTO comments (content, thread_id, user_id) VALUES (:content, :thread_id, :user_id)");
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':thread_id', $thread_id);
    $stmt->bindParam(':user_id', $user_id);

    try {
        $stmt->execute();
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }

    header('Location: view_threads.php'); // Redirect to the threads page
    exit;
} else {
    // Redirect to login page if not logged in or data incomplete
    header('Location: login.php');
    exit;
}
?>
