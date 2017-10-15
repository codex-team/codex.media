<? /** add form for new page */ ?>
<? if ($user->id): ?>

    <?= View::factory('templates/pages/form_wrapper', array(
        'hideEditorToolbar' => true
    )); ?>

<? endif ?>
<? /***/ ?>

<div class="island tabs">
    <a class="tabs__tab <?= $active_tab == Model_Feed_Pages::MAIN ? 'tabs__tab--current' : '' ?>" href="/<?= Model_Feed_Pages::MAIN ?>">
        Главное
    </a>
    <a class="tabs__tab <?= $active_tab == Model_Feed_Pages::TEACHERS ? 'tabs__tab--current' : '' ?>" href="/<?= Model_Feed_Pages::TEACHERS ?>">
        Блоги преподавателей
    </a>
    <a class="tabs__tab <?= $active_tab == Model_Feed_Pages::ALL ? 'tabs__tab--current' : '' ?>" href="/<?= Model_Feed_Pages::ALL ?>">
        Все записи
    </a>
</div>

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
        Показать больше записей
    </a>
   <!--  <script>
        window.docReady(function() {
            codex.appender.init({
                buttonId           : 'buttonLoadNews',
                currentPage        : '<?= $page_number ?>',
                url                : '<?= $active_tab ? "/".$active_tab."/" : "/" ?>',
                targetBlockId      : 'list_of_news',
                autoLoading        : true,
                dontWaitFirstClick : true,
            });
        });
    </script> -->

    <div module="appender">
        <module-settings>
            {
                "buttonId" : "buttonLoadNews",
                "currentPage" : "<?= $page_number ?>",
                "url",          : "<?= $active_tab ? "/".$active_tab."/" : "/" ?>",
                "targetBlockId" : "list_of_news",
                "autoLoading": "true",
                "dontWaitFirstClick": "true"
            }
        </module-settings>
    </div>
<? endif ?>
