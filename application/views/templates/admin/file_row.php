<tr>
	<td class="ext"><span class="ext_tag"><?= $file['extension'] ?></span></td>
	<td class="title editable" data-id="<?= $file['id'] ?>"><?= $file['title'] ?></td>
	<td class="size"><?= (int)$file['size'] < 1000 ? $file['size'] . PHP_EOL . 'КБ' : ceil($file['size'] / 1000) . PHP_EOL . 'МБ' ?></td>
	<td class="actions">
		<u class="remove" onclick="editFile('remove' , <?= (int)$file['id'] ?>, $(this))">удалить</u>
		<u class="rollback hide" onclick="editFile( 'restore' , <?= (int)$file['id'] ?>, $(this))">восстановить</u>
	</td>
</tr>