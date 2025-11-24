<?php
include 'session.php';
include 'connection.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (!$dbhandle) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Get user statistics (using safe queries that don't rely on created_at)
$total_users_query = "SELECT COUNT(*) as total FROM users";
$total_users_result = mysqli_query($dbhandle, $total_users_query);
$total_users = mysqli_fetch_assoc($total_users_result)['total'];

// Get recent users (fallback to total users if we can't determine recency)
$recent_users = 0;
try {
    // Check if created_at column exists
    $check_column_query = "SHOW COLUMNS FROM users LIKE 'created_at'";
    $column_result = mysqli_query($dbhandle, $check_column_query);
    if (mysqli_num_rows($column_result) > 0) {
        $recent_users_query = "SELECT COUNT(*) as recent FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
        $recent_users_result = mysqli_query($dbhandle, $recent_users_query);
        $recent_users = mysqli_fetch_assoc($recent_users_result)['recent'];
    } else {
        // If no created_at column, use a safe fallback
        $recent_users_query = "SELECT COUNT(*) as recent FROM users";
        $recent_users_result = mysqli_query($dbhandle, $recent_users_query);
        $recent_users = mysqli_fetch_assoc($recent_users_result)['recent'];
    }
} catch (Exception $e) {
    // If any error occurs, default to total users
    $recent_users = $total_users;
}

$total_assessments_query = "SELECT COUNT(*) as total FROM assessment";
$total_assessments_result = mysqli_query($dbhandle, $total_assessments_query);
if ($total_assessments_result) {
    $total_assessments = mysqli_fetch_assoc($total_assessments_result)['total'];
} else {
    $total_assessments = 0;
}

// Get users data
$query = "SELECT * FROM users ORDER BY id DESC";
$result = mysqli_query($dbhandle, $query);
if (!$result) {
    die("Error fetching users: " . mysqli_error($dbhandle));
}
$users = mysqli_fetch_all($result, MYSQLI_ASSOC);

$error_message = $_SESSION['error_message'] ?? null;
$success_message = $_SESSION['success_message'] ?? null;
unset($_SESSION['error_message'], $_SESSION['success_message']); 

if ($error_message) {
    echo "<script>alert('" . addslashes($error_message) . "');</script>";
}
if ($success_message) {
    echo "<script>alert('" . addslashes($success_message) . "');</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StressSense - Admin Dashboard</title>
    <link rel="shortcut icon" href="images/stresssense_logo.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #dbeafe, #f0f9ff);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col">

<!-- HEADER -->
<header class="bg-white/70 backdrop-blur shadow-sm py-3 px-6 flex items-center justify-between border-b">
    <div class="flex items-center gap-2">
        <img src="images/stresssense_logo.png" class="w-10 h-10" alt="Logo">
        <span class="text-xl font-semibold tracking-wide text-gray-700">STRESS SENSE</span>
        <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full ml-2">Admin</span>
    </div>
    <nav class="flex items-center gap-4">
        <span class="text-sm text-gray-600">Welcome, <?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></span>
        <a href="logout.php" onclick="return confirm('Are you sure you want to log out?');"
           class="bg-red-600 hover:bg-red-700 text-white text-sm py-2 px-4 rounded-lg transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            Logout
        </a>
    </nav>
</header>

<!-- MAIN CONTENT -->
<main class="flex-grow px-4 py-6">
    <div class="max-w-7xl mx-auto">
        <!-- Dashboard Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white/90 backdrop-blur rounded-xl shadow-lg border border-blue-100/70 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Users</p>
                        <p class="text-3xl font-bold text-blue-700"><?= $total_users ?></p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white/90 backdrop-blur rounded-xl shadow-lg border border-green-100/70 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Active Users</p>
                        <p class="text-3xl font-bold text-green-700"><?= $recent_users ?></p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white/90 backdrop-blur rounded-xl shadow-lg border border-purple-100/70 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Assessments</p>
                        <p class="text-3xl font-bold text-purple-700"><?= $total_assessments ?></p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="bg-white/90 backdrop-blur rounded-xl shadow-lg border border-blue-100/70 overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <h2 class="text-2xl font-bold text-blue-700 mb-4 md:mb-0">User Management</h2>
                    <a href="create_user_form.php" 
                       class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition flex items-center gap-2 text-sm w-fit">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Add New User
                    </a>
                </div>
            </div>

            <?php if (empty($users)): ?>
                <div class="p-8 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                    </div>
                    <p class="text-gray-600 mb-4">No users found in the system.</p>
                    <a href="create_user_form.php" 
                       class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-lg transition inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Add First User
                    </a>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Birthday</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($users as $user): ?>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-700 font-semibold text-sm mr-3">
                                                <?= strtoupper(substr($user['fname'] ?? 'U', 0, 1)) ?>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    <?= htmlspecialchars(($user['fname'] ?? '') . ' ' . ($user['lname'] ?? '')) ?>
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    @<?= htmlspecialchars($user['username'] ?? 'N/A') ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900"><?= htmlspecialchars($user['cno'] ?? 'N/A') ?></div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            <?= ($user['gender'] ?? '') === 'm' ? 'bg-blue-100 text-blue-800' : 
                                               (($user['gender'] ?? '') === 'f' ? 'bg-pink-100 text-pink-800' : 'bg-gray-100 text-gray-800') ?>">
                                            <?= match($user['gender'] ?? '') {
                                                'm' => 'Male',
                                                'f' => 'Female', 
                                                'x' => 'Not Specified',
                                                default => 'N/A'
                                            } ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= htmlspecialchars($user['birthday'] ?? 'N/A') ?>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            <?= ($user['role'] ?? '') === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800' ?>">
                                            <?= ucfirst(htmlspecialchars($user['role'] ?? 'user')) ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="edit_user_form.php?id=<?= $user['id'] ?>" 
                                               class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded-lg transition flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                                Edit
                                            </a>
                                            <form method="POST" action="process_user.php" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')" class="inline">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1 rounded-lg transition flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<!-- FOOTER -->
    <footer class="bg-white/80 backdrop-blur py-4 text-center text-gray-600 text-sm border-t">
        &copy; 2025 StressSense. All Rights Reserved |
        <a href="About Us.php" class="hover:underline">About Us</a> |
        <a href="PrivacyPolicy.php" class="hover:underline">Privacy Policy</a> |
        <a href="TermsOfService.php" class="hover:underline">Terms</a> |
        <a href="Contact.php" class="hover:underline">Contact</a>
    </footer>

</body>
</html>

<?php
if ($dbhandle) {
    mysqli_close($dbhandle);
}
?>