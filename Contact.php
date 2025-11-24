<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StressSense - Contact Us</title>
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
        <a href="Home.php" class="text-gray-700 hover:text-blue-700 text-sm">HOME</a>
        <a href="Assessment.php" class="text-gray-700 hover:text-blue-700 text-sm">ASSESSMENT</a>
        <a href="History.php" class="text-gray-700 hover:text-blue-700 text-sm">HISTORY</a>
        <a href="Tips And Resources.php" class="text-gray-700 hover:text-blue-700 text-sm">TIPS & RESOURCES</a>
        <a href="Settings.php" class="text-gray-700 hover:text-blue-700 text-sm">SETTINGS</a>
    </nav>
</header>

<!-- MAIN CONTENT -->
<main class="flex-grow px-4 py-6">
    <div class="max-w-6xl mx-auto flex flex-col md:flex-row gap-6">

        <!-- CONTENT -->
        <div class="bg-white/90 backdrop-blur rounded-xl shadow-lg border border-blue-100/70 p-6 flex-1">
            <h1 class="text-2xl font-bold text-blue-700 mb-4">Contact Us</h1>
            <p class="text-gray-700 mb-6 text-sm">We're here to help! Get in touch with us for any questions or support.</p>

            <div class="grid md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="bg-blue-50 rounded-lg p-4">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-blue-700">Email</h3>
                        </div>
                        <p class="text-gray-700 text-sm">stresssense@gmail.com</p>
                    </div>

                    <div class="bg-green-50 rounded-lg p-4">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9a9 9 0 00-9 9"/>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-green-700">Website</h3>
                        </div>
                        <p class="text-gray-700 text-sm">www.stresssense.com</p>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-200">
                    <h3 class="font-semibold text-blue-700 mb-3">Response Time</h3>
                    <p class="text-gray-700 text-sm mb-4">We typically respond to all inquiries within 24 hours during business days.</p>
                    
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Monday - Friday: 9:00 AM - 6:00 PM</span>
                    </div>
                </div>
            </div>
        </div>
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

</body>
</html>