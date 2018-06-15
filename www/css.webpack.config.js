/**
 * CSS Bundle config
 */
// import { Builder } from  "./builder.js";

const merge         = require('webpack-merge');
const baseConfig    = require('./base.webpack.config');
const Builder       = require('./builder');
const ExtractTextPlugin = require("extract-text-webpack-plugin");

/** Build Project if exists */
Builder.buildCss();

/** Webpack Configuration */
module.exports = merge(baseConfig, {

    entry: './public/build/prebuild-css.js',
    output: {
        filename: './public/build/build-css.js',
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
        new ExtractTextPlugin("public/build/bundle.css"),

    ],

    devtool: false

});