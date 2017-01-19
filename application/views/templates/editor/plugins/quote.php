<?
    if ( empty($block->style) ){
        $block->style = 'simple';
    }
?>
<? if ($block->style == 'simple'): ?>
    <!-- Simple quote -->
    <blockquote class="quoteStyle-simple--text">
        <?=$block->text; ?>
    </blockquote>

<? elseif ($block->style == 'withCaption') : ?>
    <!-- Quote with caption -->
    <blockquote data-quote-style="withCaption">
        <div class=" quoteStyle-withCaption--blockquote ce_quote--text"><?=$block->text; ?></div>
        <div class=" quoteStyle-withCaption--author ce_quote--author"><?=$block->author; ?></div>
    </blockquote>

<? elseif ($block->style == 'withPhoto') : ?>
    <!-- Quote with Photo -->
    <blockquote class="quoteStyle-withPhoto--wrapper" data-quote-style="withPhoto">
        <div class=" quoteStyle-withPhoto--photo authorsPhoto-wrapper">
            <img class="authorsPhoto" src="/upload/redactor_images/b_eb37d60ecf6598a0deb1c8749098c69e.jpg">
        </div>
        <div class=" quoteStyle-withPhoto--authorWrapper">
            <div class=" quoteStyle-withPhoto--author ce_quote--author"><?=$block->author; ?></div>
            <div class=" quoteStyle-withPhoto--job ce_quote--job"><?=$block->job; ?></div>
        </div>
        <div class=" quoteStyle-withPhoto--quote ce_quote--text"><?=$block->text; ?></div>
    </blockquote>
<? endif ;?>
