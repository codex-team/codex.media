<?

if (!isset($block['photo'])) {
    $block['photo'] = '';
}

?>

<div class="article__person">
    <div class="article__person-photo" style="<?= $block['photo'] ? 'background-image: url(' . $block['photo'] . '); background-size: contain;' : '' ?>"></div>
    <div class="article__person-name"><?= $block['name'] ?></div>
    <div class="article__person-cite"><?= $block['description'] ?></div>
    <a class="article__person-url" href="<?= $block['link'] ?>" rel="nofollow" target="_blank"><?= $block['link'] ?></a>
</div>
