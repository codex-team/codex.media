<div class="editor-wrapper">
    <textarea hidden name="html" id="codex_editor" cols="30" rows="10" style="width: 100%;height: 300px;"></textarea>
    <textarea hidden name="content" id="json_result" cols="30" rows="10" style="width: 100%;height: 300px;"></textarea>
</div>

<script src="https://cdn.ifmo.su/editor/v1.5/codex-editor.js"></script>
<link rel="stylesheet" href="https://cdn.ifmo.su/editor/v1.5/codex-editor.css" />

<script src="https://cdn.ifmo.su/editor/v1.5/plugins/paragraph/paragraph.js"></script>
<link rel="stylesheet" href="https://cdn.ifmo.su/editor/v1.5/plugins/paragraph/paragraph.css">

<script src="https://cdn.ifmo.su/editor/v1.5/plugins/header/header.js"></script>
<link rel="stylesheet" href="https://cdn.ifmo.su/editor/v1.5/plugins/header/header.css">

<script>

    /** Document is ready */
    codex.docReady(function(){

        codex.editor.start({

            textareaId: 'codex_editor',

            initialBlockPlugin : 'paragraph',

            hideToolbar: <?= !empty($hideEditorToolbar) && $hideEditorToolbar ? 'true' : 'false' ?>,

            tools : {
                paragraph: {
                    type: 'paragraph',
                    iconClassname: 'ce-icon-paragraph',
                    render: paragraph.render,
                    validate: paragraph.validate,
                    save: paragraph.save,
                    allowedToPaste: true,
                    showInlineToolbar: true,
                    destroy: paragraph.destroy,
                    allowRenderOnPaste: true
                },
                heading_styled: {
                    type: 'heading_styled',
                    iconClassname: 'ce-icon-header',
                    appendCallback: header.appendCallback,
                    makeSettings: header.makeSettings,
                    render: header.render,
                    validate: header.validate,
                    save: header.save,
                    destroy: header.destroy,
                    displayInToolbox: true
                }
            },

            data : {
                items : <?= json_encode($page->blocks) ?: '[]' ?> 
            }
        });
    });

</script>