/**
 * Module for load and start EditorJS
 *
 * Usage:
 *
 * <div data-module="writing">
 *   <module-settings hidden>
 *     {
 *      "holderId" : "placeForEditor",
 *      "formId": "atlasForm",
 *      "initializeWithTools": "<?= $hideEditorToolbar ?>"
 *     }
 *   </module-settings>
 * </div>
 */

const ajax = require('@codexteam/ajax');
const notifier = require('codex-notifier');

class Writing {

    constructor() {

        /**
         * Editor class Instance
         */
        this.editor = null;

        /**
         * Form with editor data
         */
        this.form = null;

    }


    /**
     * Load Editor from separate chunk
     * @param {Object} settings - settings for Editor initialization
     * @return {Promise<Editor>} - CodeX Editor promise
     */
    loadEditor(settings) {

        return import(/* webpackChunkName: "editor" */ 'modules/editor')
            .then(({default: Editor}) => {

                return new Editor(settings);

            });

    };

    /**
     * Initialize EditorJS instance
     * @param {Object} settings - writing module settings
     * @param {string} settings.holderId - id of editor's holder
     * @param {string} settings.formId - id of form with editor's content
     * @param {string} settings.initializeWithTools - whether to hide or show editor's toolbar
     */
    init(settings) {

        this.form = document.getElementById(settings.formId);

        const editorSettings = {
            holder: document.getElementById(settings.holderId),
            blocks: this.getPageBlocks(),
            initializeWithTools: !settings.initializeWithTools
        };

        this.loadEditor(editorSettings).then((editor) => {

            this.editor = editor;

        });

        if (!this.form) {

            console.warn(`Form with id «${settings.formId}» not found`);

        }

    }

    /**
     * Get page's blocks
     * @return {Array} pageBlocks - page's blocks[] data
     */
    getPageBlocks() {

        /** Page's content from form */
        const formValue = this.form.elements['content'].getAttribute('value');

        /** Page's bocks */
        let pageBlocks = [];

        if (formValue) {

            /** Get content that was written before */
            try {

                pageBlocks = JSON.parse(formValue).blocks;

            } catch (error) {

                console.error('Errors occurred while parsing Editor data:', error.message);

            }

        }

        return pageBlocks;

    }

    /**
     * Open small version of Editor on main: hide Editor's wrapper and reveal it's contents
     * @param openSettings
     * @param {HTMLElement} openSettings.wrapper - element being clicked to reveal editor
     * @param {string} openSettings.holderId - editor's contents initially hidden
     * @param {string} openSettings.wrapperOpenedClass - class to hide writing holder
     */
    open(openSettings) {

        if (!this.editor) {

            return;

        }

        const writingWrapper = openSettings.wrapper;
        const writingHolder = document.getElementById(openSettings.holderId);

        writingHolder.classList.remove('hide');
        writingWrapper.classList.add(openSettings.wrapperOpenedClass);
        writingWrapper.onclick = null;

    }

    /**
     * Send form's data via ajax
     * @param {HTMLElement} button - submission button clicked
     */
    submitForm(button) {

        button.classList.add('loading');

        this.editor.save()
            .then((savedData) => {

                this.form.elements['content'].value = JSON.stringify(savedData);

                /**
                 * Send article data via ajax
                 */
                window.setTimeout(() => {

                    ajax.post({
                        url: '/p/save',
                        data: this.form
                    }).then((response) => {

                        const {body} = response;

                        if (body.success) {

                            window.location.href = body.redirect;

                        }

                    }).catch((error) => {

                        this.showErrorMessage(error);
                        button.classList.remove('loading');

                    });

                }, 500);

            });

    }

    /**
     * If page's form submission via ajax failed show message with error text
     * @param {string} error - form submission error message
     */
    showErrorMessage(error) {

        notifier.show({
            message: error.message,
            style: 'error'
        });

    }

    /**
     * Open Editor in fullscreen mode with toolbar
     */
    openFullScreen() {

        this.editor.save()
            .then((savedData) => {

                this.form.elements['content'].value = JSON.stringify(savedData);
                this.form.submit();

            });

    }

}

module.exports = new Writing();
