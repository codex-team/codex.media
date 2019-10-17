<?
    $classes = array();
    if (!empty($block['stretched']) && $block['stretched'] == 'true') {
        $classes[] = 'article-image--stretched';
    }
    if (!empty($block['withBorder']) && $block['withBorder'] == 'true') {
        $classes[] = 'article-image--bordered';
    }
    if (!empty($block['withBackground']) && $block['withBackground'] == 'true') {
        $classes[] = 'article-image--backgrounded';
    }
?>

<figure class="article-image <?= implode(' ', $classes) ?>">
    <img src="<?= $block['file']['url'] ?>" alt="<? !empty($block['caption']) ? $block['caption'] : '' ?>">
    <? if (!empty($block['caption'])): ?>
        <footer class="article-image-caption">
            <?= $block['caption'] ?>
        </footer>
    <? endif ?>
</figure>
