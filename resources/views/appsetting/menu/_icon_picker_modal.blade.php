<div class="modal fade" id="iconPickerModal" tabindex="-1" role="dialog" aria-labelledby="iconPickerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
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
                                <i class="{{ $icon }} fs-2"></i>
                                <div class="small text-muted text-truncate mt-1">{{ $icon }}</div>
                            </div>
                        </div>
                    @endforeach
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
                bootstrap.Modal.getInstance(document.getElementById('iconPickerModal')).hide();
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