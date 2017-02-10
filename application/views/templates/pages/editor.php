<!-- editot start -->

<textarea hidden name="html" id="codex_editor" cols="30" rows="10" style="width: 100%;height: 300px;"></textarea>
<textarea hidden name="content" id="json_result" cols="30" rows="10" style="width: 100%;height: 300px;"></textarea>


<!-- Developers plugin -->

<script src="/public/extensions/codex.editor/plugins/paragraph/paragraph.js?<?= filemtime('public/extensions/codex.editor/plugins/paragraph/paragraph.js'); ?>"></script>
<link rel="stylesheet" href="/public/extensions/codex.editor/plugins/paragraph/paragraph.css?<?= filemtime('public/extensions/codex.editor/plugins/paragraph/paragraph.css'); ?>">

<script src="/public/extensions/codex.editor/plugins/header/header.js?<?= filemtime('public/extensions/codex.editor/plugins/header/header.js'); ?>"></script>
<link rel="stylesheet" href="/public/extensions/codex.editor/plugins/header/header.css?<?= filemtime('public/extensions/codex.editor/plugins/header/header.css'); ?>">

<script src="/public/extensions/codex.editor/plugins/paste/paste.js?<?= filemtime('public/extensions/codex.editor/plugins/paste/paste.js'); ?>"></script>
<script src="/public/extensions/codex.editor/plugins/paste/patterns.js?<?= filemtime('public/extensions/codex.editor/plugins/paste/patterns.js'); ?>"></script>
<link rel="stylesheet" href="/public/extensions/codex.editor/plugins/paste/paste.css?<?= filemtime('public/extensions/codex.editor/plugins/paste/paste.css'); ?>">

<!-- Editor scripts and styles -->
<script src="/public/extensions/codex.editor/codex-editor.js?v=?<?=filemtime('public/extensions/codex.editor/codex-editor.js'); ?>"></script>
<link rel="stylesheet" href="/public/extensions/codex.editor/codex-editor.css?v=<?=filemtime('public/extensions/codex.editor/codex-editor.css'); ?>" />

<script>

    /** Document is ready */
    codex.docReady(function(){

        codex.editor.start({

            textareaId: 'codex_editor',

            initialBlockPlugin : 'paragraph',

            tools : {
                paragraph: {
                    type: 'paragraph',
                    iconClassname: 'ce-icon-paragraph',
                    render: paragraph.render,
                    validate: paragraph.validate,
                    save: paragraph.save,
                    allowedToPaste: true,
                    showInlineToolbar: true
                },
                heading_styled: {
                    type: 'heading_styled',
                    iconClassname: 'ce-icon-header',
                    appendCallback: header.appendCallback,
                    makeSettings: header.makeSettings,
                    render: header.render,
                    validate: header.validate,
                    save: header.save,
                    displayInToolbox: true
                },
                paste: {
                    type: 'paste',
                    prepare: paste.prepare,
                    make: paste.make,
                    save: paste.save,
                    enableLineBreaks: false,
                    callbacks: paste.pasted
                },
            },

            data : INPUT,
        });
    });


    /**
     * Redactor input
     */
    var INPUT = {
        items : <?= json_encode($page->blocks) ?: '[]' ?> ,
    };

</script>

<style>

.ce-redactor{
    padding-top: 50px;
}

</style>

<!-- editor end -->
