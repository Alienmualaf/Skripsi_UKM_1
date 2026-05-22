<!DOCTYPE html>
<html>
<head>
    <title>Laporan Kegiatan - {{ $ukm->name }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h2, h3 { text-align: center; }
    </style>
</head>
<body>
    <h2>LAPORAN KEGIATAN UKM</h2>
    <h3>{{ strtoupper($ukm->name) }}</h3>
    <hr>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kegiatan</th>
                <th>Lokasi</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Selesai</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($events as $index => $event)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $event->title }}<br><small>{{ $event->description }}</small></td>
                <td>{{ $event->location ?? '-' }}</td>
                <td>{{ $event->start_date }}</td>
                <td>{{ $event->end_date }}</td>
                <td>{{ ucfirst($event->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
