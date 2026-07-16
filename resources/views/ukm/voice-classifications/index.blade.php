@extends('layouts.app')

@section('title', 'Klasifikasi Suara')
@section('header', 'Klasifikasi Suara PSUP')

@section('content')
@if(session('success'))
    <div class="card mb-4 animate-fade-in" style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 1rem 1.5rem; border-radius: var(--radius-md); font-weight: 600;">
        <i class="ph-fill ph-check-circle" style="font-size: 1.15rem; vertical-align: middle; margin-right: 0.5rem;"></i>
        {{ session('success') }}
    </div>
@endif

<div style="margin-bottom: 1.5rem;">
    <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">Manajemen Klasifikasi Suara</h3>
    <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Atur jenis klasifikasi suara (seperti Sopran, Alto, Tenor, Bass) untuk mengorganisasikan penyanyi dalam paduan suara.</p>
</div>

<style>
    @media (max-width: 768px) {
        .table {
            display: table !important;
            table-layout: fixed !important;
            width: 100% !important;
        }
        thead, tbody, tr {
            min-width: auto !important;
            display: table-row-group !important;
        }
        thead {
            display: table-header-group !important;
        }
        tr {
            display: table-row !important;
        }
        .table td, .table th {
            padding: 0.65rem 0.5rem !important;
            font-size: 0.8rem !important;
            word-wrap: break-word !important;
            white-space: normal !important;
        }
    }
</style>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Form Add -->
    <div class="card" style="padding: 1.5rem; height: fit-content;">
        <h4 style="margin: 0 0 1.25rem 0; font-weight: 800; font-size: 1rem; color: var(--text-primary); display: flex; align-items: center; gap: 0.35rem;">
            <i class="ph ph-plus-circle" style="color: var(--accent-color);"></i> Tambah Jenis Suara
        </h4>
        
        <form action="{{ route('ukm.voice-classifications.store') }}" method="POST">
            @csrf
            <div class="form-group mb-4">
                <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.5rem; display: block;">Nama Klasifikasi Suara</label>
                <input type="text" name="name" class="form-control" placeholder="Contoh: Tenor" required style="padding: 0.65rem;">
                @error('name') <p style="color: var(--danger-color); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%; font-weight: bold; padding: 0.65rem 1rem; border-radius: 8px;">
                <i class="ph ph-plus"></i> Tambah Klasifikasi
            </button>
        </form>
    </div>

    <!-- Table List -->
    <div class="card md:col-span-2" style="padding: 1.5rem;">
        <h4 style="margin: 0 0 1.25rem 0; font-weight: 800; font-size: 1rem; color: var(--text-primary); display: flex; align-items: center; gap: 0.35rem;">
            <i class="ph ph-microphone-stage" style="color: var(--accent-color);"></i> Daftar Klasifikasi Suara
        </h4>
        
        <div class="table-wrapper" style="margin-bottom: 0; border: none; padding: 0; box-shadow: none;">
            <table class="table" style="vertical-align: middle; width: 100%;">
                <thead>
                    <tr>
                        <th>Nama Klasifikasi</th>
                        <th style="width: 80px; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($classifications as $c)
                    <tr>
                        <td style="font-weight: 600; color: var(--text-primary);">{{ $c->name }}</td>
                        <td style="text-align: right;">
                            <form action="{{ route('ukm.voice-classifications.destroy', $c->id) }}" method="POST" onsubmit="return confirm('Hapus klasifikasi suara ini? Anggota yang menggunakan klasifikasi ini akan di-reset.')" style="margin: 0; display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; padding: 0; border-radius: 6px; font-size: 0.95rem;" title="Hapus">
                                    <i class="ph ph-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="text-center text-secondary py-4">Belum ada klasifikasi suara yang ditambahkan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
