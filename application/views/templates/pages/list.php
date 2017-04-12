<? if ($pages): ?>

    <? foreach ($pages as $index => $page): ?>

        <? /**
            * if the elem is the first
            * $page === reset($pages)
            */ ?>
        <article id="js-page-<?= $page->id ?>" class="island <?= $index != 0 ? 'island--margined' : '' ?> post-list-item <?= $page->rich_view ? 'post-list-item--big' : '' ?> <?= $page->dt_pin ? 'post-list-item_pinned' : '' ?>">

            <div class="post-list-item__header">
                <? if ($user->isAdmin || $user->id == $page->author->id): ?>
                    <span class="island-settings js-page-settings" data-id="<?= $page->id ?>">
                        <? include(DOCROOT . 'public/app/svg/ellipsis.svg'); ?>
                    </span>
                <? endif ?>
                <time class="post-list-item__date">
                    <a href="<?= $page->url ?>">
                        <?= date_format(date_create($page->date), 'd F Y, G:i') ?>
                    </a>
                </time>
                <a class="post-list-item__author" href="/user/<?= $page->author->id ?>">
                    <img src="<?= $page->author->photo ?>" alt="<?= $page->author->name ?>"><?= $page->author->name ?>
                </a>
            </div>

            <a class="post-list-item__title" href="/p/<?= $page->id ?>/<?= $page->uri ?>"><?= $page->title ?></a>

            <? if (!$page->rich_view): ?>
                <div class="post-list-item__body">
                    <?= $page->description ?>
                </div>
            <? endif ?>

            <? if (!empty($page->cover)): ?>
                <a class="posts-list-item__cover" style="background-image:  url('/upload/pages/covers/o_<?= $page->cover ?>');" href="/p/<?= $page->id ?>/<?= $page->uri ?>">
                    <? include(DOCROOT . "public/app/svg/camera.svg") ?>
                </a>
            <? else: ?>
                <div class="posts-list-item__cover posts-list-item__cover--empty" onclick="codex.pages.cover.set(<?= $page->id ?>)">
                    <? include(DOCROOT . "public/app/svg/camera.svg") ?>
                </div>
            <? endif ?>



            <div class="post-list-item__footer">
                <a class="post-list-item__comments" href="/p/<?= $page->id ?>/<?= $page->uri ?>" rel="nofollow">
                    <? include(DOCROOT . "public/app/svg/comment.svg") ?>
                    <? if ($page->commentsCount > 0): ?>
                        <?= $page->commentsCount . PHP_EOL . $methods->num_decline($page->commentsCount, 'комментарий', 'комментария', 'комментариев'); ?>
                    <? else: ?>
                        Комментировать
                    <? endif ?>
                </a>
            </div>
        </article>

    <? endforeach; ?>

    <? if ($user->id): ?>
        <script>
            codex.docReady(function() {

                /** Island settings menu */
                codex.islandSettings.init({
                    selector : '.js-page-settings',
                    items : [{
                            title : 'Редактировать',
                            handler : codex.pages.openWriting
                        },
                        {
                            title : 'Удалить',
                            handler : codex.pages.remove
                        },
                        {
                            title: 'Установить обложку',
                            handler : codex.pages.cover.toggleButton
                        }]
                });
            });
        </script>
    <? endif ?>

<? else: ?>

    <div class="island island--padded island--centered">
        <div class="empty-motivator">
            <? include(DOCROOT . "public/app/svg/article.svg") ?><br>
            <?= $emptyListMessage ?: 'Список статей пуст' ?>
        </div>
    </div>

<? endif ?>
