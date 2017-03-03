<?
    $parent_id = isset($parent_id) ? $parent_id : '0';
    $root_id = isset($root_id) ? $root_id : '0';
?>

<? if ($user->id): ?>
    <img class="comment-form__photo" src="<?= $user->photo ?>" alt="<?= $user->name ?>">

    <div class="constrain comment-form"
        data-parentId="<?= $parent_id ?>"
        data-rootId="<?= $root_id ?>"
        data-sendCommentAction="/p/<?= $page->id ?>/<?= $page->uri ?>/add-comment">

        <div class="comment-form__placeholder" onclick="codex.comments.appendForm(event);">Ваш комментарий...</div>

    </div>

<? else: ?>
    <div>
        <a href="/auth">Авторизуйтесь, чтобы оставить комментарий</a>
    </div>
<? endif ?>
