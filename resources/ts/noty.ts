import * as Noty from 'noty';
import { Type } from 'noty';

/**
 * Show new noty message with custom (or default) parameters
 * @param message
 * @param type
 * @param timeout
 */
export function showNoty(message, type = 'success', timeout = 2500) {
    new Noty({
        text: message,
        progressBar: true,
        timeout: timeout,
        layout: 'topRight',
        type: type as Type,
    }).show();
}

export function registerNoty() {
    window['showNoty'] = showNoty;
}

export function laravelFireNoty() {
    window.dispatchEvent(new Event('laravel_showNoty'));
}
