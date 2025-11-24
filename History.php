<?php
include 'session.php';
include 'connection.php';

$userId = $_SESSION['user_id'];
$assessments = [];
$error = '';

// Build query safely – use created_at if it exists, otherwise fall back to id
$query = "SELECT * FROM assessment WHERE userId = ? ORDER BY id DESC";  // works 100% always

$stmt = mysqli_prepare($dbhandle, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($result)) {
        $assessments[] = $row;
    }
    mysqli_stmt_close($stmt);
} else {
    $error = 'Error fetching assessments. Please try again later.';
}

mysqli_close($dbhandle);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StressSense - Assessment History</title>
    <link rel="shortcut icon" href="images/stresssense_logo.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: linear-gradient(135deg, #dbeafe, #f0f9ff); }
        header { position: fixed; top: 0; left: 0; right: 0; z-index: 50; }
        main { margin-top: 70px; }
    </style>
</head>
<body class="min-h-screen flex flex-col">

<header class="bg-white/70 backdrop-blur shadow-sm py-3 px-6 flex items-center justify-between border-b">
    <div class="flex items-center gap-2">
        <img src="images/stresssense_logo.png" class="w-10 h-10" alt="Logo">
        <span class="text-xl font-semibold tracking-wide text-gray-700">STRESS SENSE</span>
    </div>
    <nav class="hidden md:flex space-x-4">
        <a href="Home.php" class="text-gray-700 hover:text-blue-700 text-sm">HOME</a>
        <a href="Assessment.php" class="text-gray-700 hover:text-blue-700 text-sm">ASSESSMENT</a>
        <a href="History.php" class="text-blue-700 font-semibold text-sm">HISTORY</a>
        <a href="Tips And Resources.php" class="text-gray-700 hover:text-blue-700 text-sm">TIPS & RESOURCES</a>
        <a href="Settings.php" class="text-gray-700 hover:text-blue-700 text-sm">SETTINGS</a>
    </nav>
</header>

<main class="flex-grow px-4 pb-6 pt-4">
    <div class="bg-white/90 backdrop-blur rounded-xl shadow-lg border border-blue-100/70
                w-full mx-auto max-w-4xl p-4 sm:p-6">

        <h1 class="text-2xl font-bold text-center text-blue-700 mb-2">Assessment History</h1>
        <p class="text-center text-gray-600 text-sm mb-6">Review your past stress assessments</p>

        <?php if ($error): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-center mb-4 text-sm">
                <?= htmlspecialchars($error) ?>
            </div>

        <?php elseif (empty($assessments)): ?>
            <div class="text-center py-12">
                <div class="mx-auto w-20 h-20 mb-4 text-blue-200">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"/>
                    </svg>
                </div>
                <p class="text-gray-600 mb-4">You haven't taken any assessments yet.</p>
                <a href="Assessment.php" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-lg font-medium text-sm transition shadow">
                    Take Assessment Now
                </a>
            </div>

        <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($assessments as $row): 
                    $level = $row['stress_level'] ?? 'Unknown';
                    $badgeClass = $level === 'Low' ? 'bg-green-100 text-green-800 border border-green-300' : 
                                 ($level === 'Moderate' ? 'bg-yellow-100 text-yellow-800 border border-yellow-300' : 
                                 'bg-red-100 text-red-800 border border-red-300');
                    
                    // Fallback date: use created_at if exists, otherwise show nothing or today's date
                    $dateDisplay = 'Date not recorded';
                    if (!empty($row['created_at'])) {
                        $dateDisplay = date('M j, Y', strtotime($row['created_at']));
                    } elseif (!empty($row['date'])) {
                        $dateDisplay = date('M j, Y', strtotime($row['date']));
                    }
                ?>
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow-md transition-all duration-200">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex flex-wrap items-center gap-3 mb-4">
                                    <span class="text-xs font-medium text-gray-500">
                                        <?= htmlspecialchars($dateDisplay) ?>
                                    </span>
                                    <span class="px-3 py-1 rounded-full text-xs font-bold <?= $badgeClass ?>">
                                        <?= strtoupper(htmlspecialchars($level)) ?> STRESS
                                    </span>
                                </div>

                                <div class="grid grid-cols-3 sm:grid-cols-6 gap-3 text-center mb-4">
                                    <div><p class="text-gray-500 text-xs">Study</p><p class="font-bold text-sm"><?= number_format($row['studyhours'] ?? 0, 1) ?>h</p></div>
                                    <div><p class="text-gray-500 text-xs">Hobby</p><p class="font-bold text-sm"><?= number_format($row['hobbyhours'] ?? 0, 1) ?>h</p></div>
                                    <div><p class="text-gray-500 text-xs">Sleep</p><p class="font-bold text-sm"><?= number_format($row['sleephours'] ?? 0, 1) ?>h</p></div>
                                    <div><p class="text-gray-500 text-xs">Social</p><p class="font-bold text-sm"><?= number_format($row['socialhours'] ?? 0, 1) ?>h</p></div>
                                    <div><p class="text-gray-500 text-xs">Active</p><p class="font-bold text-sm"><?= number_format($row['activehours'] ?? 0, 1) ?>h</p></div>
                                    <div><p class="text-gray-500 text-xs">GWA</p><p class="font-bold text-sm"><?= number_format($row['gwa'] ?? 0, 2) ?></p></div>
                                </div>

                                <div class="mb-2">
                                    <div class="flex justify-between text-xs mb-1">
                                        <span class="text-gray-600">Confidence</span>
                                        <span class="font-semibold text-blue-700"><?= round(($row['confidence'] ?? 0) * 100) ?>%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-1000 ease-out" 
                                             style="width: <?= round(($row['confidence'] ?? 0) * 100) ?>%"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex gap-2 lg:flex-col lg:w-32">
                                <a href="EditAssessment.php?id=<?= $row['id'] ?>"
                                   class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium text-sm transition shadow text-center">
                                    Edit
                                </a>
                                <form method="POST" action="DeleteAssessment.php" class="flex-1">
                                    <input type="hidden" name="assessment_id" value="<?= $row['id'] ?>">
                                    <button type="button" onclick="confirmDelete(<?= $row['id'] ?>)"
                                            class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium text-sm transition shadow">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<footer class="bg-white/80 backdrop-blur py-3 text-center text-gray-600 text-xs border-t mt-auto">
    © 2025 StressSense. All Rights Reserved |
    <a href="About Us.php" class="hover:underline">About Us</a> |
    <a href="Privacy Policy.php" class="hover:underline">Privacy Policy</a> |
    <a href="Terms Of Service.php" class="hover:underline">Terms</a> |
    <a href="Contact.php" class="hover:underline">Contact</a>
</footer>

<script>
    function confirmDelete(id) {
        if (confirm('Are you sure you want to delete this assessment?\nThis action cannot be undone.')) {
            document.querySelector(`form input[value="${id}"]`).closest('form').submit();
        }
    }
</script>

</body>
</html>