<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 12px; line-height: 1.6; padding: 40px; }
        .letter-frame { border: 1px solid #000; padding: 30px; min-height: 800px; position: relative; }
        .kop { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .date { text-align: right; margin-bottom: 20px; }
        .subject { margin-bottom: 20px; font-weight: bold; }
        .body { min-height: 300px; margin-bottom: 50px; }
        .footer-table { width: 100%; border: none; }
        .attachment-note { margin-top: 30px; border-top: 1px solid #eee; padding-top: 10px; font-size: 10px; color: #666; text-align: center; }
    </style>
</head>
<body>
    <div class="letter-frame">
        <div class="kop">
            <h2 style="margin: 0;">UKM {{ strtoupper($ukm->name) }}</h2>
            <p style="margin: 5px 0;">Universitas Pancasila</p>
        </div>

        <div class="date">
            {{ $sub->created_at->format('d F Y') }}
        </div>

        <div class="subject">
            Perihal: {{ $sub->title }}<br>
            Kategori: {{ optional($sub->category)->name ?? 'Umum' }}
        </div>

        <div class="body">
            {!! nl2br(e($sub->description)) !!}
        </div>

        <table class="footer-table">
            <tr>
                <td style="width: 60%;"></td>
                <td style="text-align: center;">
                    Hormat Kami,<br>
                    UKM {{ $ukm->name }}<br><br><br><br>
                    ( ____________________ )
                </td>
            </tr>
        </table>

        @if($sub->attachment)
            <div class="attachment-note">
                @php
                    $ext = pathinfo($sub->attachment, PATHINFO_EXTENSION);
                    $isImage = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif']);
                @endphp
                
                @if($isImage)
                    <p>[ Gambar Lampiran ]</p>
                    <img src="{{ public_path('storage/' . $sub->attachment) }}" style="max-width: 100%; max-height: 250px; border: 1px solid #ddd;">
                @else
                    <p><strong>DOKUMEN ASLI TERLAMPIR DI HALAMAN BERIKUTNYA</strong></p>
                    <p>Berkas: {{ basename($sub->attachment) }} (ID: #SUB-{{ $sub->id }})</p>
                @endif
            </div>
        @endif
    </div>
</body>
</html>
