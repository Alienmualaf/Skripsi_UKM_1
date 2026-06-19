<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sejarah | {{ $profile->name }}</title>
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

    <main class="max-w-4xl mx-auto py-20 px-6">
        <div class="text-center mb-16">
            <span class="text-[10px] font-black text-gold-500 uppercase tracking-[0.2em]">TIMELINE PERJALANAN</span>
            <h1 class="font-serif font-normal text-4xl sm:text-5xl text-navy-900 mt-3 mb-6">Sejarah Organisasi</h1>
            <div class="w-16 h-1 bg-gold-500 mx-auto rounded-full"></div>
        </div>
        
        <div class="relative border-l-2 border-slate-200 ml-4 md:ml-32">
            @forelse($histories as $history)
                <div class="mb-14 relative pl-8 md:pl-0">
                    <div class="absolute -left-[9px] top-2 w-[16px] h-[16px] rounded-full border-4 border-white bg-gold-500 shadow-md"></div>
                    
                    <div class="hidden md:block absolute -left-32 top-1 w-28 text-right">
                        <span class="font-serif font-normal text-2xl text-navy-900">{{ $history->year }}</span>
                    </div>

                    <div class="md:hidden block mb-2">
                        <span class="font-serif font-normal text-xl text-gold-600">{{ $history->year }}</span>
                    </div>

                    <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-8 shadow-md hover:shadow-xl transition-all duration-300">
                        <h4 class="font-serif font-normal text-xl text-navy-900 mb-3">{{ $history->title }}</h4>
                        <p class="text-slate-600 text-sm leading-relaxed mb-6">
                            {{ $history->description }}
                        </p>
                        @if($history->photo)
                            <div class="max-w-md rounded-2xl overflow-hidden border border-slate-100 cursor-pointer" onclick="zoomStructure('{{ asset('storage/' . $history->photo) }}')">
                                <img src="{{ asset('storage/' . $history->photo) }}" alt="{{ $history->title }}" class="w-full h-auto object-cover hover:scale-[1.01] transition-transform duration-300">
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-16 text-slate-400 text-sm font-medium pl-8 md:pl-0 bg-white border border-slate-100 rounded-3xl shadow-sm">
                    <i class="ph ph-hourglass text-4xl mb-3 text-slate-200"></i>
                    <p>Sejarah belum diunggah.</p>
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
