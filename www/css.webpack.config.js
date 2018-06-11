/**
 * CSS Bundle config
 */
require('dotenv').load();

const webpack       = require('webpack');
const merge         = require('webpack-merge');
const path          = require('path');
const baseConfig    = require('./base.webpack.config');
const fs            = require('fs');

const ExtractTextPlugin = require("extract-text-webpack-plugin");
const StyleLintPlugin   = require('stylelint-webpack-plugin');

let applicationDirectory = path.resolve(__dirname, './public/app/css');
let projectDirectory = path.resolve(__dirname, './projects/' + process.env.PROJECT + '/public/css');

let applicationFiles = [];
let projectFiles = [];
let preBundle = [];

function readDirFilesRecusrivelly(directory, extension, files = []) {
    fs.readdirSync(directory).map( (name) => {
        if (extension.test(name)) {
            files.push({
                name: name,
                path: path.join(directory, name)
            });
        } else if (fs.statSync(directory).isDirectory()) {
            readDirFilesRecusrivelly(path.join(directory, name), extension, files);
        }
    });

}

readDirFilesRecusrivelly(applicationDirectory, new RegExp('.css$'), applicationFiles);
readDirFilesRecusrivelly(projectDirectory, new RegExp('.css$'), projectFiles);

for(let i = 0; i < applicationFiles.length; i++) {

    let inherited = projectFiles.find( (file) => {
       return file.name === applicationFiles[i].name;
    });

    if (!inherited) {
        preBundle.push(applicationFiles[i].path);
    } else {
        preBundle.push(inherited.path);
    }
}

let preBundleContent = '';

preBundle.forEach( function(file) {
    preBundleContent = preBundleContent + `@import url('${file}');\n`;
});

fs.writeFileSync(path.resolve(__dirname, './public/prebuild/prebuild.css'), preBundleContent);

module.exports = merge(baseConfig, {

    entry: './public/prebuild/prebuild.js',
    output: {
        filename: './public/prebuild/build-css.js',
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
                    importLoaders: true
                }
            },
            'postcss-loader'
        ])
      }
    ]},

    plugins: [

        /** Минифицируем CSS и JS */
        new webpack.optimize.UglifyJsPlugin({
            /** Disable warning messages. Cant disable uglify for 3rd party libs such as html-janitor */
            compress: {
                warnings: false
           }
        }),

        /** Block build if errors found */
        new webpack.NoEmitOnErrorsPlugin(),

        /** Вырезает CSS из JS сборки в отдельный файл */
        new ExtractTextPlugin("public/build/bundle.css"),
    ],

});