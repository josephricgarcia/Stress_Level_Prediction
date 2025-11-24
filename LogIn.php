<?php
include 'connection.php';
session_start();

function verifyPasswordWithSalt(string $password, string $salt, string $hash): bool {
    $saltedPassword = $salt . $password;
    return password_verify($saltedPassword, $hash);
}

$error_message = '';
$success_message = isset($_GET['success']) ? $_GET['success'] : '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error_message = "Please fill in all fields.";
    } else {
        $stmt = mysqli_prepare($dbhandle, "SELECT id, username, password, salt, role FROM users WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);

            if (verifyPasswordWithSalt($password, $user['salt'], $user['password'])) {
                $login_time = date('Y-m-d H:i:s');
                $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
                $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';

                $log_stmt = mysqli_prepare(
                    $dbhandle,
                    "INSERT INTO user_log (username, date, time_in, ip_address, user_agent)
                     VALUES (?, CURDATE(), ?, ?, ?)"
                );
                mysqli_stmt_bind_param($log_stmt, "ssss", $username, $login_time, $ip, $user_agent);
                mysqli_stmt_execute($log_stmt);
                $_SESSION['log_id'] = mysqli_insert_id($dbhandle);

                $_SESSION['logged_in'] = true;
                $_SESSION['username'] = $username;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];

                if ($user['role'] === 'admin') {
                    header("Location: AdminDashboard.php");
                } else {
                    header("Location: Home.php");
                }
                exit;
            } else {
                $error_message = "Invalid password.";
            }
        } else {
            $error_message = "User not found.";
        }

        mysqli_stmt_close($stmt);
    }

    mysqli_close($dbhandle);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StressSense - Log In</title>
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
    
    .pulse {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(79, 70, 229, 0); }
        100% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0); }
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
    
    .input-focus:focus {
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        border-color: #6366f1;
    }
    
    /* Fix for auto-filled inputs */
    input:-webkit-autofill,
    input:-webkit-autofill:hover,
    input:-webkit-autofill:focus,
    input:-webkit-autofill:active {
        -webkit-text-fill-color: white !important;
        -webkit-box-shadow: 0 0 0px 1000px rgba(30, 41, 59, 0.6) inset !important;
        transition: background-color 5000s ease-in-out 0s;
        caret-color: white;
    }
</style>
</head>

<body class="min-h-screen flex flex-col">
    <!-- Animated Background Elements -->
    <div class="absolute top-10 left-10 w-20 h-20 rounded-full bg-purple-900 opacity-20 floating"></div>
    <div class="absolute top-1/4 right-10 w-16 h-16 rounded-full bg-indigo-900 opacity-30 floating" style="animation-delay: 0.5s;"></div>
    <div class="absolute bottom-1/4 left-20 w-24 h-24 rounded-full bg-violet-900 opacity-20 floating" style="animation-delay: 1s;"></div>
    <div class="absolute bottom-10 right-1/4 w-12 h-12 rounded-full bg-blue-900 opacity-30 floating" style="animation-delay: 1.5s;"></div>

    <header class="py-4 px-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="relative">
                <img src="images/stresssense_logo.png" class="w-12 h-12 z-10 relative" alt="Logo">
                <div class="absolute -inset-2 bg-indigo-500/30 rounded-full blur-sm"></div>
            </div>
            <span class="text-2xl font-bold tracking-wide text-white">STRESS SENSE</span>
        </div>
        <div class="text-indigo-200 text-sm">
            <i class="fas fa-headset mr-2"></i>Need help? <a href="Contact.php" class="underline hover:text-white transition">Contact us</a>
        </div>
    </header>

    <div class="flex-grow flex items-center justify-center px-4 py-8">
        <div class="glass-card p-10 rounded-3xl w-full max-w-md relative overflow-hidden">
            <!-- Decorative Elements -->
            <div class="absolute -top-10 -right-10 w-24 h-24 rounded-full bg-gradient-to-br from-indigo-600 to-purple-700 opacity-20"></div>
            <div class="absolute -bottom-8 -left-8 w-20 h-20 rounded-full bg-gradient-to-br from-violet-600 to-purple-700 opacity-20"></div>
            
            <div class="relative z-10">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-white mb-2">Welcome Back</h1>
                    <p class="text-indigo-200">A calmer, healthier you starts here.</p>
                </div>

                <form action="LogIn.php" method="post" class="space-y-6">
                    <div>
                        <label class="block text-white font-medium mb-2">
                            <i class="fas fa-user mr-2"></i>Username
                        </label>
                        <div class="relative">
                            <input 
                                type="text" 
                                id="username" 
                                name="username"
                                class="w-full px-4 py-3 pl-10 pr-4 border border-white/30 rounded-xl bg-white/10 text-white placeholder-indigo-200 input-focus focus:outline-none transition-all duration-300"
                                placeholder="Enter your username"
                                required
                            >
                            <i class="fas fa-user absolute left-3 top-1/2 transform -translate-y-1/2 text-indigo-300"></i>
                        </div>
                    </div>

                    <div>
                        <label class="block text-white font-medium mb-2">
                            <i class="fas fa-lock mr-2"></i>Password
                        </label>
                        <div class="relative">
                            <input 
                                type="password" 
                                id="password" 
                                name="password"
                                class="w-full px-4 py-3 pl-10 pr-12 border border-white/30 rounded-xl bg-white/10 text-white placeholder-indigo-200 input-focus focus:outline-none transition-all duration-300"
                                placeholder="Enter your password"
                                required
                            >
                            <i class="fas fa-lock absolute left-3 top-1/2 transform -translate-y-1/2 text-indigo-300"></i>
                            <button 
                                type="button" 
                                id="togglePassword"
                                class="absolute inset-y-0 right-4 flex items-center text-indigo-300 hover:text-white transition-colors"
                                tabindex="-1"
                            >
                                <i id="eyeIcon" class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center text-indigo-200">
                            <input type="checkbox" class="rounded bg-white/10 border-white/30 text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2">Remember me</span>
                        </label>
                        <a href="#" class="text-indigo-200 hover:text-white hover:underline transition">Forgot password?</a>
                    </div>

                    <button 
                        type="submit" 
                        name="submit"
                        class="w-full btn-gradient text-white py-3 rounded-xl text-lg font-semibold transition-all duration-300 pulse"
                    >
                        <i class="fas fa-sign-in-alt mr-2"></i>Log In
                    </button>

                    <p class="text-center text-indigo-200 mt-6">
                        Don't have an account?
                        <a href="SignUp.php" class="text-white font-semibold hover:underline ml-1 transition">
                            Create one
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </div>

    <footer class="py-4 text-center text-indigo-300 text-sm">
        <div class="container mx-auto px-4">
            &copy; 2025 StressSense. All Rights Reserved |
            <a href="About Us.php" class="hover:text-white mx-1 transition">About Us</a> |
            <a href="Privacy Policy.php" class="hover:text-white mx-1 transition">Privacy Policy</a> |
            <a href="Terms Of Service.php" class="hover:text-white mx-1 transition">Terms</a> |
            <a href="Contact.php" class="hover:text-white mx-1 transition">Contact</a>
        </div>
    </footer>

    <?php if (!empty($error_message)): ?>
        <div id="errorToast" class="fixed top-5 right-5 bg-red-900/90 text-red-100 p-4 rounded-lg shadow-lg z-50 transform transition-transform duration-300 translate-x-0 border border-red-700">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <span><?= htmlspecialchars($error_message); ?></span>
                <button class="ml-4 text-red-200 hover:text-white" onclick="closeToast('errorToast')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <script>
            setTimeout(() => {
                const toast = document.getElementById('errorToast');
                if (toast) {
                    toast.classList.add('translate-x-full');
                    setTimeout(() => toast.remove(), 300);
                }
            }, 5000);
            
            function closeToast(id) {
                const toast = document.getElementById(id);
                toast.classList.add('translate-x-full');
                setTimeout(() => toast.remove(), 300);
            }
        </script>
    <?php endif; ?>
    
    <?php if (!empty($success_message)): ?>
        <div id="successToast" class="fixed top-5 right-5 bg-green-900/90 text-green-100 p-4 rounded-lg shadow-lg z-50 transform transition-transform duration-300 translate-x-0 border border-green-700">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span><?= htmlspecialchars($success_message); ?></span>
                <button class="ml-4 text-green-200 hover:text-white" onclick="closeToast('successToast')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <script>
            setTimeout(() => {
                const toast = document.getElementById('successToast');
                if (toast) {
                    toast.classList.add('translate-x-full');
                    setTimeout(() => toast.remove(), 300);
                }
            }, 5000);
        </script>
    <?php endif; ?>

    <script>
        const passwordInput = document.getElementById("password");
        const togglePassword = document.getElementById("togglePassword");
        const eyeIcon = document.getElementById("eyeIcon");

        togglePassword.addEventListener("click", () => {
            const isHidden = passwordInput.type === "password";
            passwordInput.type = isHidden ? "text" : "password";
            eyeIcon.classList.toggle("fa-eye");
            eyeIcon.classList.toggle("fa-eye-slash");
        });
    </script>

</body>
</html>