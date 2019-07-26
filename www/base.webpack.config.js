const webpack = require('webpack');

module.exports = {

    devtool: 'source-map',

    /** Пересборка при изменениях */
    // watch: true,
    watchOptions: {

        /* Таймаут перед пересборкой */
        aggregateTimeout: 50
    },

    plugins: [

        // /** Минифицируем CSS и JS */
        // new webpack.optimize.UglifyJsPlugin({
        // /** Disable warning messages. Cant disable uglify for 3rd party libs such as html-janitor */
        //     compress: {
        //         warnings: false
        //    }
        // }),

        /** Block build if errors found */
        new webpack.NoEmitOnErrorsPlugin(),
    ]
};