<?php
include 'session.php';
include 'connection.php';

$user_id = $_SESSION['user_id'];

// Fetch user data
$query = "SELECT lname, fname, mname, gender, birthday, cno, username FROM users WHERE id = ?";
$stmt = mysqli_prepare($dbhandle, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Fetch actual assessment count
$countQuery = "SELECT COUNT(*) as assessment_count FROM assessment WHERE userId = ?";
$stmtCount = mysqli_prepare($dbhandle, $countQuery);
mysqli_stmt_bind_param($stmtCount, "i", $user_id);
mysqli_stmt_execute($stmtCount);
$countResult = mysqli_stmt_get_result($stmtCount);
$countData = mysqli_fetch_assoc($countResult);
$assessmentCount = $countData['assessment_count'];
mysqli_stmt_close($stmtCount);

$full_name = trim($user['fname'] . ' ' . ($user['mname'] ? $user['mname'] . ' ' : '') . $user['lname']);
$initial = strtoupper(substr($full_name, 0, 1));  // First letter only

$gender_display = match($user['gender']) {
    'm' => 'Male',
    'f' => 'Female',
    'x' => 'Prefer not to say',
    default => 'Not specified'
};

$birthday_display = $user['birthday']
    ? (new DateTime($user['birthday']))->format('F j, Y')
    : 'Not provided';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StressSense - Settings</title>
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
        
        .info-card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        
        .info-card:hover {
            background: rgba(30, 41, 59, 0.9);
            transform: translateY(-5px);
        }
        
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
            <a href="Assessment.php" class="text-indigo-200 hover:text-white text-sm transition flex items-center">
                <i class="fas fa-clipboard-list nav-icon"></i>ASSESSMENT
            </a>
            <a href="History.php" class="text-indigo-200 hover:text-white text-sm transition flex items-center">
                <i class="fas fa-history nav-icon"></i>HISTORY
            </a>
            <a href="Tips And Resources.php" class="text-indigo-200 hover:text-white text-sm transition flex items-center">
                <i class="fas fa-lightbulb nav-icon"></i>TIPS & RESOURCES
            </a>
            <a href="Settings.php" class="text-white font-semibold text-sm hover:text-indigo-200 transition flex items-center">
                <i class="fas fa-cog nav-icon"></i>SETTINGS
            </a>
        </nav>
        
        <div class="flex items-center gap-4">
            <span class="text-indigo-200 text-sm hidden md:block">Welcome, <?= htmlspecialchars($_SESSION['username'] ?? 'User') ?></span>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="flex-1 flex items-start justify-center px-4 py-6">
        <div class="w-full max-w-4xl">
            <h1 class="text-center text-3xl font-bold text-white mb-6">Account Settings</h1>

            <div class="glass-card rounded-3xl overflow-hidden">
                <div class="flex flex-col md:flex-row">
                    <!-- LEFT: Profile Section -->
                    <div class="bg-gradient-to-br from-blue-900/80 to-purple-900/80 text-white p-8 text-center md:w-80 flex flex-col items-center justify-center">
                        <!-- First Letter Avatar -->
                        <div class="w-24 h-24 rounded-full bg-white/10 backdrop-blur-sm flex items-center justify-center text-5xl font-bold shadow-lg border-4 border-white/20">
                            <?= $initial ?>
                        </div>
                        <h2 class="mt-6 text-xl font-bold"><?= htmlspecialchars($full_name) ?></h2>
                        <p class="text-blue-200 text-md mt-2">@<?= htmlspecialchars($user['username']) ?></p>
                    </div>

                    <!-- RIGHT: User Info Cards -->
                    <div class="flex-1 p-8">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <!-- Full Name -->
                            <div class="info-card rounded-2xl p-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-blue-500/30 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-user text-blue-300 text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-white/70 text-sm">Full Name</p>
                                        <p class="font-semibold text-white text-lg"><?= htmlspecialchars($full_name) ?></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Gender -->
                            <div class="info-card rounded-2xl p-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-purple-500/30 rounded-xl flex items-center justify-center">
                                        <?php if ($user['gender'] === 'm'): ?>
                                            <i class="fas fa-mars text-purple-300 text-xl"></i>
                                        <?php elseif ($user['gender'] === 'f'): ?>
                                            <i class="fas fa-venus text-purple-300 text-xl"></i>
                                        <?php elseif ($user['gender'] === 'x'): ?>
                                            <i class="fas fa-transgender text-purple-300 text-xl"></i>
                                        <?php else: ?>
                                            <i class="fas fa-user text-purple-300 text-xl"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <p class="text-white/70 text-sm">Gender</p>
                                        <p class="font-semibold text-white text-lg"><?= htmlspecialchars($gender_display) ?></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Birthday -->
                            <div class="info-card rounded-2xl p-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-green-500/30 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-birthday-cake text-green-300 text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-white/70 text-sm">Birthday</p>
                                        <p class="font-semibold text-white text-lg"><?= $birthday_display ?></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Number -->
                            <div class="info-card rounded-2xl p-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-indigo-500/30 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-phone text-indigo-300 text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-white/70 text-sm">Contact Number</p>
                                        <p class="font-semibold text-white text-lg"><?= htmlspecialchars($user['cno']) ?></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Username -->
                            <div class="info-card rounded-2xl p-5 sm:col-span-2">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-amber-500/30 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-at text-amber-300 text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-white/70 text-sm">Username</p>
                                        <p class="font-semibold text-white text-lg">@<?= htmlspecialchars($user['username']) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-10 flex flex-col sm:flex-row gap-5 justify-center">
                            <form method="POST" action="update_form.php">
                                <input type="hidden" name="update_id" value="<?= $user_id ?>">
                                <button type="submit" class="btn-gradient text-white font-medium py-3 px-8 rounded-xl transition-all duration-300 flex items-center gap-3 text-lg">
                                    <i class="fas fa-edit"></i>
                                    Edit Account
                                </button>
                            </form>

                            <form method="POST" action="logout.php">
                                <button type="submit" onclick="return confirm('Are you sure you want to log out?');"
                                        class="bg-red-600 hover:bg-red-700 text-white font-medium py-3 px-8 rounded-xl transition-all duration-300 flex items-center gap-3 text-lg shadow-lg">
                                    <i class="fas fa-sign-out-alt"></i>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="py-4 text-center text-white/70 text-sm">
        <div class="container mx-auto px-4">
            © 2025 StressSense. All Rights Reserved |
            <a href="About Us.php" class="hover:underline mx-1">About Us</a> |
            <a href="Privacy Policy.php" class="hover:underline mx-1">Privacy Policy</a> |
            <a href="Terms Of Service.php" class="hover:underline mx-1">Terms</a> |
            <a href="Contact.php" class="hover:underline mx-1">Contact</a>
        </div>
    </footer>

</body>
</html>

<?php mysqli_close($dbhandle); ?>