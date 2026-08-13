@extends('layouts.app')

@section('title', 'Arsip Surat')
@section('header', 'Arsip Surat PSUP')

@section('content')
@if(session('success'))
    <div class="card mb-4 animate-fade-in" style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 1rem 1.5rem; border-radius: var(--radius-md); font-weight: 600;">
        <i class="ph-fill ph-check-circle" style="font-size: 1.15rem; vertical-align: middle; margin-right: 0.5rem;"></i>
        {{ session('success') }}
    </div>
@endif

<div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">Arsip & Persuratan</h3>
        <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Kelola nomor registrasi surat masuk, surat keluar, delegasi lomba, dan permohonan peminjaman ruangan.</p>
    </div>
    @if(!false)
    <a href="{{ route('pengurus.letters.create') }}" class="btn btn-primary" style="padding: 0.65rem 1.25rem; font-weight: 700; border-radius: 10px; display: inline-flex; align-items: center; gap: 0.35rem; text-decoration: none;">
        <i class="ph ph-plus"></i> Arsipkan Surat
    </a>
    @endif
</div>

<style>
    /* ── Filter form: stack on mobile ── */
    @media (max-width: 768px) {
        .letter-filter-form {
            flex-direction: column !important;
            gap: 0.75rem !important;
        }
        .letter-filter-form > div {
            width: 100% !important;
            min-width: 0 !important;
            flex: none !important;
        }
        .letter-search-group {
            flex-direction: column !important;
            gap: 0.5rem !important;
        }
        .letter-search-group button {
            width: 100% !important;
            height: 2.5rem !important;
        }
    }

    /* ── Desktop: show table, hide cards ── */
    .letter-table-wrapper { display: block; }
    .letter-card-list    { display: none; }

    /* ── Mobile: hide table, show cards ── */
    @media (max-width: 768px) {
        .letter-table-wrapper { display: none !important; }
        .letter-card-list    { display: flex !important; flex-direction: column; gap: 0.75rem; }

        .letter-card {
            background: var(--surface-color);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1rem 1rem 0.75rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        .letter-card-number {
            font-family: monospace;
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--text-muted);
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }
        .letter-card-subject {
            font-size: 0.9375rem;
            font-weight: 800;
            color: var(--text-primary);
            line-height: 1.35;
        }
        .letter-card-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.35rem;
            align-items: center;
        }
        .letter-card-dest {
            font-size: 0.8rem;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        .letter-card-footer {
            display: flex;
            gap: 0.5rem;
            align-items: center;
            padding-top: 0.6rem;
            border-top: 1px solid var(--border-color);
            margin-top: 0.25rem;
        }
        .btn-pdf-mobile {
            flex: 1;
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
            padding: 0.55rem 0.75rem;
            font-size: 0.8125rem;
            font-weight: 700;
            border-radius: 8px;
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            color: #475569;
            text-decoration: none;
        }
        .btn-icon-edit {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px; height: 38px;
            border-radius: 8px;
            background: var(--bg-color);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            text-decoration: none;
            font-size: 1.05rem;
            flex-shrink: 0;
        }
        .btn-icon-del {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px; height: 38px;
            border-radius: 8px;
            background: #fef2f2;
            border: 1px solid #fee2e2;
            color: #dc2626;
            cursor: pointer;
            font-size: 1.05rem;
            flex-shrink: 0;
        }
    }
</style>

<div class="card" style="padding: 1.5rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 1rem;">
        <h4 style="margin: 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
            <i class="ph ph-envelope" style="color: var(--accent-color);"></i> Dokumen Surat
        </h4>
        <span style="font-size: 0.8125rem; color: var(--text-secondary); font-weight: 600;">
            Total: {{ $letters->total() }} Surat
        </span>
    </div>

    {{-- Filter Form --}}
    <form method="GET" action="{{ route('pengurus.letters.index') }}" class="letter-filter-form" style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap; margin-bottom: 1.25rem;">
        <div style="width: 200px;">
            <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Jenis Surat</label>
            <select name="type" class="form-control" style="padding: 0.5rem 0.75rem; width: 100%; border-radius: 8px; border: 1px solid var(--border-color);" onchange="this.form.submit()">
                <option value="">Semua Jenis</option>
                @foreach($types as $t)
                    <option value="{{ $t }}" {{ request('type') === $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>
        </div>

        <div class="letter-search-group" style="flex: 2; min-width: 250px; display: flex; gap: 0.5rem; align-items: flex-end;">
            <div style="position: relative; flex: 1;">
                <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Cari Perihal / No. Surat</label>
                <i class="ph ph-magnifying-glass" style="position: absolute; left: 0.85rem; top: calc(50% + 0.4rem); transform: translateY(-50%); color: var(--text-secondary); font-size: 1rem;"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari perihal atau nomor surat..." class="form-control" style="padding-left: 2.25rem; height: 2.5rem; font-size: 0.875rem;">
            </div>
            <button type="submit" class="btn btn-primary" style="height: 2.5rem; padding: 0 1rem; font-weight: 700; border-radius: 8px;">Cari</button>
        </div>

        @if(request()->anyFilled(['type', 'search']))
            <a href="{{ route('pengurus.letters.index') }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; text-decoration: none; color: var(--text-primary); font-size: 0.875rem; display: flex; align-items: center; gap: 0.25rem; height: 2.5rem;">
                <i class="ph ph-x-circle"></i> Reset
            </a>
        @endif
    </form>

    {{-- ══════════════ DESKTOP TABLE ══════════════ --}}
    <div class="letter-table-wrapper">
        <div class="table-wrapper" style="margin-bottom: 0; border: none; padding: 0; box-shadow: none;">
            <table class="table">
                <thead>
                    <tr>
                        <th>No. Surat</th>
                        <th>Perihal</th>
                        <th>Jenis</th>
                        <th>Kategori</th>
                        <th>Tujuan/Asal</th>
                        <th>Tanggal</th>
                        <th>Berkas PDF</th>
                        <th style="width: 150px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($letters as $let)
                    <tr>
                        <td style="font-family: monospace; font-weight: bold; color: var(--text-primary);">{{ $let->letter_number }}</td>
                        <td style="font-weight: 700; color: var(--text-primary);">{{ $let->subject }}</td>
                        <td>
                            <span class="badge" style="background: rgba(14, 165, 233, 0.1); color: #0369a1; font-weight: 600;">{{ $let->type }}</span>
                        </td>
                        <td>
                            @if($let->related_to === 'Program Kerja')
                                <span class="badge" style="background: rgba(30, 64, 175, 0.1); color: #1e40af; font-size: 0.75rem; padding: 0.25rem 0.5rem; border-radius: 4px;">Proker: {{ $let->program->name ?? '-' }}</span>
                            @else
                                <span class="badge" style="background: rgba(107, 114, 128, 0.1); color: #4b5563; font-size: 0.75rem; padding: 0.25rem 0.5rem; border-radius: 4px;">Umum</span>
                            @endif
                        </td>
                        <td style="color: var(--text-secondary);">{{ $let->destination }}</td>
                        <td style="color: var(--text-secondary);">{{ date('d-m-Y', strtotime($let->date)) }}</td>
                        <td>
                            @if($let->file_path)
                                <a href="{{ asset('storage/' . $let->file_path) }}" target="_blank" class="btn" style="background: #f1f5f9; border: 1px solid #cbd5e1; padding: 0.35rem 0.65rem; border-radius: 6px; font-size: 0.75rem; font-weight: 600; color: #475569; text-decoration: none; display: inline-flex; align-items: center; gap: 0.25rem;">
                                    <i class="ph ph-file-pdf" style="font-size: 1rem; color: #ef4444;"></i> Unduh PDF
                                </a>
                            @else
                                <span style="font-size: 0.75rem; color: var(--text-muted); font-style: italic;">Tidak ada berkas</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: 0.5rem; justify-content: center;">
                                <a href="{{ route('pengurus.letters.edit', $let->id) }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.4rem 0.8rem; font-size: 0.8rem; font-weight: 600; color: var(--text-primary); text-decoration: none;"><i class="ph ph-pencil-simple"></i> Edit</a>
                                <form action="{{ route('pengurus.letters.destroy', $let->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus surat ini?');" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; font-weight: 600;"><i class="ph ph-trash"></i> Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-secondary py-4">Belum ada surat yang terarsip.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ══════════════ MOBILE CARD LIST ══════════════ --}}
    <div class="letter-card-list">
        @forelse($letters as $let)
        <div class="letter-card">
            {{-- Nomor surat --}}
            <div class="letter-card-number"><i class="ph ph-hash"></i> {{ $let->letter_number }}</div>

            {{-- Perihal --}}
            <div class="letter-card-subject">{{ $let->subject }}</div>

            {{-- Badges --}}
            <div class="letter-card-meta">
                <span class="badge" style="background: rgba(14,165,233,0.1); color: #0369a1; font-weight: 700; font-size: 0.72rem;">{{ $let->type }}</span>
                @if($let->related_to === 'Program Kerja')
                    <span class="badge" style="background: rgba(30,64,175,0.08); color: #1e40af; font-size: 0.72rem; padding: 0.2rem 0.45rem; border-radius: 4px;">
                        Proker: {{ $let->program->name ?? '-' }}
                    </span>
                @else
                    <span class="badge" style="background: rgba(107,114,128,0.08); color: #4b5563; font-size: 0.72rem; padding: 0.2rem 0.45rem; border-radius: 4px;">Umum</span>
                @endif
                <span style="font-size: 0.75rem; color: var(--text-muted); margin-left: auto;">
                    {{ date('d M Y', strtotime($let->date)) }}
                </span>
            </div>

            {{-- Tujuan --}}
            @if($let->destination)
            <div class="letter-card-dest">
                <i class="ph ph-map-pin" style="color: var(--text-muted);"></i>
                {{ $let->destination }}
            </div>
            @endif

            {{-- Footer: PDF + Actions --}}
            <div class="letter-card-footer">
                @if($let->file_path)
                    <a href="{{ asset('storage/' . $let->file_path) }}" target="_blank" class="btn-pdf-mobile">
                        <i class="ph ph-file-pdf" style="color: #ef4444;"></i> Unduh PDF
                    </a>
                @else
                    <span style="flex:1; font-size:0.75rem; color:var(--text-muted); font-style:italic; display:inline-flex; align-items:center; gap:0.25rem;">
                        <i class="ph ph-file-dashed"></i> Tidak ada berkas
                    </span>
                @endif
                <a href="{{ route('pengurus.letters.edit', $let->id) }}" class="btn-icon-edit" title="Edit">
                    <i class="ph ph-pencil-simple"></i>
                </a>
                @if(!false)
                <form action="{{ route('pengurus.letters.destroy', $let->id) }}" method="POST" onsubmit="return confirm('Hapus surat ini?');" style="margin:0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-icon-del" title="Hapus"><i class="ph ph-trash"></i></button>
                </form>
                @endif
            </div>
        </div>
        @empty
        <div style="text-align:center; padding: 3rem 1rem; color: var(--text-muted);">
            <i class="ph ph-envelope-simple-open" style="font-size:2.5rem; display:block; margin-bottom:0.5rem;"></i>
            Belum ada surat yang terarsip.
        </div>
        @endforelse
    </div>

    <div style="margin-top: 1.25rem;">
        {{ $letters->links('shared.pagination') }}
    </div>
</div>
@endsection
