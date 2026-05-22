<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
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
                <a href="#fitur" class="text-xs font-bold text-slate-500 hover:text-blue-700 transition-colors tracking-wide uppercase">Fitur Utama</a>
                <a href="#alur-kerja" class="text-xs font-bold text-slate-500 hover:text-blue-700 transition-colors tracking-wide uppercase">Alur Kerja</a>
                <a href="#studi-kasus" class="text-xs font-bold text-slate-500 hover:text-blue-700 transition-colors tracking-wide uppercase">Perbandingan</a>
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
<main class="relative pt-44 pb-20 flex flex-col justify-center items-center text-center">
    <div class="max-w-[1200px] mx-auto px-6 w-full relative z-10">
        
        <!-- Status Pill -->
        <div class="inline-flex items-center gap-2 bg-slate-100 border border-slate-200 px-4.5 py-1.5 rounded-full text-slate-600 font-bold text-[10px] uppercase tracking-widest mb-8">
            <span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
            TRANSFORMASI DIGITAL UKM KAMPUS
        </div>

        <!-- Hero Title -->
        <h2 class="font-outfit text-4xl sm:text-6xl md:text-[4rem] font-extrabold tracking-tight mb-8 leading-[1.1] text-slate-900">
            Kelola Organisasi UKM Lebih <br>
            <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-700 via-blue-800 to-amber-600">Terstruktur & Akuntabel</span>
        </h2>

        <!-- Subtitle Description -->
        <p class="text-slate-500 mb-12 max-w-3xl mx-auto text-base sm:text-lg leading-relaxed font-medium">
            Platform all-in-one terintegrasi untuk Unit Kegiatan Mahasiswa Universitas Pancasila. 
            Permudah pencatatan kas, absensi sesi latihan, distribusi berkas materi, 
            hingga verifikasi dan ekspor berkas LPJ digital instan.
        </p>

        <!-- CTA Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-20 w-full sm:w-auto">
            <a href="/register" 
               class="w-full sm:w-auto bg-blue-700 hover:bg-blue-600 text-white px-8 py-4 rounded-xl text-sm font-bold tracking-wide shadow-lg transition-all duration-200 hover:-translate-y-0.5 flex items-center justify-center gap-2">
                <i class="ph ph-rocket-launch text-lg"></i> Mulai Sekarang
            </a>
            <a href="#fitur" 
               class="w-full sm:w-auto bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 px-8 py-4 rounded-xl text-sm font-bold tracking-wide shadow-sm transition-all duration-200">
                Eksplor Fitur
            </a>
        </div>

        <!-- 🌟 PREMIUM LIGHT DASHBOARD MOCKUP -->
        <div class="relative w-full max-w-5xl mx-auto rounded-3xl border border-slate-200 shadow-xl bg-white overflow-hidden text-left mb-24">
            <!-- Mockup Header Bar -->
            <div class="h-14 bg-slate-50 border-b border-slate-200/80 px-6 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <span class="w-3 h-3 rounded-full bg-slate-200"></span>
                    <span class="w-3 h-3 rounded-full bg-slate-200"></span>
                    <span class="w-3 h-3 rounded-full bg-slate-200"></span>
                </div>
                <div class="text-xs text-slate-400 font-bold font-mono">ukm-pancasila.ac.id/dashboard</div>
                <div class="w-12"></div>
            </div>
            
            <!-- Mockup Workspace Area -->
            <div class="flex h-[420px] bg-slate-50/50">
                <!-- Sidebar Mockup -->
                <div class="w-56 bg-white border-r border-slate-200/85 p-6 flex flex-col justify-between hidden sm:flex shrink-0">
                    <div class="space-y-6">
                        <div class="flex items-center gap-3 px-1">
                            <img src="{{ asset('images/logoup.png') }}" class="w-6 h-6 object-contain">
                            <span class="text-xs font-black text-slate-800 tracking-wider font-outfit uppercase">Portal UKM</span>
                        </div>
                        
                        <div class="space-y-1">
                            <div class="text-[10px] px-3.5 py-2.5 rounded-xl bg-blue-50 border border-blue-100 text-blue-700 font-bold flex items-center gap-3">
                                <i class="ph-fill ph-squares-four text-sm"></i> Dashboard
                            </div>
                            <div class="text-[10px] px-3.5 py-2.5 text-slate-500 font-bold flex items-center gap-3">
                                <i class="ph ph-identification-card text-sm"></i> Profil UKM
                            </div>
                            <div class="text-[10px] px-3.5 py-2.5 text-slate-500 font-bold flex items-center gap-3">
                                <i class="ph ph-users text-sm"></i> Anggota
                            </div>
                            <div class="text-[10px] px-3.5 py-2.5 text-slate-500 font-bold flex items-center gap-3">
                                <i class="ph ph-books text-sm"></i> Materi Belajar
                            </div>
                            <div class="text-[10px] px-3.5 py-2.5 text-slate-500 font-bold flex items-center gap-3">
                                <i class="ph ph-money text-sm"></i> Kas Keuangan
                            </div>
                            <div class="text-[10px] px-3.5 py-2.5 text-slate-500 font-bold flex items-center gap-3">
                                <i class="ph ph-file-pdf text-sm"></i> Unduh LPJ
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3 p-2 bg-slate-50 rounded-2xl border border-slate-100">
                        <div class="w-7 h-7 rounded-xl bg-blue-600 text-white font-black text-xs flex items-center justify-center font-outfit">A</div>
                        <div class="leading-none">
                            <div class="text-[10px] font-bold text-slate-700">Admin BPH</div>
                            <span class="text-[8px] text-amber-600 font-bold mt-0.5 inline-block">Pengurus UKM</span>
                        </div>
                    </div>
                </div>

                <!-- Main Grid Mockup -->
                <div class="flex-1 p-6 space-y-6 overflow-y-auto">
                    <div class="flex justify-between items-center pb-4 border-b border-slate-200">
                        <div>
                            <h3 class="text-xs font-black text-slate-800">UKM Basketball Club</h3>
                            <p class="text-[9px] text-slate-400 font-bold mt-0.5">Universitas Pancasila</p>
                        </div>
                        <div class="flex items-center gap-2 bg-emerald-50 border border-emerald-100 px-3 py-1 rounded-full text-[9px] font-bold text-emerald-600">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Sesi Aktif
                        </div>
                    </div>

                    <!-- Mini Stat Boxes -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-white p-4 rounded-2xl border border-slate-200 flex flex-col justify-between h-20 shadow-sm">
                            <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Anggota Aktif</span>
                            <span class="text-xs font-extrabold text-slate-800">124 Mahasiswa</span>
                        </div>
                        <div class="bg-white p-4 rounded-2xl border border-slate-200 flex flex-col justify-between h-20 shadow-sm">
                            <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Presensi Latihan</span>
                            <span class="text-xs font-extrabold text-blue-600">92% Kehadiran</span>
                        </div>
                        <div class="bg-white p-4 rounded-2xl border border-slate-200 flex flex-col justify-between h-20 shadow-sm">
                            <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Kas Terkumpul</span>
                            <span class="text-xs font-extrabold text-emerald-600">Rp 5.250.000</span>
                        </div>
                        <div class="bg-white p-4 rounded-2xl border border-slate-200 flex flex-col justify-between h-20 shadow-sm">
                            <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Draf LPJ</span>
                            <span class="text-xs font-extrabold text-amber-600">Siap Cetak</span>
                        </div>
                    </div>

                    <!-- Agenda Table Block -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 space-y-4 shadow-sm">
                        <div class="flex justify-between items-center">
                            <h4 class="text-[10px] font-black text-slate-700 uppercase tracking-widest">Sesi Kegiatan Terdekat</h4>
                            <span class="text-[8px] bg-slate-50 border border-slate-200 text-slate-500 px-2 py-0.5 rounded-lg font-bold">Agenda Terjadwal</span>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-[10px]">
                                <thead>
                                    <tr class="border-b border-slate-200 text-slate-400 font-bold">
                                        <th class="pb-2">Nama Agenda</th>
                                        <th class="pb-2">Waktu Sesi</th>
                                        <th class="pb-2">Lokasi</th>
                                        <th class="pb-2 text-right">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-slate-600">
                                    <tr>
                                        <td class="py-3 font-bold text-slate-800">Latihan Strategi Pertahanan</td>
                                        <td class="py-3 font-medium">Jumat, 16.00 WIB</td>
                                        <td class="py-3 font-medium">Hall Serbaguna UP</td>
                                        <td class="py-3 text-right"><span class="bg-blue-50 border border-blue-100 text-blue-600 px-2 py-0.5 rounded-full font-bold text-[8px]">Mendatang</span></td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 font-bold text-slate-800">Fokus Fisik & Stamina</td>
                                        <td class="py-3 font-medium">Minggu, 08.00 WIB</td>
                                        <td class="py-3 font-medium">Lapangan Outdoor UP</td>
                                        <td class="py-3 text-right"><span class="bg-blue-50 border border-blue-100 text-blue-600 px-2 py-0.5 rounded-full font-bold text-[8px]">Mendatang</span></td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 font-bold text-slate-800">Rapat Pleno LPJ Proker</td>
                                        <td class="py-3 font-medium">12 Mei 2026</td>
                                        <td class="py-3 font-medium">Sekretariat UKM</td>
                                        <td class="py-3 text-right"><span class="bg-emerald-50 border border-emerald-100 text-emerald-600 px-2 py-0.5 rounded-full font-bold text-[8px]">Selesai</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>

<!-- 🌟 STATS METRIC BENTO SECTION -->
<section class="py-16 bg-white border-t border-b border-slate-100 relative z-10">
    <div class="max-w-[1200px] mx-auto px-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6" id="statistik">
            <div class="bg-[#fafbfd] p-6 rounded-2xl border border-slate-200 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-650 text-2xl shrink-0">
                    <i class="ph ph-buildings"></i>
                </div>
                <div>
                    <div class="text-2xl font-black text-slate-800 font-outfit">12+</div>
                    <div class="text-[10px] font-bold text-slate-450 uppercase tracking-wider mt-0.5">UKM Terdaftar</div>
                </div>
            </div>
            
            <div class="bg-[#fafbfd] p-6 rounded-2xl border border-slate-200 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600 text-2xl shrink-0">
                    <i class="ph ph-users"></i>
                </div>
                <div>
                    <div class="text-2xl font-black text-slate-800 font-outfit">1,500+</div>
                    <div class="text-[10px] font-bold text-slate-450 uppercase tracking-wider mt-0.5">Mahasiswa Aktif</div>
                </div>
            </div>
            
            <div class="bg-[#fafbfd] p-6 rounded-2xl border border-slate-200 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 text-2xl shrink-0">
                    <i class="ph ph-trend-up"></i>
                </div>
                <div>
                    <div class="text-2xl font-black text-slate-800 font-outfit">98%</div>
                    <div class="text-[10px] font-bold text-slate-450 uppercase tracking-wider mt-0.5">Akurasi Uang Kas</div>
                </div>
            </div>
            
            <div class="bg-[#fafbfd] p-6 rounded-2xl border border-slate-200 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-purple-50 border border-purple-100 flex items-center justify-center text-purple-600 text-2xl shrink-0">
                    <i class="ph ph-clock"></i>
                </div>
                <div>
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

<!-- 🌟 COMPARATIVE CARD SECTION (STUDI KASUS) -->
<section id="studi-kasus" class="py-24 bg-[#fafbfd] relative z-10">
    <div class="max-w-[1200px] mx-auto px-6">
        
        <!-- Header -->
        <div class="text-center max-w-xl mx-auto mb-20">
            <span class="text-xs font-bold uppercase tracking-widest text-blue-700">KOMPARASI SISTEM</span>
            <h2 class="font-outfit text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight mt-3">
                Sebelum vs Sesudah Menggunakan UniUKM
            </h2>
            <p class="text-slate-500 text-sm mt-3 leading-relaxed">
                Bandingkan efisiensi operasional organisasi dengan sistem digital baru.
            </p>
        </div>

        <!-- Split Grid Bento Comparison -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
            
            <!-- Left Side: Manual Method (Kuno) -->
            <div class="bg-red-50/50 border border-red-200/60 p-8 rounded-3xl shadow-sm space-y-6 relative overflow-hidden">
                <div class="absolute right-[-20px] bottom-[-20px] w-36 h-36 bg-red-100/30 rounded-full filter blur-2xl"></div>
                <div class="flex items-center gap-3">
                    <span class="w-2.5 h-2.5 rounded-full bg-red-500 animate-pulse"></span>
                    <h3 class="font-outfit font-extrabold text-lg text-slate-800 uppercase tracking-wider">Operasional Manual Kuno</h3>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <i class="ph ph-warning-circle text-red-500 text-xl shrink-0 mt-0.5"></i>
                        <p class="text-xs sm:text-sm text-slate-655 font-semibold">Buku uang kas dari kertas manual rawan hilang dan sulit dipantau berkala oleh anggota secara transparan.</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <i class="ph ph-warning-circle text-red-500 text-xl shrink-0 mt-0.5"></i>
                        <p class="text-xs sm:text-sm text-slate-655 font-semibold">Absensi fisik di lembaran kertas sering rusak, hilang, serta sangat rawan kecurangan titip tanda tangan.</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <i class="ph ph-warning-circle text-red-500 text-xl shrink-0 mt-0.5"></i>
                        <p class="text-xs sm:text-sm text-slate-655 font-semibold">Pengurus harus menyusun ulang rekap kas & rekap absen dari nol untuk format draf LPJ di akhir kepengurusan.</p>
                    </div>
                </div>
            </div>

            <!-- Right Side: Digital System (Modern) -->
            <div class="bg-emerald-50/50 border border-emerald-200/50 p-8 rounded-3xl shadow-sm space-y-6 relative overflow-hidden">
                <div class="absolute right-[-20px] bottom-[-20px] w-36 h-36 bg-emerald-100/30 rounded-full filter blur-2xl"></div>
                <div class="flex items-center gap-3">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <h3 class="font-outfit font-extrabold text-lg text-slate-800 uppercase tracking-wider">Sistem Digital Modern</h3>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <i class="ph ph-check-circle text-emerald-500 text-xl shrink-0 mt-0.5"></i>
                        <p class="text-xs sm:text-sm text-slate-700 font-semibold">Keuangan kas tercatat otomatis, saldo terhitung seketika dan dapat diakses transparan oleh anggota di dasbor.</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <i class="ph ph-check-circle text-emerald-500 text-xl shrink-0 mt-0.5"></i>
                        <p class="text-xs sm:text-sm text-slate-700 font-semibold">Presensi digital terintegrasi aman. Member melakukan check-in mandiri di HP dan datanya terekam aman.</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <i class="ph ph-check-circle text-emerald-500 text-xl shrink-0 mt-0.5"></i>
                        <p class="text-xs sm:text-sm text-slate-700 font-semibold">Tidak ada proses manual. Cukup klik tombol Cetak LPJ untuk mendapatkan draf berkas PDF siap cetak instan.</p>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- 🌟 GRAND PRESTIGIOUS CALL TO ACTION (CTA) -->
<section class="py-24 bg-white relative z-10 overflow-hidden border-t border-slate-100">
    <div class="max-w-[1000px] mx-auto px-6 relative z-10">
        <div class="bg-gradient-to-br from-blue-700 to-indigo-900 rounded-3xl p-8 sm:p-14 text-center space-y-8 relative overflow-hidden shadow-xl text-white">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_30%,rgba(255,255,255,0.08),transparent_50%)]"></div>
            
            <h2 class="font-outfit text-3xl sm:text-5xl font-black text-white leading-tight">
                Digitalisasi Administrasi & <br>Kegiatan UKM Anda Sekarang
            </h2>
            
            <p class="text-blue-100 max-w-2xl mx-auto text-xs sm:text-sm leading-relaxed font-semibold">
                Daftarkan UKM Anda hari ini untuk mempermudah presensi mandiri, klasifikasi divisi anggota secara dinamis, serta kelola aktivitas kegiatan dan kas dalam satu portal terpadu!
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="/register" 
                   class="w-full sm:w-auto bg-gradient-to-r from-ambergold-500 to-amber-600 hover:from-ambergold-400 hover:to-amber-500 text-slate-950 px-8 py-3.5 rounded-xl text-xs font-black uppercase tracking-wider shadow-md transition-transform hover:-translate-y-0.5">
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
<footer class="bg-slate-900 text-slate-400 text-[11px] py-16 relative z-10">
    <div class="max-w-[1200px] mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-10">
        
        <!-- Left Logo -->
        <div class="flex items-center space-x-3">
            <div class="w-7 h-7 bg-white/10 border border-white/10 rounded p-1">
                <img src="{{ asset('images/logoup.png') }}" alt="Logo Universitas Pancasila" class="w-full h-full object-contain">
            </div>
            <div>
                <p class="font-outfit font-black text-white text-xs">Sistem UKM</p>
                <p class="text-[8px] text-amber-500 font-bold uppercase tracking-widest leading-none">Universitas Pancasila</p>
            </div>
        </div>

        <!-- Center Text -->
        <div class="text-center md:text-left leading-normal font-semibold">
            <p class="text-slate-200">Sistem Informasi Manajemen Unit Kegiatan Mahasiswa (UKM)</p>
            <p class="text-slate-400 mt-1 text-[9px]">Studi Kasus: Multi-Organisasi Unit Kegiatan Mahasiswa (UKM) Universitas Pancasila</p>
        </div>

        <!-- Right Copyright -->
        <div class="text-center md:text-right font-bold text-[10px] space-y-1">
            <p>© {{ date('Y') }} All Rights Reserved.</p>
            <p class="text-slate-400">Portal Resmi Layanan Kegiatan Mahasiswa Universitas Pancasila</p>
        </div>

    </div>
</footer>

</body>
</html>