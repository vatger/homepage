import Toastify from 'toastify-js';

/**
 * Show new noty message with custom (or default) parameters
 */
export const showNoty = function (
    message,
    type = 'success',
    timeout = 2500,
    destination: string | undefined = undefined,
    onclick: (() => void) | undefined = undefined
) {
    Toastify({
        text: message,
        duration: timeout,
        destination: destination,
        newWindow: true,
        close: true,
        gravity: 'top', // `top` or `bottom`
        position: 'right', // `left`, `center` or `right`
        stopOnFocus: true, // Prevents dismissing of toast on hover
        style: {
            background: 'linear-gradient(to right, #00b09b, #96c93d)',
        },
        onClick: onclick, // Callback after click
    }).showToast();
};

export function registerNoty() {
    window['showNoty'] = showNoty;
}

export function laravelFireNoty() {
    window.dispatchEvent(new Event('laravel_showNoty'));
}
