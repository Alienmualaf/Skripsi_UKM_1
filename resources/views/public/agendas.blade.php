<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Penampilan | {{ $profile->name }}</title>
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
            <span class="text-[10px] font-black text-gold-500 uppercase tracking-[0.2em]">SHOWCASE & KEGIATAN</span>
            <h1 class="font-serif font-normal text-4xl sm:text-5xl text-navy-900 mt-3 mb-6">Jadwal Penampilan</h1>
            <div class="w-16 h-1 bg-gold-500 mx-auto rounded-full"></div>
        </div>
        
        <div class="space-y-6">
            @forelse($agendas as $agenda)
                <div class="bg-white border border-slate-100 rounded-3xl p-8 shadow-md hover:shadow-xl transition-all duration-300 flex flex-col sm:flex-row justify-between sm:items-center gap-6 border-l-[6px] border-l-gold-500">
                    <div class="space-y-2">
                        <span class="inline-block px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-wider
                            @if($agenda->status == 'Selesai') bg-emerald-50 text-emerald-800 border border-emerald-100
                            @elseif($agenda->status == 'Berjalan') bg-blue-50 text-blue-800 border border-blue-100
                            @else bg-amber-50 text-amber-800 border border-amber-100 @endif">
                            {{ $agenda->status }}
                        </span>
                        <h3 class="font-serif font-normal text-xl text-navy-900">{{ $agenda->title }}</h3>
                        <p class="text-slate-600 text-sm leading-relaxed">{{ $agenda->description }}</p>
                    </div>
                    <div class="text-xs text-slate-500 space-y-2 sm:text-right shrink-0 border-t sm:border-t-0 pt-4 sm:pt-0 border-slate-100">
                        <div class="flex items-center sm:justify-end gap-2">
                            <i class="ph ph-calendar text-gold-500 text-sm"></i>
                            <span>{{ date('d F Y', strtotime($agenda->performance_date)) }}</span>
                        </div>
                        @if($agenda->performance_time)
                            <div class="flex items-center sm:justify-end gap-2">
                                <i class="ph ph-clock text-gold-500 text-sm"></i>
                                <span>{{ $agenda->performance_time }} WIB</span>
                            </div>
                        @endif
                        <div class="flex items-center sm:justify-end gap-2">
                            <i class="ph ph-map-pin text-gold-500 text-sm"></i>
                            <span>{{ $agenda->venue }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-16 text-slate-400 text-sm font-medium bg-white rounded-3xl border border-slate-100 shadow-sm">
                    <i class="ph ph-calendar-blank text-4xl mb-3 text-slate-200"></i>
                    <p>Belum ada jadwal penampilan saat ini.</p>
                </div>
            @endforelse
        </div>
    </main>
</body>
</html>
