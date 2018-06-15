/**
 * Project Builder
 */
require('dotenv').load();

const path = require('path');
const fs = require('fs');

class Builder {

    /**
     * Util that recursively get's folders files with passed extension and fills an array
     * @param {String} directory
     * @param {String} extension
     * @param {Array} outputFiles
     */
    static readDirFilesRecusrivelly(directory, extension, outputFiles = []) {

        let directoryItems = fs.readdirSync(directory);

        let files = directoryItems.filter( (name) => {
            return !fs.statSync(path.join(directory, name)).isDirectory() && extension.test(name);
        });

        let folders = directoryItems.filter( (name) => {
            return fs.statSync(path.join(directory, name)).isDirectory();
        });

        outputFiles.push(...files.map(name => {
            return {
                name,
                path: path.join(directory, name)
            }
        }));

        folders.forEach( folderName => {
            Builder.readDirFilesRecusrivelly(path.join(directory, folderName), extension, outputFiles);
        })

    }

    /**
     * Create prebuild-css js file that loads project CSS
     */
    static preBuild() {
        let prebuildJsContest =
            "const css = require('./prebuild.css');\n" +
            "\n" +
            "module.exports = {};";

        fs.writeFileSync(path.resolve(__dirname, './public/build/prebuild-css.js'), prebuildJsContest);
    }

    /**
     * Build Project CSS
     */
    static buildCss() {

        // prepare JS file that loads prebuilded CSS
        Builder.preBuild();

        let applicationDirectory = path.resolve(__dirname, './public/app/css');
        let projectDirectory = path.resolve(__dirname, './projects/' + process.env.PROJECT + '/public/css');

        let applicationFiles = [];
        let projectFiles = [];
        let preBundle = [];

        if (fs.existsSync(applicationDirectory) && fs.statSync(applicationDirectory).isDirectory()) {
            Builder.readDirFilesRecusrivelly(applicationDirectory, new RegExp('.css$'), applicationFiles);
        }

        if (fs.existsSync(projectDirectory) && fs.statSync(projectDirectory).isDirectory()) {
            Builder.readDirFilesRecusrivelly(projectDirectory, new RegExp('.css$'), projectFiles);
        }

        /**
         * Inherite default styles with project's styles
         */
        for (let i = 0; i < applicationFiles.length; i++) {

            /**
             * Check if project has an inherited file with styles
             */
            let inherited = projectFiles.find( (file) => {
                return file.name === applicationFiles[i].name;
            });

            /**
             * If inherited file does not exist then add default styles
             * otherwise add file from project
             */
            if (!inherited) {
                preBundle.push(applicationFiles[i].path);
            } else {
                preBundle.push(inherited.path);
            }
        }

        /**
         * Add other project's styles
         */
        for (let i = 0; i < projectFiles.length; i++) {
            if (!preBundle.includes(projectFiles[i].path)) {
                preBundle.push(projectFiles[i].path);
            }
        }

        let preBundleContent = '';

        preBundle.forEach( function(file) {
            preBundleContent = preBundleContent + `@import url('${file}');\n`;
        });

        fs.writeFileSync(path.resolve(__dirname, './public/build/prebuild.css'), preBundleContent);
    }
}

module.exports = Builder;