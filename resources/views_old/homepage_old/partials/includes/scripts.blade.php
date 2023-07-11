<script src="https://cdn.jsdelivr.net/npm/luxon@2.3.0/build/global/luxon.min.js"></script>
<!-- javascript -->
@vite(['resources/js/app.js'])
<script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
<script>
    feather.replace()
</script>
<script src="https://cdn.jsdelivr.net/npm/js-cookie/dist/js.cookie.min.js"></script>
<script src="https://code.iconify.design/iconify-icon/1.0.0/iconify-icon.min.js"></script>
{{--
    Adjust color scheme based on OS colors.
    This might require a page reload.
 --}}
<script>
    // code to set the `color_scheme` cookie
    let color_scheme = Cookies.get("color_scheme");

    function get_color_scheme() {
        return (window.matchMedia && window.matchMedia("(prefers-color-scheme: dark)").matches) ? "dark" : "light";
    }

    function update_color_scheme() {
        Cookies.set("color_scheme", get_color_scheme());
        location.reload();
    }

    // read & compare cookie `color-scheme`
    if ((typeof color_scheme === "undefined") || (get_color_scheme() != color_scheme))
        update_color_scheme();
    // detect changes and change the cookie
    if (window.matchMedia)
        window.matchMedia("(prefers-color-scheme: dark)").addListener(update_color_scheme);
</script>

@stack('custom-script')
