@php
    $ukms = \App\Models\UKM::withCount(['memberships' => function ($query) {
        $query->where('status', 'approved');
    }])->get();
@endphp
<!DOCTYPE html>
<html lang="id" class="scroll-smooth overflow-x-hidden">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Manajemen UKM - Universitas Pancasila</title>

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
                        cobalt: {
                            50: '#f0f6ff',
                            100: '#e0efff',
                            600: '#1d4ed8',
                            700: '#1e40af',
                            800: '#0d47a1',
                            900: '#0b3a82',
                        },
                        ambergold: {
                            400: '#fbbf24',
                            500: '#f59e0b',
                            600: '#d97706',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        /* Custom Modern Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Glowing Blobs and Gradients */
        .glow-sphere {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            opacity: 0.12;
            pointer-events: none;
            z-index: 0;
        }
        
        .glow-primary {
            background: radial-gradient(circle, #3b82f6 0%, rgba(59, 130, 246, 0) 70%);
        }
        
        .glow-secondary {
            background: radial-gradient(circle, #f59e0b 0%, rgba(245, 158, 11, 0) 70%);
        }

        @keyframes float-slower {
            0%, 100% { transform: translateY(0px) scale(1); }
            50% { transform: translateY(-15px) scale(1.03); }
        }

        @keyframes float-faster {
            0%, 100% { transform: translateY(0px) scale(1); }
            50% { transform: translateY(12px) scale(0.97); }
        }

        .animate-float-slow {
            animation: float-slower 8s ease-in-out infinite;
        }

        .animate-float-fast {
            animation: float-faster 6s ease-in-out infinite;
        }

        /* Glassmorphism class (Light) */
        .glass-card {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(241, 245, 249, 0.8);
        }

        /* Grid Background Pattern */
        .bg-grid-lines {
            background-size: 32px 32px;
            background-image: 
                linear-gradient(to right, rgba(148, 163, 184, 0.06) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(148, 163, 184, 0.06) 1px, transparent 1px);
        }
        .bg-grid-mask {
            mask-image: radial-gradient(circle at 60% 50%, black 10%, transparent 85%);
            -webkit-mask-image: radial-gradient(circle at 60% 50%, black 10%, transparent 85%);
        }
    </style>
</head>

<body class="bg-[#fafbfd] text-slate-800 font-sans antialiased overflow-x-hidden">

<!-- Ambient Background Lights (Pastel/Light) -->
<div class="glow-sphere glow-primary w-[500px] h-[500px] top-[-100px] left-[-100px] animate-float-slow"></div>
<div class="glow-sphere glow-secondary w-[400px] h-[400px] top-[200px] right-[-100px] animate-float-fast"></div>
<div class="glow-sphere glow-primary w-[600px] h-[600px] bottom-[500px] left-[10%] animate-float-slow"></div>

<!-- 🌟 FLOATING HEADER / NAVBAR -->
<div class="fixed top-0 inset-x-0 z-50 px-4 pt-4">
    <header class="max-w-[1200px] mx-auto rounded-2xl border border-slate-200/80 shadow-md bg-white/80 backdrop-blur-lg px-6 py-3.5 transition-all duration-300">
        <nav class="flex justify-between items-center">
            
            <!-- Logo -->
            <a href="/" class="flex items-center space-x-3 group">
                <div class="w-9 h-9 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-center p-1.5 transition-transform group-hover:scale-105">
                    <img src="{{ asset('images/logoup.png') }}" alt="Logo Universitas Pancasila" class="w-full h-full object-contain">
                </div>
                <div>
                    <h1 class="font-outfit font-black text-sm tracking-tight text-slate-850 leading-tight">Sistem UKM</h1>
                    <p class="text-[9px] text-amber-600 font-bold tracking-wider uppercase leading-none">Universitas Pancasila</p>
                </div>
            </a>

            <!-- Menu Navigation Links -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="#fitur" class="text-xs font-bold text-slate-500 hover:text-blue-700 transition-colors tracking-wide uppercase">Fitur Layanan</a>
                <a href="#alur-kerja" class="text-xs font-bold text-slate-500 hover:text-blue-700 transition-colors tracking-wide uppercase">Alur Kerja</a>
                <a href="#daftar-ukm" class="text-xs font-bold text-slate-500 hover:text-blue-700 transition-colors tracking-wide uppercase">Daftar UKM</a>
            </div>

            <!-- Auth Actions -->
            <div class="flex items-center space-x-3">
                <a href="/login" class="text-xs font-bold text-slate-600 hover:text-blue-700 transition-colors px-4 py-2 uppercase tracking-wider">
                    Masuk
                </a>
                <a href="/register" 
                   class="bg-blue-600 hover:bg-blue-500 text-white px-5 py-2.5 rounded-xl text-xs font-black tracking-wider uppercase shadow-md transition-all duration-200 hover:-translate-y-0.5">
                     Daftar Akun
                </a>
            </div>

        </nav>
    </header>
</div>

<!-- 🌟 HERO SECTION -->
<main class="relative pt-48 pb-20 overflow-hidden bg-gradient-to-b from-blue-50/60 via-slate-50/40 to-[#fafbfd]">
    <!-- Grid Overlay -->
    <div class="absolute inset-0 bg-grid-lines bg-grid-mask pointer-events-none z-0"></div>

    <div class="max-w-[1000px] mx-auto px-6 relative z-10 text-center space-y-8">
        <!-- Badge -->
        <div class="inline-flex items-center gap-2 bg-blue-50 border border-blue-100/80 px-4 py-1.5 rounded-full text-blue-700 font-extrabold text-[10px] uppercase tracking-wider shadow-sm mx-auto">
            <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span>
            Portal Layanan Resmi Kemahasiswaan
        </div>
        
        <!-- Main Headings -->
        <h2 class="font-outfit text-4xl sm:text-5xl lg:text-[4rem] font-black text-slate-900 tracking-tight leading-[1.08] max-w-3xl mx-auto">
            Sistem Layanan Kegiatan Mahasiswa <br>
            <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-700 via-indigo-650 to-amber-600">Universitas Pancasila</span>
        </h2>
        
        <!-- Description -->
        <p class="text-slate-500 text-base sm:text-lg leading-relaxed font-medium max-w-2xl mx-auto">
            Portal resmi untuk digitalisasi administrasi, transparansi keuangan kas, absensi kehadiran, dan otomatisasi Laporan Pertanggungjawaban (LPJ) seluruh Unit Kegiatan Mahasiswa (UKM) di lingkungan Universitas Pancasila.
        </p>
        
        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 pt-2 justify-center items-center">
            <a href="/login" 
               class="group bg-blue-700 hover:bg-blue-600 text-white px-8 py-4 rounded-2xl text-xs font-black tracking-wider uppercase shadow-lg shadow-blue-700/10 hover:shadow-blue-700/25 transition-all duration-300 hover:-translate-y-0.5 flex items-center justify-center gap-2.5">
                Akses Portal Mahasiswa <i class="ph ph-arrow-right text-sm transition-transform group-hover:translate-x-1"></i>
            </a>
            <a href="#daftar-ukm" 
               class="bg-white border border-slate-200 hover:border-slate-350 hover:bg-slate-50 text-slate-700 px-8 py-4 rounded-2xl text-xs font-black tracking-wider uppercase shadow-sm transition-all duration-300 text-center">
                Direktori UKM UP
            </a>
        </div>
    </div>
</main>

<!-- 🌟 STATS METRIC BENTO SECTION -->
<section class="py-12 bg-transparent relative z-10 -mt-10">
    <div class="max-w-[1200px] mx-auto px-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6" id="statistik">
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 flex items-center gap-4 shadow-sm hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-650 text-2xl shrink-0">
                    <i class="ph ph-buildings"></i>
                </div>
                <div class="text-left">
                    <div class="text-2xl font-black text-slate-800 font-outfit">12+</div>
                    <div class="text-[10px] font-bold text-slate-450 uppercase tracking-wider mt-0.5">UKM Terdaftar</div>
                </div>
            </div>
            
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 flex items-center gap-4 shadow-sm hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600 text-2xl shrink-0">
                    <i class="ph ph-users"></i>
                </div>
                <div class="text-left">
                    <div class="text-2xl font-black text-slate-800 font-outfit">1,500+</div>
                    <div class="text-[10px] font-bold text-slate-450 uppercase tracking-wider mt-0.5">Mahasiswa Aktif</div>
                </div>
            </div>
            
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 flex items-center gap-4 shadow-sm hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 text-2xl shrink-0">
                    <i class="ph ph-trend-up"></i>
                </div>
                <div class="text-left">
                    <div class="text-2xl font-black text-slate-800 font-outfit">98%</div>
                    <div class="text-[10px] font-bold text-slate-450 uppercase tracking-wider mt-0.5">Akurasi Uang Kas</div>
                </div>
            </div>
            
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 flex items-center gap-4 shadow-sm hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-xl bg-purple-50 border border-purple-100 flex items-center justify-center text-purple-650 text-2xl shrink-0">
                    <i class="ph ph-clock"></i>
                </div>
                <div class="text-left">
                    <div class="text-2xl font-black text-slate-800 font-outfit">Real-time</div>
                    <div class="text-[10px] font-bold text-slate-450 uppercase tracking-wider mt-0.5">Sinkronisasi LPJ</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 🌟 PRESTIGIOUS BENTO GRID SHOWCASE (FITUR UTAMA) -->
<section id="fitur" class="py-24 bg-[#fafbfd] relative z-10">
    <div class="max-w-[1200px] mx-auto px-6">
        
        <!-- Header -->
        <div class="text-center max-w-xl mx-auto mb-20">
            <span class="text-xs font-bold uppercase tracking-widest text-blue-700">EKOSISTEM DIGITAL</span>
            <h2 class="font-outfit text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight mt-3">
                Fitur Unggulan Sistem Informasi UKM
            </h2>
            <p class="text-slate-500 text-sm mt-3 leading-relaxed">
                Kelola segala aspek administrasi UKM dengan satu dasbor terintegrasi dan ringkas.
            </p>
        </div>

        <!-- Bento Grid Layout -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- Bento Box 1: Double Columns (LMS & Materi) -->
            <div class="md:col-span-2 bg-white p-8 rounded-3xl border border-slate-200 shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
                <div class="absolute right-[-40px] bottom-[-20px] w-64 h-64 bg-blue-100/30 rounded-full filter blur-3xl group-hover:bg-blue-100/50 transition-all"></div>
                
                <div class="flex flex-col md:flex-row gap-6 items-start md:items-center justify-between">
                    <div class="space-y-4 max-w-md">
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 text-xl">
                            <i class="ph ph-books"></i>
                        </div>
                        <h3 class="font-outfit font-extrabold text-xl text-slate-800">LMS & Perpustakaan Digital Latihan</h3>
                        <p class="text-slate-500 text-xs sm:text-sm leading-relaxed">
                            Bagi dan akses file materi penunjang kegiatan (.pdf, materi video, partitur, naskah latihan) secara daring tanpa batas ruang penyimpanan fisik.
                        </p>
                    </div>
                    
                    <!-- Inside Visual Mockup -->
                    <div class="bg-slate-50 p-4.5 rounded-2xl border border-slate-200 w-full md:w-56 space-y-2 shrink-0">
                        <div class="flex items-center gap-2 p-2 bg-white rounded-xl border border-slate-100 shadow-sm">
                            <i class="ph-fill ph-file-pdf text-red-500 text-base"></i>
                            <div class="leading-none"><span class="text-[9px] font-bold text-slate-700 block">Taktik_Basket.pdf</span><span class="text-[7px] text-slate-400">2.4 MB</span></div>
                        </div>
                        <div class="flex items-center gap-2 p-2 bg-white rounded-xl border border-slate-100 shadow-sm">
                            <i class="ph-fill ph-video-camera text-blue-500 text-base"></i>
                            <div class="leading-none"><span class="text-[9px] font-bold text-slate-700 block">Video_Replay.mp4</span><span class="text-[7px] text-slate-400">45 MB</span></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bento Box 2: Standard (Absensi Sesi Mandiri) -->
            <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
                <div class="absolute right-[-40px] bottom-[-20px] w-48 h-48 bg-amber-100/20 rounded-full filter blur-2xl"></div>
                <div class="space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600 text-xl">
                        <i class="ph ph-check-square-offset"></i>
                    </div>
                    <h3 class="font-outfit font-extrabold text-xl text-slate-800">Absensi Mandiri Praktis</h3>
                    <p class="text-slate-500 text-xs sm:text-sm leading-relaxed">
                        Tinggalkan kertas absen. Anggota mengisi kehadiran mandiri dengan satu ketukan tombol di HP ketika sesi dimulai oleh admin.
                    </p>
                </div>
            </div>

            <!-- Bento Box 3: Standard (Keuangan & Kas) -->
            <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
                <div class="absolute right-[-40px] bottom-[-20px] w-48 h-48 bg-emerald-100/20 rounded-full filter blur-2xl"></div>
                <div class="space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 text-xl">
                        <i class="ph ph-money"></i>
                    </div>
                    <h3 class="font-outfit font-extrabold text-xl text-slate-800">Pencatatan Keuangan & Kas</h3>
                    <p class="text-slate-550 text-xs sm:text-sm leading-relaxed">
                        Catat uang kas bulanan anggota, iuran event, dan kas pengeluaran operasional secara transparan dan akurat.
                    </p>
                </div>
            </div>

            <!-- Bento Box 4: Double Columns (Ekspor LPJ Digital) -->
            <div class="md:col-span-2 bg-white p-8 rounded-3xl border border-slate-200 shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
                <div class="absolute right-[-40px] bottom-[-20px] w-64 h-64 bg-amber-100/30 rounded-full filter blur-3xl group-hover:bg-amber-100/50 transition-all"></div>
                
                <div class="flex flex-col md:flex-row gap-6 items-start md:items-center justify-between">
                    <div class="space-y-4 max-w-md">
                        <div class="w-12 h-12 rounded-2xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600 text-xl">
                            <i class="ph ph-file-pdf"></i>
                        </div>
                        <h3 class="font-outfit font-extrabold text-xl text-slate-800">Ekspor Dokumen LPJ Instan</h3>
                        <p class="text-slate-500 text-xs sm:text-sm leading-relaxed">
                            Cetak Laporan Pertanggungjawaban (LPJ) keuangan & daftar kehadiran kegiatan secara otomatis dalam bentuk file PDF yang terformat rapi sesuai kebutuhan Biro Kemahasiswaan.
                        </p>
                    </div>
                    
                    <!-- Inside Visual Action Mockup -->
                    <div class="bg-slate-50 p-4.5 rounded-2xl border border-slate-200 w-full md:w-56 space-y-3 shrink-0 flex flex-col items-center justify-center py-6 text-center shadow-sm">
                        <div class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-400 text-base shadow-sm">
                            <i class="ph ph-file-text"></i>
                        </div>
                        <div>
                            <span class="text-[9px] font-bold text-slate-800 block">LPJ_Kegiatan_2026.pdf</span>
                            <span class="text-[7px] text-slate-400 mt-0.5 block">File siap diunduh</span>
                        </div>
                        <button class="w-full py-1.5 bg-blue-600 hover:bg-blue-500 text-white rounded-lg text-[8px] font-extrabold tracking-wide uppercase transition-colors shadow-sm">
                            <i class="ph ph-download"></i> Cetak LPJ PDF
                        </button>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- 🌟 TIMELINE SECTION (ALUR KERJA) -->
<section id="alur-kerja" class="py-24 bg-white border-t border-b border-slate-100 relative z-10">
    <div class="max-w-[1200px] mx-auto px-6">
        
        <!-- Header -->
        <div class="text-center max-w-xl mx-auto mb-20">
            <span class="text-xs font-bold uppercase tracking-widest text-blue-700">TAHAPAN KERJA</span>
            <h2 class="font-outfit text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight mt-3">
                Bagaimana Sistem Bekerja?
            </h2>
            <p class="text-slate-500 text-sm mt-3 leading-relaxed">
                Empat langkah praktis digitalisasi sistem operasional organisasi.
            </p>
        </div>

        <!-- Timeline Path Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            
            <!-- Step 1 -->
            <div class="bg-[#fafbfd] p-6 rounded-2xl border border-slate-200 relative group shadow-sm">
                <span class="absolute top-4 right-4 text-4xl font-black text-slate-200 group-hover:text-slate-300 transition-colors font-mono">01</span>
                <div class="space-y-4 pt-4">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 text-lg">
                        <i class="ph ph-user-plus"></i>
                    </div>
                    <h3 class="font-outfit font-extrabold text-base text-slate-800">Daftar Akun Member</h3>
                    <p class="text-slate-550 text-xs leading-relaxed">
                        Mahasiswa mendaftar akun pada platform dan memilih UKM yang ingin mereka ikuti secara online.
                    </p>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="bg-[#fafbfd] p-6 rounded-2xl border border-slate-200 relative group shadow-sm">
                <span class="absolute top-4 right-4 text-4xl font-black text-slate-200 group-hover:text-slate-300 transition-colors font-mono">02</span>
                <div class="space-y-4 pt-4">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600 text-lg">
                        <i class="ph ph-seal-check"></i>
                    </div>
                    <h3 class="font-outfit font-extrabold text-base text-slate-800">Persetujuan BPH UKM</h3>
                    <p class="text-slate-550 text-xs leading-relaxed">
                        BPH meninjau data pendaftar baru melalui halaman verifikasi dan mengubah status keanggotaan menjadi Approved.
                    </p>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="bg-[#fafbfd] p-6 rounded-2xl border border-slate-200 relative group shadow-sm">
                <span class="absolute top-4 right-4 text-4xl font-black text-slate-200 group-hover:text-slate-300 transition-colors font-mono">03</span>
                <div class="space-y-4 pt-4">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 text-lg">
                        <i class="ph ph-check-square"></i>
                    </div>
                    <h3 class="font-outfit font-extrabold text-base text-slate-800">Sesi & Absensi Mandiri</h3>
                    <p class="text-slate-550 text-xs leading-relaxed">
                        Anggota mengikuti kegiatan, mengakses berkas latihan di LMS, serta melakukan check-in kehadiran secara instan.
                    </p>
                </div>
            </div>

            <!-- Step 4 -->
            <div class="bg-[#fafbfd] p-6 rounded-2xl border border-slate-200 relative group shadow-sm">
                <span class="absolute top-4 right-4 text-4xl font-black text-slate-200 group-hover:text-slate-300 transition-colors font-mono">04</span>
                <div class="space-y-4 pt-4">
                    <div class="w-10 h-10 rounded-xl bg-purple-50 border border-purple-100 flex items-center justify-center text-purple-600 text-lg">
                        <i class="ph ph-file-arrow-up"></i>
                    </div>
                    <h3 class="font-outfit font-extrabold text-base text-slate-800">Rekapitulasi & LPJ</h3>
                    <p class="text-slate-555 text-xs leading-relaxed">
                        Sistem merekap absensi & kas secara otomatis dan menyajikannya dalam file cetak LPJ PDF instan di akhir proker.
                    </p>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- 🌟 DAFTAR UNIT KEGIATAN MAHASISWA (UKM) SECTION -->
<section id="daftar-ukm" class="py-24 bg-[#fafbfd] relative z-10">
    <div class="max-w-[1200px] mx-auto px-6">
        
        <!-- Header -->
        <div class="text-center max-w-xl mx-auto mb-20">
            <span class="text-xs font-bold uppercase tracking-widest text-blue-700">DIREKTORI ORGANISASI</span>
            <h2 class="font-outfit text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight mt-3">
                Unit Kegiatan Mahasiswa Universitas Pancasila
            </h2>
            <p class="text-slate-500 text-sm mt-3 leading-relaxed">
                Temukan dan bergabunglah dengan organisasi kemahasiswaan yang sesuai dengan minat dan bakat Anda.
            </p>
        </div>

        <!-- UKM Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
            @forelse($ukms as $ukm)
                <div class="bg-white p-6.5 rounded-3xl border border-slate-200 shadow-sm flex flex-col justify-between hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                    <div>
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 bg-blue-50 border border-blue-100 rounded-2xl flex items-center justify-center p-2 overflow-hidden flex-shrink-0">
                                @if($ukm->logo)
                                    <img src="{{ filter_var($ukm->logo, FILTER_VALIDATE_URL) ? $ukm->logo : asset('storage/' . $ukm->logo) }}" alt="{{ $ukm->name }}" class="w-full h-full object-cover">
                                @else
                                    <img src="{{ asset('images/logoup.png') }}" alt="Logo Universitas Pancasila" class="w-full h-full object-contain opacity-50">
                                @endif
                            </div>
                            <div>
                                <h3 class="font-outfit font-extrabold text-base text-slate-800 leading-tight">{{ $ukm->name }}</h3>
                                <span class="text-[10px] bg-amber-50 border border-amber-100 text-amber-700 font-bold px-2 py-0.5 rounded-full mt-1.5 inline-block uppercase tracking-wider">
                                    {{ $ukm->memberships_count }} Anggota Aktif
                                </span>
                            </div>
                        </div>
                        <p class="text-slate-500 text-xs sm:text-sm leading-relaxed mb-6">
                            {{ Str::limit($ukm->description, 120, '...') }}
                        </p>
                    </div>
                    <div>
                        <a href="/login" class="w-full py-3 bg-blue-50 hover:bg-blue-100 border border-blue-100 text-blue-700 rounded-xl text-xs font-black tracking-wider uppercase text-center block transition-all">
                            Gabung UKM
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white py-16 px-6 rounded-3xl border border-slate-200 text-center shadow-sm">
                    <i class="ph ph-buildings text-slate-300 text-5xl mb-4 block"></i>
                    <p class="text-slate-500 font-bold text-sm">Belum ada Unit Kegiatan Mahasiswa yang terdaftar di sistem.</p>
                </div>
            @endforelse
        </div>

    </div>
</section>

<!-- 🌟 GRAND PRESTIGIOUS CALL TO ACTION (CTA) -->
<section class="py-24 bg-white relative z-10 overflow-hidden border-t border-slate-100">
    <div class="max-w-[1000px] mx-auto px-6 relative z-10">
        <div class="bg-gradient-to-br from-blue-900 to-slate-950 rounded-3xl p-8 sm:p-14 text-center space-y-8 relative overflow-hidden shadow-xl text-white border border-blue-950">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_30%,rgba(245,158,11,0.05),transparent_50%)]"></div>
            
            <h2 class="font-outfit text-3xl sm:text-5xl font-black text-white leading-tight">
                Mulai Langkah Kontribusi Anda <br>di Universitas Pancasila
            </h2>
            
            <p class="text-slate-300 max-w-2xl mx-auto text-xs sm:text-sm leading-relaxed font-semibold">
                Daftar sebagai anggota atau kelola kepengurusan UKM Anda secara digital hari ini. Bersama SIM-UKM, wujudkan kegiatan mahasiswa yang lebih aktif, berprestasi, dan transparan.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="/register" 
                   class="w-full sm:w-auto bg-amber-500 hover:bg-amber-400 text-slate-950 px-8 py-3.5 rounded-xl text-xs font-black uppercase tracking-wider shadow-md transition-transform hover:-translate-y-0.5">
                    Daftar Akun Baru
                </a>
                <a href="/login" 
                   class="w-full sm:w-auto bg-white/10 border border-white/20 hover:bg-white/20 text-white px-8 py-3.5 rounded-xl text-xs font-black uppercase tracking-wider transition-colors">
                    Masuk Portal
                </a>
            </div>
        </div>
    </div>
</section>

<!-- 🌟 MINIMALIST LIGHT FOOTER -->
<footer class="bg-slate-950 text-slate-400 text-[11px] py-16 relative z-10 border-t border-slate-900">
    <div class="max-w-[1200px] mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-10">
        
        <!-- Left Logo -->
        <div class="flex items-center space-x-3">
            <div class="w-8 h-8 bg-white border border-slate-800 rounded p-1 flex items-center justify-center">
                <img src="{{ asset('images/logoup.png') }}" alt="Logo Universitas Pancasila" class="w-full h-full object-contain">
            </div>
            <div>
                <p class="font-outfit font-black text-white text-xs">Sistem UKM</p>
                <p class="text-[8px] text-amber-500 font-bold uppercase tracking-widest leading-none">Universitas Pancasila</p>
            </div>
        </div>

        <!-- Center Text -->
        <div class="text-center md:text-left leading-normal font-semibold">
            <p class="text-slate-200">Sistem Informasi Manajemen Unit Kegiatan Mahasiswa (SIM-UKM)</p>
            <p class="text-slate-500 mt-1 text-[9px]">Portal Resmi Biro Kemahasiswaan & Alumni Universitas Pancasila</p>
        </div>

        <!-- Right Copyright -->
        <div class="text-center md:text-right font-bold text-[10px] space-y-1">
            <p>© {{ date('Y') }} All Rights Reserved.</p>
            <p class="text-slate-500">Membina Kreativitas, Prestasi, dan Karakter Mahasiswa Pancasila</p>
        </div>

    </div>
</footer>

</body>
</html>