<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    </head>
    <body class="bg-light min-vh-100 d-flex align-items-center justify-content-center py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <div class="text-center mb-4">
                        <h1>Doxadigital Core App</h1>
                        <a href="/">
                            <svg class="icon icon-3xl text-primary">
                                <use xlink:href="{{ asset('icons/coreui.svg#full') }}"></use>
                            </svg>
                        </a>
                    </div>

                    <div class="card">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>