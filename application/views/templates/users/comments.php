<div id="list_of_comments" class="post-list">

    <?= View::factory('templates/comments/list', array(
        'user' => $user,
        'comments' => $user_feed,
        'emptyListMessage' => '<p>Пользователь не оставил ни одного комментария.</p>'
    )); ?>

</div>

<? if (isset($next_page) && $next_page): ?>
    <a class="button button--load-more island island--padded island--centered island--stretched" id="buttonLoadComments" href="/user/<?= $viewUser->id ?>/comments/<?= $page_number + 1 ?>">
        Показать больше комментариев
    </a>

    <div data-module-required="codex.appender">
        <module-settings>
            {
                "buttonId" : "buttonLoadComments",
                "currentPage" : "<?= $page_number ?>",
                "url"          : "<?= "/user/" . $viewUser->id . "/comments/" ?>",
                "targetBlockId" : "list_of_comments",
                "autoLoading": "true"
            }
        </module-settings>
    </div>
    
<? endif ?>
