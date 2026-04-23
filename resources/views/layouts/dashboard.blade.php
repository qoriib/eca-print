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
            overflow-x: hidden;
        }
        .sidebar {
            height: 100vh;
            background-color: #ffffff;
            border-right: 1px solid #e9ecef;
            width: 260px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1050;
            transition: all 0.3s ease-in-out;
        }
        .main-content {
            margin-left: 260px;
            padding: 1.5rem;
            transition: all 0.3s ease-in-out;
            min-height: calc(100vh - 70px);
        }
        .nav-link {
            color: #495057;
            padding: 0.8rem 1.2rem;
            display: flex;
            align-items: center;
            border-radius: 0.5rem;
            margin: 0.2rem 0.8rem;
            transition: all 0.2s;
            font-size: 0.95rem;
        }
        .nav-link:hover, .nav-link.active {
            background-color: #f0f4ff;
            color: #4e73df;
            font-weight: 500;
        }
        .nav-link i {
            margin-right: 0.8rem;
            font-size: 1.2rem;
        }
        .navbar {
            background-color: #ffffff;
            border-bottom: 1px solid #e9ecef;
            padding: 0.75rem 1.5rem;
            margin-left: 260px;
            height: 70px;
            transition: all 0.3s ease-in-out;
        }
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1040;
        }
        @media (max-width: 991.98px) {
            .sidebar {
                margin-left: -260px;
            }
            .main-content, .navbar {
                margin-left: 0;
            }
            .sidebar.show {
                margin-left: 0;
            }
            .sidebar.show ~ .sidebar-overlay {
                display: block;
            }
            .main-content {
                padding: 1rem;
            }
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
    </style>
</head>
<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column shadow-sm" id="sidebar">
        <div class="p-4 d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold text-primary mb-0">Eca Print</h4>
                <small class="text-muted">Panel @yield('role_name')</small>
            </div>
            <button class="btn d-lg-none p-0 text-muted" id="closeSidebar">
                <i class="bi bi-x-lg fs-4"></i>
            </button>
        </div>
        
        <nav class="mt-2 flex-grow-1 overflow-y-auto">
            @php $role = Auth::user()->role; @endphp
            
            <a href="{{ route('dashboard.' . $role) }}" class="nav-link {{ request()->routeIs('dashboard.*') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i> Dashboard
            </a>

            @if($role === 'admin')
                <div class="px-4 mt-4 mb-2 small text-muted text-uppercase fw-bold" style="letter-spacing: 1px; font-size: 0.7rem;">Master Data</div>
                <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> Pengguna
                </a>
                <a href="{{ route('kategori-produk.index') }}" class="nav-link {{ request()->routeIs('kategori-produk.*') ? 'active' : '' }}">
                    <i class="bi bi-tags"></i> Kategori
                </a>
                <a href="{{ route('produk.index') }}" class="nav-link {{ request()->routeIs('produk.*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam"></i> Produk
                </a>
            @endif

            @if($role === 'admin' || $role === 'operator')
                <div class="px-4 mt-4 mb-2 small text-muted text-uppercase fw-bold" style="letter-spacing: 1px; font-size: 0.7rem;">Operasional</div>
                <a href="{{ route('produksi.index') }}" class="nav-link {{ request()->routeIs('produksi.*') ? 'active' : '' }}">
                    <i class="bi bi-gear-wide-connected"></i> Produksi
                </a>
            @endif

            <div class="px-4 mt-4 mb-2 small text-muted text-uppercase fw-bold" style="letter-spacing: 1px; font-size: 0.7rem;">Transaksi</div>
            <a href="{{ route('pesanan.index') }}" class="nav-link {{ request()->routeIs('pesanan.*') ? 'active' : '' }}">
                <i class="bi bi-cart3"></i> Pesanan
            </a>
            <a href="{{ route('pembayaran.index') }}" class="nav-link {{ request()->routeIs('pembayaran.*') ? 'active' : '' }}">
                <i class="bi bi-credit-card-2-front"></i> Pembayaran
            </a>

            <div class="px-4 mt-4 mb-2 small text-muted text-uppercase fw-bold" style="letter-spacing: 1px; font-size: 0.7rem;">Layanan</div>
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
                <button type="submit" class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center py-2">
                    <i class="bi bi-box-arrow-right me-2"></i> Keluar
                </button>
            </form>
        </div>
    </div>

    <!-- Header Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container-fluid">
            <button class="btn btn-light d-lg-none me-2 shadow-sm border" type="button" id="toggleSidebar">
                <i class="bi bi-list fs-4"></i>
            </button>
            
            <div class="ms-auto d-flex align-items-center">
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark" data-bs-toggle="dropdown">
                        <div class="text-end me-3 d-none d-sm-block">
                            <div class="fw-bold small lh-1">{{ Auth::user()->name }}</div>
                            <small class="text-muted text-capitalize" style="font-size: 0.75rem;">{{ Auth::user()->role }}</small>
                        </div>
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=4e73df&color=fff&bold=true" alt="Profile" class="rounded-circle shadow-sm border" width="38">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                        <li class="px-3 py-2 d-sm-none border-bottom mb-2">
                            <div class="fw-bold small">{{ Auth::user()->name }}</div>
                            <small class="text-muted text-capitalize">{{ Auth::user()->role }}</small>
                        </li>
                        <li><a class="dropdown-item py-2" href="{{ route('notifikasi.index') }}"><i class="bi bi-bell me-2"></i> Notifikasi</a></li>
                        <li><a class="dropdown-item py-2" href="#"><i class="bi bi-person me-2"></i> Profil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger py-2"><i class="bi bi-box-arrow-right me-2"></i> Keluar</button>
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
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle-fill me-2 fs-5"></i> 
                    <div>{{ session('success') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i> 
                    <div>{{ session('error') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('toggleSidebar');
            const closeBtn = document.getElementById('closeSidebar');
            const overlay = document.getElementById('sidebarOverlay');

            function toggleSidebar() {
                sidebar.classList.toggle('show');
            }

            if (toggleBtn) toggleBtn.addEventListener('click', toggleSidebar);
            if (closeBtn) closeBtn.addEventListener('click', toggleSidebar);
            if (overlay) overlay.addEventListener('click', toggleSidebar);
        });
    </script>
    @stack('scripts')
</body>
</html>
