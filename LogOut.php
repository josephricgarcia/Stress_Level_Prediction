<?php
include 'connection.php';
session_start();

// Only proceed if user is logged in
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    $username = $_SESSION['username'];
    $logout_time = date('Y-m-d H:i:s');

    // Update the user_log entry with time_out
    if (isset($_SESSION['log_id'])) {
        $log_id = $_SESSION['log_id'];
        $update_stmt = mysqli_prepare($dbhandle, 
            "UPDATE user_log SET time_out = ? WHERE id = ? AND username = ?");
        mysqli_stmt_bind_param($update_stmt, "sis", $logout_time, $log_id, $username);
        mysqli_stmt_execute($update_stmt);
        mysqli_stmt_close($update_stmt);
    }
}

// Destroy session
session_unset();
session_destroy();

// Redirect to login
header("Location: LogIn.php?success=You+have+been+logged+out+successfully.");
exit();
?>