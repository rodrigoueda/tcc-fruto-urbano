var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */
elixir(function(mix) {
    mix.less('app.less');

    mix.scripts([
        '../../../node_modules/jquery/dist/jquery.js',
        '../../../node_modules/bootstrap/dist/js/bootstrap.js',
        '../../../node_modules/openlayers/dist/ol.js',
        '../../../node_modules/datatables/media/js/jquery.dataTables.js',
        '../../../node_modules/sweetalert/dist/sweetalert.min.js',
        '../../../node_modules/summernote/dist/summernote.js',
        '../../../node_modules/bootstrap-toggle/js/bootstrap-toggle.js',
        '../../../node_modules/bootstrap-select/js/bootstrap-select.js',

        'core.js',
        'map.js',
        'search.js',
        'events.js',

    ], 'public/js/vendor.js');

    mix.copy('./node_modules/font-awesome/fonts/**', 'public/fonts');
    mix.copy('./node_modules/bootstrap/fonts/**', 'public/fonts');
    mix.copy('./resources/assets/images/**', 'public/img');
    mix.copy('./node_modules/sweetalert/dist/sweetalert.css', 'public/css');
});
