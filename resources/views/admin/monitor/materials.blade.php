@extends('layouts.app')

@section('title', 'Monitor Materi Latihan')
@section('header', 'Monitoring Materi Latihan')

@section('content')
<div style="margin-bottom: 1.5rem;">
    <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">Override Data: Berkas Materi Latihan</h3>
    <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Pengawasan folder dan kumpulan materi latihan lagu (Partitur, MP3, Rekaman Latihan) yang diupload oleh Pengurus & Admin UKM.</p>
</div>

<div class="card" style="padding: 1.5rem;">
    <h4 style="margin: 0 0 1rem 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
        <i class="ph ph-files" style="color: var(--accent-color);"></i> Database Materi & Berkas Partitur
    </h4>
    
    <div class="table-wrapper" style="margin-bottom: 0; border: none; padding: 0; box-shadow: none;">
        <table class="table">
            <thead>
                <tr>
                    <th>Judul Materi</th>
                    <th>Tipe</th>
                    <th>Folder Tujuan</th>
                    <th>Pengupload</th>
                    <th>Diupload Pada</th>
                    <th style="width: 180px; text-align: center;">Override Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($materials as $m)
                <tr>
                    <td style="font-weight: 700; color: var(--text-primary);">{{ $m->title }}</td>
                    <td><span class="badge" style="background: var(--bg-color); border: 1px solid var(--border-color); color: var(--text-primary); font-weight: bold;">{{ $m->type }}</span></td>
                    <td><span class="badge badge-info" style="background: var(--accent-light); color: var(--accent-color); font-weight: bold;">{{ $m->folder ? $m->folder->name : 'General' }}</span></td>
                    <td>{{ $m->uploader ? $m->uploader->name : '-' }}</td>
                    <td style="font-size: 0.8125rem; color: var(--text-secondary);">{{ $m->created_at->format('d-m-Y H:i') }}</td>
                    <td>
                        <div style="display: flex; gap: 0.35rem; justify-content: center; align-items: center;">
                            <form action="{{ route('admin.monitor.override.delete', ['model' => 'material', 'id' => $m->id]) }}" method="POST" onsubmit="return confirm('Hapus paksa file materi ini? File fisik di disk juga akan terhapus.');" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 0.35rem 0.65rem; font-size: 0.75rem; font-weight: 700; border-radius: 6px; display: inline-flex; align-items: center; gap: 0.15rem;"><i class="ph ph-trash"></i> Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-secondary py-4">Tidak ada data materi latihan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top: 1rem;">
        {{ $materials->appends(['materials_page' => $materials->currentPage()])->links('shared.pagination') }}
    </div>
</div>
@endsection
