<?php
include 'session.php';
include 'connection.php';

// Fetch the latest assessment for the user
$stmt = mysqli_prepare($dbhandle, 
    "SELECT stress_level, 
     FROM assessment 
     WHERE userId = ? 
     ORDER BY assessment_date DESC 
     LIMIT 1"
);
mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$assessment = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

$stress_level = $assessment['stress_level'] ?? 'N/A';
$confidence = isset($assessment['confidence']) ? round($assessment['confidence'] * 100, 2) : 0;

// Determine color based on stress level
$color = 'gray';
if ($stress_level === 'Low') {
    $color = 'green';
} elseif ($stress_level === 'Moderate') {
    $color = 'yellow';
} elseif ($stress_level === 'High') {
    $color = 'red';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StressSense: Result</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="shortcut icon" href="images/stresssense_logo.png">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .result-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 20px;
            max-width: 500px;
            margin: 20px auto;
            text-align: center;
        }
        .result-card h2 {
            margin-bottom: 20px;
        }
        .chart-container {
            max-width: 200px;
            margin: 0 auto 20px;
        }
        .button-group {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            color: white;
            font-weight: bold;
        }
        .btn-retake { background: #4CAF50; }
        .btn-history { background: #2196F3; }
        .btn-tips { background: #FF9800; }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="images/stresssense_logo.png" alt="Logo"> STRESS SENSE
        </div>
        <nav>
            <a href="Home.php">HOME</a>
            <a href="Assessment.php">ASSESSMENT</a>
            <a href="History.php">HISTORY</a>
            <a href="Tips And Resources.php">TIPS AND RESOURCES</a>
            <a href="Settings.php">SETTINGS</a>
        </nav>
    </header>

    <div class="result-content">
        <div class="result-card">
            <h2>Your Stress Assessment Result</h2>
            <p>Stress Level: <strong style="color: <?php echo $color; ?>"><?php echo $stress_level; ?></strong></p>
            <p>Confidence: <?php echo $confidence; ?>%</p>
            <div class="chart-container">
                <canvas id="stressChart"></canvas>
            </div>
            <div class="button-group">
                <a href="Assessment.php" class="btn btn-retake">Retake Assessment</a>
                <a href="History.php" class="btn btn-history">View History</a>
                <a href="Tips And Resources.php" class="btn btn-tips">Tips & Resources</a>
            </div>
        </div>
    </div>

    <footer>
        © 2025 StressSense. All Rights Reserved. |
        <a href="About Us.php">About Us</a> | 
        <a href="Privacy Policy.php">Privacy Policy</a> | 
        <a href="Terms Of Service.php">Terms of Service</a> | 
        <a href="Contact.php">Contact Us</a>
    </footer>

    <script>
        const ctx = document.getElementById('stressChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Stress Level', 'Remaining'],
                datasets: [{
                    data: [<?php echo $confidence; ?>, <?php echo 100 - $confidence; ?>],
                    backgroundColor: ['<?php echo $color; ?>', '#e0e0e0'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
</body>
</html>