/**
 * Module Dispatcher
 * Class for Modules initialization
 *
 * @copyright CodeX Team
 * @license MIT/GPL
 * @author @polinashneider
 *
 * @version 1.0.0
 *
 * @example
 *
 * new moduleDispatcher({
 *   Library : codex
 * });
 */

/**
 * Module object structure:
 *
 * @typedef {Module} Module
 * @property {String} name          - Module's name
 * @property {Element} element      - DOM Element with data-module
 * @property {Object|null} settings - Module settings got from <module-settings>
 * @property {Object} moduleClass   - JS class that handles the Module
 */

class Module {
    constructor({name, element, settings, moduleClass}) {

        this.name = name;
        this.element = element;
        this.settings = settings;
        this.moduleClass = moduleClass;

    }

    /**
     * Initialize each Module by calling its own «init» method
     */
    init() {

        try {

            console.assert(this.moduleClass.init instanceof Function, 'Module «' + this.name + '» should implement init method');


            if (this.moduleClass.init instanceof Function) {

                this.moduleClass.init(this.settings, this.element);
                console.log(`Module «${this.name}» initialized`);

            }

        } catch(e) {

            console.warn('Module «' + this.name + '» was not initialized because of ', e);

        }

    }

    /**
     * Destroy each Module by calling its own «destroy» method, if it exists
     * It is optional
     */
    destroy() {

        if (this.moduleClass.destroy instanceof Function) {

            this.moduleClass.destroy();
            console.log(`Module «${this.name}» destroyed.`);

        }

    }
 }



/**
 * Class structure
 *
 * @typedef {moduleDispatcher} moduleDispatcher
 * @property {Object} Library    - global object, containing Modules to init
 * @property {Module[]} modules  - list of Modules
*/
export default class moduleDispatcher {

    /**
     * @param {Object|null} settings — settings object, optional
     * @param {Object} settings.Library — global object containing Modules
     */
    constructor(settings) {

        this.Library = settings.Library || window;

        /**
         * Found modules list
         * @type {Module[]}
         */
        this.modules = this.findModules(document);

        /**
         * Now we are ready to init Modules
         */
        this.initModules();

    }

   /**
    * Return all modules inside the passed Element
    *
    * @param {Element} element — where to find modules
    *
    * @return {Module[]} found modules list
    */
    findModules(element) {

        /**
         * Store found modules
         * @type {Module[]}
         */
        let modules = [];

        /**
         * Elements with data-module
         * @type {NodeList}
         */
        let elements = element.querySelectorAll('[data-module]');

        /**
         * Iterate found Elements and push them to the Modules list
         */
        for (let i = elements.length - 1; i >= 0; i--) {

            /**
             * One Element can contain several Modules
             * @type {Array}
             */
            modules.push(...this.extractModulesData(elements[i]));

        }

        return modules;

    }

    /**
     * Get all modules from an Element
     *
     * @example <div data-module="comments likes">
     *
     * @type {Module[]}
     *
     * @return {Module[]} - Array of Module objects with settings
     */
    extractModulesData(element) {

        let modules = [];
        /**
         * Get value of data-module attribute
         */
        let modulesList = element.dataset.module;

        /**
         * In case of multiple spaces in modulesList replace with single ones
         */

        modulesList = modulesList.replace(/\s+/, ' ');

        /**
         * One Element can contain several modules
         * @example <div data-module="comments likes">
         * @type {Array}
         */
        let moduleNames = modulesList.split(' ');

        moduleNames.forEach( (name, index) => {

            let module = new Module({
                name: name,
                element: element,
                settings: this.getModuleSettings(element, index, name),
                moduleClass: this.Library[name]
            });

            modules.push(module);

        });

        return modules;

    }

   /**
    * Returns Settings for the Module
    *
    * @param {object} element — HTML element with data-module attribute
    * @param {Number} index   - index of module (in case if an Element countains several modules)
    * @param {String} name    - Module's name
    *
    * @example
    *
    * <module-settings hidden>
    *     {
    *       "currentPage" : "<?= $page_number ?>",
    *       "targetBlockId" : "list_of_news",
    *       "autoLoading": "true"
    *     }
    * </module-settings>
    *
    */
    getModuleSettings(element, index, name) {

        let settingsNodes = element.querySelector('module-settings'),
            settingsObject;

        if (!settingsNodes) {

            return null;

        }

        try {

            settingsObject = settingsNodes.textContent.trim();
            settingsObject = JSON.parse(settingsObject);

        } catch(e) {

            console.warn(`Can not parse Module «${name}» settings bacause of: ` + e);
            console.groupCollapsed(name + ' settings');
            console.log(settingsObject);
            console.groupEnd();

            return null;

        }

        /**
         * Case 1:
         *
         * Single module, settings via object
         *
         * <module-settings>
         *     {
         *         // Comments Module settings
         *     }
         * </module-settings>
         */
        if (!Array.isArray(settingsObject)) {

            if (index === 0) {

                return settingsObject;

            } else {

                console.warn('Wrong settings format. For several Modules use an array instead of object.');
                return null;

            }

        }

        /**
         * Case 2:
         *
         * Several modules, settings via array
         *
         * <module-settings>
         *   [
         *     {
         *         // Module 1 settings
         *     },
         *     {
         *         // Module 2 settings
         *     },
         *     ...
         *   ]
         * </module-settings>
         */
        if (settingsObject[index]) {

            return settingsObject[index];

        } else {

            return null;

        }

    }

    /**
     * Initializes a list of Modules via calling {@link Module#init} for each
     */
    initModules() {

        console.groupCollapsed('ModuleDispatcher');

        this.modules.forEach( module => {

            module.init();

        });

        console.groupEnd();

    }
};