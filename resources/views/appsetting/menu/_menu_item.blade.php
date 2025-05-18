<div class="menu-item mb-2" data-id="{{ $menuItem->id }}" data-type="{{ $menuItem->item_type }}" data-feature-id="{{ $menuItem->app_feature_id }}" data-path="{{ $menuItem->path }}">
    <div class="menu-item-header p-2 border rounded bg-light d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <span class="handle me-2 cursor-move"><svg class="icon"><use xlink:href="{{ asset('static/coreui-pro/dist/css/coreui-icons-sprite.svg') }}#cil-menu"></use></svg></span>
            <span class="menu-item-title"><svg class="icon"><use xlink:href="{{ asset('static/coreui-pro/dist/css/coreui-icons-sprite.svg') }}#{{ str_replace('cil-', '', $menuItem->icon) }}"></use></svg> {{ $menuItem->title }}</span>
        </div>
        <div class="menu-item-actions">
            
            <button type="button" class="btn btn-sm btn-primary edit-menu-item me-1"
                    data-title="{{ $menuItem->title }}"
                    data-path="{{ $menuItem->path }}"
                    data-icon="{{ $menuItem->icon }}"
                    data-item-id="{{ $menuItem->id }}">
                <svg class="icon"><use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-pencil"></use></svg>
                
            </button>
            <button type="button" class="btn btn-sm btn-danger delete-menu-item">
                <svg class="icon"><use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-trash"></use></svg>
            </button>
        </div>
    </div>

    <div class="menu-item-children ps-4 mt-2">
        @foreach($menuItem->children as $child)
            @include('appsetting.menu._menu_item', ['menuItem' => $child])
        @endforeach
     
    </div>
</div>