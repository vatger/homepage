/**
 * Custom config for routes. These can be loaded in native .js files
 */
const config = {
    routes: {
        api: {
            events: {
                'loadEvents': "{{ route('api.loadEvents') }}",
            },
            atcfb: {
                'checkUser': "{{ route('api.user.check') }}"
            }
        },
        global: {

        }
    },
    tinyMce: {
        'default': {
            skin: 'oxide-dark',
            content_css: 'dark',
            plugins: 'lists',
            menubar: 'false',
            toolbar: 'undo redo | styleselect | bold italic | bullist numlist',
            toolbar_mode: 'floating',
            selector: 'textarea',
        }
    }
};

/**
 * Setup default ajax Settings
 */
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

/**
 * Show new noty message with custom (or default) parameters
 * @param message
 * @param type
 * @param timeout
 */
function showNoty(message, type = 'success', timeout = 2500) {
    new Noty({
        text: message,
        progressBar: true,
        timeout: timeout,
        layout: 'topRight',
        type: type,
    }).show();
}

/**
 * Returns the corresponding short ATC rating from its ID
 * @param id
 * @returns {string}
 */
function convertAtcRating(id) {
    switch (id) {
        case -1:
            return "INAC";

        case 0:
            return "SUS";

        case 1:
            return "OBS";

        case 2:
            return "S1";

        case 3:
            return "S2";

        case 4:
            return "S3";

        case 5:
            return "C1";

        case 6:
            return "C2";

        case 7:
            return "C3";

        case 8:
            return "I1";

        case 9:
            return "I2";

        case 10:
            return "I3";

        case 11:
            return "SUP";

        case 12:
            return "ADM";
    }

    return "err";
}