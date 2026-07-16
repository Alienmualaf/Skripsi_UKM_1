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
    <style>
        /* Custom transitions and glow effects */
        .premium-shadow {
            box-shadow: 0 20px 50px -12px rgba(10, 11, 16, 0.5), 0 0 40px 0 rgba(197, 160, 89, 0.05);
        }
        .premium-input:focus {
            box-shadow: 0 0 0 4px rgba(197, 160, 89, 0.15);
        }
    </style>
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
                            800: '#0f111a',
                            900: '#0a0b10',
                            950: '#050508'
                        },
                        gold: {
                            100: '#f8f4eb',
                            400: '#d9b46c',
                            500: '#c5a059',
                            600: '#aa8643',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-navy-950 text-slate-800 font-sans antialiased min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-x-hidden">
    
    <!-- Smooth fixed background to prevent scrolling repaint stutter -->
    <div class="fixed inset-0 z-0 bg-cover bg-center bg-no-repeat pointer-events-none" style="background-image: linear-gradient(rgba(10, 11, 16, 0.88), rgba(10, 11, 16, 0.95)), url('{{ asset('images/REMINISCENTIA.jpeg') }}'); transform: translate3d(0,0,0); will-change: transform;"></div>
    
    <!-- Premium Backdrop Elements -->
    <div class="absolute top-[-10%] right-[-10%] w-[500px] h-[500px] bg-gold-500/10 rounded-full blur-[120px] pointer-events-none z-0"></div>
    <div class="absolute bottom-[-10%] left-[-10%] w-[500px] h-[500px] bg-navy-800/80 rounded-full blur-[120px] pointer-events-none z-0"></div>

    <div class="max-w-2xl w-full space-y-8 bg-white p-8 sm:p-12 rounded-[28px] border border-slate-100 premium-shadow relative z-10 my-8 transition-all duration-300">
        
        <!-- Header -->
        <div class="text-center">
            <a href="/" class="inline-flex items-center gap-3 mb-6 group">
                <div class="w-14 h-14 rounded-full overflow-hidden border-2 border-gold-500 bg-white p-0.5 transition-all duration-300 group-hover:scale-105 shrink-0 shadow-md">
                    @if($profile->logo)
                        <img src="{{ asset('storage/' . $profile->logo) }}" alt="Logo" class="w-full h-full object-contain">
                    @else
                        <img src="{{ asset('images/logo_PSUP.jpeg') }}" alt="Logo PSUP" class="w-full h-full object-contain">
                    @endif
                </div>
                <div class="text-left">
                    <span class="font-sans font-extrabold text-[10px] tracking-widest text-navy-900 uppercase block">{{ $profile->name }}</span>
                    <span class="font-sans font-bold text-xs text-gold-500 uppercase tracking-widest block">Portal Anggota</span>
                </div>
            </a>
            <h2 class="font-serif font-normal text-3xl text-navy-900 tracking-tight">Formulir Pendaftaran</h2>
            <p class="text-slate-500 text-sm mt-2 max-w-md mx-auto leading-relaxed">Bergabunglah dengan keluarga besar Paduan Suara Universitas Pancasila. Silakan lengkapi data diri Anda di bawah ini.</p>
            <div class="w-16 h-[2px] bg-gold-500 mx-auto mt-4 rounded-full"></div>
        </div>

        <!-- Alert Success -->
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-8 rounded-2xl text-sm flex items-start gap-4 shadow-sm animate-fade-in">
                <i class="ph-fill ph-check-circle text-3xl text-emerald-600 flex-shrink-0 mt-0.5"></i>
                <div class="space-y-3">
                    <h4 class="font-bold text-lg text-emerald-900">Pendaftaran Berhasil!</h4>
                    <p class="text-emerald-700 leading-relaxed">{{ session('success') }}</p>
                    <div class="pt-2">
                        <a href="/" class="inline-flex items-center gap-2 bg-navy-900 hover:bg-navy-800 text-white font-bold text-xs uppercase tracking-wider px-6 py-3 rounded-xl transition-all shadow-md">
                            <i class="ph ph-house"></i> Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <!-- Form -->
        @if(!session('success'))
        <form action="{{ route('register-candidate.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Section 1: Informasi Akademik & Pribadi -->
            <div class="space-y-5">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                    <i class="ph ph-identification-card text-gold-500 text-xl"></i>
                    <span class="text-xs font-extrabold text-navy-900 uppercase tracking-wider font-sans">Data Akademik & Diri</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="name" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Lengkap</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none">
                                <i class="ph ph-user text-lg"></i>
                            </span>
                            <input type="text" name="name" id="name" required value="{{ old('name') }}" placeholder="Masukkan nama lengkap"
                                   class="premium-input w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 focus:border-gold-500 focus:bg-white rounded-xl text-sm focus:outline-none transition-all duration-300 @error('name') border-red-500 @enderror">
                        </div>
                        @error('name') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="npm" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">NPM</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none">
                                <i class="ph ph-hash text-lg"></i>
                            </span>
                            <input type="text" name="npm" id="npm" required value="{{ old('npm') }}" placeholder="Contoh: 4520210001"
                                   class="premium-input w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 focus:border-gold-500 focus:bg-white rounded-xl text-sm focus:outline-none transition-all duration-300 @error('npm') border-red-500 @enderror">
                        </div>
                        @error('npm') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="gender" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Jenis Kelamin</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none">
                                <i class="ph ph-gender-intersex text-lg"></i>
                            </span>
                            <select name="gender" id="gender" required 
                                    class="premium-input w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 focus:border-gold-500 focus:bg-white rounded-xl text-sm focus:outline-none transition-all duration-300 appearance-none bg-white">
                                <option value="L" {{ old('gender') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('gender') === 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            <span class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 pointer-events-none">
                                <i class="ph ph-caret-down text-sm"></i>
                            </span>
                        </div>
                        @error('gender') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label placeholder="Contoh: 2024" for="class_year" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Angkatan</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none">
                                <i class="ph ph-calendar-blank text-lg"></i>
                            </span>
                            <input type="text" name="class_year" id="class_year" required placeholder="Contoh: 2024" value="{{ old('class_year') }}" 
                                   class="premium-input w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 focus:border-gold-500 focus:bg-white rounded-xl text-sm focus:outline-none transition-all duration-300 @error('class_year') border-red-500 @enderror">
                        </div>
                        @error('class_year') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="faculty" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Fakultas</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none">
                                <i class="ph ph-student text-lg"></i>
                            </span>
                            <input type="text" name="faculty" id="faculty" required value="{{ old('faculty') }}" placeholder="Fakultas Teknik / Ekonomi"
                                   class="premium-input w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 focus:border-gold-500 focus:bg-white rounded-xl text-sm focus:outline-none transition-all duration-300 @error('faculty') border-red-500 @enderror">
                        </div>
                        @error('faculty') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="major" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Program Studi</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none">
                                <i class="ph ph-book-open text-lg"></i>
                            </span>
                            <input type="text" name="major" id="major" required value="{{ old('major') }}" placeholder="Teknik Informatika / Akuntansi"
                                   class="premium-input w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 focus:border-gold-500 focus:bg-white rounded-xl text-sm focus:outline-none transition-all duration-300 @error('major') border-red-500 @enderror">
                        </div>
                        @error('major') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Section 2: Informasi Kontak & Akun -->
            <div class="space-y-5 pt-4">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                    <i class="ph ph-lock-key text-gold-500 text-xl"></i>
                    <span class="text-xs font-extrabold text-navy-900 uppercase tracking-wider font-sans">Kontak & Kredensial Akun</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="phone" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">No. WhatsApp</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none">
                                <i class="ph ph-phone text-lg"></i>
                            </span>
                            <input type="text" name="phone" id="phone" required placeholder="Contoh: 08123456789" value="{{ old('phone') }}" autocomplete="off"
                                   class="premium-input w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 focus:border-gold-500 focus:bg-white rounded-xl text-sm focus:outline-none transition-all duration-300 @error('phone') border-red-500 @enderror">
                        </div>
                        @error('phone') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Alamat Email</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none">
                                <i class="ph ph-envelope text-lg"></i>
                            </span>
                            <input type="email" name="email" id="email" required value="{{ old('email') }}" placeholder="nama@email.com" autocomplete="new-email"
                                   class="premium-input w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 focus:border-gold-500 focus:bg-white rounded-xl text-sm focus:outline-none transition-all duration-300 @error('email') border-red-500 @enderror">
                        </div>
                        @error('email') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="password" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Password</label>
                        <div class="relative flex items-center">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none">
                                <i class="ph ph-lock text-lg"></i>
                            </span>
                            <input type="password" name="password" id="password" required placeholder="Minimal 6 karakter" autocomplete="new-password"
                                   class="premium-input w-full pl-10 pr-10 py-3 bg-slate-50 border border-slate-200 focus:border-gold-500 focus:bg-white rounded-xl text-sm focus:outline-none transition-all duration-300 @error('password') border-red-500 @enderror">
                            <button type="button" class="toggle-password-btn absolute right-3.5 text-slate-400 cursor-pointer flex items-center justify-center p-1">
                                <i class="ph ph-eye text-lg"></i>
                            </button>
                        </div>
                        @error('password') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Konfirmasi Password</label>
                        <div class="relative flex items-center">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none">
                                <i class="ph ph-lock-key-open text-lg"></i>
                            </span>
                            <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="Ulangi password" autocomplete="new-password"
                                   class="premium-input w-full pl-10 pr-10 py-3 bg-slate-50 border border-slate-200 focus:border-gold-500 focus:bg-white rounded-xl text-sm focus:outline-none transition-all duration-300">
                            <button type="button" class="toggle-password-btn absolute right-3.5 text-slate-400 cursor-pointer flex items-center justify-center p-1">
                                <i class="ph ph-eye text-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Informasi Tambahan -->
            <div class="space-y-5 pt-4">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                    <i class="ph ph-microphone-stage text-gold-500 text-xl"></i>
                    <span class="text-xs font-extrabold text-navy-900 uppercase tracking-wider font-sans">Informasi Pendukung</span>
                </div>

                <div>
                    <label for="choir_experience" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Pengalaman Paduan Suara / Seni Musik</label>
                    <div class="relative">
                        <textarea name="choir_experience" id="choir_experience" rows="3" placeholder="Tuliskan pengalaman Anda bernyanyi di paduan suara gereja, sekolah, atau prestasi tarik suara lainnya jika ada..." 
                                  class="premium-input w-full p-4 bg-slate-50 border border-slate-200 focus:border-gold-500 focus:bg-white rounded-xl text-sm focus:outline-none transition-all duration-300">{{ old('choir_experience') }}</textarea>
                    </div>
                    @error('choir_experience') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Unggah Foto Formal</label>
                    <div class="border-2 border-dashed border-slate-200 rounded-xl p-5 bg-slate-50 hover:bg-slate-100/50 transition-colors duration-300 flex flex-col items-center justify-center text-center cursor-pointer relative" onclick="document.getElementById('photo').click()">
                        <i class="ph ph-cloud-arrow-up text-3xl text-gold-500 mb-2"></i>
                        <span class="text-xs font-bold text-slate-700 block" id="file-name-label">Klik untuk memilih foto</span>
                        <span class="text-[10px] text-slate-400 block mt-1">Format JPG/PNG, maksimal 2MB</span>
                        <input type="file" name="photo" id="photo" class="hidden" accept="image/*" onchange="updateFileName(this)">
                    </div>
                    @error('photo') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Footer Buttons -->
            <div class="flex items-center justify-between pt-6 border-t border-slate-100">
                <a href="/" class="text-xs font-bold text-slate-500 hover:text-navy-900 transition-colors uppercase tracking-wider flex items-center gap-1.5 font-sans">
                    <i class="ph ph-arrow-left"></i> Kembali
                </a>
                <button type="submit" 
                        class="bg-gradient-to-r from-gold-500 to-gold-600 hover:from-gold-600 hover:to-gold-500 text-white font-extrabold text-[11px] tracking-widest uppercase px-8 py-3.5 rounded-xl shadow-lg hover:shadow-gold-500/20 transition-all duration-300 transform hover:-translate-y-0.5">
                    Kirim Pendaftaran
                </button>
            </div>
        </form>
        @endif

    </div>

    <script>
        function updateFileName(input) {
            const label = document.getElementById('file-name-label');
            if (input.files && input.files[0]) {
                label.textContent = input.files[0].name;
                label.classList.remove('text-slate-700');
                label.classList.add('text-navy-900');
            } else {
                label.textContent = 'Klik untuk memilih foto';
                label.classList.remove('text-navy-900');
                label.classList.add('text-slate-700');
            }
        }

        document.querySelectorAll('.toggle-password-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const input = this.parentElement.querySelector('input');
                const icon = this.querySelector('i');
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.className = 'ph ph-eye-slash text-lg';
                } else {
                    input.type = 'password';
                    icon.className = 'ph ph-eye text-lg';
                }
            });
        });
    </script>
</body>
</html>
