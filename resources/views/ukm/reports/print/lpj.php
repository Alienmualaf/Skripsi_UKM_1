<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>LPJ — Laporan Pertanggungjawaban PSUP</title>
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

/* ── KOP SURAT ── */
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

/* ── JUDUL DOKUMEN ── */
.doc-title {
    text-align: center;
    margin: 1.5rem 0;
}
.doc-title h1 {
    font-size: 14pt;
    font-weight: bold;
    text-transform: uppercase;
    line-height: 1.5;
}

/* ── BAB / SECTION ── */
.bab {
    margin-bottom: 1.5rem;
}
.bab-title {
    text-align: center;
    font-weight: bold;
    font-size: 12pt;
    text-transform: uppercase;
    margin-bottom: 0.25rem;
}
.bab-subtitle {
    text-align: center;
    font-weight: bold;
    font-size: 11pt;
    text-transform: uppercase;
    margin-bottom: 0.75rem;
}

/* ── TABEL ── */
table {
    width: 100%;
    border-collapse: collapse;
    font-size: 10pt;
    margin-bottom: 10px;
}
th {
    background: #f2f2f2;
    font-weight: bold;
    padding: 6px 8px;
    text-align: left;
    border: 1px solid #000000;
    text-transform: uppercase;
}
td {
    padding: 6px 8px;
    border: 1px solid #000000;
    vertical-align: top;
    color: #000000;
}
.text-center { text-align: center; }
.text-right  { text-align: right; }
.fw-bold     { font-weight: bold; }

/* ── SUMMARY BOX ── */
.summary-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 8px;
    margin-bottom: 1.5rem;
}
.summary-box {
    border: 1px solid #000000;
    padding: 8px 10px;
    text-align: center;
    background: #f9f9f9;
}
.summary-box .num { font-size: 14pt; font-weight: bold; color: #000000; }
.summary-box .lbl { font-size: 8pt; font-weight: bold; text-transform: uppercase; color: #000000; }

/* ── TANDA TANGAN ── */
.sign-section {
    display: flex;
    justify-content: space-around;
    margin-top: 3rem;
    page-break-inside: avoid;
}
.sign-box { text-align: center; width: 180px; }
.sign-line {
    border-top: 1px solid #000000;
    margin-top: 3.5rem;
    padding-top: 4px;
    font-weight: bold;
    font-size: 10pt;
}
.sign-role { font-size: 9pt; color: #000000; }

/* ── FOOTER ── */
.doc-footer {
    margin-top: 2rem;
    text-align: center;
    font-size: 8.5pt;
    color: #555555;
    border-top: 1px solid #000000;
    padding-top: 6px;
}

/* ── SECTION DIVIDER ── */
.section-divider {
    font-size: 11pt;
    font-weight: bold;
    text-transform: uppercase;
    border-bottom: 1px solid #000000;
    padding-bottom: 4px;
    margin: 1.25rem 0 0.6rem;
}

@media print {
    body { background: transparent; }
}
</style>
</head>
<body>

<!-- KOP SURAT -->
<div class="kop-surat">
    <img src="{{ asset('images/senat.png') }}" alt="Logo Senat">
    <div class="kop-tengah">
        <h2>PADUAN SUARA UNIVERSITAS PANCASILA</h2>
        <h3>SENAT MAHASISWA UNIVERSITAS PANCASILA</h3>
    </div>
    <img src="{{ asset('images/logo_PSUP.jpeg') }}" alt="Logo PSUP">
</div>
<hr class="kop-line">

<!-- JUDUL DOKUMEN -->
<div class="doc-title">
    <h1>
        LAPORAN PERTANGGUNGJAWABAN KEPENGURUSAN<br>
        PADUAN SUARA UNIVERSITAS PANCASILA
    </h1>
    <p style="font-size: 11pt; margin-top: 0.5rem;">
        Periode: {{ date('d-m-Y', strtotime($startDate)) }} s.d. {{ date('d-m-Y', strtotime($endDate)) }}
    </p>
</div>

<!-- RINGKASAN STATISTIK -->
<div class="section-divider">Ringkasan Pelaksanaan Program</div>
<div class="summary-grid">
    <div class="summary-box">
        <div class="num">{{ $programs->count() }}</div>
        <div class="lbl">Program Kerja</div>
    </div>
    <div class="summary-box">
        <div class="num">{{ $performances->count() }}</div>
        <div class="lbl">Penampilan</div>
    </div>
    <div class="summary-box">
        <div class="num">{{ $members->count() }}</div>
        <div class="lbl">Anggota Aktif</div>
    </div>
    <div class="summary-box">
        <div class="num">{{ $letters->count() }}</div>
        <div class="lbl">Surat Terarsip</div>
    </div>
    <div class="summary-box" style="grid-column: span 2;">
        <div class="num">Rp {{ number_format($income, 0, ',', '.') }}</div>
        <div class="lbl">Total Pemasukan</div>
    </div>
    <div class="summary-box" style="grid-column: span 2;">
        <div class="num">Rp {{ number_format($saldo, 0, ',', '.') }}</div>
        <div class="lbl">Saldo Akhir</div>
    </div>
</div>

<!-- BAB I: PROGRAM KERJA -->
<div class="bab">
    <div class="bab-title">BAB I</div>
    <div class="bab-subtitle">Rekapitulasi Program Kerja</div>
    <table>
        <thead>
            <tr>
                <th class="text-center" style="width:5%">No</th>
                <th style="width:30%">Nama Program</th>
                <th style="width:20%">Divisi</th>
                <th style="width:15%">Tipe</th>
                <th style="width:15%">Tanggal</th>
                <th style="width:15%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($programs as $i => $prog)
            <tr>
                <td class="text-center">{{ $i+1 }}</td>
                <td class="fw-bold">{{ $prog->name }}</td>
                <td>{{ $prog->division }}</td>
                <td>{{ $prog->activity_type }}</td>
                <td>{{ $prog->start_date ? date('d-m-Y', strtotime($prog->start_date)) : '-' }}</td>
                <td>{{ $prog->status }}</td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center" style="font-style: italic;">Tidak ada data program kerja.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- BAB II: PENAMPILAN -->
<div class="bab">
    <div class="bab-title">BAB II</div>
    <div class="bab-subtitle">Rekapitulasi Penampilan</div>
    <table>
        <thead>
            <tr>
                <th class="text-center" style="width:5%">No</th>
                <th style="width:35%">Judul Penampilan</th>
                <th style="width:25%">Venue / Lokasi</th>
                <th style="width:18%">Tanggal</th>
                <th style="width:17%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($performances as $i => $perf)
            <tr>
                <td class="text-center">{{ $i+1 }}</td>
                <td class="fw-bold">{{ $perf->title }}</td>
                <td>{{ $perf->venue }}</td>
                <td>{{ $perf->performance_date ? date('d-m-Y', strtotime($perf->performance_date)) : '-' }}</td>
                <td>{{ $perf->status }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center" style="font-style: italic;">Tidak ada data penampilan.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- BAB III: KEUANGAN -->
<div class="bab">
    <div class="bab-title">BAB III</div>
    <div class="bab-subtitle">Rekapitulasi Keuangan</div>
    <table>
        <thead>
            <tr>
                <th class="text-center" style="width:5%">No</th>
                <th style="width:15%">Tanggal</th>
                <th>Keterangan Transaksi / Kegiatan</th>
                <th class="text-center" style="width:15%">Jenis</th>
                <th class="text-right" style="width:25%">Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($finances as $i => $f)
            <tr>
                <td class="text-center">{{ $i+1 }}</td>
                <td>{{ date('d-m-Y', strtotime($f->transaction_date)) }}</td>
                <td>{{ $f->title }}</td>
                <td class="text-center">
                    {{ $f->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                </td>
                <td class="text-right">
                    {{ number_format($f->amount, 0, ',', '.') }}
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center" style="font-style: italic;">Tidak ada data transaksi keuangan.</td></tr>
            @endforelse
            <tr style="background: #f2f2f2; font-weight: bold;">
                <td colspan="4" class="text-right">Total Pemasukan</td>
                <td class="text-right">Rp {{ number_format($income, 0, ',', '.') }}</td>
            </tr>
            <tr style="background: #f2f2f2; font-weight: bold;">
                <td colspan="4" class="text-right">Total Pengeluaran</td>
                <td class="text-right">Rp {{ number_format($expense, 0, ',', '.') }}</td>
            </tr>
            <tr style="background: #f2f2f2; font-weight: bold; font-size: 12pt;">
                <td colspan="4" class="text-right">Saldo Akhir</td>
                <td class="text-right">Rp {{ number_format($saldo, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</div>

<!-- BAB IV: INVENTARIS -->
<div class="bab">
    <div class="bab-title">BAB IV</div>
    <div class="bab-subtitle">Rekapitulasi Inventaris Organisasi</div>
    <table>
        <thead>
            <tr>
                <th class="text-center" style="width:5%">No</th>
                <th style="width:30%">Nama Barang</th>
                <th style="width:15%">Kode</th>
                <th style="width:15%">Kategori</th>
                <th style="width:12%">Kondisi</th>
                <th class="text-center" style="width:10%">Jumlah</th>
                <th style="width:13%">Lokasi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($inventories as $i => $inv)
            <tr>
                <td class="text-center">{{ $i+1 }}</td>
                <td class="fw-bold">{{ $inv->name }}</td>
                <td>{{ $inv->code ?? '—' }}</td>
                <td>{{ $inv->category ?? '—' }}</td>
                <td>{{ $inv->condition ?? '—' }}</td>
                <td class="text-center">{{ $inv->quantity }}</td>
                <td>{{ $inv->storage_location ?? '—' }}</td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center" style="font-style: italic;">Tidak ada data inventaris.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- BAB V: PERSURATAN -->
<div class="bab">
    <div class="bab-title">BAB V</div>
    <div class="bab-subtitle">Rekapitulasi Persuratan</div>
    <table>
        <thead>
            <tr>
                <th class="text-center" style="width:5%">No</th>
                <th style="width:25%">No. Surat</th>
                <th style="width:15%">Tanggal</th>
                <th style="width:30%">Perihal</th>
                <th style="width:15%">Tujuan/Asal</th>
                <th style="width:10%">Jenis</th>
            </tr>
        </thead>
        <tbody>
            @forelse($letters as $i => $l)
            <tr>
                <td class="text-center">{{ $i+1 }}</td>
                <td>{{ $l->letter_number }}</td>
                <td>{{ date('d-m-Y', strtotime($l->date)) }}</td>
                <td class="fw-bold">{{ $l->subject }}</td>
                <td>{{ $l->destination ?? '—' }}</td>
                <td>{{ $l->type }}</td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center" style="font-style: italic;">Tidak ada data surat.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- PENUTUP -->
<div class="bab" style="page-break-inside: avoid;">
    <div class="bab-title">PENUTUP</div>
    <p style="text-indent: 2em; margin-bottom: 0.75rem; text-align: justify;">
        Demikian Laporan Pertanggungjawaban Pengurus Paduan Suara Universitas Pancasila
        periode {{ date('d-m-Y', strtotime($startDate)) }} hingga {{ date('d-m-Y', strtotime($endDate)) }}
        ini kami buat dengan sebenar-benarnya. Semoga laporan ini dapat menjadi bahan
        evaluasi dan acuan bagi kepengurusan selanjutnya.
    </p>
    <p style="text-indent: 2em; text-align: justify;">
        Atas perhatian dan kepercayaan seluruh pihak, kami mengucapkan terima kasih.
    </p>
</div>

<!-- TEMPAT & TANDA TANGAN -->
<p style="margin-top: 1.5rem; page-break-inside: avoid;">Jakarta, {{ date('d-m-Y') }}</p>
<div class="sign-section">
    <div class="sign-box">
        <div class="sign-role">Ketua Umum PSUP</div>
        <div class="sign-line">( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )</div>
    </div>
    <div class="sign-box">
        <div class="sign-role">Sekretaris Umum</div>
        <div class="sign-line">( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )</div>
    </div>
    <div class="sign-box">
        <div class="sign-role">Bendahara Umum</div>
        <div class="sign-line">( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )</div>
    </div>
</div>

<div class="doc-footer">
    Laporan Pertanggungjawaban resmi Paduan Suara Universitas Pancasila &bull; Dicetak tanggal {{ date('d-m-Y H:i') }} WIB
</div>

<script>window.onload = function() { window.print(); };</script>
</body>
</html>
