<div class="dropdown-header bg-body-tertiary text-body-secondary fw-semibold my-2" data-coreui-i18n="superadmin">Super Admin</div>
@foreach($menuItems as $menuItem)
    <a class="dropdown-item" href="{{ url($menuItem->path) }}">
        <svg class="icon me-2">
            <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#{{ $menuItem->icon }}"></use>
        </svg>
        <span data-coreui-i18n="{{ Str::lower(str_replace(' ', '', $menuItem->title)) }}">{{ $menuItem->title }}</span>
    </a>
@endforeach