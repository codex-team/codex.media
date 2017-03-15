<? /** add form for new page */ ?>
<? if ($user->id): ?>

    <?= View::factory('templates/pages/form', array(
        'hideEditorToolbar' => true
    )); ?>

<? endif ?>
<? /***/ ?>

<ul class="island tabs">
    <li>
        <a class="tabs__tab <?= $active_tab == Model_Page::FEED_KEY_NEWS ? 'tabs__tab--current' : '' ?>" href="/<?= Model_Page::FEED_KEY_NEWS ?>">
            Новости
        </a>
    </li>
    <li>
        <a class="tabs__tab <?= $active_tab == Model_Page::FEED_KEY_TEACHERS_BLOGS ? 'tabs__tab--current' : '' ?>" href="/<?= Model_Page::FEED_KEY_TEACHERS_BLOGS ?>">
            Блоги преподавателей
        </a>
    </li>
    <li>
        <a class="tabs__tab <?= $active_tab == Model_Page::FEED_KEY_BLOGS ? 'tabs__tab--current' : '' ?>" href="/<?= Model_Page::FEED_KEY_BLOGS ?>">
            Все записи
        </a>
    </li>
</ul>

<? /** pages list */ ?>
<div id="list_of_news" class="post-list">
    <? $emptyListMessage = $user->id ? 'Добавьте новую запись с помощью формы вверху страницы' : 'Здесь появятся новости и интересные публикации' ?>
    <?= View::factory('templates/posts_list', array(
        'pages' => $pages,
        'emptyListMessage' => $emptyListMessage
    )); ?>
</div>

<? if ($next_page): ?>
    <a class="load_more_button" id="buttonLoadNews" href="/<?= $page_number + 1 ?>">
        <div class="island island--padded island--centered island--stretched">
            Показать больше новостей
        </div>
    </a>
    <script>
        codex.docReady(function() {
            codex.appender.init({
                buttonId      : 'buttonLoadNews',
                currentPage   : '<?= $page_number ?>',
                url           : '<?= $active_tab ? "/".$active_tab."/" : "/" ?>',
                targetBlockId : 'list_of_news',
                autoLoading   : true,
            });
        });
    </script>
<? endif ?>
