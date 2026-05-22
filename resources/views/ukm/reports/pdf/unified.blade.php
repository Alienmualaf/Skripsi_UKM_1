<!DOCTYPE html>
<html>
<head>
    <title>{{ $report_title }} - {{ $ukm->name }}</title>
    <style>
        @page {
            margin: 2.5cm;
        }
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12pt;
            line-height: 1.4;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .new-page {
            page-break-before: always;
        }
        .cover-page {
            text-align: center;
            padding-top: 5cm;
        }
        .cover-title { font-size: 20pt; font-weight: bold; text-transform: uppercase; }
        .cover-ukm { font-size: 18pt; font-weight: bold; margin-top: 10px; }
        .cover-period { font-size: 14pt; margin-top: 20px; }
        .section-title-page {
            text-align: center;
            padding-top: 8cm;
            page-break-before: always;
        }
        .section-title-page h1 {
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .content-section {
            page-break-before: always;
            padding-top: 0.5cm;
        }
        header {
            position: fixed;
            top: -1.5cm;
            left: 0;
            right: 0;
            height: 1cm;
            border-bottom: 1px solid #000;
            font-size: 10pt;
        }
        header .left { float: left; }
        header .right { float: right; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        .no-border, .no-border tr, .no-border td { border: none !important; }
        .footer {
            margin-top: 50px;
            text-align: right;
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
@php 
    $sectionCounter = 1; 
    $romans = ['', 'I', 'II', 'III', 'IV', 'V', 'VI'];
    $loopEvents = isset($event) ? collect([$event]) : ($events ?? collect([]));
@endphp

<div class="cover-page">
    <div class="cover-title">{{ $report_title }}</div>
    <div class="cover-ukm">UKM {{ strtoupper($ukm->name) }}</div>
    <div class="cover-period">
        @if(isset($event))
            Agenda: {{ $event->title }}
        @elseif(isset($startDate) && isset($endDate))
            Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
        @endif
    </div>
    <p style="margin-top: 8cm;">Universitas Pancasila</p>
</div>

<header>
    <div class="left">UKM {{ strtoupper($ukm->name) }}</div>
    <div class="right">{{ $report_title }}</div>
</header>

@if($type === 'events' || $type === 'lpj')
<div class="section-title-page">
    <h1>{{ $romans[$sectionCounter++] }}. KEGIATAN</h1>
</div>
<div class="content-section">
    @forelse($loopEvents as $e)
        <div class="bold" style="font-size: 14pt; margin-bottom: 10px;">{{ $e->title }}</div>
        <table class="no-border" style="width: auto;">
            <tr><td>Nama Kegiatan</td><td>: {{ $e->title }}</td></tr>
            <tr><td>Tanggal</td><td>: {{ \Carbon\Carbon::parse($e->start_date)->format('d F Y') }}</td></tr>
            <tr><td>Tempat</td><td>: {{ $e->location ?? '-' }}</td></tr>
        </table>
        <p class="bold" style="margin-top: 15px; margin-bottom: 5px;">Daftar Peserta:</p>
        <table>
            <thead>
                <tr>
                    <th style="width: 40px; text-align: center;">No</th>
                    <th>Nama Anggota</th>
                    <th>Klasifikasi</th>
                </tr>
            </thead>
            <tbody>
                @php $participants = $e->participants()->where('role_in_ukm', '!=', 'admin')->get(); @endphp
                @forelse($participants as $idx => $p)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td>{{ $p->user->name }}</td>
                    <td>{{ optional($p->classification)->name ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="3" class="text-center">Tidak ada data peserta.</td></tr>
                @endforelse
            </tbody>
        </table>
    @empty
        <p class="text-center">Tidak ada data kegiatan.</p>
    @endforelse
</div>
@endif

@if($type === 'finances' || $type === 'lpj' || $type === 'events')
<div class="section-title-page">
    <h1>{{ $romans[$sectionCounter++] }}. KEUANGAN</h1>
</div>
<div class="content-section">
    @if(isset($kas_awal))
        <p class="bold">Kas Awal: Rp {{ number_format($kas_awal, 0, ',', '.') }}</p>
    @endif
    <table>
        <thead>
            <tr>
                <th style="width: 40px; text-align: center;">No</th>
                <th style="width: 100px;">Tanggal</th>
                <th>Keterangan</th>
                <th style="width: 110px;">Pemasukan</th>
                <th style="width: 110px;">Pengeluaran</th>
            </tr>
        </thead>
        <tbody>
            @php $tIn = 0; $tOut = 0; @endphp
            @forelse($finances as $idx => $f)
            <tr>
                <td class="text-center">{{ $idx + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($f->transaction_date)->format('d/m/Y') }}</td>
                <td>{{ $f->title }}</td>
                <td class="text-right">{{ $f->type == 'income' ? number_format($f->amount, 0, ',', '.') : '-' }}</td>
                <td class="text-right">{{ $f->type == 'expense' ? number_format($f->amount, 0, ',', '.') : '-' }}</td>
            </tr>
            @php if($f->type == 'income') $tIn += $f->amount; else $tOut += $f->amount; @endphp
            @empty
            <tr><td colspan="5" class="text-center">Tidak ada data transaksi.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div style="margin-top: 20px;">
        <p>Total Pemasukan: Rp {{ number_format($tIn, 0, ',', '.') }}</p>
        <p>Total Pengeluaran: Rp {{ number_format($tOut, 0, ',', '.') }}</p>
        @if(isset($kas_awal))
            <br>
            <p class="bold">Kas Akhir: Rp {{ number_format($kas_awal + $tIn - $tOut, 0, ',', '.') }}</p>
        @endif
    </div>
</div>
@endif

@if($type === 'lpj')
<div class="section-title-page">
    <h1>{{ $romans[$sectionCounter++] }}. INVENTARIS</h1>
</div>
<div class="content-section">
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
            @forelse($inventories as $idx => $item)
            <tr>
                <td class="text-center">{{ $idx + 1 }}</td>
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
@endif

@if($type === 'memberships')
<div class="section-title-page">
    <h1>{{ $romans[$sectionCounter++] }}. DAFTAR ANGGOTA</h1>
</div>
<div class="content-section">
    <table>
        <thead>
            <tr>
                <th style="width: 40px; text-align: center;">No</th>
                <th>Nama Anggota</th>
                <th>Fakultas</th>
                <th>Klasifikasi</th>
            </tr>
        </thead>
        <tbody>
            @php $mLoop = $memberships ?? ($active_members ?? collect([])); @endphp
            @forelse($mLoop as $idx => $m)
            <tr>
                <td class="text-center">{{ $idx + 1 }}</td>
                <td>{{ $m->user->name }}</td>
                <td>{{ $m->user->faculty ?? '-' }}</td>
                <td>{{ optional($m->classification)->name ?? '-' }}</td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center">Tidak ada data anggota.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endif

<div class="new-page">
    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
        <br><br><br>
        <p><strong>Admin UKM {{ $ukm->name }}</strong></p>
    </div>
</div>
</body>
</html>
