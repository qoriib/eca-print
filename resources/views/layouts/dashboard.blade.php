<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Eca Print Dashboard</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f7f6;
        }
        .sidebar {
            min-height: 100vh;
            background-color: #ffffff;
            border-right: 1px solid #e9ecef;
            width: 260px;
            position: fixed;
            z-index: 1000;
        }
        .main-content {
            margin-left: 260px;
            padding: 2rem;
        }
        .nav-link {
            color: #495057;
            padding: 0.8rem 1.5rem;
            display: flex;
            align-items: center;
            border-radius: 0.5rem;
            margin: 0.2rem 1rem;
            transition: all 0.2s;
        }
        .nav-link:hover, .nav-link.active {
            background-color: #f0f4ff;
            color: #4e73df;
        }
        .nav-link i {
            margin-right: 0.8rem;
            font-size: 1.1rem;
        }
        .navbar {
            background-color: #ffffff;
            border-bottom: 1px solid #e9ecef;
            padding: 0.75rem 2rem;
            margin-left: 260px;
        }
        .stat-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        @media (max-width: 992px) {
            .sidebar {
                margin-left: -260px;
            }
            .main-content, .navbar {
                margin-left: 0;
            }
            .sidebar.show {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column" id="sidebar">
        <div class="p-4">
            <h4 class="fw-bold text-primary mb-0">Eca Print</h4>
            <small class="text-muted">Panel @yield('role_name')</small>
        </div>
        
        <nav class="mt-2 flex-grow-1">
            @php $role = Auth::user()->role; @endphp
            
            <a href="{{ route('dashboard.' . $role) }}" class="nav-link {{ request()->routeIs('dashboard.*') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            @if($role === 'admin')
                <div class="px-4 mt-4 mb-2 small text-muted text-uppercase fw-bold">Manajemen Data</div>
                <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> Pengguna
                </a>
                <a href="{{ route('kategori-produk.index') }}" class="nav-link {{ request()->routeIs('kategori-produk.*') ? 'active' : '' }}">
                    <i class="bi bi-tags"></i> Kategori Produk
                </a>
                <a href="{{ route('produk.index') }}" class="nav-link {{ request()->routeIs('produk.*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam"></i> Produk
                </a>
            @endif

            @if($role === 'admin' || $role === 'operator')
                <div class="px-4 mt-4 mb-2 small text-muted text-uppercase fw-bold">Operasional</div>
                <a href="{{ route('produksi.index') }}" class="nav-link {{ request()->routeIs('produksi.*') ? 'active' : '' }}">
                    <i class="bi bi-gear"></i> Produksi
                </a>
            @endif

            <div class="px-4 mt-4 mb-2 small text-muted text-uppercase fw-bold">Transaksi</div>
            <a href="{{ route('pesanan.index') }}" class="nav-link {{ request()->routeIs('pesanan.*') ? 'active' : '' }}">
                <i class="bi bi-cart"></i> Pesanan
            </a>
            <a href="{{ route('pembayaran.index') }}" class="nav-link {{ request()->routeIs('pembayaran.*') ? 'active' : '' }}">
                <i class="bi bi-wallet2"></i> Pembayaran
            </a>

            <div class="px-4 mt-4 mb-2 small text-muted text-uppercase fw-bold">Akun</div>
            <a href="{{ route('notifikasi.index') }}" class="nav-link {{ request()->routeIs('notifikasi.*') ? 'active' : '' }}">
                <i class="bi bi-bell"></i> Notifikasi
                @if(isset($unreadCount) && $unreadCount > 0)
                    <span class="badge rounded-pill bg-danger ms-auto">{{ $unreadCount }}</span>
                @endif
            </a>
        </nav>

        <div class="p-3 border-top">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center">
                    <i class="bi bi-box-arrow-right me-2"></i> Keluar
                </button>
            </form>
        </div>
    </div>

    <!-- Header Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <button class="btn d-lg-none" type="button" onclick="document.getElementById('sidebar').classList.toggle('show')">
                <i class="bi bi-list fs-4"></i>
            </button>
            
            <div class="ms-auto d-flex align-items-center">
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark" data-bs-toggle="dropdown">
                        <div class="text-end me-2 d-none d-sm-block">
                            <div class="fw-bold small">{{ Auth::user()->name }}</div>
                            <small class="text-muted text-capitalize">{{ Auth::user()->role }}</small>
                        </div>
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=4e73df&color=fff" alt="Profile" class="rounded-circle" width="35">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                        <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i> Profil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i> Keluar</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <main class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
