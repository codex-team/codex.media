<? /** add form for new page */ ?>
<? if ($user->id): ?>

    <?= View::factory('templates/pages/form'); ?>

    <style>
        .ce-redactor {
            padding-top: 40px;
            min-height: 200px;
            padding-bottom: 40px;
        }
    </style>

<? endif ?>
<? /***/ ?>


<? /** menu for pages lists */ ?>
<div class="list_users_heading">
    <ul class="page_menu">
        <li><?= $feed_key != Model_Page::FEED_KEY_NEWS && $feed_key ?
            '<a href="/'.Model_Page::FEED_KEY_NEWS.'">Новости</a></li>' : 'Новости' ?></li>

        <li><?= $feed_key != Model_Page::FEED_KEY_TEACHERS_BLOGS ?
            '<a href="/'.Model_Page::FEED_KEY_TEACHERS_BLOGS.'">Блоги</a>' : 'Блоги' ?></li>

        <li><?= $feed_key != Model_Page::FEED_KEY_BLOGS ?
            '<a href="/'.Model_Page::FEED_KEY_BLOGS.'">Все записи</a>' : 'Все записи' ?></li>


    </ul>
</div>
<? /***/ ?>


<? /** pages list */ ?>
<? if ($pages): ?>

    <div id="list_of_news" class="news">
        <?= View::factory('templates/news_list', array( 'pages'=> $pages)); ?>
    </div>

    <? if ($next_page): ?>
        <a class="load_more_button w_island" id="button_load_news" href="/<?= $page_number + 1 ?>">Показать больше новостей</a>
        <script>
            codex.documentIsReady(function() {
                codex.appender.init({
                    button_id       : 'button_load_news',
                    current_page    : '<?= $page_number ?>',
                    url             : '<?= $feed_key ? "/".$feed_key."/" : "/" ?>',
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
