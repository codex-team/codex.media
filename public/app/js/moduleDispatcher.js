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
      * @param {object} moduleNode
      */
    initModule(moduleNode) {

        let moduleName = moduleNode.dataset.module,
            moduleSettings,
            parsedSettings,
            moduleObject;

        try {

            moduleName = moduleName.split(' ');

            moduleSettings = moduleNode.querySelector('module-settings');

            if (moduleSettings) {

                moduleSettings = moduleSettings.textContent.trim();
                parsedSettings = JSON.parse(moduleSettings);

            }

            for (var i = 0; i < moduleName.length; i++) {

                try {

                    moduleObject = this.globalObj[moduleName[i]];

                    if (parsedSettings['multipleSettings']) {

                        if (moduleObject.init) {

                            moduleObject.init(parsedSettings['multipleSettings'][i], moduleNode);

                        }

                    } else {

                        if (moduleObject.init) {

                            moduleObject.init(parsedSettings, moduleNode);

                        }

                    }

                } catch(e) {

                    console.assert('ModuleDispatcher: module «' + moduleName[i] + '» should implement init method');

                }

            }


        } catch(e) {

            console.warn('ModuleDispatcher error: ', e);

        }

    }

};
