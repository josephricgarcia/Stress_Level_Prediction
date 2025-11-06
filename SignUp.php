<?php
include 'connection.php';
session_start();

// Generate a salted & hashed password
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
    $role = 'user'; // Default role for new users

    // Validation
    if (empty($lname) || empty($fname) || empty($username) || empty($password)) {
        $error_message = "Please fill in all required fields.";
    } elseif ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        // Check if username exists
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

                // Insert user with all fields, matching AdminDashboard.php column names
                $stmt = mysqli_prepare($dbhandle, 
                    "INSERT INTO users (lname, fname, mname, gender, birthday, cno, username, password, salt, role) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                mysqli_stmt_bind_param($stmt, "ssssssssss", 
                    $lname, $fname, $mname, $gender, $birthday, $cno, $username, $hashedPassword, $salt, $role);

                if (mysqli_stmt_execute($stmt)) {
                    // Set session variables to match session.php and other files
                    $_SESSION['logged_in'] = true;
                    $_SESSION['username'] = $username;
                    $_SESSION['user_id'] = mysqli_insert_id($dbhandle);
                    $_SESSION['role'] = $role;
                    header("Location: Home.php");
                    exit;
                } else {
                    $error_message = "Error: " . mysqli_error($dbhandle);
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
    <title>StressSense: User Form</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="shortcut icon" href="images/stresssense_logo.png">
</head>
<body>
    <header>
        <div class="logo">
            <img src="images/stresssense_logo.png" alt="Logo"> STRESS SENSE
        </div>
    </header>

    <div class="user-form">
        <h1>Create Your Account Here</h1>
        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        <form action="SignUp.php" method="post">
            <input type="text" id="lname" name="lname" placeholder="Last Name" value="<?php echo htmlspecialchars($lname); ?>" required> 
            <input type="text" id="fname" name="fname" placeholder="First Name" value="<?php echo htmlspecialchars($fname); ?>" required>
            <input type="text" id="mname" name="mname" placeholder="Middle Name" value="<?php echo htmlspecialchars($mname); ?>" required>
            <div class="form-row">
                <select id="gender" name="gender" required>
                    <option value="" disabled <?php echo empty($gender) ? 'selected' : ''; ?>>Select Gender</option>
                    <option value="m" <?php echo $gender == 'm' ? 'selected' : ''; ?>>Male</option>
                    <option value="f" <?php echo $gender == 'f' ? 'selected' : ''; ?>>Female</option>
                    <option value="x" <?php echo $gender == 'x' ? 'selected' : ''; ?>>Prefer not to say</option>
                </select>
                <input type="date" id="birthday" name="birthday" value="<?php echo htmlspecialchars($birthday); ?>" required>
            </div>
            <input type="text" id="cno" name="cno" placeholder="Contact Number" value="<?php echo htmlspecialchars($cno); ?>" required>
            <input type="text" id="username" name="username" placeholder="Username" value="<?php echo htmlspecialchars($username); ?>" required>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit" name="submit">Create Account</button>
            <p>Already have an account? <a href="LogIn.php">Log in here</a></p>
        </form>
    </div>

    <footer>
        &copy; 2025 StressSense. All Rights Reserved. |
        <a href="AboutUs.php">About Us</a> | <a href="PrivacyPolicy.php">Privacy Policy</a> | <a href="TermsOfService.php">Terms of Service</a> | <a href="Contact.php">Contact Us</a>
    </footer>
</body>
</html>