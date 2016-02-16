<div class="user_page">
	<div class="ava">
		<img src="<?= $user->photo_medium ?>" />
	</div>
	<h1 class="name">
		<a href="user/<?= $user->id ?>"><?= $user->name ?></a>
		<?
            switch ($user->status){
                case Model_User::USER_STATUS_ADMIN 	    : echo "[администратор]"; break;   # надо будет убрать, чтобы не светить админские профили
                case Model_User::USER_STATUS_TEACHER 	: echo "[преподаватель]"; break;
                case Model_User::USER_STATUS_BANNED 	: echo "[заблокирован]"; break;
            }
		?>
	</h1>
	<ul style="color:white;">
	    <? if ($user->email): ?>
    	    <li>Email: <?= $user->email; ?></li>
    	<? endif; ?>
    	<? if ($user->phone): ?>
    	    <li>Телефон: <?= $user->phone; ?></li>
	    <? endif; ?>
	</ul>
	<? if ($user->vk): ?>
		<a href="//vk.com/<?= $user->vk_uri ?>" target="_blank"><?= $user->vk_name ? $user->vk_name : $user->vk_uri ?></a>
	<? endif; ?>
	<? if ($user->facebook): ?>
		<a href="//fb.com/<?= $user->facebook ?>" target="_blank"><?= $user->facebook_name ? $user->facebook_name : $user->name ?></a>
	<? endif ?>
	<? if ($user->twitter): ?>
		<a href="//twitter.com/<?= $user->twitter_username ?>" target="_blank"><?= $user->twitter_name ? $user->twitter_name : $user->name ?></a>
	<? endif ?>
</div>
<? if ($success): ?>
	<div class="info_block align_c">
		Обновления сохранены
	</div>
<? endif; ?>
<? if ($error): ?>
    <div class="info_block align_c" style="background-color:#EBA4B5; color:#F7053E;">
        <? foreach ($error as $info): ?>
            <?= $info; ?>
        <? endforeach; ?>
    </div>
<? endif; ?>
<div class="profile_panel clear">
    <div class="fl_l left">
        <form method="POST" action="user/settings" enctype="multipart/form-data">
        
            <input type="hidden" name="csrf" value="<?= Security::token(); ?>" />
            
            <div class="input mb5">
                <input type="file" name="new_ava">
            </div>
            <label for="login_email">Email</label>
            <div class="input mb5">
                <input type="email" name="new_email" id="login_email" value="<?= $user->email; ?>">
            </div>
            <div class="input mb5">
                <label for="phone_number">Номер телефона</label>
                <input type="text" name="phone_number" id="phone_number" value="<?= $user->phone; ?>">
            </div>
            <? if (!$user->vk && !$user->twitter && !$user->facebook):?>
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
    <? if ($user->isMe): ?>
	    <? if ($user->status < Model_User::USER_STATUS_TEACHER ): ?>
		    <a class="button" href="/user/<?= $user->id ?>?act=rise">Активировать аккаунт преподавателя</a>
	    <? else: ?>
	    	<a class="button" href="/user/<?= $user->id ?>?act=degrade">Отключить аккаунт преподавателя</a>
	    <? endif ?>
	    <? if ($user->status !=  Model_User::USER_STATUS_BANNED ): ?>
	    	<a class="button fl_r" href="/user/<?= $user->id ?>?act=ban">Заблокировать пользователя</a>
	    <? else: ?>
	    	<a class="button fl_r" href="/user/<?= $user->id ?>?act=unban">Разблокировать пользователя</a>
	    <? endif ?>
	    <? if (!$user->vk && $user->email): ?>
	    	<a class="button" href="/auth/vk?state=attach">Прикрепить профиль ВК</a>
	    <? else: ?>
	    	<a class="button" href="/auth/vk?state=remove">Открепить профиль ВК</a>
	    <? endif; ?>
	    <? if (!$user->facebook && $user->email): ?>
	    	<a class="button" href="/auth/fb?state=attach">Прикрепить профиль FB</a>
	    <? else: ?>
	    	<a class="button" href="/auth/fb?state=remove">Открепить профиль FB</a>
	    <? endif; ?>
	    <? if (!$user->twitter && $user->email): ?>
	    	<a class="button" href="/auth/tw?state=attach">Прикрепить профиль TW</a>
	    <? else: ?>
	    	<a class="button" href="/auth/tw?state=remove">Открепить профиль TW</a
	    <? endif; ?>
    <? endif; ?>
    </div>
</div>
