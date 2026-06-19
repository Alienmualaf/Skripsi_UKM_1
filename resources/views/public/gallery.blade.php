<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri | {{ $profile->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        serif: ['Georgia', 'serif'],
                    },
                    colors: {
                        navy: {
                            800: '#0A1128',
                            900: '#0a0b10',
                            950: '#000314'
                        },
                        gold: {
                            400: '#F3D279',
                            500: '#c5a059',
                            600: '#a37f3d',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-white text-navy-800 font-sans antialiased min-h-screen">
    <!-- Header (Sticky, Dark Navy) -->
    <header class="sticky top-0 z-50 bg-[#0a0b10] border-b border-white/10 py-5 px-8 shadow-md">
        <div class="max-w-6xl mx-auto flex items-center justify-between">
            <a href="/" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-full overflow-hidden border border-gold-500 bg-white p-0.5 transition-transform group-hover:scale-105 shrink-0">
                    @if($profile->logo)
                        <img src="{{ asset('storage/' . $profile->logo) }}" alt="Logo" class="w-full h-full object-contain">
                    @else
                        <img src="{{ asset('images/logo_PSUP.jpeg') }}" alt="Logo PSUP" class="w-full h-full object-contain">
                    @endif
                </div>
                <span class="font-sans font-bold text-xs sm:text-sm tracking-wider text-white uppercase group-hover:text-gold-400 transition-colors">{{ $profile->alias }} Portal</span>
            </a>
            <a href="/" class="text-[10px] sm:text-xs font-bold text-white/70 hover:text-gold-400 transition-colors uppercase tracking-widest border border-white/20 hover:border-gold-500 px-4 py-2 transition-all">Kembali ke Beranda</a>
        </div>
    </header>

    <main class="max-w-6xl mx-auto py-20 px-6">
        <div class="text-center mb-16">
            <span class="text-[10px] font-black text-gold-500 uppercase tracking-[0.2em]">DOKUMENTASI FOTO</span>
            <h1 class="font-serif font-normal text-4xl sm:text-5xl text-navy-900 mt-3 mb-6">Galeri Kegiatan</h1>
            <div class="w-16 h-1 bg-gold-500 mx-auto rounded-full"></div>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($galleries as $gallery)
                <div class="bg-white border border-slate-100 rounded-3xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 group cursor-pointer" onclick="zoomStructure('{{ asset('storage/' . $gallery->file_path) }}')">
                    <div class="aspect-video overflow-hidden bg-slate-50 relative">
                        <img src="{{ asset('storage/' . $gallery->file_path) }}" alt="{{ $gallery->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-navy-950/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <span class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-navy-900 shadow-md">
                                <i class="ph ph-magnifying-glass-plus text-lg font-bold"></i>
                            </span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h4 class="font-sans font-bold text-sm text-navy-900 line-clamp-1 leading-tight">{{ $gallery->title }}</h4>
                        <p class="text-slate-500 text-xs mt-2 line-clamp-2 leading-relaxed">{{ $gallery->description }}</p>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-16 text-slate-400 text-sm font-medium bg-white rounded-3xl border border-slate-100 shadow-sm">
                    <i class="ph ph-image text-4xl mb-3 text-slate-200"></i>
                    <p>Belum ada foto dokumentasi galeri.</p>
                </div>
            @endforelse
        </div>
    </main>

    <!-- Modal Image Zoom -->
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
