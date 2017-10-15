export default class moduleDispatcher = class {

    constructor() {
        
    }

    delegate (element) {
        let modulesRequired;

        if (element) {
          modulesRequired = element.querySelectorAll('[data-module-required]');
        } else {
          modulesRequired = document.querySelectorAll('[data-module-required]');
        }

        for (let i = 0; i < modulesRequired.length; i++) {
          initModule(modulesRequired[i]);
          console.log(modulesRequired[i])
        }
    };

    /**
    * get's module name from data attributes
    * Calls module with settings that are defined below on <module-settings> tag
    */
    initModule(foundRequiredModule) {

    let moduleName = foundRequiredModule.dataset.moduleRequired,
        moduleSettings;

        if (self[moduleName]) {

            moduleSettings = foundRequiredModule.querySelector('module-settings');

            if (moduleSettings) {
                moduleSettings = moduleSettings.textContent.trim();
            }

            if (self[moduleName].init) {
                let parsedSettings = JSON.parse(moduleSettings);

                self[moduleName].init.call(foundRequiredModule, parsedSettings);
            }
        }
    }

};