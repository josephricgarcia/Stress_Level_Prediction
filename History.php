<?php
include 'session.php';
include 'connection.php';

$userId = $_SESSION['user_id'];
$assessments = [];
$error = '';

// Build query safely – use created_at if it exists, otherwise fall back to id
$query = "SELECT * FROM assessment WHERE userId = ? ORDER BY id DESC";  // works 100% always

$stmt = mysqli_prepare($dbhandle, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($result)) {
        $assessments[] = $row;
    }
    mysqli_stmt_close($stmt);
} else {
    $error = 'Error fetching assessments. Please try again later.';
}

mysqli_close($dbhandle);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StressSense - Assessment History</title>
    <link rel="shortcut icon" href="images/stresssense_logo.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1e1b4b 0%, #3730a3 50%, #5b21b6 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
            color: #e2e8f0;
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
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
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
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.3);
        }
        
        .assessment-card {
            background: rgba(30, 41, 59, 0.6);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        
        .assessment-card:hover {
            background: rgba(30, 41, 59, 0.8);
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        
        .data-box {
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.1);
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
            <a href="Assessment.php" class="text-indigo-200 hover:text-white text-sm transition flex items-center">
                <i class="fas fa-clipboard-list nav-icon"></i>ASSESSMENT
            </a>
            <a href="History.php" class="text-white font-semibold text-sm hover:text-indigo-200 transition flex items-center">
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
    <main class="flex-grow px-4 pb-6 pt-4">
        <div class="glass-card rounded-3xl w-full mx-auto max-w-6xl p-6 md:p-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-white">Assessment History</h1>
                <p class="text-indigo-200 mt-2">Review your past stress assessments</p>
            </div>

            <?php if ($error): ?>
                <div class="bg-red-900/80 text-red-100 p-4 rounded-xl text-center mb-6 border border-red-700">
                    <div class="flex items-center justify-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span><?= htmlspecialchars($error) ?></span>
                    </div>
                </div>

            <?php elseif (empty($assessments)): ?>
                <div class="text-center py-12">
                    <div class="mx-auto w-24 h-24 mb-4 text-indigo-300">
                        <i class="fas fa-clipboard-list text-6xl"></i>
                    </div>
                    <p class="text-indigo-200 text-lg mb-6">You haven't taken any assessments yet.</p>
                    <a href="Assessment.php" class="btn-gradient text-white py-3 px-8 rounded-xl font-semibold text-lg inline-block">
                        <i class="fas fa-plus-circle mr-2"></i>Take Assessment Now
                    </a>
                </div>

            <?php else: ?>
                <div class="space-y-6">
                    <?php foreach ($assessments as $row): 
                        $level = $row['stress_level'] ?? 'Unknown';
                        $badgeClass = $level === 'Low' ? 'bg-gradient-to-r from-green-600 to-emerald-600' : 
                                     ($level === 'Moderate' ? 'bg-gradient-to-r from-amber-500 to-orange-500' : 
                                     'bg-gradient-to-r from-red-600 to-pink-600');
                        
                        // Fallback date: use created_at if exists, otherwise show nothing or today's date
                        $dateDisplay = 'Date not recorded';
                        if (!empty($row['created_at'])) {
                            $dateDisplay = date('M j, Y', strtotime($row['created_at']));
                        } elseif (!empty($row['date'])) {
                            $dateDisplay = date('M j, Y', strtotime($row['date']));
                        }
                    ?>
                        <div class="assessment-card rounded-2xl p-6">
                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                                <div class="flex-1">
                                    <div class="flex flex-wrap items-center gap-4 mb-6">
                                        <span class="text-indigo-200 font-medium">
                                            <i class="fas fa-calendar-alt mr-2"></i><?= htmlspecialchars($dateDisplay) ?>
                                        </span>
                                        <span class="px-4 py-2 rounded-full text-white font-bold <?= $badgeClass ?>">
                                            <?= strtoupper(htmlspecialchars($level)) ?> STRESS
                                        </span>
                                    </div>

                                    <div class="grid grid-cols-2 md:grid-cols-6 gap-4 text-center mb-6">
                                        <div class="data-box rounded-xl p-3">
                                            <p class="text-indigo-200 text-xs">Study</p>
                                            <p class="text-white font-bold text-lg"><?= number_format($row['studyhours'] ?? 0, 1) ?>h</p>
                                        </div>
                                        <div class="data-box rounded-xl p-3">
                                            <p class="text-indigo-200 text-xs">Hobby</p>
                                            <p class="text-white font-bold text-lg"><?= number_format($row['hobbyhours'] ?? 0, 1) ?>h</p>
                                        </div>
                                        <div class="data-box rounded-xl p-3">
                                            <p class="text-indigo-200 text-xs">Sleep</p>
                                            <p class="text-white font-bold text-lg"><?= number_format($row['sleephours'] ?? 0, 1) ?>h</p>
                                        </div>
                                        <div class="data-box rounded-xl p-3">
                                            <p class="text-indigo-200 text-xs">Social</p>
                                            <p class="text-white font-bold text-lg"><?= number_format($row['socialhours'] ?? 0, 1) ?>h</p>
                                        </div>
                                        <div class="data-box rounded-xl p-3">
                                            <p class="text-indigo-200 text-xs">Active</p>
                                            <p class="text-white font-bold text-lg"><?= number_format($row['activehours'] ?? 0, 1) ?>h</p>
                                        </div>
                                        <div class="data-box rounded-xl p-3">
                                            <p class="text-indigo-200 text-xs">GWA</p>
                                            <p class="text-white font-bold text-lg"><?= number_format($row['gwa'] ?? 0, 2) ?></p>
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <div class="flex justify-between text-white mb-2">
                                            <span class="font-medium">Confidence</span>
                                            <span class="font-bold"><?= round(($row['confidence'] ?? 0) * 100) ?>%</span>
                                        </div>
                                        <div class="w-full bg-slate-700 rounded-full h-3">
                                            <div class="bg-gradient-to-r from-blue-500 to-purple-500 h-3 rounded-full transition-all duration-1000 ease-out" 
                                                 style="width: <?= round(($row['confidence'] ?? 0) * 100) ?>%"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex gap-3 lg:flex-col lg:w-40">
                                    <a href="EditAssessment.php?id=<?= $row['id'] ?>"
                                       class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-3 rounded-xl font-medium transition shadow-lg text-center flex items-center justify-center gap-2">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <button type="button" onclick="confirmDelete(<?= $row['id'] ?>)"
                                            class="flex-1 bg-rose-700 hover:bg-rose-800 text-white px-4 py-3 rounded-xl font-medium transition shadow-lg flex items-center justify-center gap-2">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Delete Form (hidden) -->
                <form id="deleteForm" method="POST" action="DeleteAssessment.php" class="hidden">
                    <input type="hidden" name="assessment_id" id="deleteAssessmentId">
                </form>
            <?php endif; ?>
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="py-4 text-center text-indigo-300 text-sm">
        <div class="container mx-auto px-4">
            © 2025 StressSense. All Rights Reserved |
            <a href="About Us.php" class="hover:text-white mx-1 transition">About Us</a> |
            <a href="Privacy Policy.php" class="hover:text-white mx-1 transition">Privacy Policy</a> |
            <a href="Terms Of Service.php" class="hover:text-white mx-1 transition">Terms</a> |
            <a href="Contact.php" class="hover:text-white mx-1 transition">Contact</a>
        </div>
    </footer>

    <script>
        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this assessment?\nThis action cannot be undone.')) {
                document.getElementById('deleteAssessmentId').value = id;
                document.getElementById('deleteForm').submit();
            }
        }
    </script>

</body>
</html>