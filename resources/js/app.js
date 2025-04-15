import * as coreui from '@coreui/coreui-pro';
import { initSidebarHandler } from './sidebar-handler';
window.coreui = coreui;

import Alpine from 'alpinejs';

window.Alpine = Alpine;


Alpine.start();

document.addEventListener('DOMContentLoaded', initSidebarHandler);

