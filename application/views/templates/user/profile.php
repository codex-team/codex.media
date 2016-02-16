<div class="user_page">
	<div class="ava">
		<img src="<?= $viewUser->photo_medium ?>" />
	</div>
	<h1 class="name">
		<?= $viewUser->name ?>
		<?
			switch ($viewUser->status){
				case Model_User::USER_STATUS_ADMIN 	    : echo "[администратор]"; break;   # надо будет убрать, чтобы не светить админские профили
				case Model_User::USER_STATUS_TEACHER 	: echo "[преподаватель]"; break;
				case Model_User::USER_STATUS_BANNED 	: echo "[заблокирован]"; break;
		   	}
		?>
	</h1>
	<ul style="color:white;">
	    <? if ($viewUser->email): ?>
    	    <li>Email: <?= $viewUser->email; ?></li>
    	<? endif; ?>
    	<? if ($viewUser->phone): ?>
    	    <li>Телефон: <?= $viewUser->phone; ?></li>
	    <? endif; ?>
	</ul>
	<? if ($viewUser->vk): ?>
		<a href="//vk.com/<?= $viewUser->vk_uri ?>" target="_blank"><?= $viewUser->vk_name ? $viewUser->vk_name : $viewUser->vk_uri ?></a>
	<? endif; ?>
	<? if ($viewUser->facebook): ?>
		<a href="//fb.com/<?= $viewUser->facebook ?>" target="_blank"><?= $viewUser->facebook_name ? $viewUser->facebook_name : $viewUser->name ?></a>
	<? endif ?>
	<? if ($viewUser->twitter): ?>
		<a href="//twitter.com/<?= $viewUser->twitter_username ?>" target="_blank"><?= $viewUser->twitter_name ? $viewUser->twitter_name : $viewUser->name ?></a>
	<? endif ?>
	<br />
    <? if ($viewUser->isMe): ?>
    	<a href="/user/settings">Настройки профиля</a>
    <? endif; ?>
</div>
<? if (isset($setUserStatus) && $setUserStatus): ?>
	<div class="info_block align_c">
		Обновления сохранены
	</div>
<? endif; ?>
<div class="profile_panel clear">
    <h2>Мои страницы</h2>

	<ul>
	<? if($viewUser->isMe && $user->isTeacher): ?>
		<li><a class="button green" href="/p/add-page">Создать страницу</a></li>
	<? endif?>
	<? if ($userPages): ?>
		<? foreach ($userPages as $page): ?>
			<li><h3><a href="/p/<?= $page->id ?>/<?= $page->uri ?>"><?= $page->title ?></a></h3></li>
		<? endforeach; ?>
	<? else: ?>
		пользователь пока еще не создал ни одной страницы
	<? endif ?>
	</ul>
</div>
