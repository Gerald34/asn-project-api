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
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/toastr.scss', 'public/css');

mix.webpackConfig({
    resolve: {
        modules: [
            'node_modules',
            path.resolve(__dirname, 'resources/assets/js'),
            path.resolve(__dirname, 'resources/assets/svg'),
        ]
    },
    module: {
        rules: [{
            test: /\.svg$/,
            use: [{
                loader: 'html-loader',
                options: {
                    minimize: true
                }
            }]
        }]
    }
});

Mix.listen('configReady', function (config) {
    const rules = config.module.rules;
    const targetRegex = /(\.(png|jpe?g|gif)$|^((?!font).)*\.svg$)/;

    for (let rule of rules) {
        if (rule.test.toString() == targetRegex.toString()) {
            rule.exclude = /\.svg$/;
            break;
        }
    }
});
