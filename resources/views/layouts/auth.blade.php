<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Sistem Informasi UKM</title>
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #1e40af; /* Majestic Sapphire Ocean Blue */
            --primary-gradient: linear-gradient(135deg, #1e40af 0%, #1d4ed8 100%);
            --primary-hover: linear-gradient(135deg, #1d4ed8 0%, #172554 100%);
            --secondary: #f59e0b; /* Radiant Butterscotch Gold */
            --bg: #f3f7fa;
            --surface: rgba(255, 255, 255, 0.85);
            --text-main: #0f1d3a; /* Deep Sapphire Charcoal */
            --text-muted: #5a6e85;
            --border: #dbeafe;
            --border-focus: #1e40af;
            --accent-glow: rgba(30, 64, 175, 0.12);
            --danger: #ef4444;
            --radius-lg: 1.25rem;
            --radius-md: 0.75rem;
            --shadow: 0 10px 30px rgba(30, 64, 175, 0.05), 4px 8px 20px rgba(30, 64, 175, 0.03);
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
            background-color: var(--bg);
            
            /* Dotted grid background */
            background-image: radial-gradient(rgba(30, 64, 175, 0.08) 1.5px, transparent 1.5px);
            background-size: 20px 20px;
            background-position: 0 0;
            
            padding: 1.5rem;
            color: var(--text-main);
            position: relative;
            overflow: hidden;
        }

        /* Ambient Animated Backdrop Blobs */
        .backdrop-blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            pointer-events: none;
            opacity: 0.35;
            z-index: 1;
        }

        .blob-blue {
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, rgba(30, 64, 175, 0.25) 0%, rgba(30, 64, 175, 0) 70%);
            top: -50px;
            right: -50px;
            animation: float 8s ease-in-out infinite alternate;
        }

        .blob-yellow {
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(245, 158, 11, 0.2) 0%, rgba(245, 158, 11, 0) 70%);
            bottom: -50px;
            left: -50px;
            animation: float 10s ease-in-out infinite alternate-reverse;
        }

        @keyframes float {
            0% { transform: translateY(0px) scale(1); }
            100% { transform: translateY(30px) scale(1.1); }
        }

        .auth-wrapper {
            width: 100%;
            max-width: 440px;
            background: var(--surface);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 16px;
            padding: 3rem 2.5rem;
            box-shadow: var(--shadow);
            border: 1.5px solid var(--border);
            position: relative;
            z-index: 2;
            animation: fadeIn 0.5s cubic-bezier(0.4, 0, 0.2, 1) forwards;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .auth-wrapper:hover {
            border-color: var(--primary);
            box-shadow: 0 12px 36px rgba(30, 64, 175, 0.08);
        }

        .auth-wrapper::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border-radius: 16px 16px 0 0;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .auth-header {
            text-align: center;
            margin-bottom: 2.25rem;
        }

        .auth-logo {
            width: 64px;
            height: 64px;
            object-fit: contain;
            margin: 0 auto 1rem;
            display: block;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .auth-logo:hover {
            transform: scale(1.08) rotate(5deg);
        }

        .auth-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.75rem;
            font-weight: 800;
            margin-bottom: 0.35rem;
            color: var(--text-main);
            letter-spacing: -0.02em;
        }

        .auth-subtitle {
            color: var(--text-muted);
            font-size: 0.875rem;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--text-main);
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1.15rem;
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: var(--radius-md);
            font-size: 0.95rem;
            color: var(--text-main);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            outline: none;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.01);
        }

        .form-control:focus {
            border-color: var(--border-focus);
            box-shadow: 0 0 0 4px var(--accent-glow);
            transform: translateY(-1px);
        }

        .btn-submit {
            width: 100%;
            padding: 0.8rem 1.5rem;
            background: var(--primary-gradient);
            color: white;
            border: none;
            border-radius: var(--radius-md);
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            margin-top: 0.75rem;
            box-shadow: 0 4px 12px rgba(13, 71, 161, 0.15);
        }

        .btn-submit:hover {
            background: var(--primary-hover);
            box-shadow: 0 6px 16px rgba(13, 71, 161, 0.25);
            transform: translateY(-1px);
        }

        .btn-submit:active {
            transform: scale(0.97);
        }

        .auth-footer {
            margin-top: 1.75rem;
            text-align: center;
            font-size: 0.875rem;
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
            color: #1d4ed8;
            text-decoration: underline;
        }

        .error-message {
            color: var(--danger);
            font-size: 0.8rem;
            margin-top: 0.45rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
    </style>
</head>
<body>
    <!-- Background elements -->
    <div class="backdrop-blob blob-blue"></div>
    <div class="backdrop-blob blob-yellow"></div>

    <div class="auth-wrapper">
        <div class="auth-header">
            <a href="/">
                <img src="{{ asset('images/logo-up.png') }}" alt="Logo" class="auth-logo">
            </a>
            <h1 class="auth-title">UniUKM</h1>
            <p class="auth-subtitle">@yield('subtitle')</p>
        </div>

        @yield('content')
        
    </div>
</body>
</html>
