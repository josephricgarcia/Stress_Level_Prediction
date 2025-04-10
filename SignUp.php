<?php
include 'connection.php';

if (!isset($dbhandle) || !$dbhandle) {
    echo "<script>alert('localhost says: Database connection failed: " . mysqli_connect_error() . "');</script>";
    exit();
}

$confirm_password = "";

$lname = $fname = $mname = $gender = $birthday = $contact_no = $username = "";
$error_message = "";

if (!$dbhandle) {
    echo "<script>alert('localhost says: Database connection failed: " . mysqli_connect_error() . "');</script>";
    exit();
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

    if ($password != $confirm_password) {
        echo "<script>alert('localhost says: Passwords do not match!');</script>";
    } else {
        $check_sql = "SELECT username FROM users WHERE username = ?";
        if ($stmt_check = mysqli_prepare($dbhandle, $check_sql)) {
            mysqli_stmt_bind_param($stmt_check, "s", $username);
            mysqli_stmt_execute($stmt_check);
            mysqli_stmt_store_result($stmt_check);

            if (mysqli_stmt_num_rows($stmt_check) > 0) {
                echo "<script>alert('localhost says: Username already exists! Please choose a different username.');</script>";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $sql = "INSERT INTO users (lname, fname, mname, gender, birthday, cno, username, password, role) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'user')";

                if ($stmt = mysqli_prepare($dbhandle, $sql)) {
                    mysqli_stmt_bind_param($stmt, "ssssssss", $lname, $fname, $mname, $gender, $birthday, $contact_no, $username, $hashed_password);

                    if (mysqli_stmt_execute($stmt)) {
                        echo "<script>alert('localhost says: Account created successfully!'); window.location.href = 'LogIn.php';</script>";
                        exit();
                    } else {
                        echo "<script>alert('localhost says: Account registration failed: " . mysqli_error($dbhandle) . "');</script>";
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    echo "<script>alert('localhost says: SQL statement preparation failed: " . mysqli_error($dbhandle) . "');</script>";
                }
            }
            mysqli_stmt_close($stmt_check);
        } else {
            echo "<script>alert('localhost says: SQL statement preparation failed: " . mysqli_error($dbhandle) . "');</script>";
        }
    }
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
            <input type="text" id="contact_no" name="cno" placeholder="Contact Number" value="<?php echo htmlspecialchars($contact_no); ?>" required>
            <input type="text" id="username" name="username" placeholder="Username" value="<?php echo htmlspecialchars($username); ?>" required>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit" name="submit">Create Account</button>
            <p>Already have an account?<a href="LogIn.php"> Log in here</a></p>
        </form>
    </div>

    <footer>
        &copy; 2025 StresSense. All Rights Reserved. |
        <a href="About Us.php">About Us</a> | <a href="Privacy Policy.php">Privacy Policy</a> | <a href="Terms Of Service.php">Terms of Service</a> | <a href="Contact.php">Contact Us</a>
    </footer>
</body>
</html>