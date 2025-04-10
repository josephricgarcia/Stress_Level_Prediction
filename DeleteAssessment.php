<?php
include 'session.php';
include 'connection.php';

if (!$dbhandle) {
    die("Database connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assessment_id'])) {
    $assessmentId = intval($_POST['assessment_id']);
    $userId = $_SESSION['user_id'];

    // Verify assessment belongs to current user
    $checkQuery = "SELECT id FROM assessment WHERE id = ? AND userId = ?";
    $checkStmt = mysqli_prepare($dbhandle, $checkQuery);
    mysqli_stmt_bind_param($checkStmt, 'ii', $assessmentId, $userId);
    mysqli_stmt_execute($checkStmt);
    mysqli_stmt_store_result($checkStmt);

    if (mysqli_stmt_num_rows($checkStmt) > 0) {
        // Delete the assessment
        $deleteQuery = "DELETE FROM assessment WHERE id = ?";
        $deleteStmt = mysqli_prepare($dbhandle, $deleteQuery);
        mysqli_stmt_bind_param($deleteStmt, 'i', $assessmentId);
        
        if (mysqli_stmt_execute($deleteStmt)) {
            $_SESSION['success_message'] = "Assessment deleted successfully.";
        } else {
            $_SESSION['error_message'] = "Error deleting assessment: " . mysqli_error($dbhandle);
        }
        mysqli_stmt_close($deleteStmt);
    } else {
        $_SESSION['error_message'] = "Assessment not found or unauthorized access.";
    }

    mysqli_stmt_close($checkStmt);
} else {
    $_SESSION['error_message'] = "Invalid request.";
}

mysqli_close($dbhandle);
header("Location: History.php");
exit();
?>