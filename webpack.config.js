/**
 * Bundle config
 */

 var webpack           = require('webpack');
 var ExtractTextPlugin = require("extract-text-webpack-plugin");
 var StyleLintPlugin   = require('stylelint-webpack-plugin');


module.exports = {

    entry: './public/app/js/main.js',

    output: {
        filename: './public/build/bundle.js',
        library: 'codex'
    },

    module: {
        loaders: [
            {
                test: /\.css$/,
                loader: ExtractTextPlugin.extract("css-loader?uglify=1&importLoaders=1!postcss-loader")
            },
            {
                test : /\.js$/,
                loader: "eslint-loader"

            }
        ]
    },

    /**
    * PostCSS configuration
    */
    postcss: function () {
        return [

            /** Позволяет использовать CSSnext во вложенных файлах*/
            require('postcss-smart-import'),

            /** Позволяет использовать новые возможности CSS: переменные, фукнции и тд*/
            require('postcss-cssnext'),

        ];
    },

    plugins: [

        /** Минифицируем CSS и JS */
        new webpack.optimize.UglifyJsPlugin({
            /** Disable warning messages. Cant disable uglify for 3rd party libs such as html-janitor */
            compress: {
                warnings: false
            }
        }),

        /** Block biuld if errors found */
        // new webpack.NoErrorsPlugin(),

        /** Вырезает CSS из JS сборки в отдельный файл */
        new ExtractTextPlugin("public/build/bundle.css"),

        /** Проврка синтаксиса CSS */
        new StyleLintPlugin({
            context : './public/app/css/',
            files : 'main.css'
        }),

    ],

    devtool: "source-map",

    /** Пересборка при изменениях */
    watch: true,
    watchOptions: {

        /** Таймаут перед пересборкой */
        aggragateTimeout: 50
    }
};