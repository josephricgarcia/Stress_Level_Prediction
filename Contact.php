<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StressSense - Contact Us</title>
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
        
        .contact-card {
            background: rgba(30, 41, 59, 0.6);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        
        .contact-card:hover {
            background: rgba(30, 41, 59, 0.8);
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        
        .icon-bg {
            background: rgba(30, 41, 59, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.1);
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
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-white mb-3">Contact Us</h1>
                    <p class="text-indigo-200 text-lg">We're here to help! Get in touch with us for any questions or support.</p>
                </div>

                <div class="grid md:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <div class="contact-card rounded-2xl p-6">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 icon-bg rounded-xl flex items-center justify-center">
                                    <i class="fas fa-envelope text-blue-400 text-xl"></i>
                                </div>
                                <h3 class="font-semibold text-white text-lg">Email</h3>
                            </div>
                            <p class="text-indigo-200">stresssense@gmail.com</p>
                        </div>

                        <div class="contact-card rounded-2xl p-6">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 icon-bg rounded-xl flex items-center justify-center">
                                    <i class="fas fa-globe text-green-400 text-xl"></i>
                                </div>
                                <h3 class="font-semibold text-white text-lg">Website</h3>
                            </div>
                            <p class="text-indigo-200">www.stresssense.com</p>
                        </div>
                    </div>

                    <div class="contact-card rounded-2xl p-6 bg-gradient-to-br from-blue-600/20 to-indigo-600/20 border border-blue-500/30">
                        <h3 class="font-semibold text-white text-lg mb-4">Response Time</h3>
                        <p class="text-indigo-200 mb-6">We typically respond to all inquiries within 24 hours during business days.</p>
                        
                        <div class="flex items-center gap-3 text-indigo-200">
                            <i class="fas fa-clock text-blue-400"></i>
                            <span>Monday - Friday: 9:00 AM - 6:00 PM</span>
                        </div>
                    </div>
                </div>
                
                <!-- Additional Contact Info -->
                <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="contact-card rounded-2xl p-5 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 icon-bg rounded-xl flex items-center justify-center">
                            <i class="fas fa-headset text-purple-400 text-2xl"></i>
                        </div>
                        <h4 class="font-semibold text-white mb-2">Support</h4>
                        <p class="text-indigo-200 text-sm">24/7 technical assistance</p>
                    </div>
                    
                    <div class="contact-card rounded-2xl p-5 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 icon-bg rounded-xl flex items-center justify-center">
                            <i class="fas fa-comments text-amber-400 text-2xl"></i>
                        </div>
                        <h4 class="font-semibold text-white mb-2">Live Chat</h4>
                        <p class="text-indigo-200 text-sm">Available during business hours</p>
                    </div>
                    
                    <div class="contact-card rounded-2xl p-5 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 icon-bg rounded-xl flex items-center justify-center">
                            <i class="fas fa-file-alt text-teal-400 text-2xl"></i>
                        </div>
                        <h4 class="font-semibold text-white mb-2">Documentation</h4>
                        <p class="text-indigo-200 text-sm">Comprehensive guides & FAQs</p>
                    </div>
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

</body>
</html>