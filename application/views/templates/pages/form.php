<form class="writing island island--bottom-rounded" action="/p/writing" id="atlasForm" method="post" name="atlas">

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
        <textarea class="writing__title js-autoresizable" rows="1" name="title" placeholder="Заголовок" id="editorWritingTitle"><?= $page->title ?></textarea>
    </div>

    <div class="editor-wrapper" id="placeForEditor"></div>

    <div class="writing__actions">

        <div class="writing__actions-content">

            <?
                $newsFeedKey = Model_Feed_Pages::TYPE_NEWS;

                $fromIndexPage   = !empty(Request::$current) && Request::$current->controller() == 'Index';
                $fromNewsTab     = Request::$current->param('feed_key', $newsFeedKey) == $newsFeedKey;
                $fromUserProfile = Request::$current->controller() == 'User_Index';

                $isNews = $page->isNewsPage || ($fromIndexPage && $fromNewsTab);
            ?>

            <span class="button master" onclick="codex.writing.submit(this)">Отправить</span>

            <? if ($user->isAdmin() && !$fromUserProfile): ?>
                <span name="cdx-custom-checkbox" class="writing__is-news" data-name="isNews" data-checked="<?= $isNews ?>">
                    Новость
                </span>
            <? endif; ?>

            <? if (!empty($hideEditorToolbar) && $hideEditorToolbar): ?>
                <span class="writing-fullscreen__button" onclick="codex.writing.openEditorFullscreen()">
                    <? include(DOCROOT . 'public/app/svg/zoom.svg') ?>
                    <span class="writing-fullscreen__text">На весь экран</span>
                </span>
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

        var plugins = ['paragraph', 'header', 'image', 'attaches', 'list'],
            scriptPath = 'https://cdn.ifmo.su/editor/v1.6/',
            settings = {
                holderId          : 'placeForEditor',
                pageId            : <?= $page->id ?>,
                parentId          : <?= $page->id_parent ?>,
                hideEditorToolbar : <?= $hideEditorToolbar ? 'true' : 'false' ?>,
                data              : <?= $page->content ?: '[]' ?>,
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

        <? if (!$hideEditorToolbar): ?>
            editorReady.then(codex.writing.init);
        <? endif ?>
    });
</script>
