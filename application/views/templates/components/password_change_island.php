<div class="island island--padded island--margined profile-settings__island" id="email-confirmation">
    <form method="POST" action="user/settings" enctype="multipart/form-data">
        <input type="hidden" name="csrf" value="<?= Security::token(); ?>" />

        <label class="profile-settings__label" for="current_password">Текущий пароль</label>
        <input class="profile-settings__oldpassword profile-settings__input" required type="password" name="current_password" id="current_password">

        <label class="profile-settings__label" for="new_password">Новый пароль</label>
        <input class="profile-settings__newpassword profile-settings__input" required type="password" name="new_password" id="new_password">

        <label class="profile-settings__label" for="repeat_password">Подтверждение пароля</label>
        <input class="profile-settings__newpasswordconfirm profile-settings__input" required type="password" name="repeat_password" id="repeat_password">

        <div class="profile-settings__buttons">
            <button class="button master">Сменить пароль</button>
        </div>

        <?php if (isset($error)) echo Debug::vars($error); ?>

    </form>

</div>

<script>

</script>
