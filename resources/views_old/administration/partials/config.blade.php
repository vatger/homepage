<!-- Global configuration -->
<script>
    /**
     * Custom config for routes. These can be loaded in native .js files
     */
    const config = {
        tinyMce: {
            'default': {
                @if (Auth::check() && Auth::user()->settings->dark_mode)
                    skin: 'oxide-dark',
                    content_css: 'dark',
                @endif
                plugins: '',
                menubar: 'false',
                toolbar_mode: 'floating',
            },
            'admin': {
                @if (Auth::check() && Auth::user()->settings->dark_mode)
                    skin: 'oxide-dark',
                    content_css: 'dark',
                @endif
                plugins: 'lists link image',
                menubar: 'false',
                toolbar: 'undo redo | styleselect | bold italic forecolor | bullist numlist | link image',
                toolbar_mode: 'floating',
                selector: 'textarea',
                //TODO: Add more toolbar options for administrative tasks
            },
            'admin_reduced': {
                @if (Auth::check() && Auth::user()->settings->dark_mode)
                    skin: 'oxide-dark',
                    content_css: 'dark',
                @endif
                plugins: 'lists',
                menubar: 'false',
                toolbar: 'undo redo | styleselect | bold italic forecolor | bullist numlist',
                toolbar_mode: 'floating',
                selector: 'textarea',
                //TODO: Add more toolbar options for administrative tasks
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

    @foreach ($errors->all() as $error)
        new Noty({
            text: '{{ $error }}',
            progressBar: true,
            timeout: 5000,
            layout: 'topRight',
            type: 'error',
        }).show();
    @endforeach

    @if (\Illuminate\Support\Facades\Session::has('success'))
        new Noty({
            text: '{{ \Illuminate\Support\Facades\Session::get('success') }}',
            progressBar: true,
            timeout: 5000,
            layout: 'topRight',
            type: 'success',
        }).show();
    @endif
</script>

<!-- Custom utility functions -->
<script>
    function showNoty(message, type = 'success', timeout = 2500) {
        new Noty({
            text: message,
            progressBar: true,
            timeout: timeout,
            layout: 'topRight',
            type: type,
        }).show();
    }

    function formatDate(date, hideDate = false) {
        if (!date) return null;

        let d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear(),
            hour = '' + d.getUTCHours(),
            min = '' + d.getUTCMinutes();


        if (month.length < 2)
            month = '0' + month;
        if (day.length < 2)
            day = '0' + day;
        if (hour.length < 2)
            hour = '0' + hour;
        if (min.length < 2)
            min = '0' + min;

        if (hideDate)
            return [day, month, year].join('.');
        else
            return [day, month, year].join('.') + ", " + [hour, min].join(':');
    }
</script>

<!-- Custom global JS for livewire triggers -->
<script>
    window.addEventListener('livewire_showNoty', event => {
        showNoty(event.detail.message, event.detail.type, event.detail.timeout)
    });

    window.addEventListener('livewire_showModal', event => {
        $('#' + event.detail.dom_id).modal('show');
    });

    window.addEventListener('livewire_hideModal', event => {
        $('#' + event.detail.dom_id).modal('hide');
    });
</script>
