<? /** add form for new page */ ?>
<? if ($user->id): ?>

    <?= View::factory('templates/pages/form'); ?>

<? endif ?>
<? /***/ ?>

<ul class="island tabs island--margined">
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
<? if ($pages): ?>

    <div id="list_of_news" class="news">
        <?= View::factory('templates/posts_list', array( 'pages'=> $pages)); ?>
    </div>

    <? if ($next_page): ?>
        <a class="load_more_button w_island" id="button_load_news" href="/<?= $page_number + 1 ?>">Показать больше новостей</a>
        <script>
            codex.documentIsReady(function() {
                codex.appender.init({
                    button_id       : 'button_load_news',
                    current_page    : '<?= $page_number ?>',
                    url             : '<?= $active_tab ? "/".$active_tab."/" : "/" ?>',
                    target_block_id : 'list_of_news',
                    auto_loading    : true,
                });
            });
        </script>
    <? endif ?>

<? else: ?>
    <div class="w_island w_island_centercol">
        <div class="empty_motivatior">
            <i class="icon_noarticles"></i><br/>
            <? if ($user->id): ?>
                Добавьте новую запись с помощью формы вверху страницы
            <? else: ?>
                Здесь появятся новости и интересные публикации
            <? endif ?>
        </div>
    </div>
<? endif ?>
<? /***/ ?>
