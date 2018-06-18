/**
 * CSS Bundle config
 */
// import { Builder } from  "./builder.js";

const merge         = require('webpack-merge');
const baseConfig    = require('./base.webpack.config');
const Builder       = require('./builder');
const ExtractTextPlugin = require("extract-text-webpack-plugin");
const path          = require('path');

/** Build Project if exists */
Builder.buildCss();

/** Webpack Configuration */
module.exports = merge(baseConfig, {

    entry: './public/build/prebuild-css.js',
    output: {
        path: path.join(__dirname, 'public/build'),
        filename: 'build-css.js',
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
              name: '[name].[ext]',
              publicPath: '/public/build/assets',
              regExp: 'node_modules/(.*)',
              outputPath: 'assets/',
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
                    importLoaders: true,
                    minimize: true
                }
            },
            'postcss-loader'
        ])
      }
    ]},

    plugins: [
        /** Вырезает CSS из JS сборки в отдельный файл */
        new ExtractTextPlugin('bundle.css'),

    ],

    devtool: false

});