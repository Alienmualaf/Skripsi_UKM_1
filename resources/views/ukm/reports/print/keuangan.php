<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Keuangan PSUP</title>
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
.summary {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
}
.sum-card {
    flex: 1;
    border: 1px solid #000000;
    padding: 10px;
    background: #f9f9f9;
}
.sum-card .amount {
    font-size: 12pt;
    font-weight: bold;
}
.sum-card .label {
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
.text-right {
    text-align: right;
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
    LAPORAN KEUANGAN BULANAN/TAHUNAN PSUP<br>
    <span style="font-size: 11pt; font-weight: normal; text-transform: none;">Periode Laporan s.d. {{ date('d-m-Y') }}</span>
</div>

<div class="summary">
    <div class="sum-card">
        <div class="label">Total Pemasukan</div>
        <div class="amount">Rp {{ number_format($income, 0, ',', '.') }}</div>
    </div>
    <div class="sum-card">
        <div class="label">Total Pengeluaran</div>
        <div class="amount">Rp {{ number_format($expense, 0, ',', '.') }}</div>
    </div>
    <div class="sum-card">
        <div class="label">Saldo Akhir</div>
        <div class="amount">Rp {{ number_format($saldo, 0, ',', '.') }}</div>
    </div>
</div>

<div class="section">
    <div class="section-title">Detail Transaksi Keuangan</div>
    <table>
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No</th>
                <th style="width: 12%;">Tanggal</th>
                <th style="width: 12%;">Jenis</th>
                <th style="width: 20%;">Transaksi</th>
                <th style="width: 15%;">Digunakan Untuk</th>
                <th>Deskripsi</th>
                <th class="text-right" style="width: 15%;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @forelse($finances as $index => $f)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ date('d-m-Y', strtotime($f->transaction_date)) }}</td>
                <td>{{ $f->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}</td>
                <td>{{ $f->title }}</td>
                <td>{{ $f->used_for === 'Program Kerja' ? ($f->program->name ?? '-') : 'Umum' }}</td>
                <td>{{ $f->description ?? '-' }}</td>
                <td class="text-right" style="font-weight: bold;">
                    {{ $f->type === 'income' ? '+' : '-' }}Rp {{ number_format($f->amount, 0, ',', '.') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; font-style: italic;">Tidak ada data transaksi keuangan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="footer">
    Laporan Keuangan Resmi Paduan Suara Universitas Pancasila &bull; Dicetak tanggal {{ date('d-m-Y H:i') }} WIB
</div>

<script>window.onload = function() { window.print(); };</script>
</body>
</html>
