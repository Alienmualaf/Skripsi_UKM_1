@extends('layouts.app')

@section('title', 'Pelatih & Pembina UKM')
@section('header', 'Monitoring Pelatih & Pembina UKM')

@section('content')
<div style="margin-bottom: 1.5rem;">
    <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">Monitoring Pelatih & Pembina</h3>
    <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Super Admin mengawasi data penanggung jawab teknis, pelatih profesional, dan pembina seluruh UKM.</p>
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
            <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Kategori</label>
            <select name="category" class="form-control" style="padding: 0.5rem 0.75rem; width: 100%; border-radius: 8px; border: 1px solid var(--border-color);" onchange="this.form.submit()">
                <option value="">Semua Kategori</option>
                <option value="pelatih" {{ request('category') == 'pelatih' ? 'selected' : '' }}>Pelatih</option>
                <option value="bph" {{ request('category') == 'bph' ? 'selected' : '' }}>Pembina (BPH)</option>
                <option value="lainnya" {{ request('category') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
            </select>
        </div>
        
        <div style="flex: 2; min-width: 250px; display: flex; gap: 0.5rem; align-items: flex-end;">
            <div style="position: relative; flex: 1;">
                <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Cari Personel</label>
                <i class="ph ph-magnifying-glass" style="position: absolute; left: 0.85rem; top: calc(50% + 0.4rem); transform: translateY(-50%); color: var(--text-secondary); font-size: 1rem;"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, keahlian..." class="form-control" style="padding-left: 2.25rem; height: 2.5rem; font-size: 0.875rem;">
            </div>
            <button type="submit" class="btn btn-primary" style="height: 2.5rem; padding: 0 1rem; font-weight: 700; border-radius: 8px;">Cari</button>
        </div>

        @if(request()->anyFilled(['ukm_id', 'category', 'search']))
            <a href="{{ request()->url() }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; text-decoration: none; color: var(--text-primary); font-size: 0.875rem; display: flex; align-items: center; gap: 0.25rem; height: 2.5rem;"><i class="ph ph-x-circle"></i> Reset</a>
        @endif
    </form>
</div>

<!-- Table Card -->
<div class="card" style="padding: 1.5rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
        <h4 style="margin: 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
            <i class="ph ph-user-focus" style="color: var(--accent-color);"></i> Daftar Pelatih & Pembina
        </h4>
        <span style="font-size: 0.8125rem; color: var(--text-secondary); font-weight: 600;">
            Total: {{ $coaches->total() }} Personel
        </span>
    </div>

    <div class="table-wrapper" style="margin-bottom: 0; border: none; padding: 0; box-shadow: none;">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama Lengkap</th>
                    <th>UKM</th>
                    <th>Kategori</th>
                    <th>Keahlian/Skills</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($coaches as $c)
                <tr>
                    <td style="font-weight: 600; color: var(--text-primary);">{{ $c->name }}</td>
                    <td style="font-weight: 600; color: var(--accent-color);">{{ $c->ukm->name }}</td>
                    <td>
                        <span class="badge {{ $c->category == 'pelatih' ? 'badge-info' : 'badge-approved' }}">
                            {{ ucfirst($c->category) }}
                        </span>
                    </td>
                    <td>{{ $c->skills ?: '-' }}</td>
                    <td style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $c->description ?: '-' }}</td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <button onclick="editCoach({{ json_encode($c) }})" class="btn" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; font-weight: 600; background: var(--bg-color); color: var(--accent-color); border: 1px solid var(--border-color);">
                                <i class="ph ph-pencil"></i> Edit
                            </button>
                            <form action="/admin/coaches/{{ $c->id }}" method="POST" onsubmit="return confirm('Hapus data pelatih ini?')" style="display: inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; font-weight: 600;">
                                    <i class="ph ph-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
                @if($coaches->isEmpty())
                <tr><td colspan="6" class="text-secondary text-center py-8">Tidak ada data pelatih/pembina.</td></tr>
                @endif
            </tbody>
        </table>
    </div>
    <div style="margin-top: 1.25rem;">
        {{ $coaches->links('shared.pagination') }}
    </div>
</div>

<!-- Modal Edit -->
<div id="modal-edit-coach" class="modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); align-items: center; justify-content: center; z-index: 1000; backdrop-filter: blur(4px);">
    <div class="card" style="width: 100%; max-width: 500px; margin: 1rem;">
        <h3 style="font-weight: 700; margin-bottom: 1.5rem; color: var(--text-primary);">Edit Pelatih/Pembina</h3>
        <form id="edit-form-coach" method="POST">
            @csrf @method('PUT')
            <div style="margin-bottom: 1rem;">
                <label class="form-label mb-2">Nama Lengkap</label>
                <input type="text" name="name" id="edit-name" class="form-control" required>
            </div>
            <div style="margin-bottom: 1rem;">
                <label class="form-label mb-2">Kategori</label>
                <select name="category" id="edit-category" class="form-control" required>
                    <option value="pelatih">Pelatih</option>
                    <option value="bph">Pembina (BPH)</option>
                    <option value="lainnya">Lainnya</option>
                </select>
            </div>
            <div style="margin-bottom: 1rem;">
                <label class="form-label mb-2">Keahlian/Skills</label>
                <input type="text" name="skills" id="edit-skills" class="form-control">
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label class="form-label mb-2">Deskripsi/Keterangan</label>
                <textarea name="description" id="edit-description" class="form-control" rows="3"></textarea>
            </div>
            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <button type="button" onclick="document.getElementById('modal-edit-coach').style.display='none'" class="btn" style="background: var(--bg-color);">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function editCoach(c) {
    document.getElementById('edit-form-coach').action = '/admin/coaches/' + c.id;
    document.getElementById('edit-name').value = c.name;
    document.getElementById('edit-category').value = c.category;
    document.getElementById('edit-skills').value = c.skills || '';
    document.getElementById('edit-description').value = c.description || '';
    document.getElementById('modal-edit-coach').style.display = 'flex';
}
</script>
@endsection
