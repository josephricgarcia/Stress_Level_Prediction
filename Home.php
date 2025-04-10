<?php
include 'session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StresSense</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="shortcut icon" href="images/stresssense_logo.png">
</head>
<body>

    <header>
        <div class="logo">
            <img src="images/stresssense_logo.png" alt="Logo"> STRESS SENSE
        </div>
        <nav>
            <a href="Home.php" class="active">HOME</a>
            <a href="Assessment.php">ASSESSMENT</a>
            <a href="History.php">HISTORY</a>
            <a href="Tips And Resources.php">TIPS AND RESOURCES</a>
            <a href="Settings.php">SETTINGS</a>
        </nav>
    </header>

    <section class="info">
        <h1>"Check your stress, know your best."</h1>
        <p>A quick way to evaluate your academic stress level through an insightful assessment.</p>
        
        <button id="getStartedButton">GET STARTED</button>
    </section>

    <footer>
        &copy; 2025 StresSense. All Rights Reserved. |
        <a href="About Us.php">About Us</a> | <a href="Privacy Policy.php">Privacy Policy</a> | <a href="Terms Of Service.php">Terms of Service</a> | <a href="Contact.php">Contact Us</a>
    </footer>
    

    <script>
        document.getElementById("getStartedButton").onclick = function () {
            window.location.href = "Assessment.php";
        };
    </script>

</body>
</html>