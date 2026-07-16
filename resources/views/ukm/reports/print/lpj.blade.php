<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>LPJ — Laporan Pertanggungjawaban PSUP</title>
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
.bab { 
    margin-bottom: 1.5rem; 
}
.bab-title { 
    text-align: center; 
    font-weight: bold; 
    font-size: 14pt; 
    text-transform: uppercase; 
    margin-bottom: 4px; 
}
.bab-subtitle { 
    text-align: center; 
    font-weight: bold; 
    font-size: 12pt; 
    text-transform: uppercase; 
    margin-bottom: 12px; 
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
    text-transform: uppercase;
}
table.data-table td { 
    padding: 6px; 
    border: 0.5pt solid #000; 
    vertical-align: top; 
}
.text-center { 
    text-align: center; 
}
.text-right { 
    text-align: right; 
}
.fw-bold { 
    font-weight: bold; 
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
.sign-table { 
    width: 100%; 
    border: none; 
    margin-top: 2.5rem; 
    page-break-inside: avoid; 
}
.sign-table td { 
    border: none; 
    padding: 0; 
    text-align: center; 
}
.sign-role { 
    font-size: 10pt; 
}
.sign-line { 
    margin-top: 3.5rem; 
    font-weight: bold; 
    font-size: 11pt; 
}
.doc-footer { 
    margin-top: 24px; 
    text-align: center; 
    font-size: 8.5pt; 
    color: #555; 
    border-top: 0.5pt solid #000; 
    padding-top: 6px; 
}
</style>
</head>
<body>

{{-- COVER PAGE --}}
<div style="page-break-after: always; text-align: center; padding-top: 4cm; font-family: 'Times New Roman', Times, serif;">
    <img src="{{ public_path('images/logo_PSUP.jpeg') }}" style="height: 140px; width: auto; margin-bottom: 2cm;"><br>
    
    <div style="font-size: 18pt; font-weight: bold; text-transform: uppercase; margin-bottom: 1cm; line-height: 1.3;">
        LAPORAN PERTANGGUNGJAWABAN KEPENGURUSAN<br>
        PADUAN SUARA UNIVERSITAS PANCASILA
    </div>
    
    <div style="font-size: 12pt; margin-bottom: 4cm; font-style: italic;">
        Periode: {{ date('d-m-Y', strtotime($startDate)) }} s.d. {{ date('d-m-Y', strtotime($endDate)) }}
    </div>
    
    <div style="font-size: 12pt; font-weight: bold; text-transform: uppercase; line-height: 1.3;">
        SENAT - KELUARGA MAHASISWA UNIVERSITAS PANCASILA<br>
        UNIVERSITAS PANCASILA<br>
        JAKARTA<br>
        {{ date('Y') }}
    </div>
</div>

{{-- BAB I SEPARATOR --}}
<div style="page-break-after: always; text-align: center; padding-top: 8cm; font-family: 'Times New Roman', Times, serif;">
    <div style="font-size: 16pt; font-weight: bold; text-transform: uppercase; line-height: 1.5;">
        Laporan Pelaksanaan Program Kerja
    </div>
</div>

{{-- BAB I CONTENT --}}
<div class="bab">
    @forelse($programs as $i => $prog)
    <div style="{{ $i > 0 ? 'page-break-before: always;' : '' }} margin-bottom: 20px;">
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
            <span style="font-size:14pt; font-weight:bold;">"{{ $prog->name }}"</span>
        </div>

        <table class="info-table">
            <tr>
                <td class="label">Keterangan</td>
                <td class="colon">:</td>
                <td>Kegiatan {{ $prog->activity_type === 'Competition' ? 'Lomba' : ($prog->activity_type === 'Performance' ? 'Penampilan / Job' : 'Event') }} {{ $prog->name }} {{ $prog->description ? '— ' . $prog->description : '' }}</td>
            </tr>
            <tr>
                <td class="label">Hari/Tanggal</td>
                <td class="colon">:</td>
                <td>{{ $prog->start_date ? date('d-m-Y', strtotime($prog->start_date)) : '-' }} @if($prog->end_date && $prog->end_date !== $prog->start_date) s.d. {{ date('d-m-Y', strtotime($prog->end_date)) }} @endif</td>
            </tr>
            <tr>
                <td class="label">Penanggung Jawab</td>
                <td class="colon">:</td>
                <td>{{ $prog->pic ?? '-' }}</td>
            </tr>
            @if($prog->performance && $prog->performance->classroom && $prog->performance->classroom->trainer)
            <tr>
                <td class="label">Pelatih / Konduktor</td>
                <td class="colon">:</td>
                <td>{{ $prog->performance->classroom->trainer->name }}</td>
            </tr>
            @endif
            @if(in_array(strtolower($prog->activity_type), ['competition', 'performance']))
            <tr>
                <td class="label">Daftar Penyanyi</td>
                <td class="colon">:</td>
                <td>(Terlampir di bawah)</td>
            </tr>
            @endif
        </table>

        @if(in_array(strtolower($prog->activity_type), ['competition', 'performance']))
        @php
            $mems = ($prog->performance && $prog->performance->classroom) ? $prog->performance->classroom->members : null;
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
        @if($mems && $mems->count() > 0)
        <div style="margin-top: 12pt; margin-bottom: 12pt;">
            <p style="font-weight:bold; font-size:11pt; margin-bottom:6px; text-transform:uppercase;">Daftar Penyanyi yang Ikut</p>
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
                    @for($k = 0; $k < $maxRows; $k++)
                    <tr>
                        <td>
                            {{ isset($soprans[$k]) ? ($k + 1) . '. ' . $soprans[$k]->name : '' }}
                        </td>
                        <td>
                            {{ isset($altos[$k]) ? ($k + 1) . '. ' . $altos[$k]->name : '' }}
                        </td>
                        <td>
                            {{ isset($tenors[$k]) ? ($k + 1) . '. ' . $tenors[$k]->name : '' }}
                        </td>
                        <td>
                            {{ isset($basses[$k]) ? ($k + 1) . '. ' . $basses[$k]->name : '' }}
                        </td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
        @endif
        @endif
    </div>
    @empty
    <p style="text-align:center;font-style:italic;">Tidak ada program kerja pada periode ini.</p>
    @endforelse
</div>

{{-- BAB II SEPARATOR --}}
<div style="page-break-before: always; page-break-after: always; text-align: center; padding-top: 8cm; font-family: 'Times New Roman', Times, serif;">
    <div style="font-size: 16pt; font-weight: bold; text-transform: uppercase; line-height: 1.5;">
        Pencatatan Keuangan
    </div>
</div>

{{-- BAB II CONTENT --}}
<div class="bab">
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

    <table class="data-table">
        <thead>
            <tr>
                <th class="text-center" style="width:5%;">No</th>
                <th style="width:15%;">Tanggal</th>
                <th>Keterangan Transaksi</th>
                <th class="text-center" style="width:15%;">Jenis</th>
                <th class="text-right" style="width:22%;">Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($finances as $i => $f)
            <tr>
                <td class="text-center">{{ $i+1 }}</td>
                <td>{{ date('d-m-Y', strtotime($f->transaction_date)) }}</td>
                <td>{{ $f->title }}</td>
                <td class="text-center">{{ $f->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}</td>
                <td class="text-right">{{ number_format($f->amount, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center" style="font-style:italic;">Tidak ada data transaksi keuangan.</td></tr>
            @endforelse
            <tr style="background:#f2f2f2;font-weight:bold;"><td colspan="4" class="text-right">Total Pemasukan</td><td class="text-right">Rp {{ number_format($income, 0, ',', '.') }}</td></tr>
            <tr style="background:#f2f2f2;font-weight:bold;"><td colspan="4" class="text-right">Total Pengeluaran</td><td class="text-right">Rp {{ number_format($expense, 0, ',', '.') }}</td></tr>
            <tr style="background:#f2f2f2;font-weight:bold;font-size:12pt;"><td colspan="4" class="text-right">Saldo Akhir</td><td class="text-right">Rp {{ number_format($saldo, 0, ',', '.') }}</td></tr>
        </tbody>
    </table>
</div>

{{-- BAB III SEPARATOR --}}
<div style="page-break-before: always; page-break-after: always; text-align: center; padding-top: 8cm; font-family: 'Times New Roman', Times, serif;">
    <div style="font-size: 16pt; font-weight: bold; text-transform: uppercase; line-height: 1.5;">
        Daftar Inventaris
    </div>
</div>

{{-- BAB III CONTENT --}}
<div class="bab">
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

    <table class="data-table">
        <thead>
            <tr>
                <th class="text-center" style="width:5%;">No</th>
                <th style="width:40%;">Nama Barang</th>
                <th style="width:15%;">Kategori</th>
                <th style="width:12%;">Kondisi</th>
                <th class="text-center" style="width:10%;">Jumlah</th>
                <th>Lokasi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($inventories as $i => $inv)
            <tr>
                <td class="text-center">{{ $i+1 }}</td>
                <td class="fw-bold">{{ $inv->name }}</td>
                <td>{{ $inv->category ?? '—' }}</td>
                <td>{{ $inv->condition ?? '—' }}</td>
                <td class="text-center">{{ $inv->quantity }}</td>
                <td>{{ $inv->storage_location ?? '—' }}</td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center" style="font-style:italic;">Tidak ada data inventaris.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- BAB IV SEPARATOR --}}
<div style="page-break-before: always; page-break-after: always; text-align: center; padding-top: 8cm; font-family: 'Times New Roman', Times, serif;">
    <div style="font-size: 16pt; font-weight: bold; text-transform: uppercase; line-height: 1.5;">
        Persuratan
    </div>
</div>

{{-- BAB IV CONTENT --}}
<div class="bab">
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

    <table class="data-table">
        <thead>
            <tr>
                <th class="text-center" style="width:5%;">No</th>
                <th style="width:25%;">No. Surat</th>
                <th style="width:15%;">Tanggal</th>
                <th style="width:30%;">Perihal</th>
                <th style="width:15%;">Tujuan/Asal</th>
                <th>Jenis</th>
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
            <tr><td colspan="6" class="text-center" style="font-style:italic;">Tidak ada data surat.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- BAB V SEPARATOR --}}
<div style="page-break-before: always; page-break-after: always; text-align: center; padding-top: 8cm; font-family: 'Times New Roman', Times, serif;">
    <div style="font-size: 16pt; font-weight: bold; text-transform: uppercase; line-height: 1.5;">
        Penutup
    </div>
</div>

{{-- PENUTUP CONTENT --}}
<div class="bab">
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
    <p style="text-indent:2em; margin-bottom:12pt; text-align:justify;">
        Demikian Laporan Pertanggungjawaban Pengurus Paduan Suara Universitas Pancasila
        periode {{ date('d-m-Y', strtotime($startDate)) }} hingga {{ date('d-m-Y', strtotime($endDate)) }}
        ini kami buat dengan sebenar-benarnya. Semoga laporan ini dapat menjadi bahan evaluasi dan acuan bagi kepengurusan selanjutnya.
    </p>
    <p style="text-indent:2em; text-align:justify; margin-bottom: 24pt;">Atas perhatian dan kepercayaan seluruh pihak, kami mengucapkan terima kasih.</p>
    
    <p style="margin-top:1.5rem; page-break-inside:avoid; text-align: right; padding-right: 2cm;">Jakarta, {{ date('d-m-Y') }}</p>
    <table class="sign-table">
        <tr>
            <td style="width: 33.3%;">
                <div class="sign-role">Ketua Umum PSUP</div>
                <div class="sign-line">( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )</div>
            </td>
            <td style="width: 33.3%;">
                <div class="sign-role">Sekretaris Umum</div>
                <div class="sign-line">( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )</div>
            </td>
            <td style="width: 33.3%;">
                <div class="sign-role">Bendahara Umum</div>
                <div class="sign-line">( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )</div>
            </td>
        </tr>
    </table>
</div>

<div class="doc-footer">
    Laporan Pertanggungjawaban resmi Paduan Suara Universitas Pancasila &bull; Dicetak tanggal {{ date('d-m-Y H:i') }} WIB
</div>
</body>
</html>
