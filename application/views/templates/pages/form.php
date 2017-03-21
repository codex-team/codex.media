<form class="writing island" action="/p/writing" id="atlasForm" method="post" name="atlas">

    <?
        /** if there is no information about page */
        if (!isset($page)) {
            $page = new Model_Page();
        }

        /** Name of object's type in genitive declension */
        // $object_name = $page->is_news_page ? 'новости' : 'страницы';
    ?>

    <?= Form::hidden('csrf', Security::token()); ?>
    <?= Form::hidden('id', $page->id); ?>
    <?= Form::hidden('id_parent', $page->id_parent); ?>

    <div class="writing__title-wrapper">
        <input class="writing__title" type="text" name="title" placeholder="Заголовок" value="<?= $page->title ?>" id="editorWritingTitle">
    </div>

    <div class="editor-wrapper" id="placeForEditor"></div>

    <div class="writing__actions clear">
        <div class="writing__actions-content">

            <span class="button master fl_r" onclick="codex.writing.submit()">Отправить</span>

            <? if (!empty($hideEditorToolbar) && $hideEditorToolbar): ?>
                <span class="button fl_r" onclick="codex.writing.openEditorFullscreen()">На весь экран</span>
            <? endif ?>

        </div>

    </div>

</form>


<?
    $hideEditorToolbar = !empty($hideEditorToolbar) && $hideEditorToolbar;
?>
<script>

    /** Document is ready */
    codex.docReady(function(){

        var plugins = ['paragraph', 'header', 'image', 'attaches'],
            scriptPath = 'https://cdn.ifmo.su/editor/v1.6/',
            settings = {
                holderId          : 'placeForEditor',
                pageId            : <?= $page->id ?>,
                parentId          : <?= $page->id_parent ?>,
                hideEditorToolbar : <?= $hideEditorToolbar ? 'true' : 'false' ?>,
                items             : <?= json_encode($page->blocks) ?: '[]' ?>,
                resources         : []
            };

        // scriptPath = '/public/extensions/codex.editor/'

        settings.resources.push({
            name : 'codex-editor',
            path : {
                script : scriptPath + 'codex-editor.js',
                style  : scriptPath + 'codex-editor.css',
            }
        });

        for (var i = 0, plugin; !!(plugin = plugins[i]); i++) {
            settings.resources.push({
                name : plugin,
                path : {
                    script : scriptPath + 'plugins/' + plugin + '/' + plugin + '.js',
                    style  : scriptPath + 'plugins/' + plugin + '/' + plugin + '.css',
                }
            });
        }

        var editorReady = codex.writing.prepare(settings);

        <? if (!$hideEditorToolbar): ?>;
            editorReady.then(codex.writing.init);
        <? endif ?>
    });

</script>
