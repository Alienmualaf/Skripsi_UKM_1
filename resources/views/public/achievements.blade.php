<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prestasi - PSUP</title>
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
        <span class="text-[10px] font-bold text-blue-600 uppercase tracking-widest">PENGHARGAAN</span>
        <h1 class="font-outfit font-black text-3xl sm:text-4xl text-slate-900 mt-2 mb-8">Prestasi Kami</h1>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
            @forelse($achievements as $achievement)
                <div class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm">
                    @if($achievement->photo)
                        <div class="aspect-video w-full rounded-xl overflow-hidden bg-slate-100 mb-4">
                            <img src="{{ asset('storage/' . $achievement->photo) }}" alt="{{ $achievement->title }}" class="w-full h-full object-cover">
                        </div>
                    @endif
                    <span class="text-[10px] font-bold text-amber-600 tracking-wider uppercase">{{ date('d M Y', strtotime($achievement->date)) }}</span>
                    <h3 class="font-outfit font-bold text-slate-900 mt-1 text-base">{{ $achievement->title }}</h3>
                    <p class="text-slate-500 text-xs mt-2 leading-relaxed">{{ $achievement->description }}</p>
                </div>
            @empty
                <div class="col-span-2 text-center py-12 text-slate-400 text-xs font-medium">
                    Belum ada data prestasi tercatat.
                </div>
            @endforelse
        </div>
    </main>
</body>
</html>
