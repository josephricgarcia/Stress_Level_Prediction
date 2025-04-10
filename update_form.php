<?php
include 'session.php';
include 'connection.php';

if (!$dbhandle) {
    die("Database connection failed: " . htmlspecialchars(mysqli_connect_error()));
}

$user = [
    'id' => '',
    'lname' => '',
    'fname' => '',
    'mname' => '',
    'gender' => '', 
    'birthday' => '',
    'cno' => '',
];

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
    $lname = $_POST['lname'] ?? '';
    $fname = $_POST['fname'] ?? '';
    $mname = $_POST['mname'] ?? '';
    $gender = $_POST['gender'] ?? 'm';
    $birthday = $_POST['birthday'] ?? '';
    $contact_no = $_POST['cno'] ?? '';
    
    $sql = "UPDATE users SET 
            lname = ?, 
            fname = ?, 
            mname = ?, 
            gender = ?, 
            birthday = ?, 
            cno = ? 
            WHERE id = ?";
    
    $stmt = $dbhandle->prepare($sql);
    $stmt->bind_param("ssssssi", $lname, $fname, $mname, $gender, $birthday, $contact_no, $id);
    
    if ($stmt->execute()) {
        echo "<script>
                alert('User Information updated successfully!');
                window.location.href = 'Settings.php';
              </script>";
    } else {
        echo "<script>alert('User Information update failed: " . $dbhandle->error . "');</script>";
    }
    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StresSense</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="shortcut icon" href="images/stresssense_logo.png">
</head>
<body>

    <header>
        <div class="logo">
            <img src="images/stresssense_logo.png" alt="Logo"> STRESS SENSE
        </div>
        <nav>
            <a href="Home.php">HOME</a>
            <a href="Assessment.php">ASSESSMENT</a>
            <a href="Tips And Resources.php">TIPS AND RESOURCES</a>
            <a href="Settings.php">SETTINGS</a>
        </nav>
    </header>

    <div class="form-container">
        <h2>Update User Information</h2>
        <form method="POST" action="update_form.php">
            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
            
            <div class="form-group">
                <label for="lname">Last Name:</label>
                <input type="text" id="lname" name="lname" value="<?php echo htmlspecialchars($user['lname']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="fname">First Name:</label>
                <input type="text" id="fname" name="fname" value="<?php echo htmlspecialchars($user['fname']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="mname">Middle Name:</label>
                <input type="text" id="mname" name="mname" value="<?php echo htmlspecialchars($user['mname']); ?>">
            </div>
            
            <div class="form-group">
                <label for="gender">Gender:</label>
                <select id="gender" name="gender" required>
                    <option value="m" <?php echo ($user['gender'] === 'm') ? 'selected' : ''; ?>>Male</option>
                    <option value="f" <?php echo ($user['gender'] === 'f') ? 'selected' : ''; ?>>Female</option>
            <option value="x" <?php echo ($user['gender'] === 'x') ? 'selected' : ''; ?>>Prefer not to say</option>
                            </select>
                        </div>

            <div class="form-group">
                <label for="birthday">Birthday:</label>
                <input type="date" id="birthday" name="birthday" value="<?php echo htmlspecialchars($user['birthday']); ?>" required>

                <label for="contact_no">Contact No:</label>
                <input type="text" id="cno" name="cno" value="<?php echo htmlspecialchars($user['cno']); ?>" required>
            </div>
            
            <div class="form-group1">
                <button type="submit" name="update_user" class="submit-btn">Update User</button>
                <a href="Settings.php" class="cancel-btn">Cancel</a>
            </div>
        </form>
    </div>

    <footer>
        &copy; 2025 StresSense. All Rights Reserved. |
        <a href="About Us.php">About Us</a> | <a href="Privacy Policy.php">Privacy Policy</a> | <a href="Terms Of Service.php">Terms of Service</a> | <a href="Contact.php">Contact Us</a>
    </footer>

</body>
</html>
