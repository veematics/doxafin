class ToastNotification {
    constructor() {
        this.initContainer();
    }

    initContainer() {
        let container = document.getElementById('toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'position-fixed bottom-0 end-0 p-3';
            container.style.zIndex = '1050';
            document.body.appendChild(container);
        }
        this.container = container;
    }

    show(message, type = 'success') {
        const icons = {
            success: '<svg class="me-2" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>',
            warning: '<svg class="me-2" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
            error: '<svg class="me-2" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>'
        };

        const bgColors = {
            success: 'text-bg-success',
            warning: 'text-bg-warning',
            error: 'text-bg-danger'
        };

        const toastElement = document.createElement('div');
        toastElement.className = `toast align-items-center ${bgColors[type]} border-0`;
        toastElement.setAttribute('role', 'alert');
        toastElement.setAttribute('aria-live', 'assertive');
        toastElement.setAttribute('aria-atomic', 'true');
        toastElement.innerHTML = `
            <div class="d-flex text-white">
                <div class="toast-body d-flex align-items-center">
                    ${icons[type]}
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-coreui-dismiss="toast"></button>
            </div>
        `;

        this.container.appendChild(toastElement);
        const toast = new coreui.Toast(toastElement);
        toast.show();

        // Remove toast after it's hidden
        toastElement.addEventListener('hidden.coreui.toast', () => {
            toastElement.remove();
        });
    }
}

// Create global instance
window.toast = new ToastNotification();