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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1e1b4b 0%, #3730a3 50%, #5b21b6 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23000000' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
            z-index: -1;
        }
        
        .glass-card {
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        }
        
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        
        @keyframes floating {
            0% { transform: translate(0, 0px); }
            50% { transform: translate(0, 10px); }
            100% { transform: translate(0, -0px); }
        }
        
        .tip-card {
            background: rgba(30, 41, 59, 0.6);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        
        .tip-card:hover {
            background: rgba(30, 41, 59, 0.8);
            transform: translateY(-8px);
            box-shadow: 0 15px 30px -8px rgba(0, 0, 0, 0.2);
        }
        
        .icon-circle {
            background: rgba(30, 41, 59, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .swiper-slide {
            display: flex;
            justify-content: center;
        }
        
        .swiper-button-next, .swiper-button-prev {
            color: #c7d2fe;
        }
        
        .swiper-button-next:hover, .swiper-button-prev:hover {
            color: white;
        }
        
        .swiper-pagination-bullet {
            background: #c7d2fe;
            opacity: 0.5;
        }
        
        .swiper-pagination-bullet-active {
            background: white;
            opacity: 1;
        }
        .nav-icon {
            width: 16px;
            text-align: center;
            margin-right: 6px;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col">
    <!-- Animated Background Elements -->
    <div class="absolute top-10 left-10 w-20 h-20 rounded-full bg-purple-900 opacity-20 floating"></div>
    <div class="absolute top-1/4 right-10 w-16 h-16 rounded-full bg-indigo-900 opacity-30 floating" style="animation-delay: 0.5s;"></div>
    <div class="absolute bottom-1/4 left-20 w-24 h-24 rounded-full bg-violet-900 opacity-20 floating" style="animation-delay: 1s;"></div>
    <div class="absolute bottom-10 right-1/4 w-12 h-12 rounded-full bg-blue-900 opacity-30 floating" style="animation-delay: 1.5s;"></div>

    <!-- HEADER -->
    <header class="py-4 px-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="relative">
                <img src="images/stresssense_logo.png" class="w-12 h-12 z-10 relative" alt="Logo">
                <div class="absolute -inset-2 bg-indigo-500/30 rounded-full blur-sm"></div>
            </div>
            <span class="text-2xl font-bold tracking-wide text-white">STRESS SENSE</span>
        </div>

        <nav class="hidden md:flex space-x-6">
            <a href="Home.php" class="text-indigo-200 hover:text-white text-sm transition flex items-center">
                <i class="fas fa-home nav-icon"></i>HOME
            </a>
            <a href="Assessment.php" class="text-indigo-200 hover:text-white text-sm transition flex items-center">
                <i class="fas fa-clipboard-list nav-icon"></i>ASSESSMENT
            </a>
            <a href="History.php" class="text-indigo-200 hover:text-white text-sm transition flex items-center">
                <i class="fas fa-history nav-icon"></i>HISTORY
            </a>
            <a href="Tips And Resources.php" class="text-white font-semibold text-sm hover:text-indigo-200 transition flex items-center">
                <i class="fas fa-lightbulb nav-icon"></i>TIPS & RESOURCES
            </a>
            <a href="Settings.php" class="text-indigo-200 hover:text-white text-sm transition flex items-center">
                <i class="fas fa-cog nav-icon"></i>SETTINGS
            </a>
        </nav>
        
        <div class="flex items-center gap-4">
            <span class="text-indigo-200 text-sm hidden md:block">Welcome, <?= htmlspecialchars($_SESSION['username'] ?? 'User') ?></span>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="flex-grow px-4 py-6">
        <div class="max-w-6xl mx-auto">

            <div class="text-center mb-10">
                <h1 class="text-3xl font-bold text-white mb-3">Stress Management Tips</h1>
                <p class="text-indigo-200 text-lg">Tailored strategies to help you feel your best</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 mb-12">
                <!-- Low Stress Card -->
                <div class="tip-card rounded-2xl p-6 border-2 border-green-600/50">
                    <div class="text-center mb-6">
                        <div class="w-20 h-20 mx-auto icon-circle rounded-full flex items-center justify-center shadow-lg">
                            <i class="fas fa-check-circle text-green-400 text-4xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-white mt-4">Low Stress</h2>
                        <div class="w-32 h-3 bg-green-800/50 rounded-full mx-auto mt-3 overflow-hidden">
                            <div class="h-3 bg-green-500 rounded-full" style="width: 30%;"></div>
                        </div>
                        <p class="text-green-300 text-md font-medium mt-2">Keep up the great work!</p>
                    </div>
                    <ul class="space-y-4 text-white text-md">
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check text-green-400 text-lg mt-1"></i>
                            <span>Exercise regularly</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check text-green-400 text-lg mt-1"></i>
                            <span>Get 7–9 hours of quality sleep</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check text-green-400 text-lg mt-1"></i>
                            <span>Practice deep breathing daily</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check text-green-400 text-lg mt-1"></i>
                            <span>Take mindful breaks</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check text-green-400 text-lg mt-1"></i>
                            <span>Stay organized & plan ahead</span>
                        </li>
                    </ul>
                </div>

                <!-- Moderate Stress Card -->
                <div class="tip-card rounded-2xl p-6 border-2 border-amber-600/50">
                    <div class="text-center mb-6">
                        <div class="w-20 h-20 mx-auto icon-circle rounded-full flex items-center justify-center shadow-lg">
                            <i class="fas fa-exclamation-triangle text-amber-400 text-4xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-white mt-4">Moderate Stress</h2>
                        <div class="w-32 h-3 bg-amber-800/50 rounded-full mx-auto mt-3 overflow-hidden">
                            <div class="h-3 bg-amber-500 rounded-full" style="width: 60%;"></div>
                        </div>
                        <p class="text-amber-300 text-md font-medium mt-2">Time to recharge</p>
                    </div>
                    <ul class="space-y-4 text-white text-md">
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check text-amber-400 text-lg mt-1"></i>
                            <span>Practice mindfulness or meditation</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check text-amber-400 text-lg mt-1"></i>
                            <span>Set healthy boundaries</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check text-amber-400 text-lg mt-1"></i>
                            <span>Schedule daily self-care</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check text-amber-400 text-lg mt-1"></i>
                            <span>Talk to someone you trust</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check text-amber-400 text-lg mt-1"></i>
                            <span>Reduce caffeine & screen time</span>
                        </li>
                    </ul>
                </div>

                <!-- High Stress Card -->
                <div class="tip-card rounded-2xl p-6 border-2 border-red-600/50">
                    <div class="text-center mb-6">
                        <div class="w-20 h-20 mx-auto icon-circle rounded-full flex items-center justify-center shadow-lg">
                            <i class="fas fa-heartbeat text-red-400 text-4xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-white mt-4">High Stress</h2>
                        <div class="w-32 h-3 bg-red-800/50 rounded-full mx-auto mt-3 overflow-hidden">
                            <div class="h-3 bg-red-500 rounded-full" style="width: 90%;"></div>
                        </div>
                        <p class="text-red-300 text-md font-medium mt-2">You are not alone</p>
                    </div>
                    <ul class="space-y-4 text-white text-md">
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check text-red-400 text-lg mt-1"></i>
                            <span>Seek professional counseling</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check text-red-400 text-lg mt-1"></i>
                            <span>Practice progressive muscle relaxation</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check text-red-400 text-lg mt-1"></i>
                            <span>Break tasks into tiny steps</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check text-red-400 text-lg mt-1"></i>
                            <span>Practice self-compassion daily</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check text-red-400 text-lg mt-1"></i>
                            <span>Prioritize sleep, nutrition & movement</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Guided Relaxation Videos -->
            <div class="glass-card rounded-3xl p-8">
                <h1 class="text-2xl font-bold text-center text-white mb-4">Guided Relaxation Videos</h1>
                <p class="text-center text-indigo-200 text-lg mb-8">Take a moment to breathe and reset</p>

                <div class="swiper mySwiper max-w-5xl mx-auto">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div class="bg-black/30 rounded-2xl overflow-hidden">
                                <iframe class="w-full h-56 md:h-64 rounded-2xl shadow-lg" src="https://www.youtube.com/embed/grfXR6FAsI8" title="5-Minute Meditation" frameborder="0" allowfullscreen></iframe>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="bg-black/30 rounded-2xl overflow-hidden">
                                <iframe class="w-full h-56 md:h-64 rounded-2xl shadow-lg" src="https://www.youtube.com/embed/L9zwduYp9G0" title="Deep Breathing" frameborder="0" allowfullscreen></iframe>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="bg-black/30 rounded-2xl overflow-hidden">
                                <iframe class="w-full h-56 md:h-64 rounded-2xl shadow-lg" src="https://www.youtube.com/embed/_2BFj-k__s0" title="Body Scan" frameborder="0" allowfullscreen></iframe>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="bg-black/30 rounded-2xl overflow-hidden">
                                <iframe class="w-full h-56 md:h-64 rounded-2xl shadow-lg" src="https://www.youtube.com/embed/CZTc8_FwHGM" title="Calm Music" frameborder="0" allowfullscreen></iframe>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="bg-black/30 rounded-2xl overflow-hidden">
                                <iframe class="w-full h-56 md:h-64 rounded-2xl shadow-lg" src="https://www.youtube.com/embed/Bk2-dKH2Ta4" title="Sleep Meditation" frameborder="0" allowfullscreen></iframe>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-pagination mt-6"></div>
                </div>
            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="py-4 text-center text-indigo-300 text-sm">
        <div class="container mx-auto px-4">
            &copy; 2025 StressSense. All Rights Reserved |
            <a href="About Us.php" class="hover:text-white mx-1 transition">About Us</a> |
            <a href="Privacy Policy.php" class="hover:text-white mx-1 transition">Privacy Policy</a> |
            <a href="Terms Of Service.php" class="hover:text-white mx-1 transition">Terms</a> |
            <a href="Contact.php" class="hover:text-white mx-1 transition">Contact</a>
        </div>
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
                768: { slidesPerView: 2, spaceBetween: 20 },
                1024: { slidesPerView: 3, spaceBetween: 25 }
            }
        });
    </script>
</body>
</html>