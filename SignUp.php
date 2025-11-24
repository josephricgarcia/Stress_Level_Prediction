<?php
include 'connection.php';
session_start();

function hashPasswordWithSalt(string $password): array {
    $salt = bin2hex(random_bytes(16));
    $saltedPassword = $salt . $password;
    $hashedPassword = password_hash($saltedPassword, PASSWORD_ARGON2ID);
    if ($hashedPassword === false) {
        throw new Exception("Password hashing failed");
    }
    return ['hash' => $hashedPassword, 'salt' => $salt];
}

$error_message = '';
$lname = $fname = $mname = $gender = $birthday = $cno = $username = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lname = trim($_POST['lname'] ?? '');
    $fname = trim($_POST['fname'] ?? '');
    $mname = trim($_POST['mname'] ?? '');
    $gender = trim($_POST['gender'] ?? '');
    $birthday = trim($_POST['birthday'] ?? '');
    $cno = trim($_POST['cno'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');
    $role = 'user';

    if (empty($lname) || empty($fname) || empty($username) || empty($password)) {
        $error_message = "Please fill in all required fields.";
    } elseif ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        $stmt = mysqli_prepare($dbhandle, "SELECT username FROM users WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $error_message = "Username already exists.";
            mysqli_stmt_close($stmt);
        } else {
            mysqli_stmt_close($stmt);
            try {
                $hashedData = hashPasswordWithSalt($password);
                $hashedPassword = $hashedData['hash'];
                $salt = $hashedData['salt'];

                $stmt = mysqli_prepare($dbhandle,
                    "INSERT INTO users (lname, fname, mname, gender, birthday, cno, username, password, salt, role)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
                );
                mysqli_stmt_bind_param($stmt, "ssssssssss",
                    $lname, $fname, $mname, $gender, $birthday, $cno, $username, $hashedPassword, $salt, $role
                );

                if (mysqli_stmt_execute($stmt)) {
                    $_SESSION['logged_in'] = true;
                    $_SESSION['username'] = $username;
                    $_SESSION['user_id'] = mysqli_insert_id($dbhandle);
                    $_SESSION['role'] = $role;
                    header("Location: Home.php");
                    exit;
                } else {
                    $error_message = "Database error: " . mysqli_error($dbhandle);
                }

                mysqli_stmt_close($stmt);
            } catch (Exception $e) {
                $error_message = "Error: " . $e->getMessage();
            }
        }
    }

    mysqli_close($dbhandle);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StressSense - Create Account</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="shortcut icon" href="images/stresssense_logo.png">
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

        /* Make select & date match text fields when filled or focused */
        .input-unified {
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            transition: all 0.3s ease;
        }

        .input-unified option[disabled][value=""] {
            color: rgba(255, 255, 255, 0.7);
        }

        .input-unified:required:valid {
            background-color: rgba(255, 255, 255, 0.95);  /* white like other inputs */
            border-color: #ffffff;
            color: #111827; /* dark text for contrast */
        }

        /* Highlight on focus */
        .input-unified:focus {
            background-color: rgba(255, 255, 255, 0.95);
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        }

    </style>
</head>

<body class="flex flex-col min-h-screen">
    <!-- Animated Background Elements -->
    <div class="absolute top-10 left-10 w-20 h-20 rounded-full bg-purple-900 opacity-20 floating"></div>
    <div class="absolute top-1/4 right-10 w-16 h-16 rounded-full bg-indigo-900 opacity-30 floating" style="animation-delay: 0.5s;"></div>
    <div class="absolute bottom-1/4 left-20 w-24 h-24 rounded-full bg-violet-900 opacity-20 floating" style="animation-delay: 1s;"></div>
    <div class="absolute bottom-10 right-1/4 w-12 h-12 rounded-full bg-blue-900 opacity-30 floating" style="animation-delay: 1.5s;"></div>

    <header class="py-4 px-6 flex items-center gap-3">
        <div class="relative">
            <img src="images/stresssense_logo.png" class="w-12 h-12 z-10 relative" alt="Logo">
            <div class="absolute -inset-2 bg-indigo-500/30 rounded-full blur-sm"></div>
        </div>
        <span class="text-2xl font-bold tracking-wide text-white">STRESS SENSE</span>
    </header>

    <main class="flex-grow px-4 py-3 overflow-auto">
        <div class="max-w-3xl mx-auto">
            <div class="glass-card rounded-3xl overflow-hidden">
                <div class="p-6 border-b border-white/20">
                    <div class="flex items-center justify-center">
                        <h2 class="text-2xl font-bold text-white text-center">Create Your Account</h2>
                    </div>
                </div>

                <div class="p-6">
                    <p class="text-center text-indigo-200 mb-6">Join us and start your stress-management journey.</p>

                    <?php if (!empty($error_message)): ?>
                        <div id="errorToast" class="bg-red-900/80 text-red-100 p-4 rounded-xl mb-6 text-center border border-red-700">
                            <div class="flex items-center justify-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                <span><?= htmlspecialchars($error_message); ?></span>
                            </div>
                        </div>
                    <?php endif; ?>

                    <form action="SignUp.php" method="post" class="space-y-6">
                        <!-- Personal Information Section -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-white border-b border-white/20 pb-2">Personal Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="lname" class="block text-white font-medium mb-2">Last Name</label>
                                    <input type="text" id="lname" name="lname" placeholder="Last Name" 
                                           value="<?= htmlspecialchars($lname); ?>" required
                                           class="w-full px-4 py-3 border border-white/30 rounded-xl bg-white/10 text-white placeholder-indigo-200 input-focus focus:outline-none transition-all duration-300">
                                </div>
                                
                                <div>
                                    <label for="fname" class="block text-white font-medium mb-2">First Name</label>
                                    <input type="text" id="fname" name="fname" placeholder="First Name" 
                                           value="<?= htmlspecialchars($fname); ?>" required
                                           class="w-full px-4 py-3 border border-white/30 rounded-xl bg-white/10 text-white placeholder-indigo-200 input-focus focus:outline-none transition-all duration-300">
                                </div>
                                
                                <div>
                                    <label for="mname" class="block text-white font-medium mb-2">Middle Name</label>
                                    <input type="text" id="mname" name="mname" placeholder="Middle Name" 
                                           value="<?= htmlspecialchars($mname); ?>"
                                           class="w-full px-4 py-3 border border-white/30 rounded-xl bg-white/10 text-white placeholder-indigo-200 input-focus focus:outline-none transition-all duration-300">
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="gender" class="block text-white font-medium mb-2">Gender</label>
                                <select id="gender" name="gender" required
                                    class="input-unified w-full px-4 py-3 border rounded-xl 
                                        bg-white/10 text-white input-focus focus:outline-none
                                        transition-all duration-300 [color-scheme:dark]">
                                    <option value="" disabled <?= empty($gender) ? 'selected' : ''; ?>>Select Gender</option>
                                    <option value="m" <?= $gender == 'm' ? 'selected' : ''; ?>>Male</option>
                                    <option value="f" <?= $gender == 'f' ? 'selected' : ''; ?>>Female</option>
                                    <option value="x" <?= $gender == 'x' ? 'selected' : ''; ?>>Prefer not to say</option>
                                </select>
                            </div>
               
                            <div>
                                <label for="birthday" class="block text-white font-medium mb-2">Birthday</label>
                                <input type="date" id="birthday" name="birthday" 
                                    value="<?= htmlspecialchars($birthday); ?>" required
                                    class="input-unified w-full px-4 py-3 border border-white/30 rounded-xl 
                                            bg-white/10 text-white placeholder-indigo-200 input-focus 
                                            focus:outline-none transition-all duration-300">
                            </div>

                                
                                <div>
                                    <label for="cno" class="block text-white font-medium mb-2">Contact Number</label>
                                    <input type="text" id="cno" name="cno" placeholder="Contact Number" 
                                           value="<?= htmlspecialchars($cno); ?>" required
                                           class="w-full px-4 py-3 border border-white/30 rounded-xl bg-white/10 text-white placeholder-indigo-200 input-focus focus:outline-none transition-all duration-300">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Account Information Section -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-white border-b border-white/20 pb-2">Account Information</h3>
                            
                            <div>
                                <label for="username" class="block text-white font-medium mb-2">Username</label>
                                <input type="text" id="username" name="username" placeholder="Username" 
                                       value="<?= htmlspecialchars($username); ?>" required
                                       class="w-full px-4 py-3 border border-white/30 rounded-xl bg-white/10 text-white placeholder-indigo-200 input-focus focus:outline-none transition-all duration-300">
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="relative">
                                    <label for="password" class="block text-white font-medium mb-2">Password</label>
                                    <input type="password" id="password" name="password" placeholder="Password" required
                                           class="w-full px-4 py-3 pr-12 border border-white/30 rounded-xl bg-white/10 text-white placeholder-indigo-200 input-focus focus:outline-none transition-all duration-300">
                                    <button type="button" id="togglePassword" class="absolute right-4 bottom-3 text-indigo-300 hover:text-white transition">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                
                                <div class="relative">
                                    <label for="confirm_password" class="block text-white font-medium mb-2">Confirm Password</label>
                                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required
                                           class="w-full px-4 py-3 pr-12 border border-white/30 rounded-xl bg-white/10 text-white placeholder-indigo-200 input-focus focus:outline-none transition-all duration-300">
                                    <button type="button" id="toggleConfirmPassword" class="absolute right-4 bottom-3 text-indigo-300 hover:text-white transition">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="flex flex-col sm:flex-row gap-4 pt-4">
                            <button type="submit" 
                                    class="flex-1 btn-gradient text-white py-4 px-6 rounded-xl font-semibold transition-all duration-300 flex items-center justify-center gap-2">
                                <i class="fas fa-user-plus"></i>
                                Create Account
                            </button>
                            <a href="LogIn.php" 
                               class="flex-1 bg-white/10 hover:bg-white/20 text-white py-4 px-6 rounded-xl font-semibold transition-all duration-300 flex items-center justify-center gap-2 text-center border border-white/30">
                                <i class="fas fa-arrow-left"></i>
                                Back to Login
                            </a>
                        </div>
                        
                        <p class="text-center text-indigo-200 mt-6">
                            Already have an account?
                            <a href="LogIn.php" class="text-white font-semibold hover:underline ml-1 transition">
                                Log in here
                            </a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <footer class="py-4 text-center text-indigo-300 text-sm">
        <div class="container mx-auto px-4">
            &copy; 2025 StressSense. All Rights Reserved |
            <a href="AboutUs.php" class="hover:text-white mx-1 transition">About Us</a> |
            <a href="PrivacyPolicy.php" class="hover:text-white mx-1 transition">Privacy Policy</a> |
            <a href="TermsOfService.php" class="hover:text-white mx-1 transition">Terms</a> |
            <a href="Contact.php" class="hover:text-white mx-1 transition">Contact</a>
        </div>
    </footer>

    <script>
        // Password visibility toggle
        const passwordInput = document.getElementById("password");
        const confirmPasswordInput = document.getElementById("confirm_password");
        const togglePassword = document.getElementById("togglePassword");
        const toggleConfirmPassword = document.getElementById("toggleConfirmPassword");

        togglePassword.addEventListener("click", () => {
            const isHidden = passwordInput.type === "password";
            passwordInput.type = isHidden ? "text" : "password";
            togglePassword.innerHTML = isHidden ? '<i class="fas fa-eye-slash"></i>' : '<i class="fas fa-eye"></i>';
        });

        toggleConfirmPassword.addEventListener("click", () => {
            const isHidden = confirmPasswordInput.type === "password";
            confirmPasswordInput.type = isHidden ? "text" : "password";
            toggleConfirmPassword.innerHTML = isHidden ? '<i class="fas fa-eye-slash"></i>' : '<i class="fas fa-eye"></i>';
        });

        // Simple password confirmation validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match! Please check your entries.');
                document.getElementById('password').focus();
            }
        });
    </script>

</body>
</html>