@extends('layouts.app')

@section('title', 'Keanggotaan UKM')
@section('header', 'Monitoring Anggota UKM')

@section('content')
<div style="margin-bottom: 1.5rem;">
    <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">Monitoring Keanggotaan UKM</h3>
    <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Super Admin mengawasi pendaftaran, status keanggotaan, peran pengurus, dan klasifikasi peserta seluruh UKM.</p>
</div>

<!-- Filter Card -->
<div class="card" style="border-top: 3px solid var(--accent-color); padding: 1.25rem; margin-bottom: 1.5rem;">
    <form method="GET" style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 180px;">
            <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Filter berdasarkan UKM</label>
            <select name="ukm_id" class="form-control" style="padding: 0.5rem 0.75rem; width: 100%; border-radius: 8px; border: 1px solid var(--border-color);" onchange="this.form.submit()">
                <option value="">Semua UKM</option>
                @foreach($ukms as $u)
                    <option value="{{ $u->id }}" {{ request('ukm_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                @endforeach
            </select>
        </div>
        <div style="width: 150px;">
            <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Peran</label>
            <select name="role" class="form-control" style="padding: 0.5rem 0.75rem; width: 100%; border-radius: 8px; border: 1px solid var(--border-color);" onchange="this.form.submit()">
                <option value="">Semua Peran</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="member" {{ request('role') == 'member' ? 'selected' : '' }}>Anggota</option>
            </select>
        </div>
        <div style="width: 150px;">
            <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Status</label>
            <select name="status" class="form-control" style="padding: 0.5rem 0.75rem; width: 100%; border-radius: 8px; border: 1px solid var(--border-color);" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Tertunda</option>
            </select>
        </div>
        @if(request()->anyFilled(['ukm_id', 'role', 'status']))
            <a href="{{ request()->url() }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; text-decoration: none; color: var(--text-primary); font-size: 0.875rem; display: flex; align-items: center; gap: 0.25rem;"><i class="ph ph-x-circle"></i> Reset</a>
        @endif
    </form>
</div>

<!-- Table Card -->
<div class="card" style="padding: 1.5rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
        <h4 style="margin: 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
            <i class="ph ph-users" style="color: var(--accent-color);"></i> Daftar Keanggotaan UKM
        </h4>
        <span style="font-size: 0.8125rem; color: var(--text-secondary); font-weight: 600;">
            Total: {{ count($memberships) }} Anggota
        </span>
    </div>

    <div class="table-wrapper" style="margin-bottom: 0; border: none; padding: 0; box-shadow: none;">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama Mahasiswa</th>
                    <th>UKM</th>
                    <th>Peran</th>
                    <th>Klasifikasi</th>
                    <th>Status</th>
                    <th>Bergabung</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($memberships as $m)
                <tr>
                    <td>
                        <div style="font-weight: 600; color: var(--text-primary);">{{ $m->user->name }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-secondary);">{{ $m->user->npm ?? '-' }}</div>
                    </td>
                    <td style="font-weight: 600; color: var(--accent-color);">{{ $m->ukm->name }}</td>
                    <td>
                        <span class="badge {{ $m->role_in_ukm == 'admin' ? 'badge-warning' : 'badge-info' }}">
                            {{ $m->role_in_ukm == 'admin' ? 'Admin UKM' : 'Anggota' }}
                        </span>
                    </td>
                    <td>{{ $m->classification->name ?? '-' }}</td>
                    <td>
                        <span class="badge {{ $m->status == 'approved' ? 'badge-approved' : 'badge-danger' }}">
                            {{ $m->status == 'approved' ? 'Approved' : 'Pending' }}
                        </span>
                    </td>
                    <td>{{ $m->created_at->format('d M Y') }}</td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <button onclick="editMembership({{ json_encode($m) }})" class="btn" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; font-weight: 600; background: var(--bg-color); color: var(--accent-color); border: 1px solid var(--border-color);">
                                <i class="ph ph-pencil"></i> Edit
                            </button>
                            <form action="/admin/memberships/{{ $m->id }}" method="POST" onsubmit="return confirm('Hapus keanggotaan ini?')" style="display: inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; font-weight: 600;">
                                    <i class="ph ph-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
                @if($memberships->isEmpty())
                <tr><td colspan="7" class="text-secondary text-center py-8">Tidak ada data keanggotaan.</td></tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Edit -->
<div id="modal-edit-membership" class="modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); align-items: center; justify-content: center; z-index: 1000; backdrop-filter: blur(4px);">
    <div class="card" style="width: 100%; max-width: 500px; margin: 1rem;">
        <h3 style="font-weight: 700; margin-bottom: 1.5rem; color: var(--text-primary);">Edit Keanggotaan</h3>
        <form id="edit-form-membership" method="POST">
            @csrf @method('PUT')
            <div style="margin-bottom: 1rem;">
                <label class="form-label mb-2">Peran di UKM</label>
                <select name="role_in_ukm" id="edit-role" class="form-control" required>
                    <option value="member">Anggota (Member)</option>
                    <option value="admin">Admin UKM (Admin)</option>
                </select>
            </div>
            <div style="margin-bottom: 1rem;">
                <label class="form-label mb-2">Status</label>
                <select name="status" id="edit-status" class="form-control" required>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                </select>
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label class="form-label mb-2">Klasifikasi/Seksi</label>
                <select name="ukm_classification_id" id="edit-classification" class="form-control">
                    <option value="">- Tanpa Klasifikasi -</option>
                    @foreach($classifications as $c)
                        <option value="{{ $c->id }}" data-ukm="{{ $c->ukm_id }}">{{ $c->name }} ({{ $c->ukm->name }})</option>
                    @endforeach
                </select>
            </div>
            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <button type="button" onclick="document.getElementById('modal-edit-membership').style.display='none'" class="btn" style="background: var(--bg-color);">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function editMembership(m) {
    document.getElementById('edit-form-membership').action = '/admin/memberships/' + m.id;
    document.getElementById('edit-role').value = m.role_in_ukm;
    document.getElementById('edit-status').value = m.status;
    document.getElementById('edit-classification').value = m.ukm_classification_id || '';
    
    // Filter classifications to only show the selected UKM's classifications
    const select = document.getElementById('edit-classification');
    Array.from(select.options).forEach(opt => {
        if (!opt.value) return;
        if (opt.getAttribute('data-ukm') == m.ukm_id) {
            opt.style.display = 'block';
        } else {
            opt.style.display = 'none';
        }
    });

    document.getElementById('modal-edit-membership').style.display = 'flex';
}
</script>
@endsection
