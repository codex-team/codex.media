<div class="user_page">
	<div class="ava">
		<img src="<?= $viewUser->photo_medium ?>" />
	</div>
	<h1 class="name">
		<?= $viewUser->name ?>
		<?
			switch ($viewUser->status){
				case Model_User::USER_STATUS_ADMIN 	: echo "[администратор]"; break;   # надо будет убрать, чтобы не светить админские профили
				case Model_User::USER_STATUS_TEACHER 	: echo "[преподаватель]"; break;
				case Model_User::USER_STATUS_BANNED 	: echo "[заблокирован]"; break;
		   	}
		?>
	</h1>
	<? if ($viewUser->vk): ?>
		<a href="//vk.com/<?= $viewUser->vk_uri ?>" target="_blank"><?= $viewUser->vk_name ? $viewUser->vk_name : $viewUser->vk_uri ?></a>
	<? endif; ?>
	<? if ($viewUser->facebook): ?>
		<a href="//fb.com/<?= $viewUser->facebook ?>" target="_blank"><?= $viewUser->facebook_name ? $viewUser->facebook_name : $viewUser->name ?></a>
	<? endif ?>
	<? if ($viewUser->twitter): ?>
		<a href="//twitter.com/<?= $viewUser->twitter_username ?>" target="_blank"><?= $viewUser->twitter_name ? $viewUser->twitter_name : $viewUser->name ?></a>
	<? endif ?>
</div>
<? if ($success): ?>
	<div class="info_block align_c">
		Обновления сохранены
	</div>
<? endif; ?>
<div class="profile_panel clear">
	<? if ($user->isAdmin): ?>
		<? if (!$viewUser->isTeacher): ?>
			<a class="button" href="/user/<?= $viewUser->id ?>?newStatus=teacher">Активировать аккаунт преподавателя</a>
		<? else: ?>
			<a class="button" href="/user/<?= $viewUser->id ?>?newStatus=student">Отключить аккаунт преподавателя</a>
		<? endif ?>
		<? if ($viewUser->status !=  Model_User::USER_STATUS_BANNED ): ?>
			<a class="button fl_r" href="/user/<?= $viewUser->id ?>?newStatus=banned">Заблокировать пользователя</a>
		<? else: ?>
			<a class="button fl_r" href="/user/<?= $viewUser->id ?>?newStatus=student">Разблокировать пользователя</a>
		<? endif ?>
	<? endif ?>

	<? if (!$viewUser->vk && $viewUser->email): ?>
		<a class="button" href="/auth/vk?state=attach">Прикрепить профиль ВК</a>
	<? else: ?>
		<a class="button" href="/auth/vk?state=remove">Открепить профиль ВК</a>
	<? endif; ?>
	<? if (!$viewUser->facebook && $viewUser->email): ?>
		<a class="button" href="/auth/fb?state=attach">Прикрепить профиль FB</a>
	<? else: ?>
		<a class="button" href="/auth/fb?state=remove">Открепить профиль FB</a>
	<? endif; ?>
	<? if (!$viewUser->twitter && $viewUser->email): ?>
		<a class="button" href="/auth/tw?state=attach">Прикрепить профиль TW</a>
	<? else: ?>
		<a class="button" href="/auth/tw?state=remove">Открепить профиль TW</a>
	<? endif; ?>

	<h2>Страницы пользователя</h2>
	<ul>
	<? if($user->id == $viewUser->id && $user->isTeacher): ?>
		<a class="button green" href="/page/add">Создать страницу</a>
	<? endif?>
	<? if ($userPages): ?>
		<? foreach ($userPages as $page): ?>
			<li><h3><a href="/page/<?= $page->id ?>/<?= $page->uri ?>"><?= $page->title ?></a></h3></li>
		<? endforeach; ?>
	<? else: ?>
		пользователь пока еще не создал ни одной страницы
	<? endif ?>
	</ul>
</div>