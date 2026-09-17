<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ceylon AG | Smart Agricultural Solutions</title>
    <meta name="description" content="Ceylon AG delivers practical agricultural solutions with a focus on quality, reliability and sustainable growth for homes, businesses and modern agriculture.">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

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
                            forest: {
                                900: '#072410',
                                800: '#0B3D1B',
                                700: '#145A27',
                                600: '#1B7A36',
                                500: '#22C55E'
                            },
                            accent: {
                                yellow: '#EAB308',
                                gold: '#CA8A04',
                                light: '#FEF08A'
                            }
                        },
                        fontFamily: {
                            sans: ['"Plus Jakarta Sans"', 'sans-serif']
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
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #FAFAF7;
            color: #1E293B;
            overflow-x: hidden;
        }

        /* Subtle modern card styling */
        .corporate-card {
            background-color: #FFFFFF;
            border: 1px solid #E5E7EB;
            border-radius: 1.25rem;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .corporate-card:hover {
            border-color: #CBD5E1;
            transform: translateY(-3px);
            box-shadow: 0 16px 32px -8px rgba(11, 61, 27, 0.08);
        }

        /* Respect prefers-reduced-motion */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
                scroll-behavior: auto !important;
            }
        }
    </style>
</head>
<body class="antialiased selection:bg-[#0B3D1B] selection:text-white" x-data="{ mobileMenuOpen: false }">

    @php
        // Fetch genuine company settings
        $company = null;
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('company_settings')) {
                $company = \App\Models\CompanySetting::first();
            }
        } catch (\Throwable $e) {
            $company = null;
        }
        $companyName = $company->company_name ?? 'Ceylon AG';
        $companyAddress = $company->address ?? 'I Jothipala Mawatha, Malabe';
        $companyPhone = $company->phone ?? '076 538 0483';
        $companyEmail = $company->email ?? 'info@ceylonagromarketing.lk';
        $companyWebsite = $company->website ?? 'https://ceylonagromarketing.lk/';

        // Fetch genuine PESTO product details without exposing internal prices or stock counts
        $pestoProduct = null;
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('products')) {
                $pestoProduct = \App\Models\Product::where('name', 'like', '%pesto%')->first() 
                    ?? \App\Models\Product::first();
            }
        } catch (\Throwable $e) {
            $pestoProduct = null;
        }
        $pestoSku = $pestoProduct->sku ?? 'CAM-01';
        $pestoImage = ($pestoProduct && $pestoProduct->image_path && file_exists(storage_path('app/public/' . $pestoProduct->image_path)))
            ? asset('storage/' . $pestoProduct->image_path)
            : (file_exists(public_path('storage/products/k6jRXhGW5vMqWo6TnoJ7LdC4zsBljSUcjWX3oQR0.jpg'))
                ? asset('storage/products/k6jRXhGW5vMqWo6TnoJ7LdC4zsBljSUcjWX3oQR0.jpg')
                : asset('images/logo.png'));

        $agriImage = file_exists(public_path('images/ceylon_agriculture.jpg'))
            ? asset('images/ceylon_agriculture.jpg')
            : asset('images/logo.png');
    @endphp

    <!-- ========================================================================= -->
    <!-- 2. NAVIGATION (Sticky, Minimal & Compact) -->
    <!-- ========================================================================= -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-stone-200/80 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            
            <!-- LEFT: Official Ceylon AG Logo (Not redesigned) -->
            <a href="/" class="flex items-center gap-3.5 group">
                <div class="w-11 h-11 rounded-xl bg-white p-1.5 border border-stone-200 shadow-sm flex items-center justify-center shrink-0 group-hover:border-[#0B3D1B]/40 transition-colors">
                    <img src="{{ asset('images/logo.png') }}" alt="Ceylon AG Logo" class="w-full h-full object-contain">
                </div>
                <div class="flex flex-col">
                    <span class="text-lg font-extrabold text-stone-900 tracking-tight leading-none group-hover:text-[#0B3D1B] transition-colors">
                        Ceylon AG
                    </span>
                    <span class="text-[10px] font-semibold text-stone-500 uppercase tracking-wider mt-0.5">
                        Smart Agricultural Solutions
                    </span>
                </div>
            </a>

            <!-- CENTER / RIGHT: Navigation Links -->
            <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-stone-600">
                <a href="#home" class="hover:text-[#0B3D1B] transition-colors">Home</a>
                <a href="#about" class="hover:text-[#0B3D1B] transition-colors">About</a>
                <a href="#solutions" class="hover:text-[#0B3D1B] transition-colors">Solutions</a>
                <a href="#pesto" class="hover:text-[#0B3D1B] transition-colors">PESTO</a>
                <a href="#contact" class="hover:text-[#0B3D1B] transition-colors">Contact</a>
            </nav>

            <!-- RIGHT: Clean CTA & Portal Access -->
            <div class="hidden md:flex items-center gap-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-xs font-semibold text-stone-700 hover:text-[#0B3D1B] transition-colors">
                            Portal Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-xs font-semibold text-stone-600 hover:text-stone-900 transition-colors px-2 py-1">
                            Login
                        </a>
                    @endauth
                @endif

                <a href="#contact" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-[#0B3D1B] hover:bg-[#145A27] transition-all shadow-sm hover:shadow">
                    Get in Touch
                </a>
            </div>

            <!-- Mobile Hamburger Button -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2.5 rounded-xl text-stone-700 hover:text-stone-900 hover:bg-stone-100 transition-colors" aria-label="Toggle Menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Mobile Drawer Navigation -->
        <div x-show="mobileMenuOpen" x-transition.opacity.duration.200ms class="md:hidden bg-white border-b border-stone-200 px-6 pt-3 pb-6 space-y-3.5 shadow-lg">
            <a href="#home" @click="mobileMenuOpen = false" class="block py-2 text-sm font-semibold text-stone-800 hover:text-[#0B3D1B]">Home</a>
            <a href="#about" @click="mobileMenuOpen = false" class="block py-2 text-sm font-semibold text-stone-800 hover:text-[#0B3D1B]">About</a>
            <a href="#solutions" @click="mobileMenuOpen = false" class="block py-2 text-sm font-semibold text-stone-800 hover:text-[#0B3D1B]">Solutions</a>
            <a href="#pesto" @click="mobileMenuOpen = false" class="block py-2 text-sm font-semibold text-stone-800 hover:text-[#0B3D1B]">PESTO</a>
            <a href="#contact" @click="mobileMenuOpen = false" class="block py-2 text-sm font-semibold text-stone-800 hover:text-[#0B3D1B]">Contact</a>
            
            <div class="pt-4 border-t border-stone-100 flex flex-col gap-2.5">
                <a href="#contact" @click="mobileMenuOpen = false" class="w-full text-center py-3 rounded-xl text-xs font-bold text-white bg-[#0B3D1B] hover:bg-[#145A27] transition-colors shadow-sm">
                    Get in Touch
                </a>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="w-full text-center py-2.5 rounded-xl text-xs font-semibold text-stone-700 bg-stone-100 hover:bg-stone-200 transition-colors">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="w-full text-center py-2.5 rounded-xl text-xs font-semibold text-stone-700 bg-stone-100 hover:bg-stone-200 transition-colors">
                            Login
                        </a>
                    @endauth
                @endif
            </div>
        </div>
    </header>

    <!-- ========================================================================= -->
    <!-- 3. HERO SECTION -->
    <!-- ========================================================================= -->
    <section id="home" class="relative py-16 md:py-24 lg:py-28 bg-[#FAFAF7] border-b border-stone-200/70 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-12 gap-12 lg:gap-14 items-center">
                
                <!-- Left Column: Hero Content -->
                <div class="lg:col-span-7 space-y-7">
                    
                    <!-- Small Eyebrow Label -->
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-50 border border-emerald-200/80">
                        <span class="w-2 h-2 rounded-full bg-[#15803D]"></span>
                        <span class="text-xs font-bold uppercase tracking-wider text-[#0B3D1B]">
                            CEYLON AG &bull; Smart Agricultural Solutions
                        </span>
                    </div>

                    <!-- Main Headline -->
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-stone-900 tracking-tight leading-[1.15]">
                        Growing Better.<br>
                        <span class="text-[#0B3D1B]">Building Smarter.</span>
                    </h1>

                    <!-- Supporting Text -->
                    <p class="text-lg sm:text-xl text-stone-600 leading-relaxed max-w-2xl font-normal">
                        Practical agricultural solutions designed to support homes, businesses and modern agriculture.
                    </p>

                    <!-- CTA Buttons -->
                    <div class="flex flex-wrap items-center gap-4 pt-2">
                        <a href="#solutions" class="inline-flex items-center justify-center px-7 py-3.5 rounded-xl text-sm font-bold text-white bg-[#0B3D1B] hover:bg-[#145A27] transition-all shadow-sm hover:shadow-md">
                            Explore Solutions
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </a>

                        <a href="#contact" class="inline-flex items-center justify-center px-7 py-3.5 rounded-xl text-sm font-bold text-stone-700 bg-white hover:bg-stone-50 border border-stone-300 hover:border-stone-400 transition-all shadow-sm">
                            Contact Us
                        </a>
                    </div>

                    <!-- Subtle Trust Note -->
                    <div class="pt-3 flex items-center gap-6 text-xs font-medium text-stone-500">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-[#15803D]" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Quality Formulations
                        </span>
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-[#15803D]" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Eco-Conscious Focus
                        </span>
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-[#15803D]" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Reliable Support
                        </span>
                    </div>

                </div>

                <!-- Right Column: Agricultural Photography Visual -->
                <div class="lg:col-span-5 relative">
                    <div class="relative rounded-3xl overflow-hidden border border-stone-200/90 shadow-xl bg-white p-2">
                        <div class="relative rounded-2xl overflow-hidden h-[340px] sm:h-[420px] bg-stone-100">
                            <img src="{{ $agriImage }}" alt="Ceylon AG Sustainable Agriculture" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-stone-900/40 via-transparent to-transparent"></div>
                        </div>

                        <!-- Elegant Floating Visual Element -->
                        <div class="absolute bottom-6 left-6 right-6 p-4 rounded-2xl bg-white/95 backdrop-blur-md border border-stone-200 shadow-md flex items-center gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-[#0B3D1B] flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-[#0B3D1B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-stone-900">Sustainable Agriculture</h4>
                                <p class="text-[11px] text-stone-500">Built for resilient crops and clean living spaces.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 4. BRAND INTRODUCTION (Concise Editorial Section) -->
    <!-- ========================================================================= -->
    <section id="about" class="py-20 md:py-28 bg-white border-b border-stone-200/70">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
            
            <!-- Small Label -->
            <span class="inline-block text-xs font-bold uppercase tracking-widest text-[#15803D] bg-emerald-50 px-3 py-1 rounded-full border border-emerald-100">
                WHO WE ARE
            </span>

            <!-- Heading -->
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-stone-900 tracking-tight leading-tight">
                Solutions Built for a Better Tomorrow
            </h2>

            <!-- Short Paragraph -->
            <p class="text-lg sm:text-xl text-stone-600 leading-relaxed font-normal max-w-3xl mx-auto">
                Ceylon AG delivers practical agricultural solutions with a focus on quality, reliability and sustainable growth.
            </p>

            <div class="w-16 h-1 bg-[#EAB308] mx-auto rounded-full mt-4"></div>

        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 5. SOLUTIONS SECTION (3 Clean Categories Only) -->
    <!-- ========================================================================= -->
    <section id="solutions" class="py-20 md:py-28 bg-[#FAFAF7] border-b border-stone-200/70">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
            
            <div class="text-center max-w-2xl mx-auto space-y-3">
                <span class="text-xs font-bold uppercase tracking-widest text-[#15803D]">
                    OUR SOLUTIONS
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-stone-900 tracking-tight">
                    Practical Solutions. Real Impact.
                </h2>
                <p class="text-base text-stone-600">
                    Carefully developed approaches designed to answer key agricultural and environmental demands.
                </p>
            </div>

            <!-- 3 Main Solution Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <!-- Card 1: Agricultural Solutions -->
                <div class="corporate-card p-8 sm:p-9 space-y-5">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 border border-emerald-100 text-[#0B3D1B] flex items-center justify-center">
                        <svg class="w-7 h-7 text-[#0B3D1B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-stone-900 tracking-tight">
                        Agricultural Solutions
                    </h3>
                    <p class="text-sm text-stone-600 leading-relaxed">
                        Practical crop care approaches focused on plant resilience, foliar strength, and balanced field productivity under local conditions.
                    </p>
                    <div class="pt-2">
                        <a href="#contact" class="inline-flex items-center text-xs font-bold text-[#0B3D1B] hover:text-[#145A27] transition-colors">
                            Learn more
                            <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Card 2: Pest Management -->
                <div class="corporate-card p-8 sm:p-9 space-y-5">
                    <div class="w-14 h-14 rounded-2xl bg-amber-50 border border-amber-100 text-[#CA8A04] flex items-center justify-center">
                        <svg class="w-7 h-7 text-[#CA8A04]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-stone-900 tracking-tight">
                        Pest Management
                    </h3>
                    <p class="text-sm text-stone-600 leading-relaxed">
                        Botanical, eco-conscious repellency formulations designed for safe, effective pest defense in homes, gardens, and commercial spaces without harsh fumes.
                    </p>
                    <div class="pt-2">
                        <a href="#pesto" class="inline-flex items-center text-xs font-bold text-[#0B3D1B] hover:text-[#145A27] transition-colors">
                            Explore PESTO
                            <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Card 3: Business Solutions -->
                <div class="corporate-card p-8 sm:p-9 space-y-5">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 border border-emerald-100 text-[#0B3D1B] flex items-center justify-center">
                        <svg class="w-7 h-7 text-[#0B3D1B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-stone-900 tracking-tight">
                        Business Solutions
                    </h3>
                    <p class="text-sm text-stone-600 leading-relaxed">
                        Dedicated merchant partnerships, assigned field representatives, and transparent quotation workflows to help retailers and distributors grow reliably.
                    </p>
                    <div class="pt-2">
                        <a href="#contact" class="inline-flex items-center text-xs font-bold text-[#0B3D1B] hover:text-[#145A27] transition-colors">
                            Partner with us
                            <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 6. PESTO FEATURE SECTION (Split Layout Product Showcase) -->
    <!-- ========================================================================= -->
    <section id="pesto" class="py-20 md:py-28 bg-white border-b border-stone-200/70">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-12 gap-12 lg:gap-16 items-center">
                
                <!-- LEFT: Existing PESTO Product Visual -->
                <div class="lg:col-span-5 flex flex-col items-center">
                    <div class="w-full max-w-md rounded-3xl bg-[#FAFAF7] p-8 sm:p-12 border border-stone-200/90 shadow-lg flex flex-col items-center justify-center relative group">
                        <div class="absolute top-4 right-4 px-3 py-1 rounded-full bg-emerald-100 text-[#0B3D1B] text-[11px] font-bold">
                            SKU: {{ $pestoSku }}
                        </div>
                        <img src="{{ $pestoImage }}" alt="Ceylon AG PESTO Bottle" class="w-56 h-56 sm:w-72 sm:h-72 object-contain group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <span class="text-xs font-medium text-stone-400 mt-4 tracking-wide">
                        Ceylon AG PESTO &bull; Official Solution
                    </span>
                </div>

                <!-- RIGHT: Headline, Copy & Highlights -->
                <div class="lg:col-span-7 space-y-6">
                    
                    <span class="text-xs font-bold uppercase tracking-widest text-[#15803D] bg-emerald-50 px-3 py-1 rounded-full border border-emerald-100">
                        OUR KEY SOLUTION
                    </span>

                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-stone-900 tracking-tight">
                        PESTO
                    </h2>

                    <p class="text-base sm:text-lg text-stone-600 leading-relaxed font-normal">
                        A practical solution designed to help create cleaner, more comfortable living and working environments.
                    </p>

                    <!-- Feature Highlights (Clean Rounded Cards) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                        <div class="p-5 rounded-2xl bg-[#FAFAF7] border border-stone-200 space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-[#15803D]"></span>
                                <strong class="text-sm font-bold text-stone-900">Why Choose PESTO?</strong>
                            </div>
                            <p class="text-xs text-stone-600 leading-relaxed">
                                Pre-mixed, ready-to-use botanical spray safe for home gardens, indoor foliage, and commercial environments.
                            </p>
                        </div>

                        <div class="p-5 rounded-2xl bg-[#FAFAF7] border border-stone-200 space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-[#EAB308]"></span>
                                <strong class="text-sm font-bold text-stone-900">Why Sell PESTO?</strong>
                            </div>
                            <p class="text-xs text-stone-600 leading-relaxed">
                                High retail customer demand for eco-safe options, straightforward merchant supply, and dependable brand backing.
                            </p>
                        </div>
                    </div>

                    <!-- CTA Button -->
                    <div class="pt-3">
                        <a href="#contact" class="inline-flex items-center justify-center px-7 py-3.5 rounded-xl text-sm font-bold text-white bg-[#0B3D1B] hover:bg-[#145A27] transition-all shadow-sm hover:shadow-md">
                            Discover PESTO
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    </div>

                </div>

            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 7. WHY CEYLON AG (3 Columns) -->
    <!-- ========================================================================= -->
    <section class="py-20 md:py-28 bg-[#FAFAF7] border-b border-stone-200/70">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
            
            <div class="text-center max-w-2xl mx-auto space-y-3">
                <span class="text-xs font-bold uppercase tracking-widest text-[#15803D]">
                    RELIABLE STANDARDS
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-stone-900 tracking-tight">
                    Why Ceylon AG
                </h2>
                <p class="text-base text-stone-600">
                    Built on core principles that empower farmers, businesses, and households with confidence.
                </p>
            </div>

            <!-- 3 Simple Columns -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <!-- Point 1: Quality -->
                <div class="bg-white p-8 rounded-2xl border border-stone-200 space-y-3 text-center sm:text-left">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-[#0B3D1B] flex items-center justify-center mx-auto sm:mx-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-stone-900">
                        Quality
                    </h3>
                    <p class="text-sm text-stone-600 leading-relaxed">
                        Uncompromising standards in formulation, tested for consistent field effectiveness, safety, and dependable results.
                    </p>
                </div>

                <!-- Point 2: Practical Solutions -->
                <div class="bg-white p-8 rounded-2xl border border-stone-200 space-y-3 text-center sm:text-left">
                    <div class="w-12 h-12 rounded-xl bg-amber-50 text-[#CA8A04] flex items-center justify-center mx-auto sm:mx-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-stone-900">
                        Practical Solutions
                    </h3>
                    <p class="text-sm text-stone-600 leading-relaxed">
                        Engineered specifically for real-world agricultural and environmental challenges without unnecessary complexity.
                    </p>
                </div>

                <!-- Point 3: Trusted Service -->
                <div class="bg-white p-8 rounded-2xl border border-stone-200 space-y-3 text-center sm:text-left">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-[#0B3D1B] flex items-center justify-center mx-auto sm:mx-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-stone-900">
                        Trusted Service
                    </h3>
                    <p class="text-sm text-stone-600 leading-relaxed">
                        Dedicated merchant partnerships, assigned field representatives, and transparent support at every step of distribution.
                    </p>
                </div>

            </div>

        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 8. BUSINESS PARTNERSHIP SECTION -->
    <!-- ========================================================================= -->
    <section class="py-16 md:py-24 bg-white border-b border-stone-200/70">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="rounded-3xl bg-[#0B3D1B] text-white p-10 sm:p-14 md:p-16 relative overflow-hidden shadow-xl">
                
                <!-- Subtle decorative background accent -->
                <div class="absolute -right-16 -top-16 w-80 h-80 rounded-full bg-emerald-800/30 blur-3xl pointer-events-none"></div>
                <div class="absolute -left-16 -bottom-16 w-80 h-80 rounded-full bg-amber-500/10 blur-3xl pointer-events-none"></div>

                <div class="relative max-w-3xl space-y-6">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-[#FEF08A] text-xs font-bold uppercase tracking-wider">
                        Commercial &amp; Retail Collaboration
                    </div>

                    <h2 class="text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tight text-white leading-tight">
                        Let's Grow Together
                    </h2>

                    <p class="text-base sm:text-lg text-emerald-100/90 leading-relaxed max-w-2xl font-normal">
                        Looking for reliable agricultural solutions for your business? Let's build a better solution together.
                    </p>

                    <div class="pt-2">
                        <a href="#contact" class="inline-flex items-center justify-center px-8 py-4 rounded-xl text-sm font-bold text-[#0B3D1B] bg-white hover:bg-emerald-50 transition-all shadow-md">
                            Contact Ceylon AG
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 9. CONTACT SECTION (Real Company Details Only) -->
    <!-- ========================================================================= -->
    <section id="contact" class="py-20 md:py-28 bg-[#FAFAF7] border-b border-stone-200/70">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
            
            <div class="text-center max-w-2xl mx-auto space-y-3">
                <span class="text-xs font-bold uppercase tracking-widest text-[#15803D]">
                    GET IN TOUCH
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-stone-900 tracking-tight">
                    Contact Ceylon AG
                </h2>
                <p class="text-base text-stone-600">
                    Reach out for inquiries, product distribution, and agricultural consultation.
                </p>
            </div>

            <!-- Contact Information Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <!-- Company Name / Address -->
                <div class="bg-white p-7 rounded-2xl border border-stone-200 space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-[#0B3D1B] flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h4 class="text-sm font-bold text-stone-900">Address</h4>
                    <p class="text-xs text-stone-600 leading-relaxed font-medium">
                        {{ $companyAddress }}
                    </p>
                </div>

                <!-- Phone -->
                <div class="bg-white p-7 rounded-2xl border border-stone-200 space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-[#0B3D1B] flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <h4 class="text-sm font-bold text-stone-900">Direct Phone</h4>
                    <p class="text-xs font-medium">
                        <a href="tel:{{ $companyPhone }}" class="text-stone-700 hover:text-[#0B3D1B] transition-colors">
                            {{ $companyPhone }}
                        </a>
                    </p>
                </div>

                <!-- Email -->
                <div class="bg-white p-7 rounded-2xl border border-stone-200 space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-[#0B3D1B] flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h4 class="text-sm font-bold text-stone-900">Official Email</h4>
                    <p class="text-xs font-medium">
                        <a href="mailto:{{ $companyEmail }}" class="text-stone-700 hover:text-[#0B3D1B] transition-colors break-all">
                            {{ $companyEmail }}
                        </a>
                    </p>
                </div>

                <!-- Website -->
                <div class="bg-white p-7 rounded-2xl border border-stone-200 space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-[#0B3D1B] flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                        </svg>
                    </div>
                    <h4 class="text-sm font-bold text-stone-900">Official Website</h4>
                    <p class="text-xs font-medium">
                        <a href="{{ $companyWebsite }}" target="_blank" rel="noopener noreferrer" class="text-stone-700 hover:text-[#0B3D1B] transition-colors break-all">
                            {{ $companyWebsite }}
                        </a>
                    </p>
                </div>

            </div>

            <!-- Direct Contact Form Card -->
            <div class="max-w-3xl mx-auto bg-white p-8 sm:p-10 rounded-3xl border border-stone-200 shadow-sm" x-data="{
                name: '',
                inquiryType: 'General Inquiry',
                message: '',
                sendInquiry() {
                    const subject = encodeURIComponent(`${this.inquiryType} from ${this.name || 'Ceylon AG Visitor'}`);
                    const body = encodeURIComponent(`Name: ${this.name}\nInquiry Type: ${this.inquiryType}\n\nMessage:\n${this.message}`);
                    window.location.href = `mailto:{{ $companyEmail }}?subject=${subject}&body=${body}`;
                }
            }">
                <h3 class="text-xl font-bold text-stone-900 mb-2">Send an Inquiry</h3>
                <p class="text-xs text-stone-500 mb-6">Our agricultural and distribution specialists will review your message promptly.</p>

                <form @submit.prevent="sendInquiry" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-stone-700 mb-1">Your Name</label>
                            <input type="text" x-model="name" required placeholder="Full Name" class="w-full text-xs rounded-xl border-stone-300 focus:border-[#0B3D1B] focus:ring-[#0B3D1B] py-2.5 px-3.5">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-stone-700 mb-1">Inquiry Type</label>
                            <select x-model="inquiryType" class="w-full text-xs rounded-xl border-stone-300 focus:border-[#0B3D1B] focus:ring-[#0B3D1B] py-2.5 px-3.5">
                                <option value="General Inquiry">General Inquiry</option>
                                <option value="PESTO Product Inquiry">PESTO Product Inquiry</option>
                                <option value="Merchant Partnership">Merchant Partnership</option>
                                <option value="Agricultural Consultation">Agricultural Consultation</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-stone-700 mb-1">Your Message</label>
                        <textarea x-model="message" required rows="4" placeholder="How can Ceylon AG assist your home, business, or farm?" class="w-full text-xs rounded-xl border-stone-300 focus:border-[#0B3D1B] focus:ring-[#0B3D1B] py-2.5 px-3.5"></textarea>
                    </div>

                    <button type="submit" class="inline-flex items-center justify-center px-6 py-3 rounded-xl text-xs font-bold text-white bg-[#0B3D1B] hover:bg-[#145A27] transition-colors shadow-sm">
                        Submit Inquiry
                        <svg class="w-3.5 h-3.5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </form>
            </div>

        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 10. FOOTER (Minimal, Professional Corporate Footer) -->
    <!-- ========================================================================= -->
    <footer class="bg-white text-stone-600 py-14 border-t border-stone-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            
            <div class="grid grid-cols-1 md:grid-cols-12 gap-10 items-start">
                
                <!-- Left: Logo & Company Description -->
                <div class="md:col-span-5 space-y-4">
                    <a href="/" class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white p-1.5 border border-stone-200 shadow-sm flex items-center justify-center shrink-0">
                            <img src="{{ asset('images/logo.png') }}" alt="Ceylon AG Logo" class="w-full h-full object-contain">
                        </div>
                        <span class="text-lg font-extrabold text-stone-900 tracking-tight">
                            Ceylon AG
                        </span>
                    </a>
                    <p class="text-xs text-stone-500 leading-relaxed max-w-sm">
                        Practical agricultural solutions designed to support homes, businesses and modern agriculture across Sri Lanka.
                    </p>
                </div>

                <!-- Center: Navigation Links -->
                <div class="md:col-span-3 space-y-3">
                    <h5 class="text-xs font-bold text-stone-900 uppercase tracking-wider">Navigation</h5>
                    <ul class="space-y-2 text-xs font-medium text-stone-600">
                        <li><a href="#home" class="hover:text-[#0B3D1B] transition-colors">Home</a></li>
                        <li><a href="#about" class="hover:text-[#0B3D1B] transition-colors">About Us</a></li>
                        <li><a href="#solutions" class="hover:text-[#0B3D1B] transition-colors">Solutions</a></li>
                        <li><a href="#pesto" class="hover:text-[#0B3D1B] transition-colors">PESTO Showcase</a></li>
                        <li><a href="#contact" class="hover:text-[#0B3D1B] transition-colors">Contact Us</a></li>
                    </ul>
                </div>

                <!-- Right: Existing Contact Information -->
                <div class="md:col-span-4 space-y-3">
                    <h5 class="text-xs font-bold text-stone-900 uppercase tracking-wider">Contact Information</h5>
                    <div class="space-y-1.5 text-xs text-stone-500">
                        <p class="font-semibold text-stone-800">{{ $companyName }}</p>
                        <p>{{ $companyAddress }}</p>
                        <p>Phone: <a href="tel:{{ $companyPhone }}" class="text-stone-700 hover:text-[#0B3D1B] transition-colors">{{ $companyPhone }}</a></p>
                        <p>Email: <a href="mailto:{{ $companyEmail }}" class="text-stone-700 hover:text-[#0B3D1B] transition-colors">{{ $companyEmail }}</a></p>
                        <p>Website: <a href="{{ $companyWebsite }}" target="_blank" rel="noopener noreferrer" class="text-stone-700 hover:text-[#0B3D1B] transition-colors">{{ $companyWebsite }}</a></p>
                    </div>
                </div>

            </div>

            <!-- Bottom Copyright -->
            <div class="pt-8 border-t border-stone-200 text-xs text-stone-500 flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left">
                <div>
                    &copy; {{ date('Y') }} Ceylon AG. All rights reserved.
                </div>
                <div class="text-stone-400">
                    Smart Agricultural Solutions
                </div>
            </div>

        </div>
    </footer>

</body>
</html>
