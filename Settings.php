<?php
include 'session.php';
include 'connection.php';

$user_id = $_SESSION['user_id'];
$query = "SELECT lname, fname, mname, gender, birthday, cno, username FROM users WHERE id = ?";
$stmt = mysqli_prepare($dbhandle, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

$full_name = trim($user['fname'] . ' ' . $user['mname'] . ' ' . $user['lname']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StressSense: Settings</title>
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
            <a href="History.php">HISTORY</a>
            <a href="Tips And Resources.php">TIPS AND RESOURCES</a>
            <a href="Settings.php" class="active">SETTINGS</a>
        </nav>
    </header>

    <form action="update_form.php" method="POST">
    <div class="user-details">
        <img src="images/person.png" alt="User Profile">
        <div class="user-info">
            <div class="user-row">
                <label>Name:</label>
                <span><?php echo htmlspecialchars($full_name); ?></span>
            </div>
            <div class="user-row">
                <label>Gender:</label>
                <span><?php echo htmlspecialchars($user['gender']); ?></span>
            </div>
            <div class="user-row">
                <label>Birthday:</label>
                <span><?php echo htmlspecialchars($user['birthday']); ?></span>
            </div>
            <div class="user-row">
                <label>Contact Number:</label>
                <span><?php echo htmlspecialchars($user['cno']); ?></span>
            </div>
            <div class="user-row">
                <label>Username:</label>
                <span><?php echo htmlspecialchars($user['username']); ?></span>
            </div>
        </div>
    </div>

    <div class="operation">
        <form method="POST" action="Settings.php">
            <input type="hidden" name="update_id" value="<?php echo htmlspecialchars($user_id); ?>">
            <button type="submit" class="edit-btn">Edit Account</button>
            <a href="logout.php" class="cancel-btn" id="logout-link">Logout</a>
        </form>
    </div>
</form>

<script>
    document.getElementById('logout-link').addEventListener('click', function(event) {
        if (!confirm('Are you sure you want to log out?')) {
            event.preventDefault();
        }
    });
</script>

    <footer>
        &copy; 2025 StresSense. All Rights Reserved. |
        <a href="About Us.php">About Us</a> | <a href="Privacy Policy.php">Privacy Policy</a> | <a href="Terms Of Service.php">Terms of Service</a> | <a href="Contact.php">Contact Us</a>
    </footer>

</body>
</html>
