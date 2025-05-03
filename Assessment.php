<?php
include 'session.php';
include 'connection.php';

if (isset($_POST['submit'])) {
    // Input validation
    $fields = [
        'studyhours' => FILTER_VALIDATE_FLOAT,
        'hobbyhours' => FILTER_VALIDATE_FLOAT,
        'sleephours' => FILTER_VALIDATE_FLOAT,
        'socialhours' => FILTER_VALIDATE_FLOAT,
        'activehours' => FILTER_VALIDATE_FLOAT,
        'gwa' => FILTER_VALIDATE_FLOAT
    ];
    
    $inputs = filter_input_array(INPUT_POST, $fields);
    $valid = true;
    
    foreach ($inputs as $field => $value) {
        if ($value === false || ($field !== 'gwa' && ($value < 0 || $value > 24)) || ($field === 'gwa' && ($value < 1.0 || $value > 5.0))) {
            $valid = false;
            break;
        }
    }

    if (!$valid) {
        echo "<script>alert('Invalid input. Please check values.');</script>";
    } else {
        // API Call
        $apiData = json_encode([
            'studyhours' => (float)$inputs['studyhours'],
            'hobbyhours' => (float)$inputs['hobbyhours'],
            'sleephours' => (float)$inputs['sleephours'],
            'socialhours' => (float)$inputs['socialhours'],
            'activehours' => (float)$inputs['activehours'],
            'gwa' => (float)$inputs['gwa']
        ]);

        $ch = curl_init('http://127.0.0.1:8000/predict_stress');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => $apiData,
            CURLOPT_TIMEOUT => 10
        ]);

        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status === 200) {
            $prediction = json_decode($response, true);
            $stmt = mysqli_prepare($dbhandle, 
                "INSERT INTO assessment 
                (studyhours, hobbyhours, sleephours, socialhours, activehours, gwa, userId, stress_level, confidence) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );
            
            mysqli_stmt_bind_param($stmt, "ddddddiss", 
                $inputs['studyhours'],
                $inputs['hobbyhours'],
                $inputs['sleephours'],
                $inputs['socialhours'],
                $inputs['activehours'],
                $inputs['gwa'],
                $_SESSION['user_id'],
                $prediction['stress_level'],
                $prediction['confidence']
            );

            if (mysqli_stmt_execute($stmt)) {
                $message = "Stress Level: {$prediction['stress_level']}, Confidence: " . round($prediction['confidence'] * 100, 2) . "%";
                echo "<script>alert('$message');</script>";
            } else {
                echo "<script>alert('Database error: " . mysqli_error($dbhandle) . "');</script>";
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "<script>alert('Prediction service unavailable. Try again later.');</script>";
        }
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
                <p>Note: All values are in hours per day except GWA.</p>

                <div class="assessment">
                    <label for="studyhours">Study Hours</label>
                    <input type="number" id="studyhours" name="studyhours" step="0.1" min="0" max="24" required>
                </div>

                <div class="assessment">
                    <label for="hobbyhours">Hobby Hours</label>
                    <input type="number" id="hobbyhours" name="hobbyhours" step="0.1" min="0" max="24" required>
                </div>

                <div class="assessment">
                    <label for="sleephours">Sleep Hours</label>
                    <input type="number" id="sleephours" name="sleephours" step="0.1" min="0" max="24" required>
                </div>

                <div class="assessment">
                    <label for="socialhours">Social Hours</label>
                    <input type="number" id="socialhours" name="socialhours" step="0.1" min="0" max="24" required>
                </div>

                <div class="assessment">
                    <label for="activehours">Active Hours</label>
                    <input type="number" id="activehours" name="activehours" step="0.1" min="0" max="24" required>
                </div>

                <div class="assessment">
                    <label for="gwa">GWA</label>
                    <input type="number" id="gwa" name="gwa" step="0.1" min="1.0" max="5.0" required>
                </div>

                <button type="submit" name="submit" class="submit-btn">SUBMIT</button>
            </form>
        </div>
    </div>

    <footer>
        © 2025 StressSense. All Rights Reserved. |
        <a href="About Us.php">About Us</a> | 
        <a href="Privacy Policy.php">Privacy Policy</a> | 
        <a href="Terms Of Service.php">Terms of Service</a> | 
        <a href="Contact.php">Contact Us</a>
    </footer>
</body>
</html>