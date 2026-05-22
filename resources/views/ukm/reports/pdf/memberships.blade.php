<!DOCTYPE html>
<html>
<head>
    <title>Laporan Rekrutmen & Anggota - {{ $ukm->name }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h2, h3 { text-align: center; }
    </style>
</head>
<body>
    <h2>LAPORAN REKRUTMEN DAN ANGGOTA UKM</h2>
    <h3>{{ strtoupper($ukm->name) }}</h3>
    <hr>
    
    <p>Total Anggota Aktif: <strong>{{ count($memberships) }}</strong> Orang</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Anggota</th>
                <th>Email</th>
                <th>Fakultas</th>
                <th>Tanggal Bergabung</th>
                <th>Peran</th>
                <th>Klasifikasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($memberships as $index => $m)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $m->user->name }}</td>
                <td>{{ $m->user->email }}</td>
                <td>{{ $m->user->faculty ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($m->created_at)->format('d-m-Y') }}</td>
                <td>{{ ucfirst($m->role_in_ukm) }}</td>
                <td>{{ optional($m->classification)->name ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
