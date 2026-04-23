<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Eca Print</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@500;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
        }

        .auth-bg {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(circle at top right, #4e73df, transparent),
                radial-gradient(circle at bottom left, #224abe, transparent);
            background-color: #4e73df;
            padding: 2rem 1rem;
        }

        .auth-card {
            border: none;
            border-radius: 1.5rem;
            overflow: hidden;
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }

        .brand-logo {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            letter-spacing: -1px;
            color: #4e73df;
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.15);
            border-color: #4e73df;
        }

        .btn-primary {
            background-color: #4e73df;
        }

        .btn-primary:hover {
            background-color: #2e59d9;
        }

        @media (max-width: 576px) {
            .auth-card {
                border-radius: 1.25rem;
            }
        }
    </style>
</head>

<body>
    <div class="auth-bg">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-8 col-sm-10">
                    <div class="text-center mb-4 text-white">
                        <h2 class="brand-logo fs-1 text-white mb-1">Eca Print</h2>
                        <p class="opacity-75">Solusi Cetak Cepat & Berkualitas</p>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="auth-card p-4 p-md-5">
                        @yield('content')
                    </div>

                    <div class="text-center mt-4 text-white opacity-50 small">
                        &copy; {{ date('Y') }} Eca Print Mandiri. All rights reserved.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>