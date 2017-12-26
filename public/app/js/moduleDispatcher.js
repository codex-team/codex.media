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
    /*
     * @param {object} obj
     */
    constructor(obj) {

        this.initModules(obj);

    }

    /**
     * @param {HTMLElement} element
     */

    initModules(obj, element) {

        let modulesRequired;

        if (element !== undefined) {

            modulesRequired = element.querySelectorAll('[data-module]');

        } else {

            modulesRequired = document.querySelectorAll('[data-module]');

        }

        for (let i = 0; i < modulesRequired.length; i++) {

            this.initModule(obj, modulesRequired[i]);

        }

    }

    /**
      * Get module's name from data attributes
      * Call module with settings that are defined below on <module-settings> tag
      *
      * @param {object} moduleNode
      */
    initModule(obj, moduleNode) {

        let moduleName = moduleNode.dataset.module,
            moduleSettings,
            parsedSettings,
            moduleObject;

        if (moduleName) {

            moduleSettings = moduleNode.querySelector('module-settings');

            if (moduleSettings) {

                moduleSettings = moduleSettings.textContent.trim();
                parsedSettings = JSON.parse(moduleSettings);

            }

            moduleObject = obj[moduleName];

            if (moduleObject.init) {

                moduleObject.init(parsedSettings);

            } else {

                console.assert(moduleObject.init, 'ModuleDispatcher: module «' + moduleName + '» should implement init method');

            }

        }

    }

};