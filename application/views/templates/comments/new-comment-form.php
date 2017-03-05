<?
    $parent_id = isset($comment) ? $comment->id      : '0';
    $root_id   = isset($comment) ? $comment->root_id : '0';
    $page_id   = isset($comment) ? $comment->page_id : $page_id;
?>

<? if ($user->id): ?>

    <img class="comment-form__photo" src="<?= $user->photo ?>" alt="<?= $user->name ?>">

    <div class="constrain comment-form-wrapper" id="replyFormToComment<?= $parent_id ?>"
        data-parentId="<?= $parent_id ?>"
        data-rootId="<?= $root_id ?>"
        data-sendCommentAction="add-comment/p-<?= $page_id ?>">

        <div class="comment-form__placeholder" onclick="codex.comments.appendForm(event);">Ваш комментарий...</div>

    </div>

<? else: ?>

    <a href="/auth">Авторизуйтесь, чтобы оставить комментарий</a>

<? endif ?>
