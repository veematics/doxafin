<div class="dropdown-header bg-body-tertiary text-body-secondary fw-semibold my-2" data-coreui-i18n="superadmin">Your Account</div>

@foreach($menuItems as $menuItem)
    <a class="dropdown-item" href="{{ $menuItem->path }}">
        <svg class="icon me-2">
            <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#{{ $menuItem->icon }}"></use>
        </svg>
        <span data-coreui-i18n="{{ Str::lower(str_replace(' ', '', $menuItem->title)) }}">{{ $menuItem->title }}</span>
    </a>
@endforeach
<a class="dropdown-item" href="#">
                  <svg class="icon me-2">
                    <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-account-logout"></use>
                  </svg><form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" id="logout-button" class="btn btn-link" style="color: var(--cui-dropdown-link-color);padding: 0px;    margin: 0px;    position: relative;    left: -5px;    text-decoration: none;"><span data-coreui-i18n="logout">Logout</span></button>
                  </form></a>