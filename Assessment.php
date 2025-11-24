<?php
include 'session.php';
include 'connection.php';

$showResult = false;
$prediction = null;
$message = '';

if (isset($_POST['submit'])) {
    // Input validation
    $fields = [
        'studyhours'    => FILTER_VALIDATE_FLOAT,
        'hobbyhours'    => FILTER_VALIDATE_FLOAT,
        'sleephours'    => FILTER_VALIDATE_FLOAT,
        'socialhours'   => FILTER_VALIDATE_FLOAT,
        'activehours'   => FILTER_VALIDATE_FLOAT,
        'gwa'           => FILTER_VALIDATE_FLOAT
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
        $message = 'Invalid input. Please check your values.';
    } else {
        $apiData = json_encode([
            'studyhours'  => (float)$inputs['studyhours'],
            'hobbyhours'  => (float)$inputs['hobbyhours'],
            'sleephours'  => (float)$inputs['sleephours'],
            'socialhours' => (float)$inputs['socialhours'],
            'activehours' => (float)$inputs['activehours'],
            'gwa'         => (float)$inputs['gwa']
        ]);

        $ch = curl_init('http://127.0.0.1:8000/predict_stress');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS     => $apiData,
            CURLOPT_TIMEOUT        => 10
        ]);

        $response = curl_exec($ch);
        $status   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status === 200 && $response) {
            $prediction = json_decode($response, true);

            $stmt = mysqli_prepare($dbhandle,
                "INSERT INTO assessment 
                (studyhours, hobbyhours, sleephours, socialhours, activehours, gwa, userId, stress_level, confidence) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );
            mysqli_stmt_bind_param($stmt, "ddddddisd",
                $inputs['studyhours'], $inputs['hobbyhours'], $inputs['sleephours'],
                $inputs['socialhours'], $inputs['activehours'], $inputs['gwa'],
                $_SESSION['user_id'], $prediction['stress_level'], $prediction['confidence']
            );

            if (mysqli_stmt_execute($stmt)) {
                $showResult = true;
                $message = "Stress Level: {$prediction['stress_level']}, Confidence: " . round($prediction['confidence'] * 100, 2) . "%";
            } else {
                $message = 'Database error: ' . mysqli_error($dbhandle);
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "<script>alert('Prediction service unavailable. Please try again later.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StressSense - Assessment</title>
    <link rel="shortcut icon" href="images/stresssense_logo.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background: linear-gradient(135deg, #dbeafe, #f0f9ff); }
    </style>
</head>
<body class="min-h-screen flex flex-col">

<!-- HEADER -->
<header class="bg-white/70 backdrop-blur shadow-sm py-3 px-6 flex items-center justify-between border-b">
    <div class="flex items-center gap-2">
        <img src="images/stresssense_logo.png" class="w-10 h-10" alt="Logo">
        <span class="text-xl font-semibold tracking-wide text-gray-700">STRESS SENSE</span>
    </div>
    <nav class="space-x-4">
        <a href="Home.php" class="text-gray-700 hover:text-blue-700 text-sm">HOME</a>
        <a href="Assessment.php" class="text-blue-700 font-semibold text-sm">ASSESSMENT</a>
        <a href="History.php" class="text-gray-700 hover:text-blue-700 text-sm">HISTORY</a>
        <a href="Tips And Resources.php" class="text-gray-700 hover:text-blue-700 text-sm">TIPS & RESOURCES</a>
        <a href="Settings.php" class="text-gray-700 hover:text-blue-700 text-sm">SETTINGS</a>
    </nav>
</header>

<!-- MAIN CONTENT -->
<main class="flex-grow flex items-center justify-center px-4 py-6">
    <div class="bg-white/90 backdrop-blur p-6 rounded-xl shadow-lg w-full max-w-md border border-blue-100/70">

        <?php if ($showResult && $prediction): ?>
            <!-- RESULT CARD -->
            <div class="text-center">
                <h1 class="text-2xl font-bold text-blue-700 mb-4">Your Stress Assessment</h1>
                <p class="text-lg text-gray-700 mb-6"><?= htmlspecialchars($message) ?></p>

                <div class="max-w-xs mx-auto mb-6">
                    <canvas id="stressChart"></canvas>
                </div>

                <div class="flex flex-col gap-3">
                    <button onclick="location.href='Assessment.php'" 
                            class="bg-orange-500 hover:bg-orange-600 text-white py-2 px-6 rounded-lg font-medium transition text-sm">
                        Retake Assessment
                    </button>
                    <a href="History.php" 
                       class="bg-teal-500 hover:bg-teal-600 text-white py-2 px-6 rounded-lg font-medium transition text-sm">
                        View History
                    </a>
                    <a href="Tips And Resources.php" 
                       class="bg-green-600 hover:bg-green-700 text-white py-2 px-6 rounded-lg font-medium transition text-sm">
                        Tips & Resources
                    </a>
                </div>
            </div>

            <script>
                const ctx = document.getElementById('stressChart').getContext('2d');
                const level = '<?= $prediction['stress_level'] ?>';
                let data, colors, label, percent;

                if (level === 'Low') {
                    data = [33, 67];
                    colors = ['#28a745', '#e9ecef'];
                    label = 'Low Stress';
                    percent = '33%';
                } else if (level === 'Moderate') {
                    data = [66, 34];
                    colors = ['#ffc107', '#e9ecef'];
                    label = 'Moderate Stress';
                    percent = '66%';
                } else {
                    data = [100, 0];
                    colors = ['#dc3545', '#e9ecef'];
                    label = 'High Stress';
                    percent = '100%';
                }

                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: [label, ''],
                        datasets: [{ data, backgroundColor: colors, borderWidth: 0 }]
                    },
                    options: {
                        cutout: '75%',
                        plugins: {
                            legend: { display: false },
                            tooltip: { callbacks: { label: () => `${label}: ${percent}` } }
                        }
                    },
                    plugins: [{
                        afterDraw(chart) {
                            const ctx = chart.ctx;
                            ctx.save();
                            ctx.font = 'bold 1.5rem sans-serif';
                            ctx.fillStyle = '#1e40af';
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';
                            ctx.fillText(percent, chart.width / 2, chart.height / 2);
                            ctx.restore();
                        }
                    }]
                });
            </script>

        <?php else: ?>
            <!-- ASSESSMENT FORM -->
            <h1 class="text-2xl font-bold text-center text-blue-700 mb-3">Stress Assessment</h1>
            <p class="text-center text-gray-600 text-sm mb-6">Enter your daily average values (hours) and current GWA.</p>

            <?php if ($message): ?>
                <div class="bg-red-50 border border-red-200 text-red-700 px-3 py-2 rounded-lg mb-4 text-center text-sm">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <form action="Assessment.php" method="post" class="space-y-4">
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-1 text-sm">Study Hours / day</label>
                        <input type="number" step="0.1" min="0" max="24" name="studyhours" required
                               class="w-full px-3 py-2 border rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 transition text-sm">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1 text-sm">Hobby Hours / day</label>
                        <input type="number" step="0.1" min="0" max="24" name="hobbyhours" required
                               class="w-full px-3 py-2 border rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 transition text-sm">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1 text-sm">Sleep Hours / day</label>
                        <input type="number" step="0.1" min="0" max="24" name="sleephours" required
                               class="w-full px-3 py-2 border rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 transition text-sm">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1 text-sm">Social Hours / day</label>
                        <input type="number" step="0.1" min="0" max="24" name="socialhours" required
                               class="w-full px-3 py-2 border rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 transition text-sm">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1 text-sm">Active/Exercise Hours / day</label>
                        <input type="number" step="0.1" min="0" max="24" name="activehours" required
                               class="w-full px-3 py-2 border rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 transition text-sm">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1 text-sm">Current GWA</label>
                        <input type="number" step="0.01" min="1.0" max="5.0" name="gwa" required
                               class="w-full px-3 py-2 border rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 transition text-sm">
                    </div>
                </div>

                <div class="text-center mt-6">
                    <button type="submit" name="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white py-3 px-8 rounded-lg font-medium shadow transition-all duration-200 text-sm">
                        SUBMIT ASSESSMENT
                    </button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</main>

<!-- FOOTER -->
<footer class="bg-white/80 backdrop-blur py-3 text-center text-gray-600 text-xs border-t">
    &copy; 2025 StressSense. All Rights Reserved |
    <a href="About Us.php" class="hover:underline">About Us</a> |
    <a href="Privacy Policy.php" class="hover:underline">Privacy Policy</a> |
    <a href="Terms Of Service.php" class="hover:underline">Terms</a> |
    <a href="Contact.php" class="hover:underline">Contact</a>
</footer>

</body>
</html>