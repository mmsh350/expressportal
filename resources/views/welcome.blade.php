<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Expressportal') }} - Premium Identity Verification</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body { font-family: 'Inter', sans-serif; }
        .hero-pattern {
            background-color: #1e3a8a;
            background-image: radial-gradient(at 0% 0%, hsla(253,16%,7%,1) 0, transparent 50%), radial-gradient(at 50% 0%, hsla(225,39%,30%,1) 0, transparent 50%), radial-gradient(at 100% 0%, hsla(339,49%,30%,1) 0, transparent 50%);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased selection:bg-blue-600 selection:text-white">

    <!-- Navigation -->
    <nav class="absolute w-full z-50 top-0 transition-all duration-300 py-6 px-6 lg:px-12">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-blue-900 font-bold text-xl shadow-lg">E</div>
                <span class="text-white font-bold text-2xl tracking-tight">{{ config('app.name', 'Expressportal') }}</span>
            </div>
            
            <div class="hidden md:flex items-center gap-8 text-white/90 font-medium text-sm">
                <a href="#features" class="hover:text-white transition-colors">Features</a>
                <a href="#services" class="hover:text-white transition-colors">Services</a>
                <a href="#pricing" class="hover:text-white transition-colors">Pricing</a>
            </div>

            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ route('user.dashboard') }}" class="text-white font-medium text-sm hover:text-blue-200 transition-colors">Dashboard</a>
                @else
                    <a href="{{ route('auth.login') }}" class="text-white font-medium text-sm hover:text-blue-200 transition-colors hidden sm:block">Sign In</a>
                    <a href="{{ route('auth.register') }}" class="bg-white text-blue-900 px-6 py-2.5 rounded-full font-semibold text-sm hover:bg-blue-50 transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5">Get Started</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <main class="relative hero-pattern min-h-screen flex items-center justify-center overflow-hidden pt-20 pb-16 px-6 lg:px-8">
        <!-- Abstract Shapes -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-blue-500/20 rounded-full blur-3xl mix-blend-screen pointer-events-none"></div>
        <div class="absolute top-1/2 left-1/4 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-purple-500/20 rounded-full blur-3xl mix-blend-screen pointer-events-none"></div>

        <div class="relative max-w-5xl mx-auto text-center z-10">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass-card text-blue-100 text-xs font-medium mb-8 border border-blue-400/30">
                <span class="flex h-2 w-2 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                </span>
                Identity Verification Made Simple
            </div>
            
            <h1 class="text-5xl md:text-7xl font-extrabold text-white tracking-tight mb-8 leading-[1.1]">
                Seamless & Secure <br/>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-300 to-teal-200">Identity Processing.</span>
            </h1>
            
            <p class="text-lg md:text-xl text-blue-100/80 max-w-2xl mx-auto mb-12 font-light leading-relaxed">
                Empower your business with lightning-fast NIN, BVN, and demographic verifications. Reliable infrastructure built for the modern digital economy.
            </p>
            
            <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
                <a href="{{ route('auth.register') }}" class="w-full sm:w-auto bg-white text-blue-900 px-8 py-4 rounded-full font-bold text-base hover:bg-blue-50 transition-all shadow-[0_0_40px_rgba(255,255,255,0.3)] hover:shadow-[0_0_60px_rgba(255,255,255,0.5)] hover:-translate-y-1 flex items-center justify-center gap-2">
                    Create Free Account <i class="bi bi-arrow-right"></i>
                </a>
                <a href="#services" class="w-full sm:w-auto glass-card text-white px-8 py-4 rounded-full font-semibold text-base hover:bg-white/10 transition-all hover:-translate-y-1 flex items-center justify-center gap-2">
                    Explore Services
                </a>
            </div>

            <!-- Stats -->
            <div class="mt-20 grid grid-cols-2 md:grid-cols-4 gap-8 border-t border-white/10 pt-10">
                <div>
                    <div class="text-3xl font-bold text-white mb-1">99.9%</div>
                    <div class="text-blue-200/70 text-sm font-medium">Uptime Guarantee</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-white mb-1">&lt;1s</div>
                    <div class="text-blue-200/70 text-sm font-medium">Verification Speed</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-white mb-1">24/7</div>
                    <div class="text-blue-200/70 text-sm font-medium">Expert Support</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-white mb-1">NIMC</div>
                    <div class="text-blue-200/70 text-sm font-medium">Official Integration</div>
                </div>
            </div>
        </div>
    </main>

    <!-- Services Section -->
    <section id="services" class="py-24 bg-white relative">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-blue-600 font-semibold tracking-wide uppercase text-sm mb-3">Core Infrastructure</h2>
                <h3 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Everything you need to verify identities</h3>
                <p class="text-gray-500 text-lg">We provide a comprehensive suite of APIs and tools to ensure secure and seamless onboarding for your users.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div class="bg-gray-50 rounded-3xl p-8 border border-gray-100 hover:shadow-xl hover:border-blue-100 transition-all duration-300 group">
                    <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center text-2xl mb-6 group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">NIN Verification</h4>
                    <p class="text-gray-500 leading-relaxed mb-6">Instantly verify National Identity Numbers (NIN) with comprehensive demographic details and live image retrieval.</p>
                    <a href="{{ route('auth.register') }}" class="text-blue-600 font-semibold inline-flex items-center gap-1 hover:text-blue-700">Learn more <i class="bi bi-chevron-right text-xs"></i></a>
                </div>

                <!-- Card 2 -->
                <div class="bg-gray-50 rounded-3xl p-8 border border-gray-100 hover:shadow-xl hover:border-blue-100 transition-all duration-300 group">
                    <div class="w-14 h-14 bg-teal-100 text-teal-600 rounded-2xl flex items-center justify-center text-2xl mb-6 group-hover:scale-110 group-hover:bg-teal-600 group-hover:text-white transition-all">
                        <i class="bi bi-bank"></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">BVN Validation</h4>
                    <p class="text-gray-500 leading-relaxed mb-6">Securely validate Bank Verification Numbers to prevent fraud and ensure financial compliance for your platform.</p>
                    <a href="{{ route('auth.register') }}" class="text-teal-600 font-semibold inline-flex items-center gap-1 hover:text-teal-700">Learn more <i class="bi bi-chevron-right text-xs"></i></a>
                </div>

                <!-- Card 3 -->
                <div class="bg-gray-50 rounded-3xl p-8 border border-gray-100 hover:shadow-xl hover:border-blue-100 transition-all duration-300 group">
                    <div class="w-14 h-14 bg-purple-100 text-purple-600 rounded-2xl flex items-center justify-center text-2xl mb-6 group-hover:scale-110 group-hover:bg-purple-600 group-hover:text-white transition-all">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">IPE & Delinking</h4>
                    <p class="text-gray-500 leading-relaxed mb-6">Automated processing for Identity Profile Errors (IPE) and robust NIN delinking services tailored for scale.</p>
                    <a href="{{ route('auth.register') }}" class="text-purple-600 font-semibold inline-flex items-center gap-1 hover:text-purple-700">Learn more <i class="bi bi-chevron-right text-xs"></i></a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 py-12 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold text-sm">E</div>
                <span class="text-white font-bold text-xl tracking-tight">{{ config('app.name', 'Expressportal') }}</span>
            </div>
            
            <p class="text-gray-400 text-sm">
                &copy; {{ date('Y') }} {{ config('app.name', 'Expressportal') }}. All rights reserved.
            </p>
            
            <div class="flex gap-4">
                <a href="#" class="text-gray-400 hover:text-white transition-colors"><i class="bi bi-twitter"></i></a>
                <a href="#" class="text-gray-400 hover:text-white transition-colors"><i class="bi bi-linkedin"></i></a>
                <a href="#" class="text-gray-400 hover:text-white transition-colors"><i class="bi bi-envelope"></i></a>
            </div>
        </div>
    </footer>

</body>
</html>
