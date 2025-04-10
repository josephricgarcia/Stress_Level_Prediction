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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['id']) && $_POST['action'] === 'delete') {
    $userId = intval($_POST['id']);

    // Prevent deleting the currently logged-in admin
    if ($_SESSION['user_id'] == $userId) {
        $_SESSION['error_message'] = "You cannot delete your own account.";
        header("Location: AdminDashboard.php");
        exit();
    }

    $query = "DELETE FROM users WHERE id = ?";
    $stmt = mysqli_prepare($dbhandle, $query);
    mysqli_stmt_bind_param($stmt, 'i', $userId);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success_message'] = "User deleted successfully.";
    } else {
        $_SESSION['error_message'] = "Error deleting user: " . mysqli_error($dbhandle);
    }

    mysqli_stmt_close($stmt);
    header("Location: AdminDashboard.php");
    exit();
} else {
    $_SESSION['error_message'] = "Invalid request.";
    header("Location: AdminDashboard.php");
    exit();
}

if ($dbhandle) {
    mysqli_close($dbhandle);
}
?>
