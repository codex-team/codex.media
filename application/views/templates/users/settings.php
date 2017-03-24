<div class="island island--padded">

     <div class="island__navigation">
        <a href="/user/<?= $user->id ?>" class="island__navigation-item">
            <? include(DOCROOT . "public/app/svg/arrow-left.svg") ?>
            Профиль
        </a>
        <a href="/logout" class="island__navigation-item island__navigation-item--right">
            Выйти
        </a>
    </div>

    <? if ($success): ?>
        <div class="info_block">
            Обновления сохранены
        </div>
    <? endif; ?>

    <? if ($error): ?>
        <div class="info_block">
            <? foreach ($error as $info): ?>
                <?= $info; ?>
            <? endforeach; ?>
        </div>
    <? endif; ?>

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
            <input class="form__input" type="text" name="name" value="<?= $user->name ?>" />

            <label class="form__label">О себе</label>
            <textarea class="form__input js-autoresizable" name="bio"><?= $user->bio ?></textarea>

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
            <?= !$user->twitter ? 'Привязать' : $user->twitter ?>
        </a>

    </section>

</div>

<?= View::factory('templates/components/email_confirm_island'); ?>

<script>

    codex.user.init();

</script>
