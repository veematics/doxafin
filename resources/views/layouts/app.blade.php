<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" type="image/x-icon" href="{{ $appSetup->AppsShortLogo ? asset('storage/images/app/' . $appSetup->AppsShortLogo) : asset('images/logo-narrow.webp') }}">
        <title>{{ $appSetup->AppsName }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/sass/app.scss', 'resources/css/custom.css', 'resources/js/app.js'])
        <script>
            // Theme initialization
            document.addEventListener('DOMContentLoaded', function() {
                const storedTheme = localStorage.getItem('theme') || 'auto';
                setTheme(storedTheme);

                // Theme switcher event handlers
                document.querySelectorAll('[data-coreui-theme-value]').forEach(toggle => {
                    toggle.addEventListener('click', () => {
                        const theme = toggle.getAttribute('data-coreui-theme-value');
                        setTheme(theme);
                        localStorage.setItem('theme', theme);
                    });
                });

                function setTheme(theme) {
                    if (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                        document.documentElement.setAttribute('data-coreui-theme', 'dark');
                    } else {
                        document.documentElement.setAttribute('data-coreui-theme', theme);
                    }
                }

                // Listen for system theme changes when in auto mode
                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
                    const storedTheme = localStorage.getItem('theme') || 'auto';
                    if (storedTheme === 'auto') {
                        setTheme('auto');
                    }
                });
            });
        </script>
    </head>
    <body>
       
    <div class="sidebar sidebar-fixed sidebar-dark bg-dark-gradient border-end" id="sidebar">
      <div class="sidebar-header border-bottom">
        <div class="sidebar-brand">
          <div class="sidebar-brand-full" alt="CoreUI Logo">
            <img src="{{ $appSetup->AppsLogo ? asset('storage/images/app/' . $appSetup->AppsLogo) : asset('images/logo.png') }}" width="200">
            </div>
          <div class="sidebar-brand-narrow" width="50" height="50" alt="CoreUI Logo">
          <img src="{{ $appSetup->AppsShortLogo ? asset('storage/images/app/' . $appSetup->AppsShortLogo) : asset('images/logo-narrow.webp') }}" width="40">
            </div>
        </div>
        <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>
        <button class="btn-close d-lg-none" type="button" data-coreui-theme="dark" aria-label="Close" onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()"></button>
      </div>
      <ul class="sidebar-nav" data-coreui="navigation" data-simplebar>
        <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">
            <svg class="nav-icon">
              <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-speedometer"></use>
            </svg><span data-coreui-i18n="dashboard">Dashboard</span></a></li>
            <li class="nav-title" data-coreui-i18n="components">Finance and Project Management</li>
            <x-sidebar-menu />   
         

      </ul>
      <x-debug-info />
    </div>

    <!-- ... rest of the content remains unchanged ... -->
    <div class="sidebar sidebar-light sidebar-lg sidebar-end sidebar-overlaid border-start" id="aside">
      <div class="sidebar-header p-0 position-relative">
        <ul class="nav nav-underline-border w-100" role="tablist">
          <li class="nav-item"><a class="nav-link active" data-coreui-toggle="tab" href="#timeline" role="tab">
              <svg class="icon">
                <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-envelope-closed"></use>
              </svg></a></li>
              <!-- add more li to add panes change href and make sure use same id on tab-content
                for example href="#timeline" -> id="timeline"
              -->

        </ul>
        <button class="btn-close position-absolute top-50 end-0 translate-middle my-0" type="button" aria-label="Close" onclick="coreui.Sidebar.getInstance(document.querySelector('#aside')).toggle()"></button>
      </div>
      <!-- Tab panes-->
      <div class="tab-content">
        <div class="tab-pane active" id="timeline" role="tabpanel">
          <x-last-messages />
        </div>
 

      </div>
    </div>
    <div class="wrapper d-flex flex-column min-vh-100">
      <header class="header header-sticky p-0 mb-4">
        <div class="container-fluid px-4">
          <button class="header-toggler d-lg-none" type="button" onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()" style="margin-inline-start: -14px;">
            <svg class="icon icon-lg">
              <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-menu"></use>
            </svg>
          </button>
        
          <ul class="header-nav d-none d-md-flex ms-auto ">
            <li class="nav-item dropdown"><a class="nav-link" data-coreui-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><span class="d-inline-block my-1 mx-2 position-relative">
                  <svg class="icon icon-lg">
                    <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-bell"></use>
                  </svg><span class="position-absolute top-0 start-100 translate-middle p-1 rounded-circle" id="alertNotification" ></span></span></a>
              <div class="dropdown-menu dropdown-menu-end dropdown-menu-lg pt-0" style="min-width: 600px">
                <!-- With cache -->
                  <x-alert-notifications />
              </div>
            </li>
         
          </ul>
          <ul class="header-nav ms-auto ms-md-0">
          <li class="nav-item py-1">
            <form class="d-flex" action="{{ route('clients.contacts.search') }}" method="GET">
                <div class="input-group">
                    <input class="form-control form-control-sm" type="search" name="s" placeholder="Search Contact or Company..." aria-label="Search">
                    <button class="btn btn-sm btn-outline-primary" type="submit">
                        <svg class="icon">
                            <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-search"></use>
                        </svg>
                    </button>
                </div>
            </form>
          </li>
            <li class="nav-item py-1">
              <div class="vr h-100 mx-2 text-body text-opacity-75"></div>
            </li>
            
            <li class="nav-item dropdown">
              <button class="btn btn-link nav-link" type="button" aria-expanded="false" data-coreui-toggle="dropdown">
                <svg class="icon icon-lg theme-icon-active">
                  <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-contrast"></use>
                </svg>
              </button>
              <ul class="dropdown-menu dropdown-menu-end" style="--cui-dropdown-min-width: 8rem;">
                <li>
                  <button class="dropdown-item d-flex align-items-center" type="button" data-coreui-theme-value="light">
                    <svg class="icon icon-lg me-3">
                      <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-sun"></use>
                    </svg><span data-coreui-i18n="light">Light</span>
                  </button>
                </li>
                <li>
                  <button class="dropdown-item d-flex align-items-center" type="button" data-coreui-theme-value="dark">
                    <svg class="icon icon-lg me-3">
                      <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-moon"></use>
                    </svg><span data-coreui-i18n="dark"> Dark</span>
                  </button>
                </li>
                <li>
                  <button class="dropdown-item d-flex align-items-center active" type="button" data-coreui-theme-value="auto">
                    <svg class="icon icon-lg me-3">
                      <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-contrast"></use>
                    </svg>Auto
                  </button>
                </li>
              </ul>
            </li>
            <li class="nav-item py-1">
              <div class="vr h-100 mx-2 text-body text-opacity-75"></div>
            </li>
            <li class="nav-item dropdown"><a class="nav-link py-0" data-coreui-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                <div class="avatar avatar-md">
                  <img  class="avatar-img" src="{{ auth()->user()->avatar ? asset('images/avatars/' . auth()->user()->avatar) : asset('images/avatars/avatar-default.svg') }}" />
                  </div></a>
              <div class="dropdown-menu dropdown-menu-end pt-0">
            
                    <x-personal-menu />
                    <x-super-admin-menu />
              </div>
            </li>
          </ul>
          <button class="header-toggler" type="button" onclick="coreui.Sidebar.getInstance(document.querySelector('#aside')).show()" style="margin-inline-end: -12px">
            <svg class="icon icon-lg">
              <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-applications-settings"></use>
            </svg>
          </button>
        </div>
      </header>
      <div class="body flex-grow-1">
        <div class="container-lg px-4 app-width">
        {{ $slot }}

            
          </div>
         
        </div>
      </div>
      <footer class="footer px-4">
        <div>Doxadigital 2025 </div>

      </footer>
      @stack('scripts')
          <!-- Add this before closing body tag -->
    <script src="{{ asset('js/toast-notification.js') }}"></script>
    </div>
    </body>
</html>