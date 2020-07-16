<?

    $tag = 'ul';

    if (!empty($block['style']) && $block['style'] == 'ordered') {
        $tag = 'ol';
    }

?>
<<?= $tag ?> class="article-list">
    <? for ($i = 0; $i < count($block['items']); $i++) : ?>
        <li><?=$block['items'][$i]; ?></li>
    <? endfor; ?>
</<?=$tag; ?>>
