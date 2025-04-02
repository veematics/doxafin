// Import all of CoreUI's JS
import * as coreui from '@coreui/coreui-pro'

window.coreui = coreui

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
