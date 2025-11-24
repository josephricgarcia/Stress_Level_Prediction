<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StressSense - Privacy Policy</title>
    <link rel="shortcut icon" href="images/stresssense_logo.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1e1b4b 0%, #3730a3 50%, #5b21b6 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
            color: #e5e7eb;
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
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.5);
        }
        
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        
        @keyframes floating {
            0% { transform: translate(0, 0px); }
            50% { transform: translate(0, 10px); }
            100% { transform: translate(0, -0px); }
        }
        
        .policy-section {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        
        .policy-section:hover {
            background: rgba(30, 41, 59, 0.9);
            transform: translateY(-2px);
        }
        
        .nav-link {
            position: relative;
            transition: all 0.3s ease;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -5px;
            left: 0;
            background: linear-gradient(90deg, #6366f1, #8b5cf6);
            transition: width 0.3s ease;
        }
        
        .nav-link:hover::after {
            width: 100%;
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
            <a href="Tips And Resources.php" class="text-indigo-200 hover:text-white text-sm transition flex items-center">
                <i class="fas fa-lightbulb nav-icon"></i>TIPS & RESOURCES
            </a>
            <a href="Settings.php" class="text-indigo-200 hover:text-white text-sm transition flex items-center">
                <i class="fas fa-cog nav-icon"></i>SETTINGS
            </a>
        </nav>
        <div class="flex items-center gap-4">
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="flex-grow px-4 py-6">
        <div class="max-w-4xl mx-auto">
            <div class="glass-card rounded-3xl p-8">
                <div class="flex justify-between items-center mb-8">
                    <h1 class="text-3xl font-bold text-white">Privacy Policy</h1>
                    <span class="text-sm text-white/70 bg-white/10 px-4 py-2 rounded-full backdrop-blur-sm">Last Updated: February 2025</span>
                </div>
                
                <p class="text-white/80 text-lg mb-8">Welcome to Stress Sense. Your privacy is important to us. This Privacy Policy explains how we collect, use, and protect your information when you use our platform.</p>

                <div class="space-y-8">
                    <div class="policy-section rounded-2xl p-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 bg-blue-500/30 rounded-xl flex items-center justify-center">
                                <i class="fas fa-database text-blue-300 text-xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-white">1. Information We Collect</h3>
                        </div>
                        <p class="text-white/80 mb-4">We collect different types of information to enhance your experience:</p>
                        <ul class="text-white/80 space-y-3 ml-4">
                            <li class="flex items-start gap-3">
                                <i class="fas fa-circle text-blue-300 text-xs mt-2"></i>
                                <span><strong class="text-white">Personal Information:</strong> Name (if provided), Email address (only if you contact us).</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <i class="fas fa-circle text-blue-300 text-xs mt-2"></i>
                                <span><strong class="text-white">Usage Data:</strong> Device type, browser type, IP address, interaction with our platform.</span>
                            </li>
                        </ul>
                    </div>

                    <div class="policy-section rounded-2xl p-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 bg-green-500/30 rounded-xl flex items-center justify-center">
                                <i class="fas fa-cogs text-green-300 text-xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-white">2. How We Use Your Information</h3>
                        </div>
                        <p class="text-white/80 mb-4">We use collected data to:</p>
                        <ul class="text-white/80 space-y-3 ml-4">
                            <li class="flex items-start gap-3">
                                <i class="fas fa-circle text-green-300 text-xs mt-2"></i>
                                <span>Provide personalized stress assessments and recommendations.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <i class="fas fa-circle text-green-300 text-xs mt-2"></i>
                                <span>Improve the accuracy of our stress analysis tools.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <i class="fas fa-circle text-green-300 text-xs mt-2"></i>
                                <span>Enhance user experience and platform security.</span>
                            </li>
                        </ul>
                    </div>

                    <div class="policy-section rounded-2xl p-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 bg-purple-500/30 rounded-xl flex items-center justify-center">
                                <i class="fas fa-shield-alt text-purple-300 text-xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-white">3. Data Protection & Security</h3>
                        </div>
                        <p class="text-white/80 mb-4">Your privacy is our priority. We implement strong security measures such as:</p>
                        <ul class="text-white/80 space-y-3 ml-4">
                            <li class="flex items-start gap-3">
                                <i class="fas fa-circle text-purple-300 text-xs mt-2"></i>
                                <span>End-to-end encryption for sensitive data.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <i class="fas fa-circle text-purple-300 text-xs mt-2"></i>
                                <span>No permanent storage of assessment data.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <i class="fas fa-circle text-purple-300 text-xs mt-2"></i>
                                <span>Restricted access to user data.</span>
                            </li>
                        </ul>
                    </div>

                    <div class="policy-section rounded-2xl p-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 bg-amber-500/30 rounded-xl flex items-center justify-center">
                                <i class="fas fa-user-check text-amber-300 text-xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-white">4. Your Rights & Choices</h3>
                        </div>
                        <p class="text-white/80 mb-4">You have the right to:</p>
                        <ul class="text-white/80 space-y-3 ml-4">
                            <li class="flex items-start gap-3">
                                <i class="fas fa-circle text-amber-300 text-xs mt-2"></i>
                                <span>Request access to your stored data.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <i class="fas fa-circle text-amber-300 text-xs mt-2"></i>
                                <span>Delete or modify your information.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <i class="fas fa-circle text-amber-300 text-xs mt-2"></i>
                                <span>Opt out of data collection (except essential cookies).</span>
                            </li>
                        </ul>
                    </div>

                    <div class="policy-section rounded-2xl p-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 bg-red-500/30 rounded-xl flex items-center justify-center">
                                <i class="fas fa-sync-alt text-red-300 text-xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-white">5. Changes to This Privacy Policy</h3>
                        </div>
                        <p class="text-white/80">We may update this Privacy Policy as we improve Stress Sense. Any changes will be reflected on this page with a new update date.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="py-4 text-center text-white/70 text-sm">
        <div class="container mx-auto px-4">
            © 2025 StressSense. All Rights Reserved |
            <a href="About Us.php" class="hover:underline mx-1">About Us</a> |
            <a href="Privacy Policy.php" class="hover:underline mx-1">Privacy Policy</a> |
            <a href="Terms Of Service.php" class="hover:underline mx-1">Terms</a> |
            <a href="Contact.php" class="hover:underline mx-1">Contact</a>
        </div>
    </footer>

</body>
</html>