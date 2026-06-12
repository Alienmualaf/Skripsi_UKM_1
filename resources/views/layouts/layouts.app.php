<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PSUP') - {{ $websiteSettings['site_name'] ?? 'Universitas Pancasila' }}</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('css/app_layout.css') }}">
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body>
    <div class="app-container">
        @php
            $isIframe = request()->has('iframe') || request()->query('iframe') || request('iframe') || isset($_GET['iframe']) || strpos(request()->fullUrl(), 'iframe') !== false;
        @endphp
        <!-- Sidebar -->
        @if(!$isIframe)
        @auth
        <aside class="sidebar">
            <button class="sidebar-close-btn" id="sidebar-close" title="Tutup Menu">
                <i class="ph ph-x"></i>
            </button>
            
            <div class="sidebar-header" style="display: flex; align-items: center; gap: 0.75rem; padding: 1.5rem 1rem;">
                <a href="/" style="display: flex; align-items: center; gap: 0.75rem; text-decoration: none; width: 100%;">
                    <div style="width: 54px; height: 54px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <img src="{{ asset('images/logo_PSUP.jpeg') }}" alt="Logo PSUP" style="width: 100%; height: 100%; object-fit: contain;">
                    </div>
                    <div style="display: flex; flex-direction: column; justify-content: center; gap: 2px;">
                        <span style="font-weight: 800; font-size: 0.95rem; line-height: 1.1; letter-spacing: -0.02em; color: var(--text-primary); font-family: 'Outfit', sans-serif;">{{ $websiteSettings['site_name'] ?? 'PSUP' }} Portal</span>
                        <span style="font-size: 0.6rem; font-weight: 700; color: #d97706; text-transform: uppercase; letter-spacing: 0.05em; line-height: 1;">Univ Pancasila</span>
                    </div>
                </a>
            </div>
            
            <ul class="nav-menu">
                @php
                    $u = auth()->user();
                @endphp

                <!-- 1. ADMINISTRATOR SIDEBAR -->
                @if($u->isSuperAdmin())
                    <li class="nav-section-title">Admin Utama</li>
                    <li class="nav-item">
                        <a href="/admin/dashboard" class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">
                            <i class="ph ph-squares-four"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/admin/users" class="{{ request()->is('admin/users*') ? 'active' : '' }}">
                            <i class="ph ph-users"></i> Manajemen Pengguna
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/admin/roles-permissions" class="{{ request()->is('admin/roles-permissions') ? 'active' : '' }}">
                            <i class="ph ph-key"></i> Role & Permission
                        </a>
                    </li>
                    
                    <li class="nav-section-title">Monitoring Sistem</li>
                    <li class="nav-item">
                        <a href="/admin/logs/activity" class="{{ request()->is('admin/logs/activity') ? 'active' : '' }}">
                            <i class="ph ph-clock-counter-clockwise"></i> Activity Log
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/admin/logs/login" class="{{ request()->is('admin/logs/login') ? 'active' : '' }}">
                            <i class="ph ph-sign-in"></i> Login History
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/admin/logs/audit" class="{{ request()->is('admin/logs/audit') ? 'active' : '' }}">
                            <i class="ph ph-list-checks"></i> Audit Trail
                        </a>
                    </li>

                    <li class="nav-section-title">Monitoring Data</li>
                    <li class="nav-item">
                        <a href="/admin/monitor/members" class="{{ request()->is('admin/monitor/members') ? 'active' : '' }}">
                            <i class="ph ph-users-three"></i> Anggota & Pelatih
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/admin/monitor/agendas" class="{{ request()->is('admin/monitor/agendas') ? 'active' : '' }}">
                            <i class="ph ph-calendar"></i> Agenda & Proker
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/admin/monitor/keuangan" class="{{ request()->is('admin/monitor/keuangan') ? 'active' : '' }}">
                            <i class="ph ph-coins"></i> Keuangan Kas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/admin/monitor/inventaris" class="{{ request()->is('admin/monitor/inventaris') ? 'active' : '' }}">
                            <i class="ph ph-package"></i> Aset & Materi Latihan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/admin/monitor/jobs" class="{{ request()->is('admin/monitor/jobs') ? 'active' : '' }}">
                            <i class="ph ph-microphone-stage"></i> Gigs & Presensi
                        </a>
                    </li>

                    <li class="nav-section-title">Maintenance</li>
                    <li class="nav-item">
                        <a href="/admin/settings" class="{{ request()->is('admin/settings') ? 'active' : '' }}">
                            <i class="ph ph-gear"></i> Pengaturan Website
                        </a>
                    </li>
                @endif

                <!-- 2. ADMIN UKM SIDEBAR -->
                @if($u->isAdminUkm() || $u->isSuperAdmin())
                    <li class="nav-section-title">Menu Admin UKM</li>
                    <li class="nav-item">
                        <a href="/ukm/dashboard" class="{{ request()->is('ukm/dashboard') ? 'active' : '' }}">
                            <i class="ph ph-squares-four"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/ukm/profile" class="{{ request()->is('ukm/profile') ? 'active' : '' }}">
                            <i class="ph ph-identification-card"></i> Profil PSUP
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/ukm/members" class="{{ request()->is('ukm/members*') ? 'active' : '' }}">
                            <i class="ph ph-users-three"></i> Kelola Anggota
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/ukm/registrations" class="{{ request()->is('ukm/registrations*') ? 'active' : '' }}">
                            <i class="ph ph-user-plus"></i> Pendaftaran Calon
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/ukm/trainers" class="{{ request()->is('ukm/trainers*') ? 'active' : '' }}">
                            <i class="ph ph-chalkboard-teacher"></i> Kelola Pelatih
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/ukm/voice-classifications" class="{{ request()->is('ukm/voice-classifications*') ? 'active' : '' }}">
                            <i class="ph ph-microphone-stage"></i> Klasifikasi Suara
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/ukm/reports" class="{{ request()->is('ukm/reports*') ? 'active' : '' }}">
                            <i class="ph ph-file-chart-bar"></i> Pusat Laporan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/ukm/galleries" class="{{ request()->is('ukm/galleries*') ? 'active' : '' }}">
                            <i class="ph ph-images"></i> Galeri Kegiatan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/ukm/achievements" class="{{ request()->is('ukm/achievements*') ? 'active' : '' }}">
                            <i class="ph ph-trophy"></i> Prestasi & Medali
                        </a>
                    </li>

                    <li class="nav-section-title">Menu Pengurus</li>
                    <li class="nav-item">
                        <a href="/pengurus/programs" class="{{ request()->is('pengurus/programs*') ? 'active' : '' }}">
                            <i class="ph ph-calendar-blank"></i> Proker & Job
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="/pengurus/finances" class="{{ request()->is('pengurus/finances*') ? 'active' : '' }}">
                            <i class="ph ph-money"></i> Keuangan Kas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/pengurus/inventories" class="{{ request()->is('pengurus/inventories*') ? 'active' : '' }}">
                            <i class="ph ph-package"></i> Inventaris
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/pengurus/materials" class="{{ request()->is('pengurus/materials*') ? 'active' : '' }}">
                            <i class="ph ph-folders"></i> Materi Latihan
                        </a>
                    </li>
                @endif

                <!-- 3. PENGURUS SIDEBAR (Dinamis berdasarkan Divisi) -->
                @if($u->isPengurus() && !$u->isSuperAdmin() && !$u->isAdminUkm())
                    <li class="nav-section-title">Menu Pengurus</li>
                    <li class="nav-item">
                        <a href="/pengurus/dashboard" class="{{ request()->is('pengurus/dashboard') ? 'active' : '' }}">
                            <i class="ph ph-squares-four"></i> Dashboard
                        </a>
                    </li>

                    @php $div = $u->division ?? ''; @endphp

                    {{-- Sekretaris --}}
                    @if(!$div || $div === 'Sekretaris')
                    <li class="nav-section-title">Sekretaris</li>
                    <li class="nav-item"><a href="/ukm/members" class="{{ request()->is('ukm/members*') ? 'active' : '' }}"><i class="ph ph-users"></i> Anggota</a></li>
                    <li class="nav-item"><a href="/pengurus/letters" class="{{ request()->is('pengurus/letters*') ? 'active' : '' }}"><i class="ph ph-envelope"></i> Persuratan</a></li>
                    @endif

                    {{-- Bendahara --}}
                    @if(!$div || $div === 'Bendahara')
                    <li class="nav-section-title">Keuangan</li>
                    <li class="nav-item"><a href="/pengurus/finances" class="{{ request()->is('pengurus/finances*') ? 'active' : '' }}"><i class="ph ph-money"></i> Keuangan Kas</a></li>
                    @endif

                    {{-- Divisi Latihan --}}
                    @if(!$div || $div === 'Divisi Latihan')
                    <li class="nav-section-title">Divisi Latihan</li>
                    <li class="nav-item"><a href="/pengurus/trainers" class="{{ request()->is('pengurus/trainers*') ? 'active' : '' }}"><i class="ph ph-chalkboard-teacher"></i> Pelatih</a></li>
                    <li class="nav-item"><a href="/pengurus/materials" class="{{ request()->is('pengurus/materials*') ? 'active' : '' }}"><i class="ph ph-folders"></i> Materi Master</a></li>
                    <li class="nav-item"><a href="/pengurus/classrooms" class="{{ request()->is('pengurus/classrooms*') ? 'active' : '' }}"><i class="ph ph-chalkboard"></i> Classroom</a></li>

                    @endif

                    {{-- Divisi Humas --}}
                    @if(!$div || $div === 'Divisi Humas')
                    <li class="nav-section-title">Humas & Program</li>
                    <li class="nav-item"><a href="/pengurus/programs" class="{{ request()->is('pengurus/programs*') ? 'active' : '' }}"><i class="ph ph-calendar-blank"></i> Proker & Job</a></li>
                    <li class="nav-item"><a href="/pengurus/announcements" class="{{ request()->is('pengurus/announcements*') ? 'active' : '' }}"><i class="ph ph-megaphone"></i> Pengumuman</a></li>
                    @endif

                    {{-- Divisi Perlengkapan --}}
                    @if(!$div || $div === 'Divisi Perlengkapan')
                    <li class="nav-section-title">Perlengkapan</li>
                    <li class="nav-item"><a href="/pengurus/inventories" class="{{ request()->is('pengurus/inventories*') ? 'active' : '' }}"><i class="ph ph-package"></i> Inventaris</a></li>
                    @endif

                    {{-- Selalu tampil (laporan) --}}
                    <li class="nav-section-title">Laporan</li>
                    <li class="nav-item"><a href="/ukm/reports" class="{{ request()->is('ukm/reports*') ? 'active' : '' }}"><i class="ph ph-file-chart-bar"></i> Pusat Laporan</a></li>
                @endif

                <!-- 4. ANGGOTA SIDEBAR -->
                @if($u->isAnggota() && !$u->isSuperAdmin() && !$u->isAdminUkm() && !$u->isPengurus())
                    <li class="nav-section-title">Menu Anggota</li>
                    <li class="nav-item">
                        <a href="/member/dashboard" class="{{ request()->is('member/dashboard') ? 'active' : '' }}">
                            <i class="ph ph-house"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/member/materials" class="{{ request()->is('member/materials*') ? 'active' : '' }}">
                            <i class="ph ph-folders"></i> Materi Latihan
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="/member/classrooms" class="{{ request()->is('member/classrooms*') ? 'active' : '' }}">
                            <i class="ph ph-chalkboard"></i> Classroom Saya
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/member/profile" class="{{ request()->is('member/profile*') ? 'active' : '' }}">
                            <i class="ph ph-user-circle"></i> Profil Saya
                        </a>
                    </li>
                @endif
            </ul>
        </aside>
        @endauth
        @endif

        <!-- Main Content -->
        <main class="main-content animate-fade-in" style="{{ !auth()->check() || $isIframe ? 'margin-left: 0; max-width: 100%; padding: 1rem;' : '' }}">
            @if(!$isIframe)
            @auth
            <div class="topbar">
                <div style="display: flex; align-items: center; gap: 0.75rem; flex: 1; min-width: 0;">
                    <button class="menu-toggle-btn" id="menu-toggle" title="Buka Menu" style="flex-shrink: 0;">
                        <i class="ph ph-list"></i>
                    </button>
                    
                    @php
                        $isFirstLevel = request()->is('member/dashboard') || 
                                         request()->is('ukm/dashboard') || 
                                         request()->is('admin/dashboard') || 
                                         request()->is('pengurus/dashboard') ||
                                         request()->is('/') || 
                                         request()->is('login') || 
                                         request()->is('register');
                                         
                        $hideBackButton = $isFirstLevel;
                    @endphp
                    @if(!$hideBackButton)
                        <a href="javascript:history.back()" class="btn btn-secondary" style="width: 36px; height: 36px; padding: 0; border-radius: 8px; display: flex; align-items: center; justify-content: center; border: 1px solid var(--border-color); background: var(--surface-color); color: var(--text-primary); transition: all 0.2s; text-decoration: none; box-shadow: var(--shadow-sm); flex-shrink: 0;" title="Kembali">
                            <i class="ph ph-arrow-left" style="font-size: 1.1rem; font-weight: bold;"></i>
                        </a>
                    @endif
                    <h2 style="font-weight: 600; font-size: 1.25rem; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; flex: 1; min-width: 0; display: flex; align-items: center; gap: 0.5rem;" title="@yield('header')">
                        @yield('header')
                    </h2>
                </div>
                
                <div class="profile-dropdown">
                    <div class="user-profile flex items-center gap-3" id="profile-trigger" style="cursor: pointer;">
                        <div style="text-align: right;">
                            <div style="font-weight: 700; font-size: 0.875rem;">{{ auth()->user()->name }}</div>
                            <div style="color: var(--text-secondary); font-size: 0.75rem; text-transform: capitalize; font-weight: 500;">
                                {{ str_replace('_', ' ', auth()->user()->role ? auth()->user()->role->display_name : 'Anggota') }}
                            </div>
                        </div>
                        <div class="avatar">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    </div>

                    <div class="dropdown-menu" id="profile-menu">
                        <div style="padding: 0.75rem 1rem;">
                            <div style="font-weight: 700; font-size: 0.875rem;">{{ auth()->user()->name }}</div>
                            <div style="color: var(--text-secondary); font-size: 0.75rem;">{{ auth()->user()->email }}</div>
                        </div>
                        <div class="dropdown-divider"></div>
                        
                        <a href="/member/profile" class="dropdown-item">
                            <i class="ph ph-user-circle"></i> Profil Saya
                        </a>

                        <button id="theme-toggle" class="dropdown-item">
                            <i class="ph ph-moon" id="theme-icon"></i>
                            <span>Tema Gelap</span>
                        </button>

                        <div class="dropdown-divider"></div>
                        
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item" style="color: var(--danger-color);">
                                <i class="ph ph-sign-out"></i> Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endauth
            @endif

            <!-- Toast Container for modern notifications -->
            <div id="toast-container" style="position: fixed; top: 1.5rem; right: 1.5rem; z-index: 99999; display: flex; flex-direction: column; gap: 0.75rem; pointer-events: none;"></div>

            <!-- Custom Confirm Modal Overlay -->
            <div id="confirm-modal-overlay" style="position: fixed; inset: 0; z-index: 999999; background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px); display: flex; align-items: center; justify-content: center; opacity: 0; pointer-events: none; transition: opacity 0.25s ease;">
                <div id="confirm-modal" style="background: var(--surface-color); border: 1px solid var(--border-color); border-radius: 16px; width: 90%; max-width: 400px; padding: 1.5rem; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15), 0 10px 10px -5px rgba(0, 0, 0, 0.05); transform: scale(0.9); transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);">
                    <div style="display: flex; align-items: flex-start; gap: 1rem; margin-bottom: 1.25rem;">
                        <div style="background: rgba(239, 68, 68, 0.1); color: var(--danger-color); border-radius: 10px; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="ph-fill ph-warning" style="font-size: 1.5rem;"></i>
                        </div>
                        <div style="flex: 1;">
                            <h4 style="margin: 0 0 0.35rem 0; font-weight: 800; font-size: 1.05rem; color: var(--text-primary); font-family: 'Outfit', sans-serif;">Konfirmasi Tindakan</h4>
                            <p id="confirm-modal-message" style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5; font-weight: 500;"></p>
                        </div>
                    </div>
                    <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                        <button id="confirm-btn-cancel" style="background: var(--bg-color); border: 1px solid var(--border-color); color: var(--text-primary); font-weight: 700; padding: 0.6rem 1.25rem; font-size: 0.8125rem; border-radius: 8px; cursor: pointer; transition: all 0.2s; font-family: 'Plus Jakarta Sans', sans-serif;">Batal</button>
                        <button id="confirm-btn-ok" style="background: var(--danger-color); border: none; color: white; font-weight: 700; padding: 0.6rem 1.25rem; font-size: 0.8125rem; border-radius: 8px; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.15); font-family: 'Plus Jakarta Sans', sans-serif;">Lanjutkan</button>
                    </div>
                </div>
            </div>

            <style>
                /* Hide inline duplicate success/error cards in content pages */
                .card.mb-4.animate-fade-in[style*="#ecfdf5"], 
                .card.mb-4.animate-fade-in[style*="background: #ecfdf5"],
                .card.mb-4.animate-fade-in[style*="#fef2f2"],
                .card.mb-4.animate-fade-in[style*="background: #fef2f2"],
                .card.mb-4.animate-fade-in[style*="background: rgba(239, 68, 68, 0.1)"],
                div[style*="background-color: var(--success-color)"],
                div[style*="background-color: var(--danger-color)"] {
                    display: none !important;
                }
            </style>

            @yield('content')
        </main>
    </div>

    <script>
        const themeToggleBtn = document.getElementById('theme-toggle');
        const themeIcon = document.getElementById('theme-icon');
        const htmlElement = document.documentElement;
        const profileTrigger = document.getElementById('profile-trigger');
        const profileMenu = document.getElementById('profile-menu');
        const profileDropdown = document.querySelector('.profile-dropdown');

        function updateThemeUI() {
            if (!themeIcon || !themeToggleBtn) return;
            const isDark = htmlElement.classList.contains('dark');
            
            if (isDark) {
                themeIcon.className = 'ph ph-sun';
                themeToggleBtn.querySelector('span').textContent = 'Tema Terang';
            } else {
                themeIcon.className = 'ph ph-moon';
                themeToggleBtn.querySelector('span').textContent = 'Tema Gelap';
            }
        }

        if (themeToggleBtn) {
            updateThemeUI();
            themeToggleBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                htmlElement.classList.toggle('dark');
                localStorage.setItem('theme', htmlElement.classList.contains('dark') ? 'dark' : 'light');
                updateThemeUI();
            });
        }

        if (profileTrigger) {
            profileTrigger.addEventListener('click', function(e) {
                e.stopPropagation();
                profileMenu.classList.toggle('show');
            });

            document.addEventListener('click', function(e) {
                if (profileDropdown && !profileDropdown.contains(e.target)) {
                    profileMenu.classList.remove('show');
                }
            });
        }

        function showToast(message, type) {
            type = type || 'success';
            const container = document.getElementById('toast-container');
            if (!container) return;

            const toast = document.createElement('div');
            toast.style.cssText = 'pointer-events: auto; min-width: 300px; max-width: 450px; background: var(--surface-color); border: 1px solid ' + (type === 'success' ? 'rgba(16, 185, 129, 0.2)' : 'rgba(239, 68, 68, 0.2)') + '; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08); border-radius: 12px; padding: 1rem 1.25rem; display: flex; align-items: center; gap: 0.75rem; transform: translateX(120%); transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.4s ease; opacity: 0;';

            const iconColor = type === 'success' ? 'var(--success-color)' : 'var(--danger-color)';
            const iconClass = type === 'success' ? 'ph-fill ph-check-circle' : 'ph-fill ph-warning-circle';

            toast.innerHTML = '<i class="' + iconClass + '" style="color: ' + iconColor + '; font-size: 1.5rem; flex-shrink: 0;"></i>' +
                '<div style="flex: 1; color: var(--text-primary); font-size: 0.875rem; font-weight: 600; line-height: 1.4; font-family: \'Plus Jakarta Sans\', sans-serif;">' + message + '</div>' +
                '<button onclick="this.parentElement.remove()" style="background: none; border: none; color: var(--text-secondary); cursor: pointer; padding: 0.25rem; display: flex; align-items: center; justify-content: center; opacity: 0.6; transition: opacity 0.2s;" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0.6">' +
                    '<i class="ph ph-x" style="font-size: 1rem; font-weight: bold;"></i>' +
                '</button>';

            container.appendChild(toast);

            // Trigger slide-in
            setTimeout(function() {
                toast.style.transform = 'translateX(0)';
                toast.style.opacity = '1';
            }, 50);

            // Slide-out and remove
            setTimeout(function() {
                toast.style.transform = 'translateX(120%)';
                toast.style.opacity = '0';
                setTimeout(function() {
                    toast.remove();
                }, 400);
            }, 4000);
        }

        let confirmCallback = null;

        function showCustomConfirm(message, callback) {
            const overlay = document.getElementById('confirm-modal-overlay');
            const modal = document.getElementById('confirm-modal');
            const msgEl = document.getElementById('confirm-modal-message');
            
            if (!overlay || !modal || !msgEl) return;
            
            msgEl.textContent = message;
            confirmCallback = callback;
            
            overlay.style.pointerEvents = 'auto';
            overlay.style.opacity = '1';
            modal.style.transform = 'scale(1)';
        }

        function hideCustomConfirm() {
            const overlay = document.getElementById('confirm-modal-overlay');
            const modal = document.getElementById('confirm-modal');
            if (!overlay || !modal) return;
            
            overlay.style.pointerEvents = 'none';
            overlay.style.opacity = '0';
            modal.style.transform = 'scale(0.9)';
            confirmCallback = null;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menu-toggle');
            const sidebarClose = document.getElementById('sidebar-close');
            const sidebar = document.querySelector('.sidebar');
            const sidebarOverlay = document.getElementById('sidebar-overlay');

            if (menuToggle && sidebar) {
                menuToggle.addEventListener('click', function() {
                    sidebar.classList.add('active');
                });

                if (sidebarClose) {
                    sidebarClose.addEventListener('click', function() {
                        sidebar.classList.remove('active');
                    });
                }
            }

            const cancelBtn = document.getElementById('confirm-btn-cancel');
            const okBtn = document.getElementById('confirm-btn-ok');
            
            if (cancelBtn) {
                cancelBtn.addEventListener('click', hideCustomConfirm);
            }
            
            if (okBtn) {
                okBtn.addEventListener('click', function() {
                    if (confirmCallback) {
                        confirmCallback();
                    }
                    hideCustomConfirm();
                });
            }
            
            // Auto-intercept forms with onsubmit="return confirm(...)"
            document.querySelectorAll('form').forEach(function(form) {
                const onsubmitAttr = form.getAttribute('onsubmit');
                if (onsubmitAttr && onsubmitAttr.indexOf('confirm(') !== -1) {
                    const match = onsubmitAttr.match(/confirm\(['"](.+?)['"]\)/);
                    if (match && match[1]) {
                        const message = match[1];
                        form.removeAttribute('onsubmit'); // Remove default confirm
                        form.addEventListener('submit', function(e) {
                            if (form.dataset.confirmed === 'true') {
                                return;
                            }
                            e.preventDefault();
                            showCustomConfirm(message, function() {
                                form.dataset.confirmed = 'true';
                                form.submit();
                            });
                        });
                    }
                }
            });

            // Spawn session toasts if present
            @if(session('success'))
                showToast({!! json_encode(session('success')) !!}, 'success');
            @endif
            @if(session('error'))
                showToast({!! json_encode(session('error')) !!}, 'error');
            @endif
        });
    </script>
</body>
</html>