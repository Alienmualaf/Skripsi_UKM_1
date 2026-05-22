<!DOCTYPE html>
<html>
<head>
    <title>LPJ - {{ $ukm->name }}</title>
    <style>
        @page {
            margin: 2.5cm;
        }
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        
        .header {
            text-align: center;
            margin-bottom: 50px;
        }
        .header h1 {
            font-size: 18pt;
            margin: 0;
            text-transform: uppercase;
        }
        .header h2 {
            font-size: 16pt;
            margin: 10px 0;
        }

        .section {
            page-break-before: always;
            clear: both;
        }
        .section:first-child {
            page-break-before: avoid;
        }
        
        .section-title {
            font-size: 16pt;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            margin-bottom: 30px;
        }

        .sub-section {
            margin-bottom: 30px;
        }
        .sub-title {
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }

        .summary-box {
            margin-top: 20px;
        }
        .summary-row {
            margin-bottom: 5px;
        }

        .no-border, .no-border tr, .no-border td {
            border: none !important;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PERTANGGUNGJAWABAN (LPJ)</h1>
        <h2>UKM {{ strtoupper($ukm->name) }}</h2>
        <p>Universitas Pancasila</p>
        <p>Periode: {{ isset($startDate) ? \Carbon\Carbon::parse($startDate)->format('d/m/Y') : '-' }} s/d {{ isset($endDate) ? \Carbon\Carbon::parse($endDate)->format('d/m/Y') : '-' }}</p>
    </div>

    <!-- I. KEGIATAN -->
    <div class="section">
        <div class="section-title">I. KEGIATAN</div>
        
        @forelse($events as $event)
        <div class="sub-section">
            <div class="sub-title">{{ $event->title }}</div>
            <table class="no-border" style="width: auto; margin-bottom: 10px;">
                <tr>
                    <td style="padding: 2px 8px 2px 0;">Nama Kegiatan</td>
                    <td style="padding: 2px 8px;">: {{ $event->title }}</td>
                </tr>
                <tr>
                    <td style="padding: 2px 8px 2px 0;">Tanggal</td>
                    <td style="padding: 2px 8px;">: {{ \Carbon\Carbon::parse($event->start_date)->format('d F Y') }}</td>
                </tr>
                <tr>
                    <td style="padding: 2px 8px 2px 0;">Tempat</td>
                    <td style="padding: 2px 8px;">: {{ $event->location ?? '-' }}</td>
                </tr>
            </table>

            <p class="bold" style="margin-bottom: 5px;">Peserta:</p>
            <table>
                <thead>
                    <tr>
                        <th style="width: 40px; text-align: center;">No</th>
                        <th>Nama Anggota</th>
                        <th>Klasifikasi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $participants = $event->participants()->where('role_in_ukm', '!=', 'admin')->get();
                    @endphp
                    @forelse($participants as $index => $p)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $p->user->name }}</td>
                        <td>{{ optional($p->classification)->name ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center">Tidak ada data peserta.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @empty
        <p class="text-center">Tidak ada kegiatan dalam periode ini.</p>
        @endforelse
    </div>

    <!-- II. KEUANGAN -->
    <div class="section">
        <div class="section-title">II. KEUANGAN</div>
        
        <p class="bold">Kas Awal: Rp {{ number_format($kas_awal, 0, ',', '.') }}</p>

        <table>
            <thead>
                <tr>
                    <th style="width: 40px; text-align: center;">No</th>
                    <th style="width: 100px;">Tanggal</th>
                    <th>Keterangan</th>
                    <th style="width: 120px;">Pemasukan</th>
                    <th style="width: 120px;">Pengeluaran</th>
                </tr>
            </thead>
            <tbody>
                @php 
                    $total_in = 0;
                    $total_out = 0;
                @endphp
                @foreach($finances as $index => $f)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($f->transaction_date)->format('d/m/Y') }}</td>
                    <td>{{ $f->title }}</td>
                    <td class="text-right">{{ $f->type == 'income' ? number_format($f->amount, 0, ',', '.') : '-' }}</td>
                    <td class="text-right">{{ $f->type == 'expense' ? number_format($f->amount, 0, ',', '.') : '-' }}</td>
                </tr>
                @php
                    if($f->type == 'income') $total_in += $f->amount;
                    else $total_out += $f->amount;
                @endphp
                @endforeach
            </tbody>
        </table>

        <div class="summary-box">
            <p>Total Pemasukan: Rp {{ number_format($total_in, 0, ',', '.') }}</p>
            <p>Total Pengeluaran: Rp {{ number_format($total_out, 0, ',', '.') }}</p>
            <br>
            <p class="bold">Kas Akhir:</p>
            <p class="bold">Rp {{ number_format($kas_awal + $total_in - $total_out, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- III. INVENTARIS -->
    <div class="section">
        <div class="section-title">III. INVENTARIS</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 40px; text-align: center;">No</th>
                    <th>Nama Barang</th>
                    <th style="width: 80px; text-align: center;">Jumlah</th>
                    <th>Kondisi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inventories as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->name }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td>{{ ucfirst($item->condition) }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center">Tidak ada data inventaris.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer text-right" style="margin-top: 50px;">
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
        <br><br><br>
        <p><strong>Admin UKM {{ $ukm->name }}</strong></p>
    </div>
</body>
</html>
