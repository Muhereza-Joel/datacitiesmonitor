const mix = require("laravel-mix");

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
mix.styles(
    [
        "public/assets/vendor/bootstrap/css/bootstrap.min.css",
        "public/assets/vendor/bootstrap-icons/bootstrap-icons.css",
        "public/assets/vendor/boxicons/css/boxicons.min.css",
        "public/assets/vendor/quill/quill.snow.css",
        "public/assets/vendor/quill/quill.bubble.css",
        "public/assets/vendor/remixicon/remixicon.css",
        "public/assets/vendor/simple-datatables/style.css",
        "public/assets/vendor/jquery-ui/jquery-ui.min.css",
        "public/assets/css/style.css",
    ],
    "public/css/all.css"
).minify("public/css/all.css");

mix.js("resources/js/app.js", "public/js")
    .sass("resources/sass/app.scss", "public/css")
    .sourceMaps();
