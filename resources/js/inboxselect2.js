import jQuery from 'jquery';
// Let's use CDN instead since node_modules isn't accessible via HTTP

window.$ = jQuery;
window.jQuery = jQuery;

document.addEventListener('DOMContentLoaded', function() {
    // Direct CDN loading for Select2
    function loadScript() {
        // Use CDN directly since node_modules isn't web-accessible
        const cdnScript = document.createElement('script');
        cdnScript.src = 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js';
        cdnScript.onload = function() {
            console.log('Select2 script loaded from CDN');
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css';
            document.head.appendChild(link);
            setTimeout(initSelect2, 300);
        };
        document.head.appendChild(cdnScript);
    }
    
    // Load the script
    loadScript();
    
    // Fix duplicate function issue - remove one of these
    function initSelect2() {
        try {
            // Verify Select2 is available
            if (typeof $.fn.select2 === 'function') {
                // Check if already initialized and destroy if needed
                try {
                    if ($('.select2-recipient').data('select2')) {
                        $('.select2-recipient').select2('destroy');
                    }
                } catch (e) {
                    console.log('Error destroying previous Select2 instance', e);
                }
                
                $('.select2-recipient').select2({
                    dropdownParent: $('#sendMessageModal'),
                    width: '100%',
                    placeholder: 'Search recipient...',
                    allowClear: true,
                    minimumInputLength: 1,
                    tags: false
                });
                console.log('Select2 initialized successfully');
            } else {
                console.error('Select2 function not available');
            }
        } catch (e) {
            console.error('Error initializing Select2:', e);
        }
    }
    
    // Also initialize on modal show
    $('#sendMessageModal').on('shown.coreui.modal', function() {
        if (typeof $.fn.select2 === 'function') {
            initSelect2();
        } else {
            console.log('Select2 not available on modal show, trying to load again');
            loadScript();
        }
    });
    
    $('#sendMessageModal').on('hidden.coreui.modal', function() {
        try {
            if (typeof $.fn.select2 === 'function' && $('.select2-recipient').data('select2')) {
                $('.select2-recipient').select2('destroy');
            }
        } catch (e) {
            console.log('Select2 could not be destroyed', e);
        }
    });
    
    // Function to initialize Select2
    function initSelect2() {
        try {
            // Check if already initialized and destroy if needed
            if (typeof $('.select2-recipient').select2 === 'function') {
                try {
                    if ($('.select2-recipient').data('select2')) {
                        $('.select2-recipient').select2('destroy');
                    }
                } catch (e) {
                    console.log('Error destroying previous Select2 instance', e);
                }
                
                $('.select2-recipient').select2({
                    dropdownParent: $('#sendMessageModal'),
                    width: '100%',
                    placeholder: 'Search recipient...',
                    allowClear: true,
                    minimumInputLength: 1,
                    tags: false
                });
                console.log('Select2 initialized successfully');
            } else {
                console.error('Select2 function not available');
            }
        } catch (e) {
            console.error('Error initializing Select2:', e);
        }
    }
    
    $(document).on('submit', '#sendMessageForm', function(e) {
        e.preventDefault();
        const modalElement = document.getElementById('sendMessageModal');
        const form = $(this);
        
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                form.find('button[type="submit"]').prop('disabled', true);
            },
            success: function(response) {
                if (response.status === 'success') {
                    window.toast.show(response.message, 'success');
                    form[0].reset();
                    $('.select2-recipient').val(null).trigger('change');
                    const modal = coreui.Modal.getInstance(modalElement);
                    if (modal) {
                        modal.hide();
                    }
                    setTimeout(() => {
                        window.location.href = '/inbox';
                    }, 1000);
                } else {
                    window.toast.show('Failed to send message', 'error');
                }
            },
            error: function() {
                window.toast.show('An error occurred while sending the message', 'error');
            },
            complete: function() {
                form.find('button[type="submit"]').prop('disabled', false);
            }
        });
        
        return false;
    });
});