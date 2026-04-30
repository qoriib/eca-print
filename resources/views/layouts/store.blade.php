<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - {{ \App\Models\Pengaturan::first()->nama_usaha ?? 'Eca Print' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fbff;
            color: var(--text-dark);
            overflow-x: hidden;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1rem 0;
            transition: all 0.3s;
        }

        .navbar-brand {
            font-weight: 800;
            letter-spacing: -1px;
            color: var(--bs-primary) !important;
            font-size: 1.5rem;
        }

        .nav-link {
            font-weight: 600;
            color: var(--text-dark) !important;
            margin: 0 10px;
            font-size: 0.95rem;
            transition: color 0.3s;
        }

        .nav-link:hover {
            color: var(--bs-primary) !important;
        }

        .hero-section {
            padding: 120px 0 80px;
            background: linear-gradient(135deg, #fff 0%, #f0f4ff 100%);
        }

        .footer {
            background: #fff;
            padding: 30px 0;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }

        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        @media (max-width: 768px) {
            .hero-section {
                padding: 80px 0 60px;
                text-align: center;
            }

            .hero-section h1 {
                font-size: 2.5rem;
            }

            .hero-section .d-flex {
                justify-content: center;
            }

            .table-responsive {
                border-radius: 10px;
            }

            .stepper-container {
                overflow-x: auto;
                padding-bottom: 10px;
            }

            .stepper-item {
                min-width: 80px;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                {{ \App\Models\Pengaturan::first()->nama_usaha ?? 'Eca Print' }}
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/#katalog">Produk</a>
                    </li>
                    @auth
                        @if(Auth::user()->role === 'pelanggan')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('pesanan.index') }}">Pesanan Saya</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('dashboard.' . Auth::user()->role) }}">Dashboard</a>
                            </li>
                        @endif
                        <li class="nav-item dropdown ms-lg-3">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=4e73df&color=fff" class="rounded-circle me-2" width="32">
                                <span>{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm mt-3">
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i>Keluar
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item ms-lg-3">
                            <a class="btn btn-outline-primary px-4 me-2" href="{{ route('login') }}">Masuk</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary px-4" href="{{ route('register') }}">Daftar</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="content-wrapper">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container text-center">
            <h5 class="fw-bold mb-2">{{ \App\Models\Pengaturan::first()->nama_usaha ?? 'Eca Print' }}</h5>
            <p class="text-muted mb-2">{{ \App\Models\Pengaturan::first()->alamat ?? 'Jl. Percetakan No. 123' }}</p>
            <p class="small text-muted mb-0">&copy; {{ date('Y') }} {{ \App\Models\Pengaturan::first()->nama_usaha ?? 'Eca Print' }}. {{ \App\Models\Pengaturan::first()->catatan_footer ?? 'Seluruh hak cipta dilindungi.' }}</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
