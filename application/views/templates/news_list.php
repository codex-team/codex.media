<? foreach ($pages as $page): ?>

    <article class="post-list-item w_island <?= $page->rich_view ? 'post-list-item_view_rich' : '' ?> <?= $page->dt_pin ? 'post-list-item_pinned' : '' ?>">

        <a class="post-list-item__title" href="/p/<?= $page->id ?>/<?= $page->uri ?>"><?= $page->title ?></a>
        <? if (!$page->rich_view): ?>
            <div class="post-list-item__body">
                <?= $page->content ?>
            </div>
        <? endif ?>
        <div class="post-list-item__footer">
            <time class="fl_r"><?= date_format(date_create($page->date), 'd F Y, G:i') ?></time>
            <a class="post-list-item__read_more" href="/p/<?= $page->id ?>/<?= $page->uri ?>" rel="nofollow">Подробнее</a>
            <a class="post-list-item__comments" href="/p/<?= $page->id ?>/<?= $page->uri ?>" rel="nofollow">Комментировать</a>
        </div>
    </article>

<? endforeach; ?>
