<div class="w_island w_island_centercol">
    <div class="breadcrumb">

        <a class="breadcrumb__nav-chain" href="/user/<?= $user->id ?>"><?= $user->name ?></a> »
        <span class="breadcrumb__nav-chain">Настройки аккаунта</span>

    </div>

    <? if ($success): ?>
        <div class="info-block align_c">
            Обновления сохранены
        </div>
    <? endif; ?>
    <? if ($error): ?>
        <div class="info-block align_c" style="background-color:#EBA4B5; color:#F7053E;">
            <? foreach ($error as $info): ?>
                <?= $info; ?>
            <? endforeach; ?>
        </div>
    <? endif; ?>
    <div class="profile-panel clear">

        <form class="profile-panel__base-form" method="POST" action="user/settings" enctype="multipart/form-data">

            <input type="hidden" name="csrf" value="<?= Security::token(); ?>" />

            <div class="prifile_settings--ava-holder">

                <img class="profile_settings--ava" src="<?= $user->photo_medium ?>" />

                <span class="button fileinput iconic">
                    <i class="icon-picture"></i> Изменить фотографию
                    <input type="file" name="new_ava">
                </span>

            </div>


            <label for="email" class="base-form__label">Email</label>
            <input type="email" name="email" id="email" value="<?= $user->email; ?>">

            <label for="phone" class="base-form__label">Номер телефона</label>
            <input type="text" name="phone" id="phone" value="<?= $user->phone; ?>">

            <? if ($user->password):?>

                <label for="current_password" class="base-form__label">Текущий пароль</label>
                <input type="password" name="current_password" id="current_password">
            <? endif; ?>

                <label for="login_password" class="base-form__label">Новый пароль</label>
                <input type="password" name="new_password" id="login_password">

                <label for="repeat_password" class="base-form__label">Повторите пароль</label>
                <input type="password" name="repeat_password" id="repeat_password">


            <input type="submit" class="base-form__input-submit" name="submit" value="Сохранить"/>


        </form>

        <div class="social-linking">

            <? if ( $user->vk == ( NULL || '' ) ): ?>
                <a class="social-linking__button iconic" href="/auth/vk?state=attach"><i class="icon-vkontakte"></i> Прикрепить профиль ВКонтакте</a>
            <? else: ?>
                <a class="social-linking__button iconic" href="/auth/vk?state=remove"><i class="icon-vkontakte"></i> Открепить профиль ВКонтакте</a>
            <? endif; ?>
            <? if ( $user->facebook == ( NULL || '' ) ): ?>
                <a class="social-linking__button iconic" href="/auth/fb?state=attach"><i class="icon-facebook"></i> Прикрепить профиль Facebook</a>
            <? else: ?>
                <a class="social-linking__button iconic" href="/auth/fb?state=remove"><i class="icon-facebook"></i> Открепить профиль Facebook</a>
            <? endif; ?>
            <? if ( $user->twitter == ( NULL || '' ) ): ?>
                <a class="social-linking__button iconic" href="/auth/tw?state=attach"><i class="icon-twitter"></i> Прикрепить профиль Twitter</a>
            <? else: ?>
                <a class="social-linking__button iconic" href="/auth/tw?state=remove"><i class="icon-twitter"></i> Открепить профиль Twitter</a>
            <? endif; ?>

        </div>
    </div>
</div>
