<?php
    $parent_id = isset($comment) ? $comment->id : '0';
    $root_id = isset($comment) ? $comment->root_id : '0';
    $page_id = isset($comment) ? $comment->page_id : $page_id;
?>

<?php if ($user->id): ?>

    <div class="comment-form__placeholder" onclick="codex.comments.reply( this );" id="reply<?= $parent_id ?>"
        data-parent-id="<?= $parent_id ?>"
        data-root-id="<?= $root_id ?>"
        data-action="add-comment/p-<?= $page_id ?>">

        <img class="comment-form__photo" src="<?= $user->photo ?>" alt="<?= $user->name ?>">
        <span class="comment-form__placeholder-text">Ваш комментарий...</span>
        <span class="comment-form__placeholder-name"><?= $user->name ?></span>

    </div>

<?php else: ?>

    <a class="comment-form__placeholder" href="/auth">
        <?php include(DOCROOT . "public/app/svg/comment-bubble.svg") ?>  Комментировать
    </a>

<?php endif ?>
