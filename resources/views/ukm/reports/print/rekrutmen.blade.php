<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Rekrutmen Anggota PSUP</title>
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
.stats-table { 
    width: 100%; 
    border: none; 
    margin-bottom: 18px; 
}
.stats-table td { 
    border: 0.5pt solid #000; 
    padding: 8px; 
    text-align: center; 
    background: #f9f9f9; 
}
.stat-num { 
    font-size: 14pt; 
    font-weight: bold; 
}
.stat-lbl { 
    font-size: 8pt; 
    font-weight: bold; 
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
table.data-table { 
    width: 100%; 
    border-collapse: collapse; 
    font-size: 11pt; 
    margin-bottom: 8px; 
}
table.data-table th { 
    background: #f2f2f2; 
    font-weight: bold; 
    padding: 5px 7px; 
    text-align: left; 
    border: 0.5pt solid #000; 
}
table.data-table td { 
    padding: 5px 7px; 
    border: 0.5pt solid #000; 
    vertical-align: top;
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
    Laporan Rekrutmen Anggota Baru PSUP<br>
    <span style="font-size:12pt; font-weight:normal; text-transform:none;">Total {{ $members->count() }} Anggota &bull; Dicetak tanggal {{ date('d-m-Y') }}</span>
</div>

@php $byVoice = $members->groupBy(fn($m) => $m->voiceClassification->name ?? 'Belum Diklasifikasi'); @endphp

<table class="stats-table">
    <tr>
        <td style="width: 20%;">
            <div class="stat-num">{{ $members->count() }}</div>
            <div class="stat-lbl">Total Pendaftar</div>
        </td>
        @foreach($byVoice as $voice => $list)
        <td style="width: 2%; border: none; background: transparent;"></td>
        <td>
            <div class="stat-num">{{ $list->count() }}</div>
            <div class="stat-lbl">{{ $voice }}</div>
        </td>
        @endforeach
    </tr>
</table>

<div class="section">
    <div class="section-title">Daftar Anggota / Calon Anggota</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width:5%;text-align:center;">No</th>
                <th>Nama Lengkap</th>
                <th>NPM</th>
                <th>Fakultas</th>
                <th>Program Studi</th>
                <th>Angkatan</th>
                <th>Suara</th>
                <th>Bergabung</th>
            </tr>
        </thead>
        <tbody>
            @forelse($members as $i => $m)
            <tr>
                <td style="text-align:center;">{{ $i+1 }}</td>
                <td style="font-weight:bold;">{{ $m->name }}</td>
                <td>{{ $m->npm ?? '-' }}</td>
                <td>{{ $m->faculty ?? '-' }}</td>
                <td>{{ $m->major ?? '-' }}</td>
                <td>{{ $m->class_year ?? '-' }}</td>
                <td>{{ $m->voiceClassification->name ?? '-' }}</td>
                <td>{{ date('d-m-Y', strtotime($m->created_at)) }}</td>
            </tr>
            @empty
            <tr><td colspan="8" style="text-align:center;font-style:italic;">Tidak ada data rekrutmen anggota.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="footer">
    Laporan Rekrutmen Resmi Paduan Suara Universitas Pancasila &bull; Dicetak tanggal {{ date('d-m-Y H:i') }} WIB
</div>
</body>
</html>
