<?php
include 'connection.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: LogIn.php");
    exit();
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

$confirm_password = "";
$lname = $fname = $mname = $gender = $birthday = $contact_no = $username = $role = "";

if (!$dbhandle) {
    die("Database connection failed: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
    $lname = $_POST['lname'];
    $fname = $_POST['fname'];
    $mname = $_POST['mname'];
    $gender = $_POST['gender'];
    $birthday = $_POST['birthday'];
    $contact_no = $_POST['cno'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];

    // Validate role
    if (!in_array($role, ['user', 'admin'])) {
        echo "<script>alert('Invalid role selected!');</script>";
        $role = 'user'; // Default to 'user'
    }

    if ($password != $confirm_password) {
        echo "<script>alert('Passwords do not match!');</script>";
    } else {
        $check_sql = "SELECT username FROM users WHERE username = ?";
        if ($stmt_check = mysqli_prepare($dbhandle, $check_sql)) {
            mysqli_stmt_bind_param($stmt_check, "s", $username);
            mysqli_stmt_execute($stmt_check);
            mysqli_stmt_store_result($stmt_check);

            if (mysqli_stmt_num_rows($stmt_check) > 0) {
                echo "<script>alert('Username already exists! Please choose a different username.');</script>";
            } else {
                try {
                    $hashedData = hashPasswordWithSalt($password);
                    $hashed_password = $hashedData['hash'];
                    $salt = $hashedData['salt'];

                    $sql = "INSERT INTO users (lname, fname, mname, gender, birthday, cno, username, password, salt, role) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                    if ($stmt = mysqli_prepare($dbhandle, $sql)) {
                        mysqli_stmt_bind_param($stmt, "ssssssssss", $lname, $fname, $mname, $gender, $birthday, $contact_no, $username, $hashed_password, $salt, $role);

                        if (mysqli_stmt_execute($stmt)) {
                            echo "<script>alert('Account created successfully!');</script>";
                            header("Location: AdminDashboard.php");
                            exit();
                        } else {
                            echo "<script>alert('Account registration failed: " . mysqli_error($dbhandle) . "');</script>";
                        }
                        mysqli_stmt_close($stmt);
                    } else {
                        echo "<script>alert('SQL statement preparation failed: " . mysqli_error($dbhandle) . "');</script>";
                    }
                } catch (Exception $e) {
                    echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
                }
            }
            mysqli_stmt_close($stmt_check);
        } else {
            echo "<script>alert('SQL statement preparation failed: " . mysqli_error($dbhandle) . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User - StressSense Admin</title>
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
        
        .password-container {
            position: relative;
        }
        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: rgba(255, 255, 255, 0.7);
            z-index: 10;
        }
        .toggle-password:hover {
            color: white;
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

        input, select {
            background: rgba(30, 41, 59, 0.7) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: white !important;
        }

        input::placeholder {
            color: rgba(255, 255, 255, 0.6) !important;
        }

        input:focus, select:focus {
            background: rgba(30, 41, 59, 0.9) !important;
            border-color: #6366f1 !important;
        }

        select option {
            background: #1e293b;
            color: white;
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

        <nav class="hidden md:flex space-x-6">
            <a href="Home.php" class="nav-link text-white/80 hover:text-white text-sm transition">HOME</a>
            <a href="Assessment.php" class="nav-link text-white/80 hover:text-white text-sm transition">ASSESSMENT</a>
            <a href="History.php" class="nav-link text-white/80 hover:text-white text-sm transition">HISTORY</a>
            <a href="Tips And Resources.php" class="nav-link text-white/80 hover:text-white text-sm transition">TIPS & RESOURCES</a>
            <a href="Settings.php" class="nav-link text-white/80 hover:text-white text-sm transition">SETTINGS</a>
        </nav>
    </header>

    <!-- MAIN CONTENT -->
    <main class="flex-grow flex items-center justify-center px-4 py-6">
        <div class="glass-card p-8 rounded-3xl w-full max-w-2xl relative overflow-hidden">
            <!-- Decorative Elements -->
            <div class="absolute -top-10 -right-10 w-24 h-24 rounded-full bg-gradient-to-br from-indigo-900 to-purple-900 opacity-20"></div>
            <div class="absolute -bottom-8 -left-8 w-20 h-20 rounded-full bg-gradient-to-br from-pink-900 to-purple-900 opacity-20"></div>
            
            <div class="relative z-10">
                <h1 class="text-3xl font-bold text-center text-white mb-2">Create New User</h1>
                <p class="text-center text-white/80 mb-6">Add a new user to the StressSense system</p>

                <form action="create_user_form.php" method="post" class="space-y-6">
                    <!-- Personal Information Section -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-white border-b border-white/20 pb-2">Personal Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="fname" class="block text-white font-medium mb-2">
                                    <i class="fas fa-user mr-2"></i>First Name
                                </label>
                                <input type="text" id="fname" name="fname" placeholder="First Name" 
                                       value="<?php echo htmlspecialchars($fname); ?>" required
                                       class="w-full px-4 py-3 border border-white/30 rounded-xl bg-white/10 text-white placeholder-white/60 input-focus focus:outline-none transition-all duration-300">
                            </div>
                            
                            <div>
                                <label for="mname" class="block text-white font-medium mb-2">
                                    <i class="fas fa-user mr-2"></i>Middle Name
                                </label>
                                <input type="text" id="mname" name="mname" placeholder="Middle Name" 
                                       value="<?php echo htmlspecialchars($mname); ?>" required
                                       class="w-full px-4 py-3 border border-white/30 rounded-xl bg-white/10 text-white placeholder-white/60 input-focus focus:outline-none transition-all duration-300">
                            </div>
                            
                            <div>
                                <label for="lname" class="block text-white font-medium mb-2">
                                    <i class="fas fa-user mr-2"></i>Last Name
                                </label>
                                <input type="text" id="lname" name="lname" placeholder="Last Name" 
                                       value="<?php echo htmlspecialchars($lname); ?>" required
                                       class="w-full px-4 py-3 border border-white/30 rounded-xl bg-white/10 text-white placeholder-white/60 input-focus focus:outline-none transition-all duration-300">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="gender" class="block text-white font-medium mb-2">
                                    <i class="fas fa-venus-mars mr-2"></i>Gender
                                </label>
                                <select id="gender" name="gender" required
                                        class="w-full px-4 py-3 border border-white/30 rounded-xl bg-white/10 text-white input-focus focus:outline-none transition-all duration-300">
                                    <option value="" disabled <?php echo empty($gender) ? 'selected' : ''; ?>>Select Gender</option>
                                    <option value="m" <?php echo $gender == 'm' ? 'selected' : ''; ?>>Male</option>
                                    <option value="f" <?php echo $gender == 'f' ? 'selected' : ''; ?>>Female</option>
                                    <option value="x" <?php echo $gender == 'x' ? 'selected' : ''; ?>>Prefer not to say</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="birthday" class="block text-white font-medium mb-2">
                                    <i class="fas fa-birthday-cake mr-2"></i>Birthday
                                </label>
                                <input type="date" id="birthday" name="birthday" 
                                       value="<?php echo htmlspecialchars($birthday); ?>" required
                                       class="w-full px-4 py-3 border border-white/30 rounded-xl bg-white/10 text-white input-focus focus:outline-none transition-all duration-300">
                            </div>
                            
                            <div>
                                <label for="role" class="block text-white font-medium mb-2">
                                    <i class="fas fa-user-tag mr-2"></i>Role
                                </label>
                                <select id="role" name="role" required
                                        class="w-full px-4 py-3 border border-white/30 rounded-xl bg-white/10 text-white input-focus focus:outline-none transition-all duration-300">
                                    <option value="" disabled <?= empty($role) ? 'selected' : ''; ?>>Select Role</option>
                                    <option value="user" <?= $role == 'user' ? 'selected' : ''; ?>>User</option>
                                    <option value="admin" <?= $role == 'admin' ? 'selected' : ''; ?>>Admin</option>
                                </select>
                            </div>
                        </div>
                        
                        <div>
                            <label for="contact_no" class="block text-white font-medium mb-2">
                                <i class="fas fa-phone mr-2"></i>Contact Number
                            </label>
                            <input type="text" id="contact_no" name="cno" placeholder="Contact Number" 
                                   value="<?php echo htmlspecialchars($contact_no); ?>" required
                                   class="w-full px-4 py-3 border border-white/30 rounded-xl bg-white/10 text-white placeholder-white/60 input-focus focus:outline-none transition-all duration-300">
                        </div>
                    </div>
                    
                    <!-- Account Information Section -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-white border-b border-white/20 pb-2">Account Information</h3>
                        
                        <div>
                            <label for="username" class="block text-white font-medium mb-2">
                                <i class="fas fa-user-circle mr-2"></i>Username
                            </label>
                            <input type="text" id="username" name="username" placeholder="Username" 
                                   value="<?php echo htmlspecialchars($username); ?>" required
                                   class="w-full px-4 py-3 border border-white/30 rounded-xl bg-white/10 text-white placeholder-white/60 input-focus focus:outline-none transition-all duration-300">
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="password" class="block text-white font-medium mb-2">
                                    <i class="fas fa-lock mr-2"></i>Password
                                </label>
                                <div class="password-container">
                                    <input type="password" id="password" name="password" placeholder="Password" required
                                           class="w-full px-4 py-3 pr-10 border border-white/30 rounded-xl bg-white/10 text-white placeholder-white/60 input-focus focus:outline-none transition-all duration-300">
                                    <span class="toggle-password" data-target="password">
                                        <i class="fas fa-eye"></i>
                                    </span>
                                </div>
                            </div>
                            
                            <div>
                                <label for="confirm_password" class="block text-white font-medium mb-2">
                                    <i class="fas fa-lock mr-2"></i>Confirm Password
                                </label>
                                <div class="password-container">
                                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required
                                           class="w-full px-4 py-3 pr-10 border border-white/30 rounded-xl bg-white/10 text-white placeholder-white/60 input-focus focus:outline-none transition-all duration-300">
                                    <span class="toggle-password" data-target="confirm_password">
                                        <i class="fas fa-eye"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-4">
                        <button type="submit" name="submit" 
                                class="flex-1 btn-gradient text-white py-3 px-4 rounded-xl font-semibold transition-all duration-300 shadow-lg flex items-center justify-center gap-2">
                            <i class="fas fa-user-plus"></i>
                            Create User Account
                        </button>
                        <a href="AdminDashboard.php"
                            class="flex-1 bg-red-600 hover:bg-red-700 text-white py-3 px-4 rounded-xl font-semibold transition-all duration-300 shadow-lg flex items-center justify-center gap-2">
                             <i class="fas fa-times"></i>
                             Cancel
                        </a>
                    </div>
                </form>
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

<script>
    // Password visibility toggle
    document.querySelectorAll('.toggle-password').forEach(toggle => {
        toggle.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const passwordInput = document.getElementById(targetId);
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Toggle eye icon
            if (type === 'text') {
                this.innerHTML = '<i class="fas fa-eye-slash"></i>';
            } else {
                this.innerHTML = '<i class="fas fa-eye"></i>';
            }
        });
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