import _ from 'lodash';

import jQuery from 'jquery';
import * as bootstrap from 'bootstrap';
import * as axios from 'axios';
import { DateTime } from 'luxon';

export function registerLibs() {
    try {
        // window.Popper = require('popper.js').default;
        window['_'] = _;
        window['$'] = window['jQuery'] = jQuery;
        window['bootstrap'] = bootstrap;
        window['axios'] = axios;
        window['DateTime'] = DateTime;
    } catch (e) {
        console.log(e);
    }
}

export function setupLibs() {
    window['axios'].defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

    let token = document.head.querySelector('meta[name="csrf-token"]');
    if (token) {
        window['axios'].defaults.headers.common['X-CSRF-TOKEN'] = token['content'];
    } else {
        console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
    }
}
