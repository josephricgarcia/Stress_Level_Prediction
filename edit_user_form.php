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
    <title>Update User</title>
    <link rel="shortcut icon" href="images/stresssense_logo.png">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<header>
        <div class="logo">
            <img src="images/stresssense_logo.png" alt="Logo"> STRESS SENSE
        </div>
        <nav>
            <a href="LogOut.php">Logout</a>
        </nav>
    </header>

    <div class="user-form">
        <h1>Edit User Account</h1>
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="error-message"><?= htmlspecialchars($_SESSION['error_message']); unset($_SESSION['error_message']); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="edit_user_form.php">
            <input type="hidden" name="id" value="<?= htmlspecialchars($edit_user['id'] ?? '') ?>">
            
            <input type="text" id="lname" name="lname" placeholder="Last Name" value="<?= htmlspecialchars($edit_user['lname'] ?? '') ?>" required> 
            <input type="text" id="fname" name="fname" placeholder="First Name" value="<?= htmlspecialchars($edit_user['fname'] ?? '') ?>" required>
            <input type="text" id="mname" name="mname" placeholder="Middle Name" value="<?= htmlspecialchars($edit_user['mname'] ?? '') ?>" required>

            <div class="form-row">
                <select id="gender" name="gender" required>
                    <option value="m" <?= ($edit_user['gender'] ?? '') === 'm' ? 'selected' : '' ?>>Male</option>
                    <option value="f" <?= ($edit_user['gender'] ?? '') === 'f' ? 'selected' : '' ?>>Female</option>
                    <option value="x" <?= ($edit_user['gender'] ?? '') === 'x' ? 'selected' : '' ?>>Prefer not to say</option>
                </select>

                <select id="role" name="role" required>
                    <option value="user" <?= ($edit_user['role'] ?? '') === 'user' ? 'selected' : '' ?>>User</option>
                    <option value="admin" <?= ($edit_user['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
                </select>

                <input type="date" id="birthday" name="birthday" value="<?= htmlspecialchars($edit_user['birthday'] ?? '') ?>" required>
            </div>

            <input type="text" id="contact_no" name="cno" placeholder="Contact Number" value="<?= htmlspecialchars($edit_user['cno'] ?? '') ?>" required>
            <input type="text" id="username" name="username" placeholder="Username" value="<?= htmlspecialchars($edit_user['username'] ?? '') ?>" required>
            <input type="password" id="password" name="password" placeholder="Password (leave blank to keep current)">
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password (leave blank to keep current)">
            
            <button type="submit" name="update_user" class="submit-btn">Update</button>
            <a href="AdminDashboard.php" style="color: red; text-decoration: none; margin-left: 10px;">Cancel</a>
        </form>
    </div>
</body>
</html>