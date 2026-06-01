<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Sistem UKM - Universitas Pancasila</title>
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/auth_layout.css') }}">
</head>
<body>
    <div class="auth-card-container">
        <!-- Left Side: Image Showcase -->
        <div class="auth-image-side">
            <img src="{{ file_exists(public_path('images/bgup.jpg')) ? asset('images/bgup.jpg') : 'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?q=80&w=1000' }}" alt="Campus Backdrop" class="auth-image-bg">
            <div class="auth-image-content">
                <span class="welcome-text">SELAMAT DATANG</span>
                <h1 class="academic-system-title">Sistem Kegiatan Mahasiswa</h1>
                <p class="academic-univ-title">Universitas Pancasila</p>
            </div>
        </div>

        <!-- Right Side: Form Side -->
        <div class="auth-form-side">
            <div class="auth-header">
                <div class="auth-logo-container">
                    <img src="{{ asset('images/logoup.png') }}" alt="Logo Universitas Pancasila">
                </div>
                <h2 class="auth-title">@yield('title')</h2>
                <p class="auth-subtitle">@yield('subtitle')</p>
            </div>

            @yield('content')
        </div>
    </div>
</body>
</html>
