<div class="menu-item" data-id="{{ $menuItem->id }}">
    <div class="menu-item-header d-flex align-items-center p-2 border rounded mb-2">
        <i class="cil-menu handle me-2"></i>
        <div class="me-auto">
            <span class="menu-item-title">{{ $menuItem->title }}</span>
            <small class="text-muted">{{ $menuItem->item_type }}</small>
        </div>
        <div class="btn-group">
            <button type="button" class="btn btn-sm btn-outline-primary toggle-item-form">
                <i class="cil-pencil"></i>
            </button>
            <button type="button" class="btn btn-sm btn-outline-danger delete-menu-item">
                <i class="cil-trash"></i>
            </button>
        </div>
    </div>

    <div class="menu-item-form p-3 border rounded mb-3" style="display: none;">
        <form class="update-menu-item-form">
            <input type="hidden" name="id" value="{{ $menuItem->id }}">
            
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" class="form-control" name="title" value="{{ $menuItem->title }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Icon</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="{{ $menuItem->icon }}"></i>
                    </span>
                    <input type="text" class="form-control" name="icon" value="{{ $menuItem->icon }}" readonly>
                    <button type="button" class="btn btn-outline-secondary icon-picker-btn">
                        Select Icon
                    </button>
                </div>
            </div>

            @if($menuItem->item_type === 'free_form')
                <div class="mb-3">
                    <label class="form-label">URL</label>
                    <input type="text" class="form-control" name="path" value="{{ $menuItem->path }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Target</label>
                    <select class="form-select" name="target">
                        <option value="_self" {{ $menuItem->target === '_self' ? 'selected' : '' }}>Same Window</option>
                        <option value="_blank" {{ $menuItem->target === '_blank' ? 'selected' : '' }}>New Window</option>
                    </select>
                </div>
            @endif

            <div class="d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-secondary cancel-edit">Cancel</button>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>

    @if($menuItem->children->isNotEmpty())
        <div class="menu-item-children ps-4">
            @foreach($menuItem->children as $child)
                @include('admin.menus._menu_item', ['menuItem' => $child])
            @endforeach
        </div>
    @endif
</div>