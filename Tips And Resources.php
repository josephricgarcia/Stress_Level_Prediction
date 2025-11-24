<?php
include 'session.php';
include 'connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StressSense - Tips & Resources</title>
    <link rel="shortcut icon" href="images/stresssense_logo.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@heroicons/heroicons@2.1.1/24/solid/index.min.js"></script>
    <style>
        body { background: linear-gradient(135deg, #dbeafe, #f0f9ff); }
        .tip-card { transition: all 0.3s ease; }
        .tip-card:hover { transform: translateY(-8px); box-shadow: 0 15px 30px -8px rgba(0, 0, 0, 0.15); }
        .icon-circle { background: linear-gradient(135deg, rgba(255,255,255,0.95), rgba(255,255,255,0.7)); backdrop-filter: blur(10px); }
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
        <a href="Tips And Resources.php" class="text-blue-700 font-semibold text-sm">TIPS & RESOURCES</a>
        <a href="Settings.php" class="text-gray-700 hover:text-blue-700 text-sm">SETTINGS</a>
    </nav>
</header>

<!-- MAIN CONTENT -->
<main class="flex-grow px-4 py-6">
    <div class="max-w-6xl mx-auto">

        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-center text-blue-700 mb-2">Stress Management Tips</h1>
            <p class="text-center text-gray-600 text-sm">Tailored strategies to help you feel your best</p>
        </div>

        <div class="grid md:grid-cols-3 gap-6 mb-12">
            <!-- Low Stress Card -->
            <div class="tip-card bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-200 rounded-xl p-6 shadow-lg">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 mx-auto icon-circle rounded-full flex items-center justify-center shadow">
                        <svg class="w-10 h-10 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-green-800 mt-4">Low Stress</h2>
                    <div class="w-24 h-2 bg-green-200 rounded-full mx-auto mt-1 overflow-hidden">
                        <div class="h-2 bg-green-600 rounded-full" style="width: 30%;"></div>
                    </div>
                    <p class="text-green-600 text-sm font-medium mt-1">Keep up the great work!</p>
                </div>
                <ul class="space-y-3 text-gray-700 text-sm">
                    <li class="flex items-start gap-2"><span class="text-green-600 text-lg">✓</span> Exercise regularly</li>
                    <li class="flex items-start gap-2"><span class="text-green-600 text-lg">✓</span> Get 7–9 hours of quality sleep</li>
                    <li class="flex items-start gap-2"><span class="text-green-600 text-lg">✓</span> Practice deep breathing daily</li>
                    <li class="flex items-start gap-2"><span class="text-green-600 text-lg">✓</span> Take mindful breaks</li>
                    <li class="flex items-start gap-2"><span class="text-green-600 text-lg">✓</span> Stay organized & plan ahead</li>
                </ul>
            </div>

            <!-- Moderate Stress Card -->
            <div class="tip-card bg-gradient-to-br from-amber-50 to-orange-50 border-2 border-amber-200 rounded-xl p-6 shadow-lg">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 mx-auto icon-circle rounded-full flex items-center justify-center shadow">
                        <svg class="w-10 h-10 text-amber-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 6.75h.008v.008H12v-.008z"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-amber-800 mt-4">Moderate Stress</h2>
                    <div class="w-24 h-2 bg-amber-200 rounded-full mx-auto mt-1 overflow-hidden">
                        <div class="h-2 bg-amber-600 rounded-full" style="width: 60%;"></div>
                    </div>
                    <p class="text-amber-600 text-sm font-medium mt-1">Time to recharge</p>
                </div>
                <ul class="space-y-3 text-gray-700 text-sm">
                    <li class="flex items-start gap-2"><span class="text-amber-600 text-lg">✓</span> Practice mindfulness or meditation</li>
                    <li class="flex items-start gap-2"><span class="text-amber-600 text-lg">✓</span> Set healthy boundaries</li>
                    <li class="flex items-start gap-2"><span class="text-amber-600 text-lg">✓</span> Schedule daily self-care</li>
                    <li class="flex items-start gap-2"><span class="text-amber-600 text-lg">✓</span> Talk to someone you trust</li>
                    <li class="flex items-start gap-2"><span class="text-amber-600 text-lg">✓</span> Reduce caffeine & screen time</li>
                </ul>
            </div>

            <!-- High Stress Card -->
            <div class="tip-card bg-gradient-to-br from-rose-50 to-red-50 border-2 border-red-200 rounded-xl p-6 shadow-lg">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 mx-auto icon-circle rounded-full flex items-center justify-center shadow">
                        <svg class="w-10 h-10 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3v.01M12 15h.01"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-red-800 mt-4">High Stress</h2>
                    <div class="w-24 h-2 bg-red-200 rounded-full mx-auto mt-1 overflow-hidden">
                        <div class="h-2 bg-red-600 rounded-full" style="width: 90%;"></div>
                    </div>
                    <p class="text-red-600 text-sm font-medium mt-1">You are not alone</p>
                </div>
                <ul class="space-y-3 text-gray-700 text-sm">
                    <li class="flex items-start gap-2"><span class="text-red-600 text-lg">✓</span> Seek professional counseling</li>
                    <li class="flex items-start gap-2"><span class="text-red-600 text-lg">✓</span> Practice progressive muscle relaxation</li>
                    <li class="flex items-start gap-2"><span class="text-red-600 text-lg">✓</span> Break tasks into tiny steps</li>
                    <li class="flex items-start gap-2"><span class="text-red-600 text-lg">✓</span> Practice self-compassion daily</li>
                    <li class="flex items-start gap-2"><span class="text-red-600 text-lg">✓</span> Prioritize sleep, nutrition & movement</li>
                </ul>
            </div>
        </div>

        <!-- Guided Relaxation Videos -->
        <div class="bg-white/90 backdrop-blur-lg rounded-xl shadow-lg p-6 border border-blue-100/70">
            <h1 class="text-xl font-bold text-center text-blue-700 mb-2">Guided Relaxation Videos</h1>
            <p class="text-center text-gray-600 text-sm mb-6">Take a moment to breathe and reset</p>

            <div class="swiper mySwiper max-w-4xl mx-auto">
                <div class="swiper-wrapper">
                    <div class="swiper-slide"><iframe class="w-full h-48 md:h-56 rounded-lg shadow" src="https://www.youtube.com/embed/grfXR6FAsI8" title="5-Minute Meditation" frameborder="0" allowfullscreen></iframe></div>
                    <div class="swiper-slide"><iframe class="w-full h-48 md:h-56 rounded-lg shadow" src="https://www.youtube.com/embed/L9zwduYp9G0" title="Deep Breathing" frameborder="0" allowfullscreen></iframe></div>
                    <div class="swiper-slide"><iframe class="w-full h-48 md:h-56 rounded-lg shadow" src="https://www.youtube.com/embed/_2BFj-k__s0" title="Body Scan" frameborder="0" allowfullscreen></iframe></div>
                    <div class="swiper-slide"><iframe class="w-full h-48 md:h-56 rounded-lg shadow" src="https://www.youtube.com/embed/CZTc8_FwHGM" title="Calm Music" frameborder="0" allowfullscreen></iframe></div>
                    <div class="swiper-slide"><iframe class="w-full h-48 md:h-56 rounded-lg shadow" src="https://www.youtube.com/embed/Bk2-dKH2Ta4" title="Sleep Meditation" frameborder="0" allowfullscreen></iframe></div>
                </div>
                <div class="swiper-button-next text-blue-600 hover:text-blue-800 scale-75"></div>
                <div class="swiper-button-prev text-blue-600 hover:text-blue-800 scale-75"></div>
                <div class="swiper-pagination mt-4"></div>
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

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    new Swiper('.mySwiper', {
        loop: true,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        breakpoints: {
            640: { slidesPerView: 1 },
            768: { slidesPerView: 2, spaceBetween: 15 },
            1024: { slidesPerView: 3, spaceBetween: 20 }
        }
    });
</script>
</body>
</html>