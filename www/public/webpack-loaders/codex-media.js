/**
 * CodeX Media Webpack loader
 */
const getOptions = require('loader-utils').getOptions;

const fs = require('fs');
const path = require('path');

/** Check project static path to get custom script */
module.exports = function(source, map) {

    if (!map) {
        return source;
    }

    let filename = map.file,
        options = getOptions(this),
        callback = this.async();

    if (!options.project) {
        callback(null, source);
    }

    let customScriptPath = path.resolve('projects/' + options.project + '/public/js/' + filename);
    this.addDependency(customScriptPath);

    try {
        fs.readFile(customScriptPath, "utf-8", function(err, customScript) {
            if (err) return callback(null, source);
            callback(null, customScript);
        });
    } catch (e) {}

};