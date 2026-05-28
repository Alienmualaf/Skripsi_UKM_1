<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Sistem UKM - Universitas Pancasila</title>
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #1d4ed8; /* Royal Blue matching landing/dashboards (Tailwind blue-700) */
            --primary-hover: #1e40af; /* Tailwind blue-800 */
            --secondary: #fbbf24;
            --bg: #f8fafc;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --radius-lg: 20px;
            --radius-md: 12px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #e5e7eb;
            /* SEVIMA Damask repeating pattern wallpaper */
            background-image: url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M40 0c5-5 10 5 15 0c5 5 5 15 0 20c-5-5-10-5-15 0c-5-5-5-15 0-20zm0 80c5-5 10 5 15 0c5 5 5 15 0 20c-5-5-10-5-15 0c-5-5-5-15 0-20zM0 40c5-5 10 5 15 0c5 5 5 15 0 20c-5-5-10-5-15 0c-5-5-5-15 0-20zm80 0c5-5 10 5 15 0c5 5 5 15 0 20c-5-5-10-5-15 0c-5-5-5-15 0-20z' fill='%239cbdca' fill-opacity='0.12' fill-rule='evenodd'/%3E%3C/svg%3E");
            padding: 2rem 1.5rem;
            color: var(--text-main);
        }

        .auth-card-container {
            width: 100%;
            max-width: 920px;
            height: 600px;
            background: #ffffff;
            border-radius: var(--radius-lg);
            overflow: hidden;
            display: flex;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--border);
        }

        .auth-image-side {
            flex: 1.15;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 3.5rem 3rem;
            color: white;
            overflow: hidden;
        }

        .auth-image-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            object-position: center !important;
            z-index: 0;
        }

        .auth-image-side::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(15, 23, 42, 0.85) 0%, rgba(15, 23, 42, 0.4) 50%, rgba(15, 23, 42, 0.15) 100%);
            z-index: 1;
        }

        .auth-image-content {
            position: relative;
            z-index: 2;
            text-align: left;
        }

        .welcome-text {
            font-size: 0.8rem;
            font-weight: 800;
            color: #84cc16; /* Lime/Green Accent matching screenshot */
            text-transform: uppercase;
            letter-spacing: 0.15em;
            display: block;
            margin-bottom: 0.65rem;
        }

        .academic-system-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.75rem;
            font-weight: 800;
            line-height: 1.25;
            color: #ffffff;
            margin-bottom: 0.25rem;
            letter-spacing: -0.01em;
        }

        .academic-univ-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.45rem;
            font-weight: 800;
            color: #ffffff;
            line-height: 1.2;
        }

        .auth-form-side {
            flex: 0.85;
            padding: 2.25rem 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: #ffffff;
            overflow-y: auto;
        }

        .auth-header {
            text-align: center;
            margin-bottom: 1.25rem;
        }

        .auth-logo-container {
            width: 72px;
            height: 72px;
            background-color: #ffffff;
            border: 1.5px solid var(--border);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            margin: 0 auto 1.25rem;
            transition: transform 0.3s ease;
        }
        
        .auth-logo-container img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .auth-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.45rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            color: var(--text-main);
            letter-spacing: -0.02em;
        }

        .auth-subtitle {
            color: var(--text-muted);
            font-size: 0.8rem;
            font-weight: 500;
            line-height: 1.4;
            max-width: 290px;
            margin: 0 auto;
        }

        .form-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.25rem;
            margin-bottom: 1.25rem;
        }

        .form-group {
            margin-bottom: 0.9rem;
            text-align: left;
        }

        .form-label {
            display: block;
            font-size: 0.775rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--text-main);
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            background: #ffffff;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-md);
            font-size: 0.9rem;
            color: var(--text-main);
            transition: all 0.2s ease;
            outline: none;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(29, 78, 216, 0.12);
        }

        .btn-submit {
            width: 100%;
            padding: 0.8rem 1.5rem;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: var(--radius-md);
            font-size: 0.9rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 0.75rem;
            box-shadow: 0 4px 12px rgba(29, 78, 216, 0.15);
        }

        .btn-submit:hover {
            background: var(--primary-hover);
            box-shadow: 0 6px 16px rgba(29, 78, 216, 0.25);
            transform: translateY(-1px);
        }

        .btn-submit:active {
            transform: scale(0.98);
        }

        .auth-footer {
            margin-top: 1.5rem;
            text-align: center;
            font-size: 0.825rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .auth-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 700;
            transition: color 0.2s;
        }

        .auth-footer a:hover {
            color: var(--primary-hover);
            text-decoration: underline;
        }

        .error-message {
            color: #ef4444;
            font-size: 0.775rem;
            margin-top: 0.45rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        @media (max-width: 768px) {
            .auth-card-container {
                flex-direction: column;
                max-width: 440px;
                min-height: auto;
            }
            .auth-image-side {
                display: none;
            }
            .auth-form-side {
                padding: 3rem 2rem;
            }
            .form-grid-2 {
                grid-template-columns: 1fr;
                gap: 1.25rem;
            }
        }
    </style>
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
