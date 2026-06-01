@extends('layouts.app')

@section('title', 'Laporan Keuangan')
@section('header', 'Monitoring Keuangan UKM')

@section('content')
<div style="margin-bottom: 1.5rem;">
    <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-primary); margin: 0 0 0.25rem 0;">Monitoring Keuangan UKM</h3>
    <p style="margin: 0; color: var(--text-secondary); font-size: 0.875rem; line-height: 1.5;">Super Admin memonitor transaksi masuk, keluar, dan saldo kas seluruh UKM.</p>
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
        
        <div style="flex: 2; min-width: 250px; display: flex; gap: 0.5rem; align-items: flex-end;">
            <div style="position: relative; flex: 1;">
                <label class="form-label" style="font-weight: 700; font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 0.35rem; display: block;">Cari Transaksi</label>
                <i class="ph ph-magnifying-glass" style="position: absolute; left: 0.85rem; top: calc(50% + 0.4rem); transform: translateY(-50%); color: var(--text-secondary); font-size: 1rem;"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari keterangan..." class="form-control" style="padding-left: 2.25rem; height: 2.5rem; font-size: 0.875rem;">
            </div>
            <button type="submit" class="btn btn-primary" style="height: 2.5rem; padding: 0 1rem; font-weight: 700; border-radius: 8px;">Cari</button>
        </div>

        @if(request()->filled('ukm_id') || request()->filled('search'))
            <a href="{{ request()->url() }}" class="btn" style="background: var(--bg-color); border: 1px solid var(--border-color); padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; text-decoration: none; color: var(--text-primary); font-size: 0.875rem; display: flex; align-items: center; gap: 0.25rem; height: 2.5rem;"><i class="ph ph-x-circle"></i> Reset</a>
        @endif
    </form>
</div>

<!-- Table Card -->
<div class="card" style="padding: 1.5rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
        <h4 style="margin: 0; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem; color: var(--text-primary);">
            <i class="ph ph-money" style="color: var(--accent-color);"></i> Log Transaksi Keuangan
        </h4>
        <span style="font-size: 0.8125rem; color: var(--text-secondary); font-weight: 600;">
            Total: {{ $finances->total() }} Transaksi
        </span>
    </div>

    <div class="table-wrapper" style="margin-bottom: 0; border: none; padding: 0; box-shadow: none;">
        <table class="table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Keterangan</th>
                    <th>UKM</th>
                    <th>Tipe</th>
                    <th>Jumlah</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($finances as $f)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($f->transaction_date)->format('d M Y') }}</td>
                    <td style="font-weight: 600; color: var(--text-primary);">{{ $f->title }}</td>
                    <td style="font-weight: 600; color: var(--accent-color);">{{ $f->ukm->name }}</td>
                    <td>
                        <span class="badge {{ $f->type == 'income' ? 'badge-approved' : 'badge-danger' }}">
                            {{ $f->type == 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                        </span>
                    </td>
                    <td style="font-weight: 700; color: {{ $f->type == 'income' ? '#03a85a' : '#dc2626' }};">
                        Rp {{ number_format($f->amount, 0, ',', '.') }}
                    </td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <button onclick="editFinance({{ json_encode($f) }})" class="btn" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; font-weight: 600; background: var(--bg-color); color: var(--accent-color); border: 1px solid var(--border-color);">
                                <i class="ph ph-pencil"></i> Edit
                            </button>
                            <form action="/admin/finances/{{ $f->id }}" method="POST" onsubmit="return confirm('Hapus transaksi ini?')" style="display: inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; font-weight: 600;">
                                    <i class="ph ph-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
                @if($finances->isEmpty())
                <tr><td colspan="6" class="text-secondary text-center py-8">Tidak ada data keuangan.</td></tr>
                @endif
            </tbody>
        </table>
    </div>
    <div style="margin-top: 1.25rem;">
        {{ $finances->links('shared.pagination') }}
    </div>
</div>

<!-- Modal Edit -->
<div id="modal-edit-finance" class="modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); align-items: center; justify-content: center; z-index: 1000; backdrop-filter: blur(4px);">
    <div class="card" style="width: 100%; max-width: 500px; margin: 1rem;">
        <h3 style="font-weight: 700; margin-bottom: 1.5rem; color: var(--text-primary);">Edit Transaksi Keuangan</h3>
        <form id="edit-form-finance" method="POST">
            @csrf @method('PUT')
            <div style="margin-bottom: 1rem;">
                <label class="form-label mb-2">Keterangan</label>
                <input type="text" name="title" id="edit-title" class="form-control" required>
            </div>
            <div style="margin-bottom: 1rem;">
                <label class="form-label mb-2">Tipe</label>
                <select name="type" id="edit-type" class="form-control" required>
                    <option value="income">Pemasukan (Income)</option>
                    <option value="expense">Pengeluaran (Expense)</option>
                </select>
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label class="form-label mb-2">Jumlah (Rp)</label>
                <input type="number" name="amount" id="edit-amount" class="form-control" required>
            </div>
            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <button type="button" onclick="document.getElementById('modal-edit-finance').style.display='none'" class="btn" style="background: var(--bg-color);">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function editFinance(f) {
    document.getElementById('edit-form-finance').action = '/admin/finances/' + f.id;
    document.getElementById('edit-title').value = f.title;
    document.getElementById('edit-type').value = f.type;
    document.getElementById('edit-amount').value = f.amount;
    document.getElementById('modal-edit-finance').style.display = 'flex';
}
</script>
@endsection
