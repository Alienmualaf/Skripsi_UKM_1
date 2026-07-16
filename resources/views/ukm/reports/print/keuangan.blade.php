<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Keuangan PSUP</title>
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
.summary-table { 
    width: 100%; 
    border: none; 
    margin-bottom: 18px; 
}
.summary-table td { 
    padding: 8px; 
    border: 0.5pt solid #000; 
    background: #f9f9f9; 
}
.sum-label { 
    font-size: 9pt; 
    font-weight: bold; 
    text-transform: uppercase; 
}
.sum-amount { 
    font-size: 12pt; 
    font-weight: bold; 
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
    padding: 6px; 
    text-align: left; 
    border: 0.5pt solid #000; 
}
table.data-table td { 
    padding: 6px; 
    border: 0.5pt solid #000; 
    vertical-align: top;
}
.text-right { 
    text-align: right; 
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
    Laporan Keuangan Bulanan/Tahunan PSUP<br>
    <span style="font-size:12pt; font-weight:normal; text-transform:none;">Periode Laporan s.d. {{ date('d-m-Y') }}</span>
</div>

<table class="summary-table">
    <tr>
        <td style="width: 32%;">
            <div class="sum-label">Total Pemasukan</div>
            <div class="sum-amount">Rp {{ number_format($income, 0, ',', '.') }}</div>
        </td>
        <td style="width: 2%; border: none; background: transparent;"></td>
        <td style="width: 32%;">
            <div class="sum-label">Total Pengeluaran</div>
            <div class="sum-amount">Rp {{ number_format($expense, 0, ',', '.') }}</div>
        </td>
        <td style="width: 2%; border: none; background: transparent;"></td>
        <td style="width: 32%;">
            <div class="sum-label">Saldo Akhir</div>
            <div class="sum-amount">Rp {{ number_format($saldo, 0, ',', '.') }}</div>
        </td>
    </tr>
</table>

<div class="section">
    <div class="section-title">Detail Transaksi Keuangan</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width:5%;text-align:center;">No</th>
                <th style="width:12%;">Tanggal</th>
                <th style="width:12%;">Jenis</th>
                <th style="width:20%;">Transaksi</th>
                <th style="width:15%;">Digunakan Untuk</th>
                <th>Deskripsi</th>
                <th class="text-right" style="width:15%;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @forelse($finances as $index => $f)
            <tr>
                <td style="text-align:center;">{{ $index + 1 }}</td>
                <td>{{ date('d-m-Y', strtotime($f->transaction_date)) }}</td>
                <td>{{ $f->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}</td>
                <td>{{ $f->title }}</td>
                <td>{{ $f->used_for === 'Program Kerja' ? ($f->program->name ?? '-') : 'Umum' }}</td>
                <td>{{ $f->description ?? '-' }}</td>
                <td class="text-right" style="font-weight:bold;">{{ $f->type === 'income' ? '+' : '-' }}Rp {{ number_format($f->amount, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;font-style:italic;">Tidak ada data transaksi keuangan.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="footer">
    Laporan Keuangan Resmi Paduan Suara Universitas Pancasila &bull; Dicetak tanggal {{ date('d-m-Y H:i') }} WIB
</div>
</body>
</html>
