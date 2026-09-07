<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ceylon AG PESTO | 100% Organic Pest Control Spray — Made in Sri Lanka</title>
    <meta name="description" content="Ceylon AG PESTO is a 100% organic, eco-friendly pest repellent spray (200ml) manufactured in Sri Lanka for healthy, clean, and comfortable spaces.">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

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
                                primary: '#1E8E3E',
                                dark: '#0F4D22',
                                accent: '#6CC24A',
                                light: '#F3F9F4',
                                text: '#1F2937'
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

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #FAFCF8;
            color: #1F2937;
            overflow-x: hidden;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(229, 231, 235, 0.85);
            box-shadow: 0 20px 40px -15px rgba(30, 142, 62, 0.07);
        }

        .gradient-text {
            background: linear-gradient(135deg, #1E8E3E 0%, #0F4D22 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .gradient-bg-hero {
            background: radial-gradient(circle at 85% 15%, rgba(108, 194, 74, 0.12) 0%, rgba(250, 252, 248, 0) 55%),
                        radial-gradient(circle at 15% 65%, rgba(30, 142, 62, 0.08) 0%, rgba(250, 252, 248, 0) 50%);
        }

        .grid-pattern {
            background-image: radial-gradient(rgba(30, 142, 62, 0.07) 1.5px, transparent 1.5px);
            background-size: 28px 28px;
        }
    </style>
</head>
<body class="antialiased selection:bg-[#1E8E3E] selection:text-white" x-data="{ mobileMenuOpen: false }">

    @php
        $pestoProduct = null;
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('products')) {
                $pestoProduct = \App\Models\Product::where('name', 'like', '%pesto%')->first() 
                    ?? \App\Models\Product::first();
            }
        } catch (\Throwable $e) {
            $pestoProduct = null;
        }
        $pestoPrice = $pestoProduct ? number_format($pestoProduct->selling_price, 2) : '1,890.00';
        $pestoSku = $pestoProduct->sku ?? 'CAM-01';
        $pestoImage = ($pestoProduct && $pestoProduct->image_path && file_exists(storage_path('app/public/' . $pestoProduct->image_path)))
            ? asset('storage/' . $pestoProduct->image_path)
            : (file_exists(public_path('storage/products/k6jRXhGW5vMqWo6TnoJ7LdC4zsBljSUcjWX3oQR0.jpg'))
                ? asset('storage/products/k6jRXhGW5vMqWo6TnoJ7LdC4zsBljSUcjWX3oQR0.jpg')
                : asset('images/logo.png'));
    @endphp

    <!-- ========================================================================= -->
    <!-- MAIN NAVBAR / HEADER -->
    <!-- ========================================================================= -->
    <header class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 backdrop-blur-xl bg-white/95 border-b border-gray-100 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            
            <!-- Official Ceylon AG Brand Logo -->
            <a href="/" class="flex items-center gap-3 group" id="brand-logo-link">
                <div class="w-12 h-12 rounded-2xl bg-white p-1 shadow-md shadow-emerald-700/15 group-hover:scale-105 transition-transform duration-300 border border-emerald-100 flex items-center justify-center overflow-hidden shrink-0">
                    <img src="{{ asset('images/logo.png') }}" alt="Ceylon AG Official Logo" class="w-full h-full object-contain">
                </div>
                <div class="flex flex-col">
                    <span class="text-xl font-black tracking-tight text-gray-900 group-hover:text-[#1E8E3E] transition-colors leading-tight">Ceylon AG</span>
                    <span class="text-[10px] font-black uppercase tracking-wider text-[#1E8E3E]">PESTO &bull; 100% Organic</span>
                </div>
            </a>

            <!-- Desktop Nav Links -->
            <nav class="hidden lg:flex items-center gap-7 text-xs font-bold text-gray-700">
                <a href="#overview" class="hover:text-[#1E8E3E] transition-colors">Overview</a>
                <a href="#why-choose" class="hover:text-[#1E8E3E] transition-colors">Why Choose PESTO</a>
                <a href="#why-sell" class="hover:text-[#1E8E3E] transition-colors">Why Sell PESTO</a>
                <a href="#benefits" class="hover:text-[#1E8E3E] transition-colors">Key Benefits</a>
                <a href="#suitable-for" class="hover:text-[#1E8E3E] transition-colors">Who It's For</a>
                <a href="#contact" class="hover:text-[#1E8E3E] transition-colors">Contact</a>
            </nav>

            <!-- Authentication / Portal Actions -->
            <div class="hidden sm:flex items-center gap-3">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-xs font-extrabold text-white bg-gradient-to-r from-[#1E8E3E] to-[#0F4D22] hover:opacity-95 transition-all shadow-md shadow-emerald-700/20 hover:-translate-y-0.5">
                            My Portal
                            <svg class="w-3.5 h-3.5 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2.5 rounded-xl text-xs font-bold text-gray-700 hover:text-[#1E8E3E] hover:bg-emerald-50/70 transition-all border border-transparent hover:border-emerald-200">
                            Client & Dealer Log in
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-xs font-black text-white bg-[#1E8E3E] hover:bg-[#0F4D22] transition-all shadow-md shadow-emerald-700/20 hover:-translate-y-0.5">
                                Register Account
                            </a>
                        @endif
                    @endauth
                @endif
            </div>

            <!-- Mobile Hamburger Toggle -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 rounded-xl text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none" aria-label="Toggle navigation" id="mobile-menu-btn">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Mobile Drawer Menu -->
        <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="lg:hidden bg-white border-b border-gray-200 px-4 pt-2 pb-6 space-y-2.5 shadow-xl">
            <a href="#overview" @click="mobileMenuOpen = false" class="block px-3 py-2.5 rounded-xl text-sm font-bold text-gray-700 hover:bg-emerald-50 hover:text-[#1E8E3E]">Overview</a>
            <a href="#why-choose" @click="mobileMenuOpen = false" class="block px-3 py-2.5 rounded-xl text-sm font-bold text-gray-700 hover:bg-emerald-50 hover:text-[#1E8E3E]">Why Choose PESTO</a>
            <a href="#why-sell" @click="mobileMenuOpen = false" class="block px-3 py-2.5 rounded-xl text-sm font-bold text-gray-700 hover:bg-emerald-50 hover:text-[#1E8E3E]">Why Sell PESTO</a>
            <a href="#benefits" @click="mobileMenuOpen = false" class="block px-3 py-2.5 rounded-xl text-sm font-bold text-gray-700 hover:bg-emerald-50 hover:text-[#1E8E3E]">Key Benefits</a>
            <a href="#suitable-for" @click="mobileMenuOpen = false" class="block px-3 py-2.5 rounded-xl text-sm font-bold text-gray-700 hover:bg-emerald-50 hover:text-[#1E8E3E]">Who It's For</a>
            <a href="#contact" @click="mobileMenuOpen = false" class="block px-3 py-2.5 rounded-xl text-sm font-bold text-gray-700 hover:bg-emerald-50 hover:text-[#1E8E3E]">Contact</a>
            
            <div class="pt-4 border-t border-gray-100 flex flex-col gap-2">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="w-full text-center py-3 rounded-xl text-xs font-black text-white bg-[#1E8E3E]">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="w-full text-center py-2.5 rounded-xl text-xs font-bold text-gray-700 border border-gray-200">
                            Client & Dealer Log in
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="w-full text-center py-2.5 rounded-xl text-xs font-black text-white bg-[#1E8E3E]">
                                Register Account
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </header>

    <!-- ========================================================================= -->
    <!-- 1. HERO SECTION: CEYLON AG PESTO SHOWCASE -->
    <!-- ========================================================================= -->
    <section id="overview" class="relative pt-28 pb-16 md:pt-40 md:pb-24 gradient-bg-hero grid-pattern overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-12 gap-10 lg:gap-12 items-center">

                <!-- Left Column: Product Focus & Marketing Narrative -->
                <div class="lg:col-span-7 space-y-6 text-left">
                    <!-- Trust Pill -->
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-100/90 border border-emerald-200 text-xs font-extrabold text-[#1E8E3E]">
                        <span class="w-2 h-2 rounded-full bg-[#1E8E3E] animate-pulse"></span>
                        <span>Ceylon AG Official Innovation &bull; 100% Organic</span>
                    </div>

                    <!-- Main Headline -->
                    <div>
                        <span class="text-xs font-black tracking-widest text-[#1E8E3E] uppercase block mb-1">Naturally Protective &bull; Made in Sri Lanka</span>
                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-gray-900 tracking-tight leading-[1.1]">
                            CEYLON AG <br>
                            <span class="gradient-text">PESTO</span>
                        </h1>
                        <p class="text-lg sm:text-xl font-bold text-gray-800 mt-2">
                            100% Organic Pest Control Spray &bull; 200ML
                        </p>
                    </div>

                    <!-- Narrative Description -->
                    <p class="text-sm sm:text-base text-gray-600 leading-relaxed max-w-xl">
                        Manufactured in Sri Lanka, <strong>Ceylon AG PESTO</strong> delivers practical, plant-friendly defense powered by organic botanical ingredients. Specially formulated for everyday pest-control needs across home gardens, indoor areas, nurseries, and agricultural plots.
                    </p>

                    <!-- Feature Highlights Matrix -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                        <div class="flex items-center gap-3 p-3.5 rounded-2xl bg-white border border-gray-100 shadow-xs">
                            <div class="w-8 h-8 rounded-xl bg-emerald-100 text-[#1E8E3E] flex items-center justify-center font-black text-xs shrink-0">
                                ✓
                            </div>
                            <div class="text-xs">
                                <strong class="text-gray-900 block font-extrabold">100% Organic Formula</strong>
                                <span class="text-gray-500">Pure plant-derived protection</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-3.5 rounded-2xl bg-white border border-gray-100 shadow-xs">
                            <div class="w-8 h-8 rounded-xl bg-emerald-100 text-[#1E8E3E] flex items-center justify-center font-black text-xs shrink-0">
                                🇱🇰
                            </div>
                            <div class="text-xs">
                                <strong class="text-gray-900 block font-extrabold">Made in Sri Lanka</strong>
                                <span class="text-gray-500">Manufactured for local conditions</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-3.5 rounded-2xl bg-white border border-gray-100 shadow-xs">
                            <div class="w-8 h-8 rounded-xl bg-emerald-100 text-[#1E8E3E] flex items-center justify-center font-black text-xs shrink-0">
                                🛡️
                            </div>
                            <div class="text-xs">
                                <strong class="text-gray-900 block font-extrabold">Practical Pest Control</strong>
                                <span class="text-gray-500">Helps protect against common pests</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-3.5 rounded-2xl bg-white border border-gray-100 shadow-xs">
                            <div class="w-8 h-8 rounded-xl bg-emerald-100 text-[#1E8E3E] flex items-center justify-center font-black text-xs shrink-0">
                                💧
                            </div>
                            <div class="text-xs">
                                <strong class="text-gray-900 block font-extrabold">Ready-to-Use 200ML</strong>
                                <span class="text-gray-500">Convenient precision spray bottle</span>
                            </div>
                        </div>
                    </div>

                    <!-- Clean Retail Price & Actions (NO Stock Quantities or Internal Pricing) -->
                    <div class="pt-4 border-t border-gray-200/80 flex flex-col sm:flex-row sm:items-center gap-5">
                        <div class="pr-5 sm:border-r border-gray-200">
                            <span class="text-[10px] uppercase tracking-wider font-extrabold text-gray-400 block">Retail Price</span>
                            <span class="text-2xl sm:text-3xl font-black text-[#1E8E3E]">LKR {{ $pestoPrice }}</span>
                            <span class="text-[11px] text-gray-500 font-medium block mt-0.5">Per 200ml bottle &bull; SKU: {{ $pestoSku }}</span>
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-6 py-3.5 rounded-2xl text-xs font-black text-white bg-gradient-to-r from-[#1E8E3E] to-[#0F4D22] hover:opacity-95 transition-all shadow-lg shadow-emerald-700/20 hover:-translate-y-0.5 flex items-center gap-2">
                                    <span>Become a Dealer</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>
                            @endif

                            <a href="#contact" class="px-5 py-3.5 rounded-2xl text-xs font-bold text-gray-800 bg-white hover:bg-gray-50 transition-all border border-gray-200 shadow-xs">
                                Inquire / Contact
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Right Column: High-Fidelity PESTO Bottle Visual Showcase -->
                <div class="lg:col-span-5 flex justify-center items-center">
                    <div class="relative w-full max-w-sm sm:max-w-md">
                        <!-- Glow Aura -->
                        <div class="absolute inset-0 bg-gradient-to-tr from-emerald-500/20 via-green-400/10 to-transparent rounded-3xl blur-2xl -z-10 transform scale-95"></div>

                        <!-- Card Presentation -->
                        <div class="glass-card rounded-3xl p-6 sm:p-8 text-center space-y-4 shadow-xl border border-white/90">
                            <div class="relative mx-auto rounded-2xl overflow-hidden bg-gradient-to-b from-gray-50 via-white to-emerald-50/40 p-4 border border-emerald-100 flex items-center justify-center min-h-[380px]">
                                <img 
                                    src="{{ $pestoImage }}" 
                                    alt="Ceylon AG PESTO 100% Organic 200ml Spray Bottle" 
                                    class="max-h-[360px] sm:max-h-[420px] w-auto object-contain drop-shadow-2xl transition-transform duration-500 hover:scale-105"
                                >
                                <div class="absolute top-3 right-3 px-3 py-1 bg-[#1E8E3E] text-white font-black text-[10px] uppercase rounded-full tracking-wider shadow-md">
                                    PESTO &bull; 200ML
                                </div>
                            </div>

                            <div class="pt-2 border-t border-gray-100 flex items-center justify-between text-left">
                                <div>
                                    <h3 class="text-sm font-black text-gray-900">Ceylon AG PESTO</h3>
                                    <span class="text-[11px] text-gray-500 font-medium">100% Organic Pest Repellent Spray</span>
                                </div>
                                <span class="px-3 py-1 bg-emerald-100 text-[#1E8E3E] text-[10px] font-black uppercase rounded-full">
                                    Made in Sri Lanka
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 2. PESTO INTRODUCTION SECTION -->
    <!-- ========================================================================= -->
    <section class="py-16 bg-white border-y border-gray-100">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
            <span class="text-xs font-black uppercase tracking-widest text-[#1E8E3E] bg-emerald-50 px-3.5 py-1.5 rounded-full inline-block">
                Product Introduction
            </span>
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-gray-900 tracking-tight">
                Everyday Plant Defense, Naturally Made
            </h2>
            <p class="text-sm sm:text-base text-gray-600 leading-relaxed max-w-3xl mx-auto">
                <strong>Ceylon AG PESTO</strong> is designed to solve a fundamental need: keeping plants, gardens, and crop beds protected from nuisance pests without turning to harsh, unpleasant synthetic chemicals. Engineered with botanical essences, it is ready to spray immediately with no mixing, measuring, or special protective suits needed.
            </p>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 3. WHY CHOOSE PESTO? SECTION (CUSTOMER / USER VALUE) -->
    <!-- ========================================================================= -->
    <section id="why-choose" class="py-16 sm:py-24 bg-[#FAFCF8]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-14 space-y-3">
                <span class="text-xs font-black uppercase tracking-widest text-[#1E8E3E] bg-emerald-100/80 px-3.5 py-1.5 rounded-full inline-block">
                    For Homes, Farms & Businesses
                </span>
                <h2 class="text-3xl sm:text-4xl font-black text-gray-900 tracking-tight">
                    Why Choose PESTO?
                </h2>
                <p class="text-sm sm:text-base text-gray-600 leading-relaxed">
                    Designed for everyday life, Ceylon AG PESTO delivers reliable pest defense that you can feel comfortable using around living and working environments.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <!-- Pillar 1: Effective pest control solution -->
                <div class="p-7 rounded-3xl bg-white border border-gray-100 hover:border-emerald-300 hover:shadow-lg transition-all duration-300 space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-[#1E8E3E] flex items-center justify-center font-black text-xl">
                        🎯
                    </div>
                    <h3 class="text-lg font-black text-gray-900">Effective Pest Control Solution</h3>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        Formulated to deter common garden and agricultural pests effectively. Provides dependable protection for your valuable greenery and plants.
                    </p>
                </div>

                <!-- Pillar 2: Convenient and easy to use -->
                <div class="p-7 rounded-3xl bg-white border border-gray-100 hover:border-emerald-300 hover:shadow-lg transition-all duration-300 space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-[#1E8E3E] flex items-center justify-center font-black text-xl">
                        ⚡
                    </div>
                    <h3 class="text-lg font-black text-gray-900">Convenient and Easy to Use</h3>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        Supplied in a pre-mixed, ergonomic 200ml spray bottle. No dilution, measuring cups, or complicated preparations required—just shake and spray.
                    </p>
                </div>

                <!-- Pillar 3: Suitable for everyday pest-control needs -->
                <div class="p-7 rounded-3xl bg-white border border-gray-100 hover:border-emerald-300 hover:shadow-lg transition-all duration-300 space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-[#1E8E3E] flex items-center justify-center font-black text-xl">
                        📅
                    </div>
                    <h3 class="text-lg font-black text-gray-900">Suitable for Everyday Needs</h3>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        Ideal for regular maintenance routines in home gardens, balcony planters, potted greenery, greenhouses, and smallholding crops.
                    </p>
                </div>

                <!-- Pillar 4: Helps maintain cleaner, comfortable spaces -->
                <div class="p-7 rounded-3xl bg-white border border-gray-100 hover:border-emerald-300 hover:shadow-lg transition-all duration-300 space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-[#1E8E3E] flex items-center justify-center font-black text-xl">
                        🌿
                    </div>
                    <h3 class="text-lg font-black text-gray-900">Cleaner, Comfortable Spaces</h3>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        Helps maintain a pleasant and tidy environment around indoor plants, patios, and landscaping without the harsh odors of synthetic chemicals.
                    </p>
                </div>

                <!-- Pillar 5: A practical product for homes and businesses -->
                <div class="p-7 rounded-3xl bg-white border border-gray-100 hover:border-emerald-300 hover:shadow-lg transition-all duration-300 space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-[#1E8E3E] flex items-center justify-center font-black text-xl">
                        🏡
                    </div>
                    <h3 class="text-lg font-black text-gray-900">Practical for Homes & Businesses</h3>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        Whether caring for residential houseplants or maintaining commercial landscape beds, PESTO offers a practical, versatile solution.
                    </p>
                </div>

                <!-- Pillar 6: 100% Organic & Locally Formulated -->
                <div class="p-7 rounded-3xl bg-emerald-50/70 border border-emerald-200 hover:shadow-lg transition-all duration-300 space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-[#1E8E3E] text-white flex items-center justify-center font-black text-xl">
                        🇱🇰
                    </div>
                    <h3 class="text-lg font-black text-emerald-950">Formulated in Sri Lanka</h3>
                    <p class="text-xs text-emerald-800 leading-relaxed">
                        Engineered specifically by Ceylon AG to address local tropical climate dynamics, seasonal plant care, and everyday pest challenges.
                    </p>
                </div>

            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 4. WHY SELL PESTO? SECTION (RETAILER & DEALER VALUE) -->
    <!-- ========================================================================= -->
    <section id="why-sell" class="py-16 sm:py-24 bg-white border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-14 space-y-3">
                <span class="text-xs font-black uppercase tracking-widest text-[#1E8E3E] bg-emerald-100/80 px-3.5 py-1.5 rounded-full inline-block">
                    For Retailers, Dealers & Distributors
                </span>
                <h2 class="text-3xl sm:text-4xl font-black text-gray-900 tracking-tight">
                    Why Sell PESTO?
                </h2>
                <p class="text-sm sm:text-base text-gray-600 leading-relaxed">
                    A valuable, high-demand addition to your store shelves. Here is why agricultural merchants, hardware retailers, and supermarkets choose to stock Ceylon AG PESTO.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <!-- Business Point 1 -->
                <div class="p-8 rounded-3xl bg-slate-50 border border-slate-200/80 hover:border-emerald-300 hover:bg-emerald-50/20 transition-all duration-300 space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center font-black text-lg">
                        01
                    </div>
                    <h3 class="text-xl font-black text-gray-900">In-Demand Customer Solution</h3>
                    <p class="text-xs sm:text-sm text-gray-600 leading-relaxed">
                        A useful product customers are actively looking for. Today's consumers and growers increasingly seek organic, practical pest deterrents that are simple to apply.
                    </p>
                </div>

                <!-- Business Point 2 -->
                <div class="p-8 rounded-3xl bg-slate-50 border border-slate-200/80 hover:border-emerald-300 hover:bg-emerald-50/20 transition-all duration-300 space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center font-black text-lg">
                        02
                    </div>
                    <h3 class="text-xl font-black text-gray-900">Easy to Introduce to Customers</h3>
                    <p class="text-xs sm:text-sm text-gray-600 leading-relaxed">
                        Clear, straightforward value proposition. The ready-to-use 200ml format requires minimal sales explanation, making it an effortless recommendation at the counter.
                    </p>
                </div>

                <!-- Business Point 3 -->
                <div class="p-8 rounded-3xl bg-slate-50 border border-slate-200/80 hover:border-emerald-300 hover:bg-emerald-50/20 transition-all duration-300 space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center font-black text-lg">
                        03
                    </div>
                    <h3 class="text-xl font-black text-gray-900">Wide Customer Range</h3>
                    <p class="text-xs sm:text-sm text-gray-600 leading-relaxed">
                        Appeals across diverse shopper segments: urban homeowners, weekend gardeners, commercial nurseries, farm managers, and institutional landscapers.
                    </p>
                </div>

                <!-- Business Point 4 -->
                <div class="p-8 rounded-3xl bg-slate-50 border border-slate-200/80 hover:border-emerald-300 hover:bg-emerald-50/20 transition-all duration-300 space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center font-black text-lg">
                        04
                    </div>
                    <h3 class="text-xl font-black text-gray-900">Strong Everyday Use Case</h3>
                    <p class="text-xs sm:text-sm text-gray-600 leading-relaxed">
                        Pest control is not a one-off event. Regular plant upkeep drives steady, recurring customer visits and dependable seasonal reorders.
                    </p>
                </div>

                <!-- Business Point 5 -->
                <div class="p-8 rounded-3xl bg-slate-50 border border-slate-200/80 hover:border-emerald-300 hover:bg-emerald-50/20 transition-all duration-300 space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center font-black text-lg">
                        05
                    </div>
                    <h3 class="text-xl font-black text-gray-900">Adds Value to Product Range</h3>
                    <p class="text-xs sm:text-sm text-gray-600 leading-relaxed">
                        Complements fertilizers, seeds, and gardening tools. Elevates your business's reputation as a progressive supplier of modern, organic agro solutions.
                    </p>
                </div>

                <!-- Business Point 6: Dedicated Rep & System Support -->
                <div class="p-8 rounded-3xl bg-slate-50 border border-slate-200/80 hover:border-emerald-300 hover:bg-emerald-50/20 transition-all duration-300 space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center font-black text-lg">
                        06
                    </div>
                    <h3 class="text-xl font-black text-gray-900">Supported by Ceylon AG</h3>
                    <p class="text-xs sm:text-sm text-gray-600 leading-relaxed">
                        Backed by assigned Sales Representatives (Refs) across all districts of Sri Lanka, seamless ordering, digital quotations, and fast delivery.
                    </p>
                </div>

            </div>

            <!-- Dealer CTA Banner -->
            <div class="mt-12 p-6 sm:p-8 rounded-3xl bg-emerald-50/80 border border-emerald-200 flex flex-col sm:flex-row items-center justify-between gap-6 text-center sm:text-left">
                <div>
                    <h4 class="text-lg font-black text-emerald-950">Interested in stocking Ceylon AG PESTO?</h4>
                    <p class="text-xs sm:text-sm text-emerald-800 mt-1">Register a dealership account or connect with an assigned Sales Representative.</p>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-6 py-3 rounded-xl text-xs font-black text-white bg-[#1E8E3E] hover:bg-[#0F4D22] transition-all shadow-md">
                            Register as a Dealer
                        </a>
                    @endif
                    <a href="#contact" class="px-5 py-3 rounded-xl text-xs font-bold text-emerald-900 bg-white hover:bg-emerald-100 transition-all border border-emerald-200">
                        Inquire Now
                    </a>
                </div>
            </div>

        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 5. KEY PRODUCT SPECIFICATIONS & APPLICATION -->
    <!-- ========================================================================= -->
    <section id="benefits" class="py-16 sm:py-24 bg-[#FAFCF8] border-t border-gray-100">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 space-y-3">
                <span class="text-xs font-black uppercase tracking-widest text-[#1E8E3E]">Product Overview</span>
                <h2 class="text-3xl sm:text-4xl font-black text-gray-900 tracking-tight">Product Specifications</h2>
                <p class="text-xs sm:text-sm text-gray-500">Official product metadata for Ceylon AG PESTO</p>
            </div>

            <div class="bg-white rounded-3xl border border-gray-200 overflow-hidden shadow-sm divide-y divide-gray-100">
                <div class="p-4 sm:p-5 flex items-center justify-between text-xs sm:text-sm">
                    <span class="font-extrabold text-gray-500 uppercase text-[11px]">Product Name</span>
                    <strong class="font-black text-gray-900">Ceylon AG PESTO</strong>
                </div>
                <div class="p-4 sm:p-5 flex items-center justify-between text-xs sm:text-sm">
                    <span class="font-extrabold text-gray-500 uppercase text-[11px]">Product Classification</span>
                    <span class="font-bold text-gray-800">100% Organic Pest Control / Repellent Spray</span>
                </div>
                <div class="p-4 sm:p-5 flex items-center justify-between text-xs sm:text-sm">
                    <span class="font-extrabold text-gray-500 uppercase text-[11px]">Net Volume</span>
                    <span class="font-bold text-gray-800">200 ML Pre-mixed Bottle</span>
                </div>
                <div class="p-4 sm:p-5 flex items-center justify-between text-xs sm:text-sm">
                    <span class="font-extrabold text-gray-500 uppercase text-[11px]">Product SKU</span>
                    <span class="font-bold text-gray-800">{{ $pestoSku }}</span>
                </div>
                <div class="p-4 sm:p-5 flex items-center justify-between text-xs sm:text-sm">
                    <span class="font-extrabold text-gray-500 uppercase text-[11px]">Country of Origin</span>
                    <strong class="font-black text-[#1E8E3E]">Manufactured in Sri Lanka</strong>
                </div>
                <div class="p-4 sm:p-5 flex items-center justify-between text-xs sm:text-sm">
                    <span class="font-extrabold text-gray-500 uppercase text-[11px]">Consumer Retail Price</span>
                    <strong class="font-black text-[#1E8E3E] text-base">LKR {{ $pestoPrice }}</strong>
                </div>
                <div class="p-4 sm:p-5 flex items-center justify-between text-xs sm:text-sm">
                    <span class="font-extrabold text-gray-500 uppercase text-[11px]">Application Method</span>
                    <span class="font-bold text-gray-800">Direct foliar spray; shake well before each application</span>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 6. SUITABLE CUSTOMER & BUSINESS TYPES -->
    <!-- ========================================================================= -->
    <section id="suitable-for" class="py-16 sm:py-24 bg-white border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-14 space-y-3">
                <span class="text-xs font-black uppercase tracking-widest text-[#1E8E3E] bg-emerald-50 px-3.5 py-1.5 rounded-full inline-block">
                    Versatile Protection
                </span>
                <h2 class="text-3xl sm:text-4xl font-black text-gray-900 tracking-tight">
                    Who Is Ceylon AG PESTO For?
                </h2>
                <p class="text-sm sm:text-base text-gray-600 leading-relaxed">
                    From home gardeners to commercial retailers, PESTO is designed to fit naturally into various workflows.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <div class="p-6 rounded-3xl bg-slate-50 border border-gray-200/70 space-y-3">
                    <div class="text-2xl">🏡</div>
                    <h3 class="text-base font-extrabold text-gray-900">Home Gardeners</h3>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        For anyone nurturing indoor plants, vegetable patches, flowering beds, or urban balcony gardens.
                    </p>
                </div>

                <div class="p-6 rounded-3xl bg-slate-50 border border-gray-200/70 space-y-3">
                    <div class="text-2xl">🏬</div>
                    <h3 class="text-base font-extrabold text-gray-900">Retailers & Hardware</h3>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        Agrochemical shops, hardware stores, plant shops, and supermarkets wanting an organic shelf-ready spray.
                    </p>
                </div>

                <div class="p-6 rounded-3xl bg-slate-50 border border-gray-200/70 space-y-3">
                    <div class="text-2xl">🌱</div>
                    <h3 class="text-base font-extrabold text-gray-900">Commercial Nurseries</h3>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        Plant nurseries and landscape contractors protecting sensitive seedlings and ornamental foliage.
                    </p>
                </div>

                <div class="p-6 rounded-3xl bg-slate-50 border border-gray-200/70 space-y-3">
                    <div class="text-2xl">🌾</div>
                    <h3 class="text-base font-extrabold text-gray-900">Smallholders & Farms</h3>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        Growers seeking a convenient organic botanical deterrent for routine horticultural upkeep.
                    </p>
                </div>

            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 7. CALL TO ACTION & CONTACT SECTION -->
    <!-- ========================================================================= -->
    <section id="contact" class="py-16 sm:py-24 bg-[#0F4D22] text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-12 gap-12 items-center">
                
                <!-- Left: Call to Action Details -->
                <div class="lg:col-span-7 space-y-6">
                    <div class="w-14 h-14 rounded-2xl bg-white p-1.5 shadow-xl flex items-center justify-center overflow-hidden">
                        <img src="{{ asset('images/logo.png') }}" alt="Ceylon AG Logo" class="w-full h-full object-contain">
                    </div>
                    
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight leading-tight">
                        Connect with Ceylon AG
                    </h2>
                    
                    <p class="text-sm sm:text-base text-emerald-100/90 leading-relaxed max-w-xl">
                        Whether you are a retail store owner interested in distributing Ceylon AG PESTO or a customer seeking further product details, our team is ready to assist you.
                    </p>

                    <div class="pt-2 flex flex-wrap items-center gap-4">
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-8 py-3.5 rounded-2xl text-xs font-black text-[#0F4D22] bg-white hover:bg-emerald-50 transition-all shadow-lg hover:-translate-y-0.5">
                                Register Dealer Account
                            </a>
                        @endif

                        <a href="{{ route('login') }}" class="px-8 py-3.5 rounded-2xl text-xs font-bold text-white bg-white/10 hover:bg-white/20 backdrop-blur-md transition-all border border-white/20">
                            Dealer Portal Login
                        </a>
                    </div>
                </div>

                <!-- Right: Contact Information Card -->
                <div class="lg:col-span-5">
                    <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/15 space-y-6">
                        <h3 class="text-lg font-black text-white">Official Company Contact</h3>
                        
                        <div class="space-y-4 text-xs sm:text-sm text-emerald-100">
                            <div class="flex items-start gap-3">
                                <span class="text-base">📍</span>
                                <div>
                                    <strong class="text-white block font-extrabold">Corporate Address</strong>
                                    <span>No. 123, Agribusiness Zone, Colombo, Sri Lanka</span>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <span class="text-base">📞</span>
                                <div>
                                    <strong class="text-white block font-extrabold">Telephone Inquiries</strong>
                                    <span>+94 11 234 5678</span>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <span class="text-base">✉️</span>
                                <div>
                                    <strong class="text-white block font-extrabold">Email Support</strong>
                                    <span>info@ceylonag.com</span>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <span class="text-base">🌐</span>
                                <div>
                                    <strong class="text-white block font-extrabold">Official Website</strong>
                                    <span>www.ceylonag.com</span>
                                </div>
                            </div>
                        </div>

                        <div class="pt-2 text-[11px] text-emerald-200 border-t border-white/10">
                            Serving retail and commercial agricultural partners across all 9 provinces of Sri Lanka.
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 8. FOOTER WITH OFFICIAL LOGO -->
    <!-- ========================================================================= -->
    <footer class="bg-gray-950 text-gray-400 py-12 border-t border-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-6">
                
                <!-- Official Logo & Brand Info -->
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-white p-1 shadow-md flex items-center justify-center overflow-hidden shrink-0">
                        <img src="{{ asset('images/logo.png') }}" alt="Ceylon AG Official Logo" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <span class="text-base font-black text-white block leading-tight">Ceylon AG</span>
                        <span class="text-[11px] text-gray-400 font-medium">Ceylon AG PESTO &bull; 100% Organic Pest Control &bull; Colombo, Sri Lanka</span>
                    </div>
                </div>

                <!-- Footer Links -->
                <div class="flex flex-wrap items-center gap-6 text-xs font-bold text-gray-400">
                    <a href="#overview" class="hover:text-white transition-colors">Overview</a>
                    <a href="#why-choose" class="hover:text-white transition-colors">Why PESTO</a>
                    <a href="#why-sell" class="hover:text-white transition-colors">Why Sell</a>
                    <a href="#benefits" class="hover:text-white transition-colors">Specs</a>
                    <a href="#contact" class="hover:text-white transition-colors">Contact</a>
                    <a href="{{ route('login') }}" class="hover:text-white transition-colors">Portal Login</a>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-900 text-center text-xs text-gray-500 font-medium">
                &copy; {{ date('Y') }} Ceylon Agro Marketing (Pvt) Ltd. All rights reserved. Ceylon AG and PESTO are trademarks of Ceylon AG in Sri Lanka.
            </div>
        </div>
    </footer>

</body>
</html>
