<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Penampilan - PSUP</title>
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
        <span class="text-[10px] font-bold text-blue-600 uppercase tracking-widest">JADWAL KAMPUS</span>
        <h1 class="font-outfit font-black text-3xl sm:text-4xl text-slate-900 mt-2 mb-8">Jadwal Penampilan</h1>
        
        <div class="space-y-6">
            @forelse($agendas as $agenda)
                <div class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                    <div>
                        <span class="inline-block px-2 py-0.5 rounded-md text-[9px] font-bold bg-blue-100 text-blue-800 mb-2">{{ $agenda->status }}</span>
                        <h3 class="font-outfit font-bold text-slate-900 text-base">{{ $agenda->title }}</h3>
                        <p class="text-slate-500 text-xs mt-1 leading-relaxed">{{ $agenda->description }}</p>
                    </div>
                    <div class="text-xs text-slate-400 space-y-1 sm:text-right flex-shrink-0">
                        <div class="flex items-center sm:justify-end gap-2">
                            <i class="ph ph-calendar"></i>
                            <span>{{ date('d M Y', strtotime($agenda->performance_date)) }}</span>
                        </div>
                        <div class="flex items-center sm:justify-end gap-2">
                            <i class="ph ph-clock"></i>
                            <span>{{ $agenda->performance_time }} WIB</span>
                        </div>
                        <div class="flex items-center sm:justify-end gap-2">
                            <i class="ph ph-map-pin"></i>
                            <span>{{ $agenda->venue }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 text-slate-400 text-xs font-medium bg-white rounded-2xl border border-slate-100">
                    Belum ada jadwal penampilan saat ini.
                </div>
            @endforelse
        </div>
    </main>
</body>
</html>
