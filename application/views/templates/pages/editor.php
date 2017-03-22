<div class="editor-wrapper">
    <div id="codex_editor">

    </div>
    <textarea hidden name="html" id="" cols="30" rows="10" style="width: 100%;height: 300px;"></textarea>
    <textarea hidden name="content" id="json_result" cols="30" rows="10" style="width: 100%;height: 300px;"></textarea>
</div>

<script src="https://cdn.ifmo.su/editor/v1.6/codex-editor.js"></script>
<link rel="stylesheet" href="https://cdn.ifmo.su/editor/v1.5/codex-editor.css" />

<script src="https://cdn.ifmo.su/editor/v1.6/plugins/paragraph/paragraph.js"></script>
<link rel="stylesheet" href="https://cdn.ifmo.su/editor/v1.5/plugins/paragraph/paragraph.css">

<script src="https://cdn.ifmo.su/editor/v1.6/plugins/header/header.js"></script>
<link rel="stylesheet" href="https://cdn.ifmo.su/editor/v1.5/plugins/header/header.css">

<script>

    /** Document is ready */
    codex.docReady(function(){

        codex.editor.start({

            holderId: 'codex_editor',
            initialBlockPlugin : 'paragraph',
            hideToolbar: <?= !empty($hideEditorToolbar) && $hideEditorToolbar ? 'true' : 'false' ?>,
            sanitizer : {
                tags: {
                    p: {},
                    a: {
                        href: true,
                        target: '_blank',
                        rel: 'nofollow'
                    }
                }
            },
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

            data :<?= $page->content ?>
        });
    });

</script>