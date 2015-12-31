<h1 class="page_title">
	<?= $page['title'] ?>
</h1>

<? if ($page['content']): ?>
	<div class="page_content">
		<?= $page['content'] ?>
	</div>	
<? endif ?>
<? if ($page['html_content']): ?>
	<div class="page_content">
		<?= $page['html_content'] ?>
	</div>	
<? endif ?>

<? if ($page['childrens']): ?>
	<ul class="page_childrens childrens_underpage">
		<? foreach ($page['childrens'] as $children): ?>
			<li><a href="/page/<?= $children['uri'] ? $children['uri'] : $children['id'] ?>"><?= $children['title'] ?></a></li>
		<? endforeach ?>
	</ul>
<? endif; ?>
<? if (isset($files) && $files): ?>
	<table class="page_files inpage">
		<? foreach ($files as $file): ?>
			<tr>
				<td class="ext"><span class="ext_tag"><?= $file['extension'] ?></span></td>
				<td class="title"><?= $file['title'] ?></td>
				<td>
					<p class="size"><?= (int)$file['size'] < 1000 ? $file['size'] . PHP_EOL . 'КБ' : ceil($file['size'] / 1000) . PHP_EOL . 'МБ' ?></p>
				</td>
			</tr>
		<? endforeach ?>
	</table>
<? endif; ?>
<div class="page_comments">

	<h3>Комментарии</h3>
	<? if($user->id != 0): ?>
		<? if ($comments): ?>
			<? foreach ($comments as $comment): ?>
				<div>
					<b>
						<?= $comment->author_name ?>
					</b>
					<p><?= $comment->text ?></p>
					<i>
						<?= $comment->dt_create ?> 
						<? if ($comment->parent_id != 0): ?>
							пользователю <?= $comment->parent_name ?>
						<? endif; ?>
					</i>
					<a onclick="document.getElementById('answer_to_comment').value='<?= $comment->author ?>';
	                            document.getElementById('comment_head').innerHTML='Ваш ответ на комментарий пользователя <?= $comment->author_name ?>';">
	                	[ответить]
	                </a>
				</div>
			<? endforeach; ?>
		<? else: ?>
			<p>Нет комментариев.</p>
		<? endif; ?>

		<h2 id="comment_head">Оставьте комментарий:</h2>
		<form action="/page/<?= $page['id'] ?>/<?= $page['uri'] ?>/add-comment" method="POST" class="add_comment_form mt20">
			<textarea name="text" rows="6"></textarea>
			<input type="hidden" name="page_id" value="<?= $page['id'] ?>">
			<input type="hidden" name="parent_id" value="0" id="answer_to_comment"/>
			<input type="submit" value="Оставить комментарий" />
		</form>
	<? else: ?>
		<p>Комментарии доступны только зарегистрированным пользователям.</p>
	<? endif; ?>
</div>

