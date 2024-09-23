<?php
// Start the session and check if the user is logged in
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Database connection
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

// Retrieve threads created by the logged-in user
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT threads.id AS thread_id, threads.title, threads.content, threads.created_at, threads.image_path 
                        FROM threads 
                        WHERE threads.user_id = :user_id 
                        ORDER BY threads.created_at DESC");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$threads = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Threads</title>
    <style>
        :root {
            --primary-bg: #0055A4; 
            --text-color: white;
            --thread-bg: #003d7a; 
            --thread-border: #00274d; 
            --button-bg: white;
            --button-text-color: black;
            --button-hover-bg: #dcdcdc;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: var(--primary-bg);
            color: var(--text-color);
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-top: 0;
        }

        .nav-container {
            position: sticky;
            top: 0;
            z-index: 1000;
            background-color: var(--primary-bg);
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .nav-button {
            display: inline-block;
            background-color: var(--button-bg);
            color: var(--button-text-color);
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            text-decoration: none;
            cursor: pointer;
            border-radius: 5px;
            margin: 10px auto 0 auto;
        }

        .nav-button:hover {
            background-color: var(--button-hover-bg);
        }

        .thread {
            background-color: var(--thread-bg);
            border: 1px solid var(--thread-border);
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
            position: relative; /* Allows positioning of edit button */
        }

        .edit-button {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: var(--button-bg);
            color: var(--button-text-color);
            border: none;
            padding: 5px 10px;
            font-size: 14px;
            text-decoration: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .edit-button:hover {
            background-color: var(--button-hover-bg);
        }

        .thread h2 {
            margin: 0;
            font-size: 1.5em;
        }

        .thread p {
            margin: 5px 0;
        }

        .thread img {
            display: block;
            max-width: 200px;
            max-height: 200px;
            width: auto;
            height: auto;
            margin-top: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>My Threads</h1>

    <div class="nav-container">
        <a href="main.php" class="nav-button">Go to Main Page</a>
        <a href="view_threads.php" class="nav-button">View All Threads</a>
    </div>

    <?php if (empty($threads)): ?>
        <p>No threads found.</p>
    <?php else: ?>
        <?php foreach ($threads as $thread): ?>
            <div class="thread">
                <a href="edit_thread.php?thread_id=<?php echo htmlspecialchars($thread['thread_id']); ?>" class="edit-button">Edit Post</a>
                <h2><?php echo htmlspecialchars($thread['title']); ?></h2>
                <p>Posted on <?php echo htmlspecialchars($thread['created_at']); ?></p>
                <p><?php echo nl2br(htmlspecialchars($thread['content'])); ?></p>

                <?php if (!empty($thread['image_path']) && file_exists($thread['image_path'])): ?>
                    <a href="<?php echo htmlspecialchars($thread['image_path']); ?>">
                        <img src="<?php echo htmlspecialchars($thread['image_path']); ?>" alt="Thread Image">
                    </a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
