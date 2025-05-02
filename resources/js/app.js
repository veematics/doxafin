import * as coreui from '@coreui/coreui-pro';
import { initSidebarHandler } from './sidebar-handler';
import $ from 'jquery';

window.coreui = coreui;
window.$ = window.jQuery = $;

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', initSidebarHandler);

