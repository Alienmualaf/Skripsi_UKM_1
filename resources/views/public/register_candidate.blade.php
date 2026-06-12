<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Calon Anggota - PSUP</title>
    <!-- Tailwind & Google Fonts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body class="bg-slate-50 text-slate-900 font-sans min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">

<div class="max-w-xl w-full space-y-8 bg-white p-8 rounded-3xl border border-slate-100 shadow-xl">
    
    <!-- Header -->
    <div class="text-center">
        <a href="/" class="inline-flex items-center gap-2 mb-4">
            <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center p-1.5 border border-slate-200">
                <img src="{{ asset('images/logoup.png') }}" alt="Logo UP" class="w-full h-full object-contain">
            </div>
            <span class="font-outfit font-black text-sm text-slate-800 uppercase tracking-wider">PSUP Portal</span>
        </a>
        <h2 class="font-outfit font-black text-2xl text-slate-900">Registrasi Calon Anggota</h2>
        <p class="text-slate-500 text-xs sm:text-sm mt-2">Lengkapi formulir di bawah untuk bergabung dengan Paduan Suara Universitas Pancasila.</p>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-xl text-xs sm:text-sm flex items-start gap-2">
            <i class="ph-fill ph-check-circle text-lg flex-shrink-0"></i>
            <div>
                <span class="font-bold">Sukses!</span> {{ session('success') }}
                <a href="/" class="block mt-2 underline font-bold">Kembali ke Beranda</a>
            </div>
        </div>
    @endif

    <!-- Form -->
    @if(!session('success'))
    <form action="{{ route('register-candidate.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1">Nama Lengkap</label>
                <input type="text" name="name" id="name" required value="{{ old('name') }}" 
                       class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-blue-500 transition-colors @error('name') border-red-500 @enderror">
                @error('name') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="npm" class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1">NPM</label>
                <input type="text" name="npm" id="npm" required value="{{ old('npm') }}" 
                       class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-blue-500 transition-colors @error('npm') border-red-500 @enderror">
                @error('npm') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="gender" class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1">Jenis Kelamin</label>
                <select name="gender" id="gender" required 
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-blue-500 transition-colors bg-white">
                    <option value="L" {{ old('gender') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('gender') === 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('gender') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="class_year" class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1">Angkatan</label>
                <input type="text" name="class_year" id="class_year" required placeholder="Contoh: 2024" value="{{ old('class_year') }}" 
                       class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-blue-500 transition-colors @error('class_year') border-red-500 @enderror">
                @error('class_year') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="faculty" class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1">Fakultas</label>
                <input type="text" name="faculty" id="faculty" required value="{{ old('faculty') }}" 
                       class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-blue-500 transition-colors @error('faculty') border-red-500 @enderror">
                @error('faculty') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="major" class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1">Program Studi</label>
                <input type="text" name="major" id="major" required value="{{ old('major') }}" 
                       class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-blue-500 transition-colors @error('major') border-red-500 @enderror">
                @error('major') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1">No. WhatsApp</label>
                <input type="text" name="phone" id="phone" required placeholder="08..." value="{{ old('phone') }}" 
                       class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-blue-500 transition-colors @error('phone') border-red-500 @enderror">
                @error('phone') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1">Alamat Email</label>
                <input type="email" name="email" id="email" required value="{{ old('email') }}" 
                       class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-blue-500 transition-colors @error('email') border-red-500 @enderror">
                @error('email') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1">Password</label>
                <input type="password" name="password" id="password" required placeholder="Minimal 6 karakter"
                       class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-blue-500 transition-colors @error('password') border-red-500 @enderror">
                @error('password') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="Masukkan kembali password"
                       class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-blue-500 transition-colors">
            </div>
        </div>

        <div>
            <label for="choir_experience" class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1">Pengalaman Bernyanyi / Paduan Suara</label>
            <textarea name="choir_experience" id="choir_experience" rows="3" placeholder="Tuliskan pengalaman Anda jika ada..." 
                      class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-blue-500 transition-colors">{{ old('choir_experience') }}</textarea>
            @error('choir_experience') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="photo" class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1">Foto Formal (JPG/PNG, Max 2MB)</label>
            <input type="file" name="photo" id="photo" 
                   class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            @error('photo') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center justify-between pt-4">
            <a href="/" class="text-xs font-bold text-slate-500 hover:text-slate-800 transition-colors uppercase tracking-wider">Kembali</a>
            <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-500 text-white px-8 py-3 rounded-xl text-xs font-black tracking-wider uppercase shadow-md transition-all">
                Kirim Pendaftaran
            </button>
        </div>
    </form>
    @endif

</div>

</body>
</html>
