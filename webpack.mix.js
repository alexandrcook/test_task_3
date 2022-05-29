const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .vue()
    .sass('resources/sass/app.scss', 'public/css');

// mix.js('resources/js/app.js', 'public/js')
//     .sass('resources/sass/app.scss', 'public/css')
//     .options({
//         postCss: [ tailwindcss('./tailwind.config.js') ],
//     })
//     .version()
//     .sourceMaps();



mix.browserSync({
    host: '127.0.0.1',
    proxy: 'localhost',
    open: 'local',
    files: [
        'app/**/*.php',
        'resources/views/**/*.php',
        'public/js/**/*.js',
        'public/css/**/*.css'
    ],
    watchOptions: {
        usePolling: true,
        interval: 500
    }
});

// mix.webpackConfig({
//     devServer: {
//         host: '0.0.0.0',
//         port: 8080,
//     },
// });
