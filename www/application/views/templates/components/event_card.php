<?
    if (empty($event->cover)) {
        $defaultCoverClass = "event-card__cover--default";
    } else {
        $defaultCoverClass = "";
    }

    if ($hide_event_author) {
        $hideEventAuthor = "hidden";
    } else {
        $hideEventAuthor = "";
    }
?>
<div class="event-card">
    <a class="event-card__cover <?= $defaultCoverClass ?>" href="<?= $event->url ?>">
        <img src="/upload/pages/covers/b_<?= HTML::chars($event->cover) ?>" alt="<?= HTML::chars($event->title) ?>">
    </a>

    <a class="event-card__title" href="<?= HTML::chars($event->url) ?>">
        <?= HTML::chars($event->title) ?>
    </a>
    <footer class="event-card__footer" <?= $hideEventAuthor ?>>
        <a class="event-card__author" href="/user/<?= HTML::chars($event->author->id) ?>">
            <img src="<?= HTML::chars($event->author->photo) ?>" alt="<?= HTML::chars($event->author->name) ?>">
            <?= HTML::chars($event->author->name) ?>
        </a>
    </footer>
</div>
