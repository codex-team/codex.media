<form class="writing island island--bottom-rounded" action="/p/writing" id="atlasForm" method="post" name="atlas">

    <?
    /** if there is no information about page */
    if (!isset($page)) {
        $page = new Model_Page();
    }

    /** Name of object's type in genitive declension */
    // $object_name = $page->is_news_page ? 'новости' : 'страницы';

    $fromIndexPage = !empty(Request::$current) && Request::$current->controller() == 'Index';
    $fromNewsTab = Request::$current->param('feed_key', Model_Feed_Pages::MAIN) == Model_Feed_Pages::MAIN;
    $fromEventsTab = Request::$current->param('feed_key', Model_Feed_Pages::EVENTS) == Model_Feed_Pages::EVENTS;
    $fromUserProfile = Request::$current->controller() == 'User_Index';
    $fromCommunity = !empty($community_parent_id) && $community_parent_id != 0;

    /**
     * Page options depending on page type
     */
    $vkPost = $page->isPostedInVK;
    $isPaid = isset($page->options['is_paid']) ? $page->options['is_paid'] : 0;
    $eventDate = isset($page->options['event_date']) ? $page->options['event_date'] : '';
    $shortDescription = isset($page->options['short_description']) ? $page->options['short_description'] : '';

    if ($user->isAdmin() && $fromIndexPage && $fromNewsTab) {
        $selectedPageType = Model_Page::NEWS;
    } elseif ($user->isAdmin() && $fromIndexPage && $fromEventsTab) {
        $selectedPageType = Model_Page::EVENT;
    } elseif (!$user->isAdmin() && $fromIndexPage || !$user->isAdmin() && $fromCommunity || $fromUserProfile) {
        $selectedPageType = Model_Page::BLOG;
    } else {
        $selectedPageType = 0;
    }
    ?>

    <?= Form::hidden('csrf', Security::token()); ?>
    <?= Form::hidden('id', $page->id); ?>
    <?= Form::hidden('id_parent', $page->id_parent); ?>
    <?= Form::hidden('content', !empty($page->content) ? $page->content : ''); ?>

    <?= View::factory('templates/pages/form_type_selector', [
        'page' => $page,
        'selectedPageType' => $selectedPageType
    ]); ?>

    <div class="writing__title-wrapper">
        <textarea class="writing__title js-autoresizable" rows="1" name="title" placeholder="Заголовок" id="editorWritingTitle"><?= $page->title ?></textarea>
    </div>

    <div class="editor-wrapper" id="placeForEditor"></div>

    <div class="js-page-options">
        <div class="js-page-options__item hide" data-page-type="<?= Model_Page::COMMUNITY ?>">
            <input name="short_description" class="community-description" type="text" placeholder="Краткое описание" value="<?= $shortDescription ?>" autocomplete="off">
        </div>
        <div class="js-page-options__item hide" data-page-type="<?= Model_Page::NEWS ?>">
            <span name="cdx-custom-checkbox" data-name="vkPost" data-checked="<?= $vkPost ?>" title="Опубликовать на стене сообщества">
                Опубликовать ВКонтакте
            </span>
        </div>
        <div class="js-page-options__item hide" data-page-type="<?= Model_Page::EVENT ?>">
            <input type="text" class="js-event-date event-date" placeholder="Выбрать дату" id="event_date" name="event_date" value="<?= $eventDate ?>" autocomplete="off">
            <label for="event_date">
                <? include(DOCROOT . 'public/app/svg/calendar-icon.svg'); ?>
            </label>
            <span name="cdx-custom-checkbox" class="event-is-paid" data-name="is_paid" data-checked="<?= $isPaid ?>" title="">
                Платное
            </span>
        </div>
    </div>

    <div class="writing__actions">

        <div class="writing__actions-content">

            <span class="button master" onclick="codex.writing.submit(this)">
                <? if ($page->id): ?>
                    Сохранить
                <? else: ?>
                    Опубликовать
                <? endif; ?>
            </span>

            <? if (!empty($hideEditorToolbar) && $hideEditorToolbar): ?>
                <span class="writing-fullscreen__button" onclick="codex.writing.openEditorFullscreen()">
                    <? include(DOCROOT . 'public/app/svg/zoom.svg') ?>
                    <span class="writing-fullscreen__text">На весь экран</span>
                </span>
            <? endif ?>

        </div>

    </div>

    <? if (($user->isAdmin() && $fromUserProfile) || isset($isPersonalBlog)): ?>
        <?= Form::hidden('isPersonalBlog', isset($isPersonalBlog) ? $isPersonalBlog : '1'); ?>
    <? endif; ?>

    <? if ($fromCommunity): ?>
        <?= Form::hidden('id_parent', $community_parent_id); ?>
    <? endif; ?>

</form>


<?
    $hideEditorToolbar = !empty($hideEditorToolbar) && $hideEditorToolbar;
?>
<script>

    /** Document is ready */
    codex.docReady(function(){

        var plugins = ['paragraph', 'header', 'image', 'attaches', 'list', 'raw'],
            scriptPath = 'https://cdn.ifmo.su/editor/v1.6/',
            settings = {
                holderId          : 'placeForEditor',
                pageId            : <?= $page->id ?>,
                parentId          : <?= $page->id_parent ?>,
                hideEditorToolbar : <?= $hideEditorToolbar ? 'true' : 'false' ?>,
                data              : <?= $page->content ?: '[]' ?>,
                resources         : []
            };

        settings.resources.push({
            name : 'codex-editor',
            path : {
                script : scriptPath + 'codex-editor.js',
                style  : scriptPath + 'codex-editor.css'
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
