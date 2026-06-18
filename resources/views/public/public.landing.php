<!DOCTYPE html>
<html lang="id" class="scroll-smooth overflow-x-hidden">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $profile->name }} | Company Profile Resmi</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="{{ $profile->description }}">
    <meta name="keywords" content="PSUP, Paduan Suara Universitas Pancasila, Paduan Suara Mahasiswa, Choir, Universitas Pancasila, Wisuda, Konser Musik, Sponsorship">
    <meta name="author" content="{{ $profile->name }}">
    <meta property="og:title" content="{{ $profile->name }} - Profil & Kemitraan Resmi">
    <meta property="og:description" content="{{ $profile->description }}">
    <meta property="og:image" content="{{ $profile->logo ? asset('storage/' . $profile->logo) : asset('images/logo_PSUP.jpeg') }}">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta name="twitter:card" content="summary_large_image">

    <!-- Tailwind & Google Fonts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        outfit: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        navy: {
                            50: '#f4f6fa',
                            100: '#e9edf5',
                            800: '#0A1128',
                            900: '#00072D',
                            950: '#000314'
                        },
                        gold: {
                            400: '#F3D279',
                            500: '#D4AF37',
                            600: '#B89324',
                            700: '#997619'
                        }
                    }
                }
            }
        }
    </script>

    <style>
        .glass-nav {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
        .text-gradient-gold {
            background: linear-gradient(135deg, #D4AF37 0%, #F3D279 50%, #B89324 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .bg-gradient-navy {
            background: linear-gradient(135deg, #0A1128 0%, #00072D 100%);
        }
        .bg-gradient-gold {
            background: linear-gradient(135deg, #D4AF37 0%, #F3D279 100%);
        }
        .music-wave {
            background-image: radial-gradient(circle at 100% 150%, rgba(212, 175, 55, 0.04) 24%, white 24%, white 28%, rgba(10, 17, 40, 0.01) 28%, rgba(10, 17, 40, 0.01) 36%, white 36%, white 40%, rgba(212, 175, 55, 0.01) 40%);
        }
        .border-gold-glow {
            border: 1px solid rgba(212, 175, 55, 0.4);
            box-shadow: 0 0 25px rgba(212, 175, 55, 0.15);
        }
        .card-hover-navy:hover {
            transform: translateY(-6px);
            border-color: rgba(212, 175, 55, 0.3);
            box-shadow: 0 12px 30px rgba(10, 17, 40, 0.06);
        }
        /* Scroll Reveal Animation Styles */
        .reveal-element {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: transform, opacity;
        }
        .reveal-element.revealed {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>

<body class="bg-white text-navy-800 font-sans antialiased overflow-x-hidden music-wave">

<!-- 🌟 FIXED HEADER / NAVIGATION -->
<div id="main-header" class="fixed top-0 inset-x-0 z-50 transition-transform duration-300 bg-gradient-to-b from-navy-950/95 via-navy-950/60 to-transparent pb-10">
    <header class="max-w-[1400px] mx-auto px-6 pt-6 pb-2 transition-all duration-300">
        <nav class="flex justify-between items-center">
            
            <!-- Logo Brand (Classic Serif) -->
            <a href="/" class="flex items-center space-x-4 group">
                <div class="w-12 h-12 flex items-center justify-center transition-transform group-hover:scale-105 rounded-full overflow-hidden border border-white/20 shadow-sm shrink-0 bg-white">
                    @if($profile->logo)
                        <img src="{{ asset('storage/' . $profile->logo) }}" alt="Logo {{ $profile->alias }}" class="w-full h-full object-contain p-1">
                    @else
                        <img src="{{ asset('images/logo_PSUP.jpeg') }}" alt="Logo PSUP" class="w-full h-full object-contain p-1">
                    @endif
                </div>
                <div class="hidden sm:block">
                    <h1 class="font-serif text-xs md:text-sm tracking-widest text-white leading-tight uppercase font-normal">
                        @php
                            $name = trim($profile->name);
                            $line1 = $name;
                            $line2 = '';
                            if (stripos($name, 'Universitas') !== false) {
                                $pos = stripos($name, 'Universitas');
                                $line1 = trim(substr($name, 0, $pos));
                                $line2 = trim(substr($name, $pos));
                            }
                        @endphp
                        @if($line2)
                            <span class="block">{{ $line1 }}</span>
                            <span class="block text-[9px] md:text-[10px] text-white/70 tracking-[0.2em] mt-0.5">{{ $line2 }}</span>
                        @else
                            {{ $name }}
                        @endif
                    </h1>
                </div>
            </a>

            <!-- Navigation Links -->
            <div class="hidden lg:flex items-center space-x-8">
                <a href="#about" class="text-[11px] font-bold text-white hover:text-gold-400 transition-colors uppercase tracking-[0.15em]">Tentang</a>
                <a href="#why-us" class="text-[11px] font-bold text-white hover:text-gold-400 transition-colors uppercase tracking-[0.15em]">Bergabung</a>
                <a href="#history" class="text-[11px] font-bold text-white hover:text-gold-400 transition-colors uppercase tracking-[0.15em]">Sejarah</a>
                <a href="#structure" class="text-[11px] font-bold text-white hover:text-gold-400 transition-colors uppercase tracking-[0.15em]">Struktur</a>
                <a href="#trainers" class="text-[11px] font-bold text-white hover:text-gold-400 transition-colors uppercase tracking-[0.15em]">Pelatih</a>
                <a href="#programs" class="text-[11px] font-bold text-white hover:text-gold-400 transition-colors uppercase tracking-[0.15em]">Proker</a>
                <a href="#achievements" class="text-[11px] font-bold text-white hover:text-gold-400 transition-colors uppercase tracking-[0.15em]">Prestasi</a>
                <a href="#events" class="text-[11px] font-bold text-white hover:text-gold-400 transition-colors uppercase tracking-[0.15em]">Kegiatan</a>
                <a href="#downloads" class="text-[11px] font-bold text-white hover:text-gold-400 transition-colors uppercase tracking-[0.15em]">Berkas</a>
            </div>

            <!-- CTA Actions -->
            <div class="flex items-center space-x-5">
                @auth
                    <a href="/login" class="bg-gold-500 hover:bg-gold-400 text-navy-950 px-6 py-2.5 rounded-none text-[11px] font-bold tracking-[0.15em] uppercase transition-colors duration-200">
                        Dashboard
                    </a>
                @else
                    <a href="/login" class="text-[11px] font-bold text-white hover:text-gold-400 transition-colors uppercase tracking-[0.15em]">
                        Masuk
                    </a>
                    @if($profile->recruitment_active)
                        <a href="/daftar" class="bg-gold-500 hover:bg-gold-400 text-navy-950 px-6 py-2.5 rounded-none text-[11px] font-bold tracking-[0.15em] uppercase transition-colors duration-200">
                            Daftar
                        </a>
                    @endif
                @endauth
            </div>

        </nav>
    </header>
</div>

<!-- Header Scroll Hide Script -->
<script>
    let lastScrollY = window.scrollY;
    window.addEventListener('scroll', () => {
        const header = document.getElementById('main-header');
        if (window.scrollY > 100 && window.scrollY > lastScrollY) {
            // Scroll down: hide header
            header.style.transform = 'translateY(-150%)';
        } else {
            // Scroll up: show header
            header.style.transform = 'translateY(0)';
        }
        lastScrollY = window.scrollY;
    });
</script>

<!-- 🌟 HERO SECTION (CLASSIC CHOIR STYLE) -->
<section class="relative min-h-[100vh] flex items-center justify-end overflow-hidden bg-navy-900 pt-20">
    <!-- Background Image (Clear, no global overlay) -->
    <div class="absolute inset-0 z-0">
        @if($profile->banner)
            <img src="{{ asset('storage/' . $profile->banner) }}" alt="Hero Banner" class="w-full h-full object-cover">
        @else
            <img src="{{ asset('images/REMINISCENTIA.jpeg') }}" alt="Choir Group" class="w-full h-full object-cover">
        @endif
    </div>

    <!-- Text block completely flushed to the right edge -->
    <div class="relative z-10 w-full lg:w-3/5 xl:w-1/2 ml-auto">
        
        <!-- Dark semi-transparent box (No blur) with left border -->
        <div class="bg-navy-950/85 border-l-[6px] border-gold-500 py-16 px-10 md:px-16 lg:py-24 w-full text-left reveal-element">
            <h1 class="font-serif text-5xl sm:text-6xl md:text-7xl lg:text-[4.5rem] text-white leading-[1.1] font-normal">
                Paduan Suara<br>
                <span class="text-gold-400">Universitas Pancasila</span>
            </h1>
        </div>
        
    </div>
</section>

<!-- Stats underneath hero section -->
<div class="bg-navy-950 py-12 border-b border-white/5 relative z-20">
    <div class="max-w-[1240px] mx-auto px-6">
        <div class="flex flex-wrap justify-center gap-8 md:gap-24">
            <div class="text-center">
                <p class="text-4xl md:text-5xl font-serif text-white mb-2">1995</p>
                <p class="text-[10px] text-white/50 uppercase tracking-widest font-sans font-bold">Tahun Berdiri</p>
            </div>
            <div class="text-center">
                <p class="text-4xl md:text-5xl font-serif text-white mb-2">{{ $memberCount ?? 80 }}+</p>
                <p class="text-[10px] text-white/50 uppercase tracking-widest font-sans font-bold">Anggota Aktif</p>
            </div>
            <div class="text-center">
                <p class="text-4xl md:text-5xl font-serif text-white mb-2">{{ $achievementCount ?? 15 }}+</p>
                <p class="text-[10px] text-white/50 uppercase tracking-widest font-sans font-bold">Penghargaan</p>
            </div>
        </div>
    </div>
</div>



<!-- 🌟 TENTANG KAMI (ABOUT) -->
<section id="about" class="py-24 relative overflow-hidden">
    <div class="max-w-[1240px] mx-auto px-6 relative z-10">
        
        <!-- Top Text Grid (Matches 'Choral excellence' reference) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center mb-16 reveal-element">
            <!-- Left side (Full text) -->
            <div class="lg:col-span-7 space-y-6">
                <h2 class="font-serif text-5xl md:text-6xl lg:text-[4.5rem] text-navy-900 leading-[1.1] font-normal">
                    Harmoni Vokal
                </h2>
                <p class="text-slate-800 font-sans text-base sm:text-lg font-normal leading-relaxed">
                    {{ $profile->description }}
                </p>
                <div class="border-l-4 border-gold-500 pl-6">
                    <p class="text-slate-600 font-sans text-sm sm:text-base italic leading-relaxed">
                        "Paduan suara bukan sekadar teknik vokal yang baik, melainkan penyelarasan ego untuk melahirkan satu harmoni suara yang utuh dan menyentuh jiwa pendengar."
                    </p>
                </div>
            </div>
            
            <!-- Right side (Logo PSUP) -->
            <div class="lg:col-span-5 flex justify-center items-center">
                <div class="w-64 h-64 md:w-80 md:h-80 overflow-hidden rounded-lg shadow-lg border border-slate-100 hover:scale-[1.02] transition-transform duration-300">
                    @if($profile->logo)
                        <img src="{{ asset('storage/' . $profile->logo) }}" alt="Logo {{ $profile->alias }}" class="w-full h-full object-cover">
                    @else
                        <img src="{{ asset('images/logo_PSUP.jpeg') }}" alt="Logo PSUP" class="w-full h-full object-cover">
                    @endif
                </div>
            </div>
        </div>



    </div>
</section>

<!-- 🌟 MENGAPA BERMITRA (WHY US) -->
<section id="why-us" class="py-24 relative overflow-hidden">
    <div class="max-w-[1240px] mx-auto px-6 relative z-10">
        
        <!-- Section Title (Matches 'Powerful performances' reference) -->
        <h2 class="font-serif text-5xl md:text-6xl lg:text-[5rem] text-navy-900 leading-[1.05] font-normal mb-12 md:mb-20">
            Alasan Bergabung<br>PSUP
        </h2>

        <!-- Overlapping Layout -->
        <div class="relative w-full reveal-element">
            <!-- Image on the right -->
            <div class="w-full md:w-4/5 ml-auto h-[400px] md:h-[550px] relative shadow-xl">
                 <img src="{{ asset('images/kontan.jpg') }}" alt="Kemitraan" class="w-full h-full object-cover">
            </div>

            <!-- Overlapping White Box on the left -->
            <div class="static md:absolute top-1/2 md:-translate-y-1/2 left-0 w-full md:w-[450px] lg:w-[500px] bg-white p-8 md:p-12 lg:p-16 shadow-2xl z-10 border-t-[6px] border-gold-500">
                <h3 class="font-serif font-normal text-2xl md:text-3xl text-navy-900 mb-4 tracking-tight">Mengapa Bergabung dengan PSUP?</h3>
                <p class="text-slate-600 font-sans text-sm md:text-base leading-relaxed mb-8">
                    Bergabung bersama kami memberikan kesempatan luar biasa untuk mengembangkan bakat seni olah suara, membangun relasi yang solid, dan meraih prestasi bersama.
                </p>
                
                <ul class="space-y-4 font-sans text-sm text-navy-900 font-semibold tracking-wide">
                    <li class="flex items-center gap-4">
                        <div class="w-2 h-2 rounded-full bg-gold-500"></div> Mengembangkan Teknik & Karakter Vokal
                    </li>
                    <li class="flex items-center gap-4">
                        <div class="w-2 h-2 rounded-full bg-gold-500"></div> Meraih Prestasi di Dalam Maupun Luar Negeri
                    </li>
                    <li class="flex items-center gap-4">
                        <div class="w-2 h-2 rounded-full bg-gold-500"></div> Kesempatan Tampil di Konser Resmi
                    </li>
                    <li class="flex items-center gap-4">
                        <div class="w-2 h-2 rounded-full bg-gold-500"></div> Pengalaman Organisasi & Kerja Sama
                    </li>
                </ul>
            </div>
        </div>

    </div>
</section>

<!-- 🌟 VISI MISI -->
<section id="vision-mission" class="py-28 bg-white overflow-hidden">
    <div class="max-w-[1240px] mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-20 items-start">
            
            <!-- Visi (Left Column) -->
            <div class="lg:col-span-5 space-y-8 reveal-element">
                <div>
                    <span class="text-[10px] font-black text-navy-800 uppercase tracking-widest">ARAH STRATEGIS</span>
                    <h2 class="font-serif text-5xl md:text-6xl text-navy-900 leading-[1.1] font-normal mt-2">
                        Visi Kami
                    </h2>
                </div>
                <blockquote class="relative">
                    <span class="absolute -top-10 -left-6 text-[8rem] font-serif text-gold-500/10 leading-none pointer-events-none">“</span>
                    <p class="font-serif text-xl sm:text-2xl text-navy-850 leading-relaxed italic relative z-10 pl-2">
                        {{ $profile->vision }}
                    </p>
                </blockquote>
                <div class="w-16 h-1 bg-gradient-gold rounded-full"></div>
            </div>

            <!-- Misi (Right Column) -->
            <div class="lg:col-span-7 space-y-8 reveal-element">
                <div>
                    <span class="text-[10px] font-black text-navy-800 uppercase tracking-widest">LANGKAH NYATA</span>
                    <h2 class="font-serif text-5xl md:text-6xl text-navy-900 leading-[1.1] font-normal mt-2">
                        Misi Kami
                    </h2>
                </div>
                
                <div class="space-y-6 sm:space-y-8">
                    @php
                        $misiPoints = array_filter(explode("\n", $profile->mission));
                    @endphp
                    @forelse($misiPoints as $index => $misi)
                        <div class="flex gap-5 items-start">
                            <span class="w-8 h-8 rounded-xl bg-navy-50 border border-slate-200 flex items-center justify-center text-gold-600 text-sm font-bold shrink-0 shadow-sm">
                                {{ sprintf('%02d', $index + 1) }}
                            </span>
                            <p class="font-serif text-xl sm:text-2xl text-navy-850 leading-relaxed pt-0.5">{{ trim($misi) }}</p>
                        </div>
                    @empty
                        <p class="text-slate-400 text-xs">Misi belum ditentukan.</p>
                    @endforelse
                </div>
                <div class="w-16 h-1 bg-gradient-gold rounded-full"></div>
            </div>

        </div>
    </div>
</section>

<!-- 🌟 TIMELINE SEJARAH -->
<section id="history" class="py-24 bg-slate-50 border-t border-b border-slate-100">
    <div class="max-w-[1000px] mx-auto px-6">
        <div class="text-center mb-20">
            <span class="text-[10px] font-black text-gold-600 uppercase tracking-widest">TIMELINE PERJALANAN</span>
            <h3 class="font-serif font-normal text-3xl sm:text-4xl text-navy-900 mt-2">Sejarah & Milestone</h3>
            <div class="w-16 h-1 bg-gradient-gold mx-auto mt-4 rounded-full"></div>
        </div>

        <div class="relative border-l-2 border-slate-200 ml-4 md:ml-36">
            @forelse($histories as $history)
                <div class="mb-14 relative pl-8 md:pl-0 reveal-element">
                    <!-- Point marker -->
                    <div class="absolute -left-[9px] top-2 w-[16px] h-[16px] rounded-full border-4 border-white bg-gold-500 shadow-md"></div>
                    
                    <!-- Left side Year (MD screens) -->
                    <div class="hidden md:block absolute -left-36 top-1 w-28 text-right">
                        <span class="font-serif font-normal text-2xl text-navy-900">{{ $history->year }}</span>
                    </div>

                    <!-- Top side Year (Mobile screens) -->
                    <div class="md:hidden block mb-2">
                        <span class="font-serif font-normal text-xl text-gold-600">{{ $history->year }}</span>
                    </div>

                    <!-- Content card -->
                    <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 shadow-sm hover:shadow-md transition-shadow">
                        <h4 class="font-serif font-normal text-lg text-navy-900 mb-3">{{ $history->title }}</h4>
                        <p class="text-slate-500 text-xs sm:text-sm leading-relaxed mb-6">{{ $history->description }}</p>
                        
                        @if($history->photo)
                            <div class="max-w-md rounded-2xl overflow-hidden border border-slate-100 cursor-pointer" onclick="zoomStructure('{{ asset('storage/' . $history->photo) }}')">
                                <img src="{{ asset('storage/' . $history->photo) }}" alt="{{ $history->title }}" class="w-full h-auto object-cover hover:scale-[1.01] transition-transform duration-300">
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-16 text-slate-400 text-xs bg-white border border-slate-100 rounded-3xl pl-8 md:pl-0">
                    Sejarah belum diunggah.
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- 🌟 STRUKTUR KEPENGURUSAN -->
<section id="structure" class="py-24 bg-white">
    <div class="max-w-[950px] mx-auto px-6">
        <div class="text-center mb-20">
            <span class="text-[10px] font-black text-navy-800 uppercase tracking-widest">STRUKTUR RESMI</span>
            <h3 class="font-serif font-normal text-3xl sm:text-4xl text-navy-900 mt-2">Bagan Organisasi</h3>
            <div class="w-16 h-1 bg-gradient-navy mx-auto mt-4 rounded-full"></div>
            <p class="text-slate-500 text-xs sm:text-sm mt-4">Struktur kepengurusan Paduan Suara Universitas Pancasila.</p>
        </div>

        <div class="text-center max-w-[760px] mx-auto">
            @if($profile->structure_image)
                <div class="relative group cursor-pointer overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-md reveal-element" onclick="zoomStructure('{{ asset('storage/' . $profile->structure_image) }}')">
                    <img src="{{ asset('storage/' . $profile->structure_image) }}" alt="Bagan PSUP" class="w-full h-auto transition-transform duration-500 group-hover:scale-[1.02] block">
                    <div class="absolute inset-0 bg-navy-950/45 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                        <span class="bg-white text-navy-900 px-5 py-3 rounded-2xl font-bold text-xs shadow-lg flex items-center gap-2">
                            <i class="ph ph-magnifying-glass-plus text-base"></i> Klik Untuk Zoom Bagan
                        </span>
                    </div>
                </div>
            @else
                <div class="bg-slate-50 border border-slate-150 rounded-[36px] p-20 text-slate-400">
                    <i class="ph ph-tree-structure text-5xl mb-4 text-slate-300"></i>
                    <p class="font-bold text-sm text-slate-500">Bagan Struktur Belum Diunggah</p>
                    <p class="text-xs text-slate-400 mt-1">Pengurus belum merilis bagan kepengurusan untuk periode aktif.</p>
                </div>
            @endif
        </div>
    </div>
</section>

<!-- 🌟 TIM PELATIH & PEMBINA -->
<section id="trainers" class="py-24 bg-white border-t border-slate-100">
    <div class="max-w-[1240px] mx-auto px-6">
        <div class="text-center mb-20">
            <span class="text-[10px] font-black text-navy-800 uppercase tracking-widest">Choir Coaching</span>
            <h3 class="font-serif font-normal text-3xl sm:text-4xl text-navy-900 mt-2">Pelatih Paduan Suara Universitas Pancasila</h3>
            <div class="w-16 h-1 bg-gradient-navy mx-auto mt-4 rounded-full"></div>
            <p class="text-slate-500 text-xs sm:text-sm mt-4 max-w-lg mx-auto leading-relaxed">
                Dipimpin oleh para profesional yang berdedikasi dalam membimbing vokal dan musikalitas anggota untuk mencapai performa terbaik.
            </p>
        </div>

        @if($trainers->count() === 1)
            @php 
                $trainer = $trainers->first(); 
            @endphp
            <div class="max-w-[850px] mx-auto flex flex-col md:flex-row items-center gap-10 md:gap-16 relative reveal-element">
                <!-- Coach Image -->
                <div class="relative shrink-0">
                    <div class="w-48 h-48 sm:w-60 sm:h-60 rounded-full overflow-hidden border-4 border-gold-400 p-1.5 relative bg-slate-50 shadow-md">
                        @if($trainer->photo)
                            <img src="{{ asset('storage/' . $trainer->photo) }}" alt="{{ $trainer->name }}" class="w-full h-full object-cover rounded-full">
                        @else
                            <div class="w-full h-full rounded-full bg-navy-50 flex items-center justify-center font-bold text-navy-800 text-5xl">
                                {{ substr($trainer->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Coach Details -->
                <div class="flex-1 text-center md:text-left">
                    <span class="inline-block px-4 py-1.5 rounded-full text-[10px] font-black bg-gold-50 text-gold-700 border border-gold-100/50 mb-3 uppercase tracking-widest">
                        {{ $trainer->specialty }}
                    </span>
                    <h4 class="font-serif font-normal text-3xl sm:text-4xl text-navy-900 mb-4 leading-tight">
                        {{ $trainer->name }}
                    </h4>
                    <p class="text-slate-500 text-sm sm:text-base leading-relaxed max-w-xl">
                        Berdedikasi tinggi dalam membimbing teknik vokal, pembawaan lagu, serta musikalitas Paduan Suara Universitas Pancasila untuk terus mengukir berbagai prestasi gemilang di tingkat nasional maupun internasional.
                    </p>
                </div>
            </div>
        @else
            <div class="flex flex-wrap justify-center gap-8">
                @forelse($trainers as $trainer)
                    <div class="bg-white border border-slate-100 rounded-[32px] p-6 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col items-center text-center w-full sm:w-[calc(50%-1rem)] lg:w-[calc(33.333%-1.5rem)] max-w-[280px] card-hover-navy reveal-element">
                        <div class="w-32 h-32 rounded-full overflow-hidden border-2 border-gold-400 p-1 mb-6 shrink-0 relative bg-slate-50">
                            @if($trainer->photo)
                                <img src="{{ asset('storage/' . $trainer->photo) }}" alt="{{ $trainer->name }}" class="w-full h-full object-cover rounded-full">
                            @else
                                <div class="w-full h-full rounded-full bg-navy-50 flex items-center justify-center font-bold text-navy-800 text-2xl">
                                    {{ substr($trainer->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <h4 class="font-serif font-normal text-base text-navy-900 mb-1 leading-tight">{{ $trainer->name }}</h4>
                        <span class="inline-block px-3 py-1 rounded-full text-[9px] font-bold bg-gold-50 text-gold-700 border border-gold-100/50 mb-4">{{ $trainer->specialty }}</span>
                        <p class="text-slate-400 text-[11px] leading-relaxed mb-6">
                            Berdedikasi untuk melatih teknik vokal, harmoni, dan interpretasi musik anggota PSUP.
                        </p>
                        @if($trainer->phone)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $trainer->phone) }}" target="_blank" class="text-xs text-navy-900 hover:text-gold-600 font-bold flex items-center gap-1.5 transition-colors">
                                <i class="ph ph-whatsapp-logo text-base"></i> Hubungi WhatsApp
                            </a>
                        @endif
                    </div>
                @empty
                    <div class="w-full text-center py-16 text-slate-400 text-xs">
                        Belum ada data pelatih terdaftar.
                    </div>
                @endforelse
            </div>
        @endif
    </div>
</section>

<!-- 🌟 PROGRAM KERJA UNGGULAN -->
<section id="programs" class="py-24 bg-slate-50 border-t border-b border-slate-100">
    <div class="max-w-[1240px] mx-auto px-6">
        <div class="text-center mb-20">
            <span class="text-[10px] font-black text-gold-600 uppercase tracking-widest">PROGRAM KERJA</span>
            <h3 class="font-serif font-normal text-3xl sm:text-4xl text-navy-900 mt-2">Agenda Kegiatan Unggulan</h3>
            <div class="w-16 h-1 bg-gradient-gold mx-auto mt-4 rounded-full"></div>
        </div>

        <div class="flex flex-wrap justify-center gap-8">
            @forelse($programs as $program)
                <div class="bg-white border border-slate-100 hover:border-gold-500/20 rounded-[28px] p-7 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between w-full md:w-[calc(50%-1rem)] lg:w-[calc(33.333%-1.5rem)] max-w-sm reveal-element">
                    <div>
                        <div class="flex justify-between items-center mb-5">
                            <span class="inline-block px-3 py-1 rounded-full text-[9px] font-black bg-navy-50 text-navy-800 border border-slate-100">
                                @if($program->activity_type === 'Event')
                                    Event {{ $program->event_category }}
                                @else
                                    Kompetisi
                                @endif
                            </span>
                            <span class="inline-block px-3 py-1 rounded-full text-[9px] font-black 
                                @if($program->status == 'Selesai') bg-emerald-50 text-emerald-800 border border-emerald-100
                                @elseif($program->status == 'Berjalan') bg-blue-50 text-blue-800 border border-blue-100
                                @else bg-amber-50 text-amber-800 border border-amber-100 @endif">
                                {{ $program->status }}
                            </span>
                        </div>
                        
                        <h4 class="font-serif font-normal text-lg text-navy-900 mb-3 leading-tight">{{ $program->name }}</h4>
                        <p class="text-slate-500 text-xs sm:text-sm leading-relaxed mb-6">
                            {{ $program->description }}
                        </p>
                    </div>

                    <div class="border-t border-slate-100 pt-5 space-y-2 text-xs text-slate-400">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-1.5">
                                <i class="ph ph-calendar"></i>
                                <span>{{ date('d M Y', strtotime($program->start_date)) }}</span>
                            </div>
                            <span>s/d</span>
                            <div class="flex items-center gap-1.5">
                                <i class="ph ph-calendar"></i>
                                <span>{{ date('d M Y', strtotime($program->end_date)) }}</span>
                            </div>
                        </div>
                        @if($program->venue)
                        <div class="flex items-center gap-1.5">
                            <i class="ph ph-map-pin text-gold-500"></i>
                            <span>{{ $program->venue }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="w-full text-center py-16 text-slate-400 text-xs">
                    Belum ada program kerja yang terdaftar.
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- 🌟 PRESTASI ORGANISASI -->
<section id="achievements" class="py-24 bg-white">
    <div class="max-w-[1240px] mx-auto px-6">
        <div class="text-center mb-20">
            <span class="text-[10px] font-black text-navy-800 uppercase tracking-widest">AWARDS & LAURELS</span>
            <h3 class="font-serif font-normal text-3xl sm:text-4xl text-navy-900 mt-2">Daftar Prestasi & Medali</h3>
            <div class="w-16 h-1 bg-gradient-navy mx-auto mt-4 rounded-full"></div>
        </div>

        <div class="flex flex-wrap justify-center gap-8">
            @forelse($achievements as $achievement)
                <div class="bg-white border border-slate-100 rounded-[28px] overflow-hidden shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between w-full md:w-[calc(50%-1rem)] lg:w-[calc(33.333%-1.5rem)] max-w-sm reveal-element">
                    <div>
                        @if($achievement->photo)
                            <div class="aspect-video overflow-hidden bg-slate-50">
                                <img src="{{ asset('storage/' . $achievement->photo) }}" alt="{{ $achievement->title }}" class="w-full h-full object-cover">
                            </div>
                        @else
                            <div class="aspect-video bg-navy-50 flex items-center justify-center text-navy-800 text-4xl">
                                <i class="ph ph-trophy"></i>
                            </div>
                        @endif
                        
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-[10px] font-black text-gold-600 uppercase tracking-wider">
                                    <i class="ph ph-award"></i> Penghargaan Utama
                                </span>
                                <span class="text-xs text-slate-400 font-bold">{{ date('Y', strtotime($achievement->date)) }}</span>
                            </div>
                            <h4 class="font-serif font-normal text-base sm:text-lg text-navy-900 mb-2 leading-tight">{{ $achievement->title }}</h4>
                            <p class="text-slate-500 text-xs sm:text-sm leading-relaxed">{{ $achievement->description }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="w-full text-center py-16 text-slate-400 text-xs">
                    Belum ada prestasi yang ditambahkan.
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- 🌟 PENAMPILAN DAN KEGIATAN -->
<section id="events" class="py-24 bg-slate-50 border-t border-b border-slate-100">
    <div class="max-w-[1240px] mx-auto px-6">
        <div class="text-center mb-20">
            <span class="text-[10px] font-black text-gold-600 uppercase tracking-widest">SHOWCASE LIVE</span>
            <h3 class="font-serif font-normal text-3xl sm:text-4xl text-navy-900 mt-2">Penampilan & Kegiatan</h3>
            <div class="w-16 h-1 bg-gradient-gold mx-auto mt-4 rounded-full"></div>
        </div>

        <div class="flex flex-wrap justify-center gap-8">
            @forelse($agendas as $agenda)
                <div class="bg-white border border-slate-100 rounded-[28px] p-7 shadow-sm hover:shadow-md transition-all w-full md:w-[calc(50%-1rem)] lg:w-[calc(33.333%-1.5rem)] max-w-sm reveal-element">
                    <span class="inline-block px-3 py-1 rounded-full text-[9px] font-black bg-navy-50 text-navy-900 mb-4 border border-slate-100">{{ $agenda->status }}</span>
                    <h4 class="font-serif font-normal text-lg text-navy-900 mb-3 leading-tight">{{ $agenda->title }}</h4>
                    <p class="text-slate-500 text-xs sm:text-sm leading-relaxed mb-6">{{ $agenda->description }}</p>
                    
                    <div class="border-t border-slate-100 pt-5 text-xs text-slate-400 space-y-2.5">
                        <div class="flex items-center gap-2.5">
                            <i class="ph ph-calendar text-gold-500 text-sm"></i>
                            <span>{{ date('d F Y', strtotime($agenda->performance_date)) }}</span>
                        </div>
                        @if($agenda->performance_time)
                            <div class="flex items-center gap-2.5">
                                <i class="ph ph-clock text-gold-500 text-sm"></i>
                                <span>{{ $agenda->performance_time }} WIB</span>
                            </div>
                        @endif
                        <div class="flex items-start gap-2.5">
                            <i class="ph ph-map-pin text-gold-500 text-sm mt-0.5"></i>
                            <span>{{ $agenda->venue }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="w-full text-center py-16 text-slate-400 text-xs">
                    Tidak ada agenda terdekat.
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- 🌟 DOKUMENTASI/GALERI -->
<section id="gallery" class="py-24 bg-white">
    <div class="max-w-[1240px] mx-auto px-6">
        <div class="text-center mb-20">
            <span class="text-[10px] font-black text-navy-800 uppercase tracking-widest">DOKUMENTASI DOKUMEN</span>
            <h3 class="font-serif font-normal text-3xl sm:text-4xl text-navy-900 mt-2">Galeri Kegiatan</h3>
            <div class="w-16 h-1 bg-gradient-navy mx-auto mt-4 rounded-full"></div>
        </div>

        <div class="flex flex-wrap justify-center gap-6">
            @forelse($galleries as $gallery)
                <div class="bg-white border border-slate-100 rounded-3xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 group cursor-pointer w-full sm:w-[calc(50%-1rem)] lg:w-[calc(33.333%-1rem)] max-w-sm reveal-element" onclick="zoomStructure('{{ asset('storage/' . $gallery->file_path) }}')">
                    <div class="aspect-video overflow-hidden bg-slate-50 relative">
                        <img src="{{ asset('storage/' . $gallery->file_path) }}" alt="{{ $gallery->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-navy-950/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <span class="w-11 h-11 rounded-full bg-white flex items-center justify-center text-navy-900 shadow-md">
                                <i class="ph ph-magnifying-glass-plus text-lg font-bold"></i>
                            </span>
                        </div>
                    </div>
                    <div class="p-5">
                        <h4 class="font-bold text-sm text-navy-900 line-clamp-1 leading-tight">{{ $gallery->title }}</h4>
                        <p class="text-slate-400 text-xs mt-1.5 line-clamp-2">{{ $gallery->description }}</p>
                    </div>
                </div>
            @empty
                <div class="w-full text-center py-16 text-slate-400 text-xs">
                    Belum ada foto dokumentasi.
                </div>
            @endforelse
        </div>
    </div>
</section>



<!-- 🌟 OPEN RECRUITMENT -->
@if($profile->recruitment_active)
    <section id="recruitment" class="py-24 bg-white">
        <div class="max-w-[950px] mx-auto px-6">
            <div class="text-center mb-16">
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-800 border border-emerald-100 uppercase tracking-widest mb-4">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Pendaftaran Calon Anggota Baru
                </span>
                <h3 class="font-serif font-normal text-3xl sm:text-4xl text-navy-900 mt-2">Mulai Perjalanan Musikmu Bersama Kami</h3>
                <div class="w-16 h-1 bg-gradient-gold mx-auto mt-4 rounded-full"></div>
            </div>

            <div class="bg-slate-50 border border-slate-100 rounded-[40px] p-8 sm:p-12 shadow-sm relative overflow-hidden">
                <div class="relative z-10">
                    <!-- Top section: Title, Date and Action Button -->
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8 pb-8 border-b border-slate-200 mb-8">
                        <div class="max-w-2xl">
                            <div class="flex items-center gap-2.5 text-gold-600 mb-3">
                                <i class="ph ph-calendar-blank text-lg"></i>
                                <span class="text-xs font-black uppercase tracking-widest">
                                    Periode Pendaftaran: 
                                    {{ $profile->recruitment_start_date ? $profile->recruitment_start_date->format('d M Y') : '' }} 
                                    s/d 
                                    {{ $profile->recruitment_end_date ? $profile->recruitment_end_date->format('d M Y') : '' }}
                                </span>
                            </div>
                            <h4 class="font-serif font-normal text-2xl sm:text-3xl text-navy-900 leading-tight">
                                Jadilah bagian dari generasi harmoni paduan suara Universitas Pancasila selanjutnya.
                            </h4>
                        </div>
                        <div class="shrink-0 w-full lg:w-auto">
                            <a href="/daftar" class="inline-flex items-center justify-center gap-2 w-full lg:w-auto bg-gradient-to-r from-gold-400 to-gold-500 hover:from-gold-500 hover:to-gold-600 text-navy-950 px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg hover:shadow-gold-500/20 transition-all duration-300 transform hover:-translate-y-0.5">
                                Gabung PSUP Sekarang <i class="ph ph-arrow-right text-base"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Bottom section: Requirements and Stages in 2 columns -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <!-- Left Column: Requirements -->
                        <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8">
                            <h5 class="text-navy-900 font-extrabold text-sm uppercase tracking-wider mb-5 flex items-center gap-2">
                                <i class="ph ph-list-checks text-lg text-gold-500"></i> Persyaratan Utama
                            </h5>
                            <div class="space-y-4">
                                @php
                                    $reqs = array_filter(explode("\n", $profile->recruitment_requirements));
                                @endphp
                                @forelse($reqs as $req)
                                    <div class="flex gap-3 text-sm text-slate-600">
                                        <i class="ph ph-check-circle text-gold-500 text-lg shrink-0"></i>
                                        <span class="leading-relaxed">{{ trim($req) }}</span>
                                    </div>
                                @empty
                                    <p class="text-slate-400 text-xs">Persyaratan belum ditentukan.</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- Right Column: Stages -->
                        <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8">
                            <h5 class="text-navy-900 font-extrabold text-sm uppercase tracking-wider mb-5 flex items-center gap-2">
                                <i class="ph ph-steps text-lg text-gold-500"></i> Tahapan Registrasi & Audisi
                            </h5>
                            <div class="space-y-4">
                                @php
                                    $stages = array_filter(explode("\n", $profile->recruitment_stages));
                                @endphp
                                @forelse($stages as $stage)
                                    <div class="flex gap-3 text-sm text-slate-600">
                                        <i class="ph ph-arrow-circle-right text-gold-500 text-lg shrink-0"></i>
                                        <span class="leading-relaxed">{{ trim($stage) }}</span>
                                    </div>
                                @empty
                                    <p class="text-slate-400 text-xs">Tahapan seleksi belum ditentukan.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif

<!-- 🌟 DOWLOADS / RESMI BERKAS KEMITRAAN -->
<section id="downloads" class="py-24 bg-white border-t border-slate-100">
    <div class="max-w-[1240px] mx-auto px-6">
        <div class="text-center mb-20">
            <span class="text-[10px] font-black text-gold-600 uppercase tracking-widest">PUSAT BERKAS</span>
            <h3 class="font-serif font-normal text-3xl sm:text-4xl text-navy-900 mt-2">Unduh Dokumen Kerja Sama</h3>
            <div class="w-16 h-1 bg-gradient-gold mx-auto mt-4 rounded-full"></div>
            <p class="text-slate-500 text-xs sm:text-sm mt-4">Silakan unduh dokumen legal pendukung di bawah ini untuk proposal sponsorship atau pengenalan umum.</p>
        </div>

        <div class="flex flex-wrap justify-center gap-8 max-w-[1050px] mx-auto">
            
            <!-- Company Profile -->
            <div class="bg-slate-50 border border-slate-100 rounded-3xl p-6 sm:p-8 flex flex-col justify-between shadow-sm w-full md:w-[calc(50%-1rem)] lg:w-[calc(33.333%-1.5rem)] max-w-sm reveal-element">
                <div>
                    <div class="w-11 h-11 rounded-2xl bg-white border border-slate-200 flex items-center justify-center text-red-500 text-xl font-bold mb-6">
                        <i class="ph ph-file-pdf"></i>
                    </div>
                    <h4 class="font-serif font-normal text-base text-navy-900 mb-2">Company Profile</h4>
                    <p class="text-xs text-slate-400 leading-relaxed mb-6">Dokumen lengkap yang mendeskripsikan visi misi, sejarah, kepengurusan, prestasi, dan galeri umum PSUP.</p>
                </div>
                @if($profile->company_profile_pdf)
                    <a href="{{ asset('storage/' . $profile->company_profile_pdf) }}" target="_blank" class="w-full bg-navy-900 text-white hover:bg-gold-600 hover:text-navy-900 py-3.5 rounded-xl font-bold text-xs text-center transition-colors uppercase tracking-wider block">
                        Unduh PDF Berkas
                    </a>
                @else
                    <button class="w-full bg-slate-200 text-slate-400 py-3.5 rounded-xl font-bold text-xs text-center cursor-not-allowed uppercase tracking-wider block" disabled>
                        Belum Tersedia
                    </button>
                @endif
            </div>

            <!-- Sponsorship Proposal -->
            <div class="bg-slate-50 border border-slate-100 rounded-3xl p-6 sm:p-8 flex flex-col justify-between shadow-sm w-full md:w-[calc(50%-1rem)] lg:w-[calc(33.333%-1.5rem)] max-w-sm reveal-element">
                <div>
                    <div class="w-11 h-11 rounded-2xl bg-white border border-slate-200 flex items-center justify-center text-red-500 text-xl font-bold mb-6">
                        <i class="ph ph-file-pdf"></i>
                    </div>
                    <h4 class="font-serif font-normal text-base text-navy-900 mb-2">Sponsorship Proposal</h4>
                    <p class="text-xs text-slate-400 leading-relaxed mb-6">Paket benefit, rincian branding, anggaran biaya konser, serta mekanisme kerja sama sponsor dengan PSUP.</p>
                </div>
                @if($profile->sponsorship_proposal_pdf)
                    <a href="{{ asset('storage/' . $profile->sponsorship_proposal_pdf) }}" target="_blank" class="w-full bg-navy-900 text-white hover:bg-gold-600 hover:text-navy-900 py-3.5 rounded-xl font-bold text-xs text-center transition-colors uppercase tracking-wider block">
                        Unduh PDF Berkas
                    </a>
                @else
                    <button class="w-full bg-slate-200 text-slate-400 py-3.5 rounded-xl font-bold text-xs text-center cursor-not-allowed uppercase tracking-wider block" disabled>
                        Belum Tersedia
                    </button>
                @endif
            </div>

            <!-- Media Kit -->
            <div class="bg-slate-50 border border-slate-100 rounded-3xl p-6 sm:p-8 flex flex-col justify-between shadow-sm w-full md:w-[calc(50%-1rem)] lg:w-[calc(33.333%-1.5rem)] max-w-sm reveal-element">
                <div>
                    <div class="w-11 h-11 rounded-2xl bg-white border border-slate-200 flex items-center justify-center text-red-500 text-xl font-bold mb-6">
                        <i class="ph ph-file-pdf"></i>
                    </div>
                    <h4 class="font-serif font-normal text-base text-navy-900 mb-2">Media Kit & Logo</h4>
                    <p class="text-xs text-slate-400 leading-relaxed mb-6">Aset identitas visual resmi PSUP (logo resolusi tinggi, palet warna, tipografi) untuk kolaborasi publikasi.</p>
                </div>
                @if($profile->media_kit_pdf)
                    <a href="{{ asset('storage/' . $profile->media_kit_pdf) }}" target="_blank" class="w-full bg-navy-900 text-white hover:bg-gold-600 hover:text-navy-900 py-3.5 rounded-xl font-bold text-xs text-center transition-colors uppercase tracking-wider block">
                        Unduh PDF Berkas
                    </a>
                @else
                    <button class="w-full bg-slate-200 text-slate-400 py-3.5 rounded-xl font-bold text-xs text-center cursor-not-allowed uppercase tracking-wider block" disabled>
                        Belum Tersedia
                    </button>
                @endif
            </div>

        </div>
    </div>
</section>

<!-- 🌟 KONTAK & DETAIL SEKRERATARIAT -->
<section id="contact" class="py-24 bg-slate-50 border-t border-slate-100">
    <div class="max-w-[1240px] mx-auto px-6">
        <div class="text-center mb-20">
            <span class="text-[10px] font-black text-navy-800 uppercase tracking-widest">KONTAK KAMI</span>
            <h3 class="font-serif font-normal text-3xl sm:text-4xl text-navy-900 mt-2">Hubungi Sekretariat</h3>
            <div class="w-16 h-1 bg-gradient-navy mx-auto mt-4 rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            
            <!-- Contact Card -->
            <div class="lg:col-span-5 bg-white border border-slate-100 rounded-[32px] p-8 sm:p-10 flex flex-col justify-between shadow-sm">
                <div>
                    <h4 class="font-serif font-normal text-xl text-navy-900 mb-8 leading-tight">Hubungi Kami Secara Langsung</h4>
                    
                    <div class="space-y-6">
                        <div class="flex gap-4 items-start">
                            <div class="w-11 h-11 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center text-navy-900 text-lg shrink-0">
                                <i class="ph ph-map-pin"></i>
                            </div>
                            <div>
                                <h5 class="font-bold text-[10px] text-slate-400 uppercase tracking-wider mb-1">Sekretariat</h5>
                                <p class="text-xs text-slate-500 leading-relaxed">{{ $profile->address }}</p>
                            </div>
                        </div>

                        <div class="flex gap-4 items-start">
                            <div class="w-11 h-11 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center text-navy-900 text-lg shrink-0">
                                <i class="ph ph-envelope"></i>
                            </div>
                            <div>
                                <h5 class="font-bold text-[10px] text-slate-400 uppercase tracking-wider mb-1">E-mail Resmi</h5>
                                <a href="mailto:{{ $profile->email }}" class="text-xs text-blue-600 hover:underline font-bold">{{ $profile->email }}</a>
                            </div>
                        </div>

                        <div class="flex gap-4 items-start">
                            <div class="w-11 h-11 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center text-navy-900 text-lg shrink-0">
                                <i class="ph ph-phone"></i>
                            </div>
                            <div>
                                <h5 class="font-bold text-[10px] text-slate-400 uppercase tracking-wider mb-1">Kontak Humas</h5>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $profile->phone) }}" target="_blank" class="text-xs text-blue-600 hover:underline font-bold">{{ $profile->phone }}</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Social links -->
                <div class="border-t border-slate-100 pt-8 mt-10">
                    <h5 class="font-bold text-[10px] text-slate-400 uppercase tracking-wider mb-4">Temukan Kami di Media Sosial</h5>
                    <div class="flex gap-4">
                        @if($profile->instagram)
                            <a href="{{ $profile->instagram }}" target="_blank" class="w-11 h-11 rounded-full bg-slate-50 flex items-center justify-center border border-slate-200 text-navy-900 hover:border-gold-500 hover:text-gold-500 hover:bg-white transition-all shadow-sm">
                                <i class="ph ph-instagram-logo text-lg font-bold"></i>
                            </a>
                        @endif
                        @if($profile->youtube)
                            <a href="{{ $profile->youtube }}" target="_blank" class="w-11 h-11 rounded-full bg-slate-50 flex items-center justify-center border border-slate-200 text-navy-900 hover:border-gold-500 hover:text-gold-500 hover:bg-white transition-all shadow-sm">
                                <i class="ph ph-youtube-logo text-lg font-bold"></i>
                            </a>
                        @endif
                        @if($profile->tiktok)
                            <a href="{{ $profile->tiktok }}" target="_blank" class="w-11 h-11 rounded-full bg-slate-50 flex items-center justify-center border border-slate-200 text-navy-900 hover:border-gold-500 hover:text-gold-500 hover:bg-white transition-all shadow-sm">
                                <i class="ph ph-tiktok-logo text-lg font-bold"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Maps Embed -->
            <div class="lg:col-span-7 rounded-[32px] overflow-hidden border border-slate-200 shadow-md h-[450px] relative z-10 bg-white">
                <iframe src="https://maps.google.com/maps?q=-6.338301828326995,106.83244555983342&t=&z=17&ie=UTF8&iwloc=&output=embed" class="w-full h-full border-0" allowfullscreen="" loading="lazy"></iframe>
            </div>

        </div>
    </div>
</section>

<!-- 🌟 SOCIAL SHARE PANEL SECTION -->
<section class="py-16 bg-navy-900 text-white relative">
    <div class="max-w-[1240px] mx-auto px-6 relative z-10 text-center">
        <h4 class="font-serif font-normal text-2xl mb-4 leading-tight">Bagikan Portal Company Profile PSUP</h4>
        <p class="text-slate-400 text-xs sm:text-sm mb-8 max-w-lg mx-auto leading-relaxed">Bantu sebarkan informasi resmi PSUP ke jejaring media sosial Anda untuk menarik minat calon pendaftar, sponsor, & mitra universitas.</p>
        
        <div class="flex flex-wrap items-center justify-center gap-4">
            <a href="https://api.whatsapp.com/send?text=Kunjungi%20Website%20Resmi%20Company%20Profile%20Paduan%20Suara%20Universitas%20Pancasila%20di%20{{ urlencode(request()->url()) }}" target="_blank" class="flex items-center justify-center bg-[#25D366] text-white px-6 py-3.5 rounded-2xl text-xs font-bold gap-2.5 hover:opacity-90 transition-opacity">
                <i class="ph ph-whatsapp-logo text-base font-bold"></i> WhatsApp
            </a>
            
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" class="flex items-center justify-center bg-[#1877F2] text-white px-6 py-3.5 rounded-2xl text-xs font-bold gap-2.5 hover:opacity-90 transition-opacity">
                <i class="ph ph-facebook-logo text-base font-bold"></i> Facebook
            </a>

            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text=Website%20Resmi%20Company%20Profile%20Paduan%20Suara%20Universitas%20Pancasila" target="_blank" class="flex items-center justify-center bg-[#000000] text-white px-6 py-3.5 rounded-2xl text-xs font-bold gap-2.5 hover:opacity-90 transition-opacity">
                <i class="ph ph-twitter-logo text-base font-bold"></i> Twitter / X
            </a>
        </div>
    </div>
</section>

<!-- 🌟 FOOTER -->
<footer class="py-16 bg-navy-950 text-white border-t border-slate-900">
    <div class="max-w-[1240px] mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 mb-12">
            
            <!-- Left Brand column -->
            <div class="lg:col-span-5">
                <a href="/" class="flex items-center space-x-3.5 mb-6">
                    <div class="w-10 h-10 flex items-center justify-center rounded-full overflow-hidden border border-gold-500 shrink-0">
                        @if($profile->logo)
                            <img src="{{ asset('storage/' . $profile->logo) }}" alt="Logo" class="w-full h-full object-cover">
                        @else
                            <img src="{{ asset('images/logo_PSUP.jpeg') }}" alt="Logo PSUP" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div>
                        <h4 class="font-serif font-normal text-sm tracking-tight text-white leading-tight">{{ $profile->name }}</h4>
                        <p class="text-[9px] text-gold-500 font-bold uppercase tracking-wider leading-none">{{ $profile->alias }}</p>
                    </div>
                </a>
                <p class="text-slate-400 text-xs leading-relaxed max-w-sm">
                    {{ $profile->description }}
                </p>
            </div>

            <!-- Navigation Links Column -->
            <div class="lg:col-span-3">
                <h5 class="text-gold-400 font-bold text-xs uppercase tracking-wider mb-5">Navigasi Utama</h5>
                <ul class="space-y-2.5 text-xs text-slate-400">
                    <li><a href="#about" class="hover:text-white transition-colors">Tentang Kami</a></li>
                    <li><a href="#why-us" class="hover:text-white transition-colors">Alasan Bergabung</a></li>
                    <li><a href="#history" class="hover:text-white transition-colors">Sejarah / Timeline</a></li>
                    <li><a href="#structure" class="hover:text-white transition-colors">Bagan Organisasi</a></li>
                    <li><a href="#trainers" class="hover:text-white transition-colors">Tim Pelatih</a></li>
                    <li><a href="#programs" class="hover:text-white transition-colors">Program Kerja</a></li>
                    <li><a href="#achievements" class="hover:text-white transition-colors">Prestasi & Penghargaan</a></li>
                </ul>
            </div>

            <!-- Contact Column -->
            <div class="lg:col-span-4">
                <h5 class="text-gold-400 font-bold text-xs uppercase tracking-wider mb-5">Hubungi Kami</h5>
                <ul class="space-y-3.5 text-xs text-slate-400">
                    <li class="flex items-start gap-2.5">
                        <i class="ph ph-map-pin text-gold-500 mt-0.5 shrink-0"></i>
                        <span class="leading-relaxed">{{ $profile->address }}</span>
                    </li>
                    <li class="flex items-center gap-2.5">
                        <i class="ph ph-envelope text-gold-500 shrink-0"></i>
                        <a href="mailto:{{ $profile->email }}" class="hover:text-white transition-colors">{{ $profile->email }}</a>
                    </li>
                    <li class="flex items-center gap-2.5">
                        <i class="ph ph-phone text-gold-500 shrink-0"></i>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $profile->phone) }}" target="_blank" class="hover:text-white transition-colors">{{ $profile->phone }}</a>
                    </li>
                </ul>
            </div>

        </div>

        <div class="border-t border-slate-900 pt-8 flex flex-col sm:flex-row justify-between items-center text-xs text-slate-500">
            <p>© 2026 {{ $profile->name }}. All Rights Reserved.</p>
            <div class="flex gap-4 mt-4 sm:mt-0">
                <a href="/login" class="hover:text-slate-300">Akses Sistem Portal PSUP (Internal)</a>
            </div>
        </div>
    </div>
</footer>

<!-- modal Image Preview/Zoom -->
<div id="zoomModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 3, 20, 0.95); z-index: 99999; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.3s ease;">
    <button onclick="closeZoom()" style="position: absolute; top: 20px; right: 30px; font-size: 2.8rem; color: white; cursor: pointer; background: none; border: none; font-weight: bold;">&times;</button>
    <img id="zoomedImage" style="max-width: 92%; max-height: 92%; border-radius: 16px; box-shadow: 0 0 35px rgba(0,0,0,0.6);">
</div>

<script>
    function zoomStructure(src) {
        const modal = document.getElementById('zoomModal');
        const img = document.getElementById('zoomedImage');
        img.src = src;
        modal.style.display = 'flex';
        setTimeout(() => {
            modal.style.opacity = '1';
        }, 10);
    }

    function closeZoom() {
        const modal = document.getElementById('zoomModal');
        modal.style.opacity = '0';
        setTimeout(() => {
            modal.style.display = 'none';
        }, 300);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.05,
            rootMargin: '0px 0px -30px 0px'
        });

        document.querySelectorAll('.reveal-element').forEach(el => {
            observer.observe(el);
        });
    });</script>

</body>
</html>