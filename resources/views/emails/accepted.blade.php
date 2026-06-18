<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Diterima - PSUP</title>
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
        .badge {
            display: inline-block;
            background-color: rgba(255, 205, 14, 0.15);
            color: #b58d04;
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 14px;
            margin-bottom: 20px;
        }
        .account-box {
            background-color: #f8fafc;
            border-left: 4px solid #FFCD0E;
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }
        .account-box table {
            width: 100%;
            border-collapse: collapse;
        }
        .account-box td {
            padding: 4px 0;
            font-size: 14px;
        }
        .account-box td.label {
            font-weight: bold;
            color: #64748b;
            width: 120px;
        }
        .account-box td.value {
            color: #00072D;
            font-family: monospace;
            font-weight: bold;
            font-size: 15px;
        }
        .btn {
            display: inline-block;
            background-color: #00072D;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 15px;
            margin: 20px 0;
            text-align: center;
            border: 2px solid #00072D;
            transition: all 0.2s ease;
        }
        .btn:hover {
            background-color: #FFCD0E;
            color: #00072D !important;
            border-color: #FFCD0E;
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
            <h2>Selamat bergabung, {{ $name }}!</h2>
            <p>Kami dengan bangga memberitahukan bahwa pendaftaran Anda di <strong>Paduan Suara Universitas Pancasila (PSUP)</strong> telah <strong>DISETUJUI</strong>.</p>
            
            <p>Berdasarkan hasil seleksi dan audisi klasifikasi suara, Anda dinyatakan masuk dalam kelompok suara:</p>
            <div class="badge">{{ $voice_classification }}</div>
            
            <p>Akun keanggotaan Anda telah dibuat secara otomatis. Berikut adalah detail informasi akun untuk masuk ke dalam sistem portal:</p>
            
            <div class="account-box">
                <table>
                    <tr>
                        <td class="label">Email:</td>
                        <td class="value" style="font-family: inherit;">{{ $email }}</td>
                    </tr>
                    <tr>
                        <td class="label">Password:</td>
                        <td class="value">{{ $password }}</td>
                    </tr>
                </table>
            </div>
            
            <p style="margin-bottom: 25px;">Silakan klik tombol di bawah ini untuk menuju ke halaman login portal:</p>
            
            <div style="text-align: center;">
                <a href="{{ $login_url }}" class="btn">Login ke Portal PSUP</a>
            </div>
            
            <p style="margin-top: 25px; color: #dc2626; font-weight: bold;">PENTING: Harap segera melakukan login pertama Anda dan melengkapi data profil serta mengganti password sementara Anda demi keamanan akun.</p>
            
            <p>Selamat berkarya dan mengembangkan potensi musik Anda bersama kami!</p>
            <p>Salam hangat,<br><strong>Pengurus PSUP</strong></p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Paduan Suara Universitas Pancasila. All Rights Reserved.</p>
            <p>Universitas Pancasila, Jl. Raya Lenteng Agung No.56-80, Jakarta Selatan</p>
        </div>
    </div>
</body>
</html>
