@extends('layouts.app')

@section('title', 'Monitor Persuratan')
@section('header', 'Monitoring Persuratan')

@section('content')
<div style="margin-bottom: 1.5rem;">
    <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">Override Data: Arsip Persuratan</h3>
    <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Pengawasan seluruh dokumen surat masuk, surat keluar, surat tugas, dan peminjaman ruangan yang diarsipkan oleh Pengurus.</p>
</div>

<div class="card" style="padding: 1.5rem;">
    <h4 style="margin: 0 0 1rem 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
        <i class="ph ph-envelope" style="color: var(--accent-color);"></i> Database Surat Masuk / Keluar
    </h4>
    
    <div class="table-wrapper" style="margin-bottom: 0; border: none; padding: 0; box-shadow: none;">
        <table class="table">
            <thead>
                <tr>
                    <th>No. Surat</th>
                    <th>Perihal / Judul</th>
                    <th>Kategori / Jenis</th>
                    <th>Asal / Tujuan</th>
                    <th>Tanggal Surat</th>
                    <th style="width: 180px; text-align: center;">Override Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($letters as $l)
                <tr>
                    <td style="font-family: monospace; font-weight: 700;">{{ $l->letter_number }}</td>
                    <td style="font-weight: 700; color: var(--text-primary);">{{ $l->subject }}</td>
                    <td>
                        <span class="badge" style="background: rgba(14, 165, 233, 0.1); color: #0369a1; font-weight: 600;">{{ $l->type }}</span>
                    </td>
                    <td>{{ $l->destination ?? '-' }}</td>
                    <td style="font-size: 0.8125rem; color: var(--text-secondary);">{{ $l->date ? \Carbon\Carbon::parse($l->date)->format('d-m-Y') : '-' }}</td>
                    <td>
                        <div style="display: flex; gap: 0.35rem; justify-content: center; align-items: center;">
                            <a href="{{ route('pengurus.letters.edit', $l->id) }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.35rem 0.65rem; font-size: 0.75rem; font-weight: 700; color: var(--text-primary); text-decoration: none; border-radius: 6px; display: inline-flex; align-items: center; gap: 0.15rem;"><i class="ph ph-pencil-simple"></i> Edit</a>
                            <form action="{{ route('admin.monitor.override.delete', ['model' => 'letter', 'id' => $l->id]) }}" method="POST" onsubmit="return confirm('Hapus paksa surat ini? File arsip terkait di disk juga akan dilepas.');" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 0.35rem 0.65rem; font-size: 0.75rem; font-weight: 700; border-radius: 6px; display: inline-flex; align-items: center; gap: 0.15rem;"><i class="ph ph-trash"></i> Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-secondary py-4">Tidak ada data persuratan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top: 1rem;">
        {{ $letters->appends(['letters_page' => $letters->currentPage()])->links('shared.pagination') }}
    </div>
</div>
@endsection
