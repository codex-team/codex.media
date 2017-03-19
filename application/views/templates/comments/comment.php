<div class="comment island island--padded clear <?= isset($index) && $index == 0 ? '' : 'island--margined' ?>" id="comment<?= $comment->id ?>">

    <div class="comment__header clearfix">

        <? if ($user->isAdmin || $user->id == $comment->author->id): ?>
            <span class="island-settings js-dropdown-menu-comment--holder">
                <? include(DOCROOT . 'public/app/svg/settings.svg') ?>
            </span>
        <? endif ?>

        <a class="comment__author-photo" href="/user/<?= $comment->author->id ?>">
            <img src="<?= $comment->author->photo ?>">
        </a>

        <? /* if ($user->id == $comment->author->id || $user->isAdmin): ?>
            <a class="comment__actions--button button--delete js-approval-button"
               href="/delete-comment/<?= $comment->id ?>">
               <i class="icon-trash"></i>
            </a>
        <? endif */ ?>

        <div class="constrain">
            <a class="comment__author-name" href="/user/<?= $comment->author->id ?>">
                <?= $comment->author->name ?>
            </a><br>
            <a class="comment__time" href="/p/<?= $comment->page_id ?>#comment<?= $comment->id ?>"><?= $methods->ftime(strtotime($comment->dt_create)); ?></a>
        </div>

    </div>

    <? if ($comment->parent_comment || !$isOnPage): ?>

        <?
            $target = array(
                'text'   => isset($comment->parent_comment->text)      ? $comment->parent_comment->text      : $comment->page->title,
                'author' => isset($comment->parent_comment->author)    ? $comment->parent_comment->author    : $comment->page->author,
                'date'   => isset($comment->parent_comment->dt_create) ? $comment->parent_comment->dt_create : $comment->page->date,
                'url'    => '/p/' . $comment->page->id . ( !empty($comment->parent_comment->id) ? '#comment' . $comment->parent_comment->id : ''),
            );
        ?>

        <div class="comment-target <?= !empty($comment->parent_comment->id) ? 'comment-target--reply' : 'comment-target--page' ?>">

            <a class="comment-target__text" href="<?= $target['url'] ?>" rel="nofollow">
                <?= $target['text'] ?>
            </a>

            <? if (!empty($target['author']->id)): ?>
                <a class="comment-target__author" href="/user/<?= $target['author']->id ?>">
                    <?= $target['author']->name ?>
                </a>
            <? endif ?>

            <? if ($comment->parent_comment && !$isOnPage): ?>
                <span class="comment-target__arrow">
                    <? include(DOCROOT . "public/app/svg/arrow-right.svg") ?>
                </span>
                <a class="comment-target__page" href="/p/<?= $comment->page->id ?>">
                    <?= $comment->page->title ?>
                </a>
            <? endif ?>

            <a class="comment-target__date" href="<?= $target['url'] ?>">
                <?= $methods->ftime(strtotime($target['date'])) ?>
            </a>

        </div>
    <? endif ?>

    <div class="comment__text">
        <?= $comment->text ?>
    </div>

    <div class="comment__footer">

        <?= View::factory('templates/comments/form', array(
                'comment'   => $comment,
                'page_id'   => $comment->page_id,
                'user'      => $user,
                'parent_id' => $comment->id,
                'root_id'   =>  $comment->root_id
        )); ?>

    </div>

</div>
