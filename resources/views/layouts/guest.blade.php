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
            --bs-primary-active: #cdffad;
            
            --bs-dark: #0e0f0c;
            --ink: #0e0f0c;
            
            --body: #454745;
            --mute: #868685;
            
            --canvas: #ffffff;
            --canvas-soft: #e8ebe6;
            
            --rounded-md: 12px;
            --rounded-xl: 24px;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--body);
            background-color: var(--canvas-soft);
            font-weight: 400;
        }
        
        .fw-900 { font-weight: 900 !important; }
        .fw-600 { font-weight: 600 !important; }

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

        .form-control {
            border-radius: var(--rounded-md);
            border: 1px solid var(--ink);
            padding: 12px 16px;
            background-color: var(--canvas);
            color: var(--ink);
        }

        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(159, 232, 112, 0.4);
            border-color: var(--bs-primary);
        }

        .card {
            border-radius: var(--rounded-xl);
            border: none;
            background-color: var(--canvas);
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="text-center mb-4">
                    <i class="bi bi-hdd-network" style="font-size: 48px; color: var(--ink); background-color: var(--bs-primary); padding: 12px 24px; border-radius: var(--rounded-xl);"></i>
                    <h2 class="fw-900 mt-4" style="color: var(--ink);">IT Helpdesk</h2>
                    <p class="text-muted">Masuk untuk membuat atau memantau laporan</p>
                </div>
                
                <div class="card">
                    <div class="card-body p-4 p-md-5">
                        {{ $slot }}
                    </div>
                </div>
                
                <div class="text-center mt-4 text-muted small">
                    &copy; {{ date('Y') }} IT Campus. All rights reserved.
                </div>
            </div>
        </div>
    </div>
</body>
</html>
