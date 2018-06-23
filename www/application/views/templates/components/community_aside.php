<aside class="island main-aside">
    <div class="community-aside">
        <a href="/p/<?= $page->id ?>/<?= $page->uri ?>">
            <? if (!empty($page->cover)): ?>
                <img class="community-aside__logo" src="/upload/pages/covers/b_<?= $page->cover ?>">
            <? else: ?>
                <img class="community-aside__logo community-aside__logo--default" src="/public/app/svg/community-placeholder.svg">
            <? endif; ?>
        </a>
        <a href="/p/<?= $page->id ?>/<?= $page->uri ?>" class="community-aside__title">
            <?= $page->title ?>
        </a>
    </div>
</aside>
