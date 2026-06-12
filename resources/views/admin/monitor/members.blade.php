@extends('layouts.app')

@section('title', 'Monitor Anggota & Pelatih')
@section('header', 'Monitoring Data Anggota')

@section('content')
<div style="margin-bottom: 1.5rem;">
    <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">Override Data: Anggota, Pelatih & Suara</h3>
    <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Pengawasan seluruh data anggota UKM, pelatih, serta klasifikasi paduan suara. Administrator dapat melakukan edit langsung atau hapus permanen data bermasalah.</p>
</div>

<!-- Grid / Tabs of Data -->
<div style="display: flex; flex-direction: column; gap: 2rem;">

    <!-- 1. DATA ANGGOTA -->
    <div class="card" style="padding: 1.5rem;">
        <h4 style="margin: 0 0 1rem 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
            <i class="ph ph-users-three" style="color: var(--accent-color);"></i> Database Anggota Aktif & Calon
        </h4>
        
        <div class="table-wrapper" style="margin-bottom: 0; border: none; padding: 0; box-shadow: none;">
            <table class="table">
                <thead>
                    <tr>
                        <th>NPM / NIM</th>
                        <th>Nama Lengkap</th>
                        <th>Suara</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th style="width: 180px; text-align: center;">Override Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $m)
                    <tr>
                        <td style="font-family: monospace; font-weight: 700;">{{ $m->npm }}</td>
                        <td style="font-weight: 700; color: var(--text-primary);">{{ $m->name }}</td>
                        <td><span class="badge" style="background: var(--bg-color); border: 1px solid var(--border-color); color: var(--text-primary); font-weight: bold;">{{ $m->voiceClassification ? $m->voiceClassification->name : '-' }}</span></td>
                        <td>{{ $m->user ? $m->user->email : '-' }}</td>
                        <td>
                            <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: var(--success-color); border: 1px solid rgba(16,185,129,0.15); font-weight: bold;">{{ $m->status }}</span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 0.35rem; justify-content: center; align-items: center;">
                                <a href="{{ route('ukm.members.edit', $m->id) }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.35rem 0.65rem; font-size: 0.75rem; font-weight: 700; color: var(--text-primary); text-decoration: none; border-radius: 6px; display: inline-flex; align-items: center; gap: 0.15rem;"><i class="ph ph-pencil-simple"></i> Edit</a>
                                <form action="{{ route('admin.monitor.override.delete', ['model' => 'member', 'id' => $m->id]) }}" method="POST" onsubmit="return confirm('Hapus paksa anggota ini? Tindakan ini tidak bisa dibatalkan.');" style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding: 0.35rem 0.65rem; font-size: 0.75rem; font-weight: 700; border-radius: 6px; display: inline-flex; align-items: center; gap: 0.15rem;"><i class="ph ph-trash"></i> Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-secondary py-4">Tidak ada data anggota.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top: 1rem;">
            {{ $members->appends(['members_page' => $members->currentPage()])->links('shared.pagination') }}
        </div>
    </div>

    <!-- 2. DATA PELATIH -->
    <div class="card" style="padding: 1.5rem;">
        <h4 style="margin: 0 0 1rem 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
            <i class="ph ph-chalkboard-teacher" style="color: var(--accent-color);"></i> Database Pelatih / Conductor
        </h4>
        
        <div class="table-wrapper" style="margin-bottom: 0; border: none; padding: 0; box-shadow: none;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama Pelatih</th>
                        <th>No. Telp</th>
                        <th>Spesialisasi / Peran</th>
                        <th>Status</th>
                        <th style="width: 180px; text-align: center;">Override Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($trainers as $t)
                    <tr>
                        <td style="font-weight: 700; color: var(--text-primary);">{{ $t->name }}</td>
                        <td>{{ $t->phone }}</td>
                        <td style="font-weight: 600;">{{ $t->specialization ?? '-' }}</td>
                        <td>
                            <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: var(--success-color); border: 1px solid rgba(16,185,129,0.15); font-weight: bold;">Aktif</span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 0.35rem; justify-content: center; align-items: center;">
                                <a href="{{ route('ukm.trainers.edit', $t->id) }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.35rem 0.65rem; font-size: 0.75rem; font-weight: 700; color: var(--text-primary); text-decoration: none; border-radius: 6px; display: inline-flex; align-items: center; gap: 0.15rem;"><i class="ph ph-pencil-simple"></i> Edit</a>
                                <form action="{{ route('admin.monitor.override.delete', ['model' => 'trainer', 'id' => $t->id]) }}" method="POST" onsubmit="return confirm('Hapus paksa pelatih ini? Tindakan ini tidak bisa dibatalkan.');" style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding: 0.35rem 0.65rem; font-size: 0.75rem; font-weight: 700; border-radius: 6px; display: inline-flex; align-items: center; gap: 0.15rem;"><i class="ph ph-trash"></i> Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-secondary py-4">Tidak ada data pelatih.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top: 1rem;">
            {{ $trainers->appends(['trainers_page' => $trainers->currentPage()])->links('shared.pagination') }}
        </div>
    </div>

    <!-- 3. DATA KLASIFIKASI SUARA -->
    <div class="card" style="padding: 1.5rem;">
        <h4 style="margin: 0 0 1rem 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
            <i class="ph ph-microphone-stage" style="color: var(--accent-color);"></i> Database Klasifikasi Suara
        </h4>
        
        <div class="table-wrapper" style="margin-bottom: 0; border: none; padding: 0; box-shadow: none;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Tipe / Jenis Suara</th>
                        <th>Rentang Nada (Pitch)</th>
                        <th>Jumlah Anggota</th>
                        <th style="width: 180px; text-align: center;">Override Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($voiceClassifications as $v)
                    <tr>
                        <td style="font-weight: 700; color: var(--text-primary);">{{ $v->name }}</td>
                        <td>{{ $v->pitch_range ?? '-' }}</td>
                        <td style="font-weight: bold; color: var(--accent-color);">{{ $v->members_count }} anggota</td>
                        <td>
                            <div style="display: flex; gap: 0.35rem; justify-content: center; align-items: center;">
                                <form action="{{ route('admin.monitor.override.delete', ['model' => 'voiceClassification', 'id' => $v->id]) }}" method="POST" onsubmit="return confirm('Hapus paksa tipe suara ini? Tindakan ini tidak bisa dibatalkan.');" style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding: 0.35rem 0.65rem; font-size: 0.75rem; font-weight: 700; border-radius: 6px; display: inline-flex; align-items: center; gap: 0.15rem;"><i class="ph ph-trash"></i> Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-secondary py-4">Tidak ada data klasifikasi suara.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top: 1rem;">
            {{ $voiceClassifications->appends(['voices_page' => $voiceClassifications->currentPage()])->links('shared.pagination') }}
        </div>
    </div>

</div>
@endsection
