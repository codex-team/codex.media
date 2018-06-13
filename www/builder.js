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
     * @param {Array} files
     */
    static readDirFilesRecusrivelly(directory, extension, files = []) {
        fs.readdirSync(directory).map( (name) => {
            if (extension.test(name)) {
                files.push({
                    name: name,
                    path: path.join(directory, name)
                });
            } else if (fs.statSync(directory).isDirectory()) {
                Builder.readDirFilesRecusrivelly(path.join(directory, name), extension, files);
            }
        });

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

        Builder.readDirFilesRecusrivelly(applicationDirectory, new RegExp('.css$'), applicationFiles);
        Builder.readDirFilesRecusrivelly(projectDirectory, new RegExp('.css$'), projectFiles);

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

        fs.writeFileSync(path.resolve(__dirname, './public/build/prebuild.css'), preBundleContent);

    }
}

module.exports = Builder;