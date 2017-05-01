<?
    if ($block['style'] == 'smallCover') {
        $holderStyle = 'article__link--smallCover';
        $coverStyle = 'article-link__cover--small';
    } else {
        $holderStyle = 'article__link--bigCover';
        $coverStyle = 'article-link__cover--big';
    }

?>
<div class="article__link <?=$holderStyle; ?>">
    <div class="article-link__cover <?=$coverStyle; ?>" style='background-image: url(<?=$block['image']; ?>)'></div>

    <? if ($block['style'] == 'smallCover') : ?>
        <div class="article-link__title"><?=$block['title']; ?></div>
        <div class="article-link__description"><?=$block['description']; ?></div>
        <a class="article-link__anchor" href="<?=$block['linkUrl']; ?>" target="_blank"><?=$block['linkText']; ?></a>
    <? else : ?>
        <div class="article-link-wrapper">
            <div class="article-link__title"><?=$block['title']; ?></div>
            <div class="article-link__description"><?=$block['description']; ?></div>
            <a class="article-link__anchor" href="<?=$block['linkUrl']; ?>" target="_blank"><?=$block['linkText']; ?></a>
        </div>
    <? endif; ?>
</div>
