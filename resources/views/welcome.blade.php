<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ceylon AG | Smart Agricultural Distribution & Dealer Management Platform</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styles & Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            brand: {
                                primary: '#FFFFFF',
                                secondary: '#1E8E3E',
                                accent: '#6CC24A',
                                dark: '#1F2937',
                                light: '#F3F9F4',
                                darkgreen: '#0F4D22'
                            }
                        },
                        fontFamily: {
                            sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        }
                    }
                }
            }
        </script>
    @endif

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- GSAP & ScrollTrigger -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>

    <!-- Three.js for 3D Agriculture Scene -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #FFFFFF;
            color: #1F2937;
            overflow-x: hidden;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 20px 40px -15px rgba(30, 142, 62, 0.08);
        }

        .glass-card-dark {
            background: rgba(15, 77, 34, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(108, 194, 74, 0.2);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.3);
        }

        .gradient-text {
            background: linear-gradient(135deg, #1E8E3E 0%, #6CC24A 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .gradient-bg-hero {
            background: radial-gradient(circle at 80% 20%, rgba(108, 194, 74, 0.12) 0%, rgba(255, 255, 255, 0) 50%),
                        radial-gradient(circle at 10% 60%, rgba(30, 142, 62, 0.08) 0%, rgba(255, 255, 255, 0) 50%);
        }

        .grid-pattern {
            background-image: radial-gradient(rgba(30, 142, 62, 0.1) 1px, transparent 1px);
            background-size: 24px 24px;
        }

        .float-animation-slow {
            animation: float 6s ease-in-out infinite;
        }

        .float-animation-fast {
            animation: float 4s ease-in-out infinite alternate;
        }

        @keyframes float {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-12px) rotate(1deg); }
            100% { transform: translateY(0px) rotate(0deg); }
        }

        .pulse-glow {
            animation: pulseGlow 3s infinite alternate;
        }

        @keyframes pulseGlow {
            0% { box-shadow: 0 0 15px rgba(108, 194, 74, 0.2); }
            100% { box-shadow: 0 0 35px rgba(30, 142, 62, 0.4); }
        }
    </style>
</head>
<body class="antialiased selection:bg-emerald-500 selection:text-white" x-data="{ mobileMenuOpen: false }">

    <!-- HEADER / NAVIGATION -->
    <header class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 backdrop-blur-xl bg-white/80 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <!-- Brand Logo -->
            <a href="/" class="flex items-center gap-3 group">
                <div class="relative w-11 h-11 rounded-2xl bg-gradient-to-tr from-[#1E8E3E] to-[#6CC24A] p-0.5 shadow-md shadow-emerald-600/20 group-hover:scale-105 transition-transform duration-300">
                    <div class="w-full h-full bg-white rounded-[14px] flex items-center justify-center overflow-hidden">
                        @if(file_exists(public_path('images/logo.png')))
                            <img src="{{ asset('images/logo.png') }}" alt="Ceylon AG" class="w-8 h-8 object-contain">
                        @else
                            <svg class="w-6 h-6 text-[#1E8E3E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                        @endif
                    </div>
                </div>
                <div class="flex flex-col">
                    <span class="text-xl font-extrabold tracking-tight text-gray-900 group-hover:text-[#1E8E3E] transition-colors">Ceylon AG</span>
                    <span class="text-[10px] font-semibold uppercase tracking-widest text-[#1E8E3E]">AgriTech Platform</span>
                </div>
            </a>

            <!-- Desktop Nav Links -->
            <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-gray-600">
                <a href="#features" class="hover:text-[#1E8E3E] transition-colors">Features</a>
                <a href="#why-choose" class="hover:text-[#1E8E3E] transition-colors">Why Ceylon AG</a>
                <a href="#workflow" class="hover:text-[#1E8E3E] transition-colors">Workflow</a>
                <a href="#contact" class="hover:text-[#1E8E3E] transition-colors">Contact</a>
            </nav>

            <!-- Authentication Actions -->
            <div class="hidden md:flex items-center gap-3">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center px-6 py-2.5 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-[#1E8E3E] to-[#6CC24A] hover:opacity-95 transition-all shadow-lg shadow-emerald-700/20 hover:shadow-emerald-700/30 hover:-translate-y-0.5">
                            Dashboard
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:text-[#1E8E3E] hover:bg-emerald-50/60 transition-all">
                            Log in
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-[#1E8E3E] hover:bg-[#0F4D22] transition-all shadow-md shadow-emerald-700/20 hover:-translate-y-0.5">
                                Register
                            </a>
                        @endif
                    @endauth
                @endif
            </div>

            <!-- Mobile Hamburger Button -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 rounded-xl text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="md:hidden bg-white/95 border-b border-gray-200 px-4 pt-2 pb-6 space-y-3">
            <a href="#features" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-base font-semibold text-gray-700 hover:bg-emerald-50 hover:text-[#1E8E3E]">Features</a>
            <a href="#why-choose" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-base font-semibold text-gray-700 hover:bg-emerald-50 hover:text-[#1E8E3E]">Why Ceylon AG</a>
            <a href="#workflow" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-base font-semibold text-gray-700 hover:bg-emerald-50 hover:text-[#1E8E3E]">Workflow</a>
            <a href="#contact" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-base font-semibold text-gray-700 hover:bg-emerald-50 hover:text-[#1E8E3E]">Contact</a>
            
            <div class="pt-4 border-t border-gray-100 flex flex-col gap-2">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="w-full text-center py-3 rounded-xl text-base font-bold text-white bg-[#1E8E3E]">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="w-full text-center py-2.5 rounded-xl text-base font-semibold text-gray-700 border border-gray-200">
                            Log in
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="w-full text-center py-2.5 rounded-xl text-base font-bold text-white bg-[#1E8E3E]">
                                Register
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </header>

    <!-- HERO SECTION -->
    <section class="relative pt-32 pb-20 md:pt-44 md:pb-32 gradient-bg-hero grid-pattern overflow-hidden">
        <!-- Ambient Glowing Background Orbs -->
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-[600px] h-[600px] bg-gradient-to-tr from-emerald-300/20 to-green-400/20 blur-3xl rounded-full pointer-events-none -z-10"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-12 gap-12 lg:gap-8 items-center">

                <!-- Left Content Column -->
                <div class="lg:col-span-7 space-y-8 hero-text-animate">
                    <!-- Badge -->
                    <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full glass-card border-emerald-200/60 text-xs font-bold text-[#1E8E3E] shadow-sm">
                        <span class="flex h-2 w-2 rounded-full bg-[#6CC24A] animate-ping"></span>
                        <span>🌱 Smart AgriTech Distribution & Dealer Platform</span>
                    </div>

                    <!-- Headline -->
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-gray-900 tracking-tight leading-[1.12]">
                        Empowering Sri Lankan <br class="hidden sm:inline">
                        <span class="gradient-text">Agriculture</span> Through Innovation
                    </h1>

                    <!-- Sub Heading -->
                    <p class="text-lg sm:text-xl font-bold text-gray-800">
                        Smart Agricultural Product Distribution & Dealer Management Platform
                    </p>

                    <!-- Description -->
                    <p class="text-base sm:text-lg text-gray-600 max-w-2xl leading-relaxed">
                        Manage clients, assign products, monitor sales, track payments, and grow your agricultural distribution business efficiently with next-gen automation.
                    </p>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap items-center gap-4 pt-2">
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-8 py-4 rounded-2xl text-base font-bold text-white bg-gradient-to-r from-[#1E8E3E] to-[#6CC24A] hover:opacity-95 transition-all shadow-xl shadow-emerald-700/25 hover:shadow-emerald-700/40 hover:-translate-y-1 flex items-center gap-3">
                                <span>Get Started Now</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </a>
                        @endif

                        <a href="{{ route('login') }}" class="px-7 py-4 rounded-2xl text-base font-bold text-gray-800 glass-card hover:bg-white transition-all hover:shadow-lg hover:-translate-y-0.5 border border-gray-200">
                            Log In to Account
                        </a>
                    </div>

                    <!-- Trust Micro Metrics -->
                    <div class="pt-6 border-t border-gray-200/80 flex items-center gap-8 text-sm text-gray-500 font-medium">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#1E8E3E]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span>100% Verified Dealers</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#1E8E3E]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span>Real-Time Reports</span>
                        </div>
                    </div>
                </div>

                <!-- Right 3D Scene Column -->
                <div class="lg:col-span-5 relative flex justify-center items-center min-h-[420px] hero-3d-container">
                    <!-- 3D Canvas -->
                    <div id="three-hero-canvas" class="w-full h-[450px] rounded-3xl overflow-hidden cursor-grab active:cursor-grabbing"></div>

                    <!-- Floating Glassmorphic Badges on Top of 3D Scene -->
                    <div class="absolute top-4 left-2 sm:-left-6 glass-card p-4 rounded-2xl shadow-xl float-animation-slow flex items-center gap-3 border border-white/80">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 text-[#1E8E3E] flex items-center justify-center font-bold text-lg">
                            🌾
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 font-semibold">Active Dealer Network</div>
                            <div class="text-sm font-extrabold text-gray-900">100+ Approved Dealers</div>
                        </div>
                    </div>

                    <div class="absolute bottom-6 right-2 sm:-right-6 glass-card p-4 rounded-2xl shadow-xl float-animation-fast flex items-center gap-3 border border-white/80">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-bold text-lg">
                            ⚡
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 font-semibold">Distribution Growth</div>
                            <div class="text-sm font-extrabold text-[#1E8E3E]">+34% Yield Increase</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- FEATURES SECTION -->
    <section id="features" class="py-24 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-4">
                <span class="text-xs font-bold uppercase tracking-widest text-[#1E8E3E] bg-emerald-50 px-3 py-1.5 rounded-full">Comprehensive Platform</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight">
                    Engineered for Modern Agriculture Distribution
                </h2>
                <p class="text-base sm:text-lg text-gray-600">
                    Everything you need to streamline client approval, product assignment, order tracking, and payment processing.
                </p>
            </div>

            <!-- 8 Grid Cards -->
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">

                <!-- Feature 1 -->
                <div class="feature-card p-8 rounded-3xl bg-slate-50/60 border border-gray-100 hover:bg-white hover:border-emerald-200 hover:shadow-xl hover:shadow-emerald-900/5 transition-all duration-300 group">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-100/80 text-[#1E8E3E] flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-[#1E8E3E] group-hover:text-white transition-all duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-[#1E8E3E] transition-colors">Client Management</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Verify dealer profiles, manage NIC documentation, and enforce strict admin approval controls seamlessly.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="feature-card p-8 rounded-3xl bg-slate-50/60 border border-gray-100 hover:bg-white hover:border-emerald-200 hover:shadow-xl hover:shadow-emerald-900/5 transition-all duration-300 group">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-100/80 text-[#1E8E3E] flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-[#1E8E3E] group-hover:text-white transition-all duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-[#1E8E3E] transition-colors">Inventory Management</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Assign product catalogs, monitor stock requests, and track agricultural inventory in real time.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="feature-card p-8 rounded-3xl bg-slate-50/60 border border-gray-100 hover:bg-white hover:border-emerald-200 hover:shadow-xl hover:shadow-emerald-900/5 transition-all duration-300 group">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-100/80 text-[#1E8E3E] flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-[#1E8E3E] group-hover:text-white transition-all duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-[#1E8E3E] transition-colors">Quotation System</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Generate branded PDF price quotations instantly for client inquiries with dynamic discount pricing.
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="feature-card p-8 rounded-3xl bg-slate-50/60 border border-gray-100 hover:bg-white hover:border-emerald-200 hover:shadow-xl hover:shadow-emerald-900/5 transition-all duration-300 group">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-100/80 text-[#1E8E3E] flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-[#1E8E3E] group-hover:text-white transition-all duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-[#1E8E3E] transition-colors">Email Notifications</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Queued Hostinger SMTP system sending automated HTML alerts for orders, approvals, and receipts.
                    </p>
                </div>

                <!-- Feature 5 -->
                <div class="feature-card p-8 rounded-3xl bg-slate-50/60 border border-gray-100 hover:bg-white hover:border-emerald-200 hover:shadow-xl hover:shadow-emerald-900/5 transition-all duration-300 group">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-100/80 text-[#1E8E3E] flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-[#1E8E3E] group-hover:text-white transition-all duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-[#1E8E3E] transition-colors">Payment Tracking</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Verify bank transfer receipts, manage payment verifications, and maintain credit histories effortlessly.
                    </p>
                </div>

                <!-- Feature 6 -->
                <div class="feature-card p-8 rounded-3xl bg-slate-50/60 border border-gray-100 hover:bg-white hover:border-emerald-200 hover:shadow-xl hover:shadow-emerald-900/5 transition-all duration-300 group">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-100/80 text-[#1E8E3E] flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-[#1E8E3E] group-hover:text-white transition-all duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-[#1E8E3E] transition-colors">Reports & Analytics</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Comprehensive district-wise sales insights, performance metrics, and dealer growth analytics.
                    </p>
                </div>

                <!-- Feature 7 -->
                <div class="feature-card p-8 rounded-3xl bg-slate-50/60 border border-gray-100 hover:bg-white hover:border-emerald-200 hover:shadow-xl hover:shadow-emerald-900/5 transition-all duration-300 group">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-100/80 text-[#1E8E3E] flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-[#1E8E3E] group-hover:text-white transition-all duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-[#1E8E3E] transition-colors">Role-Based Access</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Tailored portals for Super Admin, Admin, Sales Representatives (Refs), and Dealer Clients.
                    </p>
                </div>

                <!-- Feature 8 -->
                <div class="feature-card p-8 rounded-3xl bg-slate-50/60 border border-gray-100 hover:bg-white hover:border-emerald-200 hover:shadow-xl hover:shadow-emerald-900/5 transition-all duration-300 group">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-100/80 text-[#1E8E3E] flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-[#1E8E3E] group-hover:text-white transition-all duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-[#1E8E3E] transition-colors">Secure Verification</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Signed URL email verification and approval security gates protecting system integrity.
                    </p>
                </div>

            </div>
        </div>
    </section>

    <!-- WHY CHOOSE CEYLON AG (STATS SECTION) -->
    <section id="why-choose" class="py-24 bg-[#0F4D22] text-white relative overflow-hidden">
        <!-- Subtle Pattern Overlay -->
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#6CC24A_1px,transparent_1px)] [background-size:16px_16px]"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-4">
                <span class="text-xs font-bold uppercase tracking-widest text-[#6CC24A] bg-white/10 px-3 py-1.5 rounded-full backdrop-blur-md">Trusted Performance</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight">
                    Why Choose Ceylon AG?
                </h2>
                <p class="text-base sm:text-lg text-emerald-100/80">
                    Empowering Sri Lanka's agricultural supply chain with proven reliability and speed.
                </p>
            </div>

            <!-- Animated Stats Grid -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center" x-data="{
                clients: 0,
                orders: 0,
                security: 0,
                startCounters() {
                    let duration = 2000;
                    let steps = 60;
                    let stepTime = duration / steps;
                    let cTarget = 100, oTarget = 500, sTarget = 99;
                    let currentStep = 0;
                    let timer = setInterval(() => {
                        currentStep++;
                        this.clients = Math.min(Math.round((cTarget / steps) * currentStep), cTarget);
                        this.orders = Math.min(Math.round((oTarget / steps) * currentStep), oTarget);
                        this.security = Math.min(Math.round((sTarget / steps) * currentStep), sTarget);
                        if (currentStep >= steps) clearInterval(timer);
                    }, stepTime);
                }
            }" x-intersect.once="startCounters()">
                
                <div class="p-8 rounded-3xl glass-card-dark border-emerald-500/30">
                    <div class="text-4xl sm:text-5xl font-extrabold text-[#6CC24A] mb-2 font-mono">
                        <span x-text="clients">100</span>+
                    </div>
                    <div class="text-sm sm:text-base font-semibold text-emerald-100">Clients & Dealers</div>
                    <p class="text-xs text-emerald-200/60 mt-1">Verified Partner Network</p>
                </div>

                <div class="p-8 rounded-3xl glass-card-dark border-emerald-500/30">
                    <div class="text-4xl sm:text-5xl font-extrabold text-[#6CC24A] mb-2 font-mono">
                        <span x-text="orders">500</span>+
                    </div>
                    <div class="text-sm sm:text-base font-semibold text-emerald-100">Orders Processed</div>
                    <p class="text-xs text-emerald-200/60 mt-1">Seamless Distribution</p>
                </div>

                <div class="p-8 rounded-3xl glass-card-dark border-emerald-500/30">
                    <div class="text-4xl sm:text-5xl font-extrabold text-[#6CC24A] mb-2 font-mono">
                        <span x-text="security">99</span>%
                    </div>
                    <div class="text-sm sm:text-base font-semibold text-emerald-100">Secure Operation</div>
                    <p class="text-xs text-emerald-200/60 mt-1">Encrypted Sessions & Roles</p>
                </div>

                <div class="p-8 rounded-3xl glass-card-dark border-emerald-500/30">
                    <div class="text-4xl sm:text-5xl font-extrabold text-[#6CC24A] mb-2 font-mono">
                        24/7
                    </div>
                    <div class="text-sm sm:text-base font-semibold text-emerald-100">System Support</div>
                    <p class="text-xs text-emerald-200/60 mt-1">Dedicated Assistance</p>
                </div>

            </div>
        </div>
    </section>

    <!-- WORKFLOW SECTION -->
    <section id="workflow" class="py-24 bg-slate-50/50 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-4">
                <span class="text-xs font-bold uppercase tracking-widest text-[#1E8E3E] bg-emerald-50 px-3 py-1.5 rounded-full">Step-By-Step Journey</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight">
                    How Ceylon AG Works
                </h2>
                <p class="text-base sm:text-lg text-gray-600">
                    A streamlined, secure process from account registration to sales fulfillment and reporting.
                </p>
            </div>

            <!-- Workflow Steps -->
            <div class="grid sm:grid-cols-2 lg:grid-cols-6 gap-6 relative">

                <!-- Step 1 -->
                <div class="workflow-card bg-white p-6 rounded-3xl border border-gray-100 shadow-lg shadow-gray-100 hover:-translate-y-1 transition-all duration-300 relative group">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-[#1E8E3E] font-extrabold flex items-center justify-center mb-4 group-hover:bg-[#1E8E3E] group-hover:text-white transition-colors">
                        01
                    </div>
                    <h4 class="text-lg font-bold text-gray-900 mb-2">Register</h4>
                    <p class="text-xs text-gray-500 leading-relaxed">
                        Dealers create their profile with business details.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="workflow-card bg-white p-6 rounded-3xl border border-gray-100 shadow-lg shadow-gray-100 hover:-translate-y-1 transition-all duration-300 relative group">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-[#1E8E3E] font-extrabold flex items-center justify-center mb-4 group-hover:bg-[#1E8E3E] group-hover:text-white transition-colors">
                        02
                    </div>
                    <h4 class="text-lg font-bold text-gray-900 mb-2">Approval</h4>
                    <p class="text-xs text-gray-500 leading-relaxed">
                        Admin reviews profile & verifies security status.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="workflow-card bg-white p-6 rounded-3xl border border-gray-100 shadow-lg shadow-gray-100 hover:-translate-y-1 transition-all duration-300 relative group">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-[#1E8E3E] font-extrabold flex items-center justify-center mb-4 group-hover:bg-[#1E8E3E] group-hover:text-white transition-colors">
                        03
                    </div>
                    <h4 class="text-lg font-bold text-gray-900 mb-2">Assignment</h4>
                    <p class="text-xs text-gray-500 leading-relaxed">
                        Products & pricing allocated to approved client.
                    </p>
                </div>

                <!-- Step 4 -->
                <div class="workflow-card bg-white p-6 rounded-3xl border border-gray-100 shadow-lg shadow-gray-100 hover:-translate-y-1 transition-all duration-300 relative group">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-[#1E8E3E] font-extrabold flex items-center justify-center mb-4 group-hover:bg-[#1E8E3E] group-hover:text-white transition-colors">
                        04
                    </div>
                    <h4 class="text-lg font-bold text-gray-900 mb-2">Sales</h4>
                    <p class="text-xs text-gray-500 leading-relaxed">
                        Dealers request quotations & place stock orders.
                    </p>
                </div>

                <!-- Step 5 -->
                <div class="workflow-card bg-white p-6 rounded-3xl border border-gray-100 shadow-lg shadow-gray-100 hover:-translate-y-1 transition-all duration-300 relative group">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-[#1E8E3E] font-extrabold flex items-center justify-center mb-4 group-hover:bg-[#1E8E3E] group-hover:text-white transition-colors">
                        05
                    </div>
                    <h4 class="text-lg font-bold text-gray-900 mb-2">Payments</h4>
                    <p class="text-xs text-gray-500 leading-relaxed">
                        Slips uploaded & verified with instant receipts.
                    </p>
                </div>

                <!-- Step 6 -->
                <div class="workflow-card bg-white p-6 rounded-3xl border border-gray-100 shadow-lg shadow-gray-100 hover:-translate-y-1 transition-all duration-300 relative group">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-[#1E8E3E] font-extrabold flex items-center justify-center mb-4 group-hover:bg-[#1E8E3E] group-hover:text-white transition-colors">
                        06
                    </div>
                    <h4 class="text-lg font-bold text-gray-900 mb-2">Reports</h4>
                    <p class="text-xs text-gray-500 leading-relaxed">
                        Real-time analytics & business growth reports.
                    </p>
                </div>

            </div>
        </div>
    </section>

    <!-- CALL TO ACTION (CTA) SECTION -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative rounded-3xl bg-gradient-to-r from-[#0F4D22] via-[#1E8E3E] to-[#0F4D22] text-white p-10 sm:p-16 overflow-hidden shadow-2xl shadow-emerald-950/20">
                <!-- Decorative Glow Blur Orbs -->
                <div class="absolute -top-24 -right-24 w-96 h-96 bg-[#6CC24A]/30 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-emerald-400/20 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10 max-w-3xl space-y-6">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 text-xs font-bold text-[#6CC24A] backdrop-blur-md">
                        <span>🚀 Next-Generation AgriTech</span>
                    </div>

                    <h2 class="text-3xl sm:text-5xl font-extrabold tracking-tight leading-tight">
                        Ready to Grow Your Agriculture Business?
                    </h2>

                    <p class="text-base sm:text-xl text-emerald-100/90 leading-relaxed">
                        Join Sri Lanka's leading agricultural distribution network today. Register your dealer account or log in to manage your orders.
                    </p>

                    <div class="flex flex-wrap items-center gap-4 pt-4">
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-8 py-4 rounded-2xl text-base font-bold text-[#0F4D22] bg-white hover:bg-emerald-50 transition-all shadow-xl hover:-translate-y-0.5">
                                Register Dealer Account
                            </a>
                        @endif

                        <a href="{{ route('login') }}" class="px-8 py-4 rounded-2xl text-base font-bold text-white bg-white/10 hover:bg-white/20 backdrop-blur-md transition-all border border-white/20">
                            Log In to Portal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer id="contact" class="bg-gray-900 text-gray-400 py-16 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 mb-12">

                <!-- Company Info -->
                <div class="space-y-4 md:col-span-1">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-[#1E8E3E] to-[#6CC24A] flex items-center justify-center text-white font-bold">
                            🌱
                        </div>
                        <span class="text-xl font-extrabold text-white">Ceylon AG</span>
                    </div>
                    <p class="text-xs leading-relaxed text-gray-400">
                        Ceylon Agro Marketing (Pvt) Ltd.<br>
                        Smart Agricultural Product Distribution & Dealer Management Platform.
                    </p>
                </div>

                <!-- Navigation Links -->
                <div>
                    <h5 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Navigation</h5>
                    <ul class="space-y-2.5 text-xs font-medium">
                        <li><a href="#features" class="hover:text-emerald-400 transition-colors">Features</a></li>
                        <li><a href="#why-choose" class="hover:text-emerald-400 transition-colors">Why Choose Us</a></li>
                        <li><a href="#workflow" class="hover:text-emerald-400 transition-colors">Workflow</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-emerald-400 transition-colors">Dealer Portal</a></li>
                    </ul>
                </div>

                <!-- Legal Links -->
                <div>
                    <h5 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Legal & Policy</h5>
                    <ul class="space-y-2.5 text-xs font-medium">
                        <li><a href="#" class="hover:text-emerald-400 transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-emerald-400 transition-colors">Terms & Conditions</a></li>
                        <li><a href="#" class="hover:text-emerald-400 transition-colors">Security Standards</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h5 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Contact Us</h5>
                    <ul class="space-y-2.5 text-xs text-gray-400">
                        <li class="flex items-center gap-2">
                            <span>📍</span>
                            <span>Colombo, Sri Lanka</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span>✉️</span>
                            <a href="mailto:info@ceylonagromarketing.lk" class="hover:text-emerald-400 transition-colors">info@ceylonagromarketing.lk</a>
                        </li>
                        <li class="flex items-center gap-2">
                            <span>📞</span>
                            <span>+94 (0) 11 234 5678</span>
                        </li>
                    </ul>
                </div>

            </div>

            <div class="pt-8 border-t border-gray-800/80 flex flex-col sm:flex-row items-center justify-between text-xs text-gray-500 gap-4">
                <div>
                    © {{ date('Y') }} Ceylon Agro Marketing (Pvt) Ltd. All rights reserved.
                </div>
                <div class="flex items-center gap-6">
                    <span class="text-emerald-500 font-semibold">AgriTech Solution v2.0</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- GSAP & Three.js JavaScript Initialization -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // 1. GSAP Scroll Animations
            gsap.registerPlugin(ScrollTrigger);

            gsap.from('.hero-text-animate > *', {
                duration: 1,
                y: 30,
                opacity: 0,
                stagger: 0.15,
                ease: 'power3.out'
            });

            gsap.from('.feature-card', {
                scrollTrigger: {
                    trigger: '#features',
                    start: 'top 80%'
                },
                duration: 0.8,
                y: 40,
                opacity: 0,
                stagger: 0.1,
                ease: 'power3.out'
            });

            gsap.from('.workflow-card', {
                scrollTrigger: {
                    trigger: '#workflow',
                    start: 'top 80%'
                },
                duration: 0.8,
                y: 40,
                opacity: 0,
                stagger: 0.1,
                ease: 'power3.out'
            });

            // 2. Three.js 3D Agricultural Scene
            initThreeHeroScene();
        });

        function initThreeHeroScene() {
            const container = document.getElementById('three-hero-canvas');
            if (!container || typeof THREE === 'undefined') return;

            const width = container.clientWidth;
            const height = container.clientHeight;

            const scene = new THREE.Scene();
            const camera = new THREE.PerspectiveCamera(45, width / height, 0.1, 1000);
            camera.position.z = 6;

            const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
            renderer.setSize(width, height);
            renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
            container.appendChild(renderer.domElement);

            // Lighting
            const ambientLight = new THREE.AmbientLight(0xffffff, 0.8);
            scene.add(ambientLight);

            const directionalLight = new THREE.DirectionalLight(0x6CC24A, 1.2);
            directionalLight.position.set(5, 5, 5);
            scene.add(directionalLight);

            // Create 3D Agricultural Wireframe Globe
            const globeGeo = new THREE.IcosahedronGeometry(2, 2);
            const globeMat = new THREE.MeshPhongMaterial({
                color: 0x1E8E3E,
                wireframe: true,
                transparent: true,
                opacity: 0.45,
            });
            const globe = new THREE.Mesh(globeGeo, globeMat);
            scene.add(globe);

            // Create Inner Core Leaf-Green Sphere
            const coreGeo = new THREE.SphereGeometry(1.2, 16, 16);
            const coreMat = new THREE.MeshPhongMaterial({
                color: 0x6CC24A,
                emissive: 0x0F4D22,
                shininess: 30,
                transparent: true,
                opacity: 0.8
            });
            const coreSphere = new THREE.Mesh(coreGeo, coreMat);
            scene.add(coreSphere);

            // Create Orbiting 3D Crop/Leaf Floating Particles
            const particleCount = 40;
            const particleGeo = new THREE.TetrahedronGeometry(0.12, 0);
            const particleMat = new THREE.MeshStandardMaterial({
                color: 0x6CC24A,
                roughness: 0.3
            });

            const particles = [];
            for (let i = 0; i < particleCount; i++) {
                const particle = new THREE.Mesh(particleGeo, particleMat);
                const radius = 2.4 + Math.random() * 0.8;
                const theta = Math.random() * Math.PI * 2;
                const phi = Math.acos((Math.random() * 2) - 1);

                particle.position.x = radius * Math.sin(phi) * Math.cos(theta);
                particle.position.y = radius * Math.sin(phi) * Math.sin(theta);
                particle.position.z = radius * Math.cos(phi);

                particle.userData = {
                    speed: 0.005 + Math.random() * 0.01,
                    axis: new THREE.Vector3(Math.random(), Math.random(), Math.random()).normalize()
                };

                scene.add(particle);
                particles.push(particle);
            }

            // Mouse Move Interaction
            let mouseX = 0, mouseY = 0;
            window.addEventListener('mousemove', (e) => {
                mouseX = (e.clientX / window.innerWidth - 0.5) * 0.5;
                mouseY = (e.clientY / window.innerHeight - 0.5) * 0.5;
            });

            // Render Loop
            function animate() {
                requestAnimationFrame(animate);

                globe.rotation.y += 0.003;
                globe.rotation.x += 0.001;

                coreSphere.rotation.y -= 0.002;

                // Rotate Particles
                particles.forEach(p => {
                    p.position.applyAxisAngle(p.userData.axis, p.userData.speed);
                    p.rotation.x += 0.01;
                    p.rotation.y += 0.01;
                });

                // Smooth camera follow mouse
                camera.position.x += (mouseX - camera.position.x) * 0.05;
                camera.position.y += (-mouseY - camera.position.y) * 0.05;
                camera.lookAt(scene.position);

                renderer.render(scene, camera);
            }

            animate();

            // Resize Handler
            window.addEventListener('resize', () => {
                const newWidth = container.clientWidth;
                const newHeight = container.clientHeight;
                camera.aspect = newWidth / newHeight;
                camera.updateProjectionMatrix();
                renderer.setSize(newWidth, newHeight);
            });
        }
    </script>
</body>
</html>
