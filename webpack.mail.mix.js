const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');

mix.setPublicPath('./')
   .sass('./resources/sass/mail/_index.scss', '../resources/views/vendor/mail/html/themes/default.css')
   .options({
      processCssUrls: false,
      postCss: [
         tailwindcss('./resources/sass/tailwind.config.js')
      ],
   });

// mix.setPublicPath('../resources/views/vendor/mail/html/themes/')
//    .sass('./resources/sass/mail/_index.scss', 'default.css')
//    .options({
//       processCssUrls: false,
//       postCss: [
//          tailwindcss('./resources/sass/tailwind.js')
//       ],
//    });
