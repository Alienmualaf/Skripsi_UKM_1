@extends('layouts.app')

@section('title', 'Kelola Peserta Kegiatan')
@section('header', 'Kelola Peserta Kegiatan')

@section('content')
<form action="/ukm/events/{{ $event->id }}/participants" method="POST">
    @csrf
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; align-items: start;">
        <!-- Bagian Anggota -->
        <div class="card animate-fade-in" style="min-height: 520px; display: flex; flex-direction: column;">
            <h3 style="font-weight: 700; font-size: 1.25rem; margin-bottom: 0.5rem;">Anggota UKM (Peserta)</h3>
            <p style="color: var(--text-secondary); font-size: 0.875rem; margin-bottom: 2rem;">Pilih anggota yang mengikuti kegiatan ini. Mereka akan mendapatkan akses ke materi dan pengumuman terkait.</p>

            <div class="table-responsive" style="max-height: 450px; overflow-y: auto; flex: 1;">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 40px;"><input type="checkbox" id="check-all-members" style="width: 18px; height: 18px;"></th>
                            <th>Nama Anggota</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allMembers as $m)
                        <tr>
                            <td>
                                <input type="checkbox" name="memberships[]" value="{{ $m->id }}" class="member-checkbox" 
                                    style="width: 18px; height: 18px;"
                                    {{ $event->participants->contains($m->id) ? 'checked' : '' }}>
                            </td>
                            <td>
                                <div style="font-weight: 600;">{{ $m->user->name }}</div>
                                <div style="font-size: 0.75rem; color: var(--text-secondary);">{{ $m->user->email }}</div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Bagian Pelatih -->
        <div class="card animate-fade-in" style="min-height: 520px; display: flex; flex-direction: column;">
            <h3 style="font-weight: 700; font-size: 1.25rem; margin-bottom: 0.5rem;">Pelatih & BPH Terlibat</h3>
            <p style="color: var(--text-secondary); font-size: 0.875rem; margin-bottom: 2rem;">Pilih pelatih atau BPH yang terlibat dalam agenda ini untuk keperluan absensi pelatih.</p>

            <div class="table-responsive" style="max-height: 450px; overflow-y: auto; flex: 1;">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 40px;"><input type="checkbox" id="check-all-coaches" style="width: 18px; height: 18px;"></th>
                            <th>Nama Pelatih / BPH</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allCoaches as $c)
                        <tr>
                            <td>
                                <input type="checkbox" name="coach_ids[]" value="{{ $c->id }}" class="coach-checkbox" 
                                    style="width: 18px; height: 18px;"
                                    {{ $event->coaches->contains($c->id) ? 'checked' : '' }}>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    @if($c->photo)
                                        <img src="{{ asset('storage/' . $c->photo) }}" style="width: 24px; height: 24px; border-radius: 50%; object-fit: cover;">
                                    @endif
                                    <div>
                                        <div style="font-weight: 600;">{{ $c->name }}</div>
                                        <span class="badge {{ $c->category == 'pelatih' ? 'badge-primary' : 'badge-success' }}" style="font-size: 0.6rem; text-transform: uppercase;">{{ $c->category }}</span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @if($allCoaches->isEmpty())
                        <tr>
                            <td colspan="2" style="text-align: center; padding: 2rem; color: var(--text-secondary);">
                                Belum ada data pelatih.<br>
                                <a href="/ukm/coaches" style="color: var(--accent-color); font-weight: 600;">Tambah di sini &rarr;</a>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Unified Save Bar -->
    <div style="margin-top: 1.5rem; display: flex; justify-content: flex-end; align-items: center; background: var(--surface-color); padding: 1.25rem 2rem; border-radius: var(--radius-lg); border: 1px solid var(--border-color); box-shadow: var(--shadow-sm); gap: 1rem;">
        <a href="/ukm/events" class="btn btn-secondary" style="padding: 0.75rem 2rem; font-weight: 600; text-decoration: none;">Batal</a>
        <button type="submit" class="btn btn-primary" style="padding: 0.75rem 3rem; font-weight: 600; cursor: pointer;">Simpan Perubahan</button>
    </div>
</form>

<script>
    document.getElementById('check-all-members').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.member-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });
    document.getElementById('check-all-coaches').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.coach-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });
</script>
@endsection
