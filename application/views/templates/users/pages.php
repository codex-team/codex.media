<div id="list_of_news" class="post-list">

    <?= View::factory('templates/pages/list', array(
        'pages'=> $user_feed,
        'emptyListMessage' => 'Тут появятся статьи и заметки',
        'active_tab' => 'USER_PAGES'
    )); ?>

</div>

<? if (isset($next_page) && $next_page): ?>
    <a class="button button--load-more island island--padded island--centered island--stretched" href="/user/<?= $viewUser->id ?>/pages/<?= $page_number + 1 ?>" data-module="appender">
        <module-settings>
            {
                "currentPage" : "<?= $page_number ?>",
                "url"          : "<?= "/user/" . $viewUser->id . "/pages/" ?>",
                "targetBlockId" : "list_of_news",
                "autoLoading": "true",
                "dontWaitFirstClick": "true"
            }
        </module-settings>
        Показать больше записей
    </a>
    
<? endif ?>
