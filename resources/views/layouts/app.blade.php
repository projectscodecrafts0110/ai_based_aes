<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'AES - Applicant Evaluation System')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --aes-maroon: #800000;
            --aes-gold: #b4b400;
            --aes-gold-dark: #888824;
            --aes-light: #f8f9fa;
            --aes-bg: #f5f6f8;
            --aes-text-dark: #212529;
        }

        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: var(--aes-bg);
            color: var(--aes-text-dark);
        }

        /* TOP NAVBAR */
        nav.navbar {
            background-color: var(--aes-maroon);
        }

        .navbar-brand,
        .navbar-nav .nav-link {
            color: #fff !important;
        }

        .navbar-nav .nav-link {
            font-weight: 500;
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            transition: background-color 0.2s ease;
        }

        .navbar-nav .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.15);
        }

        nav .nav-link.active {
            background-color: var(--aes-gold-dark);
            font-weight: 700;
        }

        /* SIDEBAR */
        #sidebar {
            min-width: 220px;
            max-width: 220px;
            height: calc(100vh - 56px);
            /* 56px = navbar height */
            background-color: var(--aes-gold);
            position: sticky;
            top: 56px;
        }

        #sidebar .nav-link {
            color: #000;
            font-weight: 500;
            padding: 0.6rem 0.75rem;
            border-radius: 6px;
            transition: background-color 0.2s ease, padding-left 0.2s ease;
        }

        #sidebar .nav-link:hover {
            background-color: var(--aes-gold-dark);
            padding-left: 1rem;
        }

        #sidebar .nav-link.active {
            background-color: var(--aes-gold-dark);
            font-weight: 700;
        }

        /* MAIN CONTENT */
        #main-content {
            flex: 1;
            padding: 24px;
            margin: 16px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            overflow-y: auto;
            max-height: calc(100vh - 56px);
        }

        /* FOOTER */
        footer {
            background-color: var(--aes-light);
            color: #6c757d;
        }
    </style>
</head>

<body>

    <!-- TOP NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm p-3 sticky-top">
        <div class="container-fluid">

            <a class="navbar-brand fw-bold" href="#">
                SMARTHIRE
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNavbar"
                aria-controls="topNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="topNavbar">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('about') }}">About Us</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contact') }}">Contact Us</a>
                    </li>

                    <li class="nav-item ms-lg-1">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary text-white btn-sm">
                                Logout
                            </button>
                        </form>
                    </li>

                </ul>
            </div>

        </div>
    </nav>

    <!-- BODY WRAPPER -->
    <div class="d-flex">

        <!-- SIDEBAR -->
        <aside id="sidebar" class="border-end p-3 d-none d-lg-block">
            <ul class="nav flex-column">

                <li class="nav-item mb-2">
                    <a href="{{ route('apply.filter') }}" class="nav-link">Apply for Job</a>
                </li>

                <li class="nav-item mb-2">
                    <a href="{{ route('applications.status') }}" class="nav-link">Application Status</a>
                </li>

            </ul>
        </aside>

        <!-- MAIN CONTENT -->
        <main id="main-content" class="flex-grow-1">
            @yield('content')
        </main>

    </div>

    <!-- FOOTER -->
    <footer class="text-center py-3 mt-auto small bg-dark text-white border-top">
        © {{ date('Y') }} SMARTHIRE · ISPSC
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')

</body>

</html>
