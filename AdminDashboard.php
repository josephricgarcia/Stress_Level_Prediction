<?php
include 'session.php';
include 'connection.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (!$dbhandle) {
    die("Database connection failed: " . mysqli_connect_error());
}

$query = "SELECT * FROM users";
$result = mysqli_query($dbhandle, $query);
if (!$result) {
    die("Error fetching users: " . mysqli_error($dbhandle));
}
$users = mysqli_fetch_all($result, MYSQLI_ASSOC);

$error_message = $_SESSION['error_message'] ?? null;
$success_message = $_SESSION['success_message'] ?? null;
unset($_SESSION['error_message'], $_SESSION['success_message']); 

if ($error_message) {
    echo "<script>alert('" . addslashes($error_message) . "');</script>";
}
if ($success_message) {
    echo "<script>alert('" . addslashes($success_message) . "');</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StresSense: Admin Dashboard</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="shortcut icon" href="images/stresssense_logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="images/stresssense_logo.png" alt="Logo"> STRESS SENSE
        </div>
        <nav>
            <a href="logout.php" class="logout-link">Logout</a>
        </nav>
    </header>

    <div class="history-content">
        <h2>Manage Users</h2>
        <?php if (empty($users)): ?>
            <p>No users found. <a href="create_user_form.php" class="add-user-link">Add New User</a></p>
        <?php else: ?>
            <table class="history-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Last Name</th>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Gender</th>
                        <th>Birthday</th>
                        <th>Contact No</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['id']); ?></td>
                            <td><?php echo htmlspecialchars($user['lname']); ?></td>
                            <td><?php echo htmlspecialchars($user['fname']); ?></td>
                            <td><?php echo htmlspecialchars($user['mname']); ?></td>
                            <td><?php
                                switch($user['gender']) {
                                    case 'm': echo 'Male'; break;
                                    case 'f': echo 'Female'; break;
                                    case 'x': echo 'Prefer not to say'; break;
                                    default: echo htmlspecialchars($user['gender']);
                                }
                            ?></td>
                            <td><?php echo htmlspecialchars($user['birthday']); ?></td>
                            <td><?php echo htmlspecialchars($user['cno']); ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['role']); ?></td>
                            <td class="actions">
                                <a href="edit_user_form.php?id=<?php echo $user['id']; ?>" class="edit-btn">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form method="POST" action="process_user.php" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                                    <button type="delete" class="delete-btn">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <a href="create_user_form.php" class="add-user-link">
                <i class="fas fa-user-plus"></i> Add New User
            </a>
        <?php endif; ?>
    </div>

    <footer>
        &copy; 2025 StressSense. All Rights Reserved. |
        <a href="AboutUs.php">About Us</a> | <a href="PrivacyPolicy.php">Privacy Policy</a> | 
        <a href="TermsOfService.php">Terms of Service</a> | <a href="Contact.php">Contact Us</a>
    </footer>

    <script>
        document.querySelector('.logout-link').addEventListener('click', function(event) {
            if (!confirm('Are you sure you want to log out?')) {
                event.preventDefault();
            }
        });
    </script>
</body>
</html>
<?php
if ($dbhandle) {
    mysqli_close($dbhandle);
}
?>