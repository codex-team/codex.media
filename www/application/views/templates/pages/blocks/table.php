<table class="article-table">
    <? foreach ($block['content'] as $row): ?>
        <tr>
            <? foreach ($row as $cell): ?>
                <td>
                    <?= $cell ?>
                </td>
            <? endforeach ?>
        </tr>
    <? endforeach ?>
</table>
