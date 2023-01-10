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
mix.js('resources/js/app.js', 'public/js');
mix.js('resources/js/cart.js', 'public/js');
