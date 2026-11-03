<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Pick-Up Chacabuco') - Venta de Pick-Ups y Autos</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #0f172a;
            --accent-color: #e11d48;
            --accent-hover: #be123c;
            --bg-light: #f8fafc;
        }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background-color: var(--bg-light);
            color: #334155;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar-brand {
            font-weight: 800;
            letter-spacing: -0.5px;
            color: #0f172a !important;
        }

        .navbar-brand .accent {
            color: var(--accent-color);
        }

        .nav-link {
            font-weight: 500;
            color: #475569 !important;
            transition: color 0.2s ease;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--accent-color) !important;
        }

        .btn-accent {
            background-color: var(--accent-color);
            color: #fff;
            font-weight: 600;
            border: none;
            transition: all 0.2s ease;
        }

        .btn-accent:hover {
            background-color: var(--accent-hover);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(225, 29, 72, 0.25);
        }

        .btn-dark-custom {
            background-color: var(--primary-color);
            color: #fff;
            font-weight: 600;
            border: none;
        }

        .btn-dark-custom:hover {
            background-color: #1e293b;
            color: #fff;
        }

        .card-vehicle {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            background: #fff;
        }

        .card-vehicle:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px -10px rgba(0, 0, 0, 0.12);
        }

        .vehicle-img-wrapper {
            position: relative;
            width: 100%;
            height: 220px;
            background: #e2e8f0;
            overflow: hidden;
        }

        .vehicle-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .card-vehicle:hover .vehicle-img-wrapper img {
            transform: scale(1.04);
        }

        .badge-status {
            position: absolute;
            top: 12px;
            left: 12px;
            font-size: 0.75rem;
            padding: 0.35rem 0.65rem;
            border-radius: 6px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-type {
            position: absolute;
            top: 12px;
            right: 12px;
            font-size: 0.75rem;
            padding: 0.35rem 0.65rem;
            border-radius: 6px;
            font-weight: 700;
            text-transform: uppercase;
        }

        footer {
            margin-top: auto;
            background-color: #0f172a;
            color: #94a3b8;
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Header / Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('autos.index') }}">
                <i class="bi bi-truck-flatbed text-danger fs-3"></i>
                <span>PICK-UP <span class="accent">CHACABUCO</span></span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('autos.index') ? 'active' : '' }}" href="{{ route('autos.index') }}">
                            <i class="bi bi-grid me-1"></i> Catálogo
                        </a>
                    </li>
                    @auth
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('autos.my') ? 'active' : '' }}" href="{{ route('autos.my') }}">
                            <i class="bi bi-collection me-1"></i> Mis Publicaciones
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('ventas.compras') ? 'active' : '' }}" href="{{ route('ventas.compras') }}">
                            <i class="bi bi-bag-check me-1"></i> Mis Compras
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('ventas.sales') ? 'active' : '' }}" href="{{ route('ventas.sales') }}">
                            <i class="bi bi-cash-stack me-1"></i> Mis Ventas
                        </a>
                    </li>
                    @endauth
                </ul>

                <div class="d-flex align-items-center gap-2">
                    @auth
                        <a href="{{ route('autos.create') }}" class="btn btn-accent btn-sm px-3 py-2">
                            <i class="bi bi-plus-circle me-1"></i> Publicar Auto
                        </a>

                        <div class="dropdown">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle d-flex align-items-center gap-2 px-3 py-2" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle fs-6"></i>
                                <span>{{ Auth::user()->name }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                <li class="dropdown-header">Conectado como<br><strong>{{ Auth::user()->email }}</strong></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('autos.my') }}">
                                        <i class="bi bi-collection me-2"></i> Mis Publicaciones
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('ventas.compras') }}">
                                        <i class="bi bi-bag-check me-2"></i> Mis Compras
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('ventas.sales') }}">
                                        <i class="bi bi-cash-stack me-2"></i> Mis Ventas
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-dark btn-sm px-3 py-2">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Iniciar Sesión
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-accent btn-sm px-3 py-2">
                            <i class="bi bi-person-plus me-1"></i> Registrarse
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Notificaciones Flash -->
    <div class="container mt-3">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-check-circle-fill fs-5"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-exclamation-circle-fill fs-5"></i>
                <div>{{ session('warning') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('info'))
            <div class="alert alert-info alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-info-circle-fill fs-5"></i>
                <div>{{ session('info') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <!-- Contenido Principal -->
    <main class="flex-grow-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="py-4 mt-5">
        <div class="container text-center text-md-start">
            <div class="row align-items-center">
                <div class="col-md-6 mb-3 mb-md-0">
                    <h5 class="text-white fw-bold mb-1">Pick-Up Chacabuco 2026</h5>
                    <p class="small mb-0 text-muted">Tu portal de compra y venta de camionetas y autos seleccionados.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="small mb-0 text-muted">
                        &copy; {{ date('Y') }} Pick-Up Chacabuco. Todos los derechos reservados.
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
