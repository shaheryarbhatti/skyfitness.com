<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Sky Fitness Gym – premium gym portal. Book sessions, track attendance, manage membership & payments online. Transform your fitness journey with SkyFitnessGym.com">
    <meta name="keywords"
        content="gym management system, fitness portal, gym membership online, online gym booking, fitness tracking app, gym attendance system, personal training portal, gym payment online, sky fitness gym, skyfitnessgym, fitness progress tracker, workout planner online">
    <meta name="author" content="skyfitnessgym.com">
    <link rel="icon" href="{{ asset('public/assets/images/favicon.png')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('public/assets/images/favicon.png')}}" type="image/x-icon">
    <title>Sky Fitness Gym – Premium Gym Portal</title>

    <!-- Your existing styles + assets -->
    <link rel="icon" href="{{ asset('public/assets/images/favicon.png') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@200;300;400;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('public/assets/css/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/css/vendors/icofont.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/css/vendors/themify.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/css/vendors/flag-icon.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/css/vendors/feather-icon.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/css/vendors/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/css/style.css') }}">
    <link id="color" rel="stylesheet" href="{{ asset('public/assets/css/color-1.css') }}" media="screen">
    <link rel="stylesheet" href="{{ asset('public/assets/css/responsive.css') }}">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-7 order-1">
                <img class="bg-img-cover bg-center" src="{{ asset('public/assets/images/login/login_image.jpg') }}"
                    alt="login background">
            </div>

            <div class="col-xl-5 p-0">
                <div class="login-card login-dark login-bg">
                    <div>

                        <div class="login-main">

                            <!-- Show session status / success messages (e.g. after password reset) -->
                            @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                            @endif

                            <!-- Show validation errors -->
                            @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            <form class="theme-form" method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="text-center mb-4"> {{-- Changed to text-center and added margin-bottom --}}
                                    <a class="logo d-block" href="{{ url('/') }}">
                                        {{-- Added d-block to ensure full width centering --}}
                                        <img class="img-fluid for-light"
                                            src="{{ asset('public/' . \App\Models\Setting::get('login_logo', 'assets/images/logo/logo.png')) }}"
                                            alt="logo" style="max-width: 18% !important; margin: 0 auto;">
                                        {{-- Added margin: 0 auto --}}

                                        <img class="img-fluid for-dark"
                                            src="{{ asset('public/' . \App\Models\Setting::get('login_logo', 'assets/images/logo/logo_dark.png')) }}"
                                            alt="logo dark" style="max-width: 18% !important; margin: 0 auto;">
                                    </a>
                                </div>
                                <h4>Sign in to account</h4>
                                <p>Enter your email & password to login</p>

                                <div class="form-group">
                                    <label class="col-form-label" for="email">Email Address</label>
                                    <input class="form-control @error('email') is-invalid @enderror" type="email"
                                        name="email" id="email" value="{{ old('email') }}" required autofocus
                                        autocomplete="email" placeholder="Test@gmail.com">

                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="col-form-label" for="password">Password</label>
                                    <div class="form-input position-relative">
                                        <input class="form-control @error('password') is-invalid @enderror"
                                            type="password" name="password" id="password" required
                                            autocomplete="current-password" placeholder="*********">

                                        @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror

                                        <div class="show-hide">
                                            <span class="show"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-0">
                                    <!-- <div class="checkbox p-0">
                                        <input id="remember" type="checkbox" name="remember">
                                        <label class="text-muted" for="remember">Remember password</label>
                                    </div>

                                    @if (Route::has('password.request'))
                                        <a class="link" href="{{ route('password.request') }}">Forgot password?</a>
                                    @endif -->

                                    <div class="text-end mt-3">
                                        <button class="btn btn-primary btn-block w-100" type="submit">
                                            Sign in
                                        </button>
                                    </div>
                                </div>

                                <!-- Optional: Link to register if members can self-register -->
                                <!--
                                @if (Route::has('register'))
                                    <p class="mt-3 text-center">
                                        Don't have an account? <a href="{{ route('register') }}">Register</a>
                                    </p>
                                @endif
                                -->
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts (keep your existing ones) -->
    <script src="{{ asset('public/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/icons/feather-icon/feather.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/icons/feather-icon/feather-icon.js') }}"></script>
    <script src="{{ asset('public/assets/js/config.js') }}"></script>
    <script src="{{ asset('public/assets/js/script.js') }}"></script>

    <!-- Optional: Show/hide password toggle (if your template JS doesn't handle it) -->
    <script>
    document.querySelector('.show-hide .show').addEventListener('click', function() {
        let input = this.closest('.form-input').querySelector('input');
        if (input.type === 'password') {
            input.type = 'text';
            // this.textContent = 'Hide';
        } else {
            input.type = 'password';
            // this.textContent = 'Show';
        }
    });
    </script>
</body>

</html>
