let mix = require('laravel-mix')
let NovaExtension = require('laravel-nova-devtool')

mix.extend('nova', new NovaExtension())

mix
  .setPublicPath('dist')
  .js('resources/js/tool.js', 'js')
  .css('resources/css/tool.css', 'css')
  .vue({ version: 3 })
  .nova('clevyr/nova-filemanager')
  .version()
