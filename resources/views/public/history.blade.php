<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sejarah | {{ $profile->name }}</title>
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
        <span class="text-[10px] font-black text-gold-600 uppercase tracking-widest">TIMELINE PERJALANAN</span>
        <h1 class="font-outfit font-black text-3xl sm:text-4xl text-navy-900 mt-2 mb-8">Sejarah Organisasi</h1>
        
        <div class="relative border-l-2 border-slate-200 ml-4 md:ml-32">
            @forelse($histories as $history)
                <div class="mb-12 relative pl-8 md:pl-0">
                    <div class="absolute -left-[9px] top-1.5 w-4.5 h-4.5 rounded-full border-4 border-white bg-gold-500 shadow-md"></div>
                    
                    <div class="hidden md:block absolute -left-32 top-0 w-24 text-right">
                        <span class="font-outfit font-black text-2xl text-navy-900">{{ $history->year }}</span>
                    </div>

                    <div class="md:hidden block mb-2">
                        <span class="font-outfit font-black text-xl text-gold-600">{{ $history->year }}</span>
                    </div>

                    <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
                        <h4 class="font-outfit font-black text-lg text-navy-900 mb-2">{{ $history->title }}</h4>
                        <p class="text-slate-500 text-sm leading-relaxed mb-4">
                            {{ $history->description }}
                        </p>
                        @if($history->photo)
                            <div class="max-w-md rounded-2xl overflow-hidden border border-slate-200">
                                <img src="{{ asset('storage/' . $history->photo) }}" alt="{{ $history->title }}" class="w-full h-auto object-cover">
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-12 text-slate-400 text-xs font-medium pl-8 md:pl-0 bg-white border border-slate-100 rounded-3xl">
                    Sejarah belum diunggah.
                </div>
            @endforelse
        </div>
    </main>
</body>
</html>
