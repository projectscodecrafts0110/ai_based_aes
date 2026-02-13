<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        body {
            background-image: url({{ asset('images/hero-bg.jpg') }});
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .hero {
            min-height: 80vh;
            display: flex;
            align-items: center;
        }

        .job-card:hover {
            transform: translateY(-6px);
            transition: 0.3s;
            box-shadow: 0 10px 25px rgba(0, 0, 0, .1);
        }

        footer {
            background: #444444;
            color: white
        }
    </style>
</head>

<body>

    @yield('content')

    <!-- FOOTER -->
    <footer class="py-4 mt-5">
        <div class="container text-center">
            <p class="mb-1">© {{ date('Y') }} AI-Based Applicant Evaluation System</p>
            <small>Smart recruitment powered by Artificial Intelligence</small>
        </div>
    </footer>

</body>

</html>
