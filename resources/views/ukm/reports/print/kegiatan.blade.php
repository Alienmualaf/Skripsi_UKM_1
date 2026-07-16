<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Kegiatan — {{ $program->name }}</title>
<style>
* { box-sizing: border-box; }
@page { 
    size: A4; 
    margin: 1.5cm;
}
body { 
    font-family: 'Times New Roman', Times, serif; 
    font-size: 12pt; 
    color: #000; 
    line-height: 1.15; 
}
h1, h2, h3, h4, h5, h6, hr {
    margin: 0;
    padding: 0;
}
p {
    margin-top: 0;
    margin-bottom: 6pt;
    text-align: justify;
}
.kop-table { 
    width: 100%; 
    border: none; 
    margin-bottom: 5px; 
}
.kop-table td { 
    border: none; 
    padding: 0; 
    vertical-align: middle; 
}
.kop-logo { 
    height: 75px; 
    width: auto; 
}
.kop-tengah { 
    text-align: center; 
}
.kop-tengah h2 { 
    font-size: 14pt; 
    font-weight: bold; 
    margin: 0; 
    line-height: 1.2; 
    text-transform: uppercase;
}
.kop-tengah h3 { 
    font-size: 11pt; 
    font-weight: bold; 
    margin: 0; 
    line-height: 1.2; 
    text-transform: uppercase;
}
.kop-line { 
    border: none; 
    border-top: 2.5px solid #000; 
    border-bottom: 0.5px solid #000; 
    height: 4px; 
    margin-top: 2px; 
    margin-bottom: 18px; 
}
.report-title { 
    text-align: center; 
    font-size: 16pt; 
    font-weight: bold; 
    margin-bottom: 18px; 
    text-transform: uppercase; 
}
.section { 
    margin-bottom: 18px; 
}
.section-title { 
    font-size: 13pt; 
    font-weight: bold; 
    text-transform: uppercase; 
    border-bottom: 0.5pt solid #000; 
    padding-bottom: 3px; 
    margin-bottom: 8px; 
}
.info-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 12pt;
}
.info-table td {
    border: none;
    padding: 3px 0;
    vertical-align: top;
    font-size: 12pt;
}
.info-table td.label {
    width: 150px;
}
.info-table td.colon {
    width: 15px;
    text-align: center;
}
table.data-table { 
    width: 100%; 
    border-collapse: collapse; 
    font-size: 11pt; 
    margin-bottom: 12px; 
}
table.data-table th { 
    background: #f2f2f2; 
    font-weight: bold; 
    padding: 6px; 
    text-align: left; 
    border: 0.5pt solid #000; 
}
table.data-table td { 
    padding: 6px; 
    border: 0.5pt solid #000; 
    vertical-align: top;
}
.singers-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 12pt;
    font-size: 11pt;
}
.singers-table th {
    background: #f2f2f2;
    font-weight: bold;
    padding: 6px;
    text-align: center;
    border: 0.5pt solid #000;
}
.singers-table td {
    padding: 6px;
    border: 0.5pt solid #000;
    vertical-align: top;
    width: 25%;
}
.footer { 
    margin-top: 28px; 
    text-align: center; 
    font-size: 9pt; 
    color: #555; 
    border-top: 0.5pt solid #000; 
    padding-top: 8px; 
}
</style>
</head>
<body>

<table class="kop-table">
    <tr>
        <td style="width: 15%; text-align: left;">
            <img class="kop-logo" src="{{ public_path('images/senat.png') }}" alt="Logo Senat">
        </td>
        <td style="width: 70%; text-align: center;">
            <div class="kop-tengah">
                <h2>PADUAN SUARA UNIVERSITAS PANCASILA</h2>
                <h3>SENAT - KELUARGA MAHASISWA UNIVERSITAS PANCASILA</h3>
            </div>
        </td>
        <td style="width: 15%; text-align: right;">
            <img class="kop-logo" src="{{ public_path('images/logo_PSUP.jpeg') }}" alt="Logo PSUP">
        </td>
    </tr>
</table>
<hr class="kop-line">

<div class="report-title">
    Laporan Pertanggungjawaban Kegiatan<br>
    <span style="font-size:14pt; font-weight:bold;">"{{ $program->name }}"</span>
</div>

@if(in_array(strtolower($program->activity_type), ['event', 'internal']))

<div class="section">
    <div class="section-title">1. Nama &amp; Tempat Event</div>
    <table class="info-table">
        <tr>
            <td class="label">Nama Event</td>
            <td class="colon">:</td>
            <td>{{ $program->name }}</td>
        </tr>
        <tr>
            <td class="label">Tempat / Lokasi</td>
            <td class="colon">:</td>
            <td>{{ $program->venue ?? 'Sekretariat PSUP / Universitas Pancasila' }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Pelaksanaan</td>
            <td class="colon">:</td>
            <td>{{ $program->start_date ? date('d-m-Y', strtotime($program->start_date)) : '-' }}@if($program->end_date && $program->end_date !== $program->start_date) s.d. {{ date('d-m-Y', strtotime($program->end_date)) }}@endif</td>
        </tr>
        <tr>
            <td class="label">Keterangan</td>
            <td class="colon">:</td>
            <td>{{ $program->description ?? '-' }}</td>
        </tr>
    </table>
</div>

<div class="section">
    <div class="section-title">2. Keuangan</div>
    @php $income = $finances->where('type','income')->sum('amount'); $expense = $finances->where('type','expense')->sum('amount'); @endphp
    <table class="info-table" style="margin-bottom:12px;">
        <tr>
            <td class="label">Total Pemasukan</td>
            <td class="colon">:</td>
            <td>Rp {{ number_format($income, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Total Pengeluaran</td>
            <td class="colon">:</td>
            <td>Rp {{ number_format($expense, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Saldo Akhir</td>
            <td class="colon">:</td>
            <td style="font-weight: bold;">Rp {{ number_format($income - $expense, 0, ',', '.') }}</td>
        </tr>
    </table>
    @if($finances->count())
    <table class="data-table">
        <thead>
            <tr>
                <th style="width:5%;text-align:center;">No</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Jenis</th>
                <th style="text-align:right;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($finances as $i => $f)
            <tr>
                <td style="text-align:center;">{{ $i+1 }}</td>
                <td>{{ date('d-m-Y', strtotime($f->transaction_date)) }}</td>
                <td>{{ $f->description }}</td>
                <td>{{ $f->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}</td>
                <td style="text-align:right;">Rp {{ number_format($f->amount, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>

<div class="section">
    <div class="section-title">3. Persuratan</div>
    @if($letters->count())
    <table class="data-table">
        <thead>
            <tr>
                <th style="width:5%;text-align:center;">No</th>
                <th>No. Surat</th>
                <th>Tanggal</th>
                <th>Perihal</th>
                <th>Tujuan/Asal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($letters as $i => $l)
            <tr>
                <td style="text-align:center;">{{ $i+1 }}</td>
                <td>{{ $l->letter_number }}</td>
                <td>{{ date('d-m-Y', strtotime($l->date)) }}</td>
                <td>{{ $l->subject }}</td>
                <td>{{ $l->destination ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p style="font-style:italic;">Belum ada dokumen persuratan yang tertaut.</p>
    @endif
</div>

@else

<div class="section">
    <table class="info-table">
        <tr>
            <td class="label">Keterangan</td>
            <td class="colon">:</td>
            <td>Kegiatan {{ $program->activity_type === 'Competition' ? 'Lomba' : 'Penampilan / Job' }} {{ $program->name }} {{ $program->description ? '— ' . $program->description : '' }}</td>
        </tr>
        <tr>
            <td class="label">Hari/Tanggal</td>
            <td class="colon">:</td>
            <td>{{ $program->start_date ? date('d-m-Y', strtotime($program->start_date)) : '-' }}@if($program->end_date && $program->end_date !== $program->start_date) s.d. {{ date('d-m-Y', strtotime($program->end_date)) }}@endif</td>
        </tr>
        <tr>
            <td class="label">Penanggung Jawab</td>
            <td class="colon">:</td>
            <td>{{ $program->pic ?? '-' }}</td>
        </tr>
        @if($program->performance && $program->performance->classroom && $program->performance->classroom->trainer)
        <tr>
            <td class="label">Pelatih / Konduktor</td>
            <td class="colon">:</td>
            <td>{{ $program->performance->classroom->trainer->name }}</td>
        </tr>
        @endif
        <tr>
            <td class="label">Daftar Penyanyi</td>
            <td class="colon">:</td>
            <td>(Terlampir di bawah)</td>
        </tr>
    </table>
</div>

@php
    $mems = ($program->performance && $program->performance->classroom) ? $program->performance->classroom->members : null;
    $soprans = collect();
    $altos = collect();
    $tenors = collect();
    $basses = collect();
    if ($mems) {
        foreach($mems as $m) {
            $voiceName = strtolower($m->voiceClassification->name ?? '');
            if (str_contains($voiceName, 'sopran')) {
                $soprans->push($m);
            } elseif (str_contains($voiceName, 'alto')) {
                $altos->push($m);
            } elseif (str_contains($voiceName, 'tenor')) {
                $tenors->push($m);
            } elseif (str_contains($voiceName, 'bass')) {
                $basses->push($m);
            }
        }
    }
    $maxRows = max($soprans->count(), $altos->count(), $tenors->count(), $basses->count());
@endphp
<div class="section">
    <div class="section-title">Daftar Penyanyi yang Ikut</div>
    @if($mems && $mems->count() > 0)
    <table class="singers-table">
        <thead>
            <tr>
                <th>Sopran</th>
                <th>Alto</th>
                <th>Tenor</th>
                <th>Bass</th>
            </tr>
        </thead>
        <tbody>
            @for($i = 0; $i < $maxRows; $i++)
            <tr>
                <td>
                    {{ isset($soprans[$i]) ? ($i + 1) . '. ' . $soprans[$i]->name : '' }}
                </td>
                <td>
                    {{ isset($altos[$i]) ? ($i + 1) . '. ' . $altos[$i]->name : '' }}
                </td>
                <td>
                    {{ isset($tenors[$i]) ? ($i + 1) . '. ' . $tenors[$i]->name : '' }}
                </td>
                <td>
                    {{ isset($basses[$i]) ? ($i + 1) . '. ' . $basses[$i]->name : '' }}
                </td>
            </tr>
            @endfor
        </tbody>
    </table>
    @else
    <p style="font-style:italic;">Belum ada daftar penyanyi yang ditentukan.</p>
    @endif
</div>

<div class="section">
    <div class="section-title">Keuangan</div>
    @php $income = $finances->where('type','income')->sum('amount'); $expense = $finances->where('type','expense')->sum('amount'); @endphp
    <table class="info-table" style="margin-bottom:12px;">
        <tr>
            <td class="label">Total Pemasukan</td>
            <td class="colon">:</td>
            <td>Rp {{ number_format($income, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Total Pengeluaran</td>
            <td class="colon">:</td>
            <td>Rp {{ number_format($expense, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Saldo Akhir</td>
            <td class="colon">:</td>
            <td style="font-weight: bold;">Rp {{ number_format($income - $expense, 0, ',', '.') }}</td>
        </tr>
    </table>
    @if($finances->count())
    <table class="data-table">
        <thead>
            <tr>
                <th style="width:5%;text-align:center;">No</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Jenis</th>
                <th style="text-align:right;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($finances as $i => $f)
            <tr>
                <td style="text-align:center;">{{ $i+1 }}</td>
                <td>{{ date('d-m-Y', strtotime($f->transaction_date)) }}</td>
                <td>{{ $f->description }}</td>
                <td>{{ $f->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}</td>
                <td style="text-align:right;">Rp {{ number_format($f->amount, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>

<div class="section">
    <div class="section-title">Persuratan</div>
    @if($letters->count())
    <table class="data-table">
        <thead>
            <tr>
                <th style="width:5%;text-align:center;">No</th>
                <th>No. Surat</th>
                <th>Tanggal</th>
                <th>Perihal</th>
                <th>Tujuan/Asal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($letters as $i => $l)
            <tr>
                <td style="text-align:center;">{{ $i+1 }}</td>
                <td>{{ $l->letter_number }}</td>
                <td>{{ date('d-m-Y', strtotime($l->date)) }}</td>
                <td>{{ $l->subject }}</td>
                <td>{{ $l->destination ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p style="font-style:italic;">Belum ada dokumen persuratan yang tertaut.</p>
    @endif
</div>
@endif

<div class="footer">
    Laporan Resmi Paduan Suara Universitas Pancasila &bull; Dicetak tanggal {{ date('d-m-Y H:i') }} WIB
</div>
</body>
</html>
