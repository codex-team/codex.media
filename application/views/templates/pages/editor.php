<!-- editot start -->

<textarea hidden name="html" id="codex_editor" cols="30" rows="10" style="width: 100%;height: 300px;"></textarea>
<textarea hidden name="content" id="json_result" cols="30" rows="10" style="width: 100%;height: 300px;"></textarea>


<!-- Developers plugin -->
<script src="/public/extensions/codex.editor/plugins/header/header.js?v=<?=filemtime('public/extensions/codex.editor/plugins/header/header.js'); ?>"></script>
<link rel="stylesheet" href="/public/extensions/codex.editor/plugins/header/header.css">

<script src="/public/extensions/codex.editor/plugins/paragraph/paragraph.js?v=<?=filemtime('public/extensions/codex.editor/plugins/paragraph/paragraph.js'); ?>"></script>
<link rel="stylesheet" href="/public/extensions/codex.editor/plugins/paragraph/paragraph.css">

<script src="/public/extensions/codex.editor/plugins/paste/paste.js?v=<?=filemtime('public/extensions/codex.editor/plugins/code/code.js'); ?>"></script>
<link rel="stylesheet" href="/public/extensions/codex.editor/plugins/paste/paste.css">

<script src="/public/extensions/codex.editor/plugins/code/code.js?v=<?=filemtime('public/extensions/codex.editor/plugins/code/code.js'); ?>"></script>
<link rel="stylesheet" href="/public/extensions/codex.editor/plugins/code/code.css">

<script src="/public/extensions/codex.editor/plugins/link/link.js?v=<?=filemtime('public/extensions/codex.editor/plugins/link/link.js'); ?>"></script>
<link rel="stylesheet" href="/public/extensions/codex.editor/plugins/link/link.css">

<script src="/public/extensions/codex.editor/plugins/list/list.js?v=<?=filemtime('public/extensions/codex.editor/plugins/list/list.js'); ?>"></script>
<link rel="stylesheet" href="/public/extensions/codex.editor/plugins/list/list.css">

<script src="/public/extensions/codex.editor/plugins/image/image.js?v=<?=filemtime('public/extensions/codex.editor/plugins/image/image.js'); ?>"></script>
<link rel="stylesheet" href="/public/extensions/codex.editor/plugins/image/image.css">

<script src="/public/extensions/codex.editor/plugins/quote/quote.js?v=<?=filemtime('public/extensions/codex.editor/plugins/quote/quote.js'); ?>"></script>
<link rel="stylesheet" href="/public/extensions/codex.editor/plugins/quote/quote.css">

<script src="/public/extensions/codex.editor/plugins/instagram/instagram.js?v=<?=filemtime('public/extensions/codex.editor/plugins/instagram/instagram.js'); ?>"></script>
<link rel="stylesheet" href="/public/extensions/codex.editor/plugins/instagram/instagram.css">

<script src="/public/extensions/codex.editor/plugins/twitter/twitter.js?v=<?=filemtime('public/extensions/codex.editor/plugins/twitter/twitter.js'); ?>"></script>
<link rel="stylesheet" href="/public/extensions/codex.editor/plugins/twitter/twitter.css">

<!-- Editor scripts and styles -->
<script src="/public/extensions/codex.editor/codex-editor.js?v=?<?=filemtime('public/extensions/codex.editor/codex-editor.js'); ?>"></script>
<link rel="stylesheet" href="/public/extensions/codex.editor/codex-editor.css?v=<?=filemtime('public/extensions/codex.editor/codex-editor.css'); ?>" />

<script>

    /** Document is ready */
    codex.documentIsReady(function(){

        codex.editor.start({

            textareaId: 'codex_editor',

            tools : {
                paragraph : {
                    type             : 'paragraph',
                    iconClassname    : 'ce-icon-paragraph',
                    make             : paragraphTool.make,
                    appendCallback   : null,
                    settings         : null,
                    render           : paragraphTool.render,
                    save             : paragraphTool.save,
                    displayInToolbox : false,
                    enableLineBreaks : false,
                    allowedToPaste   : true
                },
                paste : {
                    type             : 'paste',
                    iconClassname    : '',
                    prepare          : pasteTool.prepare,
                    make             : pasteTool.make,
                    appendCallback   : null,
                    settings         : null,
                    render           : null,
                    save             : pasteTool.save,
                    displayInToolbox : false,
                    enableLineBreaks : false,
                    callbacks        : pasteTool.callbacks,
                    allowedToPaste   : false
                },
                header : {
                    type             : 'header',
                    iconClassname    : 'ce-icon-header',
                    make             : headerTool.make,
                    appendCallback   : headerTool.appendCallback,
                    settings         : headerTool.makeSettings(),
                    render           : headerTool.render,
                    save             : headerTool.save
                },
                code : {
                    type             : 'code',
                    iconClassname    : 'ce-icon-code',
                    make             : codeTool.make,
                    appendCallback   : null,
                    settings         : null,
                    render           : codeTool.render,
                    save             : codeTool.save,
                    displayInToolbox : true,
                    enableLineBreaks : true
                },
                link : {
                    type             : 'link',
                    iconClassname    : 'ce-icon-link',
                    make             : linkTool.makeNewBlock,
                    appendCallback   : linkTool.appendCallback,
                    render           : linkTool.render,
                    save             : linkTool.save,
                    displayInToolbox : true,
                    enableLineBreaks : true
                },
                list : {
                    type             : 'list',
                    iconClassname    : 'ce-icon-list-bullet',
                    make             : listTool.make,
                    appendCallback   : null,
                    settings         : listTool.makeSettings(),
                    render           : listTool.render,
                    save             : listTool.save,
                    displayInToolbox : true,
                    enableLineBreaks : true
                },
                quote : {
                    type             : 'quote',
                    iconClassname    : 'ce-icon-quote',
                    make             : quoteTools.makeBlockToAppend,
                    appendCallback   : null,
                    settings         : quoteTools.makeSettings(),
                    render           : quoteTools.render,
                    save             : quoteTools.save,
                    displayInToolbox : true,
                    enableLineBreaks : true,
                    allowedToPaste   : true
                },
                image : {
                    type             : 'image',
                    iconClassname    : 'ce-icon-picture',
                    make             : ceImage.make,
                    appendCallback   : ceImage.appendCallback,
                    settings         : ceImage.makeSettings(),
                    render           : ceImage.render,
                    save             : ceImage.save,
                    isStretched      : true,
                    displayInToolbox : true,
                    enableLineBreaks : false
                },
                instagram : {
                    type             : 'instagram',
                    iconClassname    : 'ce-icon-instagram',
                    prepare          : instagramTool.prepare,
                    make             : instagramTool.make,
                    appendCallback   : null,
                    settings         : null,
                    render           : instagramTool.reneder,
                    save             : instagramTool.save,
                    displayInToolbox : false,
                    enableLineBreaks : false,
                    allowedToPaste   : false
                },
                twitter : {
                    type             : 'twitter',
                    iconClassname    : 'ce-icon-twitter',
                    prepare          : twitterTool.prepare,
                    make             : twitterTool.make,
                    appendCallback   : null,
                    settings         : null,
                    render           : twitterTool.render,
                    save             : twitterTool.save,
                    displayInToolbox : false,
                    enableLineBreaks : false,
                    allowedToPaste   : false
                }
            },

            data : INPUT
        });

    });


/**
 * Redactor input
 */
var INPUT = {
    items : <?= json_encode($page->blocks) ?: '[]' ?> ,
    count : 0,
};

</script>

<style>

.ce-redactor{
    padding-top: 50px;
}

</style>

<!-- editor end -->
