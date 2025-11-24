<?php
include 'session.php';
include 'connection.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    include 'connection.php';

    $id = isset($_POST['id']) ? trim($_POST['id']) : null;
    $lname = isset($_POST['lname']) ? trim($_POST['lname']) : null;
    $fname = isset($_POST['fname']) ? trim($_POST['fname']) : null;
    $mname = isset($_POST['mname']) ? trim($_POST['mname']) : null;
    $gender = isset($_POST['gender']) ? trim($_POST['gender']) : null;
    $birthday = isset($_POST['birthday']) ? trim($_POST['birthday']) : null;
    $cno = isset($_POST['cno']) ? trim($_POST['cno']) : null;
    $username = isset($_POST['username']) ? trim($_POST['username']) : null;
    $password = isset($_POST['password']) ? trim($_POST['password']) : null;
    $role = isset($_POST['role']) ? trim($_POST['role']) : null;

    if (!$dbhandle) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    $sql_check = "SELECT id FROM users WHERE username = ? AND id != ?";
    $stmt_check = mysqli_prepare($dbhandle, $sql_check);

    if (!$stmt_check) {
        die("Prepare failed: " . mysqli_error($dbhandle));
    }

    mysqli_stmt_bind_param($stmt_check, "si", $username, $id);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);

    if (mysqli_stmt_num_rows($stmt_check) > 0) {
        echo "<script>alert('Username already exists. Please choose a different username.');</script>";

        $edit_user = [
            'id' => $id,
            'lname' => $lname,
            'fname' => $fname,
            'mname' => $mname,
            'gender' => $gender,
            'birthday' => $birthday,
            'cno' => $cno,
            'username' => $username,
            'role' => $role
        ];
        mysqli_stmt_close($stmt_check);
        mysqli_close($dbhandle);
    } else {
        mysqli_stmt_close($stmt_check);

        $sql = "UPDATE users SET 
                lname = ?, 
                fname = ?, 
                mname = ?, 
                gender = ?, 
                birthday = ?, 
                cno = ?, 
                username = ?, 
                role = ?"
                . (!empty($password) ? ", password = ?" : "") 
                . " WHERE id = ?";

        $stmt = mysqli_prepare($dbhandle, $sql);

        if ($stmt) {
            $params = [];
            if (!empty($password)) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $params = [$lname, $fname, $mname, $gender, $birthday, $cno, $username, $role, $hashed_password, $id];
                mysqli_stmt_bind_param($stmt, "sssssssssi", ...$params);
            } else {
                $params = [$lname, $fname, $mname, $gender, $birthday, $cno, $username, $role, $id];
                mysqli_stmt_bind_param($stmt, "ssssssssi", ...$params);
            }

            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['success_message'] = "User updated successfully!";
                header("Location: AdminDashboard.php");
                exit();
            } else {
                $_SESSION['error_message'] = "Update failed: " . mysqli_error($dbhandle);

                $edit_user = [
                    'id' => $id,
                    'lname' => $lname,
                    'fname' => $fname,
                    'mname' => $mname,
                    'gender' => $gender,
                    'birthday' => $birthday,
                    'cno' => $cno,
                    'username' => $username,
                    'role' => $role
                ];
            }
            mysqli_stmt_close($stmt);
        } else {
            $_SESSION['error_message'] = "Database error: " . mysqli_error($dbhandle);
            $edit_user = [
                'id' => $id,
                'lname' => $lname,
                'fname' => $fname,
                'mname' => $mname,
                'gender' => $gender,
                'birthday' => $birthday,
                'cno' => $cno,
                'username' => $username,
                'role' => $role
            ];
        }
        mysqli_close($dbhandle);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {

    include 'connection.php';
    $id = $_GET['id'];
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = mysqli_prepare($dbhandle, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $edit_user = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
    } else {
        die("Error fetching user: " . mysqli_error($dbhandle));
    }
    mysqli_close($dbhandle);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StressSense - Edit User</title>
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
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: rgba(255, 255, 255, 0.7);
        }
        .toggle-password:hover {
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
            <span class="bg-white/20 backdrop-blur text-white text-xs px-3 py-1 rounded-full">Admin</span>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="flex-grow flex items-center justify-center px-4 py-6">
        <div class="glass-card p-8 rounded-3xl w-full max-w-2xl relative overflow-hidden">
            <!-- Decorative Elements -->
            <div class="absolute -top-10 -right-10 w-24 h-24 rounded-full bg-indigo-600/30 opacity-40"></div>
            <div class="absolute -bottom-8 -left-8 w-20 h-20 rounded-full bg-purple-600/30 opacity-40"></div>
            
            <div class="relative z-10">
                <h1 class="text-3xl font-bold text-center text-white mb-2">Edit User Account</h1>
                <p class="text-center text-white/80 mb-6">Update user information and account details</p>

                <?php if (isset($_SESSION['error_message'])): ?>
                    <div class="mb-4 p-3 bg-red-600/30 backdrop-blur border border-red-400 text-red-200 rounded-lg text-sm">
                        <?= htmlspecialchars($_SESSION['error_message']); unset($_SESSION['error_message']); ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="edit_user_form.php" class="space-y-6">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($edit_user['id'] ?? '') ?>">
                    
                    <!-- Personal Information Section -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-white border-b border-white/20 pb-2">Personal Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="lname" class="block text-white font-medium mb-2">
                                    <i class="fas fa-user mr-2"></i>Last Name
                                </label>
                                <input type="text" id="lname" name="lname" placeholder="Last Name" 
                                       value="<?= htmlspecialchars($edit_user['lname'] ?? '') ?>" required
                                       class="w-full px-4 py-3 border border-white/20 rounded-xl bg-white/10 text-white placeholder-white/50 input-focus focus:outline-none transition-all duration-300 backdrop-blur">
                            </div>
                            
                            <div>
                                <label for="fname" class="block text-white font-medium mb-2">
                                    <i class="fas fa-user mr-2"></i>First Name
                                </label>
                                <input type="text" id="fname" name="fname" placeholder="First Name" 
                                       value="<?= htmlspecialchars($edit_user['fname'] ?? '') ?>" required
                                       class="w-full px-4 py-3 border border-white/20 rounded-xl bg-white/10 text-white placeholder-white/50 input-focus focus:outline-none transition-all duration-300 backdrop-blur">
                            </div>
                            
                            <div>
                                <label for="mname" class="block text-white font-medium mb-2">
                                    <i class="fas fa-user mr-2"></i>Middle Name
                                </label>
                                <input type="text" id="mname" name="mname" placeholder="Middle Name" 
                                       value="<?= htmlspecialchars($edit_user['mname'] ?? '') ?>" required
                                       class="w-full px-4 py-3 border border-white/20 rounded-xl bg-white/10 text-white placeholder-white/50 input-focus focus:outline-none transition-all duration-300 backdrop-blur">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="gender" class="block text-white font-medium mb-2">
                                    <i class="fas fa-venus-mars mr-2"></i>Gender
                                </label>
                                <select id="gender" name="gender" required
                                        class="w-full px-4 py-3 border border-white/20 rounded-xl bg-white/10 text-white placeholder-white/50 input-focus focus:outline-none transition-all duration-300 backdrop-blur">
                                    <option value="m" <?= ($edit_user['gender'] ?? '') === 'm' ? 'selected' : '' ?>>Male</option>
                                    <option value="f" <?= ($edit_user['gender'] ?? '') === 'f' ? 'selected' : '' ?>>Female</option>
                                    <option value="x" <?= ($edit_user['gender'] ?? '') === 'x' ? 'selected' : '' ?>>Prefer not to say</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="birthday" class="block text-white font-medium mb-2">
                                    <i class="fas fa-calendar mr-2"></i>Birthday
                                </label>
                                <input type="date" id="birthday" name="birthday" 
                                       value="<?= htmlspecialchars($edit_user['birthday'] ?? '') ?>" required
                                       class="w-full px-4 py-3 border border-white/20 rounded-xl bg-white/10 text-white placeholder-white/50 input-focus focus:outline-none transition-all duration-300 backdrop-blur">
                            </div>
                            
                            <div>
                                <label for="role" class="block text-white font-medium mb-2">
                                    <i class="fas fa-user-tag mr-2"></i>Role
                                </label>
                                <select id="role" name="role" required
                                        class="w-full px-4 py-3 border border-white/20 rounded-xl bg-white/10 text-white placeholder-white/50 input-focus focus:outline-none transition-all duration-300 backdrop-blur">
                                    <option value="user" <?= ($edit_user['role'] ?? '') === 'user' ? 'selected' : '' ?>>User</option>
                                    <option value="admin" <?= ($edit_user['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
                                </select>
                            </div>
                        </div>
                        
                        <div>
                            <label for="contact_no" class="block text-white font-medium mb-2">
                                <i class="fas fa-phone mr-2"></i>Contact Number
                            </label>
                            <input type="text" id="contact_no" name="cno" placeholder="Contact Number" 
                                   value="<?= htmlspecialchars($edit_user['cno'] ?? '') ?>" required
                                   class="w-full px-4 py-3 border border-white/20 rounded-xl bg-white/10 text-white placeholder-white/50 input-focus focus:outline-none transition-all duration-300 backdrop-blur">
                        </div>
                    </div>
                    
                    <!-- Account Information Section -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-white border-b border-white/20 pb-2">Account Information</h3>
                        
                        <div>
                            <label for="username" class="block text-white font-medium mb-2">
                                <i class="fas fa-at mr-2"></i>Username
                            </label>
                            <input type="text" id="username" name="username" placeholder="Username" 
                                   value="<?= htmlspecialchars($edit_user['username'] ?? '') ?>" required
                                   class="w-full px-4 py-3 border border-white/20 rounded-xl bg-white/10 text-white placeholder-white/50 input-focus focus:outline-none transition-all duration-300 backdrop-blur">
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="password" class="block text-white font-medium mb-2">
                                    <i class="fas fa-lock mr-2"></i>Password
                                </label>
                                <div class="password-container">
                                    <input type="password" id="password" name="password" placeholder="Password (leave blank to keep current)"
                                           class="w-full px-4 py-3 pr-10 border border-white/20 rounded-xl bg-white/10 text-white placeholder-white/50 input-focus focus:outline-none transition-all duration-300 backdrop-blur">
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
                                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password (leave blank to keep current)"
                                           class="w-full px-4 py-3 pr-10 border border-white/20 rounded-xl bg-white/10 text-white placeholder-white/50 input-focus focus:outline-none transition-all duration-300 backdrop-blur">
                                    <span class="toggle-password" data-target="confirm_password">
                                        <i class="fas fa-eye"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-4">
                        <button type="submit" name="update_user" 
                                class="flex-1 btn-gradient text-white py-3 px-4 rounded-xl font-semibold transition-all duration-300 shadow-lg flex items-center justify-center gap-2">
                            <i class="fas fa-check"></i>
                            Update User Account
                        </button>
                        <a href="AdminDashboard.php"
                            class="flex-1 bg-red-600 hover:bg-red-700 text-white py-3 px-4 rounded-xl font-semibold transition-all duration-300 shadow-lg flex items-center justify-center gap-2 backdrop-blur">
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
            &copy; 2025 StressSense. All Rights Reserved |
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
            this.innerHTML = type === 'text' ? '<i class="fas fa-eye-slash"></i>' : '<i class="fas fa-eye"></i>';
        });
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