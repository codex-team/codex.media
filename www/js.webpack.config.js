/**
 * JS Bundle config
 */
require('dotenv').load();

const webpack       = require('webpack');
const path          = require('path');
const merge         = require('webpack-merge');
const baseConfig    = require('./base.webpack.config');

module.exports = merge(baseConfig, {

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
    ],

});