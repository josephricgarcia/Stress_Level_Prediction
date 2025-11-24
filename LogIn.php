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
    <style>
        body {
            background: linear-gradient(135deg, #dbeafe, #f0f9ff);
        }
    </style>
</head>

<body class="min-h-screen flex flex-col">

    <header class="bg-white/70 backdrop-blur shadow-sm py-4 px-6 flex items-center gap-3 border-b">
        <img src="images/stresssense_logo.png" class="w-12 h-12" alt="Logo">
        <span class="text-2xl font-semibold tracking-wide text-gray-700">STRESS SENSE</span>
    </header>

    <div class="flex-grow flex items-center justify-center px-4">
        <div class="bg-white/90 backdrop-blur p-10 rounded-3xl shadow-2xl w-full max-w-md border border-blue-100/70">
            <h1 class="text-3xl font-bold text-center text-blue-700 mb-2">Welcome Back</h1>
            <p class="text-center text-gray-600 mb-8">A calmer, healthier you starts here.</p>

            <form action="LogIn.php" method="post" class="space-y-5">
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Username</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username"
                        class="w-full px-4 py-3 border rounded-xl bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                        required
                    >
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1">Password</label>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="password" 
                            name="password"
                            class="w-full px-4 py-3 pr-12 border rounded-xl bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                            required
                        >
                        <button 
                            type="button" 
                            id="togglePassword"
                            class="absolute inset-y-0 right-4 flex items-center text-gray-500 hover:text-gray-700"
                            tabindex="-1"
                        >
                            <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" 
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7s-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg id="eyeClose" xmlns="http://www.w3.org/2000/svg" 
                                 class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.964 9.964 0 012.792-4.442M9.88 9.88a3 3 0 104.24 4.24M4.22 4.22l15.56 15.56" />
                            </svg>
                        </button>
                    </div>
                </div>

                <button 
                    type="submit" 
                    name="submit"
                    class="w-full bg-blue-600 text-white py-3 rounded-xl text-lg font-semibold 
                           hover:bg-blue-700 hover:shadow-lg transition-all duration-200"
                >
                    Log In
                </button>

                <p class="text-center text-gray-600 mt-4">
                    Don't have an account?
                    <a href="SignUp.php" class="text-blue-600 font-semibold hover:underline">
                        Create one
                    </a>
                </p>
            </form>
        </div>
    </div>

    <footer class="bg-white/80 backdrop-blur py-4 text-center text-gray-600 text-sm border-t">
        &copy; 2025 StressSense. All Rights Reserved |
        <a href="About Us.php" class="hover:underline">About Us</a> |
        <a href="Privacy Policy.php" class="hover:underline">Privacy Policy</a> |
        <a href="Terms Of Service.php" class="hover:underline">Terms</a> |
        <a href="Contact.php" class="hover:underline">Contact</a>
    </footer>

    <?php if (!empty($error_message)): ?>
        <script>alert("<?= htmlspecialchars($error_message); ?>");</script>
    <?php endif; ?>
    <?php if (!empty($success_message)): ?>
        <script>alert("<?= htmlspecialchars($success_message); ?>");</script>
    <?php endif; ?>

    <script>
        const passwordInput = document.getElementById("password");
        const togglePassword = document.getElementById("togglePassword");
        const eyeOpen = document.getElementById("eyeOpen");
        const eyeClose = document.getElementById("eyeClose");

        togglePassword.addEventListener("click", () => {
            const isHidden = passwordInput.type === "password";
            passwordInput.type = isHidden ? "text" : "password";
            eyeOpen.classList.toggle("hidden");
            eyeClose.classList.toggle("hidden");
        });
    </script>

</body>
</html>
