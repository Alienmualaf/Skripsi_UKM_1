<!DOCTYPE html>
<html>
<head>
    <title>Laporan Keuangan - {{ $ukm->name }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h2, h3 { text-align: center; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <h2>LAPORAN KEUANGAN UKM</h2>
    <h3>{{ strtoupper($ukm->name) }}</h3>
    <hr>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Jenis</th>
                <th>Pemasukan (Rp)</th>
                <th>Pengeluaran (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $totalIncome = 0;
                $totalExpense = 0;
            @endphp
            @foreach($finances as $index => $f)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $f->transaction_date }}</td>
                <td>{{ $f->title }}<br><small>{{ $f->description }}</small></td>
                <td>{{ ucfirst($f->type) }}</td>
                <td class="text-right">
                    @if($f->type === 'income')
                        {{ number_format($f->amount, 0, ',', '.') }}
                        @php $totalIncome += $f->amount; @endphp
                    @else
                        -
                    @endif
                </td>
                <td class="text-right">
                    @if($f->type === 'expense')
                        {{ number_format($f->amount, 0, ',', '.') }}
                        @php $totalExpense += $f->amount; @endphp
                    @else
                        -
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-right">TOTAL</th>
                <th class="text-right">{{ number_format($totalIncome, 0, ',', '.') }}</th>
                <th class="text-right">{{ number_format($totalExpense, 0, ',', '.') }}</th>
            </tr>
            <tr>
                <th colspan="4" class="text-right">SALDO AKHIR</th>
                <th colspan="2" class="text-center" style="text-align: center; font-size: 14px;">
                    Rp {{ number_format($totalIncome - $totalExpense, 0, ',', '.') }}
                </th>
            </tr>
        </tfoot>
    </table>
</body>
</html>
