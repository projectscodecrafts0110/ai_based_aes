<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Document</title>
</head>
<style>
    body {
        background-color: rgb(112, 6, 6)
    }

    #background {
        background-image: url('{{ asset('images/signup-bg.jpg') }}');
        background-size: cover;
        background-position: center;
    }
</style>

<body>
    <main class="container-fluid vh-100">
        <div class="row h-100">

            <!-- LEFT: Image Section (70%) -->
            <div class="col-lg-8 d-flex justify-content-center align-items-center" id="background">
            </div>

            <!-- RIGHT: Login Card (30%) -->
            <div class="col-lg-4 d-flex justify-content-center align-items-center shadow-lg">
                <div class="card shadow-lg border-0 rounded w-100 mx-4" style="max-width: 420px;">
                    <div class="card-body p-4">
                        <div class="text-center mb-3">
                            <img src="{{ asset('images/ispsc_logo.png') }}" alt="ISPSC Logo" class="img-fluid"
                                style="max-width: 20%">
                            <h4 class="mt-2">AI-Based AES Sign Up</h3>
                        </div>
                        <hr>
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                    required>
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                                    required>
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                                @error('password')
                                    <small class="text-danger">Password Not Match</small>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-success">
                                    Create Account
                                </button>
                            </div>

                            <div class="text-center mt-3">
                                <small>
                                    Already have an account?
                                    <a href="{{ route('index') }}">Login</a>
                                </small>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <footer class="text-center py-3 small" style="background-color: rgb(223, 223, 32)">
        © {{ date('Y') }} AI-Based AES · ISPSC
        <span class="mx-1">|</span>
        Powered by CodeCraft by Harvz
    </footer>
</body>

</html>
