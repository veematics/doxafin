<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/images/app/' . ($appSetup->AppsShortLogo ?? 'favicon.ico')) }}">
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
            <img src="{{ asset('storage/images/app/' . ($appSetup->AppsLogo ?? 'logo.png')) }}" width="200">
            </div>
          <div class="sidebar-brand-narrow" width="50" height="50" alt="CoreUI Logo">
          <img src="{{ asset('storage/images/app/' . ($appSetup->AppsShortLogo ?? 'logo-narrow.webp')) }}" width="40">
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
      </ul>
    </div>

    <!-- ... rest of the content remains unchanged ... -->
    <div class="sidebar sidebar-light sidebar-lg sidebar-end sidebar-overlaid border-start" id="aside">
      <div class="sidebar-header p-0 position-relative">
        <ul class="nav nav-underline-border w-100" role="tablist">
          <li class="nav-item"><a class="nav-link active" data-coreui-toggle="tab" href="#timeline" role="tab">
              <svg class="icon">
                <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-list"></use>
              </svg></a></li>
          <li class="nav-item"><a class="nav-link" data-coreui-toggle="tab" href="#messages" role="tab">
              <svg class="icon">
                <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-speech"></use>
              </svg></a></li>
          <li class="nav-item"><a class="nav-link" data-coreui-toggle="tab" href="#settings" role="tab">
              <svg class="icon">
                <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-settings"></use>
              </svg></a></li>
        </ul>
        <button class="btn-close position-absolute top-50 end-0 translate-middle my-0" type="button" aria-label="Close" onclick="coreui.Sidebar.getInstance(document.querySelector('#aside')).toggle()"></button>
      </div>
      <!-- Tab panes-->
      <div class="tab-content">
        <div class="tab-pane active" id="timeline" role="tabpanel">
          <div class="list-group list-group-flush">
            <div class="list-group-item border-start-4 border-start-secondary bg-body-tertiary text-center fw-bold text-body-secondary text-uppercase small" data-coreui-i18n="today">Today</div>
            <div class="list-group-item border-start-4 border-start-warning list-group-item-divider">
              <div class="avatar avatar-lg float-end"><img class="avatar-img" src="images/avatars/avatar-default.svg" alt="user@email.com"></div>
              <div>Meeting with <strong>Lucas</strong></div><small class="text-body-secondary me-3">
                <svg class="icon">
                  <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-calendar"></use>
                </svg>  1 - 3pm</small><small class="text-body-secondary">
                <svg class="icon">
                  <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-location-pin"></use>
                </svg>  Palo Alto, CA</small>
            </div>
            <div class="list-group-item border-start-4 border-start-info">
              <div class="avatar avatar-lg float-end"><img class="avatar-img" src="images/avatars/avatar-default.svg" alt="user@email.com"></div>
              <div>Skype with <strong>Megan</strong></div><small class="text-body-secondary me-3">
                <svg class="icon">
                  <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-calendar"></use>
                </svg>  4 - 5pm</small><small class="text-body-secondary">
                <svg class="icon">
                  <use xlink:href="{{ asset('assets/icons/free/brand.svg') }}#cib-skype"></use>
                </svg>  On-line</small>
            </div>
            <div class="list-group-item border-start-4 border-start-secondary bg-body-tertiary text-center fw-bold text-body-secondary text-uppercase small" data-coreui-i18n="tomorrow">Tomorrow</div>
            <div class="list-group-item border-start-4 border-start-danger list-group-item-divider">
              <div>New UI Project - <strong>deadline</strong></div><small class="text-body-secondary me-3">
                <svg class="icon">
                  <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-calendar"></use>
                </svg>  10 - 11pm</small><small class="text-body-secondary">
                <svg class="icon">
                  <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-home"></use>
                </svg>  creativeLabs HQ</small>
              <div class="avatars-stack mt-2">
                <div class="avatar avatar-xs"><img class="avatar-img" src="images/avatars/avatar-default.svg" alt="user@email.com"></div>
                <div class="avatar avatar-xs"><img class="avatar-img" src="images/avatars/avatar-default.svg" alt="user@email.com"></div>
                <div class="avatar avatar-xs"><img class="avatar-img" src="images/avatars/avatar-default.svg" alt="user@email.com"></div>
                <div class="avatar avatar-xs"><img class="avatar-img" src="images/avatars/avatar-default.svg" alt="user@email.com"></div>
                <div class="avatar avatar-xs"><img class="avatar-img" src="images/avatars/avatar-default.svg" alt="user@email.com"></div>
              </div>
            </div>
            <div class="list-group-item border-start-4 border-start-success list-group-item-divider">
              <div><strong>#10 Startups.Garden</strong> Meetup</div><small class="text-body-secondary me-3">
                <svg class="icon">
                  <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-calendar"></use>
                </svg>  1 - 3pm</small><small class="text-body-secondary">
                <svg class="icon">
                  <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-location-pin"></use>
                </svg>  Palo Alto, CA</small>
            </div>
            <div class="list-group-item border-start-4 border-start-primary list-group-item-divider">
              <div><strong>Team meeting</strong></div><small class="text-body-secondary me-3">
                <svg class="icon">
                  <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-calendar"></use>
                </svg>  4 - 6pm</small><small class="text-body-secondary">
                <svg class="icon">
                  <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-home"></use>
                </svg>  creativeLabs HQ</small>
              <div class="avatars-stack mt-2">
                <div class="avatar avatar-xs"><img class="avatar-img" src="images/avatars/avatar-default.svg" alt="user@email.com"></div>
                <div class="avatar avatar-xs"><img class="avatar-img" src="images/avatars/avatar-default.svg" alt="user@email.com"></div>
                <div class="avatar avatar-xs"><img class="avatar-img" src="images/avatars/avatar-default.svg" alt="user@email.com"></div>
                <div class="avatar avatar-xs"><img class="avatar-img" src="images/avatars/avatar-default.svg" alt="user@email.com"></div>
                <div class="avatar avatar-xs"><img class="avatar-img" src="images/avatars/avatar-default.svg" alt="user@email.com"></div>
                <div class="avatar avatar-xs"><img class="avatar-img" src="images/avatars/avatar-default.svg" alt="user@email.com"></div>
                <div class="avatar avatar-xs"><img class="avatar-img" src="images/avatars/avatar-default.svg" alt="user@email.com"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="tab-pane p-3" id="messages" role="tabpanel">
          <div class="message">
            <div class="py-3 pb-5 me-3 float-start">
              <div class="avatar"><img class="avatar-img" src="images/avatars/avatar-default.svg" alt="user@email.com"><span class="avatar-status bg-success"></span></div>
            </div>
            <div><small class="text-body-secondary">Lukasz Holeczek</small><small class="text-body-secondary float-end mt-1">1:52 PM</small></div>
            <div class="text-truncate fw-bold">Lorem ipsum dolor sit amet</div><small class="text-body-secondary">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt...</small>
          </div>
          <hr>
          <div class="message">
            <div class="py-3 pb-5 me-3 float-start">
              <div class="avatar"><img class="avatar-img" src="images/avatars/avatar-default.svg" alt="user@email.com"><span class="avatar-status bg-success"></span></div>
            </div>
            <div><small class="text-body-secondary">Lukasz Holeczek</small><small class="text-body-secondary float-end mt-1">1:52 PM</small></div>
            <div class="text-truncate fw-bold">Lorem ipsum dolor sit amet</div><small class="text-body-secondary">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt...</small>
          </div>
          <hr>
          <div class="message">
            <div class="py-3 pb-5 me-3 float-start">
              <div class="avatar"><img class="avatar-img" src="images/avatars/avatar-default.svg" alt="user@email.com"><span class="avatar-status bg-success"></span></div>
            </div>
            <div><small class="text-body-secondary">Lukasz Holeczek</small><small class="text-body-secondary float-end mt-1">1:52 PM</small></div>
            <div class="text-truncate fw-bold">Lorem ipsum dolor sit amet</div><small class="text-body-secondary">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt...</small>
          </div>
          <hr>
          <div class="message">
            <div class="py-3 pb-5 me-3 float-start">
              <div class="avatar"><img class="avatar-img" src="images/avatars/avatar-default.svg" alt="user@email.com"><span class="avatar-status bg-success"></span></div>
            </div>
            <div><small class="text-body-secondary">Lukasz Holeczek</small><small class="text-body-secondary float-end mt-1">1:52 PM</small></div>
            <div class="text-truncate fw-bold">Lorem ipsum dolor sit amet</div><small class="text-body-secondary">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt...</small>
          </div>
          <hr>
          <div class="message">
            <div class="py-3 pb-5 me-3 float-start">
              <div class="avatar"><img class="avatar-img" src="images/avatars/avatar-default.svg" alt="user@email.com"><span class="avatar-status bg-success"></span></div>
            </div>
            <div><small class="text-body-secondary">Lukasz Holeczek</small><small class="text-body-secondary float-end mt-1">1:52 PM</small></div>
            <div class="text-truncate fw-bold">Lorem ipsum dolor sit amet</div><small class="text-body-secondary">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt...</small>
          </div>
        </div>
        <div class="tab-pane p-3" id="settings" role="tabpanel">
          <h6 data-coreui-i18n="settings">Settings</h6>
          <div class="aside-options">
            <div class="clearfix mt-4">
              <div class="form-check form-switch form-switch-lg">
                <input class="form-check-input me-0" id="settingOption1" type="checkbox" checked>
                <label class="form-check-label fw-semibold small" for="settingOption1">Option 1</label>
              </div>
            </div>
            <div><small class="text-body-secondary">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</small></div>
          </div>
          <div class="aside-options">
            <div class="clearfix mt-3">
              <div class="form-check form-switch form-switch-lg">
                <input class="form-check-input me-0" id="settingOption2" type="checkbox">
                <label class="form-check-label fw-semibold small" for="settingOption2">Option 2</label>
              </div>
            </div>
            <div><small class="text-body-secondary">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</small></div>
          </div>
          <div class="aside-options">
            <div class="clearfix mt-3">
              <div class="form-check form-switch form-switch-lg">
                <input class="form-check-input me-0" id="settingOption3" type="checkbox">
                <label class="form-check-label fw-semibold small" for="settingOption3">Option 3</label>
              </div>
            </div>
          </div>
          <div class="aside-options">
            <div class="clearfix mt-3">
              <div class="form-check form-switch form-switch-lg">
                <input class="form-check-input me-0" id="settingOption4" type="checkbox" checked>
                <label class="form-check-label fw-semibold small" for="settingOption4">Option 4</label>
              </div>
            </div>
          </div>
          <hr>
          <h6 data-coreui-i18n="systemUtilization">System Utilization</h6>
          <div class="small text-uppercase fw-semibold mb-1 mt-4" data-coreui-i18n="cpuUsage">CPU Usage</div>
          <div class="progress progress-thin">
            <div class="progress-bar bg-info" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
          <div class="small text-body-secondary" data-coreui-i18n="cpuUsageDescription, { 'number_of_processes': 358, 'number_of_cores': '1/4' }">348 Processes. 1/4 Cores.</div>
          <div class="small text-uppercase fw-semibold mb-1 mt-2" data-coreui-i18n="memoryUsage">Memory Usage</div>
          <div class="progress progress-thin">
            <div class="progress-bar bg-warning" role="progressbar" style="width: 70%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
          <div class="small text-body-secondary">11444GB/16384MB</div>
          <div class="small text-uppercase fw-semibold mb-1 mt-2" data-coreui-i18n="ssdUsage">SSD Usage</div>
          <div class="progress progress-thin">
            <div class="progress-bar bg-danger" role="progressbar" style="width: 95%" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
          <div class="small text-body-secondary">243GB/256GB</div>
          <div class="small text-uppercase fw-semibold mb-1 mt-2" data-coreui-i18n="ssdUsage">SSD Usage</div>
          <div class="progress progress-thin">
            <div class="progress-bar bg-success" role="progressbar" style="width: 10%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
          <div class="small text-body-secondary">25GB/256GB</div>
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
        
          <ul class="header-nav d-none d-md-flex ms-auto">
            <li class="nav-item dropdown"><a class="nav-link" data-coreui-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><span class="d-inline-block my-1 mx-2 position-relative">
                  <svg class="icon icon-lg">
                    <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-bell"></use>
                  </svg><span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger rounded-circle"><span class="visually-hidden">New alerts</span></span></span></a>
              <div class="dropdown-menu dropdown-menu-end dropdown-menu-lg pt-0">
                <div class="dropdown-header bg-body-tertiary text-body-secondary fw-semibold rounded-top mb-2" data-coreui-i18n="notificationsCounter, { 'counter': 5 }">You have 5 notifications</div><a class="dropdown-item" href="#">
                  <svg class="icon me-2 text-success">
                    <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-user-follow"></use>
                  </svg><span data-coreui-i18n="newUserRegistered">New user registered</span></a><a class="dropdown-item" href="#">
                  <svg class="icon me-2 text-danger">
                    <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-user-unfollow"></use>
                  </svg><span data-coreui-i18n="userDeleted">User deleted</span></a><a class="dropdown-item" href="#">
                  <svg class="icon me-2 text-info">
                    <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-chart"></use>
                  </svg><span data-coreui-i18n="salesReportIsReady">Sales report is ready</span></a><a class="dropdown-item" href="#">
                  <svg class="icon me-2 text-success">
                    <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-basket"></use>
                  </svg><span data-coreui-i18n="newClient">New client</span></a><a class="dropdown-item" href="#">
                  <svg class="icon me-2 text-warning">
                    <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-speedometer"></use>
                  </svg><span data-coreui-i18n="serverOverloaded">Server overloaded</span></a>
                <div class="dropdown-header bg-body-tertiary text-body-secondary fw-semibold my-2" data-coreui-i18n="server">Server</div><a class="dropdown-item d-block py-2" href="#">
                  <div class="text-uppercase small fw-semibold mb-1" data-coreui-i18n="cpuUsage">CPU Usage</div>
                  <div class="progress progress-thin">
                    <div class="progress-bar bg-info-gradient" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                  <div class="small text-body-secondary" data-coreui-i18n="cpuUsageDescription, { 'number_of_processes': 358, 'number_of_cores': '1/4' }">348 Processes. 1/4 Cores.</div></a><a class="dropdown-item d-block py-2" href="#">
                  <div class="text-uppercase small fw-semibold mb-1" data-coreui-i18n="memoryUsage">Memory Usage</div>
                  <div class="progress progress-thin">
                    <div class="progress-bar bg-warning-gradient" role="progressbar" style="width: 70%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                  <div class="small text-body-secondary">11444MB/16384MB</div></a><a class="dropdown-item d-block py-2" href="#">
                  <div class="text-uppercase small fw-semibold mb-1" data-coreui-i18n="ssdUsage">SSD Usage</div>
                  <div class="progress progress-thin">
                    <div class="progress-bar bg-danger-gradient" role="progressbar" style="width: 95%" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                  <div class="small text-body-secondary">243GB/256GB</div></a>
              </div>
            </li>
            <li class="nav-item dropdown"><a class="nav-link" data-coreui-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><span class="d-inline-block my-1 mx-2 position-relative">
                  <svg class="icon icon-lg">
                    <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-list-rich"></use>
                  </svg><span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger rounded-circle"><span class="visually-hidden">New alerts</span></span></span></a>
              <div class="dropdown-menu dropdown-menu-end dropdown-menu-lg py-0">
                <div class="dropdown-header bg-body-tertiary text-body-secondary fw-semibold rounded-top mb-2" data-coreui-i18n="taskCounter, { 'counter': 5 }">You have 5 pending tasks</div><a class="dropdown-item d-block" href="#">
                  <div class="small mb-1">Upgrade NPM
                    <div class="fw-semibold">0%</div>
                  </div><span class="progress progress-thin">
                    <div class="progress-bar bg-info" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div></span></a><a class="dropdown-item d-block py-2" href="#">
                  <div class="d-flex justify-content-between mb-1">
                    <div class="small">ReactJS Version</div>
                    <div class="fw-semibold">25%</div>
                  </div><span class="progress progress-thin">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div></span></a><a class="dropdown-item d-block py-2" href="#">
                  <div class="d-flex justify-content-between mb-1">
                    <div class="small">VueJS Version</div>
                    <div class="fw-semibold">50%</div>
                  </div><span class="progress progress-thin">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div></span></a><a class="dropdown-item d-block py-2" href="#">
                  <div class="d-flex justify-content-between mb-1">
                    <div class="small">Add new layouts</div>
                    <div class="fw-semibold">75%</div>
                  </div><span class="progress progress-thin">
                    <div class="progress-bar bg-info" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div></span></a><a class="dropdown-item d-block py-2" href="#">
                  <div class="d-flex justify-content-between mb-1">
                    <div class="small">Angular Version</div>
                    <div class="fw-semibold">100%</div>
                  </div><span class="progress progress-thin">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div></span></a>
                <div class="p-2"><a class="btn btn-outline-primary w-100" href="#" data-coreui-i18n="viewAllTasks">View all tasks</a></div>
              </div>
            </li>
            <li class="nav-item dropdown"><a class="nav-link" data-coreui-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><span class="d-inline-block my-1 mx-2 position-relative">
                  <svg class="icon icon-lg">
                    <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-envelope-open"></use>
                  </svg><span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger rounded-circle"><span class="visually-hidden">New alerts</span></span></span></a>
              <div class="dropdown-menu dropdown-menu-end dropdown-menu-lg py-0" style="min-width: 24rem">
                <div class="dropdown-header bg-body-tertiary text-body-secondary fw-semibold rounded-top mb-2" data-coreui-i18n="messagesCounter, { 'counter': 7 }">You have 4 messages</div><a class="dropdown-item" href="#">
                  <div class="d-flex">
                    <div class="avatar flex-shrink-0 my-3 me-3"><img class="avatar-img" src="images/avatars/avatar-default.svg" alt="user@email.com"><span class="avatar-status bg-success"></span></div>
                    <div class="message text-wrap">
                      <div class="d-flex justify-content-between mt-1">
                        <div class="small text-body-secondary">Jessica Williams</div>
                        <div class="small text-body-secondary">Just now</div>
                      </div>
                      <div class="fw-semibold"><span class="text-danger">! </span>Urgent: System Maintenance Tonight</div>
                      <div class="small text-body-secondary">Attention team, we'll be conducting critical system maintenance tonight from 10 PM to 2 AM. Plan accordingly...</div>
                    </div>
                  </div></a><a class="dropdown-item" href="#">
                  <div class="d-flex">
                    <div class="avatar flex-shrink-0 my-3 me-3"><img class="avatar-img" src="images/avatars/avatar-default.svg" alt="user@email.com"><span class="avatar-status bg-warning"></span></div>
                    <div class="message text-wrap">
                      <div class="d-flex justify-content-between mt-1">
                        <div class="small text-body-secondary">Richard Johnson</div>
                        <div class="small text-body-secondary">5 minutes ago</div>
                      </div>
                      <div class="fw-semibold"><span class="text-danger">! </span>Project Update: Milestone Achieved</div>
                      <div class="small text-body-secondary">Kudos on hitting sales targets last quarter! Let's keep the momentum. New goals, new victories ahead...</div>
                    </div>
                  </div></a><a class="dropdown-item" href="#">
                  <div class="d-flex">
                    <div class="avatar flex-shrink-0 my-3 me-3"><img class="avatar-img" src="images/avatars/avatar-default.svg" alt="user@email.com"><span class="avatar-status bg-secondary"></span></div>
                    <div class="message text-wrap">
                      <div class="d-flex justify-content-between mt-1">
                        <div class="small text-body-secondary">Angela Rodriguez</div>
                        <div class="small text-body-secondary">1:52 PM</div>
                      </div>
                      <div class="fw-semibold">Social Media Campaign Launch</div>
                      <div class="small text-body-secondary">Exciting news! Our new social media campaign goes live tomorrow. Brace yourselves for engagement...</div>
                    </div>
                  </div></a><a class="dropdown-item" href="#">
                  <div class="d-flex">
                    <div class="avatar flex-shrink-0 my-3 me-3"><img class="avatar-img" src="images/avatars/avatar-default.svg" alt="user@email.com"><span class="avatar-status bg-success"></span></div>
                    <div class="message text-wrap">
                      <div class="d-flex justify-content-between mt-1">
                        <div class="small text-body-secondary">Jane Lewis</div>
                        <div class="small text-body-secondary">4:03 PM</div>
                      </div>
                      <div class="fw-semibold">Inventory Checkpoint</div>
                      <div class="small text-body-secondary">Team, it's time for our monthly inventory check. Accurate counts ensure smooth operations. Let's nail it...</div>
                    </div>
                  </div></a><a class="dropdown-item" href="#">
                  <div class="d-flex">
                    <div class="avatar flex-shrink-0 my-3 me-3"><img class="avatar-img" src="images/avatars/avatar-default.svg" alt="user@email.com"><span class="avatar-status bg-secondary"></span></div>
                    <div class="message text-wrap">
                      <div class="d-flex justify-content-between mt-1">
                        <div class="small text-body-secondary">Ryan Miller</div>
                        <div class="small text-body-secondary">3 days ago</div>
                      </div>
                      <div class="fw-semibold">Customer Feedback Results</div>
                      <div class="small text-body-secondary">Our latest customer feedback is in. Let's analyze and discuss improvements for an even better service...</div>
                    </div>
                  </div></a>
                <div class="p-2">     <a class="btn btn-outline-primary w-100" href="#" data-coreui-i18n="viewAllMessages">View all messages</a></div>
              </div>
            </li>
          </ul>
          <ul class="header-nav ms-auto ms-md-0">
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
                <div class="dropdown-header bg-body-tertiary text-body-secondary fw-semibold rounded-top mb-2" data-coreui-i18n="account">Account</div><a class="dropdown-item" href="#">
                  <svg class="icon me-2">
                    <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-bell"></use>
                  </svg><span data-coreui-i18n="updates">Updates</span><span class="badge badge-sm bg-info-gradient ms-2">42</span></a><a class="dropdown-item" href="#">
                  <svg class="icon me-2">
                    <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-envelope-open"></use>
                  </svg><span data-coreui-i18n="messages">Messages</span><span class="badge badge-sm badge-sm bg-success ms-2">42</span></a><a class="dropdown-item" href="#">
                  <svg class="icon me-2">
                    <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-task"></use>
                  </svg><span data-coreui-i18n="tasks">Tasks</span><span class="badge badge-sm bg-danger-gradient ms-2">42</span></a><a class="dropdown-item" href="#">
                  <svg class="icon me-2">
                    <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-comment-square"></use>
                  </svg><span data-coreui-i18n="comments">Comments</span><span class="badge badge-sm bg-warning-gradient ms-2">42</span></a>
                <div class="dropdown-header bg-body-tertiary text-body-secondary fw-semibold my-2" data-coreui-i18n="settings">Settings</div><a class="dropdown-item" href="{{ route('profile.show') }}">
                  <svg class="icon me-2">
                    <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-user"></use>
                  </svg><span data-coreui-i18n="profile">Profile</span></a><a class="dropdown-item" href="{{ route('profile.show') }}"">
                  <svg class="icon me-2">
                    <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-settings"></use>
                  </svg><span data-coreui-i18n="settings">Settings</span></a><a class="dropdown-item" href="#">
                  <svg class="icon me-2">
                    <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-credit-card"></use>
                  </svg><span data-coreui-i18n="payments">Payments</span><span class="badge badge-sm bg-secondary-gradient text-dark ms-2">42</span></a>
                  <a class="dropdown-item" href="#">
                  <svg class="icon me-2">
                    <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-file"></use>
                  </svg><span data-coreui-i18n="projects">Projects</span><span class="badge badge-sm bg-primary-gradient ms-2">42</span></a>
                <div class="dropdown-divider"></div><a class="dropdown-item" href="#">
                  <svg class="icon me-2">
                    <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-lock-locked"></use>
                  </svg><span data-coreui-i18n="lockAccount">Lock Account</span></a><a class="dropdown-item" href="#">
                  <svg class="icon me-2">
                    <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-account-logout"></use>
                  </svg><form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" id="logout-button" class="btn btn-link" style="color: var(--cui-dropdown-link-color);padding: 0px;    margin: 0px;    position: relative;    left: -5px;    text-decoration: none;"><span data-coreui-i18n="logout">Logout</span></button>
                  </form></a>
                  <div class="dropdown-header bg-body-tertiary text-body-secondary fw-semibold my-2" data-coreui-i18n="superadmin">Super Admin</div>
                  <a class="dropdown-item"  href="{{ route('appsetting.appsetup.index') }}">
                    <svg class="icon me-2">
                      <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-people"></use>
                    </svg><span data-coreui-i18n="appsetup">App Setup</span></a>
                  <a class="dropdown-item"  href="{{ route('appsetting.users.index') }}">
                    <svg class="icon me-2">
                      <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-people"></use>
                    </svg><span data-coreui-i18n="usermanagement">User Management</span></a>
                    <a class="dropdown-item"  href="{{ route('appsetting.appfeature.index') }}">
                    <svg class="icon me-2">
                      <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-people"></use>
                    </svg><span data-coreui-i18n="feature">Feature</span></a>
                    <a class="dropdown-item"  href="{{ route('appsetting.users.index') }}">
                    <svg class="icon me-2">
                      <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-people"></use>
                    </svg><span data-coreui-i18n="usermanagement">User Management</span></a>
                    <a class="dropdown-item"  href="{{ route('appsetting.menu.index') }}">
                    <svg class="icon me-2">
                      <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-people"></use>
                    </svg><span data-coreui-i18n="menu">Menu BUilder</span></a>
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