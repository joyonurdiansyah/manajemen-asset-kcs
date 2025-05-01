    <!DOCTYPE html>
    <html lang="en">

    <head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Login - Assetku</title>

    <!-- Favicons -->
    <link href="{{ asset('assets/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans|Nunito|Poppins" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    </head>

    <body>
    <main>
        <div class="container">
        <section class="py-4 section register min-vh-100 d-flex flex-column align-items-center justify-content-center">
            <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                <div class="py-4 d-flex justify-content-center">
                    <a href="#" class="w-auto logo d-flex align-items-center">
                    <img src="{{ asset('assets/img/logo.png') }}" alt="">
                    <span class="d-none d-lg-block">Assetku</span>
                    </a>
                </div><!-- End Logo -->

                <div class="mb-3 card">
                    <div class="card-body">

                    <div class="pt-4 pb-2">
                        <h5 class="pb-0 text-center card-title fs-4">Masuk ke dalam Akunmu</h5>
                        <p class="text-center small">Masukkan Email & Kata sandi untuk masuk</p>
                    </div>

                    <!-- Laravel Login Form -->
                    <form method="POST" action="{{ route('login') }}" class="row g-3 needs-validation" novalidate>
                        @csrf

                        @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                        @endif

                        <div class="col-12">
                        <label for="email" class="form-label">Email</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text" id="inputGroupPrepend">@</span>
                            <input type="email" name="email" class="form-control" id="email" value="{{ old('email') }}" required autofocus>
                            @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        </div>

                        <div class="col-12">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" id="password" required>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        </div>

                        <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
                            <label class="form-check-label" for="rememberMe">Remember me</label>
                        </div>
                        </div>

                        <div class="col-12">
                        <button class="btn btn-primary w-100" type="submit">Login</button>
                        </div>

                        @if (Route::has('password.request'))
                        <div class="text-center col-12">
                        <a href="{{ route('password.request') }}">Lupa Kata Sandi?</a>
                        </div>
                        @endif

                        <div class="col-12">
                        <p class="mb-0 small">Belum punya akun bre? <a href="{{ route('register') }}">Buat dulu yuk!</a></p>
                        </div>
                    </form>

                    </div>
                </div>

                </div>
            </div>
            </div>
        </section>
        </div>
    </main>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

    </body>
    </html>
