<?php
    $coverStyle = $block['style'] == 'bigCover' ? 'article-link__cover--big' : 'article-link__cover--small';
?>
<div class="article__link">
    <div class="article-link__cover <?=$coverStyle; ?>" style='background-image: url(<?=$block['image']; ?>)'></div>
    <div class="article-link__title"><?=$block['title']; ?></div>
    <div class="article-link__description"><?=$block['description']; ?></div>
    <a class="article-link__anchor" href="<?=$block['linkUrl']; ?>" target="_blank"><?=$block['linkText']; ?></a>
</div>
