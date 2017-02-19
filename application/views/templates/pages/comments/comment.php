<div class="comment island island--padded clear <?= $comment->parent_comment ? 'answer_wrapper' : 'island--margined' ?>"
     id="comment_<?= $comment->id ?>">

    <div class="comment__header clearfix">

        <a class="comment__author-photo" href="/user/<?= $comment->author->id ?>">
            <img src="<?= $comment->author->photo ?>">
        </a>

        <div class="constrain">
            <a class="comment__author-name" href="/user/<?= $comment->author->id ?>">
                <?= $comment->author->name ?>

                <? /* #TODO add 'reply' function
                <? if ($comment->parent_comment): ?>
                    <span class="comment">
                        <i class="icon-right-dir"></i>
                        <?= $comment->parent_comment->author->name ?>
                    </span>
                <? endif ?>
                */ ?>
            </a>

            <time class="comment__time">
                <?= date_format(date_create($comment->dt_create), 'd F Y') ?>
            </time>
        </div>

    </div>

    <div class="comment__content">
        <?= $comment->text ?>
    </div>


    <? /* remove buttons
    <div class="comment__actions">

        #TODO add 'reply' function
        // <? if ($user->id): ?>
        //     <span class="comment__actions--button comment__actions--button-answer" id="answer_button_<?= $comment->id ?>" data-comment-id="<?= $comment->id ?>" data-root-id="<?= $comment->root_id ?>">
        //         <i class="icon-reply"></i>
        //         Ответить
        //     </span>
        // <? endif ?>

        <? if ($user->id == $comment->author->id || $user->isAdmin): ?>
            <a class="comment__actions--button button--delete js-approval-button"
               href="/p/<?= $page->id ?>/<?= $page->uri ?>/delete-comment/<?= $comment->id ?>">
               <i class="icon-trash"></i>
               Удалить
            </a>
        <? endif ?>

    </div>
    */ ?>
</div>
