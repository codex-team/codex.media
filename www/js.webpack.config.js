/**
 * JS Bundle config
 */
require('dotenv').load();

const HawkWebpackPlugin = require('@hawk.so/webpack-plugin');

const path          = require('path');
const merge         = require('webpack-merge');
const baseConfig    = require('./base.webpack.config');

module.exports = merge(baseConfig, {
    entry: {
        codex: './public/app/js/main.js',
        HawkCatcher: './public/app/js/modules/hawk.js'
    },

    output: {
        path: path.join(__dirname, 'public/build'),
        publicPath: '/public/build/',
        filename: '[name].bundle.js',
        chunkFilename: '[name].bundle.js?h=[hash]',
        library: '[name]'
    },

    resolve: {
        modules: [
            path.join(__dirname, 'public', 'app', 'js'),
            'node_modules'
        ],
        extensions: ['.js', '.css']
    },

    resolveLoader: {
        modules: ['node_modules', path.join('.', 'public', 'webpack-loaders')]
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
                // include: [
                //   path.join('.', 'public', 'app', 'js')
                // ],
                use : [
                    {
                        /**
                         * CodeX Media Loader.
                         * Loader replace project's js source that inherits application's file
                         */
                        loader: 'codex-media',
                        options: {
                            project: process.env.PROJECT
                        }
                    },
                    /** Babel loader */
                    {
                        loader: 'babel-loader',
                        options: {
                            presets: [
                                '@babel/preset-env',
                            ],
                            plugins: [
                                'babel-plugin-transform-es2015-modules-commonjs',
                                '@babel/plugin-syntax-dynamic-import'
                            ]
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
        ]
    },

    plugins: [
        new HawkWebpackPlugin({
            integrationToken: process.env.HAWK_TOKEN
        })
    ],

    devtool: 'hidden-source-map',
});
