<?
    /**
     * Default header block is is H2
     */
    $level = $block['level'] ? $block['level'] : 2;

    switch ($level) {
        case '1':
        case '3':
        case '4':
        case '5':
        case '6':
            $tag = 'h' . $level;
            break;
        default:
            $tag = 'h2';
            break;
    };
?>

<!-- Create block tag -->
<<?= $tag ?>>
<?= $block['text'] ?>
</<?= $tag ?>>
