<?php
include 'session.php';
include 'connection.php';

$userId = $_SESSION['user_id'];
$assessments = [];
$error = '';

$query = "SELECT * FROM assessment WHERE userId = ?";
$stmt = mysqli_prepare($dbhandle, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $assessments[] = $row;
        }
    }
    mysqli_stmt_close($stmt);
} else {
    $error = 'Error fetching data.';
}
mysqli_close($dbhandle);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StressSense: Assessment History</title>
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
            <a href="History.php" class="active">HISTORY</a>
            <a href="Tips And Resources.php">TIPS AND RESOURCES</a>
            <a href="Settings.php">SETTINGS</a>
        </nav>
    </header>

    <div class="history-content">
        <h2>Assessment History</h2>
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php elseif (!empty($assessments)): ?>
            <table class="history-table">
                <thead>
                    <tr>
                        <th>Study Hours</th>
                        <th>Hobby Hours</th>
                        <th>Sleep Hours</th>
                        <th>Social Hours</th>
                        <th>Active Hours</th>
                        <th>GWA</th>
                        <th>Stress Level</th>
                        <th>Confidence</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($assessments as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['studyhours']); ?></td>
                        <td><?php echo htmlspecialchars($row['hobbyhours']); ?></td>
                        <td><?php echo htmlspecialchars($row['sleephours']); ?></td>
                        <td><?php echo htmlspecialchars($row['socialhours']); ?></td>
                        <td><?php echo htmlspecialchars($row['activehours']); ?></td>
                        <td><?php echo htmlspecialchars($row['gwa']); ?></td>
                        <td><?php echo htmlspecialchars($row['stress_level']); ?></td>
                        <td><?php echo round($row['confidence'] * 100, 2); ?>%</td>
                        <td class="actions">
                            <a href="EditAssessment.php?id=<?php echo $row['id']; ?>" class="edit-btn">Edit</a>
                            <form method="POST" action="DeleteAssessment.php" id="delete-form-<?php echo $row['id']; ?>">
                                <input type="hidden" name="assessment_id" value="<?php echo $row['id']; ?>">
                                <button type="button" class="delete-btn" onclick="confirmDelete(<?php echo $row['id']; ?>)">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No assessments found. <a href="Assessment.php">Create one now</a></p>
        <?php endif; ?>
    </div>

    <footer>
        &copy; 2025 StressSense. All Rights Reserved. |
        <a href="AboutUs.php">About Us</a> | <a href="PrivacyPolicy.php">Privacy Policy</a> | 
        <a href="TermsOfService.php">Terms of Service</a> | <a href="Contact.php">Contact Us</a>
    </footer>

    <script>
        function confirmDelete(assessmentId) {
            if (confirm('Are you sure you want to delete this assessment? This action cannot be undone.')) {
                document.getElementById('delete-form-' + assessmentId).submit();
            }
        }
    </script>
</body>
</html>