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
    // Include the stress prediction model
    include 'stress_model.php';
    
    // Get prediction from PHP model
    $prediction = predictStress(
        (float)$inputs['studyhours'],
        (float)$inputs['hobbyhours'],
        (float)$inputs['sleephours'],
        (float)$inputs['socialhours'],
        (float)$inputs['activehours'],
        (float)$inputs['gwa']
    );

    if ($prediction && isset($prediction['stress_level'])) {
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
        $message = 'Prediction error. Please try again.';
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1e1b4b 0%, #3730a3 50%, #5b21b6 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
            color: #e5e7eb;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23000000' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
            z-index: -1;
        }
        
        .glass-card {
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.5);
        }
        
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        
        @keyframes floating {
            0% { transform: translate(0, 0px); }
            50% { transform: translate(0, 10px); }
            100% { transform: translate(0, -0px); }
        }
        
        .btn-gradient {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            transition: all 0.3s ease;
        }
        
        .btn-gradient:hover {
            background: linear-gradient(135deg, #4338ca 0%, #6d28d9 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
        }
        
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
            border-color: #6366f1;
        }
        
        .stress-low { background: linear-gradient(135deg, #065f46, #047857); }
        .stress-moderate { background: linear-gradient(135deg, #92400e, #b45309); }
        .stress-high { background: linear-gradient(135deg, #991b1b, #dc2626); }
        
        .nav-link {
            position: relative;
            transition: all 0.3s ease;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -5px;
            left: 0;
            background: linear-gradient(90deg, #6366f1, #8b5cf6);
            transition: width 0.3s ease;
        }
        
        .nav-link:hover::after {
            width: 100%;
        }
                .nav-icon {
            width: 16px;
            text-align: center;
            margin-right: 6px;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col">
    <!-- Animated Background Elements -->
    <div class="absolute top-10 left-10 w-20 h-20 rounded-full bg-purple-900 opacity-20 floating"></div>
    <div class="absolute top-1/4 right-10 w-16 h-16 rounded-full bg-indigo-900 opacity-30 floating" style="animation-delay: 0.5s;"></div>
    <div class="absolute bottom-1/4 left-20 w-24 h-24 rounded-full bg-violet-900 opacity-20 floating" style="animation-delay: 1s;"></div>
    <div class="absolute bottom-10 right-1/4 w-12 h-12 rounded-full bg-blue-900 opacity-30 floating" style="animation-delay: 1.5s;"></div>

    <!-- HEADER -->
    <header class="py-4 px-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="relative">
                <img src="images/stresssense_logo.png" class="w-12 h-12 z-10 relative" alt="Logo">
                <div class="absolute -inset-2 bg-indigo-500/30 rounded-full blur-sm"></div>
            </div>
            <span class="text-2xl font-bold tracking-wide text-white">STRESS SENSE</span>
        </div>

        <nav class="hidden md:flex space-x-6">
            <a href="Home.php" class="text-indigo-200 hover:text-white text-sm transition flex items-center">
                <i class="fas fa-home nav-icon"></i>HOME
            </a>
            <a href="Assessment.php" class="text-white font-semibold text-sm hover:text-indigo-200 transition flex items-center">
                <i class="fas fa-clipboard-list nav-icon"></i>ASSESSMENT
            </a>
            <a href="History.php" class="text-indigo-200 hover:text-white text-sm transition flex items-center">
                <i class="fas fa-history nav-icon"></i>HISTORY
            </a>
            <a href="Tips And Resources.php" class="text-indigo-200 hover:text-white text-sm transition flex items-center">
                <i class="fas fa-lightbulb nav-icon"></i>TIPS & RESOURCES
            </a>
            <a href="Settings.php" class="text-indigo-200 hover:text-white text-sm transition flex items-center">
                <i class="fas fa-cog nav-icon"></i>SETTINGS
            </a>
        </nav>
        
        <div class="flex items-center gap-4">
            <span class="text-indigo-200 text-sm hidden md:block">Welcome, <?= htmlspecialchars($_SESSION['username'] ?? 'User') ?></span>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="flex-grow flex items-center justify-center px-4 py-6">
        <div class="glass-card p-8 rounded-3xl w-full max-w-2xl relative overflow-hidden">
            <!-- Decorative Elements -->
            <div class="absolute -top-10 -right-10 w-24 h-24 rounded-full bg-indigo-600/30 opacity-40"></div>
            <div class="absolute -bottom-8 -left-8 w-20 h-20 rounded-full bg-purple-600/30 opacity-40"></div>
            
            <div class="relative z-10">
                <?php if ($showResult && $prediction): ?>
                    <!-- RESULT CARD -->
                    <div class="text-center">
                        <h1 class="text-3xl font-bold text-white mb-2">Your Stress Assessment</h1>
                        <div class="mb-6 p-4 rounded-xl <?php 
                            if ($prediction['stress_level'] === 'Low') echo 'stress-low';
                            elseif ($prediction['stress_level'] === 'Moderate') echo 'stress-moderate';
                            else echo 'stress-high';
                        ?> text-white">
                            <p class="text-xl font-bold"><?= htmlspecialchars($prediction['stress_level']) ?> Stress</p>
                            <p class="text-lg">Confidence: <?= round($prediction['confidence'] * 100, 2) ?>%</p>
                        </div>

                        <div class="max-w-xs mx-auto mb-8">
                            <canvas id="stressChart"></canvas>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <button onclick="location.href='Assessment.php'" 
                                    class="bg-orange-600 hover:bg-orange-700 text-white py-3 px-4 rounded-xl font-medium transition shadow-lg flex items-center justify-center gap-2">
                                <i class="fas fa-redo"></i> Retake
                            </button>
                            <a href="History.php" 
                               class="bg-teal-600 hover:bg-teal-700 text-white py-3 px-4 rounded-xl font-medium transition shadow-lg flex items-center justify-center gap-2">
                                <i class="fas fa-history"></i> History
                            </a>
                            <a href="Tips And Resources.php" 
                               class="bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-xl font-medium transition shadow-lg flex items-center justify-center gap-2">
                                <i class="fas fa-lightbulb"></i> Tips
                            </a>
                        </div>
                    </div>

                    <script>
                        const ctx = document.getElementById('stressChart').getContext('2d');
                        const level = '<?= $prediction['stress_level'] ?>';
                        let data, colors, label, percent;

                        if (level === 'Low') {
                            data = [33, 67];
                            colors = ['#10b981', 'rgba(255,255,255,0.1)'];
                            label = 'Low Stress';
                            percent = '33%';
                        } else if (level === 'Moderate') {
                            data = [66, 34];
                            colors = ['#f59e0b', 'rgba(255,255,255,0.1)'];
                            label = 'Moderate Stress';
                            percent = '66%';
                        } else {
                            data = [100, 0];
                            colors = ['#ef4444', 'rgba(255,255,255,0.1)'];
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
                                    ctx.font = 'bold 1.5rem Poppins';
                                    ctx.fillStyle = 'white';
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
                    <h1 class="text-3xl font-bold text-center text-white mb-2">Stress Assessment</h1>
                    <p class="text-center text-white/80 mb-6">Enter your daily average values (hours) and current GWA.</p>

                    <?php if ($message): ?>
                        <div id="errorToast" class="bg-red-600/80 text-white p-4 rounded-xl mb-6 text-center backdrop-blur">
                            <div class="flex items-center justify-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                <span><?= htmlspecialchars($message) ?></span>
                            </div>
                        </div>
                    <?php endif; ?>

                    <form action="Assessment.php" method="post" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-white font-medium mb-2">
                                    <i class="fas fa-book mr-2"></i>Study Hours
                                </label>
                                <input type="number" step="0.1" min="0" max="24" name="studyhours" required
                                       class="w-full px-4 py-3 border border-white/20 rounded-xl bg-white/10 text-white placeholder-white/50 input-focus focus:outline-none transition-all duration-300 backdrop-blur"
                                       placeholder="0-24 hours">
                            </div>
                            <div>
                                <label class="block text-white font-medium mb-2">
                                    <i class="fas fa-gamepad mr-2"></i>Hobby Hours
                                </label>
                                <input type="number" step="0.1" min="0" max="24" name="hobbyhours" required
                                       class="w-full px-4 py-3 border border-white/20 rounded-xl bg-white/10 text-white placeholder-white/50 input-focus focus:outline-none transition-all duration-300 backdrop-blur"
                                       placeholder="0-24 hours">
                            </div>
                            <div>
                                <label class="block text-white font-medium mb-2">
                                    <i class="fas fa-bed mr-2"></i>Sleep Hours
                                </label>
                                <input type="number" step="0.1" min="0" max="24" name="sleephours" required
                                       class="w-full px-4 py-3 border border-white/20 rounded-xl bg-white/10 text-white placeholder-white/50 input-focus focus:outline-none transition-all duration-300 backdrop-blur"
                                       placeholder="0-24 hours">
                            </div>
                            <div>
                                <label class="block text-white font-medium mb-2">
                                    <i class="fas fa-users mr-2"></i>Social Hours
                                </label>
                                <input type="number" step="0.1" min="0" max="24" name="socialhours" required
                                       class="w-full px-4 py-3 border border-white/20 rounded-xl bg-white/10 text-white placeholder-white/50 input-focus focus:outline-none transition-all duration-300 backdrop-blur"
                                       placeholder="0-24 hours">
                            </div>
                            <div>
                                <label class="block text-white font-medium mb-2">
                                    <i class="fas fa-running mr-2"></i>Active Hours
                                </label>
                                <input type="number" step="0.1" min="0" max="24" name="activehours" required
                                       class="w-full px-4 py-3 border border-white/20 rounded-xl bg-white/10 text-white placeholder-white/50 input-focus focus:outline-none transition-all duration-300 backdrop-blur"
                                       placeholder="0-24 hours">
                            </div>
                            <div>
                                <label class="block text-white font-medium mb-2">
                                    <i class="fas fa-graduation-cap mr-2"></i>Current GWA
                                </label>
                                <input type="number" step="0.01" min="1.0" max="5.0" name="gwa" required
                                       class="w-full px-4 py-3 border border-white/20 rounded-xl bg-white/10 text-white placeholder-white/50 input-focus focus:outline-none transition-all duration-300 backdrop-blur"
                                       placeholder="1.0-5.0">
                            </div>
                        </div>

                        <div class="text-center mt-8">
                            <button type="submit" name="submit"
                                    class="btn-gradient text-white py-4 px-10 rounded-xl font-semibold text-lg transition-all duration-300 shadow-lg">
                                <i class="fas fa-paper-plane mr-2"></i>SUBMIT ASSESSMENT
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="py-4 text-center text-white/70 text-sm">
        <div class="container mx-auto px-4">
            &copy; 2025 StressSense. All Rights Reserved |
            <a href="About Us.php" class="hover:underline mx-1">About Us</a> |
            <a href="Privacy Policy.php" class="hover:underline mx-1">Privacy Policy</a> |
            <a href="Terms Of Service.php" class="hover:underline mx-1">Terms</a> |
            <a href="Contact.php" class="hover:underline mx-1">Contact</a>
        </div>
    </footer>

</body>
</html>