<? foreach ($pages as $page): ?>

    <article class="post w_island <?= $page->rich_view ? 'rich_view' : '' ?> <?= $page->dt_pin ? 'pinned' : '' ?>">
        <h3>
            <a href="/p/<?= $page->id ?>/<?= $page->uri ?>"><?= $page->title ?></a>
        </h3>
        <? if (!$page->rich_view): ?>
            <div class="body">
                <?= $page->content ?>
            </div>
        <? endif ?>
        <div class="footer">
            <time class="fl_r"><?= date_format(date_create($page->date), 'd F Y, G:i') ?></time>
            <a class="read_more" href="/p/<?= $page->id ?>/<?= $page->uri ?>" rel="nofollow">Подробнее</a>
            <a href="/p/<?= $page->id ?>/<?= $page->uri ?>" rel="nofollow">Комментировать</a>
        </div>
    </article>

<? endforeach; ?>
