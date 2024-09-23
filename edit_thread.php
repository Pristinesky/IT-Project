<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Include the database configuration
require_once('db_config.php');

// Check if the thread_id is set
if (isset($_GET['thread_id'])) {
    $thread_id = $_GET['thread_id'];
    // Prepare a query to fetch the thread data
    $stmt = $conn->prepare("SELECT title, content, image_path FROM threads WHERE id = ? AND user_id = ?");
    $stmt->execute([$thread_id, $_SESSION['user_id']]);
    $thread = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the thread exists and if the user has permission to edit it
    if (!$thread) {
        echo "Thread not found or you do not have permission to edit it.";
        exit();
    }
} else {
    echo "No thread ID provided.";
    exit();
}

// Handle form submission for updating the thread
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = htmlspecialchars($_POST['title']);
    $content = htmlspecialchars($_POST['content']);
    $new_image_path = $thread['image_path']; // Keep the current image unless a new one is uploaded

    // Check if a new image is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_type = mime_content_type($file_tmp);
        $file_size = $_FILES['image']['size'];
        $max_size = 2 * 1024 * 1024; // 2 MB max

        // Validate file type and size
        if (in_array($file_type, $allowed_types) && $file_size <= $max_size) {
            $file_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $new_file_name = uniqid('img_', true) . '.' . $file_ext;
            $upload_dir = 'uploads/';

            // Create upload directory if it doesn't exist
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            // Move the file to the upload directory
            if (move_uploaded_file($file_tmp, $upload_dir . $new_file_name)) {
                // Delete the old image if it exists
                if (!empty($thread['image_path']) && file_exists($thread['image_path'])) {
                    unlink($thread['image_path']);
                }

                // Set new image path
                $new_image_path = $upload_dir . $new_file_name;
            }
        } else {
            echo "Invalid file type or size.";
        }
    }

    // Update the thread in the database
    $update_stmt = $conn->prepare("UPDATE threads SET title = ?, content = ?, image_path = ? WHERE id = ? AND user_id = ?");
    $update_stmt->execute([$title, $content, $new_image_path, $thread_id, $_SESSION['user_id']]);

    header("Location: view_threads.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Thread</title>
    <style>
        :root {
            --primary-bg: #0055A4;
            --text-color: white;
            --form-bg: #003d7a;
            --button-bg: white;
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
        }

        form {
            background-color: var(--form-bg);
            border: 1px solid var(--text-color);
            border-radius: 5px;
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        input[type="text"],
        textarea,
        input[type="file"] {
            width: calc(100% - 22px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        textarea {
            height: 150px;
        }

        button {
            background-color: var(--button-bg);
            color: black;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }

        button:hover {
            background-color: var(--button-hover-bg);
        }

        /* Style for the image preview */
        .current-image {
            margin-top: 10px;
        }

        .current-image img {
            max-width: 200px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h1>Edit Your Thread</h1>

    <!-- Display the form with the existing thread data -->
    <form method="POST" action="" enctype="multipart/form-data">
        <label for="title">Thread Title:</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($thread['title']); ?>" required>
        <br>

        <label for="content">Content:</label>
        <textarea id="content" name="content" rows="5" required><?php echo htmlspecialchars($thread['content']); ?></textarea>
        <br>

        <!-- Display the current image if it exists -->
        <?php if (!empty($thread['image_path']) && file_exists($thread['image_path'])): ?>
            <div class="current-image">
                <label>Current Image:</label>
                <img src="<?php echo htmlspecialchars($thread['image_path']); ?>" alt="Current Thread Image">
            </div>
        <?php endif; ?>
        <br>

        <label for="image">Replace Image (optional):</label>
        <input type="file" id="image" name="image" accept="image/*">
        <br>

        <button type="submit">Update Thread</button>
    </form>
</body>
</html>
