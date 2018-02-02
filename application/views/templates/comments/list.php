<?
    $count = isset($page) ? count($page->comments) : '0'
?>

<div class="comments-list" data-count="<?= $count ?>" data-module="islandSettings comments">

    <module-settings hidden>
        [
            {
                "selector" : ".js-comment-settings",
                "items" : [{
                    "title" : "Удалить",
                    "handler" : {
                        "module" : "comments",
                        "method" : "remove"
                    }
                }]
            }
        ]
    </module-settings>

    <?
        $comments = isset($page) ? $page->comments : $comments;
    ?>

    <? if ($comments): ?>

        <? foreach ($comments as $index => $comment): ?>
            <?= View::factory('templates/comments/comment', array(
                'user' => $user,
                'comment' => $comment,
                'index' => $index,
                'isOnPage' => isset($page) ? $page : null,
            )); ?>
        <? endforeach ?>

    <? else: ?>

        <div class="empty-motivator island island--padded js-empty-comments">

            <? include(DOCROOT . "public/app/svg/comments.svg") ?>
            <?= $emptyListMessage ?: 'Комментариев нет.' ?>

            <? if (!$user->id && isset($page)): ?>
                <a class="button master" href="/auth">Авторизоваться</a>
            <? endif ?>

        </div>

    <? endif ?>

</div>
