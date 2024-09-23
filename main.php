<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php'); // Redirect to the login page
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Hive - Main Page</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet"> <!-- Custom Font -->
    <style>
        :root {
            --primary-bg: #0055A4; /* Solid blue background */
            --top-bar-bg: #ffffff; /* White background for buttons */
            --button-bg: #ddd; /* Light grey for buttons */
            --button-hover-bg: #ccc; /* Darker grey on hover */
            --text-color: #000; /* Black text color */
            --highlight-color: #FF914D; /* Orange color for highlighting "The Hive" */
        }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-image: url('blue.jpg'); /* Path to your converted blue.jpg file */
            background-size: cover; /* Cover the entire viewport */
            background-position: center; /* Center the image */
            color: var(--text-color);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            overflow: hidden;
            position: relative; /* Position relative for overlay */
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 85, 164, 0.5); /* Adjusted opacity for visibility */
            z-index: 1; /* Ensures it sits behind other content */
        }

        .top-bar {
            background-color: var(--top-bar-bg);
            height: 70px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding-right: 20px;
            position: relative;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
            z-index: 2; /* Brings the top bar above the overlay */
        }

        .top-bar a {
            background-color: var(--button-bg);
            color: var(--text-color);
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            margin-left: 10px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            border-radius: 4px;
            transition: background-color 0.3s ease, transform 0.3s ease; /* Smooth transitions for hover */
        }

        .top-bar a:hover {
            background-color: var(--button-hover-bg);
            transform: scale(1.05); /* Slight scale effect on hover */
        }

        .picon {
            position: absolute;
            top: 10px;
            left: 20px;
        }

        .picon img {
            width: 150px;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow for logo */
        }

        .welcome-text {
            font-size: 48px;
            margin: 100px 0 30px 0; /* Adjust spacing for visual balance */
            text-align: center;
            animation: fadeIn 2s ease-in-out; /* Smooth fade-in animation */
            z-index: 2; /* Brings the welcome text above the overlay */
            color: #FF914D; /* Orange color for the text */
            text-shadow: 
                -1px -1px 0 #000,  
                 1px -1px 0 #000,
                -1px  1px 0 #000,
                 1px  1px 0 #000; /* Black outline effect */
        }

        .center-buttons {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            gap: 20px;
            flex-grow: 1;
            z-index: 2; /* Brings the buttons above the overlay */
        }

        .logo-image {
            width: 250px; /* Increased width */
            height: auto; /* Maintain aspect ratio */
            margin-bottom: 20px; /* Space between image and buttons */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Optional shadow for visual appeal */
            border-radius: 8px; /* Optional rounded corners */
            animation: fadeIn 2s ease-in-out; /* Added animation */
        }

        .center-buttons a {
            background-color: var(--button-bg);
            color: var(--text-color);
            border: none;
            padding: 20px 40px;
            font-size: 24px;
            cursor: pointer;
            width: 250px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            border-radius: 4px;
            transition: background-color 0.3s ease, transform 0.3s ease; /* Smooth transitions for hover */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Soft shadow for buttons */
        }

        .center-buttons a:hover {
            background-color: var(--button-hover-bg);
            transform: translateY(-3px) scale(1.05); /* Lift and scale effect on hover */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15); /* Enhanced shadow on hover */
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 600px) {
            .welcome-text {
                font-size: 32px;
                margin-top: 60px;
            }

            .center-buttons a {
                width: 200px;
                font-size: 20px;
            }
        }

        /* Optional: Focus Styles for Accessibility */
        .top-bar a:focus,
        .center-buttons a:focus {
            outline: none; /* Remove outline for focus */
        }
    </style>
</head>
<body>

    <div class="overlay"></div>

    <div class="top-bar">
        <a href="about.php" aria-label="About the site">About</a>
        <a href="index.php" aria-label="Logout">Logout</a> <!-- Added Logout Button -->
        <div class="picon">
            <img src="MMU.png" alt="MMU Logo">
        </div>
    </div>

    <div class="welcome-text">
        Welcome to <span>The Hive</span>
    </div>

    <div class="center-buttons">
        <img src="logo.png" alt="Logo" class="logo-image"> <!-- Updated to logo.png -->

        <a href="create_thread.php">Create Threads</a>

        <a href="view_threads.php">View Threads</a>
    </div>

</body>
</html>
