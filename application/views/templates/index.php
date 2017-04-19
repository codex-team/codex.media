<? /** add form for new page */ ?>
<? if ($user->id): ?>

    <?= View::factory('templates/pages/form_wrapper', array(
        'hideEditorToolbar' => true
    )); ?>

<? endif ?>
<? /***/ ?>

<ul class="island tabs">
    <li>
        <a class="tabs__tab <?= $active_tab == Model_Feed_Pages::TYPE_NEWS ? 'tabs__tab--current' : '' ?>" href="/<?= Model_Feed_Pages::TYPE_NEWS ?>">
            Новости
        </a>
    </li>
    <li>
        <a class="tabs__tab <?= $active_tab == Model_Feed_Pages::TYPE_TEACHERS ? 'tabs__tab--current' : '' ?>" href="/<?= Model_Feed_Pages::TYPE_TEACHERS ?>">
            Блоги преподавателей
        </a>
    </li>
    <li>
        <a class="tabs__tab <?= $active_tab == Model_Feed_Pages::TYPE_ALL ? 'tabs__tab--current' : '' ?>" href="/<?= Model_Feed_Pages::TYPE_ALL ?>">
            Все записи
        </a>
    </li>
</ul>

<? /** pages list */ ?>
<div id="list_of_news" class="post-list">
    <? $emptyListMessage = $user->id ? 'Добавьте новую запись с помощью формы вверху страницы' : 'Здесь появятся новости и интересные публикации' ?>
    <?= View::factory('templates/pages/list', array(
        'pages' => $pages,
        'emptyListMessage' => $emptyListMessage,
        'active_tab' => $active_tab
    )); ?>
</div>

<? if ($next_page): ?>
    <a class="button button--load-more island island--padded island--centered island--stretched" id="buttonLoadNews" href="/<?= $page_number + 1 ?>">
        Показать больше новостей
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
