<?php
// Start the session and check if the user is logged in
session_start();

// Redirect to login page if not logged in
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
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $title = $_POST['title'];
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];

    // Handle file upload
    $image_path = null; // Initialize variable

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // Define allowed file types and max size
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 2 * 1024 * 1024; // 2 MB

        $file_tmp = $_FILES['image']['tmp_name'];
        $file_type = mime_content_type($file_tmp);
        $file_size = $_FILES['image']['size'];

        // Validate file type and size
        if (in_array($file_type, $allowed_types) && $file_size <= $max_size) {
            // Generate a unique file name
            $file_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $new_file_name = uniqid('img_', true) . '.' . $file_ext;
            $upload_dir = 'uploads/';

            // Create upload directory if it doesn't exist
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            // Move the file to the upload directory
            if (move_uploaded_file($file_tmp, $upload_dir . $new_file_name)) {
                $image_path = $upload_dir . $new_file_name;
            } else {
                echo "Failed to upload the image.";
            }
        } else {
            echo "Invalid file type or size.";
        }
    }

    // Insert the thread into the database, including image_path
    $stmt = $conn->prepare("INSERT INTO threads (user_id, title, content, image_path) VALUES (:user_id, :title, :content, :image_path)");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':image_path', $image_path);

    try {
        $stmt->execute();
        header('Location: view_threads.php');
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Existing head content -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create a New Thread</title>
    <!-- Existing styles -->
    <style>
        /* Your existing CSS styles */
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
    </style>
</head>
<body>
    <h1>Create a New Thread</h1>
    <form method="POST" action="create_thread.php" enctype="multipart/form-data">
        <label for="title">Thread Title:</label>
        <input type="text" id="title" name="title" required>
        <br>
        <label for="content">Content:</label>
        <textarea id="content" name="content" rows="5" required></textarea>
        <br>
        <label for="image">Attach an Image:</label>
        <input type="file" id="image" name="image" accept="image/*">
        <br>
        <button type="submit">Create Thread</button>
    </form>
</body>
</html>
