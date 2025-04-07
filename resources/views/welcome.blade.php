<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/x-icon" href="{{ $appSetup->AppsShortLogo ? asset('storage/images/app/' . $appSetup->AppsShortLogo) : asset('images/logo-narrow.webp') }}">
        
        <title>{{ $appSetup->AppsName }}</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            <!-- Replace the existing Vite line with -->
            @vite(['resources/sass/app.scss', 'resources/js/nonapp.js'])
        @else
            <!-- Fallback styles would go here -->
        @endif
    </head>
    <body class="bg-light min-vh-100 d-flex flex-column align-items-center justify-content-center">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-lg-10">
                    <div class="card">
                        <div class="row g-0">
                            <div class="col-lg-5 bg-dark text-white d-flex align-items-center justify-content-center p-5">
                                <div class="text-center">
                                <img src="{{ $appSetup->AppsLogo ? asset('storage/images/app/' . $appSetup->AppsLogo) : asset('images/logo.png') }}" alt="{{ $appSetup->AppsName }}" style="max-width: 250px" class="logo">
                                    <h3 class="mt-3 mb-0">{{ $appSetup->AppsTitle }}</h3>
                                    <p class="lead">{{ $appSetup->AppsSubTitle }}</p>
                                </div>
                            </div>
                            <div class="col-lg-7 p-5">
                                <h4 class="mb-3">Login</h4>
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email address</label>
                                        <input type="email" class="form-control" id="email" name="email" required autofocus>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>

                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary bg-dark">Login</button>
                                    </div>
                          
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>