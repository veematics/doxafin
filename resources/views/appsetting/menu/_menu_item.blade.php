<div class="menu-item mb-2" data-id="{{ $menuItem->id }}" data-type="{{ $menuItem->item_type }}" data-feature-id="{{ $menuItem->app_feature_id }}" data-path="{{ $menuItem->path }}">
    <div class="menu-item-header p-2 border rounded bg-light d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <span class="handle me-2 cursor-move"><i class="cil-menu"></i></span>
            <span class="menu-item-title"><i class="{{ $menuItem->icon }}"></i> {{ $menuItem->title }}</span>
        </div>
        <div class="menu-item-actions">
            <button type="button" class="btn btn-sm btn-primary edit-menu-item me-1"
                    data-title="{{ $menuItem->title }}"
                    data-path="{{ $menuItem->path }}"
                    data-icon="{{ $menuItem->icon }}"
                    data-item-id="{{ $menuItem->id }}">
                <i class="cil-pencil"></i>
            </button>
            <button type="button" class="btn btn-sm btn-danger delete-menu-item">
                <i class="cil-trash"></i>
            </button>
        </div>
    </div>
    <div class="menu-item-children ps-4 mt-2">
        @foreach($menuItem->children as $child)
            @include('appsetting.menu._menu_item', ['menuItem' => $child])
        @endforeach
    </div>
</div>