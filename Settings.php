<?php
include 'session.php';
include 'connection.php';

$user_id = $_SESSION['user_id'];
$query = "SELECT lname, fname, mname, gender, birthday, cno, username FROM users WHERE id = ?";
$stmt = mysqli_prepare($dbhandle, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

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
    <style>
        body { background: linear-gradient(135deg, #dbeafe, #f0f9ff); }
    </style>
</head>
<body class="min-h-screen flex flex-col">

<!-- HEADER -->
<header class="bg-white/80 backdrop-blur-sm shadow-sm py-3 px-6 flex items-center justify-between border-b">
    <div class="flex items-center gap-2">
        <img src="images/stresssense_logo.png" class="w-10 h-10" alt="Logo">
        <span class="text-xl font-semibold text-gray-700">STRESS SENSE</span>
    </div>
    <nav class="space-x-4 text-sm">
        <a href="Home.php" class="text-gray-700 hover:text-blue-700">HOME</a>
        <a href="Assessment.php" class="text-gray-700 hover:text-blue-700">ASSESSMENT</a>
        <a href="History.php" class="text-gray-700 hover:text-blue-700">HISTORY</a>
        <a href="Tips And Resources.php" class="text-gray-700 hover:text-blue-700">TIPS & RESOURCES</a>
        <a href="Settings.php" class="text-blue-700 font-semibold">SETTINGS</a>
    </nav>
</header>

<!-- MAIN CONTENT -->
<main class="flex-1 flex items-start justify-center px-4 py-6">
    <div class="w-full max-w-4xl">
        <h1 class="text-center text-2xl font-bold text-blue-700 mb-6">Account Settings</h1>

        <div class="bg-white rounded-xl shadow-lg border border-blue-100 overflow-hidden">
            <div class="flex flex-col md:flex-row">
                <!-- LEFT: Profile Section -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-700 text-white p-6 text-center md:w-64 flex flex-col items-center justify-center">
                    <!-- First Letter Avatar -->
                    <div class="w-20 h-20 rounded-full bg-white/25 backdrop-blur-sm flex items-center justify-center text-4xl font-bold shadow-lg border-4 border-white/30">
                        <?= $initial ?>
                    </div>
                    <h2 class="mt-4 text-lg font-bold"><?= htmlspecialchars($full_name) ?></h2>
                    <p class="text-blue-100 text-sm mt-1">@<?= htmlspecialchars($user['username']) ?></p>
                </div>

                <!-- RIGHT: User Info Cards -->
                <div class="flex-1 p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Full Name -->
                        <div class="flex items-center gap-3 bg-blue-50 rounded-lg p-4 hover:bg-blue-100/50 transition">
                            <div class="w-10 h-10 bg-blue-200 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600">Full Name</p>
                                <p class="font-semibold text-gray-800 text-sm"><?= htmlspecialchars($full_name) ?></p>
                            </div>
                        </div>

                        <!-- Gender -->
                        <div class="flex items-center gap-3 bg-purple-50 rounded-lg p-4 hover:bg-purple-100/50 transition">
                            <div class="w-10 h-10 bg-purple-200 rounded-lg flex items-center justify-center text-purple-700">
                                <?php if ($user['gender'] === 'm'): ?>
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <circle cx="9" cy="15" r="4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M15 9l6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M21 9v-6h-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                <?php elseif ($user['gender'] === 'f'): ?>
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <circle cx="12" cy="7" r="4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M12 11v6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M9 18h6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                <?php elseif ($user['gender'] === 'x'): ?>
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <circle cx="12" cy="8" r="3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M5 21v-1a6 6 0 0114 0v1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                <?php else: ?>
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path d="M12 2a7 7 0 100 14 7 7 0 000-14z" stroke-width="2"/>
                                        <path d="M12 16v1" stroke-width="2"/>
                                        <path d="M12 19v.01" stroke-width="2"/>
                                    </svg>
                                <?php endif; ?>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600">Gender</p>
                                <p class="font-semibold text-gray-800 text-sm"><?= htmlspecialchars($gender_display) ?></p>
                            </div>
                        </div>

                        <!-- Birthday -->
                        <div class="flex items-center gap-3 bg-green-50 rounded-lg p-4 hover:bg-green-100/50 transition">
                            <div class="w-10 h-10 bg-green-200 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600">Birthday</p>
                                <p class="font-semibold text-gray-800 text-sm"><?= $birthday_display ?></p>
                            </div>
                        </div>

                        <!-- Contact Number -->
                        <div class="flex items-center gap-3 bg-indigo-50 rounded-lg p-4 hover:bg-indigo-100/50 transition">
                            <div class="w-10 h-10 bg-indigo-200 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600">Contact Number</p>
                                <p class="font-semibold text-gray-800 text-sm"><?= htmlspecialchars($user['cno']) ?></p>
                            </div>
                        </div>

                        <!-- Username -->
                        <div class="flex items-center gap-3 bg-amber-50 rounded-lg p-4 hover:bg-amber-100/50 transition sm:col-span-2">
                            <div class="w-10 h-10 bg-amber-200 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600">Username</p>
                                <p class="font-semibold text-gray-800 text-sm">@<?= htmlspecialchars($user['username']) ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
                        <form method="POST" action="update_form.php">
                            <input type="hidden" name="update_id" value="<?= $user_id ?>">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-8 rounded-lg shadow transition flex items-center gap-2 text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit Account
                            </button>
                        </form>

                        <form method="POST" action="logout.php">
                            <button type="submit" onclick="return confirm('Are you sure you want to log out?');"
                                    class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-8 rounded-lg shadow transition flex items-center gap-2 text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
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
<footer class="bg-white/80 backdrop-blur py-3 text-center text-xs text-gray-600 border-t">
    © 2025 StressSense. All Rights Reserved |
    <a href="About Us.php" class="hover:underline">About Us</a> |
    <a href="Privacy Policy.php" class="hover:underline">Privacy Policy</a> |
    <a href="Terms Of Service.php" class="hover:underline">Terms</a> |
    <a href="Contact.php" class="hover:underline">Contact</a>
</footer>

</body>
</html>

<?php mysqli_close($dbhandle); ?>