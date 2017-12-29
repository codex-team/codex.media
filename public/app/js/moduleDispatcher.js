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

        this.Library = obj;

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
      * @param {object} dataModuleNode — HTML element with data-module="" attribute
      */
    initModule(dataModuleNode) {

       /**
        * @type {String} moduleName — name of module to init
        * @example
        * dataModuleNode: <span data-module="islandSettings">
        * moduleName: islandSettings
        *
        * @type {Object} moduleSettings — contents of <module-settings> tag
        *
        * @type {Object} parsedModuleSettings — JSON-parsed value of moduleSettings
        *
        * @type {String} module — module from the Library selected by name
        * @example
        * module = codex[moduleName[i]];
        */
        let moduleName = dataModuleNode.dataset.module,
            moduleSettings,
            parsedModuleSettings = {};

        try {

           /**
            * Split contents of data-module="" into array
            * Of one or more modules to init
            */
            moduleName = moduleName.split(' ');

           /**
            * Find settings values in <module-settings> and parse them
            */
            moduleSettings = dataModuleNode.querySelector('module-settings');

            if (moduleSettings) {

                moduleSettings = moduleSettings.textContent.trim();
                parsedModuleSettings = JSON.parse(moduleSettings);

            }

           /**
            * Call function to init multiple modules
            */
            for (let i = 0; i < moduleName.length; i++) {

                this.initMultipleModules(moduleName[i], parsedModuleSettings, dataModuleNode);

            }

        } catch(e) {

            console.warn('ModuleDispatcher error: ', e);

        }

    };

    /**
    * Calls init method of multiple modules
    * If data-module="" has more than one module name
    * And <module-settings> contains multiple settings values
    */
    initMultipleModules(moduleName, parsedModuleSettings, dataModuleNode) {

        try {

           /**
            * Select module by name from the Library
            *
            * @example
            * module = codex[moduleName[i]];
            */
            let module = this.Library[moduleName];

           /**
            * If we have multiple modules to init
            * With multiple parsed settings values
            *
            * @param {HTMLElement} dataModuleNode — HTML element with data-module="" attribute
            * On which ModuleDispatcher is called
            */
            if (parsedModuleSettings.length > 1) {

                if (module.init) {

                    for (let i = 0; i < parsedModuleSettings.length; i++) {

                        module.init(parsedModuleSettings[i], dataModuleNode);

                    }

                }

           /**
            * Otherwise init a single module with parsed settings
            */

            } else {

                if (module.init instanceof Function) {

                    module.init(parsedModuleSettings, dataModuleNode);

                }

            }

        } catch(e) {

            console.assert('ModuleDispatcher: module «' + moduleName + '» should implement init method');

        }

    }

};
