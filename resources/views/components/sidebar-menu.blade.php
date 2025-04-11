
<!-- Sidebar Menu {{ Cache::has('sidebar_menu_items') ? 'cached' : 'fresh' }} -->
@foreach($menuItems as $item)
        @if($item->children->count() > 0)
            <li class="nav-group" data-source="{{ Cache::has('sidebar_menu_items') ? 'cache' : 'database' }}">
                <a class="nav-link nav-group-toggle" href="#">
                    <svg class="nav-icon">
                        <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#{{ $item->icon }}"></use>
                    </svg>
                    <span>{{ $item->title }}</span>
                </a>
                <ul class="nav-group-items compact">
                    @foreach($item->children as $child)
                        <li class="nav-item">
                            <a class="nav-link ms-3" href="{{ $child->url }}">
                                <svg class="nav-icon">
                                    <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#{{ $child->icon }}"></use>
                                </svg>
                                <span>{{ $child->title }}</span>
                                @if($child->badge)
                                    <span class="badge {{ $child->badge_class }} ms-auto">{{ $child->badge }}</span>
                                @endif
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
        @else
            <li class="nav-item">
                <a class="nav-link" href="{{ $item->url }}">
                    <svg class="nav-icon">
                        <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#{{ $item->icon }}"></use>
                    </svg>
                    <span>{{ $item->title }}</span>
                    @if($item->badge)
                        <span class="badge {{ $item->badge_class }} ms-auto">{{ $item->badge }}</span>
                    @endif
                </a>
            </li>
        @endif
    @endforeach