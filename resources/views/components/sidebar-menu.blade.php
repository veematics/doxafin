
@php
    function icon($name) {
        return '<svg class="nav-icon"><use xlink:href="' . url('assets/icons/free/free.svg') . '#' . $name . '"></use></svg>';
    }
@endphp


    @foreach($menuItems as $item)
  
        <li class="nav-item {{ count($item['children']) > 0 ? 'nav-group' : '' }}">
            <a class="nav-link {{ count($item['children']) > 0 ? 'nav-group-toggle' : '' }}" href="{{ $item['path'] ?? '#' }}">
                {!! icon($item['icon']) !!}
                {{ $item['title'] }}
            </a>
            @if(count($item['children']) > 0)
                <ul class="nav-group-items">
                    @foreach($item['children'] as $child)
                        <li class="nav-item" style="margin-left: 10px;">
                            <a class="nav-link" href="{{ $child->path }}">
                                {!! icon($child->icon) !!}
                                {{ $child->title }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </li>
    @endforeach

