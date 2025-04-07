<x-app-layout>
    <!-- ... header slot ... -->

    <div class="body flex-grow-1 px-3">
        <div class="container-lg">
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
        document.addEventListener('DOMContentLoaded', function() {
            // Add these new variables at the top of your script
            const iconPickerModal = document.getElementById('iconPickerModal');
            const modal = new coreui.Modal(iconPickerModal);

            // Add this new event handler for icon selection
            document.querySelectorAll('.icon-demo').forEach(demo => {
                demo.addEventListener('click', function() {
                    const iconClass = this.dataset.icon;
                    const activeInput = document.querySelector('.icon-picker-active');
                    if (activeInput) {
                        activeInput.value = iconClass;
                        activeInput.classList.remove('icon-picker-active');
                    }
                    modal.hide();
                });
            });

            // Update the icon picker button click handler
            document.querySelectorAll('.icon-picker-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const input = this.closest('.input-group').querySelector('input[name="icon"]');
                    // Remove active class from any previously active input
                    document.querySelector('.icon-picker-active')?.classList.remove('icon-picker-active');
                    // Add active class to current input
                    input.classList.add('icon-picker-active');
                });
            });
            // Initialize Sortable
            const menuStructure = document.getElementById('menuStructure');
            const sortable = new Sortable(menuStructure, {
                group: 'nested',
                animation: 150,
                fallbackOnBody: true,
                swapThreshold: 0.65,
                handle: '.handle',
                dragClass: 'sortable-drag',
                ghostClass: 'sortable-ghost'
                // Removed onEnd callback
            });

            // Initialize nested sortables
            document.querySelectorAll('.menu-item-children').forEach(el => {
                new Sortable(el, {
                    group: 'nested',
                    animation: 150,
                    fallbackOnBody: true,
                    swapThreshold: 0.65,
                    handle: '.handle',
                    dragClass: 'sortable-drag',
                    ghostClass: 'sortable-ghost'
                });
            });

            // Save Menu Structure
            function saveMenuStructure() {
                const structure = buildMenuStructure(menuStructure);
                
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
                        // Show success toast or alert
                        alert('Menu structure saved successfully');
                        location.reload(); // Reload to get fresh IDs
                    } else {
                        alert('Failed to save menu structure');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error saving menu structure');
                });
            }

            // Build Menu Structure
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

            // Update addMenuItem to include necessary data attributes
            // Add Feature to Menu
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
                const customLinkData = {
                    item_type: 'free_form',
                    title: formData.get('title'),
                    icon: formData.get('icon') || 'cil-link', // Default icon if none selected
                    path: formData.get('path'),
                    app_feature_id: null
                };

                addMenuItem(customLinkData);
                this.reset();
            });

            // Add Menu Item Function
            function addMenuItem(data) {
                console.log('Adding menu item:', data); // Debug log
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
                            <button type="button" class="btn btn-sm btn-danger delete-item">
                                <i class="cil-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="menu-item-children ms-4 mt-2"></div>
                `;
            
                // Add delete functionality
                const deleteBtn = menuItem.querySelector('.delete-item');
                deleteBtn.addEventListener('click', function() {
                    menuItem.remove();
                });
            
                menuStructure.appendChild(menuItem);
                
                // Initialize sortable for the new item's children container
                new Sortable(menuItem.querySelector('.menu-item-children'), {
                    group: 'nested',
                    animation: 150,
                    fallbackOnBody: true,
                    swapThreshold: 0.65,
                    handle: '.handle',
                    dragClass: 'sortable-drag',
                    ghostClass: 'sortable-ghost',
                });
            }

            // Add delete functionality to existing menu items
            document.querySelectorAll('.delete-item').forEach(btn => {
                btn.addEventListener('click', function() {
                    this.closest('.menu-item').remove();
                });
            });

            // Save Menu Structure Button
            document.getElementById('saveMenuStructure').addEventListener('click', saveMenuStructure);
        });
    </script>
    @endpush
</x-app-layout>