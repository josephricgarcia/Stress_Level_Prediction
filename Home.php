<?php
include 'session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StressSense - Home</title>
    <link rel="shortcut icon" href="images/stresssense_logo.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #dbeafe, #f0f9ff);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col">

<!-- HEADER -->
<header class="bg-white/70 backdrop-blur shadow-sm py-3 px-6 flex items-center justify-between border-b">
    <div class="flex items-center gap-2">
        <img src="images/stresssense_logo.png" class="w-10 h-10" alt="Logo">
        <span class="text-xl font-semibold tracking-wide text-gray-700">STRESS SENSE</span>
    </div>

    <nav class="space-x-4">
        <a href="Home.php" class="text-blue-700 font-semibold text-sm hover:underline">HOME</a>
        <a href="Assessment.php" class="text-gray-700 hover:text-blue-700 text-sm">ASSESSMENT</a>
        <a href="History.php" class="text-gray-700 hover:text-blue-700 text-sm">HISTORY</a>
        <a href="Tips And Resources.php" class="text-gray-700 hover:text-blue-700 text-sm">TIPS & RESOURCES</a>
        <a href="Settings.php" class="text-gray-700 hover:text-blue-700 text-sm">SETTINGS</a>
    </nav>
</header>

<!-- MAIN SECTION -->
<main class="flex-grow flex items-center justify-center px-4 py-6">
    <div class="bg-white/90 backdrop-blur p-8 rounded-xl shadow-lg w-full max-w-2xl text-center border border-blue-100/70">
        <h1 class="text-2xl md:text-3xl font-bold text-blue-700 mb-3">
            "Check your stress, know your best."
        </h1>
        <p class="text-gray-600 text-sm md:text-base mb-6">
            A quick way to evaluate your academic stress level through an insightful assessment.
        </p>
        <button id="getStartedButton"
                class="bg-blue-600 text-white py-2 px-6 rounded-lg font-medium
                       hover:bg-blue-700 hover:shadow transition-all duration-200 text-sm">
            GET STARTED
        </button>
    </div>
</main>

<!-- FOOTER -->
<footer class="bg-white/80 backdrop-blur py-3 text-center text-gray-600 text-xs border-t">
    &copy; 2025 StressSense. All Rights Reserved |
    <a href="About Us.php" class="hover:underline">About Us</a> |
    <a href="Privacy Policy.php" class="hover:underline">Privacy Policy</a> |
    <a href="Terms Of Service.php" class="hover:underline">Terms</a> |
    <a href="Contact.php" class="hover:underline">Contact</a>
</footer>

<script>
    document.getElementById("getStartedButton").onclick = function () {
        window.location.href = "Assessment.php";
    };
</script>

</body>
</html>