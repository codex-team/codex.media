<div class="profile island island--padded">

    <? if ($viewUser->isMe): ?>
        <div class="island__navigation">
            Профиль
            <a href="\user\settings" class="island__navigation-item island__navigation-item--right">
                Настройки
            </a>
        </div>
    <? endif; ?>

    <div class="profile__content clearfix">

        <? if ($user->isAdmin): ?>
            <span class="island-settings js-user-settings" data-id="<?= $viewUser->id ?>">
                <? include(DOCROOT . 'public/app/svg/ellipsis.svg'); ?>
            </span>
            <script>
                codex.docReady(function() {

                    /** Island settings menu */
                    codex.islandSettings.init({
                        selector : '.js-user-settings',
                        items : [
                            {
                                title : '<?= $viewUser->isBanned ? 'Разблокировать' : 'Заблокировать' ?>',
                                handler : codex.user.changeStatus,
                                arguments : {
                                    userId : <?= $viewUser->id ?>,
                                    status : <?= $viewUser->isBanned ? 0 : 1; ?>
                                }
                            },
                            {
                                title : '<?= !$viewUser->isTeacher ? 'Сделать преподавателем' : 'Не преподаватель' ?>',
                                handler : codex.user.changeRole,
                                arguments : {
                                    userId : <?= $viewUser->id ?>,
                                    role : <?= !$viewUser->isTeacher ? Model_User::TEACHER : Model_User::REGISTERED; ?>
                                }
                            }]
                    });

                });
            </script>
        <? endif ?>

        <? /*

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

        */ ?>

        <img class="profile__ava" src="<?= $viewUser->photo_medium ?>" />

        <div class="profile__name">
    		<?= $viewUser->name ?>
        </div>

        <div class="profile__about <?= $viewUser->isMe ? 'profile__about--editable' : '' ?>" <?= $viewUser->isMe ? 'onclick="codex.user.bio.edit(this)"' : ''?>>
            <? if (!empty($viewUser->bio)): ?>
                <?= $viewUser->bio ?>
            <? else: ?>
                Ничем полезным не занимаюсь
            <? endif ?>
            <? if ($viewUser->isMe): ?>
                <? include(DOCROOT . "public/app/svg/pencil-circle.svg") ?>
            <? endif ?>
        </div>

        <? if ($viewUser->vk): ?>
            <a class="border-button border-button--vk" href="//vk.com/<?= $viewUser->vk_uri ?>" target="_blank">
                <? include(DOCROOT . "public/app/svg/vk.svg") ?>
                <?= $viewUser->vk_uri ?: $viewUser->vk_name ?>
            </a>
        <? endif; ?>

        <? if ($viewUser->facebook): ?>
            <a class="border-button border-button--facebook" href="//fb.com/<?= $viewUser->facebook ?>" target="_blank">
                <? include(DOCROOT . "public/app/svg/facebook-circle.svg") ?>
                <?= $viewUser->facebook_name ?: $viewUser->name ?>
            </a>
        <? endif ?>

        <? if ($viewUser->twitter): ?>
            <a class="border-button border-button--twitter" href="//twitter.com/<?= $viewUser->twitter_username ?>" target="_blank">
                <? include(DOCROOT . "public/app/svg/twitter.svg") ?>
                <?= $viewUser->twitter_name ?: $viewUser->name ?>
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

<? if ($viewUser->isMe): ?>
    <?= View::factory('templates/pages/form_wrapper', array(
            'hideEditorToolbar' => true
        )); ?>
<? endif ?>

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
