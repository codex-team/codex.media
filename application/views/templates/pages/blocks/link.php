<?
    $coverStyle = $block['style'] == 'bigCover' ? 'link-embed__cover--big' : 'link-embed__cover--small';
?>
<a class="link-embed" href="<?=$block['linkUrl']; ?>" target="_blank" rel="nofollow">
    <div class="link-embed__cover <?=$coverStyle; ?>" style='background-image: url(<?=$block['image']; ?>)'></div>
    <div class="link-embed__title"><?=$block['title']; ?></div>
    <div class="link-embed__description"><?=$block['description']; ?></div>
    <span class="link-embed__anchor"><?=$block['linkText']; ?></span>
</a>
