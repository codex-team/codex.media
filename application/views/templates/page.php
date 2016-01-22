<div>
	<? # блок навигации ?>
	<? if($navigation[0]->type != Model_Page::TYPE_USER_PAGE || $navigation[0]->is_menu_item == 1): ?>
		<a href="/">Главная страница</a>
	<? else: ?>
		<a href="/user/<?= $page->author->id ?>"><?= $page->author->name ?></a>
	<? endif ?>
	<? foreach ($navigation as $navig_page): ?>
	» <a <? if ($navig_page->id != $page->id): ?>href="/p/<?= $navig_page->id ?>/<?= $navig_page->uri ?>"<? endif ?> >
			<?= $navig_page->title ?></a>
	<? endforeach ?>
</div>

<h1 class="page_title">
	<?= $page->title ?>
</h1>


<div class="page_content">
	<?= $page->content ?>
	<? if ($page->content): echo "<br>"; endif ?>
	<? if($can_modify_this_page): ?>
		<a class="button green" href="/p/<?= $page->id ?>/<?= $page->uri ?>/edit">Редактировать</a>
		<a class="button gray" href="/p/<?= $page->id ?>/<?= $page->uri ?>/delete">Удалить</a>
	<? endif ?>
</div>

<?  ?>
<? if ($page->childrens || $can_modify_this_page): ?>
	<ul class="page_childrens childrens_underpage">
		<? foreach ($page->childrens as $children): ?>
			<li><a href="/p/<?= $children->id ?>/<?= $children->uri ?>"><?= $children->title ?></a></li>
		<? endforeach ?>
		<? if($can_modify_this_page): ?>
			<li><a class="button green" href="/p/<?= $page->id ?>/<?= $page->uri ?>/add-page">Добавить страницу</a></li>
		<? endif ?>
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

<? if ($user->id): ?>
	<div class="page_comments">
		Комментировать
		<form action="/addcomment" class="add_comment_form mt20">
			<textarea name="text" rows="6"></textarea>
		</form>
	</div>
<? endif ?>
