<?php
include 'session.php';
include 'connection.php';

$error = '';
$assessment = [];

// Check if assessment ID is provided
if (!isset($_GET['id'])) {
    header("Location: History.php");
    exit();
}
$assessmentId = $_GET['id'];
$userId = $_SESSION['user_id'];

// Fetch existing assessment data
$query = "SELECT * FROM assessment WHERE id = ? AND userId = ?";
$stmt = mysqli_prepare($dbhandle, $query);
mysqli_stmt_bind_param($stmt, "ii", $assessmentId, $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    $error = 'Assessment not found or access denied.';
} else {
    $assessment = mysqli_fetch_assoc($result);
}
mysqli_stmt_close($stmt);

// Handle form submission
if (isset($_POST['submit'])) {
    $studyhours = (float) filter_input(INPUT_POST, 'studyhours', FILTER_VALIDATE_FLOAT, ['options' => ['min_range' => 0, 'max_range' => 24]]);
    $hobbyhours = (float) filter_input(INPUT_POST, 'hobbyhours', FILTER_VALIDATE_FLOAT, ['options' => ['min_range' => 0, 'max_range' => 24]]);
    $sleephours = (float) filter_input(INPUT_POST, 'sleephours', FILTER_VALIDATE_FLOAT, ['options' => ['min_range' => 0, 'max_range' => 24]]);
    $socialhours = (float) filter_input(INPUT_POST, 'socialhours', FILTER_VALIDATE_FLOAT, ['options' => ['min_range' => 0, 'max_range' => 24]]);
    $activehours = (float) filter_input(INPUT_POST, 'activehours', FILTER_VALIDATE_FLOAT, ['options' => ['min_range' => 0, 'max_range' => 24]]);
    $gwa = (float) filter_input(INPUT_POST, 'gwa', FILTER_VALIDATE_FLOAT, ['options' => ['min_range' => 1.0, 'max_range' => 5.0]]);
    

    if ($studyhours !== false && $hobbyhours !== false && $sleephours !== false &&
        $socialhours !== false && $activehours !== false && $gwa !== false) {

        $updateQuery = "UPDATE assessment SET 
                        studyhours = ?, 
                        hobbyhours = ?, 
                        sleephours = ?, 
                        socialhours = ?, 
                        activehours = ?, 
                        gwa = ? 
                        WHERE id = ? AND userId = ?";
        $stmt = mysqli_prepare($dbhandle, $updateQuery);
        mysqli_stmt_bind_param($stmt, "ddddddis", $studyhours, $hobbyhours, $sleephours, $socialhours, $activehours, $gwa, $assessmentId, $userId);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: History.php?update=success");
            exit();
        } else {
            $error = 'Error updating assessment: ' . mysqli_error($dbhandle);
        }
        mysqli_stmt_close($stmt);
    } else {
        $error = 'Invalid input. Please check your entries.';
    }
}
mysqli_close($dbhandle);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StressSense: Edit Assessment</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="shortcut icon" href="images/stresssense_logo.png">
</head>
<body>
    <header>
        <div class="logo">
            <img src="images/stresssense_logo.png" alt="Logo"> STRESS SENSE
        </div>
        <nav>
            <a href="home.php">HOME</a>
            <a href="assessment.php">ASSESSMENT</a>
            <a href="History.php">HISTORY</a>
            <a href="TipsAndResources.php">TIPS AND RESOURCES</a>
            <a href="settings.php">SETTINGS</a>
        </nav>
    </header>

    <div class="assessment-content">
        <?php if ($error): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        
        <div class="assessment-form">
            <form method="POST" action="EditAssessment.php?id=<?php echo $assessmentId; ?>">
                <h2>Edit Assessment</h2>
                <p>Update your stress assessment details</p>

                <div class="assessment">
                    <label for="studyhours">Study Hours</label>
                    <input type="number" id="studyhours" name="studyhours" step="0.1" min="0" max="24" 
                           value="<?php echo htmlspecialchars($assessment['studyhours'] ?? ''); ?>" required>
                </div>

                <div class="assessment">
                    <label for="hobbyhours">Hobby Hours</label>
                    <input type="number" id="hobbyhours" name="hobbyhours" step="0.1" min="0" max="24" 
                           value="<?php echo htmlspecialchars($assessment['hobbyhours'] ?? ''); ?>" required>
                </div>

                <div class="assessment">
                    <label for="sleephours">Sleep Hours</label>
                    <input type="number" id="sleephours" name="sleephours" step="0.1" min="0" max="24" 
                           value="<?php echo htmlspecialchars($assessment['sleephours'] ?? ''); ?>" required>
                </div>

                <div class="assessment">
                    <label for="socialhours">Social Hours</label>
                    <input type="number" id="socialhours" name="socialhours" step="0.1" min="0" max="24" 
                           value="<?php echo htmlspecialchars($assessment['socialhours'] ?? ''); ?>" required>
                </div>

                <div class="assessment">
                    <label for="activehours">Active Hours</label>
                    <input type="number" id="activehours" name="activehours" step="0.1" min="0" max="24" 
                           value="<?php echo htmlspecialchars($assessment['activehours'] ?? ''); ?>" required>
                </div>

                <div class="assessment">
                    <label for="gwa">GWA</label>
                    <input type="number" id="gwa" name="gwa" step="0.1" min="1.0" max="5.0" 
                           value="<?php echo htmlspecialchars($assessment['gwa'] ?? ''); ?>" required>
                </div>

                <button type="submit" name="submit" class="update-btn">UPDATE ASSESSMENT</button>
            </form>
        </div>
    </div>

    <footer>
        &copy; 2025 StressSense. All Rights Reserved. |
        <a href="AboutUs.php">About Us</a> | 
        <a href="PrivacyPolicy.php">Privacy Policy</a> | 
        <a href="TermsOfService.php">Terms of Service</a> | 
        <a href="Contact.php">Contact Us</a>
    </footer>
</body>
</html>