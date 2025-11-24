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
        
        .btn-primary {
            background: rgba(59, 130, 246, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: rgba(37, 99, 235, 0.9);
            transform: translateY(-1px);
        }
        
        .btn-danger {
            background: rgba(239, 68, 68, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }
        
        .btn-danger:hover {
            background: rgba(220, 38, 38, 0.9);
            transform: translateY(-1px);
        }
        
        .btn-secondary {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-1px);
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
                <div class="absolute -inset-2 bg-white/10 rounded-full blur-sm"></div>
            </div>
            <span class="text-2xl font-bold tracking-wide text-white">STRESS SENSE</span>
            <span class="bg-white/10 backdrop-blur text-white text-xs px-3 py-1 rounded-full">Admin</span>
        </div>
        
        <div class="flex items-center gap-4">
            <span class="text-white/80 text-sm hidden md:block">Welcome, <?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></span>
            <a href="logout.php" onclick="return confirm('Are you sure you want to log out?');"
               class="btn-secondary text-white text-sm py-2 px-4 rounded-xl transition flex items-center gap-2">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </a>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="flex-grow px-4 py-6">
        <div class="max-w-7xl mx-auto">
            <!-- Dashboard Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="glass-card p-6 rounded-2xl relative overflow-hidden">
                    <div class="absolute -top-6 -right-6 w-16 h-16 rounded-full bg-gradient-to-br from-blue-900 to-purple-900 opacity-20"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/80 text-sm">Total Users</p>
                                <p class="text-3xl font-bold text-white"><?= $total_users ?></p>
                            </div>
                            <div class="w-12 h-12 bg-white/10 rounded-full flex items-center justify-center backdrop-blur">
                                <i class="fas fa-users text-white text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="glass-card p-6 rounded-2xl relative overflow-hidden">
                    <div class="absolute -top-6 -right-6 w-16 h-16 rounded-full bg-gradient-to-br from-green-900 to-teal-900 opacity-20"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/80 text-sm">Active Users</p>
                                <p class="text-3xl font-bold text-white"><?= $recent_users ?></p>
                            </div>
                            <div class="w-12 h-12 bg-white/10 rounded-full flex items-center justify-center backdrop-blur">
                                <i class="fas fa-user-check text-white text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="glass-card p-6 rounded-2xl relative overflow-hidden">
                    <div class="absolute -top-6 -right-6 w-16 h-16 rounded-full bg-gradient-to-br from-purple-900 to-pink-900 opacity-20"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/80 text-sm">Total Assessments</p>
                                <p class="text-3xl font-bold text-white"><?= $total_assessments ?></p>
                            </div>
                            <div class="w-12 h-12 bg-white/10 rounded-full flex items-center justify-center backdrop-blur">
                                <i class="fas fa-chart-bar text-white text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Users Table -->
            <div class="glass-card rounded-2xl overflow-hidden relative">
                <!-- Decorative Elements -->
                <div class="absolute -top-10 -right-10 w-24 h-24 rounded-full bg-gradient-to-br from-indigo-900 to-purple-900 opacity-20"></div>
                <div class="absolute -bottom-8 -left-8 w-20 h-20 rounded-full bg-gradient-to-br from-pink-900 to-purple-900 opacity-20"></div>
                
                <div class="p-6 border-b border-white/10 relative z-10">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <h2 class="text-2xl font-bold text-white mb-4 md:mb-0">User Management</h2>
                        <a href="create_user_form.php" 
                           class="btn-gradient text-white py-3 px-6 rounded-xl transition flex items-center gap-2 text-sm w-fit">
                            <i class="fas fa-plus"></i>
                            Add New User
                        </a>
                    </div>
                </div>

                <?php if (empty($users)): ?>
                    <div class="p-8 text-center relative z-10">
                        <div class="w-16 h-16 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-4 backdrop-blur">
                            <i class="fas fa-users text-white text-2xl"></i>
                        </div>
                        <p class="text-white/80 mb-4">No users found in the system.</p>
                        <a href="create_user_form.php" 
                           class="btn-gradient text-white py-3 px-6 rounded-xl transition inline-flex items-center gap-2">
                            <i class="fas fa-plus"></i>
                            Add First User
                        </a>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto relative z-10">
                        <table class="w-full">
                            <thead class="bg-white/10 backdrop-blur">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">User</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Contact</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Gender</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Birthday</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Role</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/10">
                                <?php foreach ($users as $user): ?>
                                    <tr class="hover:bg-white/5 transition">
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 bg-white/10 rounded-full flex items-center justify-center text-white font-semibold text-sm mr-3 backdrop-blur">
                                                    <?= strtoupper(substr($user['fname'] ?? 'U', 0, 1)) ?>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-white">
                                                        <?= htmlspecialchars(($user['fname'] ?? '') . ' ' . ($user['lname'] ?? '')) ?>
                                                    </div>
                                                    <div class="text-sm text-white/70">
                                                        @<?= htmlspecialchars($user['username'] ?? 'N/A') ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="text-sm text-white"><?= htmlspecialchars($user['cno'] ?? 'N/A') ?></div>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full backdrop-blur
                                                <?= ($user['gender'] ?? '') === 'm' ? 'bg-blue-500/30 text-blue-200' : 
                                                   (($user['gender'] ?? '') === 'f' ? 'bg-pink-500/30 text-pink-200' : 'bg-gray-500/30 text-gray-200') ?>">
                                                <?= match($user['gender'] ?? '') {
                                                    'm' => 'Male',
                                                    'f' => 'Female', 
                                                    'x' => 'Not Specified',
                                                    default => 'N/A'
                                                } ?>
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-white/70">
                                            <?= htmlspecialchars($user['birthday'] ?? 'N/A') ?>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full backdrop-blur
                                                <?= ($user['role'] ?? '') === 'admin' ? 'bg-purple-500/30 text-purple-200' : 'bg-green-500/30 text-green-200' ?>">
                                                <?= ucfirst(htmlspecialchars($user['role'] ?? 'user')) ?>
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="edit_user_form.php?id=<?= $user['id'] ?>" 
                                                   class="btn-primary text-white px-4 py-2 rounded-lg transition flex items-center gap-1">
                                                    <i class="fas fa-edit text-xs"></i>
                                                    Edit
                                                </a>
                                                <form method="POST" action="process_user.php" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')" class="inline">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                                    <button type="submit" 
                                                            class="btn-danger text-white px-4 py-2 rounded-lg transition flex items-center gap-1">
                                                        <i class="fas fa-trash text-xs"></i>
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

<?php
if ($dbhandle) {
    mysqli_close($dbhandle);
}
?>