<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>
        @if($type === 'keuangan') Laporan Keuangan
        @elseif($type === 'kegiatan') Laporan Kegiatan
        @elseif($type === 'rekrutmen') Laporan Rekrutmen & Anggota
        @elseif($type === 'lpj') Laporan Pertanggungjawaban
        @elseif($type === 'absensi') Laporan Absensi Kegiatan
        @elseif($type === 'inventaris') Laporan Inventaris
        @else Laporan Sistem
        @endif
        - PSUP
    </title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #000;
            margin: 0;
            padding: 2rem;
            background-color: #fff;
        }

        /* Kop Surat */
        .kop-surat {
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 25px;
            text-align: center;
        }
        .kop-logo {
            float: left;
            width: 80px;
            height: 80px;
        }
        .kop-text h2 {
            margin: 0;
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .kop-text h3 {
            margin: 5px 0 0 0;
            font-size: 16pt;
            font-weight: bold;
            color: #1e3a8a;
            text-transform: uppercase;
        }
        .kop-text p {
            margin: 5px 0 0 0;
            font-size: 9pt;
            font-style: italic;
        }

        .clear {
            clear: both;
        }

        /* Title */
        .report-title {
            text-align: center;
            margin-bottom: 20px;
        }
        .report-title h1 {
            margin: 0;
            font-size: 13pt;
            text-transform: uppercase;
            text-decoration: underline;
        }
        .report-title p {
            margin: 5px 0 0 0;
            font-size: 10pt;
        }

        /* Meta details */
        .meta-table {
            width: 100%;
            margin-bottom: 20px;
            font-size: 10pt;
        }
        .meta-table td {
            padding: 2px 0;
        }

        /* Standard Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .data-table th, .data-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            font-size: 10pt;
        }
        .data-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }

        /* Signatures */
        .signature-section {
            margin-top: 40px;
            width: 100%;
            page-break-inside: avoid;
        }
        .signature-box {
            float: right;
            width: 250px;
            text-align: center;
        }
        .signature-space {
            height: 70px;
        }

        /* Actions widget */
        .print-widget {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #fff;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.15);
            display: flex;
            gap: 10px;
            z-index: 9999;
        }
        .print-btn {
            background: #1e3a8a;
            color: #fff;
            border: none;
            padding: 6px 12px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
        }
        .print-btn-secondary {
            background: #e5e7eb;
            color: #374151;
            border: 1px solid #d1d5db;
        }

        @media print {
            body {
                padding: 0;
            }
            .print-widget {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <!-- Floating Controller -->
    <div class="print-widget">
        <button class="print-btn" onclick="window.print()"><i class="ph ph-printer"></i> Cetak</button>
        <button class="print-btn print-btn-secondary" onclick="window.close()">Tutup</button>
    </div>

    <!-- Kop Surat Formal -->
    <div class="kop-surat">
        <div class="kop-text">
            <h2>Unit Kegiatan Mahasiswa</h2>
            <h3>Paduan Suara Universitas Pancasila (PSUP)</h3>
            <p>Sekretariat: Gedung UKM Lt. 2 Universitas Pancasila, Srengseng Sawah, Jagakarsa, Jakarta Selatan | Email: psup@univpancasila.ac.id</p>
        </div>
        <div class="clear"></div>
    </div>

    <!-- Judul Laporan -->
    <div class="report-title">
        <h1>
            @if($type === 'keuangan') LAPORAN PERTANGGUNGJAWABAN KEUANGAN
            @elseif($type === 'kegiatan') LAPORAN AGENDA KEGIATAN
            @elseif($type === 'rekrutmen') LAPORAN REKRUTMEN ANGGOTA BARU
            @elseif($type === 'lpj') LAPORAN PERTANGGUNGJAWABAN PROGRAM KERJA
            @elseif($type === 'absensi') LAPORAN KEHADIRAN ABSENSI
            @elseif($type === 'inventaris') LAPORAN DAFTAR INVENTARIS ASET
            @else LAPORAN OPERASIONAL SISTEM
            @endif
        </h1>
        <p>Paduan Suara Universitas Pancasila (PSUP)</p>
    </div>

    <!-- Meta Details -->
    <table class="meta-table">
        <tr>
            <td style="width: 120px;">Periode Laporan</td>
            <td style="width: 15px;">:</td>
            <td><strong>{{ date('d-m-Y', strtotime($start_date)) }}</strong> s.d <strong>{{ date('d-m-Y', strtotime($end_date)) }}</strong></td>
            <td style="width: 120px; text-align: right;">Tanggal Cetak</td>
            <td style="width: 15px; text-align: right;">:</td>
            <td style="text-align: right;">{{ date('d-m-Y H:i') }}</td>
        </tr>
        <tr>
            <td>Status Data</td>
            <td>:</td>
            <td>Dokumen Resmi UKM</td>
            <td style="text-align: right;">Dicetak Oleh</td>
            <td style="text-align: right;">:</td>
            <td style="text-align: right;">{{ Auth::user()->name ?? 'Administrator' }}</td>
        </tr>
    </table>

    <!-- Data Content -->
    @if($type === 'keuangan')
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th style="width: 120px;">Tanggal</th>
                    <th>Keterangan / Deskripsi</th>
                    <th style="width: 150px;">Kategori</th>
                    <th style="width: 130px;">Pemasukan</th>
                    <th style="width: 130px;">Pengeluaran</th>
                </tr>
            </thead>
            <tbody>
                @php $totalIncome = 0; $totalExpense = 0; @endphp
                @forelse($data as $idx => $item)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td class="text-center">{{ date('d-m-Y', strtotime($item->transaction_date)) }}</td>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->category->name ?? 'Umum' }}</td>
                    <td class="text-right">
                        @if($item->type === 'income')
                            Rp {{ number_format($item->amount, 0, ',', '.') }}
                            @php $totalIncome += $item->amount; @endphp
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-right">
                        @if($item->type === 'expense')
                            Rp {{ number_format($item->amount, 0, ',', '.') }}
                            @php $totalExpense += $item->amount; @endphp
                        @else
                            -
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada transaksi keuangan pada periode ini.</td>
                </tr>
                @endforelse
                <tr class="font-bold" style="background-color: #f9fafb;">
                    <td colspan="4" class="text-right">TOTAL</td>
                    <td class="text-right" style="color: #047857;">Rp {{ number_format($totalIncome, 0, ',', '.') }}</td>
                    <td class="text-right" style="color: #b91c1c;">Rp {{ number_format($totalExpense, 0, ',', '.') }}</td>
                </tr>
                <tr class="font-bold" style="background-color: #f3f4f6;">
                    <td colspan="4" class="text-right">SALDO AKHIR (NET)</td>
                    <td colspan="2" class="text-center" style="font-size: 11pt;">
                        Rp {{ number_format($totalIncome - $totalExpense, 0, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>

    @elseif($type === 'kegiatan')
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama Penampilan</th>
                    <th style="width: 150px;">Program Kerja</th>
                    <th style="width: 120px;">Tanggal Tampil</th>
                    <th style="width: 150px;">Tempat / Venue</th>
                    <th style="width: 120px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $idx => $item)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td class="font-bold">{{ $item->title }}</td>
                    <td class="text-center">{{ $item->program ? $item->program->name : 'N/A' }}</td>
                    <td class="text-center">{{ date('d-m-Y', strtotime($item->performance_date)) }}</td>
                    <td>{{ $item->venue }}</td>
                    <td class="text-center">{{ $item->status }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada agenda kegiatan pada periode ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

    @elseif($type === 'rekrutmen')
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama Calon Anggota</th>
                    <th style="width: 130px;">NPM</th>
                    <th style="width: 130px;">Fakultas</th>
                    <th style="width: 150px;">Klasifikasi Suara</th>
                    <th style="width: 120px;">Status Seleksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $idx => $item)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td class="font-bold">{{ $item->name }}</td>
                    <td class="text-center">{{ $item->npm }}</td>
                    <td class="text-center">{{ $item->faculty ?? '-' }}</td>
                    <td class="text-center">{{ $item->voice_classification ?? '-' }}</td>
                    <td class="text-center font-bold">
                        @if($item->status === 'Terima') Lulus Seleksi
                        @elseif($item->status === 'Tolak') Tidak Lulus
                        @else Pending
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada pendaftaran rekrutmen pada periode ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

    @elseif($type === 'lpj')
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama Program Kerja</th>
                    <th style="width: 150px;">Target Selesai</th>
                    <th style="width: 150px;">Penanggung Jawab (PJ)</th>
                    <th style="width: 130px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $idx => $item)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td class="font-bold">{{ $item->name }}</td>
                    <td class="text-center">{{ date('d-m-Y', strtotime($item->target_date)) }}</td>
                    <td>{{ $item->pic }}</td>
                    <td class="text-center">{{ $item->status }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada program kerja pada periode ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

    @elseif($type === 'absensi')
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama Kegiatan Absen</th>
                    <th style="width: 150px;">Tanggal</th>
                    <th style="width: 150px;">Hadir (Anggota)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $idx => $item)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td class="font-bold">{{ $item->title }}</td>
                    <td class="text-center">{{ date('d-m-Y', strtotime($item->date)) }}</td>
                    <td class="text-center">{{ $item->total_hadir }} orang</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data absensi pada periode ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

    @elseif($type === 'inventaris')
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th style="width: 120px;">Kode Barang</th>
                    <th>Nama Aset / Inventaris</th>
                    <th>Kategori</th>
                    <th style="width: 100px;">Jumlah</th>
                    <th>Kondisi</th>
                    <th>Lokasi Penyimpanan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $idx => $item)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td class="text-center">{{ $item->code }}</td>
                    <td class="font-bold">{{ $item->name }}</td>
                    <td>{{ $item->category }}</td>
                    <td class="text-center">{{ $item->quantity }} pcs</td>
                    <td class="text-center">
                        @if($item->condition === 'Baik') Bagus/Baik
                        @elseif($item->condition === 'Rusak') Rusak
                        @else Hilang
                        @endif
                    </td>
                    <td>{{ $item->storage_location }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data barang inventaris.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    @endif

    <!-- Tanda Tangan -->
    <div class="signature-section">
        <div class="signature-box">
            <p>Jakarta, {{ date('d F Y') }}</p>
            <p>Pengurus UKM PSUP</p>
            <div class="signature-space"></div>
            <p><strong><u>{{ Auth::user()->name ?? 'Pengurus UKM' }}</strong></u></p>
            <p>Admin UKM PSUP</p>
        </div>
        <div class="clear"></div>
    </div>

    <!-- Script to Auto trigger print screen -->
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            // Auto trigger print dialogue shortly after rendering
            setTimeout(() => {
                window.print();
            }, 500);
        });
    </script>
</body>
</html>
