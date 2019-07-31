/**
 * Module for load and start codex-editor
 *
 * Using:
 *
 * codex.writing.prepare({
 *     holderId : 'placeForEditor',                                         // (required)
 *     hideEditorToolbar : <?= $hideEditorToolbar ? 'true' : 'false' ?>,
 *     items : <?= json_encode($page->blocks) ?: '[]' ?>,
 *     pageId   : <?= $page->id ?>,
 *     parentId : <?= $page->id_parent ?>,
 * }).then(
 *    codex.writing.init
 * );
 */

const ajax = require('@codexteam/ajax');

class Writing {

    constructor() {

        /**
         * Editor class Instance
         */
        this.editor = null;

        /**
         * DOM elements
         */
        this.nodes = {
            /**
             * Container to output saved Editor data
             */
            outputWrapper: null
        };

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

    init(settings) {

        const editorSettings = {
            holder: document.getElementById(settings.holderId),
            blocks: settings.blocks,
            initializeWithTools: !settings.initializeWithTools
        };

        this.loadEditor(editorSettings).then((editor) => {

            this.editor = editor;

        });

    }

    open(openSettings) {

        if (!this.editor) {

            return;

        }

        const holder = openSettings.targetClicked;

        document.getElementById(openSettings.formId).classList.remove('hide');
        holder.classList.add(openSettings.hidePlaceholderClass);
        holder.onclick = null;

    }

    openFullScreen() {

        this.form = document.forms.atlas;

        /**
         * Call Editor's save method
         */
        this.editor.save()
            .then((savedData) => {

                console.log(savedData);
                /**
                 * Send article data via ajax
                 */
                window.setTimeout(function () {

                    ajax.post({
                        url: '/p/save',
                        data: this.form
                    });

                }, 500);

            });

    }

}

module.exports = new Writing();
