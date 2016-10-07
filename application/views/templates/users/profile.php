<div class="w_island w_island_centercol">
    <div class="user-page">
    	<img src="<?= $viewUser->photo_medium ?>" class="user-page__img" />
    	<h1 class="user-page__name">
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
        <div class="user-page__social">
        	<? if ($viewUser->vk): ?>
        		<a href="//vk.com/<?= $viewUser->vk_uri ?>" target="_blank" class="user-page__social_link">
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
    <div class="action-line  clear">
        <? if($viewUser->isMe): ?>
            <a class="action-line__textbutton fl_r" href="/user/settings"><i class="icon-cog"></i> Настройки</a>
        <? endif; ?>
        <? if($viewUser->isMe && $user->isTeacher): ?>
            <a class="iconic action-line_button_green" href="/p/save?type=<?= Model_Page::TYPE_USER_PAGE ?>"><i class="icon-plus"></i> Создать страницу</a>
        <? else: ?>
            <span class="action-line__info">
                Зарегистрирован <?= $methods->ltime(strtotime($viewUser->dt_reg)) ?>
            </span>
        <? endif?>
        <? if ($user->isAdmin): ?>
            <span class="action-line__textbutton pointer fl_r" onclick="document.getElementById('pageAction').classList.toggle('hide')"><i class="icon-vcard"></i> Действия</span>
        <? endif ?>
    </div>
    <? if ($user->isAdmin): ?>
        <ul class="action-line__page-actions hide" id="pageAction">
            <? if (!$viewUser->isTeacher): ?>
                <li class="action-line__page-actions_li"><a href="/user/<?= $viewUser->id ?>?newStatus=teacher">Активировать аккаунт преподавателя</a></li>
            <? else: ?>
                <li class="action-line__page-actions_li"><a href="/user/<?= $viewUser->id ?>?newStatus=registered">Отключить аккаунт преподавателя</a></li>
            <? endif ?>
            <? if ($viewUser->status !=  Model_User::USER_STATUS_BANNED ): ?>
                <li class="action-line__page-actions_li"><a href="/user/<?= $viewUser->id ?>?newStatus=banned">Заблокировать</a></li>
            <? else: ?>
                <li class="action-line__page-actions_li"><a href="/user/<?= $viewUser->id ?>?newStatus=registered">Разблокировать</a></li>
            <? endif ?>
        </ul>
    <? endif ?>
</div>
	<? if ($userPages): ?>

        <div id="list_of_news" class="news">

            <?= View::factory('templates/news_list', array( 'pages'=> $userPages)); ?>

        </div>

	<? else: ?>
        <div class="w_island w_island_centercol">
    		<div class="empty-motivatior">
                <i class="icon_noarticles"></i><br/>
                К чему нам ваши статьи и страницы
            </div>
        </div>
    <? endif ?>
