module.exports = {

    prefixJS : 'cdx-script-',

    prefixCSS : 'cdx-style-',

    importScript : function (scriptPath, instanceName) {

        return new Promise(function (resolve, reject) {

            var script;

            /** Script is already loaded */
            if ( !instanceName ) {

                reject('Instance name is missed');

            } else if ( document.getElementById(this.prefixJS + instanceName) ) {

                resolve(scriptPath);

            }

            script = document.createElement('SCRIPT');
            script.async = true;
            script.defer = true;
            script.id    = codex.loader.prefixJS + instanceName;

            script.onload = function () {

                resolve(scriptPath);

            };

            script.onerror = function () {

                reject(scriptPath);

            };

            script.src = scriptPath;
            document.head.appendChild(script);

        });

    },

    importStyle : function (stylePath, instanceName) {

        return new Promise(function (resolve, reject) {

            var style;

            /** Style is already loaded */
            if ( !instanceName ) {

                reject('Instance name is missed');

            } else if ( document.getElementById(this.prefixCSS + instanceName) ) {

                resolve(stylePath);

            }

            style = document.createElement('LINK');
            style.type = 'text/css';
            style.href = stylePath;
            style.rel  = 'stylesheet';
            style.id   = codex.loader.prefixCSS + instanceName;

            style.onload = function () {

                resolve(stylePath);

            };

            style.onerror = function () {

                reject(stylePath);

            };

            style.src = stylePath;
            document.head.appendChild(style);

        });

    },

};
