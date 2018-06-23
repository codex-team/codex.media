<?
    if (empty($page->cover)) {
        $defaultCoverClass = "community-aside__cover--default";
    } else {
        $defaultCoverClass = "";
    }
?>
<aside class="island main-aside">
    <div class="community-aside">
        <a href="/p/<?= $page->id ?>/<?= $page->uri ?>" class="community-aside__cover <?= $defaultCoverClass ?>">
            <img src="/upload/pages/covers/b_<?= $page->cover ?>">
        </a>
        <a href="/p/<?= $page->id ?>/<?= $page->uri ?>" class="community-aside__title">
            <?= $page->title ?>
        </a>
    </div>
</aside>
