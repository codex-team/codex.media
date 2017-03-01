<?
    $parent_id = isset($parent_id) ? $parent_id : '0';
    $root_id = isset($root_id) ? $root_id : '0';
?>

<? if ($user->id): ?>
    <img class="comment-form__photo" src="<?= $user->photo ?>" alt="<?= $user->name ?>">

    <div class="constrain">
        <textarea class="comment-form__text" data-parentId="<?= $parent_id ?>" data-rootId="<?= $root_id ?>"
        data-sendCommentAction="/p/<?= $page->id ?>/<?= $page->uri ?>/add-comment" required rows="1" placeholder="Ваш комментарий..."></textarea>
    </div>
<? endif ?>
