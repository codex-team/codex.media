<div class="island island--padded">

    <div class="island__navigation">
        <a href="/user/<?= $user->id ?>" class="island__navigation-item">
            <? include(DOCROOT . "public/app/svg/arrow-left.svg") ?>
            Профиль
        </a>
        <a href="/logout" class="island__navigation-item island__navigation-item--right logout-button">
            Выйти
        </a>
    </div>

    <section class="profile-settings form">

        <form method="POST" action="user/settings" enctype="multipart/form-data">
            <input type="hidden" name="csrf" value="<?= Security::token(); ?>" />

            <div class="profile-settings__photo">
                <div class="profile-settings__photo-hover" onclick="codex.user.photo.change( event , <?= Model_File::USER_PHOTO ?>);">
                    <? include(DOCROOT . "public/app/svg/camera.svg") ?>
                </div>
                <img src="<?= $user->photo_medium ?>" name="js-img-updatable">
            </div>

            <label class="form__label">Фамилия и Имя</label>
            <input class="form__input" type="text" name="name" value="<?= $user->name ?>" required/>

            <label class="form__label">О себе</label>
            <textarea class="form__input js-autoresizable" name="bio"><?= $user->bio ?></textarea>

            <br>
            <button class="button master">Сохранить изменения</button>

        </form>

        <a class="border-button <?= !$user->vk ? 'border-button--vk' : '' ?>" href="/auth/vk?state=<?= !$user->vk ? 'attach' : 'remove' ?>">
            <? include(DOCROOT . "public/app/svg/vk.svg") ?>
            <?= !$user->vk ? 'Привязать' : ($user->vk_name ?: $user->vk_uri) ?>
        </a>

        <a class="border-button <?= !$user->facebook ? 'border-button--facebook' : '' ?>" href="/auth/fb?state=<?= !$user->facebook ? 'attach' : 'remove' ?>">
            <? include(DOCROOT . "public/app/svg/facebook-circle.svg") ?>
            <?= !$user->facebook ? 'Привязать' : $user->facebook_name ?>
        </a>

        <a class="border-button <?= !$user->twitter ? 'border-button--twitter' : '' ?>" href="/auth/tw?state=<?= !$user->twitter ? 'attach' : 'remove' ?>">
            <? include(DOCROOT . "public/app/svg/twitter.svg") ?>
            <?= !$user->twitter ? 'Привязать' : $user->twitter_name ?>
        </a>

    </section>

</div>

<? if ($user->email): ?>

    <?= View::factory('templates/components/email_confirm_island'); ?>
    <?= View::factory('templates/components/password_change_island'); ?>

<? else: ?>

    <?= View::factory('templates/components/set_email_island'); ?>

<? endif; ?>

<script>
    <? if (isset($success) && $success): ?>

        codex.alerts.show({
            type: 'success',
            message: 'Обновления сохранены'
        });

    <? endif; ?>

    <? if (isset($errors) && $errors): ?>

        <? foreach ($errors as $info): ?>
            codex.alerts.show({
                type: 'error',
                message: '<?= $info ?>'
            });
        <? endforeach; ?>

    <? endif; ?>
</script>
