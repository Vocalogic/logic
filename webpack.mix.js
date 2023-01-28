const mix = require('laravel-mix');

mix.options({
    terser: {
        extractComments: false,
    }
});
mix.webpackConfig({
    stats: {
        children: true,
    },
});
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
mix.js([
    'resources/js/app.js',
    'node_modules/ladda/js/ladda.js'
], 'public/js/app.js');

mix.styles([
    'node_modules/ladda/dist/ladda-themeless.min.css',

], 'public/css/all.css');

mix.js('resources/js/cart.js', 'public/js');
