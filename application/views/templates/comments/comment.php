<div class="comment island island--padded clear island--margined" id="comment_<?= $comment->id ?>">

    <div class="comment__header clearfix">

        <a class="comment__author-photo" href="/user/<?= $comment->author->id ?>">
            <img src="<?= $comment->author->photo ?>">
        </a>

        <?  if ($user->id == $comment->author->id || $user->isAdmin): ?>
            <a class="comment__actions--button button--delete js-approval-button"
               href="/p/<?= $page->id ?>/<?= $page->uri ?>/delete-comment/<?= $comment->id ?>">
               <i class="icon-trash"></i>
            </a>
        <? endif ?>

        <div class="constrain">
            <a class="comment__author-name" href="/user/<?= $comment->author->id ?>">
                <?= $comment->author->name ?>
            </a><br>

            <time class="comment__time">
                <?= $methods->ftime(strtotime($comment->dt_create)); ?>
                <a href="/p/<?= $comment->page_id ?>#comment_<?= $comment->id ?>"><?= $methods->ftime(strtotime($comment->dt_create)); ?></a>
            </time>
        </div>

    </div>

    <? if ($comment->parent_comment): ?>
        <div class="comment__replied">

            <div class="comment__replied-source">
                <?= $comment->parent_comment->text ?>
            </div>

            <span class="comment__replied-author">
                <a href="/user/<?= $comment->parent_comment->author->id ?>">
                    <?= $comment->parent_comment->author->name ?>
                </a>
            </span>

            <span class="comment__replied-date">
                <?= $methods->ftime(strtotime($comment->parent_comment->dt_create)); ?>
            </span>

            <? /* <span class="comment__replied-page">
                <?= $comment->parent_comment->text ?>
            </span> */ ?>

        </div>
    <? endif ?>

    <div class="comment__text">
        <?= $comment->text ?>
    </div>

    <div>
        <?= View::factory('templates/comments/new-comment-form', array('page' => $page, 'user' => $user, 'parent_id' => $comment->id, 'root_id' =>  $comment->root_id )); ?>
    </div>

</div>
