const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/app-admin.js', 'public/js')
    .js('resources/js/tiny-slider.js', 'public/js')
    .sass('modules/atciss/resources/scss/atciss.scss', 'public/css/atciss')
    .sass('resources/scss/app.scss', 'public/css')
    .sass('resources/scss/app-dark.scss', 'public/css')
    .sass('resources/scss/app-admin.scss', 'public/css')
    .sass('resources/scss/app-admin-dark.scss', 'public/css');
