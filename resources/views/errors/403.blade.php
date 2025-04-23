<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Access Denied</title>

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
                        <div class="card-body text-center p-4">
                            <div class="mb-4">
                                <svg class="text-danger" style="width: 64px; height: 64px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            
                            <h2 class="text-danger mb-3">Access Denied</h2>
                            
                            <div class="text-muted mb-4">
                                <p class="mb-2">{{ $exception->getMessage() ?: 'You do not have permission to access this page.' }}</p>
                                <p class="small">Error Code: 403</p>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <a href="{{ route('dashboard') }}" class="btn btn-primary">
                                    Return to Dashboard
                                </a>
                                
                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>