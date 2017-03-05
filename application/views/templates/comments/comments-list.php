<?
    $count = isset($page) ? count($page->comments) : '0'
?>

<div class="comments-list" id="commentsList" data-count="<?= $count ?>">

    <?
        $comments = isset($page) ? $page->comments : $comments;
    ?>

    <? if ($comments): ?>

        <? foreach ($comments as $index => $comment): ?>
            <?= View::factory('templates/comments/comment', array(
                'user' => $user,
                'comment' => $comment,
                'index' => $index,
                'isOnPage' => isset($page) ? $page : null,
            )); ?>
        <? endforeach ?>

    <? else: ?>

        <div class="empty-motivator island island--padded">

            <? include(DOCROOT . "public/app/svg/comments.svg") ?>
            <?= $emptyListMessage ?: 'Комментариев нет.' ?>

            <? if (!$user->id): ?>
                <a class="button master" href="/auth">Авторизоваться</a>
            <? endif ?>

        </div>

    <? endif ?>

</div>


<script>

    codex.docReady(function(){

        /**
        * Comments module
        */
        codex.comments.init();

    });

</script>
