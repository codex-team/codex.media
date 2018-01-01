 /**
  * Initiate modules
  * @type {moduleDispatcher}
  *
  * let modules = new moduleDispatcher();
  *
  * modules.findModules();
  *
  */
export default class moduleDispatcher {
   /**
    * @param {object} library — parent object
    * Containing all modules we are going to init
    */
    constructor(obj) {

        let settingsStyles = 'module-settings { display: none; }';

        this.library = obj;

        this.appendStyle(settingsStyles);

        this.findModules();

    }

   /**
    * Hides settings tags <module-settings>
    * @param {String} settingsStyles
    */
    appendStyle(settingsStyles) {

        let styleTag = document.createElement('style');

        styleTag.type = 'text/css';

        if (styleTag.styleSheet) {

            styleTag.styleSheet.cssText = settingsStyles;

        } else {

            styleTag.appendChild(document.createTextNode(settingsStyles));

        }

        document.getElementsByTagName('head')[0].appendChild(styleTag);

    }

   /**
    * Searches for module settings in <data-module> tags
    *
    * @param {HTMLElement} element — starts to search inside specified element
    * Or if this element is undefined, searches whole document for settings
    */
    findModules(element) {

        let modulesRequired;

        if (element !== undefined) {

            modulesRequired = element.querySelectorAll('[data-module]');

        } else {

            modulesRequired = document.querySelectorAll('[data-module]');

        }

        for (let i = 0; i < modulesRequired.length; i++) {

            this.initModules(modulesRequired[i]);

        }

    }

   /**
    * Get module's name from data attributes
    * Call module with settings that are defined below on <module-settings> tag
    *
    * @param {object} dataModuleNode — HTML element with data-module="" attribute
    */
    initModules(dataModuleNode) {

       /**
        * @type {String} moduleName — name of module to init
        *
        * @example
        * dataModuleNode: <span data-module="islandSettings">
        * moduleName: islandSettings
        */
        let moduleName = dataModuleNode.dataset.module;
        /**
        * @type {Object} moduleSettings — contents of <module-settings> tag
        */
        let moduleSettings;
        /**
        * @type {Object} parsedModuleSettings — JSON-parsed value of moduleSettings
        */
        let parsedModuleSettings = {};

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

                this.initModule(moduleName[i], parsedModuleSettings, dataModuleNode);

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
    initModule(moduleName, parsedModuleSettings, dataModuleNode) {

        try {

           /**
            * Select module by name from the library
            *
            * @example
            * module = this.library[moduleName];
            *
            * For this.library
            * See {@link moduleDispatcher constructor} and [constructor's obj @param]
            */
            let module = this.library[moduleName];

           /**
            * If we have multiple modules to init
            * With multiple parsed settings values
            *
            * @param {HTMLElement} dataModuleNode — HTML element with data-module="" attribute
            * On which ModuleDispatcher is called
            */
            if (module.init instanceof Function) {

                if (parsedModuleSettings.length > 1) {

                    for (let i = 0; i < parsedModuleSettings.length; i++) {

                        module.init(parsedModuleSettings[i], dataModuleNode);

                    }

               /**
                * Otherwise init a single module with parsed settings
                */

                } else {

                    module.init(parsedModuleSettings, dataModuleNode);

                }

            }

        } catch(e) {

            console.assert('ModuleDispatcher: module «' + moduleName + '» should implement init method');

        }

    }

};
