<div class="comment island island--padded clear <?= $comment->parent_comment ? 'answer_wrapper' : 'island--margined' ?>" id="comment_<?= $comment->id ?>">

    <div class="comment__header clearfix">

        <a class="comment__author-photo" href="/user/<?= $comment->author->id ?>">
            <img src="<?= $comment->author->photo ?>">
        </a>

        <div class="constrain">
            <a class="comment__author-name" href="/user/<?= $comment->author->id ?>">
                <?= $comment->author->name ?>
            </a><br>
            <? /**
                *
                * @todo add 'in reply to' cursor

                <? if ($comment->parent_comment): ?>
                    <span class="comment">
                        <i class="icon-right-dir"></i>
                        <?= $comment->parent_comment->author->name ?>
                    </span>
                <? endif ?>
                */
            ?>

            <time class="comment__time">
                <?= $methods->ftime(strtotime($comment->dt_create)); ?>
            </time>
        </div>
    </div>

    <div class="comment__text">
        <?= $comment->text ?>
    </div>

    <?
        /**
        * @todo add 'reply' function
        */
    ?>

    <?
         if ($user->id == $comment->author->id || $user->isAdmin): ?>
        <a class="comment__actions--button button--delete js-approval-button"
           href="/p/<?= $page->id ?>/<?= $page->uri ?>/delete-comment/<?= $comment->id ?>">
           <i class="icon-trash"></i>
           Удалить
        </a>
    <? endif ?>

</div>
