<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri - PSUP</title>
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
        <span class="text-[10px] font-bold text-blue-600 uppercase tracking-widest">DOKUMENTASI</span>
        <h1 class="font-outfit font-black text-3xl sm:text-4xl text-slate-900 mt-2 mb-8">Galeri Kegiatan</h1>
        
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-6">
            @forelse($galleries as $gallery)
                <div class="bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-sm">
                    <div class="aspect-video bg-slate-100">
                        <img src="{{ asset('storage/' . $gallery->file_path) }}" alt="{{ $gallery->title }}" class="w-full h-full object-cover">
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-xs text-slate-900">{{ $gallery->title }}</h3>
                        <p class="text-slate-500 text-[10px] mt-1">{{ $gallery->description }}</p>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12 text-slate-400 text-xs font-medium bg-white rounded-2xl border border-slate-100">
                    Belum ada foto dokumentasi galeri.
                </div>
            @endforelse
        </div>
    </main>
</body>
</html>
