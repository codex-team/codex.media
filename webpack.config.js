/**
 * Bundle config
 */

 let webpack           = require('webpack');
 let ExtractTextPlugin = require("extract-text-webpack-plugin");
 let StyleLintPlugin   = require('stylelint-webpack-plugin');


module.exports = {

    entry: './public/app/js/main.js',

    output: {
        filename: './public/build/bundle.js',
        library: 'codex'
    },

    module: {
        rules: [
      {
        test : /\.(png|jpg|svg)$/,
        use : [
          {
            loader: 'file-loader',
            options: {
              name: '[1].[ext]',
              publicPath: '/',
              regExp: 'node_modules/(.*)',
              outputPath: 'public/build/assets/'
            },
          }
        ]
      },
      {
        /**
         * Use for all CSS files loaders below
         * - extract-text-webpack-plugin
         * - postcss-loader
         */
        test: /\.css$/,
        /** extract-text-webpack-plugin */
        use: ExtractTextPlugin.extract([
          {
            loader: 'css-loader',
            options: {
              minimize: 1,
              importLoaders: 1
            }
          },
          /** postcss-loader */
          'postcss-loader'
        ])
      },
      {
        /**
         * Use for all JS files loaders below
         * - babel-loader
         * - eslint-loader
         */
        test: /\.js$/,
        use : [
          /** Babel loader */
          {
            loader: 'babel-loader',
            options: {
              presets: [ 'env' ]
            },
          },
          /** ES lint For webpack build */
          {
            loader: 'eslint-loader',
            options: {
              fix: true,
              sourceType: 'module'
            }
          }
        ]
      }
    ]
        // loaders: [
        //     {
        //         test: /\.css$/,
        //         loader: ExtractTextPlugin.extract("css-loader?uglify=1&importLoaders=1!postcss-loader")
        //     },
        //     {
        //         test : /\.js$/,
        //         exclude: /node_modules/,
        //         loaders: "eslint-loader?fix=true&babel-loader"
    
        //     },
        //     /**
        //      * File loader for external assets
        //      * Uses in codex.editor.personality
        //      */
        //     {
        //       test : /\.(png|jpg|svg)$/,
        //       include : /node_modules/,
        //       loaders : "file-loader?name=[1].[ext]&outputPath=public/build/assets/&publicPath=/&regExp=node_modules/(.*)"
        //     },
        // ]
    },

    /**
    * PostCSS configuration
    */
    // postcss: function () {
    //     return [

    //         /** Позволяет использовать CSSnext во вложенных файлах*/
    //         require('postcss-smart-import'),

    //         /** Позволяет использовать новые возможности CSS: переменные, фукнции и тд*/
    //         require('postcss-cssnext'),

    //     ];
    // },

    plugins: [

        /** Минифицируем CSS и JS */
        // new webpack.optimize.UglifyJsPlugin({
            /** Disable warning messages. Cant disable uglify for 3rd party libs such as html-janitor */
        //     compress: {
        //         warnings: false
        //    }
        // }),

        /** Block build if errors found */
        new webpack.NoEmitOnErrorsPlugin(),

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

        /* Таймаут перед пересборкой */
        aggregateTimeout: 50
    }
};