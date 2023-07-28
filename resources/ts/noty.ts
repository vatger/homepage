import { Type } from 'noty';

const noty = require('noty');

/**
 * Show new noty message with custom (or default) parameters
 * @param message
 * @param type
 * @param timeout
 */
export function showNoty(message, type = 'success', timeout = 2500) {
    let nc = window['Noty'];
    let n = new nc({
        text: message,
        progressBar: true,
        timeout: timeout,
        layout: 'topRight',
        type: type as Type,
    });
    n.show();
}

export function registerNoty() {
    window['Noty'] = noty;
    window['showNoty'] = showNoty;
}

export function laravelFireNoty() {
    window.dispatchEvent(new Event('laravel_showNoty'));
}
