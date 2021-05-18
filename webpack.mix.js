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

 // mix

mix.js('resources/js/src/app.js', 'public/js')
    .vue()
    .webpackConfig({
       resolve: {
         extensions: ['.js', '.vue', '.json', '.scss', '.sass'],
         alias: {
           '@': __dirname + '/resources/js/src'
          }
        },
    })
    .sass('resources/css/app.scss', 'public/css')
    .sourceMaps()
    // .postCss('resources/css/app.default.css', 'public/css', [
    // require('postcss-import'),
    // require('tailwindcss'),
    // require('autoprefixer'),
    // ]);
