@extends('layouts.app')

@section('title', 'Feed UKM')
@section('header', 'Feed Aktivitas UKM')

@section('content')
<style>
    .feed-layout {
        display: grid;
        grid-template-columns: 2.2fr 1fr;
        gap: 2rem;
        align-items: start;
        max-width: 1200px;
        margin: 0 auto;
    }

    /* Left Column Styling */
    .feed-main {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    /* Right Column Styling */
    .feed-sidebar {
        position: sticky;
        top: 2rem;
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    /* Header Banner styling */
    .feed-header-banner {
        background: linear-gradient(135deg, var(--accent-color) 0%, #3b82f6 100%);
        border-radius: 20px;
        padding: 2rem;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-md);
        margin-bottom: 0.5rem;
    }

    .feed-header-banner::after {
        content: '';
        position: absolute;
        right: -30px;
        top: -30px;
        width: 180px;
        height: 180px;
        background: rgba(255,255,255,0.06);
        border-radius: 50%;
        filter: blur(20px);
    }

    /* Filter Bar inside the feed view */
    .feed-filter-bar {
        background: var(--surface-color);
        border: 1px solid var(--border-color);
        padding: 1rem 1.25rem;
        border-radius: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
        box-shadow: var(--shadow-sm);
    }

    .feed-filters-left {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .feed-filter-btn {
        background: var(--bg-color);
        border: 1px solid var(--border-color);
        color: var(--text-secondary);
        padding: 0.5rem 1rem;
        border-radius: 30px;
        font-size: 0.8rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.35rem;
        text-decoration: none;
    }

    .feed-filter-btn:hover {
        background: var(--border-color);
        color: var(--text-primary);
    }

    .feed-filter-btn.active {
        background: var(--accent-color);
        color: white;
        border-color: var(--accent-color);
        box-shadow: 0 4px 10px rgba(30, 64, 175, 0.2);
    }

    .feed-filter-select-wrapper {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .feed-filter-select {
        padding: 0.45rem 1rem;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        background: var(--surface-color);
        color: var(--text-primary);
        font-size: 0.8125rem;
        font-weight: 700;
        outline: none;
        cursor: pointer;
        transition: border-color 0.2s;
    }

    .feed-filter-select:focus {
        border-color: var(--accent-color);
    }

    /* Social Card Styling */
    .social-card {
        background: var(--surface-color);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        padding: 1.5rem;
        box-shadow: var(--shadow-sm);
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        position: relative;
    }

    .social-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 6px;
        height: 100%;
        border-radius: 20px 0 0 20px;
    }

    .social-card.announcement::before {
        background: var(--accent-color);
    }

    .social-card.event::before {
        background: var(--success-color);
    }

    .social-card.gallery::before {
        background: var(--warning-color);
    }

    .social-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md), 0 12px 30px rgba(0,0,0,0.02);
    }

    .social-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1.25rem;
    }

    .social-poster {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .social-poster-avatar {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: var(--bg-color);
        border: 1.5px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        color: var(--accent-color);
        overflow: hidden;
        flex-shrink: 0;
    }

    .social-poster-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .social-ukm-name {
        font-size: 0.95rem;
        font-weight: 800;
        color: var(--text-primary);
        line-height: 1.2;
    }

    .social-post-meta {
        font-size: 0.75rem;
        color: var(--text-secondary);
        margin-top: 0.15rem;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .social-badge {
        font-size: 0.65rem;
        font-weight: 800;
        padding: 0.25rem 0.65rem;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    .social-badge.announcement {
        background: rgba(30, 64, 175, 0.06);
        color: var(--accent-color);
        border: 1px solid rgba(30, 64, 175, 0.15);
    }

    .social-badge.event {
        background: rgba(16, 185, 129, 0.06);
        color: var(--success-color);
        border: 1px solid rgba(16, 185, 129, 0.15);
    }

    .social-badge.gallery {
        background: rgba(245, 158, 11, 0.06);
        color: var(--warning-color);
        border: 1px solid rgba(245, 158, 11, 0.15);
    }

    .social-title {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--text-primary);
        margin: 0 0 0.75rem 0;
        line-height: 1.4;
        letter-spacing: -0.01em;
    }

    .social-body {
        font-size: 0.9rem;
        color: var(--text-secondary);
        line-height: 1.6;
        white-space: pre-line;
    }

    .social-image-container {
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid var(--border-color);
        margin-top: 1rem;
        max-height: 380px;
        background: var(--bg-color);
    }

    .social-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s;
    }

    .social-card:hover .social-image {
        transform: scale(1.02);
    }

    /* Event visual details */
    .event-info-box {
        background: var(--bg-color);
        border: 1px solid var(--border-color);
        border-radius: 14px;
        padding: 1rem;
        margin-top: 1rem;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
    }

    .event-info-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.8rem;
        color: var(--text-primary);
        font-weight: 700;
    }

    .event-info-item i {
        font-size: 1.15rem;
        color: var(--success-color);
        background: rgba(16, 185, 129, 0.1);
        padding: 0.35rem;
        border-radius: 8px;
    }

    /* Footer interaction row */
    .social-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 1.25rem;
        padding-top: 0.75rem;
        border-top: 1px solid var(--border-color);
    }

    .social-footer-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .social-interaction-btn {
        background: transparent;
        border: none;
        color: var(--text-secondary);
        font-size: 0.75rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.25rem;
        cursor: pointer;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        transition: all 0.2s;
    }

    .social-interaction-btn:hover {
        background: var(--bg-color);
        color: var(--accent-color);
    }

    /* Sidebar Student Info Widget */
    .student-widget {
        background: var(--surface-color);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        padding: 1.5rem;
        box-shadow: var(--shadow-sm);
        text-align: center;
        position: relative;
    }

    .student-widget-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 75px;
        background: linear-gradient(135deg, rgba(30,64,175,0.05) 0%, rgba(59,130,246,0.05) 100%);
        border-radius: 20px 20px 0 0;
        border-bottom: 1px solid var(--border-color);
    }

    .student-widget-avatar {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        border: 3px solid var(--surface-color);
        background: var(--accent-color);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 20px auto 0.75rem;
        position: relative;
        z-index: 1;
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .student-widget-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .student-widget-name {
        font-weight: 800;
        font-size: 1.05rem;
        color: var(--text-primary);
        margin: 0;
    }

    .student-widget-role {
        font-size: 0.7rem;
        font-weight: 800;
        color: var(--accent-color);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-top: 0.25rem;
        background: rgba(30,64,175,0.06);
        display: inline-block;
        padding: 0.15rem 0.5rem;
        border-radius: 10px;
    }

    /* Followed UKM widget inside sidebar */
    .ukm-widget {
        background: var(--surface-color);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        padding: 1.5rem;
        box-shadow: var(--shadow-sm);
    }

    .ukm-widget-title {
        font-size: 0.9rem;
        font-weight: 800;
        color: var(--text-primary);
        margin: 0 0 1rem 0;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .ukm-widget-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .ukm-widget-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.5rem;
        border-radius: 12px;
        transition: background 0.2s;
    }

    .ukm-widget-item:hover {
        background: var(--bg-color);
    }

    .ukm-widget-item-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .ukm-widget-logo {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: var(--bg-color);
        border: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
        overflow: hidden;
        flex-shrink: 0;
    }

    .ukm-widget-logo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .ukm-widget-name {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    @media (max-width: 992px) {
        .feed-layout {
            grid-template-columns: 1fr;
        }
        .feed-sidebar {
            position: static;
        }
    }
</style>

<div class="feed-layout">
    <!-- Main Feed Column -->
    <div class="feed-main">
        <!-- Banner -->
        <div class="feed-header-banner">
            <h3 style="margin: 0 0 0.25rem 0; font-size: 1.5rem; font-weight: 800; display: flex; align-items: center; gap: 0.5rem;">
                <i class="ph ph-newspaper"></i> Feed UKM Saya
            </h3>
            <p style="margin: 0; font-size: 0.875rem; opacity: 0.9; font-weight: 500;">
                Melihat update pengumuman, agenda latihan, dan galeri foto terbaru khusus dari UKM yang Anda ikuti.
            </p>
        </div>

        <!-- Filter Bar -->
        <div class="feed-filter-bar">
            <!-- Left Tabs (Types) -->
            <div class="feed-filters-left">
                <a href="?type=all&ukm_id={{ $selectedUkmId }}" class="feed-filter-btn {{ $filterType === 'all' || !$filterType ? 'active' : '' }}">
                    <i class="ph ph-grid-four"></i> Semua
                </a>
                <a href="?type=announcement&ukm_id={{ $selectedUkmId }}" class="feed-filter-btn {{ $filterType === 'announcement' ? 'active' : '' }}">
                    <i class="ph ph-megaphone"></i> Pengumuman
                </a>
                <a href="?type=event&ukm_id={{ $selectedUkmId }}" class="feed-filter-btn {{ $filterType === 'event' ? 'active' : '' }}">
                    <i class="ph ph-calendar"></i> Agenda
                </a>
                <a href="?type=gallery&ukm_id={{ $selectedUkmId }}" class="feed-filter-btn {{ $filterType === 'gallery' ? 'active' : '' }}">
                    <i class="ph ph-image"></i> Galeri
                </a>
            </div>

            <!-- Right Filter (UKM Selector) -->
            <div class="feed-filter-select-wrapper">
                <i class="ph ph-funnel" style="font-size: 0.95rem; color: var(--text-secondary);"></i>
                <select class="feed-filter-select" onchange="window.location.href = updateQueryString('ukm_id', this.value)">
                    <option value="">Semua UKM Saya</option>
                    @foreach($myUkms as $u)
                        <option value="{{ $u->id }}" {{ $selectedUkmId == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Timeline Feed Items -->
        <div style="display: flex; flex-direction: column; gap: 1.25rem;">
            @foreach($feedItems as $item)
                @if($item->feed_type === 'announcement')
                    <!-- Announcement Card -->
                    <div class="social-card announcement">
                        <div class="social-header">
                            <div class="social-poster">
                                <div class="social-poster-avatar">
                                    @if($item->ukm->logo)
                                        <img src="{{ filter_var($item->ukm->logo, FILTER_VALIDATE_URL) ? $item->ukm->logo : asset('storage/' . $item->ukm->logo) }}">
                                    @else
                                        {{ substr($item->ukm->name, 0, 2) }}
                                    @endif
                                </div>
                                <div>
                                    <div class="social-ukm-name">{{ $item->ukm->name }}</div>
                                    <div class="social-post-meta">
                                        <span>{{ $item->created_at ? $item->created_at->diffForHumans() : '-' }}</span>
                                        <span>•</span>
                                        <span>Oleh {{ $item->creator->name ?? 'Admin UKM' }}</span>
                                    </div>
                                </div>
                            </div>
                            <span class="social-badge announcement">
                                <i class="ph-fill ph-megaphone"></i> Pengumuman
                            </span>
                        </div>

                        <h4 class="social-title">{{ $item->title }}</h4>
                        <div class="social-body">{{ $item->content }}</div>

                        <div class="social-footer">
                            <div class="social-footer-left">
                                <button class="social-interaction-btn" onclick="alert('Anda menyukai postingan ini!')">
                                    <i class="ph ph-heart"></i> Like
                                </button>
                                <button class="social-interaction-btn" onclick="alert('Fitur komentar segera hadir!')">
                                    <i class="ph ph-chat-circle"></i> Comment
                                </button>
                            </div>
                            <a href="/room/{{ $item->ukm_id }}/classroom" style="font-size: 0.75rem; font-weight: 800; color: var(--accent-color); text-decoration: none; display: flex; align-items: center; gap: 0.25rem;">
                                Buka Kelas <i class="ph ph-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                @elseif($item->feed_type === 'event')
                    <!-- Event Card -->
                    <div class="social-card event">
                        <div class="social-header">
                            <div class="social-poster">
                                <div class="social-poster-avatar">
                                    @if($item->ukm->logo)
                                        <img src="{{ filter_var($item->ukm->logo, FILTER_VALIDATE_URL) ? $item->ukm->logo : asset('storage/' . $item->ukm->logo) }}">
                                    @else
                                        {{ substr($item->ukm->name, 0, 2) }}
                                    @endif
                                </div>
                                <div>
                                    <div class="social-ukm-name">{{ $item->ukm->name }}</div>
                                    <div class="social-post-meta">
                                        <span>Agenda Kegiatan</span>
                                        <span>•</span>
                                        <span>{{ $item->created_at ? $item->created_at->diffForHumans() : '-' }}</span>
                                    </div>
                                </div>
                            </div>
                            <span class="social-badge event">
                                <i class="ph-fill ph-calendar"></i> Agenda
                            </span>
                        </div>

                        <h4 class="social-title">{{ $item->title }}</h4>
                        <div class="social-body">{{ $item->description }}</div>

                        <div class="event-info-box">
                            <div class="event-info-item">
                                <i class="ph ph-calendar-blank"></i>
                                <span>{{ $item->start_date ? \Carbon\Carbon::parse($item->start_date)->format('d M Y') : '-' }}</span>
                            </div>
                            @if($item->location)
                                <div class="event-info-item">
                                    <i class="ph ph-map-pin"></i>
                                    <span>{{ $item->location }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="social-footer">
                            <div class="social-footer-left">
                                <button class="social-interaction-btn" onclick="alert('Anda menyukai postingan ini!')">
                                    <i class="ph ph-heart"></i> Like
                                </button>
                            </div>
                            <a href="/room/{{ $item->ukm_id }}/classroom" style="font-size: 0.75rem; font-weight: 800; color: var(--success-color); text-decoration: none; display: flex; align-items: center; gap: 0.25rem;">
                                Detail Agenda <i class="ph ph-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                @elseif($item->feed_type === 'gallery')
                    <!-- Gallery Card -->
                    <div class="social-card gallery">
                        <div class="social-header">
                            <div class="social-poster">
                                <div class="social-poster-avatar">
                                    @if($item->ukm->logo)
                                        <img src="{{ filter_var($item->ukm->logo, FILTER_VALIDATE_URL) ? $item->ukm->logo : asset('storage/' . $item->ukm->logo) }}">
                                    @else
                                        {{ substr($item->ukm->name, 0, 2) }}
                                    @endif
                                </div>
                                <div>
                                    <div class="social-ukm-name">{{ $item->ukm->name }}</div>
                                    <div class="social-post-meta">
                                        <span>Dokumentasi Foto</span>
                                        <span>•</span>
                                        <span>{{ $item->created_at ? $item->created_at->diffForHumans() : '-' }}</span>
                                    </div>
                                </div>
                            </div>
                            <span class="social-badge gallery">
                                <i class="ph-fill ph-image"></i> Galeri
                            </span>
                        </div>

                        <h4 class="social-title">{{ $item->title }}</h4>
                        
                        @if($item->file_path)
                            <div class="social-image-container">
                                <img src="{{ filter_var($item->file_path, FILTER_VALIDATE_URL) ? $item->file_path : asset('storage/' . $item->file_path) }}" class="social-image" alt="{{ $item->title }}">
                            </div>
                        @endif

                        <div class="social-footer">
                            <div class="social-footer-left">
                                <button class="social-interaction-btn" onclick="alert('Anda menyukai postingan ini!')">
                                    <i class="ph ph-heart"></i> Like
                                </button>
                                <button class="social-interaction-btn" onclick="alert('Fitur komentar segera hadir!')">
                                    <i class="ph ph-chat-circle"></i> Comment
                                </button>
                            </div>
                            <span style="font-size: 0.725rem; color: var(--text-secondary); font-weight: 500;">
                                Oleh {{ $item->creator->name ?? 'Pengurus' }}
                            </span>
                        </div>
                    </div>
                @endif
            @endforeach

            @if($feedItems->isEmpty())
                <!-- Empty State -->
                <div style="text-align: center; padding: 4.5rem 2rem; background: var(--surface-color); border: 1px dashed var(--border-color); border-radius: 20px; box-shadow: var(--shadow-sm);">
                    <i class="ph-bold ph-newspaper" style="font-size: 4rem; color: var(--text-secondary); opacity: 0.25; margin-bottom: 1.25rem; display: block;"></i>
                    <h4 style="font-weight: 800; color: var(--text-primary); margin-bottom: 0.5rem; font-size: 1.15rem;">Belum ada postingan feed</h4>
                    <p style="color: var(--text-secondary); font-size: 0.85rem; max-width: 420px; margin: 0 auto; line-height: 1.6;">
                        UKM yang Anda ikuti belum mengunggah pengumuman, agenda kegiatan, atau dokumentasi galeri terbaru.
                    </p>
                    <a href="/member/join" class="btn btn-primary" style="margin-top: 1.25rem; padding: 0.5rem 1.5rem; font-size: 0.85rem; font-weight: 700; border-radius: 10px;">
                        Cari & Join UKM
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Sidebar Widget Column -->
    <div class="feed-sidebar">
        <!-- User Profile Widget -->
        <div class="student-widget">
            <div class="student-widget-bg"></div>
            <div class="student-widget-avatar">
                @if(auth()->user()->photo)
                    <img src="{{ filter_var(auth()->user()->photo, FILTER_VALIDATE_URL) ? auth()->user()->photo : asset('storage/' . auth()->user()->photo) }}">
                @else
                    <span style="font-size: 1.5rem; font-weight: 800; color: white;">
                        {{ substr(auth()->user()->name, 0, 2) }}
                    </span>
                @endif
            </div>
            <h4 class="student-widget-name">{{ auth()->user()->name }}</h4>
            <span class="student-widget-role">Mahasiswa</span>

            <div style="margin-top: 1.25rem; padding-top: 1rem; border-top: 1px solid var(--border-color); display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem;">
                <div>
                    <div style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary);">{{ $myUkms->count() }}</div>
                    <div style="font-size: 0.65rem; color: var(--text-secondary); font-weight: 700; text-transform: uppercase;">UKM Diikuti</div>
                </div>
                <div>
                    <div style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary);">{{ auth()->user()->attendances()->where('status', 'present')->count() }}</div>
                    <div style="font-size: 0.65rem; color: var(--text-secondary); font-weight: 700; text-transform: uppercase;">Hadir Sesi</div>
                </div>
            </div>
        </div>

        <!-- Followed UKM quick selector list -->
        <div class="ukm-widget">
            <h4 class="ukm-widget-title">
                <i class="ph ph-tent" style="color: var(--accent-color);"></i> UKM Saya
            </h4>
            <div class="ukm-widget-list">
                @foreach($myUkms as $u)
                    <div class="ukm-widget-item">
                        <div class="ukm-widget-item-info">
                            <div class="ukm-widget-logo">
                                @if($u->logo)
                                    <img src="{{ filter_var($u->logo, FILTER_VALIDATE_URL) ? $u->logo : asset('storage/' . $u->logo) }}">
                                @else
                                    <i class="ph ph-buildings" style="font-size: 1rem; color: var(--accent-color);"></i>
                                @endif
                            </div>
                            <span class="ukm-widget-name">{{ $u->name }}</span>
                        </div>
                        <a href="/room/{{ $u->id }}/classroom" class="btn" style="padding: 0.35rem 0.65rem; font-size: 0.725rem; font-weight: 700; border-radius: 8px; background: var(--bg-color); border: 1px solid var(--border-color); text-decoration: none;">
                            Masuk
                        </a>
                    </div>
                @endforeach

                @if($myUkms->isEmpty())
                    <div style="text-align: center; color: var(--text-secondary); font-size: 0.8rem; padding: 1rem 0;">
                        Belum mengikuti UKM.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    function updateQueryString(key, value) {
        let url = new URL(window.location.href);
        if (value) {
            url.searchParams.set(key, value);
        } else {
            url.searchParams.delete(key);
        }
        return url.toString();
    }
</script>
@endsection
