<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Ceylon AG') }} - Authentication</title>

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts & Styles -->
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

        <!-- GSAP -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>

        <!-- Three.js for 3D Background & Hero Scene -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>

        <style>
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
                background-color: #F8FAF8;
                color: #1F2937;
            }

            .glass-auth-card {
                background: rgba(255, 255, 255, 0.85);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.9);
                box-shadow: 0 25px 50px -12px rgba(30, 142, 62, 0.12), 0 0 0 1px rgba(30, 142, 62, 0.05);
            }

            .gradient-text-green {
                background: linear-gradient(135deg, #1E8E3E 0%, #6CC24A 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }

            .auth-input {
                transition: all 0.25s ease-in-out;
            }

            .auth-input:focus {
                border-color: #1E8E3E;
                box-shadow: 0 0 0 4px rgba(108, 194, 74, 0.18);
                outline: none;
            }

            .float-particle {
                animation: floatParticle 8s ease-in-out infinite alternate;
            }

            @keyframes floatParticle {
                0% { transform: translateY(0px) rotate(0deg); }
                50% { transform: translateY(-15px) rotate(3deg); }
                100% { transform: translateY(0px) rotate(0deg); }
            }
        </style>
    </head>
    <body class="h-full bg-gradient-to-br from-slate-50 via-emerald-50/30 to-slate-100 antialiased selection:bg-emerald-600 selection:text-white">

        <!-- Split Screen Desktop Layout / Mobile Centered Card Layout -->
        <div class="min-h-screen flex w-full">
            
            <!-- LEFT COLUMN: Form Container -->
            <div class="w-full lg:w-1/2 flex flex-col justify-between p-6 sm:p-12 z-10 overflow-y-auto">
                
                <!-- Top Brand Navigation Logo -->
                <div class="w-full flex items-center justify-between max-w-xl mx-auto mb-6">
                    <a href="/" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-[#1E8E3E] to-[#6CC24A] p-0.5 shadow-md shadow-emerald-600/20 group-hover:scale-105 transition-transform duration-300">
                            <div class="w-full h-full bg-white rounded-[14px] flex items-center justify-center overflow-hidden">
                                @if(file_exists(public_path('images/logo.png')))
                                    <img src="{{ asset('images/logo.png') }}" alt="Ceylon AG" class="w-7 h-7 object-contain">
                                @else
                                    <svg class="w-5 h-5 text-[#1E8E3E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                @endif
                            </div>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-lg font-extrabold tracking-tight text-gray-900 group-hover:text-[#1E8E3E] transition-colors">Ceylon AG</span>
                            <span class="text-[9px] font-bold uppercase tracking-widest text-[#1E8E3E]">AgriTech Platform</span>
                        </div>
                    </a>

                    <a href="/" class="text-xs font-semibold text-gray-500 hover:text-[#1E8E3E] flex items-center gap-1 transition-colors">
                        <span>Back to Home</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>

                <!-- Main Form Card Container -->
                <div class="my-auto w-full max-w-xl mx-auto">
                    {{ $slot }}
                </div>

                <!-- Footer -->
                <div class="w-full text-center max-w-xl mx-auto pt-6 text-xs text-gray-400 font-medium">
                    © {{ date('Y') }} Ceylon Agro Marketing (Pvt) Ltd. All rights reserved.
                </div>

            </div>

            <!-- RIGHT COLUMN: 3D Scene (Desktop Only) -->
            <div class="hidden lg:flex lg:w-1/2 relative bg-gradient-to-br from-[#0F4D22] via-[#1E8E3E] to-[#0F4D22] overflow-hidden items-center justify-center">
                
                <!-- Glowing Ambient Light Orbs -->
                <div class="absolute -top-32 -right-32 w-[500px] h-[500px] bg-[#6CC24A]/25 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-32 -left-32 w-[500px] h-[500px] bg-emerald-400/20 rounded-full blur-3xl pointer-events-none"></div>

                <!-- Three.js Canvas Container -->
                <div id="auth-3d-scene" class="w-full h-full absolute inset-0 cursor-grab active:cursor-grabbing"></div>

                <!-- Overlay Branding & Floating Glass Cards -->
                <div class="relative z-10 p-12 max-w-lg text-white space-y-6 pointer-events-none">
                    <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full bg-white/10 backdrop-blur-md text-xs font-bold text-[#6CC24A] border border-white/10">
                        <span class="w-2 h-2 rounded-full bg-[#6CC24A] animate-ping"></span>
                        <span>🌱 Enterprise Dealer Management</span>
                    </div>

                    <h2 class="text-4xl font-extrabold tracking-tight leading-tight text-white">
                        Smart Agricultural Supply Chain Platform
                    </h2>

                    <p class="text-sm text-emerald-100/80 leading-relaxed">
                        Streamline dealer verification, manage agricultural product distribution, track quotation requests, and analyze regional sales.
                    </p>

                    <!-- Floating Feature Micro Cards -->
                    <div class="grid grid-cols-2 gap-4 pt-4">
                        <div class="p-4 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 float-particle">
                            <div class="text-2xl mb-1">🌾</div>
                            <div class="text-xs font-bold text-white">100+ Verified Dealers</div>
                            <div class="text-[10px] text-emerald-200/70">Island-wide Network</div>
                        </div>

                        <div class="p-4 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 float-particle" style="animation-delay: 1.5s;">
                            <div class="text-2xl mb-1">🛡️</div>
                            <div class="text-xs font-bold text-white">Encrypted & Secure</div>
                            <div class="text-[10px] text-emerald-200/70">Role-Based Access</div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <!-- 3D Three.js Animation Script -->
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                initAuth3DScene();
            });

            function initAuth3DScene() {
                const container = document.getElementById('auth-3d-scene');
                if (!container || typeof THREE === 'undefined') return;

                const width = container.clientWidth;
                const height = container.clientHeight;

                const scene = new THREE.Scene();
                const camera = new THREE.PerspectiveCamera(45, width / height, 0.1, 1000);
                camera.position.z = 7;

                const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
                renderer.setSize(width, height);
                renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
                container.appendChild(renderer.domElement);

                // Lighting
                const ambientLight = new THREE.AmbientLight(0xffffff, 0.9);
                scene.add(ambientLight);

                const directionalLight = new THREE.DirectionalLight(0x6CC24A, 1.5);
                directionalLight.position.set(5, 5, 5);
                scene.add(directionalLight);

                // 3D Outer Eco Sphere Wireframe
                const globeGeo = new THREE.IcosahedronGeometry(2.3, 2);
                const globeMat = new THREE.MeshPhongMaterial({
                    color: 0x6CC24A,
                    wireframe: true,
                    transparent: true,
                    opacity: 0.35,
                });
                const globe = new THREE.Mesh(globeGeo, globeMat);
                scene.add(globe);

                // 3D Inner Glowing Core
                const coreGeo = new THREE.SphereGeometry(1.4, 32, 32);
                const coreMat = new THREE.MeshPhongMaterial({
                    color: 0x1E8E3E,
                    emissive: 0x0F4D22,
                    shininess: 40,
                    transparent: true,
                    opacity: 0.75
                });
                const coreSphere = new THREE.Mesh(coreGeo, coreMat);
                scene.add(coreSphere);

                // 3D Orbiting Leaf / Crop Particles
                const particleCount = 45;
                const particleGeo = new THREE.TetrahedronGeometry(0.14, 0);
                const particleMat = new THREE.MeshStandardMaterial({
                    color: 0x6CC24A,
                    roughness: 0.2
                });

                const particles = [];
                for (let i = 0; i < particleCount; i++) {
                    const particle = new THREE.Mesh(particleGeo, particleMat);
                    const radius = 2.6 + Math.random() * 1.2;
                    const theta = Math.random() * Math.PI * 2;
                    const phi = Math.acos((Math.random() * 2) - 1);

                    particle.position.x = radius * Math.sin(phi) * Math.cos(theta);
                    particle.position.y = radius * Math.sin(phi) * Math.sin(theta);
                    particle.position.z = radius * Math.cos(phi);

                    particle.userData = {
                        speed: 0.003 + Math.random() * 0.008,
                        axis: new THREE.Vector3(Math.random(), Math.random(), Math.random()).normalize()
                    };

                    scene.add(particle);
                    particles.push(particle);
                }

                // Interactive Mouse Movement
                let mouseX = 0, mouseY = 0;
                window.addEventListener('mousemove', (e) => {
                    mouseX = (e.clientX / window.innerWidth - 0.5) * 0.4;
                    mouseY = (e.clientY / window.innerHeight - 0.5) * 0.4;
                });

                function animate() {
                    requestAnimationFrame(animate);

                    globe.rotation.y += 0.0025;
                    globe.rotation.x += 0.001;

                    coreSphere.rotation.y -= 0.002;

                    particles.forEach(p => {
                        p.position.applyAxisAngle(p.userData.axis, p.userData.speed);
                        p.rotation.x += 0.01;
                        p.rotation.y += 0.01;
                    });

                    camera.position.x += (mouseX - camera.position.x) * 0.05;
                    camera.position.y += (-mouseY - camera.position.y) * 0.05;
                    camera.lookAt(scene.position);

                    renderer.render(scene, camera);
                }

                animate();

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
