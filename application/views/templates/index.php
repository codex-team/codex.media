<div class="news">

    <? if($user->status == Model_User::USER_STATUS_ADMIN): ?>
        <article class="post">
            <a class="button green" href="/p/add-news">Добавить новость</a>
        </article>
    <? endif ?>

    <? foreach ($pages as $page): ?>

        <article class="post"> <?= $page->rich_view ?>
            <time><?= date_format(date_create($page->date), 'd F Y, G:i') ?></time>
            <h3><a href="/p/<?= $page->id ?>/<?= $page->uri ?>"><?= $page->title ?></a></h3>
            <div class="body">
                <?= $page->content ?>
            </div>
            <div class="footer">
                <a class="read_more" href="/p/<?= $page->id ?>/<?= $page->uri ?>" rel="nofollow">Подробнее</a>
                <a href="/p/<?= $page->id ?>/<?= $page->uri ?>" rel="nofollow">Комментировать</a>
            </div>
        </article>

    <? endforeach; ?>

</div>
