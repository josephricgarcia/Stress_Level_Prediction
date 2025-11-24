<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StressSense - Terms of Service</title>
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
        
        .terms-section {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        
        .terms-section:hover {
            background: rgba(30, 41, 59, 0.9);
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

    <main class="flex-grow px-4 py-6">
        <div class="max-w-4xl mx-auto">
            <div class="glass-card rounded-3xl p-8">
                <div class="flex justify-between items-center mb-8">
                    <h1 class="text-3xl font-bold text-white">Terms of Service</h1>
                    <span class="text-sm text-white/70 bg-white/10 px-4 py-2 rounded-full backdrop-blur-sm">Last Updated: February 2025</span>
                </div>
                
                <p class="text-white/80 text-lg mb-8">Welcome to Stress Sense! By accessing or using our platform, you agree to the following terms and conditions. Please read them carefully.</p>

                <div class="space-y-8">
                    <div class="terms-section rounded-2xl p-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 bg-blue-500/30 rounded-xl flex items-center justify-center">
                                <i class="fas fa-check-circle text-blue-300 text-xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-white">1. Acceptance of Terms</h3>
                        </div>
                        <p class="text-white/80">By using Stress Sense, you agree to follow these Terms of Service. If you do not agree, please do not use our platform.</p>
                    </div>

                    <div class="terms-section rounded-2xl p-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 bg-green-500/30 rounded-xl flex items-center justify-center">
                                <i class="fas fa-laptop-code text-green-300 text-xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-white">2. Use of Our Services</h3>
                        </div>
                        <p class="text-white/80 mb-4">You may use Stress Sense only for lawful purposes. You agree not to:</p>
                        <ul class="text-white/80 space-y-3 ml-4">
                            <li class="flex items-start gap-3">
                                <i class="fas fa-ban text-red-400 text-xs mt-2"></i>
                                <span>Use our platform for fraudulent or harmful activities.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <i class="fas fa-ban text-red-400 text-xs mt-2"></i>
                                <span>Attempt to hack, modify, or disrupt our services.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <i class="fas fa-ban text-red-400 text-xs mt-2"></i>
                                <span>Misuse AI-powered stress analysis results.</span>
                            </li>
                        </ul>
                    </div>

                    <div class="terms-section rounded-2xl p-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 bg-purple-500/30 rounded-xl flex items-center justify-center">
                                <i class="fas fa-user-shield text-purple-300 text-xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-white">3. Privacy and Data Usage</h3>
                        </div>
                        <p class="text-white/80">We respect your privacy. Our <a href="Privacy Policy.php" class="text-blue-300 hover:underline font-medium">Privacy Policy</a> explains how we collect and use your data. By using Stress Sense, you agree to our data practices.</p>
                    </div>

                    <div class="terms-section rounded-2xl p-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 bg-amber-500/30 rounded-xl flex items-center justify-center">
                                <i class="fas fa-info-circle text-amber-300 text-xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-white">4. Accuracy of Information</h3>
                        </div>
                        <p class="text-white/80">Our assessments and recommendations are based on AI-powered analysis. However, Stress Sense is not a medical service and should not be used as a substitute for professional mental health advice.</p>
                    </div>

                    <div class="terms-section rounded-2xl p-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 bg-teal-500/30 rounded-xl flex items-center justify-center">
                                <i class="fas fa-tasks text-teal-300 text-xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-white">5. User Responsibilities</h3>
                        </div>
                        <p class="text-white/80 mb-4">By using our platform, you agree to:</p>
                        <ul class="text-white/80 space-y-3 ml-4">
                            <li class="flex items-start gap-3">
                                <i class="fas fa-check text-green-400 text-xs mt-2"></i>
                                <span>Provide accurate information when using our assessment tools.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <i class="fas fa-check text-green-400 text-xs mt-2"></i>
                                <span>Use the insights responsibly and seek professional help if needed.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <i class="fas fa-check text-green-400 text-xs mt-2"></i>
                                <span>Respect intellectual property rights and avoid unauthorized copying of our content.</span>
                            </li>
                        </ul>
                    </div>

                    <div class="terms-section rounded-2xl p-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 bg-red-500/30 rounded-xl flex items-center justify-center">
                                <i class="fas fa-exclamation-triangle text-red-300 text-xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-white">6. Limitation of Liability</h3>
                        </div>
                        <p class="text-white/80 mb-4">We strive to provide accurate and helpful tools, but we do not guarantee 100% accuracy. Stress Sense is not responsible for any:</p>
                        <ul class="text-white/80 space-y-3 ml-4">
                            <li class="flex items-start gap-3">
                                <i class="fas fa-times text-red-400 text-xs mt-2"></i>
                                <span>Errors in assessment results.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <i class="fas fa-times text-red-400 text-xs mt-2"></i>
                                <span>Technical issues or downtime.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <i class="fas fa-times text-red-400 text-xs mt-2"></i>
                                <span>Consequences of relying solely on our recommendations.</span>
                            </li>
                        </ul>
                    </div>

                    <div class="terms-section rounded-2xl p-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 bg-indigo-500/30 rounded-xl flex items-center justify-center">
                                <i class="fas fa-sync text-indigo-300 text-xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-white">7. Modifications to Terms</h3>
                        </div>
                        <p class="text-white/80">We may update these Terms of Service as we improve Stress Sense. Continued use of the platform means you accept the revised terms.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="py-4 text-center text-white/70 text-sm">
        <div class="container mx-auto px-4">
            &copy; 2025 StressSense. All Rights Reserved |
            <a href="About Us.php" class="hover:underline mx-1">About Us</a> |
            <a href="Privacy Policy.php" class="hover:underline mx-1">Privacy Policy</a> |
            <a href="Terms Of Service.php" class="hover:underline mx-1">Terms</a> |
            <a href="Contact.php" class="hover:underline mx-1">Contact</a>
        </div>
    </footer>

</body>
</html>