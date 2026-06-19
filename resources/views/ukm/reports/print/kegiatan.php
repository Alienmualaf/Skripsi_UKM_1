<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Kegiatan — {{ $program->name }}</title>
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
.kv-table {
    width: 100%;
    font-size: 11pt;
}
.kv-table td {
    padding: 4px 0;
    border: none;
}
.kv-table td:first-child {
    width: 35%;
    font-weight: bold;
}
.kv-table td:last-child {
    font-weight: normal;
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

@if(in_array(strtolower($program->activity_type), ['event', 'internal']))
    {{-- EVENT STRUCTURE --}}
    <div class="report-title">
        LAPORAN KEGIATAN EVENT<br>
        <span style="font-size: 12pt; font-weight: normal;">{{ $program->name }}</span>
    </div>

    <div class="section">
        <div class="section-title">1. Nama & Tempat Event</div>
        <table class="kv-table">
            <tr><td>Nama Event</td><td>: {{ $program->name }}</td></tr>
            <tr><td>Tempat / Lokasi</td><td>: {{ $program->venue ?? 'Sekretariat PSUP / Universitas Pancasila' }}</td></tr>
            <tr><td>Tanggal Pelaksanaan</td><td>: {{ $program->start_date ? date('d-m-Y', strtotime($program->start_date)) : '-' }} @if($program->end_date && $program->end_date !== $program->start_date) s.d. {{ date('d-m-Y', strtotime($program->end_date)) }} @endif</td></tr>
            <tr><td>Deskripsi</td><td>: {{ $program->description ?? '-' }}</td></tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">2. Keuangan</div>
        @php 
            $income = $finances->where('type','income')->sum('amount'); 
            $expense = $finances->where('type','expense')->sum('amount'); 
        @endphp
        <table class="kv-table" style="margin-bottom: 15px;">
            <tr><td>Total Pemasukan</td><td>: Rp {{ number_format($income, 0, ',', '.') }}</td></tr>
            <tr><td>Total Pengeluaran</td><td>: Rp {{ number_format($expense, 0, ',', '.') }}</td></tr>
            <tr><td>Saldo Akhir</td><td>: Rp {{ number_format($income - $expense, 0, ',', '.') }}</td></tr>
        </table>
        
        @if($finances->count())
        <p style="font-weight: bold; margin-bottom: 5px; font-size: 10pt;">Rincian Transaksi Keuangan:</p>
        <table>
            <thead>
                <tr>
                    <th style="width: 5%; text-align: center;">No</th>
                    <th>Tanggal</th>
                    <th>Keterangan</th>
                    <th>Jenis</th>
                    <th style="text-align: right;">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach($finances as $i => $f)
                <tr>
                    <td style="text-align: center;">{{ $i+1 }}</td>
                    <td>{{ date('d-m-Y', strtotime($f->transaction_date)) }}</td>
                    <td>{{ $f->description }}</td>
                    <td>{{ $f->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}</td>
                    <td style="text-align: right;">Rp {{ number_format($f->amount, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    <div class="section">
        <div class="section-title">3. Persuratan</div>
        @if($letters->count())
        <table>
            <thead>
                <tr>
                    <th style="width: 5%; text-align: center;">No</th>
                    <th>No. Surat</th>
                    <th>Tanggal</th>
                    <th>Perihal</th>
                    <th>Tujuan/Asal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($letters as $i => $l)
                <tr>
                    <td style="text-align: center;">{{ $i+1 }}</td>
                    <td style="font-family: monospace;">{{ $l->letter_number }}</td>
                    <td>{{ date('d-m-Y', strtotime($l->date)) }}</td>
                    <td>{{ $l->subject }}</td>
                    <td>{{ $l->destination ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p style="font-size: 11pt; font-style: italic;">Belum ada dokumen persuratan yang tertaut.</p>
        @endif
    </div>
@else
    {{-- LOMBA, PENAMPILAN, JOB STRUCTURE --}}
    <div class="report-title">
        LAPORAN KEGIATAN {{ $program->activity_type === 'Competition' ? 'LOMBA' : 'PENAMPILAN / JOB' }}<br>
        <span style="font-size: 12pt; font-weight: normal;">{{ $program->name }}</span>
    </div>

    <div class="section">
        <div class="section-title">1. Nama Kegiatan, Penanggung Jawab, Tanggal, & Tempat</div>
        <table class="kv-table">
            <tr><td>Nama Lomba/Penampilan/Job</td><td>: {{ $program->name }}</td></tr>
            <tr><td>Penanggung Jawab (PIC)</td><td>: {{ $program->pic ?? '-' }}</td></tr>
            <tr><td>Tanggal Pelaksanaan</td><td>: {{ $program->start_date ? date('d-m-Y', strtotime($program->start_date)) : '-' }} @if($program->end_date && $program->end_date !== $program->start_date) s.d. {{ date('d-m-Y', strtotime($program->end_date)) }} @endif</td></tr>
            <tr><td>Tempat / Lokasi</td><td>: {{ $program->venue ?? 'Sekretariat PSUP / Universitas Pancasila' }}</td></tr>
            <tr><td>Deskripsi</td><td>: {{ $program->description ?? '-' }}</td></tr>
        </table>
    </div>

    @php
        $mems = null;
        if ($program->performance && $program->performance->classroom) {
            $mems = $program->performance->classroom->members;
        }
    @endphp

    <div class="section">
        <div class="section-title">2. Daftar Penyanyi yang Ikut</div>
        @if($mems && $mems->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 5%; text-align: center;">No</th>
                    <th>Nama Anggota / Penyanyi</th>
                    <th>Klasifikasi Suara</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mems as $i => $m)
                <tr>
                    <td style="text-align: center;">{{ $i+1 }}</td>
                    <td style="font-weight: bold;">{{ $m->name }}</td>
                    <td>{{ $m->voiceClassification->name ?? '-' }}</td>
                    <td>{{ $m->status }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p style="font-size: 11pt; font-style: italic;">Belum ada daftar penyanyi yang ditentukan.</p>
        @endif
    </div>

    <div class="section">
        <div class="section-title">3. Keuangan</div>
        @php 
            $income = $finances->where('type','income')->sum('amount'); 
            $expense = $finances->where('type','expense')->sum('amount'); 
        @endphp
        <table class="kv-table" style="margin-bottom: 15px;">
            <tr><td>Total Pemasukan</td><td>: Rp {{ number_format($income, 0, ',', '.') }}</td></tr>
            <tr><td>Total Pengeluaran</td><td>: Rp {{ number_format($expense, 0, ',', '.') }}</td></tr>
            <tr><td>Saldo Akhir</td><td>: Rp {{ number_format($income - $expense, 0, ',', '.') }}</td></tr>
        </table>
        
        @if($finances->count())
        <p style="font-weight: bold; margin-bottom: 5px; font-size: 10pt;">Rincian Transaksi Keuangan:</p>
        <table>
            <thead>
                <tr>
                    <th style="width: 5%; text-align: center;">No</th>
                    <th>Tanggal</th>
                    <th>Keterangan</th>
                    <th>Jenis</th>
                    <th style="text-align: right;">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach($finances as $i => $f)
                <tr>
                    <td style="text-align: center;">{{ $i+1 }}</td>
                    <td>{{ date('d-m-Y', strtotime($f->transaction_date)) }}</td>
                    <td>{{ $f->description }}</td>
                    <td>{{ $f->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}</td>
                    <td style="text-align: right;">Rp {{ number_format($f->amount, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    <div class="section">
        <div class="section-title">4. Persuratan</div>
        @if($letters->count())
        <table>
            <thead>
                <tr>
                    <th style="width: 5%; text-align: center;">No</th>
                    <th>No. Surat</th>
                    <th>Tanggal</th>
                    <th>Perihal</th>
                    <th>Tujuan/Asal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($letters as $i => $l)
                <tr>
                    <td style="text-align: center;">{{ $i+1 }}</td>
                    <td style="font-family: monospace;">{{ $l->letter_number }}</td>
                    <td>{{ date('d-m-Y', strtotime($l->date)) }}</td>
                    <td>{{ $l->subject }}</td>
                    <td>{{ $l->destination ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p style="font-size: 11pt; font-style: italic;">Belum ada dokumen persuratan yang tertaut.</p>
        @endif
    </div>
@endif

<div class="footer">
    Laporan Resmi Paduan Suara Universitas Pancasila &bull; Dicetak tanggal {{ date('d-m-Y H:i') }} WIB
</div>

<script>window.onload = function() { window.print(); };</script>
</body>
</html>
