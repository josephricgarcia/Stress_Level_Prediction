<?php
include 'connection.php';
session_start();

// Verify salted + hashed password
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
                $_SESSION['logged_in'] = true;
                $_SESSION['username'] = $username;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                
                // Redirect based on role
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
    <title>StressSense: Log In</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="shortcut icon" href="images/stresssense_logo.png">
</head>
<body>
    <header>
        <div class="logo">
            <img src="images/stresssense_logo.png" alt="Logo"> STRESS SENSE
        </div>
    </header>

    <div class="login-form">
        <h1>Sign In Your Account</h1>
        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        <?php if (!empty($success_message)): ?>
            <div class="success-message"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        <form action="LogIn.php" method="post">
            <input type="text" id="username" name="username" placeholder="Username" required>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <button type="submit" name="submit">Log In</button>
            <p>Don't have an account? <a href="SignUp.php">Sign Up</a></p>
        </form>
    </div>

    <footer>
        &copy; 2025 StressSense. All Rights Reserved. |
        <a href="AboutUs.php">About Us</a> | <a href="PrivacyPolicy.php">Privacy Policy</a> | <a href="TermsOfService.php">Terms of Service</a> | <a href="Contact.php">Contact Us</a>
    </footer>
</body>
</html>