<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struktur Organisasi | {{ $profile->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        outfit: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        navy: { 900: '#0B132B' },
                        gold: { 500: '#D4AF37' }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 text-slate-900 font-sans min-h-screen">
    <!-- Header -->
    <header class="bg-white border-b border-slate-100 py-5 px-8 shadow-sm">
        <div class="max-w-4xl mx-auto flex items-center justify-between">
            <a href="/" class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full overflow-hidden border border-gold-500">
                    @if($profile->logo)
                        <img src="{{ asset('storage/' . $profile->logo) }}" alt="Logo" class="w-full h-full object-cover">
                    @else
                        <img src="{{ asset('images/logo_PSUP.jpeg') }}" alt="Logo PSUP" class="w-full h-full object-cover">
                    @endif
                </div>
                <span class="font-outfit font-black text-sm tracking-tight text-navy-900">{{ $profile->alias }} Portal</span>
            </a>
            <a href="/" class="text-xs font-bold text-slate-500 hover:text-navy-900 transition-colors uppercase tracking-wider">Kembali ke Beranda</a>
        </div>
    </header>

    <main class="max-w-4xl mx-auto py-16 px-6">
        <span class="text-[10px] font-black text-gold-600 uppercase tracking-widest">BAGAN KEPENGURUSAN RESMI</span>
        <h1 class="font-outfit font-black text-3xl sm:text-4xl text-navy-900 mt-2 mb-8">Struktur Organisasi</h1>
        
        <div class="text-center">
            @if($profile->structure_image)
                <div class="relative group cursor-pointer overflow-hidden rounded-2xl max-w-3xl mx-auto shadow-md border border-slate-200" onclick="zoomStructure('{{ asset('storage/' . $profile->structure_image) }}')">
                    <img src="{{ asset('storage/' . $profile->structure_image) }}" alt="Struktur Organisasi {{ $profile->alias }}" class="w-full h-auto transition-transform duration-500 group-hover:scale-[1.01]">
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                        <span class="bg-white text-navy-900 px-4 py-2 rounded-lg font-bold text-xs shadow-md flex items-center gap-2">
                            <i class="ph ph-magnifying-glass-plus"></i> Klik untuk Perbesar
                        </span>
                    </div>
                </div>
            @else
                <div class="bg-white border border-slate-100 rounded-3xl p-16 text-slate-400">
                    <i class="ph ph-tree-structure text-5xl mb-4 text-slate-300"></i>
                    <p class="font-bold text-sm text-slate-500">Bagan Struktur Organisasi Belum Tersedia</p>
                    <p class="text-xs text-slate-400 mt-1">Gambar struktur kepengurusan resmi akan segera diunggah.</p>
                </div>
            @endif
        </div>
    </main>

    <!-- modal Image Zoom -->
    <div id="zoomModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); z-index: 99999; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.3s ease;">
        <button onclick="closeZoom()" style="position: absolute; top: 20px; right: 30px; font-size: 2.5rem; color: white; cursor: pointer; background: none; border: none; font-weight: bold;">&times;</button>
        <img id="zoomedImage" style="max-width: 90%; max-height: 90%; border-radius: 8px; box-shadow: 0 0 25px rgba(0,0,0,0.5);">
    </div>

    <script>
        function zoomStructure(src) {
            const modal = document.getElementById('zoomModal');
            const img = document.getElementById('zoomedImage');
            img.src = src;
            modal.style.display = 'flex';
            setTimeout(() => {
                modal.style.opacity = '1';
            }, 10);
        }

        function closeZoom() {
            const modal = document.getElementById('zoomModal');
            modal.style.opacity = '0';
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);
        }
    </script>
</body>
</html>
