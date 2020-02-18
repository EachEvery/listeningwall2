const mix = require("laravel-mix");
const tailwindcss = require("tailwindcss");
const webpack = require("webpack");

require("laravel-mix-svg");

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

mix.webpackConfig({
    resolve: {
        alias: {
            videojs: "video.js",
            WaveSurfer: "wavesurfer.js",
            RecordRTC: "recordrtc"
        }
    },
    plugins: [
        new webpack.ProvidePlugin({
            videojs: "video.js/dist/video.cjs.js",
            RecordRTC: "recordrtc",
            MediaStreamRecorder: ["recordrtc", "MediaStreamRecorder"]
        })
    ]
})
    .js("resources/js/app.js", "public/js")
    .version()
    .js("resources/js/renderResponse.js", "public/js")
    .version()
    .svg()
    .sass("resources/sass/app.scss", "public/css")
    .options({
        processCssUrls: false,
        postCss: [tailwindcss("./tailwind.js")]
    })
    .version();
