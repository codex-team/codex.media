<aside class="island main-aside">
    <a class="community-aside" href="/">
        <? if (!empty($page->cover)): ?>
            <img class="community-aside__logo" src="/upload/pages/covers/b_<?= $page->cover ?>">
        <? endif; ?>
        <div class="community-aside__title">
            <?= $site_info['title'] ?><br>
            <?= $site_info['city'] ?>
        </div>
    </a>
</aside>
