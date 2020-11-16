const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');
const colorFunction = require('postcss-color-function');
require('laravel-mix-purgecss');

mix.setPublicPath('public')
   .js('resources/js/app.js', 'js')
   .js('resources/js/auth/app.js', 'js/auth.js')
   .sass('resources/sass/app.scss', 'css')
   .options({
      processCssUrls: false,
      postCss: [
         tailwindcss('./resources/sass/tailwind.config.js'),
         colorFunction()
      ],
   })
   .webpackConfig({
      resolve: {
         alias: { '@js': __dirname + '/resources/js' }
      }
   });

if (mix.inProduction()) {
   mix.version()
      .purgeCss({
         extensions: ['html', 'js', 'php', 'vue'],
         folders: [
            'resources/views',
            'resources/js'
         ]
      });
}
