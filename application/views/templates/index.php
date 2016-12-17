<? // добавление формы на страницу ?>
<? if ($user->id): ?>
    <?
        $page = new Model_Page();
        $page->type = Model_Page::TYPE_USER_PAGE;

        if ($user->isAdmin() && ($feed_type == Model_Page::FEED_TYPE_NEWS || !$feed_type)) {

            $page->type = Model_Page::TYPE_SITE_NEWS;
        }
    ?>
    <?= View::factory('templates/pages/form', array( 'page' => $page )); ?>

    <style>

        .ce-redactor {
            padding-top: 40px;
            min-height: 200px;
            padding-bottom: 40px;
        }

    </style>
<? endif ?>

<? /*
<? if ($page_number > 1): ?>
    <div class="article post-list-item w_island separator">Page <?= $page_number ?></div>
<? endif ?>
*/ ?>

<div class="list_users_heading">
    <ul class="page_menu">
        <li><?= $feed_type != Model_Page::FEED_TYPE_NEWS && $feed_type ?
            '<a href="/?feed='.Model_Page::FEED_TYPE_NEWS.'">Новости</a></li>' : 'Новости' ?></li>

        <li><?= $feed_type != Model_Page::FEED_TYPE_TEACHERS_BLOGS ?
            '<a href="/?feed='.Model_Page::FEED_TYPE_TEACHERS_BLOGS.'">Блоги учителей</a>' : 'Блоги учителей' ?></li>

        <li><?= $feed_type != Model_Page::FEED_TYPE_BLOGS ?
            '<a href="/?feed='.Model_Page::FEED_TYPE_BLOGS.'">Все свежие записи</a>' : 'Все свежие записи' ?></li>
    </ul>
</div>

<? if ( $pages ): ?>

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
                    url             : '/',
                    getParams       : {
                        'feed' : '<?= $feed_type ?>'
                    },
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
            <? if ($user->isAdmin()): ?>
                Добавьте новый пост с помощью формы вверху страницы
            <? else: ?>
                Здесь появятся новости и интересные публикации
            <? endif ?>
        </div>
    </div>
<? endif ?>
