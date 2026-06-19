<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelatih | {{ $profile->name }}</title>
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
            <span class="text-[10px] font-black text-gold-500 uppercase tracking-[0.2em]">TIM PROFESIONAL</span>
            <h1 class="font-serif font-normal text-4xl sm:text-5xl text-navy-900 mt-3 mb-6">Pelatih Kami</h1>
            <div class="w-16 h-1 bg-gold-500 mx-auto rounded-full"></div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @forelse($trainers as $trainer)
                <div class="bg-white border border-slate-100 rounded-3xl p-8 shadow-md hover:shadow-xl transition-all duration-300 flex flex-col sm:flex-row items-center gap-6">
                    <div class="w-24 h-24 rounded-full overflow-hidden border-2 border-gold-500 p-1 shrink-0 relative bg-slate-50">
                        @if($trainer->photo)
                            <img src="{{ asset('storage/' . $trainer->photo) }}" alt="{{ $trainer->name }}" class="w-full h-full object-cover rounded-full">
                        @else
                            <div class="w-full h-full rounded-full bg-navy-50 flex items-center justify-center font-bold text-navy-800 text-3xl">
                                {{ substr($trainer->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div class="text-center sm:text-left space-y-2">
                        <span class="inline-block px-3 py-1 rounded-full text-[9px] font-black bg-gold-50 text-gold-700 border border-gold-100/50 uppercase tracking-widest">{{ $trainer->specialty }}</span>
                        <h3 class="font-serif font-normal text-xl text-navy-900">{{ $trainer->name }}</h3>
                        <p class="text-slate-500 text-xs flex items-center justify-center sm:justify-start gap-1">
                            <i class="ph ph-envelope text-sm"></i> {{ $trainer->email ?? 'Tidak ada email' }}
                        </p>
                        @if($trainer->phone)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $trainer->phone) }}" target="_blank" class="inline-flex text-xs text-navy-900 hover:text-gold-600 font-bold items-center gap-1.5 transition-colors mt-2">
                                <i class="ph ph-whatsapp-logo text-base"></i> Hubungi WhatsApp
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-2 text-center py-16 text-slate-400 text-sm font-medium bg-white rounded-3xl border border-slate-100 shadow-sm">
                    <i class="ph ph-users text-4xl mb-3 text-slate-200"></i>
                    <p>Belum ada data pelatih aktif.</p>
                </div>
            @endforelse
        </div>
    </main>
</body>
</html>
