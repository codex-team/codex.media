<div class="article__person">
    <div class="article__person-photo" style="<?= $block['photo'] ? 'background-image: url(' . $block['photo'] . '); background-size: contain;' : '' ?>"></div>
    <div class="article__person-name"><?= $block['name'] ?></div>
    <div class="article__person-cite"><?= $block['cite'] ?></div>
    <a class="article__person-url" href="<?= $block['url'] ?>" rel="nofollow" target="_blank"><?= $block['url'] ?></a>
</div>