<div class="modal fade" id="iconPickerModal" tabindex="-1" aria-labelledby="iconPickerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="iconPickerModalLabel">Select Icon</h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <input type="text" class="form-control" id="iconSearch" placeholder="Search icons...">
                </div>
                <div class="row g-3" id="iconGrid">
                    @foreach(config('coreui.icons') as $icon)
                        <div class="col-6 col-sm-4 col-md-3 col-xl-2 icon-item">
                            <div class="p-3 text-center border rounded icon-demo" data-icon="{{ $icon }}">
                                <svg class="icon fs-2">
                                <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#{{ $icon }}"></use>
                            </svg>
                                <div class="small text-muted text-truncate mt-1">{{ $icon }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
                        </div>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let activeIconInput = null;
        let activeIconPreview = null;

        // Icon Search
        document.getElementById('iconSearch').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            document.querySelectorAll('.icon-item').forEach(item => {
                const iconName = item.querySelector('.icon-demo').dataset.icon.toLowerCase();
                item.style.display = iconName.includes(searchTerm) ? '' : 'none';
            });
        });

        // Icon Selection
        document.querySelectorAll('.icon-demo').forEach(demo => {
            demo.addEventListener('click', function() {
                const iconClass = this.dataset.icon;
                if (activeIconInput) {
                    activeIconInput.value = iconClass;
                    if (activeIconPreview) {
                        activeIconPreview.className = iconClass;
                    }
                }
                const modal = new coreui.Modal(document.getElementById('iconPickerModal'));
                    modal.hide();
            });
        });

        // Open Icon Picker
        document.querySelectorAll('.icon-picker-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                activeIconInput = this.closest('.input-group').querySelector('input[type="text"]');
                activeIconPreview = this.closest('.menu-item')?.querySelector('.menu-item-icon');
            });
        });
    });
</script>
@endpush
                            <div class="icon-name small">action-redo</div>
                        </div>
                    </div>
                    <div class="col-6 col-sm-4 col-md-3 col-xl-2 icon-item">
                        <div class="p-2 text-center border rounded cursor-pointer icon-demo" data-icon="cil-action-undo">
                            <i class="cil-action-undo fs-2 mb-2"></i>
                            <div class="icon-name small">action-undo</div>
                        </div>
                    </div>
                    <div class="col-6 col-sm-4 col-md-3 col-xl-2 icon-item">
                        <div class="p-2 text-center border rounded cursor-pointer icon-demo" data-icon="cil-address-book">
                            <i class="cil-address-book fs-2 mb-2"></i>
                            <div class="icon-name small">address-book</div>
                        </div>
                    </div>
                    <div class="col-6 col-sm-4 col-md-3 col-xl-2 icon-item">
                        <div class="p-2 text-center border rounded cursor-pointer icon-demo" data-icon="cil-list">
                            <i class="cil-list fs-2 mb-2"></i>
                            <div class="icon-name small">list</div>
                        </div>
                    </div>
                    <div class="col-6 col-sm-4 col-md-3 col-xl-2 icon-item">
                        <div class="p-2 text-center border rounded cursor-pointer icon-demo" data-icon="cil-menu">
                            <i class="cil-menu fs-2 mb-2"></i>
                            <div class="icon-name small">menu</div>
                        </div>
                    </div>
                    <div class="col-6 col-sm-4 col-md-3 col-xl-2 icon-item">
                        <div class="p-2 text-center border rounded cursor-pointer icon-demo" data-icon="cil-options">
                            <i class="cil-options fs-2 mb-2"></i>
                            <div class="icon-name small">options</div>
                        </div>
                    </div>
                    <div class="col-6 col-sm-4 col-md-3 col-xl-2 icon-item">
                        <div class="p-2 text-center border rounded cursor-pointer icon-demo" data-icon="cil-pencil">
                            <i class="cil-pencil fs-2 mb-2"></i>
                            <div class="icon-name small">pencil</div>
                        </div>
                    </div>
                    <div class="col-6 col-sm-4 col-md-3 col-xl-2 icon-item">
                        <div class="p-2 text-center border rounded cursor-pointer icon-demo" data-icon="cil-people">
                            <i class="cil-people fs-2 mb-2"></i>
                            <div class="icon-name small">people</div>
                        </div>
                    </div>
                    <div class="col-6 col-sm-4 col-md-3 col-xl-2 icon-item">
                        <div class="p-2 text-center border rounded cursor-pointer icon-demo" data-icon="cil-plus">
                            <i class="cil-plus fs-2 mb-2"></i>
                            <div class="icon-name small">plus</div>
                        </div>
                    </div>
                    <div class="col-6 col-sm-4 col-md-3 col-xl-2 icon-item">
                        <div class="p-2 text-center border rounded cursor-pointer icon-demo" data-icon="cil-save">
                            <i class="cil-save fs-2 mb-2"></i>
                            <div class="icon-name small">save</div>
                        </div>
                    </div>
                    <div class="col-6 col-sm-4 col-md-3 col-xl-2 icon-item">
                        <div class="p-2 text-center border rounded cursor-pointer icon-demo" data-icon="cil-settings">
                            <i class="cil-settings fs-2 mb-2"></i>
                            <div class="icon-name small">settings</div>
                        </div>
                    </div>
                    <div class="col-6 col-sm-4 col-md-3 col-xl-2 icon-item">
                        <div class="p-2 text-center border rounded cursor-pointer icon-demo" data-icon="cil-trash">
                            <i class="cil-trash fs-2 mb-2"></i>
                            <div class="icon-name small">trash</div>
                        </div>
                    </div>
                    <div class="col-6 col-sm-4 col-md-3 col-xl-2 icon-item">
                        <div class="p-2 text-center border rounded cursor-pointer icon-demo" data-icon="cil-user">
                            <i class="cil-user fs-2 mb-2"></i>
                            <div class="icon-name small">user</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.cursor-pointer {
    cursor: pointer;
}
.icon-demo:hover {
    background-color: #eee;
}
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const iconSearch = document.getElementById('iconSearch');
        const iconGrid = document.getElementById('iconGrid');
        const iconDemos = iconGrid.querySelectorAll('.icon-demo');

        // Icon picker button click handler
        document.querySelectorAll('.icon-picker-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const input = this.closest('.input-group').querySelector('input[type="text"]');
                input.classList.add('icon-picker-active');
            });
        });

        // Search functionality
        iconSearch.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            iconDemos.forEach(demo => {
                const iconName = demo.querySelector('.icon-name').textContent.toLowerCase();
                demo.closest('.icon-item').style.display = iconName.includes(searchTerm) ? '' : 'none';
            });
        });

        // Icon selection
        iconDemos.forEach(demo => {
            demo.addEventListener('click', function() {
                const iconClass = this.dataset.icon;
                const activeInput = document.querySelector('.icon-picker-active');
                if (activeInput) {
                    activeInput.value = iconClass;
                    activeInput.classList.remove('icon-picker-active');
                    const modal = new coreui.Modal(document.getElementById('iconPickerModal'));
                    modal.hide();
                }
            });
        });
    });
</script>
@endpush