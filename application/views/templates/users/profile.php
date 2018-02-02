<div class="profile island island--padded">

    <?php if ($viewUser->isMe): ?>
        <div class="island__navigation">
            Профиль
            <a href="\user\settings" class="island__navigation-item island__navigation-item--right">
                Настройки
            </a>
        </div>
    <?php endif; ?>

    <div class="profile__content clearfix">

        <?php if ($user->isAdmin): ?>
            <span class="island-settings js-user-settings" data-id="<?= $viewUser->id ?>" data-module="islandSettings">
                <module-settings hidden>
                    {
                        "selector" : ".js-user-settings",
                        "items" : [
                            {
                                "title" : "<?= $viewUser->isBanned ? "Разблокировать" : "Заблокировать" ?>",
                                "handler" : {
                                    "module" : "user",
                                    "method" : "promote",
                                    "submethod" : "status" 
                                },
                                "arguments" : {
                                    "value" : <?= $viewUser->isBanned ? 0 : 1; ?>
                                }
                            },
                            {
                                "title" : "<?= !$viewUser->isTeacher ? "Сделать преподавателем" : "Не преподаватель" ?>",
                                "handler" : {
                                    "module" : "user",
                                    "method" : "promote",
                                    "submethod" : "role" 
                                },
                                "arguments" : {
                                    "value" : <?= !$viewUser->isTeacher ? Model_User::TEACHER : Model_User::REGISTERED; ?>
                                }
                            }]
                    }
                </module-settings>
                <?php include(DOCROOT . 'public/app/svg/ellipsis.svg'); ?>
            </span>
        <?php endif ?>

        <img class="profile__ava" src="<?= $viewUser->photo_medium ?>" />

        <h1 class="profile__name">
    		<?= $viewUser->name ?>
            <?php if ($viewUser->isTeacher): ?>
                <span class="verified" title="Преподаватель">
                    <?php include(DOCROOT . "public/app/svg/verified.svg") ?>
                </span>
            <?php endif ?>
        </h1>

        <div class="profile__about <?= $viewUser->isMe ? 'profile__about--editable' : '' ?> js-emoji-included" <?= $viewUser->isMe ? 'onclick="codex.user.bio.edit(this)"' : ''?>>
            <?php if (!empty($viewUser->bio)): ?>
                <?= $viewUser->bio ?>
            <?php else: ?>
                Ничем полезным не занимаюсь
            <?php endif ?>
            <?php if ($viewUser->isMe): ?>
                <?php include(DOCROOT . "public/app/svg/pencil-circle.svg") ?>
            <?php endif ?>
        </div>

        <?php if ($viewUser->vk): ?>
            <a class="border-button border-button--vk" href="//vk.com/<?= $viewUser->vk_uri ?>" target="_blank">
                <?php include(DOCROOT . "public/app/svg/vk.svg") ?>
                <?= $viewUser->vk_uri ?: $viewUser->vk_name ?>
            </a>
        <?php endif; ?>

        <?php if ($viewUser->facebook): ?>
            <a class="border-button border-button--facebook" href="//fb.com/<?= $viewUser->facebook ?>" target="_blank">
                <?php include(DOCROOT . "public/app/svg/facebook-circle.svg") ?>
                <?= $viewUser->facebook_name ?: $viewUser->name ?>
            </a>
        <?php endif ?>

        <?php if ($viewUser->twitter): ?>
            <a class="border-button border-button--twitter" href="//twitter.com/<?= $viewUser->twitter_username ?>" target="_blank">
                <?php include(DOCROOT . "public/app/svg/twitter.svg") ?>
                <?= $viewUser->twitter_name ?: $viewUser->name ?>
            </a>
        <?php endif ?>

    </div>

</div>

<?php /* */ ?>
<?php if (isset($isUpdateSaved) && $isUpdateSaved): ?>

    <div class="info_block align_c">
        Обновления сохранены
    </div>

<?php endif; ?>

<?php if ($viewUser->isMe): ?>
    <?= View::factory('templates/pages/form_wrapper', [
            'hideEditorToolbar' => true
        ]); ?>
<?php endif ?>

<div class="island tabs island--margined">
    <a class="tabs__tab <?= $list == 'pages' || !$list ? 'tabs__tab--current' : '' ?>" href='/user/<?= $viewUser->id ?>'>
        Блог
    </a>
    <a class="tabs__tab <?= $list == 'comments' ? 'tabs__tab--current' : '' ?>" href='/user/<?= $viewUser->id ?>/comments'>
        Комментарии
    </a>
</div>


<?= $listFactory ?>

<?php if ($emailConfirmed): ?>
    <script>
        codex.alerts.show({
            type: 'success',
            message: 'Email успешно подтвержден!'
        })
    </script>
<?php endif; ?>
