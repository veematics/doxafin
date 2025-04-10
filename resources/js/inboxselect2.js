import 'select2';

document.addEventListener('DOMContentLoaded', function() {
    $('#sendMessageModal').on('shown.coreui.modal', function() {
        $('.select2-recipient').select2({
            dropdownParent: $('#sendMessageModal'),
            width: '100%',
            placeholder: 'Search recipient...',
            allowClear: true,
            minimumInputLength: 1
        });
    });
    
    $('#sendMessageModal').on('hidden.coreui.modal', function() {
        $('.select2-recipient').select2('destroy');
    });
});