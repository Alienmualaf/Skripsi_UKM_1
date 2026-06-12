<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami | {{ $profile->name }}</title>
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
        <span class="text-[10px] font-black text-gold-600 uppercase tracking-widest">PROFIL RESMI</span>
        <h1 class="font-outfit font-black text-3xl sm:text-4xl text-navy-900 mt-2 mb-8">Tentang {{ $profile->name }}</h1>
        
        <div class="bg-white border border-slate-100 rounded-3xl p-8 shadow-sm leading-relaxed text-slate-600 space-y-6">
            <p class="text-sm sm:text-base">
                {{ $profile->description }}
            </p>
        </div>
    </main>
</body>
</html>
