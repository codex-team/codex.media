<? if (isset($users)): ?>

	<h3 class="p30">Список пользователей</h3>

	<table class="admin_table">

		<tr>
			<th>имя</th>
			<th>дата регистрации</th>
			<th>действия</th>
		</tr>

		<? foreach ($users as $dude): ?>
			<tr>
				<td>
					<a href="/user/<?= $dude['id'] ?>">
						<img class="ava" src="<?= $dude['photo'] ?>" ><?= $dude['name'] ?>
					</a>
				</td>
				<td>
					<div class="date"><?= $methods->ftime(strtotime($dude['dt_reg'])) ?></div>
				</td>
				<td></td>
			</tr>
		<? endforeach; ?>
	</table>
<? endif; ?>