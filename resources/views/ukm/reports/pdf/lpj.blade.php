<!DOCTYPE html>
<html>
<head>
    <title>LPJ Program Kerja - {{ $ukm->name }}</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; line-height: 1.4; color: #333; }
        .page-break { page-break-after: always; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 16px; }
        .section { margin-bottom: 25px; }
        .section-title { font-weight: bold; font-size: 13px; background: #eee; padding: 5px 10px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        th, td { border: 1px solid #999; padding: 6px; text-align: left; }
        th { background-color: #f5f5f5; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .appendix-title { font-size: 24px; font-weight: bold; text-align: center; margin-top: 200px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PERTANGGUNGJAWABAN (LPJ) PROGRAM KERJA</h1>
        <h2>UKM {{ strtoupper($ukm->name) }}</h2>
        <p>Periode: {{ isset($startDate) ? \Carbon\Carbon::parse($startDate)->format('d/m/Y') : '-' }} s/d {{ isset($endDate) ? \Carbon\Carbon::parse($endDate)->format('d/m/Y') : '-' }}</p>
    </div>

    <!-- 1. DAFTAR KEGIATAN -->
    <div class="section">
        <div class="section-title">I. DAFTAR KEGIATAN YANG TERLAKSANA</div>
        @forelse($events as $event)
        <div style="margin-bottom: 15px; border: 1px solid #ddd; padding: 10px; border-radius: 5px;">
            <strong>{{ $event->title }}</strong><br>
            <small>{{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }} | {{ $event->location ?? '-' }}</small>
            <p style="margin: 5px 0;">Peserta: 
                @php
                    $members = $event->participants()->where('role_in_ukm', '!=', 'admin')->get();
                @endphp
                @foreach($members as $p)
                    {{ $p->user->name }}{{ !$loop->last ? ',' : '' }}
                @endforeach
                ({{ $members->count() }} orang)
            </p>
        </div>
        @empty
        <p class="text-center text-secondary">Tidak ada kegiatan dalam periode ini.</p>
        @endforelse
    </div>

    <!-- 2. DAFTAR ANGGOTA AKTIF -->
    <div class="section">
        <div class="section-title">II. DAFTAR ANGGOTA AKTIF (NON-ADMIN)</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 40px;">No</th>
                    <th>Nama Anggota</th>
                    <th>Fakultas</th>
                    <th>Klasifikasi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($active_members as $index => $m)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $m->user->name }}</td>
                    <td>{{ $m->user->faculty ?? '-' }}</td>
                    <td>{{ optional($m->classification)->name ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center">Tidak ada data anggota aktif.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="page-break"></div>

    <!-- 3. LAPORAN KEUANGAN -->
    <div class="section">
        <div class="section-title">III. LAPORAN KEUANGAN PERIODE</div>
        
        <div style="background: #f9f9f9; padding: 10px; margin-bottom: 15px; border-radius: 5px;">
            <strong>Saldo Kas Awal (sebelum periode):</strong> 
            <span style="float: right; font-weight: bold;">Rp {{ number_format($kas_awal, 0, ',', '.') }}</span>
        </div>

        <strong>Rincian Transaksi:</strong>
        <table>
            <thead>
                <tr>
                    <th style="width: 80px;">Tanggal</th>
                    <th>Keterangan</th>
                    <th style="width: 70px;">Jenis</th>
                    <th style="width: 100px;">Nominal (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @php 
                    $total_in = 0;
                    $total_out = 0;
                @endphp
                @foreach($finances as $f)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($f->transaction_date)->format('d/m/Y') }}</td>
                    <td>{{ $f->title }}</td>
                    <td class="text-center">{{ $f->type == 'income' ? 'Masuk' : 'Keluar' }}</td>
                    <td class="text-right">{{ number_format($f->amount, 0, ',', '.') }}</td>
                </tr>
                @php
                    if($f->type == 'income') $total_in += $f->amount;
                    else $total_out += $f->amount;
                @endphp
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 15px; border-top: 2px solid #333; padding-top: 10px;">
            <table style="border: none;">
                <tr style="border: none;">
                    <td style="border: none;">Total Pemasukan Periode</td>
                    <td style="border: none;" class="text-right">Rp {{ number_format($total_in, 0, ',', '.') }}</td>
                </tr>
                <tr style="border: none;">
                    <td style="border: none;">Total Pengeluaran Periode</td>
                    <td style="border: none;" class="text-right">(Rp {{ number_format($total_out, 0, ',', '.') }})</td>
                </tr>
                <tr style="border: none; font-weight: bold; font-size: 12px; background: #eee;">
                    <td style="border: none;">SALDO KAS AKHIR</td>
                    <td style="border: none;" class="text-right">Rp {{ number_format($kas_awal + $total_in - $total_out, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- 4. DAFTAR INVENTARIS -->
    <div class="section">
        <div class="section-title">IV. DAFTAR INVENTARIS ORGANISASI</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 40px;">No</th>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Kondisi</th>
                    <th>Lokasi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inventories as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ ucfirst($item->condition) }}</td>
                    <td>{{ $item->location ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>



    <div class="footer" style="margin-top: 50px; text-align: right;">
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
        <br><br><br>
        <p><strong>Admin UKM</strong></p>
    </div>
</body>
</html>
