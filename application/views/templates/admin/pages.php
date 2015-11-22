<? if (isset($pages)): ?>
	
	<h3 class="p30">Список страниц</h3>

	<table class="admin_table">

		<tr>
			<th>название</th>
			<th>дата создания</th>
			<th>действия</th>			
		</tr>

		<? foreach ($pages as $page): ?>
			<tr>
				<td>
					<? if ($page['parent']): ?>
						<a href="/admin/pages/<?= $page['parent']['id'] ?>" class="parent_link"><?= $page['parent']['title'] ?></a> /		
					<? endif ?>
					<a href="/admin/pages/<?= $page['id'] ?>"><?= $page['title'] ?></a>
				</td>
				<td>
					<div class="date"><?= $methods->ftime(strtotime($page['date'])) ?></div>
				</td>
				<td><a href="/page/<?= $page['uri'] ? $page['uri'] : $page['id'] ?>">Посмотреть страницу</a></td>
			</tr>
		<? endforeach; ?>
	</table>
<? endif; ?>