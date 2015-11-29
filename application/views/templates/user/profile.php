<div class="user_page">
	<div class="ava">
		<img src="http://placehold.it/100x100" />
	</div>
	<h1 class="name">
		<?= $user->username; ?>
	</h1>
	<? if (isset($user->vk)): ?>
		<a href="//vk.com/id<?= $viewUser->vk ?>" target="_blank"><?= $viewUser->vk_name ? $viewUser->vk_name : 'id' . $viewUser->vk ?></a>		
	<? endif ?>
</div>
<? if (isset($success)): ?>
	<div class="info_block align_c">
		Обновления сохранены
	</div>
<? endif; ?>

<? /* ADMIN functions
<div class="profile_panel clear">
	<? if ($viewUser->status != 1 ): ?>
		<a class="button" href="/user/<?= $viewUser->id ?>?act=rise">Активировать аккаунт преподавателя</a>
	<? else: ?>
		<a class="button" href="/user/<?= $viewUser->id ?>?act=degrade">Отключить аккаунт преподавателя</a>
	<? endif ?>
	<? if ($viewUser->status != 1 ): ?>
		<a class="button fl_r" href="/user/<?= $viewUser->id ?>?act=ban">Заблокировать пользователя</a>
	<? else: ?>
		<a class="button fl_r" href="/user/<?= $viewUser->id ?>?act=unban">Разблокировать пользователя</a>
	<? endif ?>
	
</div>
*/ ?>