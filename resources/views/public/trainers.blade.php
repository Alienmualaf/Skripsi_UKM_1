<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelatih - PSUP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body class="bg-slate-50 text-slate-900 font-sans min-h-screen">
    <!-- Header -->
    <header class="bg-white border-b border-slate-200 py-6 px-8">
        <div class="max-w-4xl mx-auto flex items-center justify-between">
            <a href="/" class="flex items-center gap-3">
                <div class="w-8 h-8 bg-slate-100 border border-slate-200 rounded-lg flex items-center justify-center p-1">
                    <img src="{{ asset('images/logoup.png') }}" alt="Logo UP" class="w-full h-full object-contain">
                </div>
                <span class="font-outfit font-black text-sm tracking-tight">PSUP Portal</span>
            </a>
            <a href="/" class="text-xs font-bold text-slate-500 hover:text-slate-800 transition-colors uppercase tracking-wider">Kembali</a>
        </div>
    </header>

    <main class="max-w-4xl mx-auto py-16 px-6">
        <span class="text-[10px] font-bold text-blue-600 uppercase tracking-widest">TIM PROFESIONAL</span>
        <h1 class="font-outfit font-black text-3xl sm:text-4xl text-slate-900 mt-2 mb-8">Pelatih Kami</h1>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
            @forelse($trainers as $trainer)
                <div class="bg-white border border-slate-100 rounded-2xl p-6 flex gap-4 items-center">
                    <div class="w-20 h-20 rounded-full overflow-hidden bg-slate-100 flex-shrink-0">
                        @if($trainer->photo)
                            <img src="{{ asset('storage/' . $trainer->photo) }}" alt="{{ $trainer->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-400 text-xl bg-slate-100">
                                <i class="ph ph-user"></i>
                            </div>
                        @endif
                    </div>
                    <div>
                        <h3 class="font-outfit font-bold text-slate-900">{{ $trainer->name }}</h3>
                        <p class="text-amber-600 text-xs font-bold uppercase tracking-wide mt-1">{{ $trainer->specialty }}</p>
                        <p class="text-slate-500 text-xs mt-2">{{ $trainer->email ?? 'No email' }}</p>
                    </div>
                </div>
            @empty
                <div class="col-span-2 text-center py-12 text-slate-400 text-xs font-medium">
                    Belum ada data pelatih aktif.
                </div>
            @endforelse
        </div>
    </main>
</body>
</html>
