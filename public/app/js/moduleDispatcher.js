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

    constructor() {}

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
      */
    initModule(foundRequiredModule) {

        let moduleName = foundRequiredModule.dataset.module,
            moduleSettings;

        if (moduleName) {

            moduleSettings = foundRequiredModule.querySelector('module-settings');

            if (moduleSettings) {

                moduleSettings = moduleSettings.textContent.trim();

            }

            console.log(typeof moduleName);

            if (moduleName.init !== undefined) {

                let parsedSettings = JSON.parse(moduleSettings);

                moduleName.init.call(foundRequiredModule, parsedSettings);

            }

        }

    }

};