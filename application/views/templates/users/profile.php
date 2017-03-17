<div class="island tabs">

        <!--<a class="nav_chain" href="/user/<?= $user->id ?>"><?= $user->name ?></a> »-->

        <span class="profile-settings__profile-button-link " >Профиль</span>
        <span class="profile-settings__logout"><a href="\user\settings" class="nav_chain">Настройки</a></span>


    </div>
<div class="island island--padded">

    <div class="profile clearfix">

    	<img class="profile__ava" src="<?= $viewUser->photo_medium ?>" />

    	<div class="profile__name">
    		<?= $viewUser->name ?>
    	</div>

        <? if (!empty($viewUser->bio)): ?>
            <div class="profile__about">
        		<? /* Учитель русского языка и литературы */ ?>
        	</div>
        <? endif ?>


        <div class="profile__social-buttons">

        	<? if ($viewUser->vk): ?>
                <a href="//vk.com/<?= $viewUser->vk_uri ?>" target="_blank">
                    <span class="profile__social-button profile__social-button--vk">
                        <i class="icon-vkontakte"></i>
                        <?= $viewUser->vk_uri ? $viewUser->vk_uri : $viewUser->vk_name ?>
                    </span>
                </a>
        	<? endif; ?>

        	<? if ($viewUser->facebook): ?>
                <a href="//fb.com/<?= $viewUser->facebook ?>" target="_blank">
                    <span class="profile__social-button profile__social-button--facebook">
                        <i class="icon-facebook"></i>
                        <?= $viewUser->facebook_name ? $viewUser->facebook_name : $viewUser->name ?>
                    </span>
                </a>
        	<? endif ?>

        	<? if ($viewUser->twitter): ?>
                <a href="//twitter.com/<?= $viewUser->twitter_username ?>" target="_blank">
                    <span class="profile__social-button profile__social-button--twitter">
                        <i class="icon-twitter"></i>
                        <?= $viewUser->twitter_name ? $viewUser->twitter_name : $viewUser->name ?>
                    </span>
                </a>
        	<? endif ?>
        </div>

    </div>

    <? /* */ ?>
    <? if (isset($isUpdateSaved) && $isUpdateSaved): ?>
    	<div class="info_block align_c">
    		Обновления сохранены
    	</div>
    <? endif; ?>
    <? /**/ ?>

    <? /*
    <div class="action-line  clear">
        <? if($viewUser->isMe): ?>
            <a class="textbutton fl_r" href="/user/settings"><i class="icon-cog"></i> Настройки</a>
            <a class="button iconic green" href="/p/writing"><i class="icon-plus"></i> Создать страницу</a>
        <? else: ?>
            <span class="info">
                Зарегистрирован <?= $methods->ltime(strtotime($viewUser->dt_reg)) ?>
            </span>
        <? endif?>
        <? if ($user->isAdmin): ?>
            <span class="textbutton pointer fl_r" onclick="document.getElementById('pageAction').classList.toggle('hide')"><i class="icon-vcard"></i> Действия</span>
        <? endif ?>
    </div>
    */ ?>

    <? /* */ ?>
    <? if ($user->isAdmin): ?>
        <ul class="action-line page_actions" id="pageAction">
            <? if (!$viewUser->isTeacher): ?>
                <li><a href="/user/<?= $viewUser->id ?>?newStatus=teacher">Активировать аккаунт преподавателя</a></li>
            <? else: ?>
                <li><a href="/user/<?= $viewUser->id ?>?newStatus=registered">Отключить аккаунт преподавателя</a></li>
            <? endif ?>
            <? if ($viewUser->status != Model_User::USER_STATUS_BANNED ): ?>
                <li><a href="/user/<?= $viewUser->id ?>?newStatus=banned">Заблокировать</a></li>
            <? else: ?>
                <li><a href="/user/<?= $viewUser->id ?>?newStatus=registered">Разблокировать</a></li>
            <? endif ?>
        </ul>
    <? endif ?>
    <? /* */ ?>
</div>

<ul class="island tabs island--margined">
    <li>
        <a class="tabs__tab <?= $list == 'pages' || !$list ? 'tabs__tab--current' : '' ?>" href='/user/<?= $viewUser->id ?>'>
            Блог
        </a>
    </li>
    <li>
        <a class="tabs__tab <?= $list == 'comments' ? 'tabs__tab--current' : '' ?>" href='/user/<?= $viewUser->id ?>/comments'>
            Комментарии
        </a>
    </li>
</ul>


<?= $listFactory ?>
