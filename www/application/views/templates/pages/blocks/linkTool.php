<a class="embed-link" href="<?= $block['link'] ?>" target="_blank" rel="nofollow">
    <? if ($block['meta']['image']): ?>
        <img class="embed-link__image" src="<?= $block['meta']['image'] ?>">
    <? endif ?>

    <div class="embed-link__title">
        <?= $block['meta']['title'] ?>
    </div>

    <div class="embed-link__description">
        <?= $block['meta']['description'] ?>
    </div>

    <span class="embed-link__domain">
        <?= parse_url($block['link'], PHP_URL_HOST) ?>
    </span>
</a>
