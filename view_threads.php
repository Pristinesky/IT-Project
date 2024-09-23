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

// Retrieve all threads from the database with their comments
$stmt = $conn->prepare("SELECT threads.id AS thread_id, threads.title, threads.content, threads.created_at, threads.image_path, 
                        threads.user_id 
                        FROM threads 
                        ORDER BY threads.created_at DESC");
$stmt->execute();
$threads = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($threads as $key => $thread) {
    $comment_stmt = $conn->prepare("SELECT comments.content, comments.created_at, comments.user_id 
                                    FROM comments 
                                    WHERE thread_id = :thread_id
                                    ORDER BY comments.created_at DESC");
    $comment_stmt->bindParam(':thread_id', $thread['thread_id']);
    $comment_stmt->execute();
    $threads[$key]['comments'] = $comment_stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Threads</title>
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
            position: relative;
        }

        .edit-button {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 10;
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

        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            padding-top: 60px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.9);
        }

        .modal-content {
            margin: auto;
            display: block;
            max-width: 90%;
            max-height: 80%;
        }

        .close {
            position: absolute;
            top: 30px;
            right: 35px;
            color: #ffffff;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
        }

        .comment {
            background-color: #f0f0f0;
            color: black;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
        }
        
        form.comment-form {
            margin-top: 20px;
        }

        form.comment-form textarea {
            width: 100%;
            height: 60px;
        }

        form.comment-form button {
            display: block;
            background-color: var(--button-bg);
            color: var(--button-text-color);
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            margin-top: 10px;
            cursor: pointer;
            border-radius: 5px;
        }

        form.comment-form button:hover {
            background-color: var(--button-hover-bg);
        }
    </style>
</head>
<body>
    <h1>All Threads</h1>

    <div class="nav-container">
        <a href="main.php" class="nav-button">Go to Main Page</a>
        <a href="my_threads.php" class="nav-button">View My Threads</a> <!-- Updated button -->
    </div>

    <?php foreach ($threads as $thread): ?>
        <div class="thread">
            <?php if ($thread['user_id'] == $_SESSION['user_id']): ?>
                <a href="edit_thread.php?thread_id=<?php echo htmlspecialchars($thread['thread_id']); ?>" class="edit-button">Edit Post</a>
            <?php endif; ?>

            <h2><?php echo htmlspecialchars($thread['title']); ?></h2>
            <p>Posted by User ID: <?php echo htmlspecialchars($thread['user_id']); ?> on <?php echo htmlspecialchars($thread['created_at']); ?></p>
            <p><?php echo nl2br(htmlspecialchars($thread['content'])); ?></p>

            <?php if (!empty($thread['image_path']) && file_exists($thread['image_path'])): ?>
                <a href="<?php echo htmlspecialchars($thread['image_path']); ?>">
                    <img src="<?php echo htmlspecialchars($thread['image_path']); ?>" alt="Thread Image">
                </a>
            <?php endif; ?>

            <div class="comments">
                <?php foreach ($thread['comments'] as $comment): ?>
                    <div class="comment">
                        <p><?php echo htmlspecialchars($comment['content']); ?></p>
                        <p>Comment by User ID: <?php echo htmlspecialchars($comment['user_id']); ?> on <?php echo htmlspecialchars($comment['created_at']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>

            <form action="post_comment.php" method="post" class="comment-form">
                <input type="hidden" name="thread_id" value="<?php echo htmlspecialchars($thread['thread_id']); ?>">
                <textarea name="content" required placeholder="Add a comment..."></textarea>
                <button type="submit">Post Comment</button>
            </form>
        </div>
    <?php endforeach; ?>

    <div id="image-modal" class="modal">
        <span class="close">&times;</span>
        <img class="modal-content" id="modal-image">
    </div>

    <script>
        var modal = document.getElementById('image-modal');
        var modalImg = document.getElementById('modal-image');
        var closeBtn = document.getElementsByClassName('close')[0];

        var images = document.querySelectorAll('.thread img');
        images.forEach(function(image) {
            image.addEventListener('click', function(event) {
                event.preventDefault();
                modal.style.display = 'block';
                modalImg.src = this.parentElement.getAttribute('href');
            });
        });

        closeBtn.onclick = function() {
            modal.style.display = 'none';
            modalImg.src = '';
        }

        modal.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
                modalImg.src = '';
            }
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && modal.style.display === 'block') {
                modal.style.display = 'none';
                modalImg.src = '';
            }
        });
    </script>
</body>
</html> 