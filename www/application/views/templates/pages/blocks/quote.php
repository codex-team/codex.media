<?
    if ($block['alignment'] == 'center') {
        $centerClass = 'article-quote--center';
    } else {
        $centerClass = '';
    }
?>

<blockquote class="article-quote <?= $centerClass ?>">
    <?= $block['text'] ?>
</blockquote>
