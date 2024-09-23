<?php
// Start the session at the very top of the script
session_start();

// Database connection
$host = "localhost"; // Change if necessary
$dbname = "forum_db";
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login and Sign Up</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-image: url('wave.jpg'); /* Path to your wave.jpg file */
            background-size: cover; /* Cover the entire viewport */
            background-position: center; /* Center the image */
        }
        .container {
            text-align: center;
            border: 2px solid #000;
            padding: 40px;
            background-color: rgba(255, 255, 255, 0.25); /* 25% opacity */
        }
        h1 {
            font-size: 48px;
            margin-bottom: 40px;
            color: #FF914D; /* Orange color for "The Hive" */
            text-shadow: 
                -1px -1px 0 #000,  
                 1px -1px 0 #000,
                -1px  1px 0 #000,
                 1px  1px 0 #000; /* Black outline effect */
        }
        input[type="text"],
        input[type="password"],
        input[type="submit"] {
            display: block;
            margin: 20px auto;
            padding: 15px;
            font-size: 20px;
            width: 250px;
            border: 2px solid #000;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #ddd;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #ccc;
        }
        p {
            font-size: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>The Hive</h1> <!-- Changed the text to "The Hive" and applied orange color -->
        <form method="POST" action="">
            <input type="text" name="user_id" placeholder="User ID" required />
            <input type="password" name="password" placeholder="Password" required />
            <input type="submit" name="confirm" value="Confirm" />
            <input type="submit" name="signup" value="Sign Up" />
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['signup'])) {
                $user_id = trim($_POST['user_id']);
                $password = $_POST['password'];

                // Validate input (example: check length of username and password)
                if (strlen($user_id) < 4 || strlen($password) < 6) {
                    echo "<p>User ID must be at least 4 characters and Password must be at least 6 characters long.</p>";
                } else {
                    // Hash the password
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    // Prepare SQL and bind parameters
                    $stmt = $conn->prepare("INSERT INTO users (user_id, password) VALUES (:user_id, :password)");
                    $stmt->bindParam(':user_id', $user_id);
                    $stmt->bindParam(':password', $hashed_password);

                    // Execute and check if successful
                    try {
                        $stmt->execute();
                        echo "<p>Sign up successful! You can now log in.</p>";
                    } catch (PDOException $e) {
                        if ($e->getCode() == 23000) { // Duplicate entry error code
                            echo "<p>User ID already exists. Please choose another one.</p>";
                        } else {
                            echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
                        }
                    }
                }
            }

            if (isset($_POST['confirm'])) {
                $user_id = trim($_POST['user_id']);
                $password = $_POST['password'];

                // Query to get the user's numeric ID and hashed password from the database
                $stmt = $conn->prepare("SELECT id, password FROM users WHERE user_id = :user_id");
                $stmt->bindParam(':user_id', $user_id);
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                // Verify the password
                if ($user && password_verify($password, $user['password'])) {
                    // Store the numeric user ID in the session
                    $_SESSION['user_id'] = $user['id'];  // Correctly store the numeric ID

                    // Redirect to main.php page
                    header('Location: main.php');
                    exit(); // Always exit after calling header to prevent further script execution
                } else {
                    echo "<p>Invalid User ID or Password</p>";
                }
            }
        }
        ?>
    </div>
</body>
</html>
