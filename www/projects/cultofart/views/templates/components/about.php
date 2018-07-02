<? if (!empty($title)): ?>
    <div class="island island--padded island--bottomed js-emoji-included">
        <h3 class="island__title">
            <a href="<?= HTML::chars($page_uri) ?>">
                <?= HTML::chars($title) ?>
            </a>
        </h3>
        <div class="island__caption">
            <?= $description ?>
        </div>
        <div class="island__content">
            <? foreach ($content_blocks as $content_block) :?>
                <?= $content_block ?>
            <? endforeach; ?>
        </div>

        <a href="<?= HTML::chars($page_uri) ?>" class="island__link" onclick="codex.about.toggleContent(this, event);">
            <?= HTML::chars($link_text) ?>&nbsp;»
        </a>
    </div>
<? endif ?>