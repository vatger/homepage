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
        console.error(e);
    }
}

export function setupLibs() {
    window['axios'].defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    let token = document.head.querySelector('meta[name="csrf-token"]');
    if (token) {
        let token_content = token['content'];
        window['axios'].defaults.headers.common['X-CSRF-TOKEN'] = token_content;
        window['$'].ajaxSetup({ headers: { 'X-CSRF-TOKEN': token_content } });
    } else {
        console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
    }
}
