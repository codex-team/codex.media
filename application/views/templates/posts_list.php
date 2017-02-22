<? if ($pages): ?>

    <? foreach ($pages as $index => $page): ?>

        <? /**
            * if the elem is the first
            * $page === reset($pages)
            */ ?>
        <article class="island <?= $index != 0 ? 'island--margined' : '' ?> post-list-item <?= $page->rich_view ? 'post-list-item--big' : '' ?> <?= $page->dt_pin ? 'post-list-item_pinned' : '' ?>">

            <div class="post-list-item__header">
                <time class="post-list-item__date">
                    <a href="/p/<?= $page->id ?>/<?= $page->uri ?>">
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
            <div class="post-list-item__footer">
                <a class="post-list-item__comments" href="/p/<?= $page->id ?>/<?= $page->uri ?>" rel="nofollow">
                    <? include(DOCROOT . "public/app/svg/comment.svg") ?>
                    Комментировать
                </a>
            </div>
        </article>

    <? endforeach; ?>

<? else: ?>

    <div class="island island--padded island--centered">
        <div class="empty-motivator">
            <? include(DOCROOT . "public/app/svg/article.svg") ?><br>
            <?= $emptyListMessage ?: 'Список статей пуст' ?>
        </div>
    </div>

<? endif ?>
