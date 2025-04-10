import * as coreui from '@coreui/coreui-pro';
import { initSidebarHandler } from './sidebar-handler';
window.coreui = coreui;

import Alpine from 'alpinejs';
import $ from 'jquery';
window.$ = $;

window.Alpine = Alpine;


Alpine.start();

document.addEventListener('DOMContentLoaded', initSidebarHandler);

// Toast notification implementation
window.toast = {
    show: function(message, type = 'info', timer = 2000) {
        const toastContainer = document.getElementById('toast-container');
        if (!toastContainer) {
            const container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'toast-container position-fixed top-0 end-0 p-3';
            document.body.appendChild(container);
        }

        const toastElement = document.createElement('div');
        toastElement.className = `toast align-items-center text-white bg-${type} border-0`;
        toastElement.setAttribute('role', 'alert');
        toastElement.setAttribute('aria-live', 'assertive');
        toastElement.setAttribute('aria-atomic', 'true');
        toastElement.setAttribute('data-coreui-delay', timer);

        toastElement.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;

        document.getElementById('toast-container').appendChild(toastElement);

        const toast = new coreui.Toast(toastElement, {
            delay: timer
        });
        toast.show();

        // Remove toast after it's hidden
        toastElement.addEventListener('hidden.coreui.toast', function () {
            toastElement.remove();
        });
    }
};
