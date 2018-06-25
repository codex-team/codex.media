<?
    if (empty($event->cover))
    {
        $defaultCoverClass = "event-card__cover--default";
    } else {
        $defaultCoverClass = "";
    }
?>
<div class="event-card">
    <a class="event-card__cover <?= $defaultCoverClass ?>" href="">
        <img src="/upload/pages/covers/b_<?= $event->cover ?>" alt="">
    </a>

    <a class="event-card__title" href="">
        <?= $event->title ?>
    </a>
    <footer class="event-card__footer">
        <a class="event-card__photo" href="">
            <img class="" src="<?= $event->author->photo ?>" alt="">
        </a>
        <a class="event-card__user-name" href="">
            <?= $event->author->name ?>
        </a>
    </footer>
</div>
