<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GSAC General Hospital — Teleconsultation</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white text-gray-900">

{{-- ============================================================
     NAVBAR
     ============================================================ --}}
<header class="fixed top-0 inset-x-0 z-50 bg-white/90 backdrop-blur border-b border-gray-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            {{-- Brand --}}
            <a href="{{ route('home') }}" class="flex items-center">
                <img src="{{ asset('images/gghi logo (1).png') }}" alt="GSAC General Hospital Inc." class="h-10 w-auto">
            </a>

            {{-- Desktop nav links --}}
            <nav class="hidden md:flex items-center gap-6 text-sm font-medium text-gray-600">
                <a href="#services" class="hover:text-blue-600 transition-colors">Services</a>
                <a href="#how-it-works" class="hover:text-blue-600 transition-colors">How It Works</a>
                <a href="#specialties" class="hover:text-blue-600 transition-colors">Specialties</a>
                <a href="{{ route('doctors.index') }}" class="hover:text-blue-600 transition-colors">Find a Doctor</a>
            </nav>

            {{-- Auth CTAs --}}
            <div class="flex items-center gap-3">
                @auth
                    @if(auth()->user()->hasRole('super_admin') || auth()->user()->hasRole('admin'))
                        <a href="{{ route('admin.dashboard') }}" class="hidden sm:inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 transition-colors">
                            Admin Panel
                        </a>
                    @elseif(auth()->user()->hasRole('doctor'))
                        <a href="{{ route('doctor.dashboard') }}" class="hidden sm:inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 transition-colors">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('patient.dashboard') }}" class="hidden sm:inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 transition-colors">
                            Dashboard
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-blue-600 transition-colors">Log in</a>
                    <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 transition-colors shadow-sm">
                        Get Started
                    </a>
                @endauth
            </div>
        </div>
    </div>
</header>

{{-- ============================================================
     HERO
     ============================================================ --}}
<section class="relative pt-16 overflow-hidden bg-gradient-to-br from-slate-900 via-blue-950 to-indigo-900 min-h-screen flex items-center">
    {{-- Background blobs --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-96 h-96 rounded-full bg-blue-500/20 blur-3xl"></div>
        <div class="absolute top-1/2 -left-40 w-80 h-80 rounded-full bg-indigo-500/20 blur-3xl"></div>
        <div class="absolute bottom-0 right-1/4 w-64 h-64 rounded-full bg-cyan-500/10 blur-3xl"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            {{-- Left: copy --}}
            <div>
                {{-- Live badge --}}
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-green-500/20 border border-green-500/30 text-green-400 text-xs font-medium mb-6">
                    <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                    Doctors Available Now
                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-tight mb-6">
                    Healthcare at Your
                    <span class="bg-gradient-to-r from-blue-400 to-cyan-400 bg-clip-text text-transparent"> Fingertips</span>
                </h1>

                <p class="text-lg text-slate-300 leading-relaxed mb-8 max-w-xl">
                    GSAC General Hospital brings expert medical care to your home. Book appointments, consult via video, and manage your health — all in one secure platform.
                </p>

                {{-- CTAs --}}
                <div class="flex flex-wrap gap-4 mb-10">
                    <a href="{{ route('doctors.index') }}" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl bg-blue-500 hover:bg-blue-400 text-white font-semibold text-base transition-all shadow-lg shadow-blue-500/30 hover:shadow-blue-400/40 hover:-translate-y-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                        Find a Doctor
                    </a>
                    @guest
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-semibold text-base border border-white/20 transition-all hover:-translate-y-0.5">
                        Create Free Account
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                    </a>
                    @endguest
                </div>

                {{-- Trust signals --}}
                <div class="flex flex-wrap gap-x-6 gap-y-2 text-xs text-slate-400">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        PRC-Licensed Doctors
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        End-to-End Encrypted
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Available 24/7
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        PhilHealth Accredited
                    </span>
                </div>
            </div>

            {{-- Right: mock consultation card --}}
            <div class="hidden lg:flex justify-center">
                <div class="relative w-full max-w-sm">
                    {{-- Main card --}}
                    <div class="rounded-2xl bg-white/10 backdrop-blur border border-white/20 p-5 shadow-2xl">
                        {{-- Video call area --}}
                        <div class="rounded-xl bg-slate-800/60 aspect-video flex items-center justify-center mb-4 relative overflow-hidden">
                            <div class="w-16 h-16 rounded-full bg-blue-600/40 flex items-center justify-center">
                                <svg class="w-8 h-8 text-blue-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z"/></svg>
                            </div>
                            {{-- "Live" pill --}}
                            <div class="absolute top-3 left-3 flex items-center gap-1 px-2 py-1 rounded-full bg-red-500/90 text-white text-xs font-bold">
                                <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span> LIVE
                            </div>
                            {{-- Mini self-view --}}
                            <div class="absolute bottom-3 right-3 w-14 h-10 rounded-lg bg-slate-700 border border-white/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                            </div>
                        </div>
                        {{-- Doctor info --}}
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white font-bold text-sm">DR</div>
                            <div>
                                <p class="text-white font-semibold text-sm">Dr. Maria Santos</p>
                                <p class="text-slate-400 text-xs">Internal Medicine · 5★</p>
                            </div>
                            <div class="ml-auto flex items-center gap-1 px-2 py-0.5 rounded-full bg-green-500/20 border border-green-500/30">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-400 animate-pulse"></span>
                                <span class="text-green-400 text-xs">Online</span>
                            </div>
                        </div>
                        {{-- Control bar --}}
                        <div class="flex justify-center gap-3">
                            <button class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-white/20 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18.75a6 6 0 006-6v-1.5m-6 7.5a6 6 0 01-6-6v-1.5m6 7.5v3.75m-3.75 0h7.5M12 15.75a3 3 0 01-3-3V4.5a3 3 0 116 0v8.25a3 3 0 01-3 3z"/></svg>
                            </button>
                            <button class="w-9 h-9 rounded-full bg-red-500 flex items-center justify-center text-white hover:bg-red-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                            </button>
                            <button class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-white/20 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z"/></svg>
                            </button>
                        </div>
                    </div>

                    {{-- Floating stat badge --}}
                    <div class="absolute -bottom-5 -left-5 bg-white rounded-2xl shadow-xl px-4 py-3 border border-gray-100">
                        <p class="text-xs text-gray-500 mb-0.5">This week</p>
                        <p class="text-lg font-bold text-gray-900">120 <span class="text-sm font-medium text-blue-600">consultations</span></p>
                    </div>

                    {{-- Floating rating badge --}}
                    <div class="absolute -top-4 -right-4 bg-white rounded-2xl shadow-xl px-3 py-2.5 border border-gray-100 flex items-center gap-2">
                        <div class="flex text-amber-400">
                            @for($i=0;$i<5;$i++)<svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>@endfor
                        </div>
                        <span class="text-sm font-bold text-gray-900">4.9</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================
     STATS STRIP
     ============================================================ --}}
<section class="bg-white border-b border-gray-100 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach([
                ['50+', 'Licensed Doctors', 'text-blue-600'],
                ['20+', 'Specialties', 'text-indigo-600'],
                ['5,000+', 'Patients Served', 'text-cyan-600'],
                ['4.9★', 'Average Rating', 'text-amber-500'],
            ] as [$num, $label, $color])
            <div class="text-center">
                <p class="text-3xl font-extrabold {{ $color }}">{{ $num }}</p>
                <p class="text-sm text-gray-500 mt-1">{{ $label }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================================================
     SERVICES
     ============================================================ --}}
<section id="services" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="inline-block px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold uppercase tracking-wider mb-3">Our Services</span>
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">Everything You Need, In One Place</h2>
            <p class="mt-3 text-gray-500 max-w-xl mx-auto">From booking to follow-up, GSAC Health covers every step of your healthcare journey.</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach([
                ['bg-blue-50', 'text-blue-600', 'border-blue-100', 'M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z', 'Video Consultation', 'See a doctor face-to-face from your phone or computer, anytime.'],
                ['bg-indigo-50', 'text-indigo-600', 'border-indigo-100', 'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5', 'In-Person Booking', 'Schedule face-to-face visits at GSAC General Hospital with ease.'],
                ['bg-cyan-50', 'text-cyan-600', 'border-cyan-100', 'M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 00-1.883 2.542l.857 6a2.25 2.25 0 002.227 1.932H19.05a2.25 2.25 0 002.227-1.932l.857-6a2.25 2.25 0 00-1.883-2.542m-16.5 0V6A2.25 2.25 0 016 3.75h3.879a1.5 1.5 0 011.06.44l2.122 2.12a1.5 1.5 0 001.06.44H18A2.25 2.25 0 0120.25 9v.776', 'Digital Health Records', 'Access your full medical history, lab results, and notes in one place.'],
                ['bg-emerald-50', 'text-emerald-600', 'border-emerald-100', 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z', 'E-Prescriptions', 'Receive digital prescriptions directly from your doctor after consultation.'],
                ['bg-violet-50', 'text-violet-600', 'border-violet-100', 'M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z', 'Health Monitoring', 'Track vitals and health metrics, and share data with your care team.'],
                ['bg-rose-50', 'text-rose-600', 'border-rose-100', 'M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z', 'Secure Payments', 'Pay via GCash, Maya, GrabPay, or card — all transactions encrypted.'],
            ] as [$bg, $ic, $border, $path, $title, $desc])
            <div class="bg-white rounded-2xl p-6 border {{ $border }} shadow-sm hover:shadow-md transition-shadow">
                <div class="w-11 h-11 rounded-xl {{ $bg }} flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 {{ $ic }}" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $path }}"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2">{{ $title }}</h3>
                <p class="text-sm text-gray-500 leading-relaxed">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================================================
     HOW IT WORKS
     ============================================================ --}}
<section id="how-it-works" class="py-20 bg-gradient-to-br from-blue-600 to-indigo-700 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-96 h-96 rounded-full bg-white blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-80 h-80 rounded-full bg-white blur-3xl translate-x-1/2 translate-y-1/2"></div>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <span class="inline-block px-3 py-1 rounded-full bg-white/20 text-white text-xs font-semibold uppercase tracking-wider mb-3">Simple Process</span>
            <h2 class="text-3xl sm:text-4xl font-bold text-white">How It Works</h2>
            <p class="mt-3 text-blue-100 max-w-xl mx-auto">Get from zero to consultation in minutes.</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach([
                ['1', 'Create Account', 'Sign up for free in under 2 minutes with just your email.', 'M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM3 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 019.374 21c-2.331 0-4.512-.645-6.374-1.766z'],
                ['2', 'Find Your Doctor', 'Browse specialists by specialty, rating, or availability.', 'M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z'],
                ['3', 'Book & Pay', 'Choose a time slot, pay securely via your preferred method.', 'M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z'],
                ['4', 'Start Consultation', 'Join your video call or visit in-person at your scheduled time.', 'M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z'],
            ] as [$n, $title, $desc, $path])
            <div class="relative text-center">
                {{-- Connector line (hidden on last) --}}
                @if($n !== '4')
                <div class="hidden lg:block absolute top-8 left-1/2 w-full h-px bg-white/20"></div>
                @endif
                <div class="relative z-10 w-16 h-16 rounded-2xl bg-white/20 border border-white/30 flex items-center justify-center mx-auto mb-5 backdrop-blur">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $path }}"/>
                    </svg>
                    <span class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-white text-blue-600 text-xs font-bold flex items-center justify-center shadow">{{ $n }}</span>
                </div>
                <h3 class="font-semibold text-white text-lg mb-2">{{ $title }}</h3>
                <p class="text-blue-100 text-sm leading-relaxed">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================================================
     SPECIALTIES
     ============================================================ --}}
<section id="specialties" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="inline-block px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 text-xs font-semibold uppercase tracking-wider mb-3">Medical Specialties</span>
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">Find the Right Specialist</h2>
            <p class="mt-3 text-gray-500 max-w-xl mx-auto">Our network covers over 20 specialties to address every health concern.</p>
        </div>

        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-3">
            @foreach([
                ['🫀', 'Cardiology'],
                ['🫁', 'Pulmonology'],
                ['🧠', 'Neurology'],
                ['🦷', 'Dentistry'],
                ['👁️', 'Ophthalmology'],
                ['🦴', 'Orthopedics'],
                ['👶', 'Pediatrics'],
                ['🤰', 'OB-GYN'],
                ['🩺', 'Internal Medicine'],
                ['🧴', 'Dermatology'],
                ['🔬', 'Endocrinology'],
                ['🩻', 'Radiology'],
                ['🧬', 'Oncology'],
                ['🦻', 'ENT'],
                ['🧘', 'Psychiatry'],
            ] as [$emoji, $name])
            <a href="{{ route('doctors.index') }}" class="flex flex-col items-center gap-2 p-4 rounded-xl border border-gray-100 bg-gray-50 hover:bg-blue-50 hover:border-blue-200 transition-all group text-center">
                <span class="text-2xl">{{ $emoji }}</span>
                <span class="text-xs font-medium text-gray-700 group-hover:text-blue-700 leading-tight">{{ $name }}</span>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================================================
     TESTIMONIALS
     ============================================================ --}}
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="inline-block px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-semibold uppercase tracking-wider mb-3">Patient Stories</span>
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">What Our Patients Say</h2>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            @foreach([
                ['Maria L.', 'Quezon City', 'The video consultation was seamless. Dr. Reyes diagnosed my condition in under 15 minutes. I didn\'t even have to leave my home — amazing experience!', 5],
                ['Jose R.', 'Cebu City', 'Booking an appointment took less than 2 minutes. Payment via GCash was instant. I got my e-prescription right after the consultation.', 5],
                ['Ana P.', 'Davao City', 'As a mother of three, GSAC Health has been a lifesaver. I can consult with a pediatrician at midnight without the usual emergency room wait.', 5],
            ] as [$name, $loc, $quote, $stars])
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <div class="flex text-amber-400 mb-3">
                    @for($i=0;$i<$stars;$i++)<svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>@endfor
                </div>
                <p class="text-gray-600 text-sm leading-relaxed mb-5 italic">"{{ $quote }}"</p>
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white font-bold text-xs">
                        {{ substr($name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ $name }}</p>
                        <p class="text-xs text-gray-400">{{ $loc }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================================================
     PAYMENT METHODS
     ============================================================ --}}
<section class="py-12 bg-white border-y border-gray-100">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <p class="text-center text-sm font-medium text-gray-400 uppercase tracking-wider mb-8">Accepted Payment Methods</p>
        <div class="flex flex-wrap justify-center items-center gap-6">
            @foreach(['GCash', 'Maya', 'GrabPay', 'Visa', 'Mastercard', 'PhilHealth'] as $method)
            <div class="px-5 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-sm font-semibold text-gray-600">
                {{ $method }}
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================================================
     CTA BANNER
     ============================================================ --}}
<section class="py-20 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="rounded-3xl bg-gradient-to-br from-blue-600 to-indigo-700 p-10 md:p-14 text-center relative overflow-hidden shadow-2xl shadow-blue-500/20">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 right-0 w-64 h-64 rounded-full bg-white blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 rounded-full bg-white blur-3xl"></div>
            </div>
            <div class="relative">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white mb-4">Your Health Can't Wait</h2>
                <p class="text-blue-100 text-lg mb-8 max-w-xl mx-auto">Join thousands of Filipinos who trust GSAC Health for their medical care. Create your free account and consult a doctor today.</p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-7 py-3.5 rounded-xl bg-white text-blue-700 font-bold text-base hover:bg-blue-50 transition-all shadow-lg hover:-translate-y-0.5">
                        Create Free Account
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                    </a>
                    <a href="{{ route('doctors.index') }}" class="inline-flex items-center gap-2 px-7 py-3.5 rounded-xl bg-white/15 text-white font-bold text-base border border-white/30 hover:bg-white/25 transition-all hover:-translate-y-0.5">
                        Browse Doctors
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================
     FOOTER
     ============================================================ --}}
<footer class="bg-slate-900 text-slate-400 pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-10 mb-12">
            {{-- Brand --}}
            <div>
                <a href="{{ route('home') }}" class="flex items-center mb-4">
                    <img src="{{ asset('images/gghi logo (1).png') }}" alt="GSAC General Hospital Inc." class="h-10 w-auto brightness-0 invert">
                </a>
                <p class="text-sm leading-relaxed mb-5">GSAC General Hospital's trusted teleconsultation platform, connecting patients with licensed Filipino physicians.</p>
                <div class="flex gap-3">
                    <a href="#" class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-slate-700 flex items-center justify-center transition-colors" aria-label="Facebook">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="#" class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-slate-700 flex items-center justify-center transition-colors" aria-label="Twitter/X">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                </div>
            </div>

            {{-- Services --}}
            <div>
                <h4 class="text-white font-semibold mb-4">Services</h4>
                <ul class="space-y-2 text-sm">
                    @foreach(['Video Consultation', 'In-Person Booking', 'Digital Records', 'E-Prescriptions', 'Health Monitoring'] as $svc)
                    <li><a href="{{ route('doctors.index') }}" class="hover:text-white transition-colors">{{ $svc }}</a></li>
                    @endforeach
                </ul>
            </div>

            {{-- Specialties --}}
            <div>
                <h4 class="text-white font-semibold mb-4">Specialties</h4>
                <ul class="space-y-2 text-sm">
                    @foreach(['Cardiology', 'Pediatrics', 'OB-GYN', 'Internal Medicine', 'Dermatology', 'Orthopedics'] as $sp)
                    <li><a href="{{ route('doctors.index') }}" class="hover:text-white transition-colors">{{ $sp }}</a></li>
                    @endforeach
                </ul>
            </div>

            {{-- Contact --}}
            <div>
                <h4 class="text-white font-semibold mb-4">Contact Us</h4>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 shrink-0 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                        123 Hospital Drive, Quezon City, Metro Manila
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                        +63 2 8888 0000
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                        info@gsachealth.com.ph
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0 text-green-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Open <span class="text-green-400 font-medium">24/7</span></span>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Bottom bar --}}
        <div class="border-t border-slate-800 pt-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
            <p>&copy; {{ date('Y') }} GSAC General Hospital. All rights reserved.</p>
            <div class="flex gap-5">
                <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
                <a href="#" class="hover:text-white transition-colors">Cookie Policy</a>
            </div>
        </div>
    </div>
</footer>

</body>
</html>
