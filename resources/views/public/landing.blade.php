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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        .brand-border {
            border-color: #f1f5f9;
        }
        .text-dark {
            color: #0f172a;
        }
    </style>
</head>

<body class="bg-[#fafbfd] text-slate-900 font-sans antialiased overflow-x-hidden">

<!-- 🌟 HEADER / NAVBAR -->
<div class="fixed top-0 inset-x-0 z-50 px-4 pt-4">
    <header class="max-w-[1320px] mx-auto rounded-full border border-slate-200/60 shadow-sm bg-white/80 backdrop-blur-md px-6 py-3.5 transition-all duration-300">
        <nav class="flex justify-between items-center">
            
            <!-- Logo -->
            <a href="/" class="flex items-center space-x-3 group">
                <img src="{{ asset('images/logo-up.png') }}" alt="Logo Universitas Pancasila" class="w-9 h-9 object-contain rounded-lg">
                <div>
                    <h1 class="font-extrabold text-sm tracking-tight text-[#0d47a1] leading-tight">Sistem UKM</h1>
                    <p class="text-[9px] text-slate-400 font-bold tracking-wider uppercase leading-none">Universitas Pancasila</p>
                </div>
            </a>

            <!-- Menu Navigation Links -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="#fitur" class="text-sm font-semibold text-slate-500 hover:text-[#0d47a1] transition-colors">Fitur Utama</a>
                <a href="#alur-kerja" class="text-sm font-semibold text-slate-500 hover:text-[#0d47a1] transition-colors">Petunjuk Kerja</a>
                <a href="#studi-kasus" class="text-sm font-semibold text-slate-500 hover:text-[#0d47a1] transition-colors">Studi Kasus</a>
            </div>

            <!-- Auth Actions -->
            <div class="flex items-center space-x-4">
                <a href="/login" class="text-sm font-bold text-slate-600 hover:text-[#0d47a1] transition-colors px-2 py-1">
                    Masuk
                </a>
                <a href="/register" 
                   class="bg-[#0d47a1] hover:bg-[#0a3981] text-white px-5 py-2.5 rounded-full text-xs font-extrabold shadow-sm transition-all duration-200">
                    Daftar Akun
                </a>
            </div>

        </nav>
    </header>
</div>

<!-- 🌟 HERO SECTION -->
<main class="relative pt-36 pb-20 flex flex-col justify-center items-center text-center">
    <div class="max-w-[1320px] mx-auto px-6 w-full">
        
        <!-- Minimalist Active Status -->
        <div class="inline-flex items-center gap-2 bg-slate-100 px-4 py-1.5 rounded-full text-slate-600 font-bold text-xs uppercase tracking-wider mb-6">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            MANAJEMEN UKM TERPADU
        </div>

        <!-- Headline -->
        <h2 class="text-3xl sm:text-5xl md:text-[3.5rem] font-extrabold tracking-tight mb-6 leading-tight text-slate-900">
            Kelola Organisasi UKM Secara <br class="hidden sm:block">
            <span class="text-[#0d47a1]">Terstruktur & Transparan</span>
        </h2>

        <!-- Subtitle -->
        <p class="text-slate-500 mb-10 max-w-3xl mx-auto text-base sm:text-lg leading-relaxed font-medium">
            Platform satu pintu bagi Unit Kegiatan Mahasiswa (UKM) Universitas Pancasila. 
            Membantu pengelolaan agenda kegiatan, berkas materi latihan digital, absensi pertemuan, 
            kas operasional, hingga pencetakan berkas Laporan Pertanggungjawaban (LPJ).
        </p>

        <!-- CTA Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-16">
            <a href="/register" 
               class="w-full sm:w-auto bg-[#0d47a1] hover:bg-[#0a3981] text-white px-8 py-4 rounded-full text-sm font-extrabold shadow-md hover:shadow-lg transition-all duration-200">
                Mulai Sekarang
            </a>
            <a href="#fitur" 
               class="w-full sm:w-auto bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 px-8 py-4 rounded-full text-sm font-extrabold shadow-sm transition-all duration-200">
                Pelajari Fitur
            </a>
        </div>

        <!-- 🌟 DASHBOARD MOCKUP (Overhauled to perfectly match live system) -->
        <div class="relative w-full max-w-5xl mx-auto rounded-2xl border border-slate-200 shadow-md bg-white overflow-hidden text-left mb-20">
            <!-- Header bar / Browser Frame -->
            <div class="h-12 bg-slate-50 border-b border-slate-200 px-5 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <span class="w-3 h-3 rounded-full bg-slate-200"></span>
                    <span class="w-3 h-3 rounded-full bg-slate-200"></span>
                    <span class="w-3 h-3 rounded-full bg-slate-200"></span>
                </div>
                <div class="text-xs text-slate-400 font-bold">ukm-pancasila.ac.id/ukm/dashboard</div>
                <div class="w-12"></div>
            </div>
            
            <!-- Mockup Workspace -->
            <div class="flex h-[380px] bg-slate-50/50">
                <!-- Sidebar (Authentic Live Sidebar Options) -->
                <div class="w-52 bg-white border-r border-slate-200 p-5 flex flex-col justify-between hidden sm:flex shrink-0">
                    <div class="space-y-5">
                        <!-- Head -->
                        <div class="flex items-center gap-2 px-1">
                            <img src="{{ asset('images/logo-up.png') }}" alt="Logo Universitas Pancasila" class="w-6 h-6 object-contain rounded">
                            <span class="text-xs font-extrabold text-slate-800">Sistem UKM</span>
                        </div>
                        
                        <!-- Navigation Items (Matching app.blade.php sidebar exactly) -->
                        <div class="space-y-1">
                            <div class="text-[10px] px-3 py-1.5 rounded-md bg-blue-50 text-[#0d47a1] font-extrabold flex items-center gap-2.5">
                                <i class="ph ph-squares-four text-xs"></i> Dashboard
                            </div>
                            <div class="text-[10px] px-3 py-1.5 text-slate-500 font-bold flex items-center gap-2.5">
                                <i class="ph ph-identification-card text-xs"></i> Profil UKM
                            </div>
                            <div class="text-[10px] px-3 py-1.5 text-slate-500 font-bold flex items-center gap-2.5">
                                <i class="ph ph-users text-xs"></i> Anggota
                            </div>
                            <div class="text-[10px] px-3 py-1.5 text-slate-500 font-bold flex items-center gap-2.5">
                                <i class="ph ph-books text-xs"></i> Materi Belajar
                            </div>
                            <div class="text-[10px] px-3 py-1.5 text-slate-500 font-bold flex items-center gap-2.5">
                                <i class="ph ph-check-square text-xs"></i> Absensi Anggota
                            </div>
                            <div class="text-[10px] px-3 py-1.5 text-slate-500 font-bold flex items-center gap-2.5">
                                <i class="ph ph-money text-xs"></i> Keuangan
                            </div>
                            <div class="text-[10px] px-3 py-1.5 text-slate-500 font-bold flex items-center gap-2.5">
                                <i class="ph ph-file-pdf text-xs"></i> Laporan
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sidebar Footer User Badge -->
                    <div class="flex items-center gap-2.5 p-2 bg-slate-50 rounded-xl border border-slate-200/50">
                        <div class="w-6 h-6 rounded-full bg-[#0d47a1] text-white text-[10px] font-black flex items-center justify-center">D</div>
                        <div class="leading-none">
                            <div class="text-[10px] font-bold text-slate-700">Dimas Satrio</div>
                            <span class="text-[8px] text-slate-400 font-bold mt-0.5 inline-block">BPH Organisasi</span>
                        </div>
                    </div>
                </div>

                <!-- Main Content Panel (Authentic Live UKM Dashboard Layout) -->
                <div class="flex-1 p-6 space-y-6 overflow-y-auto">
                    <!-- Topbar Inside Dashboard Mockup -->
                    <div class="flex justify-between items-center pb-3 border-b border-slate-200/60">
                        <h3 class="text-xs font-black text-slate-800">Kelola UKM: Basket (Pancasila)</h3>
                        <div class="flex items-center gap-2 bg-white px-2.5 py-1 rounded-lg border border-slate-200 text-[10px] font-bold text-slate-600">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active UKM
                        </div>
                    </div>

                    <!-- 4 Stat Cards Grid (Matching live ukm/dashboard.blade.php stat-grid exactly) -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex flex-col justify-between h-20">
                            <div class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Total Anggota</div>
                            <div class="text-sm font-extrabold text-slate-800 mt-1">142 Anggota</div>
                        </div>
                        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex flex-col justify-between h-20">
                            <div class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Pendaftar Baru</div>
                            <div class="text-sm font-extrabold text-[#ffb300] mt-1">3 Pendaftar</div>
                        </div>
                        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex flex-col justify-between h-20">
                            <div class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Total Event</div>
                            <div class="text-sm font-extrabold text-[#0d47a1] mt-1">12 Kegiatan</div>
                        </div>
                        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex flex-col justify-between h-20">
                            <div class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Saldo UKM</div>
                            <div class="text-sm font-extrabold text-emerald-600 mt-1">Rp 4.200.000</div>
                        </div>
                    </div>

                    <!-- Event Mendatang Block (Matching live ukm/dashboard.blade.php table exactly) -->
                    <div class="bg-white p-4.5 rounded-xl border border-slate-200 shadow-sm space-y-3">
                        <div class="flex justify-between items-center">
                            <h4 class="text-[11px] font-black text-slate-800 uppercase tracking-wider">Event Mendatang</h4>
                            <span class="text-[9px] bg-slate-100 text-slate-500 px-2 py-0.5 rounded font-extrabold">3 Jadwal Terdekat</span>
                        </div>
                        
                        <!-- Table -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-[10px]">
                                <thead>
                                    <tr class="border-b border-slate-200/60 text-slate-400 font-bold">
                                        <th class="pb-2">Nama Event</th>
                                        <th class="pb-2">Tanggal</th>
                                        <th class="pb-2">Lokasi</th>
                                        <th class="pb-2 text-right">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-slate-700">
                                    <tr>
                                        <td class="py-2.5 font-bold text-slate-800">Latihan Rutin Mingguan</td>
                                        <td class="py-2.5 font-medium">20 Mei 2026</td>
                                        <td class="py-2.5 font-medium">Hall Basket Lt. 3</td>
                                        <td class="py-2.5 text-right"><span class="bg-amber-100 text-amber-700 px-2 py-0.5 rounded font-extrabold text-[8px]">Upcoming</span></td>
                                    </tr>
                                    <tr>
                                        <td class="py-2.5 font-bold text-slate-800">Latihan Gabungan UKM</td>
                                        <td class="py-2.5 font-medium">24 Mei 2026</td>
                                        <td class="py-2.5 font-medium">Gedung Serbaguna UP</td>
                                        <td class="py-2.5 text-right"><span class="bg-amber-100 text-amber-700 px-2 py-0.5 rounded font-extrabold text-[8px]">Upcoming</span></td>
                                    </tr>
                                    <tr>
                                        <td class="py-2.5 font-bold text-slate-800">Rapat Evaluasi Kas & LPJ</td>
                                        <td class="py-2.5 font-medium">15 Mei 2026</td>
                                        <td class="py-2.5 font-medium">Ruang UKM Lt. 2</td>
                                        <td class="py-2.5 text-right"><span class="bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded font-extrabold text-[8px]">Completed</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 📊 SIMPLE STATS BAR -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-5xl mx-auto pt-8 border-t border-slate-200" id="statistik">
            <div class="p-5 bg-white rounded-xl border border-slate-200 shadow-sm">
                <div class="text-2xl sm:text-3xl font-extrabold text-[#0d47a1]">12+</div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1">UKM Aktif</div>
            </div>
            <div class="p-5 bg-white rounded-xl border border-slate-200 shadow-sm">
                <div class="text-2xl sm:text-3xl font-extrabold text-[#0d47a1]">1.5K+</div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1">Mahasiswa</div>
            </div>
            <div class="p-5 bg-white rounded-xl border border-slate-200 shadow-sm">
                <div class="text-2xl sm:text-3xl font-extrabold text-[#ffb300]">98%</div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1">Transparansi Dana</div>
            </div>
            <div class="p-5 bg-white rounded-xl border border-slate-200 shadow-sm">
                <div class="text-2xl sm:text-3xl font-extrabold text-[#ffb300]">Real-time</div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1">LPJ Verification</div>
            </div>
        </div>

    </div>
</main>

<!-- 🌟 SYSTEM WORKFLOW FLOW -->
<section id="alur-kerja" class="py-20 bg-white border-t border-slate-200">
    <div class="max-w-[1320px] mx-auto px-6">
        
        <!-- Header -->
        <div class="text-center max-w-xl mx-auto mb-16">
            <span class="text-xs font-bold uppercase tracking-wider text-[#0d47a1]">PETUNJUK PENGGUNAAN</span>
            <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight mt-2">
                Langkah Pemakaian Sistem UKM
            </h2>
            <div class="w-12 h-0.5 bg-[#ffb300] mx-auto mt-4"></div>
        </div>

        <!-- Workflow Grid -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6">

            <!-- Step 1 -->
            <div class="bg-[#fafbfd] p-6 rounded-xl border border-slate-200 flex flex-col justify-between min-h-[260px] shadow-sm">
                <div>
                    <div class="w-10 h-10 rounded-lg bg-[#0d47a1] text-white flex items-center justify-center text-sm font-bold mb-4">
                        01
                    </div>
                    <h3 class="font-extrabold text-sm text-slate-800 mb-2">Daftar Akun & UKM</h3>
                    <p class="text-slate-500 text-xs leading-relaxed font-medium">
                        Buka halaman <span class="text-slate-700 font-extrabold">Daftar Akun</span>, masukkan data diri lengkap Anda, lalu pilih UKM yang ingin Anda ikuti pada formulir pendaftaran.
                    </p>
                </div>
                <span class="text-[10px] bg-slate-200/60 px-3 py-1.5 rounded-lg text-slate-600 font-extrabold w-max mt-4">Register Akun</span>
            </div>

            <!-- Step 2 -->
            <div class="bg-[#fafbfd] p-6 rounded-xl border border-slate-200 flex flex-col justify-between min-h-[260px] shadow-sm">
                <div>
                    <div class="w-10 h-10 rounded-lg bg-[#ffb300] text-slate-850 flex items-center justify-center text-sm font-bold mb-4">
                        02
                    </div>
                    <h3 class="font-extrabold text-sm text-slate-800 mb-2">Verifikasi Admin</h3>
                    <p class="text-slate-500 text-xs leading-relaxed font-medium">
                        Pengurus BPH UKM masuk ke dashboard Admin, meninjau pendaftar baru, lalu mengubah status keanggotaan menjadi <span class="text-slate-700 font-extrabold">Approved</span>.
                    </p>
                </div>
                <span class="text-[10px] bg-amber-100 text-amber-700 px-3 py-1.5 rounded-lg font-extrabold w-max mt-4">Status: Approved</span>
            </div>

            <!-- Step 3 -->
            <div class="bg-[#fafbfd] p-6 rounded-xl border border-slate-200 flex flex-col justify-between min-h-[260px] shadow-sm">
                <div>
                    <div class="w-10 h-10 rounded-lg bg-[#0d47a1] text-white flex items-center justify-center text-sm font-bold mb-4">
                        03
                    </div>
                    <h3 class="font-extrabold text-sm text-slate-800 mb-2">Isi Absensi & LMS</h3>
                    <p class="text-slate-500 text-xs leading-relaxed font-medium">
                        Anggota masuk ke Room UKM, mengunduh file materi latihan pada tab LMS, dan klik tombol <span class="text-slate-700 font-extrabold">Isi Absensi</span> saat sesi pertemuan aktif.
                    </p>
                </div>
                <span class="text-[10px] bg-emerald-100 text-emerald-700 px-3 py-1.5 rounded-lg font-extrabold w-max mt-4">Hadir Terverifikasi</span>
            </div>

            <!-- Step 4 -->
            <div class="bg-[#fafbfd] p-6 rounded-xl border border-slate-200 flex flex-col justify-between min-h-[260px] shadow-sm">
                <div>
                    <div class="w-10 h-10 rounded-lg bg-[#ffb300] text-slate-850 flex items-center justify-center text-sm font-bold mb-4">
                        04
                    </div>
                    <h3 class="font-extrabold text-sm text-slate-800 mb-2">Klasifikasi Anggota</h3>
                    <p class="text-slate-500 text-xs leading-relaxed font-medium">
                        BPH atau Pelatih memetakan <span class="text-slate-700 font-extrabold">Klasifikasi Anggota</span> (seperti Sopran/Alto di Padus, atau Center/Guard di Futsal/Basket).
                    </p>
                </div>
                <div class="flex gap-1.5 mt-4">
                    <span class="text-[9px] bg-slate-200 text-slate-655 px-2 py-0.5 rounded font-bold">DIVISI A</span>
                    <span class="text-[9px] bg-slate-200 text-slate-655 px-2 py-0.5 rounded font-bold">DIVISI B</span>
                </div>
            </div>

            <!-- Step 5 -->
            <div class="bg-[#fafbfd] p-6 rounded-xl border border-slate-200 flex flex-col justify-between min-h-[260px] shadow-sm">
                <div>
                    <div class="w-10 h-10 rounded-lg bg-[#0d47a1] text-white flex items-center justify-center text-sm font-bold mb-4">
                        05
                    </div>
                    <h3 class="font-extrabold text-sm text-slate-800 mb-2">Input Kas & LPJ</h3>
                    <p class="text-slate-500 text-xs leading-relaxed font-medium">
                        Bendahara mencatat kas. Admin mengunduh rekap otomatis via tombol <span class="text-slate-700 font-extrabold">Cetak Laporan</span> menjadi dokumen LPJ PDF.
                    </p>
                </div>
                <span class="text-[10px] bg-slate-900 text-slate-205 px-3 py-1.5 rounded-lg font-extrabold w-max flex items-center gap-1 mt-4">LPJ PDF <i class="ph ph-download-simple"></i></span>
            </div>

        </div>

    </div>
</section>

<!-- 🌟 FEATURES GRID SECTION -->
<section id="fitur" class="py-20 bg-[#fafbfd] border-t border-slate-200">
    <div class="max-w-[1320px] mx-auto px-6">
        
        <!-- Header -->
        <div class="text-center max-w-xl mx-auto mb-16">
            <span class="text-xs font-bold uppercase tracking-wider text-[#ffb300]">FITUR UTAMA</span>
            <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight mt-2">
                Fungsionalitas Administrasi UKM Terintegrasi
            </h2>
            <div class="w-12 h-0.5 bg-[#0d47a1] mx-auto mt-4"></div>
        </div>

        <!-- Features Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            <!-- Fitur 1 -->
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm hover:border-[#0d47a1]/20 hover:shadow transition-all duration-200">
                <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center mb-5 text-[#0d47a1] text-xl">
                    <i class="ph ph-squares-four"></i>
                </div>
                <h3 class="font-extrabold text-base text-slate-800 mb-3">LMS & Materi Latihan</h3>
                <p class="text-slate-550 text-sm leading-relaxed font-medium">
                    Penyedia library digital yang menyimpan file repertoar, naskah latihan, panduan taktis (.pdf) yang dikategorikan rapi dan dapat diunduh oleh anggota untuk bahan latihan mandiri.
                </p>
            </div>

            <!-- Fitur 2 -->
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm hover:border-[#ffb300]/20 hover:shadow transition-all duration-200">
                <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center mb-5 text-[#ffb300] text-xl">
                    <i class="ph ph-check-square"></i>
                </div>
                <h3 class="font-extrabold text-base text-slate-800 mb-3">Absensi & Self Check-in</h3>
                <p class="text-slate-550 text-sm leading-relaxed font-medium">
                    Sistem kehadiran ganda terintegrasi. Mahasiswa melakukan check-in mandiri via portal web, serta pencatatan absensi & rekapitulasi kehadiran pelatih secara lengkap.
                </p>
            </div>

            <!-- Fitur 3 -->
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm hover:border-emerald-600/20 hover:shadow transition-all duration-200">
                <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center mb-5 text-emerald-600 text-xl">
                    <i class="ph ph-money"></i>
                </div>
                <h3 class="font-extrabold text-base text-slate-800 mb-3">Keuangan & Mutasi Kas</h3>
                <p class="text-slate-550 text-sm leading-relaxed font-medium">
                    Pencatatan kas masuk-keluar secara transparan. Mendukung pengelolaan uang kas bulanan anggota, dana operasional, pendanaan program kerja, hingga biaya operasional pelatih.
                </p>
            </div>

            <!-- Fitur 4 -->
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm hover:border-indigo-600/20 hover:shadow transition-all duration-200">
                <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center mb-5 text-indigo-600 text-xl">
                    <i class="ph ph-envelope-simple"></i>
                </div>
                <h3 class="font-extrabold text-base text-slate-800 mb-3">Persuratan & LPJ Digital</h3>
                <p class="text-slate-550 text-sm leading-relaxed font-medium">
                    Alur perizinan surat menyurat, proposal, dan LPJ proker langsung dari Admin UKM ke Biro Kemahasiswaan. Verifikasi surat menyurat dapat dipantau dan diunduh seketika.
                </p>
            </div>

            <!-- Fitur 5 -->
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm hover:border-[#0d47a1]/20 hover:shadow transition-all duration-200">
                <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center mb-5 text-[#0d47a1] text-xl">
                    <i class="ph ph-users"></i>
                </div>
                <h3 class="font-extrabold text-base text-slate-800 mb-3">Klasifikasi Anggota</h3>
                <p class="text-slate-550 text-sm leading-relaxed font-medium">
                    Fitur spesifik sistem untuk memetakan dan mengklasifikasikan keahlian atau posisi anggota secara dinamis (seperti divisi suara Padus, posisi bermain Futsal/Basket, atau tingkatan sabuk Beladiri).
                </p>
            </div>

            <!-- Fitur 6 -->
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm hover:border-purple-600/20 hover:shadow transition-all duration-200">
                <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center mb-5 text-purple-600 text-xl">
                    <i class="ph ph-image"></i>
                </div>
                <h3 class="font-extrabold text-base text-slate-800 mb-3">Galeri Media Latihan</h3>
                <p class="text-slate-550 text-sm leading-relaxed font-medium">
                    Dokumentasi audio-video lengkap. Menyimpan rekaman penampilan, audio vokal, atau video kegiatan di galeri sebagai sarana arsip visual kegiatan.
                </p>
            </div>

        </div>

    </div>
</section>

<!-- 🌟 PAIN POINTS VS SOLUTIONS -->
<section id="studi-kasus" class="py-20 bg-white border-t border-slate-200">
    <div class="max-w-[1320px] mx-auto px-6">
        
        <!-- Header -->
        <div class="text-center max-w-xl mx-auto mb-16">
            <span class="text-xs font-bold uppercase tracking-wider text-[#ffb300]">STUDI KASUS</span>
            <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight mt-2">
                Kendala Klasik Organisasi & Solusi Sistem
            </h2>
            <div class="w-12 h-0.5 bg-[#0d47a1] mx-auto mt-4"></div>
        </div>

        <!-- Problems vs Solutions Grid -->
        <div class="space-y-6 max-w-5xl mx-auto">
            
            <!-- Case 1 -->
            <div class="bg-[#fafbfd] p-6 rounded-xl border border-slate-200 flex flex-col md:flex-row justify-between gap-6 shadow-sm">
                <div class="md:w-1/2">
                    <div class="text-xs text-red-500 font-extrabold uppercase tracking-wider mb-2">Kendala #1 • Buku Uang Kas Manual</div>
                    <p class="text-slate-700 text-sm font-extrabold leading-snug">Pencatatan uang kas masuk-keluar UKM manual seringkali kurang transparan bagi seluruh anggota.</p>
                </div>
                <div class="md:w-1/2 bg-white p-4.5 rounded-xl border border-slate-200 flex items-start gap-3 shadow-sm">
                    <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs font-black shrink-0 mt-0.5">✔</span>
                    <div class="text-xs sm:text-sm">
                        <strong class="font-extrabold text-slate-800 block text-xs sm:text-sm">Menu Keuangan & Kas</strong>
                        <span class="text-slate-500 block mt-1 font-medium leading-relaxed">Gunakan menu Keuangan untuk menginput iuran kas masuk & pengeluaran operasional UKM secara transparan.</span>
                    </div>
                </div>
            </div>

            <!-- Case 2 -->
            <div class="bg-[#fafbfd] p-6 rounded-xl border border-slate-200 flex flex-col md:flex-row justify-between gap-6 shadow-sm">
                <div class="md:w-1/2">
                    <div class="text-xs text-red-500 font-extrabold uppercase tracking-wider mb-2">Kendala #2 • Absensi Kertas Basah & Titipan</div>
                    <p class="text-slate-700 text-sm font-extrabold leading-snug">Lembar absen kertas fisik rawan sobek/basah, serta rawan diakali mahasiswa melalui titip tanda tangan.</p>
                </div>
                <div class="md:w-1/2 bg-white p-4.5 rounded-xl border border-slate-200 flex items-start gap-3 shadow-sm">
                    <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs font-black shrink-0 mt-0.5">✔</span>
                    <div class="text-xs sm:text-sm">
                        <strong class="font-extrabold text-slate-800 block text-xs sm:text-sm">Tombol Isi Absensi Mandiri</strong>
                        <span class="text-slate-500 block mt-1 font-medium leading-relaxed">Anggota mengklik tombol Isi Absensi di menu Ruang Belajar saat sesi pertemuan diaktifkan oleh pengurus.</span>
                    </div>
                </div>
            </div>

            <!-- Case 3 -->
            <div class="bg-[#fafbfd] p-6 rounded-xl border border-slate-200 flex flex-col md:flex-row justify-between gap-6 shadow-sm">
                <div class="md:w-1/2">
                    <div class="text-xs text-red-500 font-extrabold uppercase tracking-wider mb-2">Kendala #3 • Boros Anggaran Cetak Panduan & Materi</div>
                    <p class="text-slate-700 text-sm font-extrabold leading-snug">Menggandakan berkas panduan latihan (naskah teater, panduan taktik, naskah musik) fisik memakan biaya besar.</p>
                </div>
                <div class="md:w-1/2 bg-white p-4.5 rounded-xl border border-slate-200 flex items-start gap-3 shadow-sm">
                    <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs font-black shrink-0 mt-0.5">✔</span>
                    <div class="text-xs sm:text-sm">
                        <strong class="font-extrabold text-slate-800 block text-xs sm:text-sm">Tab Materi & Pustaka Digital</strong>
                        <span class="text-slate-500 block mt-1 font-medium leading-relaxed">Buka tab materi di menu Ruang Belajar untuk mengunduh berkas panduan (.pdf) langsung ke smartphone anggota.</span>
                    </div>
                </div>
            </div>

            <!-- Case 4 -->
            <div class="bg-[#fafbfd] p-6 rounded-xl border border-slate-200 flex flex-col md:flex-row justify-between gap-6 shadow-sm">
                <div class="md:w-1/2">
                    <div class="text-xs text-red-500 font-extrabold uppercase tracking-wider mb-2">Kendala #4 • Penyusunan LPJ Fisik yang Lambat</div>
                    <p class="text-slate-700 text-sm font-extrabold leading-snug">Pengurus kelabakan menyusun draf laporan pertanggungjawaban kegiatan kampus di akhir periode jabatan.</p>
                </div>
                <div class="md:w-1/2 bg-white p-4.5 rounded-xl border border-slate-200 flex items-start gap-3 shadow-sm">
                    <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs font-black shrink-0 mt-0.5">✔</span>
                    <div class="text-xs sm:text-sm">
                        <strong class="font-extrabold text-slate-800 block text-xs sm:text-sm">Tombol Cetak Laporan LPJ</strong>
                        <span class="text-slate-500 block mt-1 font-medium leading-relaxed">Klik tombol Cetak Laporan di dashboard pengurus untuk otomatis mengunduh berkas LPJ PDF instan.</span>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- 🌟 ADVANTAGE / KEUNGGULAN -->
<section id="keunggulan" class="py-20 bg-[#fafbfd] border-t border-b border-slate-200">
    <div class="max-w-[1320px] mx-auto px-6">
        <div class="grid md:grid-cols-2 gap-12 items-center">

            <!-- Text Content -->
            <div class="space-y-5">
                <span class="text-xs font-bold uppercase tracking-wider text-[#ffb300]">KEUNGGULAN UTAMA</span>
                <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 leading-tight">
                    Solusi Lengkap Kelola Kegiatan & Admin UKM
                </h2>
                <p class="text-slate-550 text-sm leading-relaxed font-medium">
                    Aplikasi web modern yang ringan, rapi, dan sangat mudah digunakan untuk mempermudah seluruh urusan administrasi dan kegiatan operasional UKM.
                </p>

                <!-- Check items -->
                <div class="space-y-4 pt-2">
                    <div class="flex items-start gap-3">
                        <span class="w-5 h-5 rounded-full bg-slate-200 text-slate-700 flex items-center justify-center shrink-0 text-xs font-bold mt-0.5">✔</span>
                        <div>
                            <strong class="font-extrabold text-sm text-slate-800 block">Absensi Mandiri yang Aman</strong>
                            <span class="text-xs text-slate-500 block mt-0.5">Anggota bisa absen lewat HP masing-masing dan hanya bisa dilakukan saat pengurus membuka sesi kegiatan.</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="w-5 h-5 rounded-full bg-slate-200 text-slate-700 flex items-center justify-center shrink-0 text-xs font-bold mt-0.5">✔</span>
                        <div>
                            <strong class="font-extrabold text-sm text-slate-800 block">Cetak Laporan LPJ Instan</strong>
                            <span class="text-xs text-slate-500 block mt-0.5">Unduh otomatis rangkuman keuangan kas, daftar hadir kegiatan, dan berkas surat resmi menjadi file PDF sekali klik.</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="w-5 h-5 rounded-full bg-slate-200 text-slate-700 flex items-center justify-center shrink-0 text-xs font-bold mt-0.5">✔</span>
                        <div>
                            <strong class="font-extrabold text-sm text-slate-800 block">Akses Praktis Lewat HP</strong>
                            <span class="text-xs text-slate-500 block mt-0.5">Tampilan website otomatis menyesuaikan layar handphone sehingga sangat nyaman digunakan di mana saja.</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tech Checklist Widget -->
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-4">
                <span class="text-xs font-bold text-[#0d47a1] bg-blue-50 px-3 py-1 rounded uppercase tracking-wider">KEUNGGULAN UTAMA SISTEM</span>
                
                <div class="space-y-3.5">
                    <div class="flex items-start gap-3 bg-slate-50/50 p-3 rounded-lg border border-slate-100">
                        <span class="w-5 h-5 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">✔</span>
                        <div class="text-xs">
                            <strong class="font-extrabold text-slate-850 block">Penyimpanan Data Terpusat</strong>
                            <span class="text-slate-450 block mt-0.5">Seluruh data anggota, catatan keuangan kas, dan berkas persuratan tersimpan rapi dan aman dalam satu sistem.</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 bg-slate-50/50 p-3 rounded-lg border border-slate-100">
                        <span class="w-5 h-5 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">✔</span>
                        <div class="text-xs">
                            <strong class="font-extrabold text-slate-850 block">Website Ringan & Super Cepat</strong>
                            <span class="text-slate-455 block mt-0.5">Ganti halaman, mencari data, dan memproses laporan berjalan instan tanpa perlu loading berputar yang lama.</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 bg-slate-50/50 p-3 rounded-lg border border-slate-100">
                        <span class="w-5 h-5 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">✔</span>
                        <div class="text-xs">
                            <strong class="font-extrabold text-slate-850 block">Materi Belajar & Latihan Digital</strong>
                            <span class="text-slate-455 block mt-0.5">Pengurus bisa membagikan berkas materi latihan atau panduan taktis secara digital untuk dipelajari anggota.</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- 🌟 CALL TO ACTION SECTION -->
<section class="py-20 bg-[#0d47a1] text-white">
    <div class="max-w-[1320px] mx-auto px-6 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-4 tracking-tight">
            Siap Digitalisasi Organisasi UKM Anda?
        </h2>
        <p class="text-blue-100 mb-10 max-w-2xl mx-auto text-sm sm:text-base font-semibold leading-relaxed">
            Daftarkan UKM Anda sekarang untuk merasakan kemudahan absensi mandiri, klasifikasi dinamis anggota, serta kelola seluruh aktivitas, kas keuangan, dan persuratan UKM Anda dalam satu dasbor terpadu!
        </p>

        <div class="flex gap-4 justify-center items-center">
            <a href="/register" 
               class="bg-white text-[#0d47a1] hover:bg-slate-50 px-6 py-3 rounded-full text-xs font-extrabold transition-all duration-200">
                Daftar Sekarang
            </a>
            <a href="/login" 
               class="border border-white/30 hover:bg-white/10 px-6 py-3 rounded-full text-xs font-extrabold transition-all duration-200">
                Masuk ke Portal
            </a>
        </div>
    </div>
</section>

<!-- 🌟 FOOTER -->
<footer class="bg-[#0f172a] text-slate-500 text-xs py-14 border-t border-slate-800">
    <div class="max-w-[1320px] mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-8">
        
        <!-- Left Logo -->
        <div class="flex items-center space-x-3">
            <img src="{{ asset('images/logo-up.png') }}" alt="Logo Universitas Pancasila" class="w-7 h-7 object-contain rounded bg-white/10 p-0.5">
            <div>
                <p class="font-bold text-slate-350 text-xs">Sistem UKM</p>
                <p class="text-[8px] text-slate-500 font-bold uppercase tracking-widest leading-none">Universitas Pancasila</p>
            </div>
        </div>

        <!-- Center Text -->
        <div class="text-center md:text-left leading-normal font-bold">
            <p class="text-slate-300">Sistem Informasi Manajemen Unit Kegiatan Mahasiswa (UKM)</p>
            <p class="text-slate-500 mt-1 text-[10px]">Studi Kasus: Multi-Organisasi Unit Kegiatan Mahasiswa (UKM) Universitas Pancasila</p>
        </div>

        <!-- Right Copyright -->
        <div class="text-center md:text-right font-bold text-[10px] space-y-1">
            <p>© {{ date('Y') }} All Rights Reserved.</p>
            <p class="text-slate-655">Portal Resmi Layanan Kegiatan Mahasiswa Universitas Pancasila</p>
        </div>

    </div>
</footer>

</body>
</html>