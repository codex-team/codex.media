<?php

    $tag = 'h2';

    if (!empty($block['heading-styles'])) {
        switch (strtoupper($block['heading-styles'])) {
            case 'H1':
                $tag = 'h1';
                break;
            case 'H2':
                $tag = 'h2';
                break;
            case 'H3':
                $tag = 'h3';
                break;
            case 'H4':
                $tag = 'h4';
                break;
            case 'H5':
                $tag = 'h5';
                break;
            case 'H6':
                $tag = 'h6';
                break;
        }
    }
?>

<!-- Create block tag -->
<<?=$tag; ?>>
    <?=$block['text']; ?>
</<?=$tag; ?>>
