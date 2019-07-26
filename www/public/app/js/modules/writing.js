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


export class Writing {

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

}
