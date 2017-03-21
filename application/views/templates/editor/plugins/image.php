<?

    $class = 'ce-plugin-image__uploaded--centered';

    if ( !empty($block['isStretch']) && $block['isStretch'] == 'true'){
        $class = 'ce-plugin-image__uploaded--stretched';
    }

?>

<img class="<?= $class; ?>" src="<?=$block['file']['url']; ?>" alt="">
<div class="ce-plugin-image__caption"><?=$block['caption']; ?></div>