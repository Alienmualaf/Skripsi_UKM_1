<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Calon Anggota | {{ $profile->alias }}</title>
    <!-- Tailwind & Google Fonts -->
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
<body class="bg-navy-950 text-navy-800 font-sans antialiased min-h-screen flex items-center justify-center py-16 px-4 sm:px-6 lg:px-8 relative overflow-x-hidden">
    <!-- Decorative background elements -->
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-gold-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-gold-500/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-2xl w-full space-y-8 bg-white p-8 sm:p-12 rounded-[36px] border border-white/10 shadow-2xl relative z-10 my-8">
        
        <!-- Header -->
        <div class="text-center">
            <a href="/" class="inline-flex items-center gap-3 mb-6 group">
                <div class="w-12 h-12 rounded-full overflow-hidden border border-gold-500 bg-white p-0.5 transition-transform group-hover:scale-105 shrink-0">
                    @if($profile->logo)
                        <img src="{{ asset('storage/' . $profile->logo) }}" alt="Logo" class="w-full h-full object-contain">
                    @else
                        <img src="{{ asset('images/logo_PSUP.jpeg') }}" alt="Logo PSUP" class="w-full h-full object-contain">
                    @endif
                </div>
                <span class="font-sans font-bold text-xs tracking-wider text-navy-900 uppercase tracking-widest">{{ $profile->alias }} Portal</span>
            </a>
            <h2 class="font-serif font-normal text-3xl text-navy-900">Registrasi Calon Anggota</h2>
            <p class="text-slate-500 text-xs sm:text-sm mt-3 max-w-md mx-auto leading-relaxed">Lengkapi formulir di bawah untuk bergabung dengan Paduan Suara Universitas Pancasila.</p>
            <div class="w-16 h-1 bg-gold-500 mx-auto mt-5 rounded-full"></div>
        </div>

        <!-- Alert Success -->
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-6 rounded-2xl text-xs sm:text-sm flex items-start gap-3 shadow-sm">
                <i class="ph-fill ph-check-circle text-2xl text-emerald-600 flex-shrink-0"></i>
                <div>
                    <span class="font-bold text-base block mb-1">Sukses!</span> {{ session('success') }}
                    <a href="/" class="inline-block mt-4 bg-navy-900 hover:bg-navy-800 text-white font-bold text-xs uppercase tracking-wider px-5 py-2.5 rounded-xl transition-all shadow-md">Kembali ke Beranda</a>
                </div>
            </div>
        @endif

        <!-- Form -->
        @if(!session('success'))
        <form action="{{ route('register-candidate.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="name" class="block text-[11px] font-black text-navy-900 uppercase tracking-wider mb-2">Nama Lengkap</label>
                    <input type="text" name="name" id="name" required value="{{ old('name') }}" 
                           class="w-full border border-slate-200 focus:border-gold-500 focus:ring-1 focus:ring-gold-500 rounded-xl px-4 py-3 text-sm focus:outline-none transition-colors @error('name') border-red-500 @enderror">
                    @error('name') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="npm" class="block text-[11px] font-black text-navy-900 uppercase tracking-wider mb-2">NPM</label>
                    <input type="text" name="npm" id="npm" required value="{{ old('npm') }}" 
                           class="w-full border border-slate-200 focus:border-gold-500 focus:ring-1 focus:ring-gold-500 rounded-xl px-4 py-3 text-sm focus:outline-none transition-colors @error('npm') border-red-500 @enderror">
                    @error('npm') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="gender" class="block text-[11px] font-black text-navy-900 uppercase tracking-wider mb-2">Jenis Kelamin</label>
                    <select name="gender" id="gender" required 
                            class="w-full border border-slate-200 focus:border-gold-500 focus:ring-1 focus:ring-gold-500 rounded-xl px-4 py-3 text-sm focus:outline-none transition-colors bg-white">
                        <option value="L" {{ old('gender') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('gender') === 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('gender') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="class_year" class="block text-[11px] font-black text-navy-900 uppercase tracking-wider mb-2">Angkatan</label>
                    <input type="text" name="class_year" id="class_year" required placeholder="Contoh: 2024" value="{{ old('class_year') }}" 
                           class="w-full border border-slate-200 focus:border-gold-500 focus:ring-1 focus:ring-gold-500 rounded-xl px-4 py-3 text-sm focus:outline-none transition-colors @error('class_year') border-red-500 @enderror">
                    @error('class_year') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="faculty" class="block text-[11px] font-black text-navy-900 uppercase tracking-wider mb-2">Fakultas</label>
                    <input type="text" name="faculty" id="faculty" required value="{{ old('faculty') }}" 
                           class="w-full border border-slate-200 focus:border-gold-500 focus:ring-1 focus:ring-gold-500 rounded-xl px-4 py-3 text-sm focus:outline-none transition-colors @error('faculty') border-red-500 @enderror">
                    @error('faculty') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="major" class="block text-[11px] font-black text-navy-900 uppercase tracking-wider mb-2">Program Studi</label>
                    <input type="text" name="major" id="major" required value="{{ old('major') }}" 
                           class="w-full border border-slate-200 focus:border-gold-500 focus:ring-1 focus:ring-gold-500 rounded-xl px-4 py-3 text-sm focus:outline-none transition-colors @error('major') border-red-500 @enderror">
                    @error('major') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="phone" class="block text-[11px] font-black text-navy-900 uppercase tracking-wider mb-2">No. WhatsApp</label>
                    <input type="text" name="phone" id="phone" required placeholder="08..." value="{{ old('phone') }}" 
                           class="w-full border border-slate-200 focus:border-gold-500 focus:ring-1 focus:ring-gold-500 rounded-xl px-4 py-3 text-sm focus:outline-none transition-colors @error('phone') border-red-500 @enderror">
                    @error('phone') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="block text-[11px] font-black text-navy-900 uppercase tracking-wider mb-2">Alamat Email</label>
                    <input type="email" name="email" id="email" required value="{{ old('email') }}" 
                           class="w-full border border-slate-200 focus:border-gold-500 focus:ring-1 focus:ring-gold-500 rounded-xl px-4 py-3 text-sm focus:outline-none transition-colors @error('email') border-red-500 @enderror">
                    @error('email') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="password" class="block text-[11px] font-black text-navy-900 uppercase tracking-wider mb-2">Password</label>
                    <input type="password" name="password" id="password" required placeholder="Minimal 6 karakter"
                           class="w-full border border-slate-200 focus:border-gold-500 focus:ring-1 focus:ring-gold-500 rounded-xl px-4 py-3 text-sm focus:outline-none transition-colors @error('password') border-red-500 @enderror">
                    @error('password') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-[11px] font-black text-navy-900 uppercase tracking-wider mb-2">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="Masukkan kembali password"
                           class="w-full border border-slate-200 focus:border-gold-500 focus:ring-1 focus:ring-gold-500 rounded-xl px-4 py-3 text-sm focus:outline-none transition-colors">
                </div>
            </div>

            <div>
                <label for="choir_experience" class="block text-[11px] font-black text-navy-900 uppercase tracking-wider mb-2">Pengalaman Bernyanyi / Paduan Suara</label>
                <textarea name="choir_experience" id="choir_experience" rows="3" placeholder="Tuliskan pengalaman Anda jika ada..." 
                          class="w-full border border-slate-200 focus:border-gold-500 focus:ring-1 focus:ring-gold-500 rounded-xl px-4 py-3 text-sm focus:outline-none transition-colors">{{ old('choir_experience') }}</textarea>
                @error('choir_experience') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="photo" class="block text-[11px] font-black text-navy-900 uppercase tracking-wider mb-2">Foto Formal (JPG/PNG, Max 2MB)</label>
                <input type="file" name="photo" id="photo" 
                       class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-navy-50 file:text-navy-900 hover:file:bg-navy-100 cursor-pointer">
                @error('photo') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-between pt-6 border-t border-slate-100">
                <a href="/" class="text-xs font-bold text-slate-500 hover:text-navy-900 transition-colors uppercase tracking-wider">Kembali</a>
                <button type="submit" 
                        class="bg-gold-500 hover:bg-gold-600 text-navy-950 px-8 py-3.5 rounded-xl text-xs font-black tracking-widest uppercase shadow-lg hover:shadow-gold-500/20 transition-all duration-300 transform hover:-translate-y-0.5">
                    Kirim Pendaftaran
                </button>
            </div>
        </form>
        @endif

    </div>
</body>
</html>
