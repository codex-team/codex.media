<div class="comments-list" id="commentsList" data-count="<?= count($page->comments) ?: '0' ?>">

    <? if ($page->comments): ?>

        <? foreach ($page->comments as $comment): ?>
            <?= View::factory('templates/comments/comment', array('page' => $page, 'user' => $user, 'comment' => $comment)); ?>
        <? endforeach ?>

    <? else: ?>

        <div class="empty-motivator island island--margined island--padded">

            <? include(DOCROOT . "public/app/svg/comments.svg") ?>
            <p>Станьте первым, кто оставит <br/> комментарий к данному материалу.</p>

            <? if (!$user->id): ?>
                <a class="button master" href="/auth">Авторизоваться</a>
            <? endif ?>

        </div>

    <? endif ?>

</div>
