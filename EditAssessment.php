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
    // Input validation
    $studyhours = filter_input(INPUT_POST, 'studyhours', FILTER_VALIDATE_FLOAT, ['options' => ['min_range' => 0, 'max_range' => 24]]);
    $hobbyhours = filter_input(INPUT_POST, 'hobbyhours', FILTER_VALIDATE_FLOAT, ['options' => ['min_range' => 0, 'max_range' => 24]]);
    $sleephours = filter_input(INPUT_POST, 'sleephours', FILTER_VALIDATE_FLOAT, ['options' => ['min_range' => 0, 'max_range' => 24]]);
    $socialhours = filter_input(INPUT_POST, 'socialhours', FILTER_VALIDATE_FLOAT, ['options' => ['min_range' => 0, 'max_range' => 24]]);
    $activehours = filter_input(INPUT_POST, 'activehours', FILTER_VALIDATE_FLOAT, ['options' => ['min_range' => 0, 'max_range' => 24]]);
    $gwa = filter_input(INPUT_POST, 'gwa', FILTER_VALIDATE_FLOAT, ['options' => ['min_range' => 1.0, 'max_range' => 5.0]]);

    if ($studyhours !== false && $hobbyhours !== false && $sleephours !== false &&
        $socialhours !== false && $activehours !== false && $gwa !== false) {
        // API Call for prediction
        $apiData = json_encode([
            'studyhours' => (float)$studyhours,
            'hobbyhours' => (float)$hobbyhours,
            'sleephours' => (float)$sleephours,
            'socialhours' => (float)$socialhours,
            'activehours' => (float)$activehours,
            'gwa' => (float)$gwa
        ]);

        $ch = curl_init('http://127.0.0.1:8000/predict_stress');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => $apiData,
            CURLOPT_TIMEOUT => 10
        ]);

        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status === 200) {
            $prediction = json_decode($response, true);

            // Update assessment with new inputs and prediction
            $updateQuery = "UPDATE assessment SET 
                            studyhours = ?, 
                            hobbyhours = ?, 
                            sleephours = ?, 
                            socialhours = ?, 
                            activehours = ?, 
                            gwa = ?, 
                            stress_level = ?, 
                            confidence = ? 
                            WHERE id = ? AND userId = ?";
            $stmt = mysqli_prepare($dbhandle, $updateQuery);
            mysqli_stmt_bind_param($stmt, "ddddddssii", 
                $studyhours, 
                $hobbyhours, 
                $sleephours, 
                $socialhours, 
                $activehours, 
                $gwa, 
                $prediction['stress_level'], 
                $prediction['confidence'], 
                $assessmentId, 
                $userId
            );

            if (mysqli_stmt_execute($stmt)) {
                header("Location: History.php?update=success");
                exit();
            } else {
                $error = 'Error updating assessment: ' . mysqli_error($dbhandle);
            }
            mysqli_stmt_close($stmt);
        } else {
            $error = 'Prediction service unavailable. Try again later.';
        }
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
    <title>StressSense - Edit Assessment</title>
    <link rel="shortcut icon" href="images/stresssense_logo.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: linear-gradient(135deg, #dbeafe, #f0f9ff); }
    </style>
</head>
<body class="min-h-screen flex flex-col">

<!-- HEADER -->
<header class="bg-white/70 backdrop-blur shadow-sm py-4 px-6 flex items-center justify-between border-b">
    <div class="flex items-center gap-3">
        <img src="images/stresssense_logo.png" class="w-12 h-12" alt="Logo">
        <span class="text-2xl font-semibold tracking-wide text-gray-700">STRESS SENSE</span>
    </div>
    <nav class="space-x-6">
        <a href="Home.php" class="text-gray-700 hover:text-blue-700">HOME</a>
        <a href="Assessment.php" class="text-gray-700 hover:text-blue-700">ASSESSMENT</a>
        <a href="History.php" class="text-gray-700 hover:text-blue-700">HISTORY</a>
        <a href="Tips And Resources.php" class="text-gray-700 hover:text-blue-700">TIPS & RESOURCES</a>
        <a href="Settings.php" class="text-gray-700 hover:text-blue-700">SETTINGS</a>
    </nav>
</header>

<!-- MAIN CONTENT -->
<main class="flex-grow flex items-center justify-center px-4 py-12">
    <div class="bg-white/90 backdrop-blur p-10 rounded-3xl shadow-2xl w-full max-w-2xl border border-blue-100/70">

        <!-- EDIT ASSESSMENT FORM -->
        <h1 class="text-4xl font-bold text-center text-blue-700 mb-4">Edit Stress Assessment</h1>
        <p class="text-center text-gray-600 mb-10">Update your daily average values (hours) and current GWA.</p>

        <?php if ($error): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-center">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="EditAssessment.php?id=<?= $assessmentId ?>" method="post" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Study Hours / day</label>
                    <input type="number" step="0.1" min="0" max="24" name="studyhours" required
                           value="<?= htmlspecialchars($assessment['studyhours'] ?? '') ?>"
                           class="w-full px-4 py-3 border rounded-xl bg-gray-50 focus:ring-2 focus:ring-blue-500 transition">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Hobby Hours / day</label>
                    <input type="number" step="0.1" min="0" max="24" name="hobbyhours" required
                           value="<?= htmlspecialchars($assessment['hobbyhours'] ?? '') ?>"
                           class="w-full px-4 py-3 border rounded-xl bg-gray-50 focus:ring-2 focus:ring-blue-500 transition">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Sleep Hours / day</label>
                    <input type="number" step="0.1" min="0" max="24" name="sleephours" required
                           value="<?= htmlspecialchars($assessment['sleephours'] ?? '') ?>"
                           class="w-full px-4 py-3 border rounded-xl bg-gray-50 focus:ring-2 focus:ring-blue-500 transition">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Social Hours / day</label>
                    <input type="number" step="0.1" min="0" max="24" name="socialhours" required
                           value="<?= htmlspecialchars($assessment['socialhours'] ?? '') ?>"
                           class="w-full px-4 py-3 border rounded-xl bg-gray-50 focus:ring-2 focus:ring-blue-500 transition">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Active/Exercise Hours / day</label>
                    <input type="number" step="0.1" min="0" max="24" name="activehours" required
                           value="<?= htmlspecialchars($assessment['activehours'] ?? '') ?>"
                           class="w-full px-4 py-3 border rounded-xl bg-gray-50 focus:ring-2 focus:ring-blue-500 transition">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Current GWA</label>
                    <input type="number" step="0.01" min="1.0" max="5.0" name="gwa" required
                           value="<?= htmlspecialchars($assessment['gwa'] ?? '') ?>"
                           class="w-full px-4 py-3 border rounded-xl bg-gray-50 focus:ring-2 focus:ring-blue-500 transition">
                </div>
            </div>

            <div class="text-center mt-8">
                <button type="submit" name="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white py-4 px-12 rounded-xl text-lg font-semibold shadow-lg transition-all duration-200">
                    UPDATE ASSESSMENT
                </button>
            </div>
        </form>
    </div>
</main>

<!-- FOOTER -->
<footer class="bg-white/80 backdrop-blur py-4 text-center text-gray-600 text-sm border-t">
    &copy; 2025 StressSense. All Rights Reserved |
    <a href="About Us.php" class="hover:underline">About Us</a> |
    <a href="Privacy Policy.php" class="hover:underline">Privacy Policy</a> |
    <a href="Terms Of Service.php" class="hover:underline">Terms</a> |
    <a href="Contact.php" class="hover:underline">Contact</a>
</footer>

</body>
</html>