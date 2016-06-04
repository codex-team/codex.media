<div class="user_page">
	<div class="ava">
		<img src="<?= $viewUser->photo_medium ?>" />
	</div>
	<h1 class="name">
		<?= $viewUser->name ?>
		<? /*
			switch ($viewUser->status){
				case Model_User::USER_STATUS_ADMIN 	    : echo "[администратор]"; break;   # надо будет убрать, чтобы не светить админские профили
				case Model_User::USER_STATUS_TEACHER 	: echo "[преподаватель]"; break;
				case Model_User::USER_STATUS_BANNED 	: echo "[заблокирован]"; break;
		   	}
            */
		?>
	</h1>
    <div class="social">
    	<? if ($viewUser->vk): ?>
    		<a href="//vk.com/<?= $viewUser->vk_uri ?>" target="_blank">
                <i class="icon-vkontakte"></i>
                <?= $viewUser->vk_uri ? $viewUser->vk_uri : $viewUser->vk_name ?>
            </a>
    	<? endif; ?>
    	<? if ($viewUser->facebook): ?>
    		<a href="//fb.com/<?= $viewUser->facebook ?>" target="_blank">
                <i class="icon-facebook"></i>
                <?= $viewUser->facebook_name ? $viewUser->facebook_name : $viewUser->name ?>
            </a>
    	<? endif ?>
    	<? if ($viewUser->twitter): ?>
    		<a href="//twitter.com/<?= $viewUser->twitter_username ?>" target="_blank">
                <i class="icon-twitter"></i>
                <?= $viewUser->twitter_name ? $viewUser->twitter_name : $viewUser->name ?>
            </a>
    	<? endif ?>
    </div>
</div>
<? if (isset($setUserStatus) && $setUserStatus): ?>
	<div class="info_block align_c">
		Обновления сохранены
	</div>
<? endif; ?>
<div class="action_line clear">
    <? if($viewUser->isMe): ?>
        <a class="textbutton fl_r" href="/user/settings"><i class="icon-cog"></i> Настройки</a>
    <? endif; ?>
    <? if($viewUser->isMe && $user->isTeacher): ?>
        <a class="button iconic green" href="/p/add-page"><i class="icon-plus"></i> Создать страницу</a>
    <? else: ?>
        <span class="info">
            Зарегистрирован <?= $methods->ltime(strtotime($viewUser->dt_reg)) ?>
        </span>
    <? endif?>
    <? if ($user->isAdmin): ?>
        <span class="textbutton pointer fl_r" onclick="document.getElementById('pageAction').classList.toggle('hide')"><i class="icon-vcard"></i> Действия</span>
    <? endif ?>
</div>
<? if ($user->isAdmin): ?>
    <ul class="action_line page_actions hide" id="pageAction">
        <? if (!$viewUser->isTeacher): ?>
            <li><a href="/user/<?= $viewUser->id ?>?newStatus=teacher">Активировать аккаунт преподавателя</a></li>
        <? else: ?>
            <li><a href="/user/<?= $viewUser->id ?>?newStatus=registered">Отключить аккаунт преподавателя</a></li>
        <? endif ?>
        <? if ($viewUser->status !=  Model_User::USER_STATUS_BANNED ): ?>
            <li><a href="/user/<?= $viewUser->id ?>?newStatus=banned">Заблокировать</a></li>
        <? else: ?>
            <li><a href="/user/<?= $viewUser->id ?>?newStatus=registered">Разблокировать</a></li>
        <? endif ?>
    </ul>
<? endif ?>



<div class="profile_panel clear">
    <h2>Страницы пользователя</h2>

	<? if ($userPages): ?>
        <ul>
    		<? foreach ($userPages as $page): ?>
    			<li><h3><a href="/p/<?= $page->id ?>/<?= $page->uri ?>"><?= $page->title ?></a></h3></li>
    		<? endforeach; ?>
        </ul>
	<? else: ?>
		пользователь пока еще не создал ни одной страницы
	<? endif ?>
</div>
