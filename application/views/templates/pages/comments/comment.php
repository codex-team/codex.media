<div class="island island--padded comment_wrapper clear <?= $comment->parent_comment ? 'answer_wrapper' : 'island--margined' ?>"
     id="comment_<?= $comment->id ?>">

    <div class="comment-header clearfix">

        <a class="comment-header__author-photo" href="/user/<?= $comment->author->id ?>">
            <img src="<?= $comment->author->photo ?>">
        </a>

        <a class="comment-header__author-name constrain" href="/user/<?= $comment->author->id ?>">
            <?= $comment->author->name ?>

            <? if ($comment->parent_comment): ?>
                <span class="comment">
                    <i class="icon-right-dir"></i>
                    <?= $comment->parent_comment->author->name ?>
                </span>
            <? endif ?>
        </a>

        <time class="comment-header__time constrain">
            <?= date_format(date_create($comment->dt_create), 'd F Y') ?>
        </time>
    </div>

    <div class="comment-content">
        <p><?= $comment->text ?></p>
    </div>


    <div class="comment-actions">

        <? if ($user->id): ?>
            <span class="comment-actions__button comment-actions__button--answer" id="answer_button_<?= $comment->id ?>" data-comment-id="<?= $comment->id ?>" data-root-id="<?= $comment->root_id ?>">
                <i class="icon-reply"></i>
                Ответить
            </span>
        <? endif ?>

        <? if ($user->id == $comment->author->id || $user->isAdmin): ?>
            <a class="comment-actions__button button--delete js-approval-button"
               href="/p/<?= $page->id ?>/<?= $page->uri ?>/delete-comment/<?= $comment->id ?>">
               <i class="icon-trash"></i>
               Удалить
            </a>
        <? endif ?>

    </div>
</div>
