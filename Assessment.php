<?php
include 'session.php';
include 'connection.php';

if (isset($_POST['submit'])) {
    $studyhours = filter_input(INPUT_POST, 'studyhours', FILTER_VALIDATE_FLOAT, ['options' => ['min_range' => 0, 'max_range' => 24]]);
    $hobbyhours = filter_input(INPUT_POST, 'hobbyhours', FILTER_VALIDATE_FLOAT, ['options' => ['min_range' => 0, 'max_range' => 24]]);
    $sleephours = filter_input(INPUT_POST, 'sleephours', FILTER_VALIDATE_FLOAT, ['options' => ['min_range' => 0, 'max_range' => 24]]);
    $socialhours = filter_input(INPUT_POST, 'socialhours', FILTER_VALIDATE_FLOAT, ['options' => ['min_range' => 0, 'max_range' => 24]]);
    $activehours = filter_input(INPUT_POST, 'activehours', FILTER_VALIDATE_FLOAT, ['options' => ['min_range' => 0, 'max_range' => 24]]);
    $gwa = filter_input(INPUT_POST, 'gwa', FILTER_VALIDATE_FLOAT, ['options' => ['min_range' => 1.0, 'max_range' => 5.0]]);

    if ($studyhours !== false && $hobbyhours !== false && $sleephours !== false &&
        $socialhours !== false && $activehours !== false && $gwa !== false) {
        
        $userId = $_SESSION['user_id'];
        $stmt = mysqli_prepare($dbhandle, 
        "INSERT INTO assessment (studyhours, hobbyhours, sleephours, socialhours, activehours, gwa, userId) 
         VALUES (?, ?, ?, ?, ?, ?, ?)
"
    );
    
    mysqli_stmt_bind_param($stmt, "ddddddi", $studyhours, $hobbyhours, $sleephours, $socialhours, $activehours, $gwa, $userId);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Assessment submitted successfully!');</script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($dbhandle) . "');</script>";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Invalid input. Please check your entries.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StressSense: Assessment</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="shortcut icon" href="images/stresssense_logo.png">
</head>
<body>
    <header>
        <div class="logo">
            <img src="images/stresssense_logo.png" alt="Logo"> STRESS SENSE
        </div>
        <nav>
            <a href="Home.php">HOME</a>
            <a href="Assessment.php" class="active">ASSESSMENT</a>
            <a href="History.php">HISTORY</a>
            <a href="Tips And Resources.php">TIPS AND RESOURCES</a>
            <a href="Settings.php">SETTINGS</a>
        </nav>
    </header>

    <div class="assessment-content">

    <div class="assessment-form">
        <form action="Assessment.php" method="post">
            <h2>Assessment Form</h2>
            <p>Note that each question is based on hours spent per day</p>

            <div class="assessment">
                <label for="studyhours">Study Hours</label>
                <input type="number" id="studyhours" name="studyhours" step="0.1" min="0" max="24.0" required>
            </div>

            <div class="assessment">
                <label for="hobbyhours">Hobby Hours</label>
                <input type="number" id="hobbyhours" name="hobbyhours" step="0.1" min="0" max="24.0" required>
            </div>

            <div class="assessment">
                <label for="sleephours">Sleep Hours</label>
                <input type="number" id="sleephours" name="sleephours" step="0.1" min="0" max="24.0" required>
            </div>

            <div class="assessment">
                <label for="socialhours">Social Hours</label>
                <input type="number" id="socialhours" name="socialhours" step="0.1" min="0" max="24.0" required>
            </div>

            <div class="assessment">
                <label for="activehours">Active Hours</label>
                <input type="number" id="activehours" name="activehours" step="0.1" min="0" max="24.0" required>
            </div>

            <div class="assessment">
                <label for="gwa">GWA</label>
                <input type="number" id="gwa" name="gwa" step="0.1" min="1.0" max="5.0" required>
            </div>

            <button type="submit" name="submit" id="submit-assessment">SUBMIT</button>
        </form>
    </div>
    </div>

    <footer>
        &copy; 2025 StressSense. All Rights Reserved. |
        <a href="About Us.php">About Us</a> | <a href="Privacy Policy.php">Privacy Policy</a> | <a href="Terms Of Service.php">Terms of Service</a> | <a href="Contact.php">Contact Us</a>
    </footer>
</body>
</html>
