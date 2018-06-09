/**
 * Bundle config
 */

let webpack           = require('webpack');
let ExtractTextPlugin = require("extract-text-webpack-plugin");
let StyleLintPlugin   = require('stylelint-webpack-plugin');
let path              = require('path');

require('dotenv').load();

module.exports = {

    entry: './public/app/js/main.js',

    output: {
        filename: './public/build/bundle.js',
        library: 'codex'
    },

    resolve: {
      modules: [
        path.resolve(__dirname, 'public/app/js'),
        "node_modules"
      ],
      extensions: [".js", ".css"],
    },

    resolveLoader: {
        modules: ['node_modules', path.resolve(__dirname, 'public/webpack-loaders')]
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
              outputPath: 'public/build/assets/',
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
        include: [
          path.resolve(__dirname, 'public/app/js')
        ],
        use : [
          {
            loader: 'codex-media',
            options: {
              project: process.env.PROJECT
            }
          },
          /** Babel loader */
          {
            loader: 'babel-loader',
            options: {
              presets: [ 'es2015' ],
            }
          },
          /** ES lint For webpack build */
          {
            loader: 'eslint-loader',
            options: {
              fix: true
            }
          }
        ]
      }
    ]},

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