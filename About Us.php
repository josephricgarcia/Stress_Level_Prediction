<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StressSense - About Us</title>
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
        
        .feature-card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        
        .feature-card:hover {
            background: rgba(30, 41, 59, 0.9);
            transform: translateY(-5px);
        }
        
        .btn-gradient {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            transition: all 0.3s ease;
        }
        
        .btn-gradient:hover {
            background: linear-gradient(135deg, #4338ca 0%, #6d28d9 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
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
        <div class="max-w-6xl mx-auto">
            <div class="glass-card rounded-3xl p-8">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-white mb-3">About StressSense</h1>
                    <p class="text-white/80 text-lg">Your quick way to evaluate your academic stress level through an insightful assessment.</p>
                </div>

                <div class="space-y-8">
                    <div class="feature-card rounded-2xl p-6">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-blue-500/30 rounded-xl flex items-center justify-center">
                                <i class="fas fa-users text-blue-300 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-white mb-3">Who We Are</h3>
                                <p class="text-white/80">At Stress Sense, we are dedicated to helping you understand and manage stress effectively. Our platform offers innovative tools and insights to assess stress levels and provide recommendations for improving mental well-being. We believe that stress management should be accessible to everyone, which is why our resources are easy to use, scientifically backed, and completely free to explore.</p>
                            </div>
                        </div>
                    </div>

                    <div class="feature-card rounded-2xl p-6">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-green-500/30 rounded-xl flex items-center justify-center">
                                <i class="fas fa-bullseye text-green-300 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-white mb-3">Our Mission</h3>
                                <p class="text-white/80">Our goal is to empower individuals with real-time stress analysis and actionable solutions. Stress Sense will help you take control of your mental health with good insights.</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h2 class="text-2xl font-bold text-white mb-6 text-center">Why Choose StressSense?</h2>
                        
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="feature-card rounded-2xl p-6">
                                <div class="flex items-start gap-4 mb-4">
                                    <div class="w-10 h-10 bg-blue-500/30 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-robot text-blue-300 text-lg"></i>
                                    </div>
                                    <h4 class="font-semibold text-white text-lg">AI-Powered Stress Detection</h4>
                                </div>
                                <p class="text-white/80">Using machine learning, our platform accurately assesses your stress levels and provides instant feedback to help you understand your emotional state.</p>
                            </div>
                            
                            <div class="feature-card rounded-2xl p-6">
                                <div class="flex items-start gap-4 mb-4">
                                    <div class="w-10 h-10 bg-green-500/30 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-user-cog text-green-300 text-lg"></i>
                                    </div>
                                    <h4 class="font-semibold text-white text-lg">Personalized Recommendations</h4>
                                </div>
                                <p class="text-white/80">Our system offers tailored ideas and strategies to help you manage stress effectively based on your specific situation.</p>
                            </div>
                            
                            <div class="feature-card rounded-2xl p-6">
                                <div class="flex items-start gap-4 mb-4">
                                    <div class="w-10 h-10 bg-purple-500/30 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-shield-alt text-purple-300 text-lg"></i>
                                    </div>
                                    <h4 class="font-semibold text-white text-lg">Fast and Secure Assessments</h4>
                                </div>
                                <p class="text-white/80">Your privacy is our priority. Our assessments are fast, secure, and confidential, ensuring instant results without risk to your personal data.</p>
                            </div>
                            
                            <div class="feature-card rounded-2xl p-6">
                                <div class="flex items-start gap-4 mb-4">
                                    <div class="w-10 h-10 bg-amber-500/30 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-mobile-alt text-amber-300 text-lg"></i>
                                    </div>
                                    <h4 class="font-semibold text-white text-lg">User-Friendly Interface</h4>
                                </div>
                                <p class="text-white/80">Stress management should be simple. Our intuitive interface makes it easy to analyze stress levels and access mental wellness resources.</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-blue-900/50 to-indigo-900/50 rounded-2xl p-8 border border-blue-300/20">
                        <div class="text-center">
                            <h2 class="text-2xl font-bold text-white mb-4">Take Control of Your Stress</h2>
                            <p class="text-white/80 text-lg">Begin your journey toward better mental health today. With Stress Sense, you can gain deeper insights into your stress levels, develop healthier coping mechanisms, and achieve a more balanced life. Start now—because your well-being matters.</p>
                        </div>
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