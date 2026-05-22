<!DOCTYPE html>
<html>
<head>
    <title>Laporan Kegiatan - {{ $event->title }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; line-height: 1.6; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 18px; }
        .header p { margin: 5px 0 0 0; font-style: italic; }
        .section { margin-bottom: 20px; }
        .section-title { font-weight: bold; font-size: 14px; border-bottom: 1px solid #ddd; margin-bottom: 10px; padding-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f9f9f9; }
        .text-right { text-align: right; }
        .footer { margin-top: 50px; text-align: right; }
        .badge { padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: bold; }
        .badge-income { background: #dcfce7; color: #166534; }
        .badge-expense { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN KEGIATAN</h1>
        <p>UKM {{ $ukm->name }}</p>
    </div>

    <div class="section">
        <div class="section-title">A. DETAIL KEGIATAN</div>
        <table>
            <tr>
                <th style="width: 30%;">Nama Kegiatan</th>
                <td>{{ $event->title }}</td>
            </tr>
            <tr>
                <th>Waktu</th>
                <td>{{ \Carbon\Carbon::parse($event->start_date)->format('d F Y, H:i') }} s/d {{ \Carbon\Carbon::parse($event->end_date)->format('d F Y, H:i') }}</td>
            </tr>
            <tr>
                <th>Tempat / Lokasi</th>
                <td>{{ $event->location ?? '-' }}</td>
            </tr>
            <tr>
                <th>Deskripsi</th>
                <td>{{ $event->description ?? 'Tidak ada deskripsi.' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">B. DAFTAR PESERTA</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama Anggota</th>
                    <th>Fakultas</th>
                    <th>Status di UKM</th>
                </tr>
            </thead>
            <tbody>
                @foreach($event->participants as $index => $p)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $p->user->name }}</td>
                    <td>{{ $p->user->faculty ?? '-' }}</td>
                    <td>{{ ucfirst($p->role_in_ukm) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">C. RINCIAN KEUANGAN KEGIATAN</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Keterangan</th>
                    <th>Jenis</th>
                    <th>Nominal (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @php 
                    $income = 0;
                    $expense = 0;
                @endphp
                @foreach($event->finances as $index => $f)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $f->title }}</td>
                    <td>
                        <span class="badge {{ $f->type == 'income' ? 'badge-income' : 'badge-expense' }}">
                            {{ $f->type == 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                        </span>
                    </td>
                    <td class="text-right">{{ number_format($f->amount, 0, ',', '.') }}</td>
                </tr>
                @php
                    if($f->type == 'income') $income += $f->amount;
                    else $expense += $f->amount;
                @endphp
                @endforeach
                <tr>
                    <th colspan="3" class="text-right">Total Pemasukan</th>
                    <th class="text-right">Rp {{ number_format($income, 0, ',', '.') }}</th>
                </tr>
                <tr>
                    <th colspan="3" class="text-right">Total Pengeluaran</th>
                    <th class="text-right">Rp {{ number_format($expense, 0, ',', '.') }}</th>
                </tr>
                <tr>
                    <th colspan="3" class="text-right">Saldo Kegiatan</th>
                    <th class="text-right">Rp {{ number_format($income - $expense, 0, ',', '.') }}</th>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
        <br><br><br>
        <p><strong>Admin UKM</strong></p>
    </div>
</body>
</html>
