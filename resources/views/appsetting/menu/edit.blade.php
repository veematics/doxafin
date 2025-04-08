<x-app-layout>
    <!-- ... header slot ... -->

    <div class="body flex-grow-1 px-3">
        <div class="container-lg">
        <div class="row align-items-center mb-4">
    <div class="col">
        <div class="fs-2 fw-semibold" data-coreui-i18n="dashboard">Edit Menu</div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" data-coreui-i18n="home">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('appsetting.menu.index') }}" data-coreui-i18n="home">Menu</a></li>
                <li class="breadcrumb-item active"><span data-coreui-i18n="dashboard">Edit Menu</span></li>
            </ol>
        </nav>
    </div>
    <div class="col-auto">
        
    </div>
</div>
            <div class="row">
                <!-- Left Column - Add Menu Items -->
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-header">
                            <strong>Add Menu Items</strong>
                        </div>
                        <div class="card-body">
                            <!-- Features Tab -->
                            <div class="accordion" id="menuItemsAccordion">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button" type="button" data-coreui-toggle="collapse" data-coreui-target="#featuresPanel">
                                            App Features
                                        </button>
                                    </h2>
                                    <div id="featuresPanel" class="accordion-collapse collapse show">
                                        <div class="accordion-body">
                                            <div class="list-group">
                                                @foreach($features as $feature)
                                                <div class="list-group-item">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <i class="{{ $feature->featureIcon }}"></i>
                                                            {{ $feature->featureName }}
                                                        </div>
                                                        <button type="button" 
                                                                class="btn btn-sm btn-primary add-feature"
                                                                data-feature-id="{{ $feature->featureID }}"
                                                                data-feature-name="{{ $feature->featureName }}"
                                                                data-feature-icon="{{ $feature->featureIcon }}"
                                                                data-feature-path="{{ $feature->featurePath }}">
                                                            Add
                                                        </button>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Custom Link Tab -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-coreui-toggle="collapse" data-coreui-target="#customLinkPanel">
                                            Custom Link
                                        </button>
                                    </h2>
                                    <div id="customLinkPanel" class="accordion-collapse collapse">
                                        <div class="accordion-body">
                                            <form id="customLinkForm">
                                                <div class="mb-3">
                                                    <label class="form-label">Title</label>
                                                    <input type="text" class="form-control" name="title" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">URL</label>
                                                    <input type="text" class="form-control" name="path" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Bind Permission</label>
                                                    <select class="form-select" name="app_feature_id">
                                                        <option value="">No Feature</option>
                                                        @foreach($features as $feature)
                                                            <option value="{{ $feature->featureID }}">{{ $feature->featureName }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Icon</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="icon" readonly>
                                                        <!-- Update the icon picker button to use data-coreui-toggle -->
                                                        <button type="button" 
                                                                class="btn btn-outline-secondary icon-picker-btn" 
                                                                data-coreui-toggle="modal" 
                                                                data-coreui-target="#iconPickerModal">
                                                            Select Icon
                                                        </button>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Add to Menu</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Menu Structure -->
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <strong>Menu Structure: {{ $menu->name }}</strong>
                            <button type="button" class="btn btn-primary" id="saveMenuStructure">
                                <i class="cil-save"></i> Save Menu
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="menuStructure" class="menu-items-container">
                                @foreach($menuItems as $menuItem)
                                    @include('appsetting.menu._menu_item', ['menuItem' => $menuItem])
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('appsetting.menu._icon_picker_modal')

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        // Global functions
        function buildMenuStructure(container) {
            const items = [];
            container.querySelectorAll(':scope > .menu-item').forEach((el, index) => {
                const titleEl = el.querySelector('.menu-item-title');
                const item = {
                    id: el.dataset.id,
                    order: index,
                    title: titleEl.textContent.trim(),
                    icon: titleEl.querySelector('i').className,
                    item_type: el.dataset.type || 'free_form',
                    app_feature_id: el.dataset.featureId || null,
                    path: el.dataset.path || null,
                    menu_id: {{ $menu->id }},
                    children: []
                };

                const childrenContainer = el.querySelector('.menu-item-children');
                if (childrenContainer) {
                    item.children = buildMenuStructure(childrenContainer);
                }

                items.push(item);
            });
            return items;
        }

        function saveMenuStructure() {
            const structure = buildMenuStructure(document.getElementById('menuStructure'));
            
            fetch(`{{ route('appsetting.menu.structure', $menu) }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ structure: structure })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.toast.show('Menu structure saved successfully', 'success');
                    location.reload();
                } else {
                    window.toast.show(data.error || 'Failed to save menu structure', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                window.toast.show('Error saving menu structure', 'error');
            });
        }

        // DOM Ready event listener
        document.addEventListener('DOMContentLoaded', function() {
            const menuStructure = document.getElementById('menuStructure');
            const iconPickerModal = document.getElementById('iconPickerModal');
            const modal = new coreui.Modal(iconPickerModal);

            // Initialize icon picker
            document.querySelectorAll('.icon-demo').forEach(demo => {
                demo.addEventListener('click', function() {
                    const iconClass = this.dataset.icon;
                    const activeInput = document.querySelector('.icon-picker-active');
                    if (activeInput) {
                        activeInput.value = iconClass;
                        activeInput.classList.remove('icon-picker-active');
                    }
                    modal.hide(); // Close the modal after icon selection
                });
            });

            // Initialize main sortable container
            new Sortable(menuStructure, {
                group: 'nested',
                animation: 150,
                fallbackOnBody: true,
                swapThreshold: 0.65,
                handle: '.handle',
                dragClass: 'sortable-drag',
                ghostClass: 'sortable-ghost',
            });

            // Initialize sortable for all existing menu item children containers
            document.querySelectorAll('.menu-item-children').forEach(el => {
                new Sortable(el, {
                    group: 'nested',
                    animation: 150,
                    fallbackOnBody: true,
                    swapThreshold: 0.65,
                    handle: '.handle',
                    dragClass: 'sortable-drag',
                    ghostClass: 'sortable-ghost',
                });
            });

            // Modify addMenuItem function to ensure new items are also draggable
            function addMenuItem(data) {
                const menuItem = document.createElement('div');
                menuItem.className = 'menu-item mb-2';
                menuItem.dataset.id = 'new_' + Date.now();
                menuItem.dataset.type = data.item_type;
                menuItem.dataset.featureId = data.app_feature_id || '';
                menuItem.dataset.path = data.path || '';
                
                // Ensure icon and title are properly escaped for HTML
                const icon = data.icon ? data.icon.replace(/"/g, '&quot;') : '';
                const title = data.title ? data.title.replace(/</g, '&lt;').replace(/>/g, '&gt;') : '';
                
                menuItem.innerHTML = `
                    <div class="menu-item-header p-2 border rounded bg-light d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="handle me-2"><i class="cil-menu"></i></div>
                            <div class="menu-item-title">
                                <i class="${icon}"></i>
                                ${title}
                            </div>
                        </div>
                        <div class="menu-item-actions">
                            <button type="button" class="btn btn-sm btn-primary edit-menu-item me-1"
                                    data-title="${title}"
                                    data-path="${data.path}"
                                    data-icon="${icon}"
                                    data-feature-id="${data.app_feature_id}"
                                    data-item-id="${menuItem.dataset.id}">
                                <i class="cil-pencil"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger delete-menu-item">
                                <i class="cil-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="menu-item-children ms-4 mt-2"></div>
                `;
            
                // Update delete button event listener
                const deleteBtn = menuItem.querySelector('.delete-menu-item');
                deleteBtn.addEventListener('click', function() {
                    menuItem.remove();
                });
            
                // Initialize sortable for the new item's children container
                const childrenContainer = menuItem.querySelector('.menu-item-children');
                new Sortable(childrenContainer, {
                    group: 'nested',
                    animation: 150,
                    fallbackOnBody: true,
                    swapThreshold: 0.65,
                    handle: '.handle',
                    dragClass: 'sortable-drag',
                    ghostClass: 'sortable-ghost',
                });

                menuStructure.appendChild(menuItem);
            }

            // Event listeners
            document.querySelectorAll('.add-feature').forEach(button => {
                button.addEventListener('click', function() {
                    const featureData = {
                        item_type: 'feature',
                        title: this.dataset.featureName,
                        icon: this.dataset.featureIcon,
                        app_feature_id: this.dataset.featureId,
                        path: this.dataset.featurePath
                    };

                    addMenuItem(featureData);
                });
            });

            // Add Custom Link
            document.getElementById('customLinkForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const isEditMode = this.dataset.editMode === 'true';
                
                const itemData = {
                    item_type: 'free_form',
                    title: formData.get('title'),
                    icon: formData.get('icon') || 'cil-link',
                    path: formData.get('path'),
                    app_feature_id: formData.get('app_feature_id') || null
                };

                if (isEditMode && this.dataset.editItemId) {
                    // Update existing menu item
                    const itemId = this.dataset.editItemId;
                    const menuItem = document.querySelector(`.menu-item[data-id="${itemId}"]`);
                    if (menuItem) {
                        // Update the menu item's data attributes
                        menuItem.dataset.path = itemData.path;
                        menuItem.dataset.featureId = itemData.app_feature_id || '';
                        
                        // Update the title and icon
                        const titleEl = menuItem.querySelector('.menu-item-title');
                        titleEl.innerHTML = `<i class="${itemData.icon}"></i> ${itemData.title}`;
                        
                        // Update the edit button's data attributes
                        const editBtn = menuItem.querySelector('.edit-menu-item');
                        editBtn.dataset.title = itemData.title;
                        editBtn.dataset.path = itemData.path;
                        editBtn.dataset.icon = itemData.icon;
                        editBtn.dataset.featureId = itemData.app_feature_id || '';
                        
                        // Reset form to add mode
                        this.dataset.editMode = 'false';
                        this.dataset.editItemId = '';
                        this.querySelector('button[type="submit"]').textContent = 'Add to Menu';
                        
                        // Close the Custom Link panel after edit
                        const customLinkPanel = document.querySelector('#customLinkPanel');
                        const accordion = new coreui.Collapse(customLinkPanel);
                        accordion.hide();

                        window.toast.show('Menu item updated successfully', 'success');
                    } else {
                        window.toast.show('Menu item not found', 'error');
                    }
                } else {
                    // Add new menu item only if not in edit mode
                    addMenuItem(itemData);
                    window.toast.show('Menu item added successfully', 'success');
                }

                this.reset();
            });

            // Update existing delete button handler
            document.querySelectorAll('.delete-menu-item').forEach(btn => {
                btn.addEventListener('click', function() {
                    this.closest('.menu-item').remove();
                });
            });

            // Save Menu Structure Button
            document.getElementById('saveMenuStructure').addEventListener('click', saveMenuStructure);
        });

        // Outside DOMContentLoaded - Global event listeners
        document.addEventListener('click', function(e) {
            if (e.target.closest('.edit-menu-item')) {
                const button = e.target.closest('.edit-menu-item');
                const menuItem = button.closest('.menu-item');
                
                // Open the Custom Link panel (ensure it's open, not toggle)
                const customLinkPanel = document.querySelector('#customLinkPanel');
                const accordion = new coreui.Collapse(customLinkPanel, {
                    toggle: false
                });
                accordion.show();
                
                // Ensure the accordion button shows as expanded
                const accordionButton = document.querySelector('button[data-coreui-target="#customLinkPanel"]');
                accordionButton.classList.remove('collapsed');
                
                // Fill the custom link form with the item's data
                document.querySelector('input[name="title"]').value = button.dataset.title;
                document.querySelector('input[name="path"]').value = button.dataset.path;
                document.querySelector('input[name="icon"]').value = button.dataset.icon;
                document.querySelector('select[name="app_feature_id"]').value = menuItem.dataset.featureId;
                
                // Add edit mode flag and item ID to the form
                const form = document.getElementById('customLinkForm');
                form.dataset.editMode = 'true';
                form.dataset.editItemId = button.dataset.itemId;
                
                // Change submit button text
                const submitBtn = form.querySelector('button[type="submit"]');
                submitBtn.textContent = 'Update Menu Item';
                
                // Scroll to the form
                form.scrollIntoView({ behavior: 'smooth' });
                
                // Show feedback
                window.toast.show('Edit the menu item details and click "Update Menu Item" to save changes', 'info');
            }
        });

        
    </script>
    @endpush

   
</x-app-layout>