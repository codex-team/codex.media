<div>
<? # блок навигации для возврата к родительской странице ?>
<? if($page->parent): ?>
	<a href="/page/<?= $page->parent->id ?>/<?= $page->parent->uri ?>"><?= $page->parent->title ?></a>
<? else: ?>
	<? if($page->type != Controller_Pages::TYPE_USER_PAGE || $page->is_menu_item == 1): ?>
		<a href="/">Главная страница</a>
	<? else: ?>
		<a href="/user/<?= $page->author ?>">К профилю автора</a>
	<? endif ?>
<? endif ?>
</div>

<h1 class="page_title">
	<?= $page->title ?>
</h1>

<? if($user->status == Controller_User::USER_STATUS_ADMIN || $user->id == $page->author): ?>
	<a href="/page/<?= $page->id ?>/<?= $page->uri ?>/edit">Редактировать</a>
<? endif ?>

<? if ($page->content): ?>
	<div class="page_content">
		<?= $page->content ?>
	</div>	
<? endif ?>

<? if ($page->childrens): ?>
	<ul class="page_childrens childrens_underpage">
		<? foreach ($page->childrens as $children): ?>
			<li><a href="/page/<?= $children['id'] ?>/<?= $children['uri'] ?>"><?= $children['title'] ?></a></li>
		<? endforeach ?>
	</ul>
<? endif; ?>

<? if($user->status == Controller_User::USER_STATUS_ADMIN || $user->id == $page->author): ?>
	<a class="button green" href="/page/<?= $page->id ?>/<?= $page->uri ?>/add-page">Добавить страницу</a>
<? endif ?>
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
	Комментировать
	<form action="/addcomment" class="add_comment_form mt20">
		<textarea name="text" rows="6"></textarea>
	</form>
</div>

