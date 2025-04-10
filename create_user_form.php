<?php
include 'connection.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: LogIn.php");
    exit();
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
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $sql = "INSERT INTO users (lname, fname, mname, gender, birthday, cno, username, password, role) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

                if ($stmt = mysqli_prepare($dbhandle, $sql)) {
                    mysqli_stmt_bind_param($stmt, "sssssssss", $lname, $fname, $mname, $gender, $birthday, $contact_no, $username, $hashed_password, $role);

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
    <title>Add New User - StresSense Admin</title>
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
        <h1>Create User Account Here</h1>
        <form action="create_user_form.php" method="post">
            <input type="text" id="lname" name="lname" placeholder="Last Name" value="<?php echo htmlspecialchars($lname); ?>" required> 
            <input type="text" id="fname" name="fname" placeholder="First Name" value="<?php echo htmlspecialchars($fname); ?>" required>
            <input type="text" id="mname" name="mname" placeholder="Middle Name" value="<?php echo htmlspecialchars($mname); ?>" required>
            <div class="form-row">
                <select id="gender" name="gender" required>
                    <option value="" disabled <?php echo empty($gender) ? 'selected' : ''; ?>>Gender</option>
                    <option value="m" <?php echo $gender == 'm' ? 'selected' : ''; ?>>Male</option>
                    <option value="f" <?php echo $gender == 'f' ? 'selected' : ''; ?>>Female</option>
                    <option value="x" <?php echo $gender == 'x' ? 'selected' : ''; ?>>Prefer not to say</option>
                </select>

                <select id="role" name="role" required>
                    <option value="" disabled <?php echo empty($role) ? 'selected' : ''; ?>>Role</option>
                    <option value="user" <?php echo $role == 'user' ? 'selected' : ''; ?>>User</option>
                    <option value="admin" <?php echo $role == 'admin' ? 'selected' : ''; ?>>Admin</option>
                </select>

                <input type="date" id="birthday" name="birthday" value="<?php echo htmlspecialchars($birthday); ?>" required>
            </div>
            <input type="text" id="contact_no" name="cno" placeholder="Contact Number" value="<?php echo htmlspecialchars($contact_no); ?>" required>
            <input type="text" id="username" name="username" placeholder="Username" value="<?php echo htmlspecialchars($username); ?>" required>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit" name="submit">Create Account</button>
            <a href="AdminDashboard.php" style="color: red; text-decoration: none; margin-left: 10px;">Cancel</a>
        </form>
    </div>
</body>
</html>