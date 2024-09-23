<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Hive - About</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet"> <!-- Custom Font -->
    <style>
        /* CSS Styles */

        :root {
            --primary-bg: #0055A4; /* Solid blue background for consistency */
            --top-bar-bg: #ffffff; /* White background for buttons */
            --button-bg: #ddd; /* Light grey for buttons */
            --button-hover-bg: #ccc; /* Darker grey on hover */
            --text-color: #FFFFFF; /* White text color */
            --highlight-color: #FFDD57; /* Yellow color for highlighting elements */
        }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif; /* Custom font for a modern look */
            background-image: url('pattern.jpg'); /* Path to your pattern.jpg file */
            background-size: cover; /* Ensures the image covers the entire viewport */
            background-position: center; /* Centers the image */
            background-repeat: no-repeat; /* Prevents the image from repeating */
            color: var(--text-color);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            overflow: hidden; /* Hide overflow for clean edges */
        }

        .top-bar {
            background-color: var(--top-bar-bg);
            height: 70px;
            width: 100%;
            display: flex;
            align-items: center;
            padding: 0 20px;
            position: fixed; /* Fixed to the top */
            top: 0;
            left: 0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Shadow for depth */
            z-index: 100;
        }

        .picon {
            position: relative;
        }

        .picon img {
            width: 150px;
            height: auto;
            border-radius: 8px; /* Rounded corners for image */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow for image */
        }

        .nav-button {
            margin-left: auto; /* Align to the right */
            background-color: var(--button-bg);
            color: #000; /* Button text color */
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none; /* Remove underline */
            transition: background-color 0.3s;
            margin-left: 10px; /* Adjusted margin to move button left */
        }

        .nav-button:hover {
            background-color: var(--button-hover-bg); /* Change background on hover */
        }

        .content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
            text-align: center;
            margin-top: 100px; /* Margin to avoid overlap with the fixed top bar */
            animation: fadeIn 2s ease-in-out; /* Smooth fade-in animation */
        }

        .content img {
            width: 300px; /* Smaller width for better layout */
            height: auto;
            margin-bottom: 20px; /* Adds space between the image and text */
            border-radius: 8px; /* Rounded corners for the image */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow for the image */
            transition: transform 0.3s ease; /* Smooth transition for hover effect */
        }

        .content img:hover {
            transform: scale(1.05); /* Slight scaling effect on hover for the image */
        }

        .content h2 {
            font-size: 20px;
            max-width: 800px;
            color: var(--text-color); /* White text color */
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 600px) {
            .picon img {
                width: 100px;
            }

            .content img {
                width: 200px;
            }

            .content h2 {
                font-size: 16px;
            }
        }

        /* Optional: Focus Styles for Accessibility */
        .top-bar a:focus {
            outline: 2px solid #0055A4;
            outline-offset: 2px;
        }
    </style>
</head>
<body>

    <!-- Top Bar with Logo -->
    <div class="top-bar">
        <div class="picon">
            <img src="MMU.png" alt="MMU Logo">
        </div>
        <a href="main.php" class="nav-button">Go to Main</a> <!-- Button added here -->
    </div>

    <!-- Content Section -->
    <div class="content">
        <!-- Smaller Image on top of the text -->
        <img src="hackerman.jpg" alt="Descriptive Image">

        <!-- Text Content -->
        <h2>Welcome to our platform, developed exclusively for MMU students to share their thoughts and ideas anonymously. While this project began as a university assignment, it has provided us with invaluable insights into the entire multimedia development process, significantly expanding our knowledge and perspectives within the industry.</h2>
    </div>

</body>
</html>
