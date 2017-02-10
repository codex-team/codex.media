<div class="island island--padded">
    <div class="island--centered">
    	<div class="ava">
    		<img src="<?= $viewUser->photo_medium ?>" />
    	</div>
    	<h1 class="name">
    		<?= $viewUser->name ?>
    		<? /*
                // галочка для отметки учителей
    			switch ($viewUser->status){
    				//case Model_User::USER_STATUS_ADMIN 	    : echo "[администратор]"; break;   # надо будет убрать, чтобы не светить админские профили
    				case Model_User::USER_STATUS_TEACHER 	: echo "[преподаватель]"; break;
    				case Model_User::USER_STATUS_BANNED 	: echo "[заблокирован]"; break;
    		   	}
                */
    		?>
    	</h1>
        <div class="island island--padded">
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
    <div class="island island--margined clear">
        <? if($viewUser->isMe): ?>
            <a class="button fl_r" href="/user/settings"><i class="icon-cog"></i>Настройки</a>
            <a class="button master" href="/p/save"><i class="icon-plus"></i>Создать страницу</a>
        <? else: ?>
            <span class="info">
                Зарегистрирован <?= $methods->ltime(strtotime($viewUser->dt_reg)) ?>
            </span>
        <? endif?>
        <? if ($user->isAdmin): ?>
            <span class="button fl_r" onclick="document.getElementById('pageAction').classList.toggle('hide')">
                <i class="icon-vcard"></i>Действия
            </span>
        <? endif ?>
    </div>
    <? if ($user->isAdmin): ?>
        <ul class="island island--margined hide" id="pageAction">
            <span class="button">
                <? if (!$viewUser->isTeacher): ?>
                    <li><a href="/user/<?= $viewUser->id ?>?newStatus=teacher">Активировать аккаунт преподавателя</a></li>
                <? else: ?>
                    <li><a href="/user/<?= $viewUser->id ?>?newStatus=registered">Отключить аккаунт преподавателя</a></li>
                <? endif ?>
            </span>
            <span class="button fl_r">
                <? if ($viewUser->status !=  Model_User::USER_STATUS_BANNED ): ?>
                    <li><a href="/user/<?= $viewUser->id ?>?newStatus=banned">Заблокировать</a></li>
                <? else: ?>
                    <li><a href="/user/<?= $viewUser->id ?>?newStatus=registered">Разблокировать</a></li>
                <? endif ?>
            </span>
        </ul>
    <? endif ?>
</div>

<? if ($userPages): ?>

    <div id="list_of_news" class="island--margined">

        <?= View::factory('templates/posts_list', array( 'pages'=> $userPages)); ?>

    </div>

<? else: ?>
    <div class="island island--padded island--margined">
        <div class="empty_motivatior">
            <? /* <i class="icon_noarticles"></i><br/> */ ?>
            К чему нам ваши статьи и страницы
        </div>
    </div>
<? endif ?>
