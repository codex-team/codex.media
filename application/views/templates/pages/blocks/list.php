<?php

    $tag = 'ul';

    if (!empty($block['type']) && $block['type'] == 'OL') {
        $tag = 'ol';
    }

?>
<<?=$tag; ?>>
    <?php for ($i = 0; $i < count($block['items']); $i++) : ?>
        <li><?=$block['items'][$i]; ?></li>
    <?php endfor; ?>
</<?=$tag; ?>>
