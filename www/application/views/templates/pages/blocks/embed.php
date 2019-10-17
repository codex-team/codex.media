<div class="article-embed">
    <iframe src="<?= $block['embed'] ?>" style="width:100%; height:<?= $block['height'] ?>px" scrolling="no"
            frameborder="no"></iframe>
    <? if (!empty($block['caption'])): ?>
        <footer class="article-embed-caption">
            <?= $block['caption'] ?>
        </footer>
    <? endif ?>
</div>
