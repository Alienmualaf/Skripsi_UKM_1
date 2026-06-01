@extends('layouts.app')

@section('title', 'Rekap Absensi Pelatih')
@section('header', 'Rekap Absensi Pelatih')

@section('content')
@php
    $activeTab = $isMultiEvent ? 'multi' : 'single';
@endphp

<!-- Navigation Tabs -->
<div style="display: flex; gap: 1rem; border-bottom: 2px solid var(--border-color); margin-bottom: 1.5rem; padding: 0 0.5rem;" class="animate-fade-in">
    <a href="{{ route('ukm.rekap.coaches', $event->id) }}" 
       style="padding: 1rem 0.5rem; font-weight: 700; font-size: 0.95rem; text-decoration: none; border-bottom: 3px solid {{ $activeTab === 'single' ? 'var(--accent-color)' : 'transparent' }}; color: {{ $activeTab === 'single' ? 'var(--accent-color)' : 'var(--text-secondary)' }}; transition: all 0.2s;">
        <i class="ph ph-calendar"></i> Rekap Per Agenda
    </a>
    <a href="?start_date={{ now()->startOfMonth()->format('Y-m-d') }}&end_date={{ now()->endOfMonth()->format('Y-m-d') }}" 
       style="padding: 1rem 0.5rem; font-weight: 700; font-size: 0.95rem; text-decoration: none; border-bottom: 3px solid {{ $activeTab === 'multi' ? 'var(--accent-color)' : 'transparent' }}; color: {{ $activeTab === 'multi' ? 'var(--accent-color)' : 'var(--text-secondary)' }}; transition: all 0.2s;">
        <i class="ph ph-chart-bar"></i> Rekap Keseluruhan (Multi Event)
    </a>
</div>

<!-- Filter Card -->
<div class="card mb-4 animate-fade-in" style="border: 1px solid var(--border-color); border-radius: 16px; box-shadow: 0 4px 10px rgba(0,0,0,0.01);">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem;">
        
        @if($activeTab === 'single')
            <!-- Single Event Filter (Agenda Dropdown) -->
            <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
                <label style="font-weight: 700; font-size: 0.875rem; color: var(--text-secondary); white-space: nowrap;">
                    <i class="ph ph-funnel" style="color: var(--accent-color);"></i> Pilih Agenda:
                </label>
                <select class="form-control" style="border-radius: 10px; border: 1.5px solid var(--border-color); height: 42px; padding: 0 1rem; width: 250px; font-weight: 600;" onchange="window.location.href='/ukm/events/' + this.value + '/rekap/coaches'">
                    @foreach($events as $e)
                    <option value="{{ $e->id }}" {{ $e->id == $event->id ? 'selected' : '' }}>{{ $e->title }}</option>
                    @endforeach
                </select>
            </div>
        @else
            <!-- Multi Event Filter (Date Picker Range) -->
            <form action="" method="GET" style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap; margin: 0;">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <label style="font-weight: 700; font-size: 0.875rem; color: var(--text-secondary); white-space: nowrap;">Dari:</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="form-control" style="border-radius: 10px; border: 1.5px solid var(--border-color); height: 42px; width: 160px; font-weight: 600;" required>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <label style="font-weight: 700; font-size: 0.875rem; color: var(--text-secondary); white-space: nowrap;">Sampai:</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="form-control" style="border-radius: 10px; border: 1.5px solid var(--border-color); height: 42px; width: 160px; font-weight: 600;" required>
                </div>
                <button type="submit" class="btn btn-primary" style="height: 42px; border-radius: 10px; font-weight: 700; display: inline-flex; align-items: center; gap: 0.25rem; box-shadow: 0 4px 10px rgba(79, 70, 229, 0.15);">
                    <i class="ph ph-magnifying-glass"></i> Filter Periode
                </button>
            </form>
        @endif
    </div>
</div>

<!-- Category Tabs Filter Pill -->
<div style="margin-bottom: 1.5rem; display: flex; justify-content: flex-start; align-items: center;" class="animate-fade-in">
    <form action="" method="GET" style="display: flex; gap: 0.5rem; background: rgba(0,0,0,0.02); padding: 0.35rem; border-radius: 12px; border: 1px solid var(--border-color); flex-wrap: wrap;">
        @if($activeTab === 'multi')
            <input type="hidden" name="start_date" value="{{ $startDate }}">
            <input type="hidden" name="end_date" value="{{ $endDate }}">
        @endif
        @foreach(['all' => 'Semua', 'pelatih' => 'Pelatih', 'bph' => 'BPH', 'lainnya' => 'Lainnya'] as $val => $label)
            <button type="submit" name="category" value="{{ $val }}" 
               style="padding: 0.5rem 1rem; border-radius: 10px; border: none; cursor: pointer; font-weight: 700; font-size: 0.8125rem; transition: all 0.2s;
                      {{ $categoryFilter == $val ? 'background: white; color: var(--accent-color); box-shadow: 0 2px 5px rgba(0,0,0,0.08);' : 'background: transparent; color: var(--text-secondary);' }}">
                {{ $label }}
            </button>
        @endforeach
    </form>
</div>

<!-- Recap Content -->
<div class="card animate-fade-in" style="border: 1px solid var(--border-color); border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.015);">
    <div style="margin-bottom: 2rem;">
        <h3 style="font-weight: 800; margin: 0; color: var(--text-color);">
            {{ $activeTab === 'single' ? 'Rekap Kehadiran Pelatih per Agenda' : 'Rekap Kehadiran Pelatih Keseluruhan' }}
        </h3>
        
        @if($activeTab === 'single')
            <p style="color: var(--text-secondary); font-size: 0.875rem; margin-top: 0.25rem;">
                Agenda: <strong>{{ $event->title }}</strong> • Total Pertemuan: <span style="color: var(--accent-color); font-weight: 700;">{{ $totalSessions }} Sesi</span>
            </p>
        @else
            <p style="color: var(--text-secondary); font-size: 0.875rem; margin-top: 0.25rem;">
                Periode: <strong>{{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}</strong> s.d <strong>{{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</strong> • Total Pertemuan Terlaksana: <span style="color: var(--accent-color); font-weight: 700;">{{ $totalSessions }} Sesi</span>
            </p>
        @endif
    </div>

    <div class="table-wrapper">
        <table class="table" style="vertical-align: middle;">
            <thead>
                <tr>
                    <th style="width: 60px; text-align: center;">No</th>
                    <th>Nama Pelatih</th>
                    <th>Kategori</th>
                    <th style="text-align: center; width: 100px;">Hadir</th>
                    <th style="text-align: center; width: 100px;">Izin</th>
                    <th style="text-align: center; width: 100px;">Tidak Hadir</th>
                    <th style="text-align: center; width: 150px;">Persentase</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rekap as $index => $item)
                <tr>
                    <td style="text-align: center; font-weight: 600; color: var(--text-secondary);">{{ $index + 1 }}</td>
                    <td style="font-weight: 700; color: var(--accent-color); cursor: pointer; text-decoration: underline; text-underline-offset: 4px;" 
                        onclick="showAttendedModal('{{ $item['nama'] }}', this)" 
                        data-attended-sessions="{{ json_encode($item['hadir_sessions']) }}">
                        {{ $item['nama'] }}
                    </td>
                    <td>
                        @php
                            $cat = $item['kategori'];
                            $badgeStyle = 'background: var(--accent-light); color: var(--accent-color);';
                            if ($cat === 'bph') {
                                $badgeStyle = 'background: #ecfdf5; color: #047857;';
                            } elseif ($cat === 'lainnya') {
                                $badgeStyle = 'background: #fffbeb; color: #b45309;';
                            }
                        @endphp
                        <span class="badge" style="{{ $badgeStyle }} font-weight: 700; font-size: 0.725rem; text-transform: uppercase;">
                            {{ $cat }}
                        </span>
                    </td>
                    <td style="text-align: center; color: var(--success-color); font-weight: 800; font-size: 1.05rem;">
                        {{ $item['hadir'] }}
                    </td>
                    <td style="text-align: center; color: var(--warning-color); font-weight: 800; font-size: 1.05rem;">
                        {{ $item['izin'] }}
                    </td>
                    <td style="text-align: center; color: var(--danger-color); font-weight: 800; font-size: 1.05rem;">
                        {{ $item['tidak_hadir'] }}
                    </td>
                    <td style="text-align: center;">
                        <div style="display: flex; flex-direction: column; align-items: center; gap: 0.25rem;">
                            @php
                                $percent = $item['persentase'];
                                $barColor = 'var(--danger-color)';
                                if ($percent >= 75) {
                                    $barColor = 'var(--success-color)';
                                } elseif ($percent >= 50) {
                                    $barColor = 'var(--warning-color)';
                                }
                            @endphp
                            <div style="font-weight: 800; font-size: 0.95rem; color: {{ $barColor }};">
                                {{ $percent }}%
                            </div>
                            <div style="width: 90px; height: 6px; background: rgba(0,0,0,0.05); border-radius: 3px; overflow: hidden;">
                                <div style="width: {{ $percent }}%; height: 100%; background: {{ $barColor }}; border-radius: 3px;"></div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 4rem; color: var(--text-secondary);">
                        <i class="ph ph-chalkboard-teacher" style="font-size: 3rem; opacity: 0.2;"></i>
                        <p style="margin-top: 1rem; font-weight: 600;">Tidak ada data pelatih/staf untuk penyaringan ini.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Detail Kehadiran Pelatih -->
<div id="coachDetailModal" class="modal-overlay" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center; backdrop-filter: blur(4px); transition: all 0.3s;">
    <div class="modal-content card animate-fade-in" style="width: 100%; max-width: 450px; padding: 2rem; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border: 1px solid var(--border-color); background: white;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="margin: 0; font-weight: 800; font-size: 1.2rem; color: var(--text-color); display: flex; align-items: center; gap: 0.5rem;">
                <i class="ph ph-chalkboard-teacher" style="color: var(--accent-color);"></i> Detail Kehadiran
            </h3>
            <button type="button" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center; border-radius: 50%;" onclick="closeCoachModal()">
                <i class="ph ph-x" style="font-size: 1rem; color: var(--text-secondary);"></i>
            </button>
        </div>
        
        <div style="margin-bottom: 1.5rem; background: var(--bg-color); padding: 0.85rem 1.25rem; border-radius: 12px; border: 1px solid var(--border-color);">
            <p style="margin: 0; font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em;">Nama Pelatih / Staf</p>
            <h4 id="modalCoachName" style="margin: 0.25rem 0 0 0; font-weight: 800; font-size: 1.15rem; color: var(--accent-color);"></h4>
        </div>
        
        <div style="border-top: 1px solid var(--border-color); padding-top: 1.25rem;">
            <p style="margin: 0 0 1rem 0; font-weight: 700; font-size: 0.875rem; color: var(--text-color); display: flex; align-items: center; gap: 0.35rem;">
                <i class="ph ph-calendar-check" style="color: var(--success-color);"></i> Sesi yang Dihadiri (Hadir Saja):
            </p>
            
            <div id="modalSessionsList" style="max-height: 250px; overflow-y: auto; padding-right: 0.25rem;">
                <!-- List will be populated here -->
            </div>
        </div>
    </div>
</div>

<script>
function showAttendedModal(name, element) {
    const rawSessions = element.getAttribute('data-attended-sessions');
    let sessions = [];
    try {
        sessions = JSON.parse(rawSessions);
    } catch (e) {
        console.error("Failed to parse sessions", e);
    }
    
    document.getElementById('modalCoachName').innerText = name;
    const listContainer = document.getElementById('modalSessionsList');
    listContainer.innerHTML = '';
    
    if (sessions.length === 0) {
        listContainer.innerHTML = `
            <div style="text-align: center; padding: 2rem; color: var(--text-secondary); background: #fef2f2; border-radius: 12px; border: 1.5px dashed #fecaca;">
                <i class="ph ph-calendar-x" style="font-size: 2.25rem; opacity: 0.5; margin-bottom: 0.5rem; display: block; margin-left: auto; margin-right: auto; color: var(--danger-color);"></i>
                <p style="margin: 0; font-weight: 700; font-size: 0.875rem; color: var(--danger-color);">Belum pernah hadir</p>
            </div>
        `;
    } else {
        const ul = document.createElement('ul');
        ul.style.listStyle = 'none';
        ul.style.padding = '0';
        ul.style.margin = '0';
        ul.style.display = 'flex';
        ul.style.flexDirection = 'column';
        ul.style.gap = '0.75rem';
        
        sessions.forEach(session => {
            const li = document.createElement('li');
            li.style.display = 'flex';
            li.style.alignItems = 'center';
            li.style.gap = '0.75rem';
            li.style.padding = '0.75rem 1rem';
            li.style.background = '#f0fdf4';
            li.style.border = '1px solid #bbf7d0';
            li.style.borderRadius = '12px';
            
            li.innerHTML = `
                <div style="width: 24px; height: 24px; border-radius: 50%; background: #dcfce7; color: #15803d; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; flex-shrink: 0;">
                    <i class="ph ph-check-bold"></i>
                </div>
                <div style="flex-grow: 1;">
                    <h5 style="margin: 0; font-weight: 700; font-size: 0.875rem; color: #166534;">` + session.title + `</h5>
                    <p style="margin: 0.1rem 0 0 0; font-size: 0.725rem; color: #15803d; opacity: 0.95; display: flex; align-items: center; gap: 0.25rem;">
                        <i class="ph ph-calendar-blank"></i> ` + session.date + `
                    </p>
                </div>
            `;
            ul.appendChild(li);
        });
        listContainer.appendChild(ul);
    }
    
    document.getElementById('coachDetailModal').style.display = 'flex';
}

function closeCoachModal() {
    document.getElementById('coachDetailModal').style.display = 'none';
}

// Close modal when clicking outside content
window.addEventListener('click', function(e) {
    const modal = document.getElementById('coachDetailModal');
    if (e.target === modal) {
        closeCoachModal();
    }
});
</script>
@endsection
