import * as coreui from '@coreui/coreui-pro';
import { initSidebarHandler } from './sidebar-handler';
window.coreui = coreui;

import Alpine from 'alpinejs';
import $ from 'jquery';

window.Alpine = Alpine;

Alpine.start();

// Initialize sidebar handler when DOM is ready
document.addEventListener('DOMContentLoaded', initSidebarHandler);
