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
    <style>
        body {
            background: linear-gradient(135deg, #dbeafe, #f0f9ff);
            height: 100vh;
            overflow: hidden;
        }
        .compact-form {
            max-height: calc(100vh - 120px);
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
            color: #6b7280;
        }
        .toggle-password:hover {
            color: #374151;
        }
    </style>
</head>

<body class="flex flex-col h-screen">

<header class="bg-white/70 backdrop-blur shadow-sm py-4 px-6 flex items-center gap-3 border-b">
    <img src="images/stresssense_logo.png" class="w-12 h-12" alt="Logo">
    <span class="text-2xl font-semibold tracking-wide text-gray-700">STRESS SENSE</span>
</header>

<main class="flex-grow px-4 py-3 overflow-auto">
    <div class="max-w-3xl mx-auto compact-form">
        <div class="bg-white/90 backdrop-blur rounded-xl shadow-lg border border-blue-100/70 overflow-hidden">
            <div class="p-4 border-b border-gray-200">
                <div class="flex items-center justify-center">
                    <h2 class="text-xl font-bold text-blue-700 text-center">Create Your Account</h2>
                </div>
            </div>

            <div class="p-4">
                <p class="text-center text-gray-600 mb-6 text-sm">Join us and start your stress-management journey.</p>

                <form action="SignUp.php" method="post" class="space-y-4">
                    <!-- Personal Information Section -->
                    <div class="space-y-3">
                        <h3 class="text-base font-semibold text-gray-700 border-b pb-1">Personal Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                            <div>
                                <label for="lname" class="block text-xs font-medium text-gray-700 mb-1">Last Name</label>
                                <input type="text" id="lname" name="lname" placeholder="Last Name" 
                                       value="<?= htmlspecialchars($lname); ?>" required
                                       class="w-full px-3 py-1 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            </div>
                            
                            <div>
                                <label for="fname" class="block text-xs font-medium text-gray-700 mb-1">First Name</label>
                                <input type="text" id="fname" name="fname" placeholder="First Name" 
                                       value="<?= htmlspecialchars($fname); ?>" required
                                       class="w-full px-3 py-1 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            </div>
                            
                            <div>
                                <label for="mname" class="block text-xs font-medium text-gray-700 mb-1">Middle Name</label>
                                <input type="text" id="mname" name="mname" placeholder="Middle Name" 
                                       value="<?= htmlspecialchars($mname); ?>"
                                       class="w-full px-3 py-1 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                            <div>
                                <label for="gender" class="block text-xs font-medium text-gray-700 mb-1">Gender</label>
                                <select id="gender" name="gender" required
                                        class="w-full px-3 py-1 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                    <option value="" disabled <?= empty($gender) ? 'selected' : ''; ?>>Select Gender</option>
                                    <option value="m" <?= $gender == 'm' ? 'selected' : ''; ?>>Male</option>
                                    <option value="f" <?= $gender == 'f' ? 'selected' : ''; ?>>Female</option>
                                    <option value="x" <?= $gender == 'x' ? 'selected' : ''; ?>>Prefer not to say</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="birthday" class="block text-xs font-medium text-gray-700 mb-1">Birthday</label>
                                <input type="date" id="birthday" name="birthday" 
                                       value="<?= htmlspecialchars($birthday); ?>" required
                                       class="w-full px-3 py-1 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            </div>
                            
                            <div>
                                <label for="cno" class="block text-xs font-medium text-gray-700 mb-1">Contact Number</label>
                                <input type="text" id="cno" name="cno" placeholder="Contact Number" 
                                       value="<?= htmlspecialchars($cno); ?>" required
                                       class="w-full px-3 py-1 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Account Information Section -->
                    <div class="space-y-3">
                        <h3 class="text-base font-semibold text-gray-700 border-b pb-1">Account Information</h3>
                        
                        <div>
                            <label for="username" class="block text-xs font-medium text-gray-700 mb-1">Username</label>
                            <input type="text" id="username" name="username" placeholder="Username" 
                                   value="<?= htmlspecialchars($username); ?>" required
                                   class="w-full px-3 py-1 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            <div class="password-container">
                                <label for="password" class="block text-xs font-medium text-gray-700 mb-1">Password</label>
                                <input type="password" id="password" name="password" placeholder="Password" required
                                       class="w-full px-3 py-1 pr-10 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                <span class="toggle-password" data-target="password">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                    </svg>
                                </span>
                            </div>
                            
                            <div class="password-container">
                                <label for="confirm_password" class="block text-xs font-medium text-gray-700 mb-1">Confirm Password</label>
                                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required
                                       class="w-full px-3 py-1 pr-10 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                <span class="toggle-password" data-target="confirm_password">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="flex flex-col sm:flex-row gap-2 pt-2">
                        <button type="submit" 
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-3 rounded-lg transition flex items-center justify-center gap-1 text-sm font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Create Account
                        </button>
                        <a href="LogIn.php" 
                           class="flex-1 bg-gray-600 hover:bg-gray-700 text-white py-2 px-3 rounded-lg transition flex items-center justify-center gap-1 text-sm font-medium text-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back to Login
                        </a>
                    </div>
                    
                    <p class="text-center text-gray-600 text-xs mt-4">
                        Already have an account?
                        <a href="LogIn.php" class="text-blue-600 font-semibold hover:underline">Log in here</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</main>

<footer class="bg-white/80 backdrop-blur py-4 text-center text-gray-600 text-sm border-t">
    &copy; 2025 StressSense. All Rights Reserved |
    <a href="AboutUs.php" class="hover:underline">About Us</a> |
    <a href="PrivacyPolicy.php" class="hover:underline">Privacy Policy</a> |
    <a href="TermsOfService.php" class="hover:underline">Terms</a> |
    <a href="Contact.php" class="hover:underline">Contact</a>
</footer>

<?php if (!empty($error_message)): ?>
    <script>alert("<?= htmlspecialchars($error_message); ?>");</script>
<?php endif; ?>

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
                this.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-slash" viewBox="0 0 16 16">
                        <path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7.028 7.028 0 0 0-2.79.588l.77.771A5.944 5.944 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.134 13.134 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755-.165.165-.337.328-.517.486l.708.709z"/>
                        <path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829l.822.822zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829z"/>
                        <path d="M3.35 5.47c-.18.16-.353.322-.518.487A13.134 13.134 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7.029 7.029 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709zm10.296 8.884-12-12 .708-.708 12 12-.708.708z"/>
                    </svg>
                `;
            } else {
                this.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                    </svg>
                `;
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