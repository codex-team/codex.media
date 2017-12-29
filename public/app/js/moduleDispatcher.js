 /**
  * Initiate modules
  * @type {moduleDispatcher}
  *
  * let modules = new moduleDispatcher();
  *
  * modules.initModules();
  *
  */
export default class moduleDispatcher {
   /**
    * @param {object} obj
    */
    constructor(obj) {

        this.globalObj = obj;

        this.initModules();

    }

   /**
    * @param {HTMLElement} element
    */
    initModules(element) {

        let modulesRequired;

        if (element !== undefined) {

            modulesRequired = element.querySelectorAll('[data-module]');

        } else {

            modulesRequired = document.querySelectorAll('[data-module]');

        }

        for (let i = 0; i < modulesRequired.length; i++) {

            this.initModule(modulesRequired[i]);

        }

    }

     /**
      * Get module's name from data attributes
      * Call module with settings that are defined below on <module-settings> tag
      *
      * @param {object} moduleNode — HTML element with data-module="" attribute
      */
    initModule(moduleNode) {

       /**
        * Calls init method of multiple modules
        * If data-module="" has more than one module name
        * And <module-settings> contains multiple settings values
        */
        this.initMultiple = function () {

            try {

               /**
                * Select module by name from the globalObj
                *
                * @example
                * moduleObject = codex[moduleName[i]];
                */
                moduleObject = this.globalObj[moduleName[i]];

               /**
                * If we have multiple modules to init
                * With multiple parsed settings values
                *
                * @param {HTMLElement} moduleNode — HTML element with data-module="" attribute
                * On which ModuleDispatcher is called
                */
                if (parsedSettings.length > 1) {

                    if (moduleObject.init) {

                        moduleObject.init(parsedSettings[i], moduleNode);

                    }

               /**
                * Otherwise init a single module with parsed settings
                */

                } else {

                    if (moduleObject.init) {

                        moduleObject.init(parsedSettings, moduleNode);

                    }

                }

            } catch(e) {

                console.assert('ModuleDispatcher: module «' + moduleName[i] + '» should implement init method');

            }

        };

       /**
        * @type {String} moduleName — name of module to init
        * @example
        * moduleNode: <span data-module="islandSettings">
        * moduleName: islandSettings
        *
        * @type {Object} moduleSettings — contents of <module-settings> tag
        *
        * @type {Object} parsedSettings — JSON-parsed value of moduleSettings
        *
        * @type {String} moduleObject — module from the globalObj selected by name
        * @example
        * moduleObject = codex[moduleName[i]];
        */
        let moduleName = moduleNode.dataset.module,
            moduleSettings,
            parsedSettings,
            moduleObject;

        try {

           /**
            * Split contents of data-module="" into array
            * Of one or more modules to init
            */
            moduleName = moduleName.split(' ');

           /**
            * Find settings values in <module-settings> and parse them
            */
            moduleSettings = moduleNode.querySelector('module-settings');

            if (moduleSettings) {

                moduleSettings = moduleSettings.textContent.trim();
                parsedSettings = JSON.parse(moduleSettings);

            }

           /**
            * Call function to init multiple modules
            */
            for (var i = 0; i < moduleName.length; i++) {

                this.initMultiple();

            }

        } catch(e) {

            console.warn('ModuleDispatcher error: ', e);

        }

    }

};
