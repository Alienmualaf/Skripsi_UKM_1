<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prestasi - {{ $profile->alias }}</title>
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

    <main class="max-w-5xl mx-auto py-20 px-6">
        <div class="text-center mb-16">
            <span class="text-[10px] font-black text-gold-500 uppercase tracking-[0.2em]">PENGHARGAAN & PRESTASI</span>
            <h1 class="font-serif font-normal text-4xl sm:text-5xl text-navy-900 mt-3 mb-6">Prestasi Kami</h1>
            <div class="w-16 h-1 bg-gold-500 mx-auto rounded-full"></div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @forelse($achievements as $achievement)
                <div class="bg-white border border-slate-100 hover:border-gold-500/20 rounded-3xl p-6 shadow-md hover:shadow-xl transition-all duration-300 flex flex-col justify-between group">
                    <div>
                        @if($achievement->photo)
                            <div class="aspect-video w-full rounded-2xl overflow-hidden bg-slate-50 mb-5 relative">
                                <img src="{{ asset('storage/' . $achievement->photo) }}" alt="{{ $achievement->title }}" class="w-full h-full object-cover group-hover:scale-[1.02] transition-transform duration-500">
                            </div>
                        @else
                            <div class="aspect-video w-full rounded-2xl bg-navy-50 flex items-center justify-center text-gold-500 text-5xl mb-5">
                                <i class="ph ph-trophy"></i>
                            </div>
                        @endif
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-[9px] font-bold text-gold-600 tracking-widest uppercase bg-gold-50 px-2.5 py-1 rounded-full">{{ date('d M Y', strtotime($achievement->date)) }}</span>
                        </div>
                        <h3 class="font-serif font-normal text-xl text-navy-900 mt-1 mb-2">{{ $achievement->title }}</h3>
                        <p class="text-slate-600 text-sm leading-relaxed">{{ $achievement->description }}</p>
                    </div>
                </div>
            @empty
                <div class="col-span-2 text-center py-16 text-slate-400 text-sm font-medium bg-white rounded-3xl border border-slate-100 shadow-sm">
                    <i class="ph ph-trophy text-4xl mb-3 text-slate-200"></i>
                    <p>Belum ada data prestasi tercatat.</p>
                </div>
            @endforelse
        </div>
    </main>
</body>
</html>
