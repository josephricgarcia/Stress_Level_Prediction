<?php
include 'session.php';
include 'connection.php';

$showResult = false;
$prediction = null;
$message = '';

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
        $message = 'Invalid input. Please check values.';
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
                $showResult = true;
                $message = "Stress Level: {$prediction['stress_level']}, Confidence: " . round($prediction['confidence'] * 100, 2) . "%";
            } else {
                $message = 'Database error: ' . mysqli_error($dbhandle);
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .result-card {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
            background: #fff;
        }
        .result-card h3 {
            margin-top: 0;
            color: #333;
        }
        .chart-container {
            max-width: 300px;
            margin: 20px auto;
        }
        .button-group {
            margin-top: 20px;
        }
        .button-group a, .button-group button {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            text-decoration: none;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .button-group .retake-btn {
            background: #fd7e14; /* Orange for retake */
        }
        .button-group .retake-btn:hover {
            background: #e06c00;
        }
        .button-group .history-btn {
            background: #20c997; /* Teal for history */
        }
        .button-group .history-btn:hover {
            background: #1ba87e;
        }
        .button-group .tips-btn {
            background: #28a745; /* Green for tips */
        }
        .button-group .tips-btn:hover {
            background: #218838;
        }
        .error-message {
            color: red;
            text-align: center;
            margin: 20px;
        }
    </style>
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
        <?php if ($showResult && $prediction): ?>
            <div class="result-card">
                <h2>Assessment Result</h2>
                <p><?php echo $message; ?></p>
                <div class="chart-container">
                    <canvas id="stressChart"></canvas>
                </div>
                <div class="button-group">
                    <button class="retake-btn" onclick="window.location.href='Assessment.php'">Retake Assessment</button>
                    <a class="history-btn" href="History.php">View History</a>
                    <a class="tips-btn" href="Tips And Resources.php">Tips and Resources</a>
                </div>
            </div>
            <script>
                const ctx = document.getElementById('stressChart').getContext('2d');
                const stressLevel = '<?php echo $prediction['stress_level']; ?>';
                let data, color, percentage, label;

                if (stressLevel === 'Low') {
                    data = [33, 67]; // 33% filled, 67% transparent
                    color = ['#28a745', 'rgba(0,0,0,0)']; // Green and transparent
                    percentage = '33%';
                    label = 'Low Stress';
                } else if (stressLevel === 'Moderate') {
                    data = [66, 34]; // 66% filled, 34% transparent
                    color = ['#ffc107', 'rgba(0,0,0,0)']; // Yellow and transparent
                    percentage = '66%';
                    label = 'Moderate Stress';
                } else {
                    data = [99.99, 0.01]; // 99.99% filled, 0.01% transparent to fix seam
                    color = ['#dc3545', 'rgba(0,0,0,0)']; // Red and transparent
                    percentage = '100%';
                    label = 'High Stress';
                }

                new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: [label, ...(stressLevel !== 'High' ? [''] : [''])],
                        datasets: [{
                            data: data,
                            backgroundColor: color,
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        if (context.label === '') return '';
                                        return `${context.label}: ${percentage}`;
                                    }
                                }
                            }
                        }
                    }
                });
            </script>
        <?php elseif ($message): ?>
            <p class="error-message"><?php echo $message; ?></p>
            <div class="assessment-form">
                <form action="Assessment.php" method="post">
                    <h2>Assessment Form</h2>
                    <p>Note: All values are in hours per day except GWA.</p>

                    <div class="assessment">
                        <label for="studyhours">Study Hours</label>
                        <input type="number" id="studyhours" name="studyhours" step="0.1" min="0" max="24" value="<?php echo isset($inputs['studyhours']) ? $inputs['studyhours'] : ''; ?>" required>
                    </div>

                    <div class="assessment">
                        <label for="hobbyhours">Hobby Hours</label>
                        <input type="number" id="hobbyhours" name="hobbyhours" step="0.1" min="0" max="24" value="<?php echo isset($inputs['hobbyhours']) ? $inputs['hobbyhours'] : ''; ?>" required>
                    </div>

                    <div class="assessment">
                        <label for="sleephours">Sleep Hours</label>
                        <input type="number" id="sleephours" name="sleephours" step="0.1" min="0" max="24" value="<?php echo isset($inputs['sleephours']) ? $inputs['sleephours'] : ''; ?>" required>
                    </div>

                    <div class="assessment">
                        <label for="socialhours">Social Hours</label>
                        <input type="number" id="socialhours" name="socialhours" step="0.1" min="0" max="24" value="<?php echo isset($inputs['socialhours']) ? $inputs['socialhours'] : ''; ?>" required>
                    </div>

                    <div class="assessment">
                        <label for="activehours">Active Hours</label>
                        <input type="number" id="activehours" name="activehours" step="0.1" min="0" max="24" value="<?php echo isset($inputs['activehours']) ? $inputs['activehours'] : ''; ?>" required>
                    </div>

                    <div class="assessment">
                        <label for="gwa">GWA</label>
                        <input type="number" id="gwa" name="gwa" step="0.1" min="1.0" max="5.0" value="<?php echo isset($inputs['gwa']) ? $inputs['gwa'] : ''; ?>" required>
                    </div>

                    <button type="submit" name="submit" class="submit-btn">SUBMIT</button>
                </form>
            </div>
        <?php else: ?>
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
        <?php endif; ?>
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