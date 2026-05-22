@extends('layouts.app')

@section('title', 'Agenda & Jadwal')
@section('header', 'Kalender Kegiatan UKM')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h3 class="text-lg font-bold" style="font-family: 'Outfit', sans-serif; font-weight: 800; color: var(--text-primary);">Kalender Kegiatan</h3>
        <p class="text-secondary text-sm">Visualisasi seluruh agenda dan jadwal UKM.</p>
    </div>
    @if(session()->has('managed_ukm_id'))
        <a href="/ukm/events?create=true" class="btn btn-primary" style="font-weight: 700; border-radius: var(--radius-md); display: inline-flex; align-items: center; gap: 0.35rem;">
            <i class="ph ph-plus-circle"></i> Tambah Kegiatan
        </a>
    @endif
</div>

<div class="animate-fade-in" style="display: grid; grid-template-columns: 300px 1fr; gap: 2rem; align-items: start; margin-top: 1.5rem;">
    <!-- LEFT SIDEBAR COLUMN: Upcoming Activities -->
    <div style="display: flex; flex-direction: column; gap: 1.25rem;">
        <div class="card" style="margin-bottom: 0; padding: 1.5rem; border-top: 3px solid var(--warning-color);">
            <h4 style="font-weight: 800; margin: 0 0 1.25rem 0; font-size: 1rem; color: var(--text-primary); font-family: 'Outfit', sans-serif;">
                <i class="ph ph-bell" style="color: var(--warning-color); font-size: 1.25rem; vertical-align: middle; margin-right: 0.25rem;"></i>
                Kegiatan Terdekat
            </h4>
            
            <div id="upcomingEventsList" style="display: flex; flex-direction: column; gap: 0.75rem;">
                <!-- Loaded via JS dynamically -->
            </div>
        </div>

        <div class="card" style="margin-bottom: 0; padding: 1.25rem; background: var(--accent-light); border: 1px solid rgba(30, 64, 175, 0.1); color: var(--accent-color); border-radius: var(--radius-md);">
            <div style="display: flex; gap: 0.65rem; align-items: flex-start;">
                <i class="ph ph-info" style="font-size: 1.35rem; flex-shrink: 0; margin-top: 2px;"></i>
                <div style="font-size: 0.8rem; line-height: 1.45;">
                    <strong style="font-family: 'Outfit', sans-serif;">Petunjuk Kalender:</strong>
                    <p style="margin: 0.25rem 0 0 0; opacity: 0.9;">Klik pada tanggal atau bar kegiatan tertentu untuk melihat detail lokasi, waktu, dan deskripsi acara.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT COLUMN: Calendar Grid Canvas -->
    <div class="card" style="margin-bottom: 0; padding: 1.5rem; border-top: 3px solid var(--accent-color);">
        <div id="calendar" style="min-height: 600px;"></div>
    </div>
</div>

<!-- Modal Detail Agenda -->
<div id="eventModal" class="modal-overlay" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center; padding: 2rem;">
    <div class="card" style="width: 100%; max-width: 600px; position: relative; animation: slideUp 0.3s ease; max-height: 90vh; display: flex; flex-direction: column; overflow: hidden; padding: 0; border-top: 3px solid var(--accent-color);">
        <div style="padding: 1.5rem; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; background: var(--surface-color);">
            <h3 id="modalDate" class="text-accent" style="font-weight: 800; margin: 0; font-size: 1.15rem; font-family: 'Outfit', sans-serif; color: var(--accent-color);">15 Mei 2026</h3>
            <button onclick="closeModal()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-secondary); display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 50%;">
                <i class="ph ph-x"></i>
            </button>
        </div>
        <div id="modalEventList" class="flex flex-col gap-4" style="overflow-y: auto; padding: 1.5rem; flex: 1;">
            <!-- Events will be injected here -->
        </div>
        <div id="noEventMessage" style="display: none; flex-direction: column; align-items: center; justify-content: center; padding: 4rem 1.5rem; text-align: center; flex: 1; min-height: 250px;">
            <i class="ph ph-calendar-blank" style="font-size: 3.5rem; opacity: 0.3; color: var(--text-secondary); display: inline-block; margin-bottom: 1rem;"></i>
            <p style="font-weight: 600; color: var(--text-primary); margin: 0;">Tidak ada kegiatan pada tanggal ini.</p>
        </div>
    </div>
</div>

<style>
    .fc { font-family: inherit; }
    .fc-header-toolbar { margin-bottom: 2rem !important; }
    .fc-button {
        text-transform: capitalize !important;
        font-weight: 600 !important;
        border-radius: 8px !important;
        transition: all 0.2s !important;
    }
    .fc-button-primary { 
        background-color: var(--bg-color) !important; 
        border-color: var(--border-color) !important; 
        color: var(--text-primary) !important;
    }
    .fc-button-primary:hover {
        background-color: var(--primary-light) !important;
        color: var(--accent-color) !important;
    }
    .fc-button-active, .fc-button-primary:not(:disabled):active, .fc-button-primary:not(:disabled).fc-button-active { 
        background-color: var(--accent-color) !important; 
        border-color: var(--accent-color) !important;
        color: white !important;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
        z-index: 2;
    }
    .fc-button:disabled { opacity: 0.5 !important; }
    
    /* Fix for button group contrast */
    .fc-button-group .fc-button {
        border-radius: 0 !important;
    }
    .fc-button-group .fc-button:first-child { border-top-left-radius: 8px !important; border-bottom-left-radius: 8px !important; }
    .fc-button-group .fc-button:last-child { border-top-right-radius: 8px !important; border-bottom-right-radius: 8px !important; }
    
    .fc-event { 
        cursor: pointer; 
        padding: 2px 6px; 
        border: none !important;
        border-radius: 4px !important;
    }
    
    .fc-event-title {
        font-weight: 600;
        font-size: 0.75rem;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .fc-daygrid-day-number { font-weight: 600; color: var(--text-primary); }
    .fc-day-today { background-color: var(--primary-light) !important; }

    @keyframes slideUp {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    .modal-overlay {
        display: flex;
        backdrop-filter: blur(4px);
    }

    .event-item {
        padding: 1rem;
        background: var(--bg-color);
        border-radius: 0.75rem;
        border-left: 4px solid var(--accent-color);
        transition: transform 0.2s;
    }
    .event-item:hover { transform: translateX(5px); }
</style>

<!-- FullCalendar Dependencies -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var allEvents = @json($formattedEvents);
    
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listMonth'
        },
        locale: 'id',
        events: allEvents,
        dateClick: function(info) {
            const dailyEvents = allEvents.filter(e => e.start === info.dateStr);
            showModal(info.dateStr, dailyEvents);
        },
        eventClick: function(info) {
            const dateStr = info.event.startStr.split('T')[0];
            const dailyEvents = allEvents.filter(e => e.start === dateStr);
            showModal(dateStr, dailyEvents);
        }
    });
    calendar.render();

    // Render upcoming events list in the sidebar dynamically from JSON
    const upcomingListEl = document.getElementById('upcomingEventsList');
    if (upcomingListEl) {
        upcomingListEl.innerHTML = '';
        // Sort events by date ascending
        const sortedEvents = [...allEvents].sort((a, b) => new Date(a.start) - new Date(b.start));
        // Filter events that are today or in the future
        const todayStr = new Date().toISOString().split('T')[0];
        const futureEvents = sortedEvents.filter(e => e.start >= todayStr).slice(0, 5); // Show top 5 upcoming events

        if (futureEvents.length === 0) {
            upcomingListEl.innerHTML = `
                <div style="text-align: center; padding: 2rem 0; color: var(--text-secondary);">
                    <i class="ph ph-calendar-blank" style="font-size: 2.5rem; opacity: 0.3; color: var(--text-secondary);"></i>
                    <p style="margin-top: 0.75rem; font-size: 0.85rem; font-weight: 600; color: var(--text-primary);">Tidak ada kegiatan terdekat.</p>
                </div>
            `;
        } else {
            futureEvents.forEach(e => {
                const date = new Date(e.start);
                const day = date.getDate();
                const month = date.toLocaleDateString('id-ID', { month: 'short' }).toUpperCase();
                
                const item = document.createElement('div');
                item.style.cssText = 'display: flex; gap: 0.75rem; padding: 0.75rem; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-color); cursor: pointer; transition: all 0.2s ease; align-items: center;';
                item.onmouseenter = () => item.style.borderColor = 'var(--accent-color)';
                item.onmouseleave = () => item.style.borderColor = 'var(--border-color)';
                item.onclick = () => showModal(e.start, [e]);
                
                item.innerHTML = `
                    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; width: 44px; height: 44px; border-radius: 6px; background: var(--accent-light); color: var(--accent-color); flex-shrink: 0; font-family: 'Outfit', sans-serif;">
                        <span style="font-size: 0.9rem; font-weight: 800; line-height: 1;">${day}</span>
                        <span style="font-size: 0.6rem; font-weight: 700; margin-top: 2px;">${month}</span>
                    </div>
                    <div style="min-width: 0; flex: 1;">
                        <h5 style="margin: 0; font-weight: 700; font-size: 0.85rem; color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-family: 'Outfit', sans-serif;">${e.title}</h5>
                        <p style="margin: 2px 0 0 0; font-size: 0.725rem; color: var(--text-secondary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            <i class="ph ph-map-pin"></i> ${e.extendedProps.location || 'Lokasi tidak diatur'}
                        </p>
                    </div>
                `;
                
                upcomingListEl.appendChild(item);
            });
        }
    }

    window.showModal = function(dateStr, dailyEvents) {
        const modal = document.getElementById('eventModal');
        const list = document.getElementById('modalEventList');
        const dateHeader = document.getElementById('modalDate');

        // Format date header
        const date = new Date(dateStr);
        dateHeader.innerText = date.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });

        list.innerHTML = '';
        if (dailyEvents.length === 0) {
            list.style.display = 'none';
            document.getElementById('noEventMessage').style.display = 'flex';
        } else {
            list.style.display = 'flex';
            document.getElementById('noEventMessage').style.display = 'none';
            dailyEvents.forEach(e => {
                const item = document.createElement('div');
                item.className = 'event-item';
                
                // Format time
                let timeStr = 'Sepanjang hari';
                if (e.extendedProps.original_start && e.extendedProps.original_start !== '00:00') {
                    timeStr = e.extendedProps.original_start + (e.extendedProps.original_end ? ' - ' + e.extendedProps.original_end : '');
                }

                item.innerHTML = `
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                        <h4 style="margin:0; font-weight: 700; color: var(--text-primary);">${e.title}</h4>
                        <span style="font-size: 0.7rem; background: var(--accent-light); color: var(--accent-color); padding: 2px 8px; border-radius: 10px; font-weight: 700;">${timeStr}</span>
                    </div>
                    <p style="margin:0 0 0.5rem 0; font-size: 0.85rem; color: var(--text-secondary);"><i class="ph ph-map-pin"></i> ${e.extendedProps.location || '-'}</p>
                    <p style="margin:0; font-size: 0.85rem; color: var(--text-secondary); line-height: 1.4;">${e.extendedProps.description || 'Tidak ada deskripsi.'}</p>
                `;
                list.appendChild(item);
            });
        }

        modal.style.display = 'flex';
    };

    window.closeModal = function() {
        document.getElementById('eventModal').style.display = 'none';
    };

    // Close on click outside
    document.getElementById('eventModal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });
});
</script>
@endsection
