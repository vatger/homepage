import _ from 'lodash';
import jQuery_ from 'jquery';
import * as bootstrap from 'bootstrap';
import axios_, { AxiosInstance, AxiosStatic } from 'axios';
import { DateTime } from 'luxon';

export function registerLibs() {
    try {
        // window.Popper = require('popper.js').default;
        window['_'] = _;
        //window['$'] = window['jQuery'] = jQuery;
        //window['bootstrap'] = bootstrap;
        //window['axios'] = axios;
        //window['DateTime'] = DateTime;
    } catch (e) {
        console.error(e);
    }
}
