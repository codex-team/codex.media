<?
    $coverStyle = Arr::get($block, 'style', 'smallCover') == 'bigCover' ? 'link-embed__cover--big' : 'link-embed__cover--small';
?>
<a class="link-embed clearfix" href="<?= Arr::get($block, 'linkUrl'); ?>" target="_blank" rel="nofollow">
    <div class="link-embed__cover <?=$coverStyle; ?>" style='background-image: url(<?=Arr::get($block, 'image'); ?>)'></div>
    <div class="link-embed__title"><?=Arr::get($block, 'title'); ?></div>
    <div class="link-embed__description"><?=Arr::get($block, 'description'); ?></div>
    <span class="link-embed__anchor"><?=Arr::get($block, 'linkText'); ?></span>
</a>
