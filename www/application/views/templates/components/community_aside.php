<aside class="island main-aside">
    <div class="community-aside">
        <? if (!empty($page->cover)): ?>
            <a href="/p/<?= $page->id ?>/<?= $page->uri ?>">
                <img class="community-aside__logo" src="/upload/pages/covers/b_<?= $page->cover ?>">
            </a>
        <? endif; ?>
        <a href="/p/<?= $page->id ?>/<?= $page->uri ?>" class="community-aside__title">
            <?= $page->title ?>
        </a>
    </div>
</aside>
