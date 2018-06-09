/**
 * Bundle config
 */

const webpack       = require('webpack');
const merge         = require('webpack-merge');
const path          = require('path');
const baseConfig    = require('./base.webpack.config');
const fs            = require('fs');

const ExtractTextPlugin = require("extract-text-webpack-plugin");
const StyleLintPlugin   = require('stylelint-webpack-plugin');

let applicationDirectory = path.resolve(__dirname, './public/app/css');
let projectDirectory = path.resolve(__dirname, './projects/school332/public/css');

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
        } else {
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

fs.appendFileSync(path.resolve(__dirname, 'public/build/prebundle.css'), preBundleContent, function (err) {
    if (err) throw err;
});

module.exports = merge(baseConfig, {

    entry: './public/build/prebuild.js',
    output: {
        filename: './public/build/build-css.js',
        library: 'codex'
    },

    resolve: {
      modules: [
        "node_modules"
      ],
      extensions: [".css"],
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
        use: [
            {
                loader: 'css-loader'
            },
            // {
            //     loader: 'codex-media-css',
            //     options: {}
            // }
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

});