@extends('layouts.app')

@section('title', 'Absensi Anggota')
@section('header', 'Monitoring Absensi Anggota')

@section('content')
<div style="margin-bottom: 1.5rem;">
    <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">Monitoring Absensi Anggota</h3>
    <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Super Admin mengawasi kehadiran anggota pada agenda kegiatan masing-masing UKM.</p>
</div>

<!-- Filter Card -->
<div class="card" style="border-top: 3px solid var(--accent-color); padding: 1.25rem; margin-bottom: 1.5rem;">
    <form method="GET" style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 200px;">
            <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Filter berdasarkan UKM</label>
            <select name="ukm_id" class="form-control" style="padding: 0.5rem 0.75rem; width: 100%; border-radius: 8px; border: 1px solid var(--border-color);" onchange="this.form.submit()">
                <option value="">Semua UKM</option>
                @foreach($ukms as $u)
                    <option value="{{ $u->id }}" {{ request('ukm_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                @endforeach
            </select>
        </div>
        <div style="width: 180px;">
            <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Status</label>
            <select name="status" class="form-control" style="padding: 0.5rem 0.75rem; width: 100%; border-radius: 8px; border: 1px solid var(--border-color);" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="hadir" {{ request('status') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                <option value="sakit" {{ request('status') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                <option value="izin" {{ request('status') == 'izin' ? 'selected' : '' }}>Izin</option>
                <option value="alpa" {{ request('status') == 'alpa' ? 'selected' : '' }}>Alpa</option>
            </select>
        </div>
        
        <div style="flex: 2; min-width: 250px; display: flex; gap: 0.5rem; align-items: flex-end;">
            <div style="position: relative; flex: 1;">
                <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Cari Absensi</label>
                <i class="ph ph-magnifying-glass" style="position: absolute; left: 0.85rem; top: calc(50% + 0.4rem); transform: translateY(-50%); color: var(--text-secondary); font-size: 1rem;"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama mahasiswa atau kegiatan..." class="form-control" style="padding-left: 2.25rem; height: 2.5rem; font-size: 0.875rem;">
            </div>
            <button type="submit" class="btn btn-primary" style="height: 2.5rem; padding: 0 1rem; font-weight: 700; border-radius: 8px;">Cari</button>
        </div>

        @if(request()->anyFilled(['ukm_id', 'status', 'search']))
            <a href="{{ request()->url() }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; text-decoration: none; color: var(--text-primary); font-size: 0.875rem; display: flex; align-items: center; gap: 0.25rem; height: 2.5rem;"><i class="ph ph-x-circle"></i> Reset</a>
        @endif
    </form>
</div>

<!-- Table Card -->
<div class="card" style="padding: 1.5rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
        <h4 style="margin: 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
            <i class="ph ph-calendar-check" style="color: var(--accent-color);"></i> Log Kehadiran Anggota
        </h4>
        <span style="font-size: 0.8125rem; color: var(--text-secondary); font-weight: 600;">
            Total: {{ $attendances->total() }} Data Absensi
        </span>
    </div>

    <div class="table-wrapper" style="margin-bottom: 0; border: none; padding: 0; box-shadow: none;">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama Mahasiswa</th>
                    <th>UKM</th>
                    <th>Kegiatan</th>
                    <th>Sesi</th>
                    <th>Status</th>
                    <th>Waktu Absen</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendances as $a)
                <tr>
                    <td>
                        <div style="font-weight: 600; color: var(--text-primary);">{{ $a->user->name }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-secondary);">{{ $a->user->npm ?? '-' }}</div>
                    </td>
                    <td style="font-weight: 600; color: var(--accent-color);">{{ $a->event->ukm->name ?? '-' }}</td>
                    <td>{{ $a->event->title ?? '-' }}</td>
                    <td>{{ $a->session->title ?? '-' }}</td>
                    <td>
                        @if($a->status === 'hadir')
                            <span class="badge badge-approved">Hadir</span>
                        @elseif($a->status === 'sakit')
                            <span class="badge badge-info">Sakit</span>
                        @elseif($a->status === 'izin')
                            <span class="badge badge-warning">Izin</span>
                        @else
                            <span class="badge badge-danger">Alpa</span>
                        @endif
                    </td>
                    <td>{{ \Carbon\Carbon::parse($a->created_at)->format('d M Y H:i') }}</td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <button onclick="editAttendance({{ json_encode($a) }})" class="btn" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; font-weight: 600; background: var(--bg-color); color: var(--accent-color); border: 1px solid var(--border-color);">
                                <i class="ph ph-pencil"></i>
                            </button>
                            <form action="/admin/attendances/{{ $a->id }}" method="POST" onsubmit="return confirm('Hapus absensi ini?')" style="display: inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; font-weight: 600;">
                                    <i class="ph ph-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
                @if($attendances->isEmpty())
                <tr><td colspan="7" class="text-secondary text-center py-8">Tidak ada data absensi anggota.</td></tr>
                @endif
            </tbody>
        </table>
    </div>
    <div style="margin-top: 1.25rem;">
        {{ $attendances->links('shared.pagination') }}
    </div>
</div>

<!-- Modal Edit -->
<div id="modal-edit-attendance" class="modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); align-items: center; justify-content: center; z-index: 1000; backdrop-filter: blur(4px);">
    <div class="card" style="width: 100%; max-width: 500px; margin: 1rem;">
        <h3 style="font-weight: 700; margin-bottom: 1.5rem; color: var(--text-primary);">Edit Status Kehadiran</h3>
        <form id="edit-form-attendance" method="POST">
            @csrf @method('PUT')
            <div style="margin-bottom: 1.5rem;">
                <label class="form-label mb-3">Status Kehadiran</label>
                <div style="display: flex; gap: 1.25rem; padding: 0.5rem 0;">
                    @foreach(['hadir' => ['label' => 'Hadir', 'color' => '#03a85a'], 'sakit' => ['label' => 'Sakit', 'color' => '#0369a1'], 'izin' => ['label' => 'Izin', 'color' => '#f59e0b'], 'alpa' => ['label' => 'Alpa', 'color' => '#dc2626']] as $val => $cfg)
                    <label style="cursor: pointer; display: flex; align-items: center; gap: 0.4rem; font-weight: 600; font-size: 0.875rem; color: var(--text-primary);">
                        <input type="radio" name="status" id="status-{{ $val }}" value="{{ $val }}" required style="accent-color: {{ $cfg['color'] }};"> {{ $cfg['label'] }}
                    </label>
                    @endforeach
                </div>
            </div>
            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <button type="button" onclick="document.getElementById('modal-edit-attendance').style.display='none'" class="btn" style="background: var(--bg-color);">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function editAttendance(a) {
    document.getElementById('edit-form-attendance').action = '/admin/attendances/' + a.id;
    const r = document.getElementById('status-' + a.status);
    if (r) r.checked = true;
    document.getElementById('modal-edit-attendance').style.display = 'flex';
}
</script>
@endsection
