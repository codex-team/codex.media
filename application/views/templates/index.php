<? if($user->isAdmin()): ?>
    <?
        $page = new Model_Page();
        $page->type = Model_Page::TYPE_SITE_NEWS;
    ?>
    <?= View::factory('templates/pages/form', array( 'page' => $page )); ?>
<? endif ?>


<? if ($page_number > 1): ?>
    <div class="article post-list-item w_island separator">Page <?= $page_number ?></div>
<? endif ?>

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
                Добавьте первую новость с помощью формы вверху страницы
            <? else: ?>
                Здесь появятся новости и интересные публикации
            <? endif ?>
        </div>
    </div>
<? endif ?>
