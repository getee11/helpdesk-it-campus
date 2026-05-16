<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Helpdesk IT') }}</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;900&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Custom Wise Design System CSS -->
    <style>
        :root {
            --bs-primary: #9fe870;
            --bs-primary-rgb: 159, 232, 112;
            --bs-primary-active: #cdffad;
            --bs-primary-pale: #e2f6d5;
            
            --bs-dark: #0e0f0c;
            --bs-dark-rgb: 14, 15, 12;
            --ink: #0e0f0c;
            
            --body: #454745;
            --mute: #868685;
            
            --canvas: #ffffff;
            --canvas-soft: #e8ebe6;
            
            --positive: #2ead4b;
            --warning: #ffd11a;
            --negative: #d03238;
            
            /* Spacing */
            --spacing-sm: 8px;
            --spacing-lg: 16px;
            --spacing-xl: 24px;
            --spacing-3xl: 48px;
            
            /* Border Radius */
            --rounded-md: 12px;
            --rounded-xl: 24px;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--body);
            background-color: var(--canvas-soft);
            font-weight: 400;
        }

        h1, h2, h3, h4, h5, h6, .display-md, .display-sm {
            color: var(--ink);
        }

        .fw-900 {
            font-weight: 900 !important;
        }
        
        .fw-600 {
            font-weight: 600 !important;
        }

        /* Buttons */
        .btn {
            border-radius: var(--rounded-xl);
            padding: 10px 24px;
            font-weight: 600;
            border: none;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background-color: var(--bs-primary);
            color: var(--ink);
        }

        .btn-primary:hover, .btn-primary:focus, .btn-primary:active {
            background-color: var(--bs-primary-active) !important;
            color: var(--ink) !important;
        }

        .btn-secondary {
            background-color: var(--canvas-soft);
            color: var(--ink);
        }
        
        .btn-secondary:hover {
            background-color: #d8dbd6;
            color: var(--ink);
        }

        .btn-danger {
            background-color: var(--negative);
            color: white;
        }

        /* Cards */
        .card {
            border-radius: var(--rounded-xl);
            border: none;
            background-color: var(--canvas);
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            margin-bottom: var(--spacing-xl);
        }

        .card-body {
            padding: var(--spacing-xl);
        }
        
        .card-feature-sage {
            background-color: var(--canvas-soft);
        }

        /* Form Inputs */
        .form-control, .form-select {
            border-radius: var(--rounded-md);
            border: 1px solid var(--ink);
            padding: 12px 16px;
            background-color: var(--canvas);
            color: var(--ink);
        }

        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 3px rgba(159, 232, 112, 0.4);
            border-color: var(--bs-primary);
        }

        .form-control::placeholder {
            color: var(--mute);
        }

        /* Badges */
        .badge {
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 9999px;
        }

        .badge-open {
            background-color: var(--bs-primary-pale);
            color: #054d28;
        }

        .badge-progress {
            background-color: #fff3cd;
            color: #b86700;
        }

        .badge-resolved {
            background-color: #d1e7dd;
            color: #0f5132;
        }

        .badge-kritis {
            background-color: var(--negative);
            color: white;
        }
        
        .badge-tinggi {
            background-color: #fd7e14;
            color: white;
        }

        .badge-sedang {
            background-color: var(--warning);
            color: var(--ink);
        }

        .badge-rendah {
            background-color: var(--positive);
            color: white;
        }

        /* Nav */
        .navbar {
            background-color: var(--canvas);
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            padding: 15px 0;
        }
        
        .navbar-brand {
            font-weight: 900;
            color: var(--ink) !important;
            font-size: 24px;
        }
        
        .nav-link {
            font-weight: 600;
            color: var(--body);
        }
        
        .nav-link.active {
            color: var(--ink) !important;
        }
        
        /* Layout */
        .main-content {
            padding: var(--spacing-3xl) 0;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="bi bi-hdd-network text-primary me-2"></i> IT Helpdesk
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('tickets.*') ? 'active' : '' }}" href="{{ route('tickets.index') }}">Tiket</a>
                    </li>
                    @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            Master Data
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('admin.categories.index') }}">Kategori Masalah</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.departments.index') }}">Departemen</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('admin.users.index') }}">Manajemen Pengguna</a></li>
                        </ul>
                    </li>
                    @endif
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-2 fw-bold" 
                                 style="width: 32px; height: 32px; background-color: {{ Auth::user()->avatar_color }}; color: {{ Auth::user()->avatar_text_color }}; font-size: 14px;">
                                {{ Auth::user()->avatar }}
                            </div>
                            <span>{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">Log Out</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="main-content">
        <div class="container">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: var(--rounded-md); border-color: var(--positive); background-color: #d1e7dd; color: #0f5132;">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: var(--rounded-md); border-color: var(--negative); background-color: #f8d7da; color: #842029;">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @yield('content')
            {{ $slot ?? '' }}
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery for client-side validations if needed -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    @stack('scripts')
</body>
</html>
