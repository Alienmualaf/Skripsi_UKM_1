<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Rekrutmen Anggota</title>
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
@page {
    size: A4;
    margin-top: 3cm;
    margin-left: 3cm;
    margin-right: 2.5cm;
    margin-bottom: 2.5cm;
}
body {
    font-family: 'Times New Roman', Times, serif;
    font-size: 11pt;
    color: #000000;
    background: #ffffff;
    line-height: 1.5;
}
.kop-surat {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 5px;
    padding-bottom: 5px;
}
.kop-surat img {
    height: 80px;
    width: auto;
}
.kop-tengah {
    text-align: center;
    flex-grow: 1;
}
.kop-tengah h2 {
    font-family: 'Times New Roman', Times, serif;
    font-size: 14pt;
    font-weight: bold;
    color: #000000;
    margin: 0;
    line-height: 1.2;
}
.kop-tengah h3 {
    font-family: 'Times New Roman', Times, serif;
    font-size: 12pt;
    font-weight: bold;
    color: #000000;
    margin: 0;
    line-height: 1.2;
}
.kop-line {
    border: none;
    border-top: 2px solid #000000;
    border-bottom: 0.5px solid #000000;
    height: 4px;
    margin-top: 2px;
    margin-bottom: 20px;
}
.report-title {
    text-align: center;
    font-size: 14pt;
    font-weight: bold;
    margin-bottom: 20px;
    text-transform: uppercase;
}
.section {
    margin-bottom: 20px;
}
.section-title {
    font-size: 11pt;
    font-weight: bold;
    text-transform: uppercase;
    border-bottom: 1px solid #000000;
    padding-bottom: 4px;
    margin-bottom: 10px;
}
.stats {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
}
.stat {
    flex: 1;
    border: 1px solid #000000;
    padding: 10px;
    text-align: center;
    background: #f9f9f9;
}
.stat .num {
    font-size: 14pt;
    font-weight: bold;
}
.stat .lbl {
    font-size: 8pt;
    font-weight: bold;
    text-transform: uppercase;
}
table {
    width: 100%;
    border-collapse: collapse;
    font-size: 10pt;
    margin-bottom: 10px;
}
th {
    background: #f2f2f2;
    color: #000000;
    font-weight: bold;
    padding: 6px 8px;
    text-align: left;
    border: 1px solid #000000;
}
td {
    padding: 6px 8px;
    border: 1px solid #000000;
    color: #000000;
}
.footer {
    margin-top: 30px;
    text-align: center;
    font-size: 9pt;
    color: #555555;
    border-top: 1px solid #000000;
    padding-top: 10px;
}
@media print {
    body {
        background: transparent;
    }
}
</style>
</head>
<body>

<div class="kop-surat">
    <img src="{{ asset('images/senat.png') }}" alt="Logo Senat">
    <div class="kop-tengah">
        <h2>PADUAN SUARA UNIVERSITAS PANCASILA</h2>
        <h3>SENAT MAHASISWA UNIVERSITAS PANCASILA</h3>
    </div>
    <img src="{{ asset('images/logo_PSUP.jpeg') }}" alt="Logo PSUP">
</div>
<hr class="kop-line">

<div class="report-title">
    LAPORAN REKRUTMEN ANGGOTA BARU PSUP<br>
    <span style="font-size: 11pt; font-weight: normal; text-transform: none;">Total {{ $members->count() }} Anggota &bull; Dicetak tanggal {{ date('d-m-Y') }}</span>
</div>

@php
$byVoice = $members->groupBy(fn($m) => $m->voiceClassification->name ?? 'Belum Diklasifikasi');
@endphp

<div class="stats">
    <div class="stat">
        <div class="num">{{ $members->count() }}</div>
        <div class="lbl">Total Pendaftar/Anggota</div>
    </div>
    @foreach($byVoice as $voice => $list)
    <div class="stat">
        <div class="num">{{ $list->count() }}</div>
        <div class="lbl">{{ $voice }}</div>
    </div>
    @endforeach
</div>

<div class="section">
    <div class="section-title">Daftar Anggota / Calon Anggota</div>
    <table>
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No</th>
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
                <td style="text-align: center;">{{ $i+1 }}</td>
                <td style="font-weight: bold;">{{ $m->name }}</td>
                <td>{{ $m->npm ?? '-' }}</td>
                <td>{{ $m->faculty ?? '-' }}</td>
                <td>{{ $m->major ?? '-' }}</td>
                <td>{{ $m->class_year ?? '-' }}</td>
                <td>{{ $m->voiceClassification->name ?? '-' }}</td>
                <td>{{ date('d-m-Y', strtotime($m->created_at)) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; font-style: italic;">Tidak ada data rekrutmen anggota.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="footer">
    Laporan Rekrutmen Resmi Paduan Suara Universitas Pancasila &bull; Dicetak tanggal {{ date('d-m-Y H:i') }} WIB
</div>

<script>window.onload = function() { window.print(); };</script>
</body>
</html>
