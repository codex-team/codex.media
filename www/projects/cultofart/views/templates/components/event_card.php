<?
    if (empty($event->cover)) {
        $defaultCoverClass = "event-card__cover--default";
    } else {
        $defaultCoverClass = "";
    }
?>
<div class="event-card">
    <a class="event-card__cover <?= $defaultCoverClass ?>" href="<?= $event->url ?>">
        <img src="/upload/pages/covers/b_<?= $event->cover ?>" alt="<?= $event->title ?>">
    </a>

    <a class="event-card__title" href="<?= $event->url ?>">
        <?= $event->title ?>
    </a>
    <footer class="event-card__footer">
        <a class="event-card__author" href="/user/<?= $event->author->id ?>">
            <img src="<?= $event->author->photo ?>" alt="<?= $event->author->name ?>">
            <?= $event->author->name ?>
        </a>
    </footer>
</div>
