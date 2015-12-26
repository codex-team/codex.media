<div class="user_page">
	<div class="ava">
		<img src="<?= $viewUser->photo_medium ?>" />
	</div>
	<h1 class="name">
		<a href="user/<?= $viewUser->id ?>"><?= $viewUser->name ?></a>
		<?
			switch ($viewUser->status){
				case Controller_User::USER_STATUS_ADMIN 	: echo "[администратор]"; break;   # надо будет убрать, чтобы не светить админские профили
				case Controller_User::USER_STATUS_TEACHER 	: echo "[преподаватель]"; break;
				case Controller_User::USER_STATUS_BANNED 	: echo "[заблокирован]"; break;
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
</div>
<? if ($success): ?>
	<div class="info_block align_c">
		Обновления сохранены
	</div>
<? endif; ?>
<? if ($error): ?>
    <div class="info_block align_c" style="background-color:#EBA4B5; color:#F7053E;">
        <?= $error; ?>
    </div>
<? endif; ?>
<div class="profile_panel clear">
    <div class="fl_l left">
        <form class="ajaxfree" method="POST" action="user/settings" enctype="multipart/form-data">
        
            <input type="hidden" name="csrf" value="<?= Security::token(); ?>" />
            
            <div class="input mb5">
                <input type="file" name="new_ava">
            </div>
            <label for="login_email">Email</label>
            <div class="input mb5">
                <input type="email" name="new_email" id="login_email" value="<?= $viewUser->email; ?>">
            </div>
            <div class="input mb5">
                <label for="phone_number">Номер телефона</label>
                <input type="text" name="phone_number" id="phone_number" value="<?= $viewUser->phone; ?>">
            </div>
            <? if (!$viewUser->vk && !$viewUser->twitter && !$viewUser->facebook):?>
                <label for="current_password">Текущий пароль</label>
                <div class="input type mb5">
                    <input type="password" name="current_password" id="current_password">
                </div>
                <label for="login_password">Новый пароль</label>
                <div class="input mb5">
                    <input type="password" name="new_password" id="login_password">
                </div>
                <label for="repeat_password">Повторите пароль</label>
                <div class="input mb5">
                    <input type="password" name="repeat_password" id="repeat_password">
                </div>
            <? endif; ?>
            <div class="input mb5">
                <input type="submit" name="submit" value="Сохранить" >
            </div>             
        </form>
    </div>
    <div class="right">
    <? if ($viewUser->isMe): ?>
	    <? if ($viewUser->status < Controller_User::USER_STATUS_TEACHER ): ?>
		    <a class="button" href="/user/<?= $viewUser->id ?>?act=rise">Активировать аккаунт преподавателя</a>
	    <? else: ?>
	    	<a class="button" href="/user/<?= $viewUser->id ?>?act=degrade">Отключить аккаунт преподавателя</a>
	    <? endif ?>
	    <? if ($viewUser->status !=  Controller_User::USER_STATUS_BANNED ): ?>
	    	<a class="button fl_r" href="/user/<?= $viewUser->id ?>?act=ban">Заблокировать пользователя</a>
	    <? else: ?>
	    	<a class="button fl_r" href="/user/<?= $viewUser->id ?>?act=unban">Разблокировать пользователя</a>
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
	    	<a class="button" href="/auth/tw?state=remove">Открепить профиль TW</a
	    <? endif; ?>
    <? endif; ?>
    </div>
</div>
