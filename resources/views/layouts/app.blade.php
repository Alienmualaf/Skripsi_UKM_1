<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'UKM Management') - Sistem Informasi UKM</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        .nav-section-title {
            margin-top: 1.5rem; 
            margin-bottom: 0.5rem;
            color: var(--text-secondary); 
            font-size: 0.65rem; 
            font-weight: 800; 
            padding-left: 0.85rem; 
            text-transform: uppercase;
            letter-spacing: 0.075em;
            opacity: 0.85;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            user-select: none;
        }
        .nav-section-title::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border-color);
            opacity: 0.4;
        }
        .nav-menu {
            padding-bottom: 3rem;
        }
        .badge-info {
            background-color: rgba(30, 64, 175, 0.06);
            color: var(--accent-color);
            border: 1.5px solid rgba(30, 64, 175, 0.2);
        }

        @if(request()->is('admin*'))
        /* Modern Super Admin styling overrides */
        .main-content {
            background-color: var(--bg-color);
        }
        
        /* Metric cards */
        .content-body > div[style*="grid-template-columns: repeat(auto-fit"][style*="gap: 1rem"] > div,
        .content-body > div[style*="grid-template-columns: repeat(auto-fit"][style*="gap: 1.25rem"] > div,
        .content-body > div[style*="grid-template-columns: repeat(auto-fit"][style*="gap:1rem"] > div {
            border-radius: 16px !important;
            box-shadow: var(--shadow-sm) !important;
            border: 1px solid var(--border-color) !important;
            border-top: 3px solid var(--accent-color) !important;
            background: var(--surface-color) !important;
            padding: 1.5rem !important;
            margin-bottom: 0 !important;
            transition: transform 0.2s ease, box-shadow 0.2s ease !important;
        }
        .content-body > div[style*="grid-template-columns: repeat(auto-fit"][style*="gap: 1rem"] > div:hover,
        .content-body > div[style*="grid-template-columns: repeat(auto-fit"][style*="gap: 1.25rem"] > div:hover,
        .content-body > div[style*="grid-template-columns: repeat(auto-fit"][style*="gap:1rem"] > div:hover {
            transform: translateY(-2px) !important;
            box-shadow: var(--shadow-md) !important;
        }
        
        /* UKM selection cards grid */
        .content-body > div[style*="grid-template-columns: repeat(auto-fill, minmax(240px, 1fr))"] {
            gap: 1.5rem !important;
            margin-bottom: 1.5rem !important;
        }
        .content-body > div[style*="grid-template-columns: repeat(auto-fill, minmax(240px, 1fr))"] > a {
            border-radius: 16px !important;
            box-shadow: var(--shadow-sm) !important;
            border: 1px solid var(--border-color) !important;
            border-top: 3px solid var(--accent-color) !important;
            background: var(--surface-color) !important;
            padding: 1.5rem !important;
            margin-bottom: 0 !important;
            transition: all 0.25s ease !important;
        }
        .content-body > div[style*="grid-template-columns: repeat(auto-fill, minmax(240px, 1fr))"] > a:hover {
            transform: translateY(-4px) !important;
            box-shadow: var(--shadow-md) !important;
            border-color: var(--accent-color) !important;
        }
        
        /* Detail lists and tables */
        .content-body > .table-wrapper {
            background: var(--surface-color) !important;
            border: 1px solid var(--border-color) !important;
            border-radius: 16px !important;
            box-shadow: var(--shadow-sm) !important;
            padding: 1.5rem !important;
            margin-bottom: 1.5rem !important;
            border-top: 3px solid var(--accent-color) !important;
        }
        
        /* Charts and reports cards */
        .content-body > div[style*="background: var(--surface-color)"][style*="border-radius"] {
            border-radius: 16px !important;
            box-shadow: var(--shadow-sm) !important;
            border: 1px solid var(--border-color) !important;
            padding: 1.5rem !important;
            margin-bottom: 1.5rem !important;
            border-top: 3px solid var(--success-color) !important;
        }
        
        /* Double charts grid */
        .content-body > div[style*="grid-template-columns: 1fr 1fr"] > div {
            border-radius: 16px !important;
            box-shadow: var(--shadow-sm) !important;
            border: 1px solid var(--border-color) !important;
            padding: 1.5rem !important;
            margin-bottom: 0 !important;
            border-top: 3px solid var(--success-color) !important;
            background: var(--surface-color) !important;
        }
        
        /* Detail Mode Header back link styling */
        .content-body > div[style*="margin-bottom: 1.5rem"][style*="display: flex"] {
            background: var(--surface-color);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 0.75rem 1.25rem;
            margin-bottom: 1.5rem !important;
            box-shadow: var(--shadow-sm);
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .content-body > div[style*="margin-bottom: 1.5rem"][style*="display: flex"] a {
            text-decoration: none;
            font-weight: 700;
            color: var(--accent-color);
            transition: opacity 0.2s;
        }
        .content-body > div[style*="margin-bottom: 1.5rem"][style*="display: flex"] a:hover {
            opacity: 0.8;
        }
        
        /* Gallery cards */
        .content-body > div[style*="grid-template-columns: repeat(auto-fill, minmax(280px, 1fr))"] > div {
            border-radius: 16px !important;
            box-shadow: var(--shadow-sm) !important;
            border: 1px solid var(--border-color) !important;
            transition: all 0.25s ease !important;
            background: var(--surface-color) !important;
        }
        .content-body > div[style*="grid-template-columns: repeat(auto-fill, minmax(280px, 1fr))"] > div:hover {
            transform: translateY(-4px) !important;
            box-shadow: var(--shadow-md) !important;
            border-color: var(--accent-color) !important;
        }
        
        /* Modals and Modal Cards */
        .modal .card {
            border-top: 4px solid var(--accent-color) !important;
            border-radius: 20px !important;
            box-shadow: var(--shadow-lg) !important;
            padding: 2.25rem !important;
        }
        
        /* Form inputs in modals */
        .modal .form-control, .modal select, .modal textarea {
            border-radius: 12px !important;
            border: 1px solid var(--border-color) !important;
            padding: 0.65rem 0.85rem !important;
            font-size: 0.875rem !important;
            transition: border-color 0.2s !important;
        }
        .modal .form-control:focus {
            border-color: var(--accent-color) !important;
        }
        @endif
    </style>
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
        <!-- Sidebar -->
        @auth
        <aside class="sidebar">
            <!-- Close Button for Mobile -->
            <button class="sidebar-close-btn" id="sidebar-close" title="Tutup Menu">
                <i class="ph ph-x"></i>
            </button>
            
            <div class="sidebar-header" style="display: flex; align-items: center; gap: 0.75rem; padding: 1.5rem;">
                <img src="{{ asset('images/logo-up.png') }}" alt="Logo" style="width: 32px; height: 32px; object-fit: contain;">
                <span style="font-weight: 800; font-size: 1.25rem; letter-spacing: -0.025em; color: var(--text-primary);">{{ \App\Services\SettingService::get('app_name', 'Sistem UKM') }}</span>
            </div>
            
            <ul class="nav-menu">
                @php
                    $managedUkmId = session('managed_ukm_id');
                    $isActuallyAdmin = auth()->user()->isSuperAdmin() || 
                                       ($managedUkmId && auth()->user()->memberships()->where('ukm_id', $managedUkmId)->where('role_in_ukm', 'admin')->exists());
                @endphp

                @if((request()->is('ukm/*') || request()->is('member/profile') || request()->is('member/dashboard') || (request()->is('calendar') && !auth()->user()->isSuperAdmin())) && $isActuallyAdmin)
                    <!-- MENU KHUSUS ADMIN UKM (SAAT MENGELOLA UKM) -->
                    <li class="nav-section-title">Menu Utama</li>
                    <li class="nav-item">
                        <a href="/ukm/dashboard" class="{{ request()->is('ukm/dashboard') ? 'active' : '' }}">
                            <i class="ph ph-squares-four"></i> Dashboard UKM
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/ukm/profile" class="{{ request()->is('ukm/profile') ? 'active' : '' }}">
                            <i class="ph ph-identification-card"></i> Profil UKM
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/ukm/members" class="{{ request()->is('ukm/members') ? 'active' : '' }}">
                            <i class="ph ph-users"></i> Kelola Anggota
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/ukm/coaches" class="{{ request()->is('ukm/coaches') ? 'active' : '' }}">
                            <i class="ph ph-briefcase"></i> Kelola Pelatih
                        </a>
                    </li>

                    <li class="nav-section-title">Kegiatan & Pembelajaran</li>
                    <li class="nav-item">
                        <a href="/calendar" class="{{ request()->is('calendar') ? 'active' : '' }}">
                            <i class="ph ph-calendar"></i> Agenda & Jadwal
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/ukm/classroom" class="{{ request()->is('ukm/classroom*') ? 'active' : '' }}">
                            <i class="ph ph-presentation"></i> Pusat Kegiatan
                        </a>
                    </li>

                    <li class="nav-section-title">Administrasi & Keuangan</li>
                    <li class="nav-item">
                        <a href="/ukm/finance" class="{{ request()->is('ukm/finance') ? 'active' : '' }}">
                            <i class="ph ph-money"></i> Keuangan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/ukm/inventory" class="{{ request()->is('ukm/inventory') ? 'active' : '' }}">
                            <i class="ph ph-package"></i> Inventaris
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/ukm/galleries" class="{{ request()->is('ukm/galleries') ? 'active' : '' }}">
                            <i class="ph ph-image"></i> Galeri
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/ukm/reports" class="{{ request()->is('ukm/reports') ? 'active' : '' }}">
                            <i class="ph ph-file-pdf"></i> Laporan
                        </a>
                    </li>
                @else
                    @if(auth()->user()->isSuperAdmin())
                        <li class="nav-section-title">Sistem Utama</li>
                        <li class="nav-item">
                            <a href="/admin/dashboard" class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">
                                <i class="ph ph-squares-four"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/admin/users" class="{{ request()->is('admin/users*') ? 'active' : '' }}">
                                <i class="ph ph-users"></i> Kelola User
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/admin/ukm" class="{{ request()->is('admin/ukm*') ? 'active' : '' }}">
                                <i class="ph ph-buildings"></i> Kelola UKM
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/admin/settings" class="{{ request()->is('admin/settings') ? 'active' : '' }}">
                                <i class="ph ph-gear"></i> Pengaturan Sistem
                            </a>
                        </li>

                        <li class="nav-section-title">Operasional Global</li>
                        <li class="nav-item">
                            <a href="/calendar" class="{{ request()->is('calendar') ? 'active' : '' }}">
                                <i class="ph ph-calendar"></i> Kalender & Agenda
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/admin/events" class="{{ request()->is('admin/events') ? 'active' : '' }}">
                                <i class="ph ph-calendar-check"></i> Kegiatan UKM
                            </a>
                        </li>

                        <li class="nav-section-title">Monitoring UKM</li>
                        <li class="nav-item">
                            <a href="/admin/memberships" class="{{ request()->is('admin/memberships') ? 'active' : '' }}">
                                <i class="ph ph-users-three"></i> Keanggotaan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/admin/coaches" class="{{ request()->is('admin/coaches') ? 'active' : '' }}">
                                <i class="ph ph-briefcase"></i> Pembina & Pelatih
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/admin/attendances" class="{{ request()->is('admin/attendances') ? 'active' : '' }}">
                                <i class="ph ph-check-square"></i> Absensi Anggota
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/admin/coach-attendances" class="{{ request()->is('admin/coach-attendances') ? 'active' : '' }}">
                                <i class="ph ph-chalkboard-teacher"></i> Absensi Pelatih
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/admin/announcements" class="{{ request()->is('admin/announcements') ? 'active' : '' }}">
                                <i class="ph ph-megaphone"></i> Pengumuman
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/admin/finances" class="{{ request()->is('admin/finances') ? 'active' : '' }}">
                                <i class="ph ph-money"></i> Keuangan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/admin/inventories" class="{{ request()->is('admin/inventories') ? 'active' : '' }}">
                                <i class="ph ph-package"></i> Inventaris
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/admin/galleries" class="{{ request()->is('admin/galleries') ? 'active' : '' }}">
                                <i class="ph ph-image"></i> Galeri
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/admin/materials" class="{{ request()->is('admin/materials') ? 'active' : '' }}">
                                <i class="ph ph-books"></i> Materi
                            </a>
                        </li>
                    @else
                        @php
                            $adminMembership = auth()->user()->memberships()->where('role_in_ukm', 'admin')->where('status', 'approved')->first();
                        @endphp
                        
                        @if(!$adminMembership)
                            <li class="nav-section-title">Menu Utama</li>
                            <li class="nav-item">
                                <a href="/member/dashboard" class="{{ request()->is('member/dashboard') ? 'active' : '' }}">
                                    <i class="ph ph-house"></i> Home
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/member/join" class="{{ request()->is('member/join') ? 'active' : '' }}">
                                    <i class="ph ph-user-plus"></i> Daftar UKM
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/calendar" class="{{ request()->is('calendar') ? 'active' : '' }}">
                                    <i class="ph ph-calendar"></i> Agenda Kalender
                                </a>
                            </li>
                        @endif
                        
                        <!-- Dinamis: Tampilkan Room UKM yang diikuti -->
                        @php
                            $myUkms = auth()->user()->memberships()->where('status', 'approved')->get();
                        @endphp
                        
                        @if($myUkms->count() > 0)
                            <li class="nav-section-title">UKM Saya</li>
                            @foreach($myUkms as $m)
                                <li class="nav-item">
                                    <a href="{{ $m->role_in_ukm === 'admin' ? '/ukm/dashboard?ukm_id='.$m->ukm_id : '/room/'.$m->ukm_id.'/classroom' }}" 
                                       class="{{ (request()->is('room/'.$m->ukm_id.'*') || request()->is('ukm/*')) ? 'active' : '' }}" 
                                       style="display: flex; justify-content: space-between; align-items: center; padding-right: 0.5rem;">
                                        <span><i class="ph ph-tent"></i> {{ $m->ukm->name }}</span>
                                        @if($m->role_in_ukm === 'member')
                                            <div onclick="toggleActivities(event, 'activities-{{ $m->ukm_id }}')" 
                                                 class="toggle-btn" 
                                                 id="btn-activities-{{ $m->ukm_id }}">
                                                <i class="ph ph-caret-down"></i>
                                            </div>
                                        @endif
                                    </a>
                                </li>
                                <!-- Jika member, tampilkan list real kegiatan terbaru dalam dropdown -->
                                @if($m->role_in_ukm === 'member')
                                    @php
                                        $activities = $m->participatingEvents()->orderBy('start_date', 'desc')->take(5)->get();
                                        $isCurrentUkm = request()->is('room/'.$m->ukm_id.'*');
                                    @endphp
                                    <ul id="activities-{{ $m->ukm_id }}" class="activity-list {{ $isCurrentUkm ? 'show' : '' }}">
                                        @foreach($activities as $act)
                                            <li class="activity-item">
                                                <a href="{{ route('classroom.show', [$m->ukm_id, $act->id]) }}" 
                                                   class="{{ request()->is('*/classroom/'.$act->id) ? 'active' : '' }}">
                                                    {{ $act->title }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                                <!-- Jika admin UKM, kasih link ke dashboard admin UKM tersebut -->
                                @if($m->role_in_ukm === 'admin')
                                    <li class="nav-item">
                                        <a href="/ukm/dashboard?ukm_id={{ $m->ukm_id }}" class="{{ request()->is('ukm/*') ? 'active' : '' }}" style="padding-left: 2.5rem; font-size: 0.875rem;">
                                            <i class="ph ph-gear"></i> Kelola {{ $m->ukm->name }}
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    @endif
                @endif
            </ul>
        </aside>
        @endauth

        <!-- Main Content -->
        <main class="main-content animate-fade-in" style="{{ !auth()->check() ? 'margin-left: 0; max-width: 100%; display: flex; align-items: center; justify-content: center;' : '' }}">
            @auth
            <div class="topbar">
                <div style="display: flex; align-items: center; gap: 0.75rem; flex: 1; min-width: 0;">
                    <!-- Hamburger Button for Mobile -->
                    <button class="menu-toggle-btn" id="menu-toggle" title="Buka Menu" style="flex-shrink: 0;">
                        <i class="ph ph-list"></i>
                    </button>
                    
                    @php
                        // Halaman utama tingkat 1 (Dashboard, Kalender, Profil)
                        $isFirstLevel = request()->is('member/dashboard') || 
                                         request()->is('ukm/dashboard') || 
                                         request()->is('admin/dashboard') || 
                                         request()->is('biro/dashboard') || 
                                         request()->is('calendar') || 
                                         request()->is('profile') ||
                                         request()->is('member/profile') ||
                                         request()->is('/') ||
                                         request()->is('member/join');
                                         
                        // Halaman Room UKM utama (dashboard level 1 UKM)
                        $isFirstLevelRoom = request()->is('room/*') && !request()->is('room/*/classroom*') && !request()->is('room/*/materials*');
                        
                        // Menu utama di sidebar Admin UKM (tanpa sub-path detail/form)
                        $isUkmPrimaryMenu = request()->is('ukm/profile') || 
                                            request()->is('ukm/members') || 
                                            request()->is('ukm/coaches') || 
                                            request()->is('ukm/materials') || 
                                            request()->is('ukm/announcements') || 
                                            request()->is('ukm/attendances') || 
                                            request()->is('ukm/coach-attendances') || 
                                            request()->is('ukm/finance') || 
                                            request()->is('ukm/inventory') || 
                                            request()->is('ukm/galleries') || 
                                            request()->is('ukm/reports');
                                            
                        // Menu utama di sidebar Super Admin & Biro
                        $isAdminBiroPrimaryMenu = request()->is('admin/users') || 
                                                  request()->is('admin/ukm') || 
                                                  request()->is('admin/settings') || 
                                                  request()->is('admin/events') || 
                                                  request()->is('admin/finances') || 
                                                  request()->is('admin/inventories') || 
                                                  request()->is('admin/galleries') || 
                                                  request()->is('admin/materials') || 
                                                  request()->is('admin/submissions') ||
                                                  request()->is('admin/memberships') ||
                                                  request()->is('admin/coaches') ||
                                                  request()->is('admin/announcements') ||
                                                  request()->is('admin/attendances') ||
                                                  request()->is('admin/coach-attendances');
                                                  
                        $hideBackButton = $isFirstLevel || $isFirstLevelRoom || $isUkmPrimaryMenu || $isAdminBiroPrimaryMenu;
                    @endphp
                    @if(!$hideBackButton)
                        <a href="javascript:history.back()" class="btn btn-secondary" style="width: 36px; height: 36px; padding: 0; border-radius: 8px; display: flex; align-items: center; justify-content: center; border: 1px solid var(--border-color); background: var(--surface-color); color: var(--text-primary); transition: all 0.2s; text-decoration: none; box-shadow: var(--shadow-sm); flex-shrink: 0;" onmouseover="this.style.borderColor='var(--text-secondary)';" onmouseout="this.style.borderColor='var(--border-color)'';" title="Kembali">
                            <i class="ph ph-arrow-left" style="font-size: 1.1rem; font-weight: bold;"></i>
                        </a>
                    @endif
                    <h2 style="font-weight: 600; font-size: 1.25rem; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; flex: 1; min-width: 0; display: flex; align-items: center; gap: 0.5rem;" title="@yield('header')">
                        @yield('header')
                        <span class="pulse-dot" style="margin-left: 0.25rem;" title="Sistem Aktif & Terkoneksi"></span>
                    </h2>
                </div>
                
                <div class="profile-dropdown">
                    <div class="user-profile flex items-center gap-3" id="profile-trigger" style="cursor: pointer;">
                        <div style="text-align: right;">
                            <div style="font-weight: 700; font-size: 0.875rem;">{{ auth()->user()->name }}</div>
                            <div style="color: var(--text-secondary); font-size: 0.75rem; text-transform: capitalize; font-weight: 500;">{{ str_replace('_', ' ', auth()->user()->role) }}</div>
                        </div>
                        <div class="avatar">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    </div>

                    <div class="dropdown-menu" id="profile-menu">
                        <div style="padding: 0.75rem 1rem;">
                            <div style="font-weight: 700; font-size: 0.875rem;">{{ auth()->user()->name }}</div>
                            <div style="color: var(--text-secondary); font-size: 0.75rem;">{{ auth()->user()->email }}</div>
                            @if(auth()->user()->faculty)
                                <div style="color: var(--accent-color); font-size: 0.75rem; font-weight: 600; margin-top: 0.25rem;">{{ auth()->user()->faculty }}</div>
                            @endif
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

            @if(session('success'))
                <div style="background-color: var(--success-color); color: white; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="ph-fill ph-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div style="background-color: var(--danger-color); color: white; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="ph-fill ph-warning-circle"></i>
                    {{ session('error') }}
                </div>
            @endif

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

        // Theme Toggle
        if (themeToggleBtn) {
            updateThemeUI();
            themeToggleBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                htmlElement.classList.toggle('dark');
                localStorage.setItem('theme', htmlElement.classList.contains('dark') ? 'dark' : 'light');
                updateThemeUI();
            });
        }

        // Profile Dropdown
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

        // Toggle Activities Dropdown
        function toggleActivities(event, id) {
            event.preventDefault();
            event.stopPropagation(); // Stop navigation to /room/{id}
            const list = document.getElementById(id);
            const btn = document.getElementById('btn-' + id);
            
            list.classList.toggle('show');
            btn.classList.toggle('rotate');
        }

        // Initial rotation for open dropdowns
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.activity-list.show').forEach(list => {
                const btn = document.getElementById('btn-' + list.id);
                if (btn) btn.classList.add('rotate');
            });
        });

        // Mobile Sidebar Responsive Toggle logic
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menu-toggle');
            const sidebarClose = document.getElementById('sidebar-close');
            const sidebar = document.querySelector('.sidebar');
            const sidebarOverlay = document.getElementById('sidebar-overlay');

            if (menuToggle && sidebar && sidebarOverlay) {
                menuToggle.addEventListener('click', function() {
                    sidebar.classList.add('active');
                    sidebarOverlay.style.display = 'block';
                });

                const closeSidebarFunc = function() {
                    sidebar.classList.remove('active');
                    sidebarOverlay.style.display = 'none';
                };

                if (sidebarClose) {
                    sidebarClose.addEventListener('click', closeSidebarFunc);
                }
                sidebarOverlay.addEventListener('click', closeSidebarFunc);
            }
        });

        // Auto-detect local back button in content to hide global topbar back button
        document.addEventListener('DOMContentLoaded', function() {
            const globalBackButton = document.querySelector('.topbar a[href="javascript:history.back()"]');
            if (globalBackButton) {
                const hasLocalBackButton = Array.from(document.querySelectorAll('.main-content a, .main-content button, .main-content .btn')).some(el => {
                    // Skip the global back button itself
                    if (el === globalBackButton || el.closest('.topbar')) return false;

                    const text = el.textContent.trim().toLowerCase();
                    const hasArrowIcon = el.querySelector('.ph-arrow-left, .ph-caret-left, .ph-arrow-u-up-left');
                    const isBackLink = el.getAttribute('href') && (el.getAttribute('href').includes('back') || el.getAttribute('href') === 'javascript:history.back()');
                    
                    return (text === 'kembali' || text === 'back' || text.startsWith('kembali ') || text.includes('kembali') || hasArrowIcon || isBackLink) && 
                           (el.classList.contains('btn') || el.tagName === 'A' || el.tagName === 'BUTTON');
                });
                if (hasLocalBackButton) {
                    globalBackButton.style.setProperty('display', 'none', 'important');
                }
            }
        });

        // Sidebar scroll persistence across page loads
        document.addEventListener('DOMContentLoaded', function() {
            const navMenu = document.querySelector('.nav-menu');
            if (navMenu) {
                // Restore scroll position
                const savedScrollTop = localStorage.getItem('sidebar-scroll-top');
                if (savedScrollTop !== null) {
                    navMenu.style.scrollBehavior = 'auto';
                    navMenu.scrollTop = parseInt(savedScrollTop, 10);
                }

                // Save scroll position when scrolled
                navMenu.addEventListener('scroll', function() {
                    localStorage.setItem('sidebar-scroll-top', navMenu.scrollTop);
                });

                // Also save scroll position on click as a fallback
                navMenu.querySelectorAll('a').forEach(link => {
                    link.addEventListener('click', function() {
                        localStorage.setItem('sidebar-scroll-top', navMenu.scrollTop);
                    });
                });
            }
        });
    </script>
    @auth
    <!-- Mobile Sidebar Backdrop Overlay -->
    <div class="sidebar-overlay" id="sidebar-overlay"></div>
    @endauth
</body>
</html>