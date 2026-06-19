<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visi & Misi | {{ $profile->name }}</title>
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
            <span class="text-[10px] font-black text-gold-500 uppercase tracking-[0.2em]">ARAH STRATEGIS</span>
            <h1 class="font-serif font-normal text-4xl sm:text-5xl text-navy-900 mt-3 mb-6">Visi & Misi</h1>
            <div class="w-16 h-1 bg-gold-500 mx-auto rounded-full"></div>
        </div>
        
        <div class="grid grid-cols-1 gap-12">
            <!-- Visi -->
            <div class="bg-white border border-slate-100 border-l-[6px] border-l-gold-500 rounded-r-3xl p-8 sm:p-10 shadow-md">
                <h3 class="font-serif font-normal text-2xl text-navy-900 mb-4">Visi Kami</h3>
                <div class="w-12 h-1 bg-gold-500 mb-6 rounded-full"></div>
                <blockquote class="relative">
                    <span class="absolute -top-10 -left-6 text-[8rem] font-serif text-gold-500/10 leading-none pointer-events-none">“</span>
                    <p class="font-serif text-lg sm:text-xl text-slate-750 leading-relaxed italic relative z-10 pl-2">
                        {{ $profile->vision }}
                    </p>
                </blockquote>
            </div>

            <!-- Misi -->
            <div class="bg-white border border-slate-100 border-l-[6px] border-l-navy-900 rounded-r-3xl p-8 sm:p-10 shadow-md">
                <h3 class="font-serif font-normal text-2xl text-navy-900 mb-4">Misi Kami</h3>
                <div class="w-12 h-1 bg-navy-900 mb-6 rounded-full"></div>
                <div class="space-y-5">
                    @php
                        $misiPoints = array_filter(explode("\n", $profile->mission));
                    @endphp
                    @forelse($misiPoints as $index => $misi)
                        <div class="flex gap-4 items-start">
                            <span class="w-7 h-7 rounded-lg bg-navy-50 border border-slate-200 flex items-center justify-center text-gold-600 text-xs font-bold shrink-0 shadow-sm">
                                {{ sprintf('%02d', $index + 1) }}
                            </span>
                            <span class="text-sm sm:text-base text-slate-700 leading-relaxed pt-0.5">{{ trim($misi) }}</span>
                        </div>
                    @empty
                        <p class="text-slate-400 text-xs">Misi belum ditentukan.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </main>
</body>
</html>
