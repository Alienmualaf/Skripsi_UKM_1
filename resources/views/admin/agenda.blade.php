@extends('layouts.app')

@section('title', 'Agenda Kegiatan Master')
@section('header', 'Agenda Kegiatan Master')

@section('content')
<style>
    .agenda-card {
        background: var(--surface-color);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 1.5rem;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        display: flex;
        gap: 1.5rem;
        position: relative;
        overflow: hidden;
    }
    .agenda-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-md);
        border-color: var(--accent-color);
    }
    .agenda-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: var(--accent-color);
        opacity: 0;
        transition: opacity 0.3s;
    }
    .agenda-card:hover::before {
        opacity: 1;
    }
    .agenda-date-box {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: rgba(197, 160, 89, 0.1);
        color: #c5a059;
        border-radius: 12px;
        min-width: 75px;
        height: 75px;
        font-weight: 800;
        flex-shrink: 0;
    }
    .agenda-date-box.performance {
        background: rgba(30, 64, 175, 0.08);
        color: #1e40af;
    }
    .agenda-badge {
        font-size: 0.7rem;
        font-weight: 700;
        padding: 0.25rem 0.65rem;
        border-radius: 6px;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }
    .badge-latihan {
        background: rgba(197, 160, 89, 0.12);
        color: #c5a059;
    }
    .badge-penampilan {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }
    .badge-job {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }
    .filter-tab {
        padding: 0.6rem 1.25rem;
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 600;
        border: 1px solid var(--border-color);
        background: var(--surface-color);
        color: var(--text-secondary);
        cursor: pointer;
        transition: all 0.2s;
    }
    .filter-tab.active {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }
    .filter-tab:hover:not(.active) {
        border-color: var(--accent-color);
        color: var(--text-primary);
    }

    /* View Mode Styles */
    .view-btn {
        background: transparent;
        color: var(--text-secondary);
        border: 1px solid transparent;
        padding: 0.5rem 0.85rem;
        border-radius: 8px;
        font-size: 0.825rem;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.35rem;
        transition: all 0.2s ease;
    }
    .view-btn:hover {
        color: var(--text-primary);
    }
    .view-btn.active {
        background: var(--surface-color);
        color: var(--primary-color);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
    }

    /* Grid layout mode (Extra Large Icon) */
    .agenda-grid-mode {
        display: grid !important;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)) !important;
        gap: 1.5rem !important;
    }
    
    .agenda-grid-mode .agenda-item-wrapper {
        margin: 0 !important;
    }
    
    .agenda-grid-mode .agenda-card {
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 2.25rem 1.5rem;
        height: 100%;
        gap: 1.5rem;
        justify-content: flex-start;
        border-radius: 20px;
    }
    
    .agenda-grid-mode .agenda-card::before {
        left: 0;
        right: 0;
        top: 0;
        bottom: auto;
        height: 4px;
        width: auto;
    }
    
    .agenda-grid-mode .agenda-date-box {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
        box-shadow: 0 6px 16px rgba(197, 160, 89, 0.15);
        border: 2px solid rgba(197, 160, 89, 0.25);
        transition: all 0.3s ease;
    }
    
    .agenda-grid-mode .agenda-card:hover .agenda-date-box {
        transform: scale(1.08);
        box-shadow: 0 8px 20px rgba(197, 160, 89, 0.25);
    }
    
    .agenda-grid-mode .agenda-date-box.performance {
        box-shadow: 0 6px 16px rgba(30, 64, 175, 0.1);
        border-color: rgba(30, 64, 175, 0.2);
        color: #1e40af;
        background: rgba(30, 64, 175, 0.08);
    }
    
    .agenda-grid-mode .agenda-card:hover .agenda-date-box.performance {
        box-shadow: 0 8px 20px rgba(30, 64, 175, 0.2);
    }
    
    .agenda-grid-mode .agenda-date-box span:first-child {
        font-size: 2rem !important;
        font-weight: 800;
        line-height: 1;
    }
    
    .agenda-grid-mode .agenda-date-box span:nth-child(2) {
        font-size: 0.9rem !important;
        font-weight: 700;
        text-transform: uppercase;
        margin-top: 0.15rem;
    }
    
    .agenda-grid-mode .agenda-date-box span:last-child {
        font-size: 0.75rem !important;
        opacity: 0.8;
        margin-top: 0.15rem;
    }
    
    .agenda-grid-mode .agenda-details {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.75rem;
        width: 100%;
    }
    
    .agenda-grid-mode .agenda-badge {
        margin: 0 auto;
        padding: 0.35rem 0.85rem;
        font-size: 0.75rem;
    }
    
    .agenda-grid-mode .agenda-title {
        font-size: 1.25rem !important;
        margin: 0.25rem 0 0.5rem 0 !important;
        line-height: 1.4;
    }
    
    .agenda-grid-mode .agenda-meta-container {
        justify-content: center !important;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem !important;
    }
    
    .agenda-grid-mode .agenda-notes {
        width: 100%;
        margin-top: 0.75rem !important;
        text-align: left;
    }
    
    .agenda-grid-mode .agenda-action-container {
        width: 100%;
        margin-top: auto;
        padding-top: 1rem;
        align-self: stretch;
    }
    
    .agenda-grid-mode .agenda-action-container a {
        width: 100%;
        justify-content: center;
    }
    
    /* List layout mode */
    .agenda-list-mode {
        display: flex !important;
        flex-direction: column !important;
        gap: 1rem !important;
    }
    
    .agenda-list-mode .agenda-card {
        display: flex;
        flex-direction: row;
        align-items: flex-start;
    }
</style>

<div style="display: flex; flex-direction: column; gap: 1.5rem;">
    <!-- Top Control Dashboard Card -->
    <div class="card" style="padding: 1.5rem; border-radius: 16px; border: 1px solid var(--border-color); background: var(--surface-color); display: flex; flex-direction: column; gap: 1.25rem; box-shadow: var(--shadow-sm);">
        
        <!-- Header & Action Buttons Row -->
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 1rem;">
            <div>
                <h1 style="margin: 0; font-size: 1.35rem; font-weight: 800; color: var(--text-primary); font-family: 'Plus Jakarta Sans', sans-serif; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="ph ph-calendar" style="color: var(--accent-color);"></i> Agenda Kegiatan
                </h1>
                <p style="margin: 0.25rem 0 0 0; color: var(--text-secondary); font-size: 0.85rem;">Pantau seluruh jadwal latihan, program kerja, dan penampilan.</p>
            </div>
            
            <!-- Quick Management Buttons -->
            <div style="display: flex; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                <a href="{{ route('admin.monitor.agendas') }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 0.35rem; text-decoration: none; padding: 0.55rem 1rem; border-radius: 8px; font-size: 0.825rem; font-weight: 700;">
                    <i class="ph ph-squares-four"></i> Monitor Agenda & Proker
                </a>
            </div>
        </div>

        <!-- Filter, Search, Stats & View switcher Row -->
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <!-- Left Side: Search & Filter Select -->
            <div style="display: flex; align-items: center; flex-wrap: wrap; gap: 0.75rem; flex-grow: 1;">
                <!-- Compact Search -->
                <div style="position: relative; min-width: 200px; flex-grow: 1; max-width: 300px;">
                    <input type="text" id="agendaSearch" onkeyup="searchAgenda()" placeholder="Cari nama agenda..." style="width: 100%; padding: 0.5rem 2.25rem 0.5rem 0.85rem; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-color); color: var(--text-primary); font-size: 0.825rem; outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='var(--accent-color)'" onblur="this.style.borderColor='var(--border-color)'">
                    <i class="ph ph-magnifying-glass" style="position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); color: var(--text-secondary); font-size: 1rem;"></i>
                </div>

                <!-- Filter Select Dropdown -->
                <div style="position: relative;">
                    <select id="agendaFilterSelect" onchange="filterAgendaSelect(this.value)" style="padding: 0.5rem 2.25rem 0.5rem 0.85rem; border-radius: 8px; border: 1px solid var(--border-color); background: var(--surface-color); color: var(--text-primary); font-size: 0.825rem; font-weight: 600; cursor: pointer; outline: none; -webkit-appearance: none; -moz-appearance: none; appearance: none;">
                        <option value="all">Semua Tipe Agenda</option>
                        <option value="latihan">Hanya Latihan</option>
                        <option value="penampilan">Penampilan & Job</option>
                        <option value="proker">Program Kerja (Proker)</option>
                    </select>
                    <i class="ph ph-caret-down" style="position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); color: var(--text-secondary); pointer-events: none; font-size: 0.85rem;"></i>
                </div>
            </div>

            <!-- Middle: Inline Stats Chips -->
            <div class="agenda-quick-stats" style="display: flex; align-items: center; flex-wrap: wrap; gap: 0.5rem; font-size: 0.8rem; font-weight: 700;">
                <span style="color: var(--text-secondary); padding: 0.15rem 0.35rem; font-weight: 600;">Ringkasan:</span>
                <span style="background: var(--bg-color); color: var(--text-primary); padding: 0.25rem 0.6rem; border-radius: 6px; border: 1px solid var(--border-color);">Total: <strong style="color: var(--accent-color);">{{ count($agendas) }}</strong></span>
                <span style="background: var(--bg-color); color: var(--text-primary); padding: 0.25rem 0.6rem; border-radius: 6px; border: 1px solid var(--border-color);">Latihan: <strong style="color: #c5a059;">{{ count($agendas->where('agenda_type', 'Latihan')) }}</strong></span>
                <span style="background: var(--bg-color); color: var(--text-primary); padding: 0.25rem 0.6rem; border-radius: 6px; border: 1px solid var(--border-color);">Show/Job: <strong style="color: #3b82f6;">{{ count($agendas->where('agenda_type', '!=', 'Latihan')) }}</strong></span>
            </div>

            <!-- Right Side: View Mode Toggles -->
            <div style="display: flex; align-items: center; gap: 0.25rem; background: var(--bg-color); padding: 0.2rem; border-radius: 8px; border: 1px solid var(--border-color);">
                <button id="btnListView" onclick="changeView('list')" class="view-btn" style="padding: 0.4rem 0.75rem; border-radius: 6px;">
                    <i class="ph ph-list"></i>
                </button>
                <button id="btnGridView" onclick="changeView('grid')" class="view-btn" style="padding: 0.4rem 0.75rem; border-radius: 6px;">
                    <i class="ph ph-squares-four"></i>
                </button>
            </div>
        </div>

    </div>

    <div style="display: flex; flex-direction: column; gap: 1rem;" id="agendaContainer">
            @forelse($agendas as $agenda)
                @php
                    $isLatihan = $agenda->agenda_type === 'Latihan';
                    $isPenampilanOrJob = $agenda->agenda_type === 'Penampilan' || $agenda->agenda_type === 'Job';
                    $typeAttr = $isLatihan ? 'latihan' : ($isPenampilanOrJob ? 'penampilan' : 'proker');
                    $agendaDate = $agenda->date ? strtotime($agenda->date) : null;
                    $day = $agendaDate ? date('d', $agendaDate) : '-';
                    $month = $agendaDate ? date('M', $agendaDate) : 'N/A';
                    $year = $agendaDate ? date('Y', $agendaDate) : '';
                    $searchData = strtolower($agenda->title . ' ' . $agenda->location . ' ' . ($agenda->notes ?? $agenda->description ?? ''));
                @endphp
                <div class="agenda-item-wrapper" data-type="{{ $typeAttr }}" data-search="{{ $searchData }}">
                    <div class="agenda-card">
                        <div class="agenda-date-box {{ !$isLatihan ? 'performance' : '' }}">
                            <span style="font-size: 1.5rem; line-height: 1;">{{ $day }}</span>
                            <span style="font-size: 0.8rem; text-transform: uppercase; margin-top: 0.15rem;">{{ $month }}</span>
                            <span style="font-size: 0.65rem; opacity: 0.8; margin-top: 0.15rem;">{{ $year }}</span>
                        </div>
                        
                        <div class="agenda-details" style="flex: 1; display: flex; flex-direction: column; gap: 0.5rem; min-width: 0;">
                            <div>
                                @if($isLatihan)
                                    <span class="agenda-badge badge-latihan"><i class="ph ph-chalkboard"></i> Latihan</span>
                                @elseif($agenda->agenda_type === 'Penampilan')
                                    <span class="agenda-badge badge-penampilan"><i class="ph ph-microphone-stage"></i> Penampilan</span>
                                @elseif($agenda->agenda_type === 'Job')
                                    <span class="agenda-badge badge-job"><i class="ph ph-briefcase"></i> Job Penampilan</span>
                                @elseif($agenda->agenda_type === 'Event')
                                    <span class="agenda-badge" style="background: rgba(14, 165, 233, 0.1); color: #0ea5e9; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; padding: 0.25rem 0.65rem; border-radius: 6px;"><i class="ph ph-calendar-star"></i> Event</span>
                                @elseif($agenda->agenda_type === 'Competition')
                                    <span class="agenda-badge" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; padding: 0.25rem 0.65rem; border-radius: 6px;"><i class="ph ph-trophy"></i> Kompetisi</span>
                                @else
                                    <span class="agenda-badge" style="background: rgba(99, 102, 241, 0.1); color: #6366f1; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; padding: 0.25rem 0.65rem; border-radius: 6px;"><i class="ph ph-target"></i> {{ $agenda->agenda_type }}</span>
                                @endif
                            </div>
                            
                            <h3 class="agenda-title" style="margin: 0; font-size: 1.15rem; font-weight: 800; color: var(--text-primary); font-family: 'Plus Jakarta Sans', sans-serif;">{{ $agenda->title }}</h3>
                            
                            <div class="agenda-meta-container" style="display: flex; flex-wrap: wrap; gap: 1rem; font-size: 0.825rem; color: var(--text-secondary); margin-top: 0.25rem;">
                                <span style="display: flex; align-items: center; gap: 0.35rem;">
                                    <i class="ph ph-clock" style="color: var(--accent-color); font-size: 1rem;"></i> {{ $agenda->time_display }}
                                </span>
                                <span style="display: flex; align-items: center; gap: 0.35rem;">
                                    <i class="ph ph-map-pin" style="color: var(--accent-color); font-size: 1rem;"></i> {{ $agenda->location ?? 'Belum ditentukan' }}
                                </span>
                                @if($isLatihan && $agenda->classroom)
                                    <span style="display: flex; align-items: center; gap: 0.35rem;">
                                        <i class="ph ph-graduation-cap" style="color: var(--accent-color); font-size: 1rem;"></i> {{ $agenda->classroom->name }}
                                    </span>
                                @endif
                            </div>
                            
                            @if($agenda->notes ?? $agenda->description)
                                <p class="agenda-notes" style="margin: 0.5rem 0 0 0; font-size: 0.85rem; color: var(--text-secondary); line-height: 1.5; background: var(--bg-color); padding: 0.75rem 1rem; border-radius: 10px; border-left: 3px solid var(--border-color);">
                                    {{ $agenda->notes ?? $agenda->description }}
                                </p>
                            @endif
                        </div>
                        
                        <div class="agenda-action-container" style="align-self: center; flex-shrink: 0;">
                            @if($agenda->link && $agenda->link !== '#')
                                <a href="{{ $agenda->link }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none; padding: 0.55rem 1.1rem; font-size: 0.825rem; border-radius: 10px; font-weight: 700; white-space: nowrap;">
                                    {{ $isLatihan || $isPenampilanOrJob ? 'Lihat Kelas' : 'Lihat Detail' }} <i class="ph ph-caret-right" style="font-weight: bold;"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="card" style="padding: 3rem; text-align: center; border-radius: 16px; border: 1px solid var(--border-color); background: var(--surface-color);">
                    <i class="ph ph-calendar-x" style="font-size: 3.5rem; color: var(--text-muted); margin-bottom: 1rem;"></i>
                    <h3 style="margin: 0 0 0.5rem 0; font-size: 1.25rem; font-weight: 800; color: var(--text-primary); font-family: 'Georgia', serif;">Belum Ada Agenda</h3>
                    <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem;">Saat ini belum ada jadwal latihan atau penampilan mendatang.</p>
                </div>
            @endforelse
        </div>
    </div>

<script>
    function filterAgendaSelect(type) {
        const items = document.querySelectorAll('.agenda-item-wrapper');
        items.forEach(item => {
            const itemType = item.getAttribute('data-type');
            if (type === 'all' || itemType === type) {
                item.dataset.filteredType = 'show';
            } else {
                item.dataset.filteredType = 'hide';
            }
            applyFilters(item);
        });
    }

    function changeView(mode) {
        const container = document.getElementById('agendaContainer');
        const btnList = document.getElementById('btnListView');
        const btnGrid = document.getElementById('btnGridView');
        
        if (mode === 'grid') {
            container.classList.remove('agenda-list-mode');
            container.classList.add('agenda-grid-mode');
            btnGrid.classList.add('active');
            btnList.classList.remove('active');
            localStorage.setItem('agenda-view-mode', 'grid');
        } else {
            container.classList.remove('agenda-grid-mode');
            container.classList.add('agenda-list-mode');
            btnList.classList.add('active');
            btnGrid.classList.remove('active');
            localStorage.setItem('agenda-view-mode', 'list');
        }
        
        // Re-apply filters to update margins correctly
        document.querySelectorAll('.agenda-item-wrapper').forEach(applyFilters);
    }

    function filterAgenda(type, button) {
        // Toggle active tabs
        document.querySelectorAll('.filter-tab').forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');

        // Filter items
        const items = document.querySelectorAll('.agenda-item-wrapper');
        
        items.forEach(item => {
            const itemType = item.getAttribute('data-type');
            if (type === 'all' || itemType === type) {
                item.dataset.filteredType = 'show';
            } else {
                item.dataset.filteredType = 'hide';
            }
            applyFilters(item);
        });
    }

    // Search function
    function searchAgenda() {
        const query = document.getElementById('agendaSearch').value.toLowerCase();
        const items = document.querySelectorAll('.agenda-item-wrapper');
        
        items.forEach(item => {
            const searchContent = item.getAttribute('data-search');
            if (searchContent.includes(query)) {
                item.dataset.filteredSearch = 'show';
            } else {
                item.dataset.filteredSearch = 'hide';
            }
            applyFilters(item);
        });
    }

    function applyFilters(item) {
        const typeShow = item.dataset.filteredType !== 'hide';
        const searchShow = item.dataset.filteredSearch !== 'hide';
        
        if (typeShow && searchShow) {
            item.style.display = 'block';
            
            const container = document.getElementById('agendaContainer');
            if (container && container.classList.contains('agenda-list-mode')) {
                item.style.margin = '0 0 1rem 0';
            } else {
                item.style.margin = '0';
            }
        } else {
            item.style.display = 'none';
        }
    }

    // Initialize view mode
    document.addEventListener('DOMContentLoaded', function() {
        const savedMode = localStorage.getItem('agenda-view-mode') || 'grid';
        changeView(savedMode);
    });
</script>
@endsection
