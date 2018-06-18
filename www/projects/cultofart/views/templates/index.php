<?/** add form for new page */ ?>
<?= View::factory('templates/pages/description_block')->render(); ?>

<? if ($user->id): ?>

    <?= View::factory('templates/pages/form_wrapper', [
        'hideEditorToolbar' => true
    ]); ?>

<? endif ?>
<? /***/ ?>

<div class="island island--squared tabs">
    <a class="tabs__tab tabs__tab--bolder <?= $active_tab == Model_Feed_Pages::MAIN ? 'tabs__tab--current tabs__tab--red' : '' ?>" href="/<?= Model_Feed_Pages::MAIN ?>">
        Главное
    </a>
    <a class="tabs__tab tabs__tab--bolder <?= $active_tab == Model_Feed_Pages::TEACHERS ? 'tabs__tab--current tabs__tab--red' : '' ?>" href="/<?= Model_Feed_Pages::TEACHERS ?>">
        Блоги преподавателей
    </a>
    <a class="tabs__tab tabs__tab--bolder <?= $active_tab == Model_Feed_Pages::ALL ? 'tabs__tab--current tabs__tab--red' : '' ?>" href="/<?= Model_Feed_Pages::ALL ?>">
        Все записи
    </a>
</div>

<?/** pages list */ ?>
<div id="list_of_news" class="post-list">
    <? $emptyListMessage = $user->id ? 'Добавьте новую запись с помощью формы вверху страницы' : 'Здесь появятся новости и интересные публикации' ?>
    <?= View::factory('templates/pages/list', [
        'pages' => $pages,
        'emptyListMessage' => $emptyListMessage,
        'active_tab' => $active_tab
    ]); ?>
</div>

<?= View::factory('templates/components/events_block')->render(); ?>

<? if ($next_page): ?>
    <a class="button button--load-more island island--squared island--padded island--centered island--stretched" href="/<?= $page_number + 1 ?>" data-module="appender">
        Показать больше записей
        <module-settings hidden>
            {
                "currentPage" : "<?= $page_number ?>",
                "url" : "<?= $active_tab ? "/" . $active_tab . "/" : "/" ?>",
                "targetBlockId" : "list_of_news",
                "autoLoading" : "true",
                "dontWaitFirstClick" : "true"
            }
        </module-settings>
    </a>
<? endif ?>
