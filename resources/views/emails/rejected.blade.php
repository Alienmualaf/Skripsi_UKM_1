<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemberitahuan Pendaftaran - PSUP</title>
    <style>
        body {
            font-family: 'Inter', Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 7, 45, 0.08);
            border: 1px solid #e2e8f0;
        }
        .header {
            background-color: #00072D;
            padding: 30px 20px;
            text-align: center;
            border-bottom: 4px solid #FFCD0E;
        }
        .header img {
            max-height: 80px;
            margin-bottom: 10px;
        }
        .header h1 {
            color: #ffffff;
            font-size: 20px;
            margin: 0;
            font-weight: 800;
            letter-spacing: 0.5px;
        }
        .content {
            padding: 30px 25px;
            color: #334155;
            line-height: 1.6;
        }
        .content h2 {
            color: #00072D;
            font-size: 22px;
            font-weight: 800;
            margin-top: 0;
            margin-bottom: 15px;
        }
        .footer {
            background-color: #f8fafc;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
        }
        .footer p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            @if(file_exists(public_path('images/logo_PSUP.jpeg')))
                <img src="{{ $message->embed(public_path('images/logo_PSUP.jpeg')) }}" alt="Logo PSUP">
            @elseif(file_exists(public_path('images/logoup.png')))
                <img src="{{ $message->embed(public_path('images/logoup.png')) }}" alt="Logo PSUP">
            @endif
            <h1>PADUAN SUARA UNIVERSITAS PANCASILA</h1>
        </div>
        <div class="content">
            <h2>Terima kasih atas partisipasi Anda</h2>
            <p>Halo {{ $name }},</p>
            
            <p>Kami mengucapkan terima kasih yang sebesar-besarnya atas minat dan partisipasi Anda dalam mengikuti proses rekrutmen serta seleksi calon anggota baru <strong>Paduan Suara Universitas Pancasila (PSUP)</strong>.</p>
            
            <p>Kami ingin menginformasikan bahwa pendaftaran Anda belum dapat kami terima pada periode rekrutmen saat ini. Seleksi tahun ini berjalan sangat ketat dengan kuota yang terbatas.</p>
            
            <p>Kami sangat menghargai bakat, waktu, dan usaha yang Anda tunjukkan selama proses pendaftaran. Jangan berkecil hati, kami sangat berharap Anda dapat terus melatih kemampuan vokal Anda dan mengikuti kembali proses rekrutmen PSUP pada periode berikutnya.</p>
            
            <p>Semoga sukses untuk studi Anda di Universitas Pancasila.</p>
            
            <p>Salam hangat,<br><strong>Pengurus PSUP</strong></p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Paduan Suara Universitas Pancasila. All Rights Reserved.</p>
            <p>Universitas Pancasila, Jl. Raya Lenteng Agung No.56-80, Jakarta Selatan</p>
        </div>
    </div>
</body>
</html>
