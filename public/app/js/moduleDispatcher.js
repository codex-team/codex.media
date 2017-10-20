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

        if (element) {

            modulesRequired = element.querySelectorAll('[data-module-required]');

        } else {

            modulesRequired = document.querySelectorAll('[data-module-required]');

        }

        for (let i = 0; i < modulesRequired.length; i++) {

            moduleDispatcher.initModule(modulesRequired[i]);

        }

    }

    /**
      * Get module's name from data attributes
      * Call module with settings that are defined below on <module-settings> tag
      */
    initModule(foundRequiredModule) {

        let moduleName = foundRequiredModule.dataset.moduleRequired,
            moduleSettings;

        if (moduleName) {

            moduleSettings = foundRequiredModule.querySelector('module-settings');

            if (moduleSettings) {

                moduleSettings = moduleSettings.textContent.trim();

            }

            if (moduleName.init) {

                let parsedSettings = JSON.parse(moduleSettings);

                moduleName.init.call(foundRequiredModule, parsedSettings);

            }

        }

    }

};