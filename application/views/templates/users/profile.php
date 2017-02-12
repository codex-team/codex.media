<div class="island island--padded">

    <div class="profile">

        <div class="profile__ava">
    		<img src="<?= $viewUser->photo_medium ?>" />
    	</div>

    	<div class="profile__name">
    		<?= $viewUser->name ?>
    	</div>

        <div class="profile__about">
    		<!-- Учитель русского языка и литературы -->
    	</div>

        <div class="profile-social">

        	<? if ($viewUser->vk): ?>
                <div class="profile-social__link profile-social__link--vk">
            		<a href="//vk.com/<?= $viewUser->vk_uri ?>" target="_blank">
                        <i class="icon-vkontakte"></i>
                        <?= $viewUser->vk_uri ? $viewUser->vk_uri : $viewUser->vk_name ?>
                    </a>
                </div>
        	<? endif; ?>

        	<? if ($viewUser->facebook): ?>
                <div class="profile-social__link profile-social__link--facebook">
            		<a href="//fb.com/<?= $viewUser->facebook ?>" target="_blank">
                        <i class="icon-facebook"></i>
                        <?= $viewUser->facebook_name ? $viewUser->facebook_name : $viewUser->name ?>
                    </a>
                </div>
        	<? endif ?>

        	<? if ($viewUser->twitter): ?>
                <div class="profile-social__link profile-social__link--twitter">
            		<a href="//twitter.com/<?= $viewUser->twitter_username ?>" target="_blank">
                        <i class="icon-twitter"></i>
                        <?= $viewUser->twitter_name ? $viewUser->twitter_name : $viewUser->name ?>
                    </a>
                </div>
        	<? endif ?>
        </div>

    </div>

    <!-- <? if (isset($setUserStatus) && $setUserStatus): ?>
    	<div class="info_block align_c">
    		Обновления сохранены
    	</div>
    <? endif; ?> -->

    <!-- <div class="action-line  clear">
        <? if($viewUser->isMe): ?>
            <a class="textbutton fl_r" href="/user/settings"><i class="icon-cog"></i> Настройки</a>
            <a class="button iconic green" href="/p/save"><i class="icon-plus"></i> Создать страницу</a>
        <? else: ?>
            <span class="info">
                Зарегистрирован <?= $methods->ltime(strtotime($viewUser->dt_reg)) ?>
            </span>
        <? endif?>
        <? if ($user->isAdmin): ?>
            <span class="textbutton pointer fl_r" onclick="document.getElementById('pageAction').classList.toggle('hide')"><i class="icon-vcard"></i> Действия</span>
        <? endif ?>
    </div> -->

    <!-- <? if ($user->isAdmin): ?>
        <ul class="action-line page_actions hide" id="pageAction">
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
    <? endif ?> -->
</div>

<ul class="island tabs island--margined">
    <li>
        <a class="tabs__tab tabs__tab--current">
            Блог
        </a>
    </li>
    <li>
        <a class="tabs__tab">
            Комментарии
        </a>
    </li>
</ul>


<div id="list_of_news" class="news">

    <?= View::factory('templates/posts_list', array('pages'=> $userPages)); ?>

</div>
