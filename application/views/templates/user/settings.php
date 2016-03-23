<div class="breadcrumb">

    <a class="nav_chain" href="/user/<?= $user->id ?>"><?= $user->name ?></a> »
    <span class="nav_chain">Настройки аккаунта</span>

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

        <form class="base_form" method="POST" action="user/settings" enctype="multipart/form-data">

            <input type="hidden" name="csrf" value="<?= Security::token(); ?>" />

            <div class="button fileinput iconic">
                <i class="icon-picture"></i> Изменить фотографию
                <input type="file" name="new_ava">
            </div>


            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="<?= $user->email; ?>">

            <label for="phone">Номер телефона</label>
            <input type="text" name="phone" id="phone" value="<?= $user->phone; ?>">

            <? if (!$user->vk && !$user->twitter && !$user->facebook):?>

                <label for="current_password">Текущий пароль</label>
                <input type="password" name="current_password" id="current_password">

                <label for="login_password">Новый пароль</label>
                <input type="password" name="new_password" id="login_password">

                <label for="repeat_password">Повторите пароль</label>
                <input type="password" name="repeat_password" id="repeat_password">

            <? endif; ?>

            <input type="submit" name="submit" value="Сохранить"/>


        </form>

        <div class="social_linking">

            <? if ( $user->vk == ( NULL || '' ) ): ?>
                <a class="button iconic" href="/auth/vk?state=attach"><i class="icon-vkontakte"></i> Прикрепить профиль ВКонтакте</a>
            <? else: ?>
                <a class="button iconic" href="/auth/vk?state=remove"><i class="icon-vkontakte"></i> Открепить профиль ВКонтакте</a>
            <? endif; ?>
            <? if ( $user->facebook == ( NULL || '' ) ): ?>
                <a class="button iconic" href="/auth/fb?state=attach"><i class="icon-facebook"></i> Прикрепить профиль Facebook</a>
            <? else: ?>
                <a class="button iconic" href="/auth/fb?state=remove"><i class="icon-facebook"></i> Открепить профиль Facebook</a>
            <? endif; ?>
            <? if ( $user->twitter == ( NULL || '' ) ): ?>
                <a class="button iconic" href="/auth/tw?state=attach"><i class="icon-twitter"></i> Прикрепить профиль Twitter</a>
            <? else: ?>
                <a class="button iconic" href="/auth/tw?state=remove"><i class="icon-twitter"></i> Открепить профиль Twitter</a>
            <? endif; ?>

        </div>

</div>
