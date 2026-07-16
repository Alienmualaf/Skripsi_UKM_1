<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('doc-title', 'Laporan') — PSUP</title>
<style>
    /* ── PDF Viewer Shell ── */
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        background: #3d3d3d;
        font-family: Arial, sans-serif;
        min-height: 100vh;
    }

    /* ── Toolbar ── */
    .pdf-toolbar {
        position: fixed;
        top: 0; left: 0; right: 0;
        height: 52px;
        background: #2c2c2c;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0 1.25rem;
        z-index: 9999;
        box-shadow: 0 2px 8px rgba(0,0,0,0.4);
    }
    .pdf-toolbar .doc-name {
        flex: 1;
        color: #e0e0e0;
        font-size: 0.875rem;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .pdf-toolbar .page-info {
        color: #9ca3af;
        font-size: 0.8rem;
        white-space: nowrap;
    }
    .toolbar-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.45rem 1rem;
        border-radius: 6px;
        font-size: 0.8125rem;
        font-weight: 700;
        cursor: pointer;
        border: none;
        text-decoration: none;
        white-space: nowrap;
        transition: opacity 0.15s;
    }
    .toolbar-btn:hover { opacity: 0.85; }
    .toolbar-btn.secondary { background: #4b5563; color: #e5e7eb; }
    .toolbar-back {
        color: #9ca3af;
        text-decoration: none;
        font-size: 0.8125rem;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.45rem 0.75rem;
        border-radius: 6px;
        transition: background 0.15s;
    }
    .toolbar-back:hover { background: rgba(255,255,255,0.08); color: #e5e7eb; }

    /* ── A4 Scroll Area ── */
    .pdf-scroll-area {
        padding-top: 72px;
        padding-bottom: 48px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0;
    }

    /* ── A4 Sheet ── */
    .a4-page {
        width: 210mm;
        min-height: 297mm;
        background: #ffffff;
        box-shadow: 0 4px 24px rgba(0,0,0,0.35);
        padding: 3cm 3cm 2.5cm 3cm;
        position: relative;

        /*
         * Horizontal grey line every 297mm — shows where
         * the printer will split pages.
         */
        background-image: repeating-linear-gradient(
            to bottom,
            transparent 0,
            transparent calc(297mm - 1px),
            #d1d5db calc(297mm - 1px),
            #d1d5db 297mm
        );
    }

    /* ── Page separator inserted by JS between .a4-page divs ── */
    .page-sep {
        width: 210mm;
        height: 28px;
        background: #3d3d3d;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }
    .page-sep::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0; right: 0;
        height: 1px;
        background: #6b7280;
    }
    .page-sep span {
        background: #3d3d3d;
        color: #6b7280;
        font-size: 10px;
        padding: 0 8px;
        position: relative;
        z-index: 1;
        font-family: Arial, sans-serif;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    /* ── Document Content Styles ── */
    .a4-page {
        font-family: 'Times New Roman', Times, serif;
        font-size: 11pt;
        color: #000000;
        line-height: 1.5;
    }
    .kop-surat {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 5px;
        padding-bottom: 5px;
    }
    .kop-surat img { height: 80px; width: auto; }
    .kop-tengah { text-align: center; flex-grow: 1; }
    .kop-tengah h2 {
        font-family: 'Times New Roman', Times, serif;
        font-size: 14pt; font-weight: bold;
        color: #000; margin: 0; line-height: 1.2;
    }
    .kop-tengah h3 {
        font-family: 'Times New Roman', Times, serif;
        font-size: 12pt; font-weight: bold;
        color: #000; margin: 0; line-height: 1.2;
    }
    .kop-line {
        border: none;
        border-top: 2px solid #000;
        border-bottom: 0.5px solid #000;
        height: 4px; margin-top: 2px; margin-bottom: 20px;
    }
    .report-title {
        text-align: center; font-size: 14pt;
        font-weight: bold; margin-bottom: 20px;
        text-transform: uppercase;
    }
    .section { margin-bottom: 20px; }
    .section-title {
        font-size: 11pt; font-weight: bold;
        text-transform: uppercase;
        border-bottom: 1px solid #000;
        padding-bottom: 4px; margin-bottom: 10px;
    }
    table { width: 100%; border-collapse: collapse; font-size: 10pt; margin-bottom: 10px; }
    th { background: #f2f2f2; color: #000; font-weight: bold; padding: 6px 8px; text-align: left; border: 1px solid #000; }
    td { padding: 6px 8px; border: 1px solid #000; color: #000; }
    .kv-table { width: 100%; font-size: 11pt; }
    .kv-table td { padding: 4px 0; border: none; }
    .kv-table td:first-child { width: 35%; font-weight: bold; }
    .doc-footer {
        margin-top: 30px;
        text-align: center;
        font-size: 9pt;
        color: #555;
        border-top: 1px solid #000;
        padding-top: 10px;
    }

    /* ── Print CSS ── */
    @media print {
        body { background: transparent !important; }
        .pdf-toolbar { display: none !important; }
        .page-sep { display: none !important; }
        .pdf-scroll-area { padding-top: 0 !important; padding-bottom: 0 !important; }
        .a4-page {
            box-shadow: none !important;
            background-image: none !important;
            background: white !important;
            page-break-after: always;
            width: 100% !important;
            min-height: auto !important;
            padding: 0 !important;
        }
        @page { size: A4; margin-top: 3cm; margin-left: 3cm; margin-right: 2.5cm; margin-bottom: 2.5cm; }
    }

    /* ── Responsive ── */
    @media (max-width: 900px) {
        .a4-page {
            width: calc(100vw - 16px) !important;
            padding: 1.25cm !important;
            min-height: auto !important;
            background-image: none !important;
        }
        .page-sep { width: calc(100vw - 16px); }
    }
</style>
</head>
<body>

{{-- ── Toolbar ── --}}
<div class="pdf-toolbar">
    <a href="javascript:history.back()" class="toolbar-back">&#8592; Kembali</a>
    <div class="doc-name">@yield('doc-title', 'Laporan PSUP')</div>
    <div class="page-info" id="page-info"></div>
    <button class="toolbar-btn secondary" onclick="window.print()">&#128438; Cetak</button>
</div>

{{-- ── A4 Viewer ── --}}
<div class="pdf-scroll-area" id="pdf-area">
    @yield('a4-content')
</div>

<script>
    window.addEventListener('DOMContentLoaded', function () {
        var area     = document.getElementById('pdf-area');
        var info     = document.getElementById('page-info');
        var children = Array.from(area.children);

        // Insert .page-sep between consecutive .a4-page siblings
        var pageNum = 1;
        for (var i = 0; i < children.length; i++) {
            var el   = children[i];
            var next = children[i + 1];
            if (el.classList.contains('a4-page') && next && next.classList.contains('a4-page')) {
                pageNum++;
                var sep = document.createElement('div');
                sep.className = 'page-sep';
                sep.innerHTML = '<span>Halaman ' + pageNum + '</span>';
                area.insertBefore(sep, next);
            }
        }

        // Update page count in toolbar
        var totalPages = document.querySelectorAll('.a4-page').length;
        if (info && totalPages > 1) {
            info.textContent = totalPages + ' halaman';
        }
    });
</script>
</body>
</html>
