@extends('layouts.app')

@section('title', 'Keuangan Kas')
@section('header', 'Keuangan Kas PSUP')

@section('content')
@if(session('success'))
    <div class="card mb-4 animate-fade-in" style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 1rem 1.5rem; border-radius: var(--radius-md); font-weight: 600;">
        <i class="ph-fill ph-check-circle" style="font-size: 1.15rem; vertical-align: middle; margin-right: 0.5rem;"></i>
        {{ session('success') }}
    </div>
@endif

<!-- Cash Balance Header Card -->
<div class="card mb-6" style="padding: 1.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; background: linear-gradient(135deg, var(--accent-color) 0%, #1e3a8a 100%); color: white;">
    <div>
        <h4 style="margin: 0; font-size: 0.8125rem; font-weight: 700; opacity: 0.85; text-transform: uppercase; letter-spacing: 0.05em;">Total Saldo Kas Bersih PSUP</h4>
        <div style="font-size: 2.25rem; font-weight: 800; margin-top: 0.25rem; font-family: 'Outfit', sans-serif;">
            Rp {{ number_format($netBalance, 0, ',', '.') }}
        </div>
    </div>
    
    <div style="display: flex; gap: 0.5rem;">
        <a href="{{ route('pengurus.finances.create') }}" class="btn" style="background: white; color: var(--accent-color); padding: 0.65rem 1.25rem; font-weight: 700; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 0.25rem;">
            <i class="ph ph-plus"></i> Tambah Transaksi
        </a>
    </div>
</div>

<div class="card" style="padding: 1.5rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 1rem;">
        <h4 style="margin: 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
            <i class="ph ph-wallet" style="color: var(--accent-color);"></i> Buku Kas & Mutasi
        </h4>
        <span style="font-size: 0.8125rem; color: var(--text-secondary); font-weight: 600;">
            Total: {{ $finances->total() }} Transaksi
        </span>
    </div>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('pengurus.finances.index') }}" style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap; margin-bottom: 1.25rem;">
        <div style="width: 200px;">
            <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Kategori</label>
            <select name="category_id" class="form-control" style="padding: 0.5rem 0.75rem; width: 100%; border-radius: 8px; border: 1px solid var(--border-color);" onchange="this.form.submit()">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <div style="width: 200px;">
            <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Jenis Aliran</label>
            <select name="type" class="form-control" style="padding: 0.5rem 0.75rem; width: 100%; border-radius: 8px; border: 1px solid var(--border-color);" onchange="this.form.submit()">
                <option value="">Semua Jenis</option>
                <option value="income" {{ request('type') === 'income' ? 'selected' : '' }}>Pemasukan</option>
                <option value="expense" {{ request('type') === 'expense' ? 'selected' : '' }}>Pengeluaran</option>
            </select>
        </div>

        @if(request()->anyFilled(['category_id', 'type']))
            <a href="{{ route('pengurus.finances.index') }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; text-decoration: none; color: var(--text-primary); font-size: 0.875rem; display: flex; align-items: center; gap: 0.25rem; height: 2.5rem;"><i class="ph ph-x-circle"></i> Reset</a>
        @endif
    </form>

    <div class="table-wrapper" style="margin-bottom: 0; border: none; padding: 0; box-shadow: none;">
        <table class="table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Transaksi</th>
                    <th>Kategori & Deskripsi</th>
                    <th>Digunakan Untuk</th>
                    <th>Jenis</th>
                    <th>Nominal</th>
                    <th style="width: 150px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($finances as $fin)
                <tr>
                    <td style="color: var(--text-secondary);">{{ date('d-m-Y', strtotime($fin->transaction_date)) }}</td>
                    <td style="font-weight: 600; color: var(--text-primary);">{{ $fin->title }}</td>
                    <td style="color: var(--text-secondary);">
                        <div style="font-size: 0.85rem; font-weight: 600; color: var(--text-primary);">{{ $fin->category->name ?? '-' }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-secondary);">{{ $fin->description }}</div>
                    </td>
                    <td>
                        @if($fin->used_for === 'Program Kerja')
                            <span class="badge" style="background: rgba(30, 64, 175, 0.1); color: #1e40af; font-size: 0.75rem; padding: 0.25rem 0.5rem; border-radius: 4px;">Proker: {{ $fin->program->name ?? '-' }}</span>
                        @else
                            <span class="badge" style="background: rgba(107, 114, 128, 0.1); color: #4b5563; font-size: 0.75rem; padding: 0.25rem 0.5rem; border-radius: 4px;">Umum</span>
                        @endif
                    </td>
                    <td>
                        @if($fin->type === 'income')
                            <span class="badge badge-approved" style="background: rgba(16, 185, 129, 0.1); color: var(--success-color);">Pemasukan</span>
                        @else
                            <span class="badge badge-danger" style="background: rgba(239, 68, 68, 0.1); color: var(--danger-color);">Pengeluaran</span>
                        @endif
                    </td>
                    <td style="font-weight: 800; color: {{ $fin->type === 'income' ? 'var(--success-color)' : 'var(--danger-color)' }};">
                        {{ $fin->type === 'income' ? '+' : '-' }} Rp {{ number_format($fin->amount, 0, ',', '.') }}
                    </td>
                    <td>
                        <div style="display: flex; gap: 0.5rem; justify-content: center;">
                            <a href="{{ route('pengurus.finances.edit', $fin->id) }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.4rem 0.8rem; font-size: 0.8rem; font-weight: 600; color: var(--text-primary); text-decoration: none;"><i class="ph ph-pencil-simple"></i> Edit</a>
                            <form action="{{ route('pengurus.finances.destroy', $fin->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?');" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; font-weight: 600;"><i class="ph ph-trash"></i> Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-secondary py-4">Belum ada transaksi tercatat dalam buku kas.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div style="margin-top: 1.25rem;">
        {{ $finances->links('shared.pagination') }}
    </div>
</div>
@endsection
