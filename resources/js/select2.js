import jQuery from 'jquery';

window.$ = jQuery;
window.jQuery = jQuery;

document.addEventListener('DOMContentLoaded', function() {
    function loadScript() {
        const cdnScript = document.createElement('script');
        cdnScript.src = 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js';
        cdnScript.onload = function() {
            console.log('Select2 script loaded from CDN');
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css';
            document.head.appendChild(link);
            initializeSelect2Components();
        };
        document.head.appendChild(cdnScript);
    }

    function initializeSelect2Components() {
        $('.select2').each(function() {
            if ($(this).data('select2')) {
                $(this).select2('destroy');
            }
            $(this).select2({
                width: '100%',
                allowClear: true
            });
        });
    }

    loadScript();
});