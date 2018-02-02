<?php
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

    <?php
        $comments = isset($page) ? $page->comments : $comments;
    ?>

    <?php if ($comments): ?>

        <?php foreach ($comments as $index => $comment): ?>
            <?= View::factory('templates/comments/comment', [
                'user' => $user,
                'comment' => $comment,
                'index' => $index,
                'isOnPage' => isset($page) ? $page : null,
            ]); ?>
        <?php endforeach ?>

    <?php else: ?>

        <div class="empty-motivator island island--padded js-empty-comments">

            <?php include(DOCROOT . "public/app/svg/comments.svg") ?>
            <?= $emptyListMessage ?: 'Комментариев нет.' ?>

            <?php if (!$user->id && isset($page)): ?>
                <a class="button master" href="/auth">Авторизоваться</a>
            <?php endif ?>

        </div>

    <?php endif ?>

</div>
