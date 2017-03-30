<?

    $tag = 'ul';

    if ( !empty($block['type']) && $block['type'] == 'OL' ) {
        $tag = 'ol';
    }

?>
<<?=$tag; ?>>
    <? for($i = 0; $i < count($block['items']); $i++) : ?>
        <li><?=$block['items'][$i]; ?></li>
    <? endfor; ?>
</<?=$tag; ?>>
