<?php
include 'session.php';
include 'connection.php';

if (!$dbhandle) {
    die("Database connection failed: " . htmlspecialchars(mysqli_connect_error()));
}

function hashPasswordWithSalt(string $password): array {
    $salt = bin2hex(random_bytes(16));
    $saltedPassword = $salt . $password;
    $hashedPassword = password_hash($saltedPassword, PASSWORD_ARGON2ID);
    if ($hashedPassword === false) {
        throw new Exception("Password hashing failed");
    }
    return ['hash' => $hashedPassword, 'salt' => $salt];
}

$user = [
    'id' => '',
    'lname' => '',
    'fname' => '',
    'mname' => '',
    'gender' => '', 
    'birthday' => '',
    'cno' => '',
    'username' => ''
];

$error_message = '';

// Check if the user ID is stored in the session
if (isset($_SESSION['user_id']) && is_numeric($_SESSION['user_id'])) {
    $id = (int)$_SESSION['user_id'];
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $dbhandle->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    }
    $stmt->close();
} else {
    echo "<script>
            alert('No user session found. Please log in.');
            window.location.href = 'login.php';
          </script>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_user'])) {
    $id = (int)$_SESSION['user_id'];
    $lname = trim($_POST['lname'] ?? '');
    $fname = trim($_POST['fname'] ?? '');
    $mname = trim($_POST['mname'] ?? '');
    $gender = trim($_POST['gender'] ?? 'm');
    $birthday = trim($_POST['birthday'] ?? '');
    $contact_no = trim($_POST['cno'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');
    
    // Basic validation
    if (empty($lname) || empty($fname) || empty($username)) {
        $error_message = "Please fill in all required fields.";
    } elseif (!empty($password) && $password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        // Check if username is already taken by another user
        $check_sql = "SELECT id FROM users WHERE username = ? AND id != ?";
        $check_stmt = $dbhandle->prepare($check_sql);
        $check_stmt->bind_param("si", $username, $id);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            $error_message = "Username already exists.";
            $check_stmt->close();
        } else {
            $check_stmt->close();
            
            try {
                if (!empty($password)) {
                    // Update with new password
                    $hashedData = hashPasswordWithSalt($password);
                    $hashedPassword = $hashedData['hash'];
                    $salt = $hashedData['salt'];
                    
                    $sql = "UPDATE users SET 
                            lname = ?, 
                            fname = ?, 
                            mname = ?, 
                            gender = ?, 
                            birthday = ?, 
                            cno = ?,
                            username = ?,
                            password = ?,
                            salt = ?
                            WHERE id = ?";
                    
                    $stmt = $dbhandle->prepare($sql);
                    $stmt->bind_param("sssssssssi", $lname, $fname, $mname, $gender, $birthday, $contact_no, $username, $hashedPassword, $salt, $id);
                } else {
                    // Update without changing password
                    $sql = "UPDATE users SET 
                            lname = ?, 
                            fname = ?, 
                            mname = ?, 
                            gender = ?, 
                            birthday = ?, 
                            cno = ?,
                            username = ?
                            WHERE id = ?";
                    
                    $stmt = $dbhandle->prepare($sql);
                    $stmt->bind_param("sssssssi", $lname, $fname, $mname, $gender, $birthday, $contact_no, $username, $id);
                }
                
                if ($stmt->execute()) {
                    // Update session username if changed
                    $_SESSION['username'] = $username;
                    echo "<script>
                            alert('Profile updated successfully!');
                            window.location.href = 'Settings.php';
                          </script>";
                    exit;
                } else {
                    $error_message = "Update failed: " . $dbhandle->error;
                }
                $stmt->close();
            } catch (Exception $e) {
                $error_message = "Error: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StressSense - Update Profile</title>
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
        
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
            border-color: #6366f1;
        }
        
        .input-unified {
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            transition: all 0.3s ease;
        }
        
        .input-unified:required:valid {
            background-color: rgba(255, 255, 255, 0.95);
            border-color: #ffffff;
            color: #111827;
        }
        
        .input-unified:focus {
            background-color: rgba(255, 255, 255, 0.95);
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
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
                <div class="absolute -inset-2 bg-indigo-500/30 rounded-full blur-sm"></div>
            </div>
            <span class="text-2xl font-bold tracking-wide text-white">STRESS SENSE</span>
        </div>

        <nav class="hidden md:flex space-x-6">
            <a href="Home.php" class="text-white/80 hover:text-white text-sm transition flex items-center nav-link">
                <i class="fas fa-home mr-2"></i>HOME
            </a>
            <a href="Assessment.php" class="text-white/80 hover:text-white text-sm transition flex items-center nav-link">
                <i class="fas fa-clipboard-list mr-2"></i>ASSESSMENT
            </a>
            <a href="History.php" class="text-white/80 hover:text-white text-sm transition flex items-center nav-link">
                <i class="fas fa-history mr-2"></i>HISTORY
            </a>
            <a href="Tips And Resources.php" class="text-white/80 hover:text-white text-sm transition flex items-center nav-link">
                <i class="fas fa-lightbulb mr-2"></i>TIPS & RESOURCES
            </a>
            <a href="Settings.php" class="text-white/80 hover:text-white text-sm transition flex items-center nav-link">
                <i class="fas fa-cog mr-2"></i>SETTINGS
            </a>
        </nav>
        
        <div class="flex items-center gap-4">
            <span class="text-white/80 text-sm hidden md:block">Welcome, <?= htmlspecialchars($_SESSION['username'] ?? 'User') ?></span>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="flex-grow flex items-center justify-center px-4 py-6">
        <div class="glass-card p-8 rounded-3xl w-full max-w-2xl relative overflow-hidden">
            <!-- Decorative Elements -->
            <div class="absolute -top-10 -right-10 w-24 h-24 rounded-full bg-indigo-600/30 opacity-40"></div>
            <div class="absolute -bottom-8 -left-8 w-20 h-20 rounded-full bg-purple-600/30 opacity-40"></div>
            
            <div class="relative z-10">
                <h1 class="text-3xl font-bold text-center text-white mb-2">Update Profile</h1>
                <p class="text-center text-white/80 mb-6">Update your personal and account information</p>

                <?php if (!empty($error_message)): ?>
                    <div id="errorToast" class="bg-red-900/80 text-red-100 p-4 rounded-xl mb-6 text-center border border-red-700">
                        <div class="flex items-center justify-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <span><?= htmlspecialchars($error_message); ?></span>
                        </div>
                    </div>
                <?php endif; ?>

                <form method="POST" action="update_form.php" class="space-y-6">
                    <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                    
                    <!-- Personal Information Section -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-white border-b border-white/20 pb-2">Personal Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="fname" class="block text-white font-medium mb-2">
                                    <i class="fas fa-user mr-2"></i>First Name
                                </label>
                                <input 
                                    type="text" 
                                    id="fname" 
                                    name="fname" 
                                    value="<?php echo htmlspecialchars($user['fname']); ?>" 
                                    class="w-full px-4 py-3 border border-white/20 rounded-xl bg-white/10 text-white placeholder-white/50 input-focus focus:outline-none transition-all duration-300 backdrop-blur"
                                    required
                                    placeholder="First Name"
                                >
                            </div>
                            
                            <div>
                                <label for="lname" class="block text-white font-medium mb-2">
                                    <i class="fas fa-user mr-2"></i>Last Name
                                </label>
                                <input 
                                    type="text" 
                                    id="lname" 
                                    name="lname" 
                                    value="<?php echo htmlspecialchars($user['lname']); ?>" 
                                    class="w-full px-4 py-3 border border-white/20 rounded-xl bg-white/10 text-white placeholder-white/50 input-focus focus:outline-none transition-all duration-300 backdrop-blur"
                                    required
                                    placeholder="Last Name"
                                >
                            </div>
                            
                            <div>
                                <label for="mname" class="block text-white font-medium mb-2">
                                    <i class="fas fa-user mr-2"></i>Middle Name
                                </label>
                                <input 
                                    type="text" 
                                    id="mname" 
                                    name="mname" 
                                    value="<?php echo htmlspecialchars($user['mname']); ?>" 
                                    class="w-full px-4 py-3 border border-white/20 rounded-xl bg-white/10 text-white placeholder-white/50 input-focus focus:outline-none transition-all duration-300 backdrop-blur"
                                    placeholder="Middle Name"
                                >
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="gender" class="block text-white font-medium mb-2">
                                    <i class="fas fa-venus-mars mr-2"></i>Gender
                                </label>
                                <select 
                                    id="gender" 
                                    name="gender" 
                                    class="input-unified w-full px-4 py-3 border rounded-xl input-focus focus:outline-none transition-all duration-300 backdrop-blur"
                                    required
                                >
                                    <option value="m" <?php echo ($user['gender'] === 'm') ? 'selected' : ''; ?>>Male</option>
                                    <option value="f" <?php echo ($user['gender'] === 'f') ? 'selected' : ''; ?>>Female</option>
                                    <option value="x" <?php echo ($user['gender'] === 'x') ? 'selected' : ''; ?>>Prefer not to say</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="birthday" class="block text-white font-medium mb-2">
                                    <i class="fas fa-birthday-cake mr-2"></i>Birthday
                                </label>
                                <input 
                                    type="date" 
                                    id="birthday" 
                                    name="birthday" 
                                    value="<?php echo htmlspecialchars($user['birthday']); ?>" 
                                    class="input-unified w-full px-4 py-3 border border-white/20 rounded-xl input-focus focus:outline-none transition-all duration-300 backdrop-blur"
                                    required
                                >
                            </div>
                            
                            <div>
                                <label for="cno" class="block text-white font-medium mb-2">
                                    <i class="fas fa-phone mr-2"></i>Contact Number
                                </label>
                                <input 
                                    type="text" 
                                    id="cno" 
                                    name="cno" 
                                    value="<?php echo htmlspecialchars($user['cno']); ?>" 
                                    class="w-full px-4 py-3 border border-white/20 rounded-xl bg-white/10 text-white placeholder-white/50 input-focus focus:outline-none transition-all duration-300 backdrop-blur"
                                    required
                                    placeholder="Contact Number"
                                >
                            </div>
                        </div>
                    </div>
                    
                    <!-- Account Information Section -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-white border-b border-white/20 pb-2">Account Information</h3>
                        
                        <div>
                            <label for="username" class="block text-white font-medium mb-2">
                                <i class="fas fa-at mr-2"></i>Username
                            </label>
                            <input 
                                type="text" 
                                id="username" 
                                name="username" 
                                value="<?php echo htmlspecialchars($user['username']); ?>" 
                                class="w-full px-4 py-3 border border-white/20 rounded-xl bg-white/10 text-white placeholder-white/50 input-focus focus:outline-none transition-all duration-300 backdrop-blur"
                                required
                                placeholder="Username"
                            >
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="relative">
                                <label for="password" class="block text-white font-medium mb-2">
                                    <i class="fas fa-lock mr-2"></i>New Password
                                </label>
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    class="w-full px-4 py-3 pr-12 border border-white/20 rounded-xl bg-white/10 text-white placeholder-white/50 input-focus focus:outline-none transition-all duration-300 backdrop-blur"
                                    placeholder="Leave blank to keep current"
                                >
                                <button type="button" id="togglePassword" class="absolute right-4 bottom-3 text-indigo-300 hover:text-white transition">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            
                            <div class="relative">
                                <label for="confirm_password" class="block text-white font-medium mb-2">
                                    <i class="fas fa-lock mr-2"></i>Confirm Password
                                </label>
                                <input 
                                    type="password" 
                                    id="confirm_password" 
                                    name="confirm_password" 
                                    class="w-full px-4 py-3 pr-12 border border-white/20 rounded-xl bg-white/10 text-white placeholder-white/50 input-focus focus:outline-none transition-all duration-300 backdrop-blur"
                                    placeholder="Leave blank to keep current"
                                >
                                <button type="button" id="toggleConfirmPassword" class="absolute right-4 bottom-3 text-indigo-300 hover:text-white transition">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <p class="text-sm text-white/70">Leave password fields blank if you don't want to change your password.</p>
                    </div>
                    
                    <!-- Buttons -->
                    <div class="flex gap-4 pt-4">
                        <button 
                            type="submit" 
                            name="update_user" 
                            class="flex-1 btn-gradient text-white py-3 px-6 rounded-xl font-semibold transition-all duration-300 flex items-center justify-center gap-2"
                        >
                            <i class="fas fa-save"></i>
                            Update Profile
                        </button>
                        <button type="button" onclick="window.location.href='Settings.php'" class="flex-1 bg-red-600 hover:bg-red-700 text-white py-3 px-6 rounded-xl font-semibold transition-all duration-300 flex items-center justify-center gap-2 border border-red-700 focus:outline-none focus:ring-2 focus:ring-red-400">
                            <i class="fas fa-times"></i>
                            Cancel
                        </button>
                    </div>
                </form>
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

        // Password confirmation validation
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