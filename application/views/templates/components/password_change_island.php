<div class="island island--padded island--margined island--centered island--bottomed form profile-settings__change-password-btn"
     onclick="<?= $user->password ? 'codex.user.changePassword.showForm(this)' : 'codex.user.changePassword.set(this)'; ?>">
    <? include(DOCROOT . "public/app/svg/lock.svg") ?>
    <?= $user->password ? 'Изменить пароль' : 'Установить пароль'; ?>
</div>
<div id="change-password-form" class="island island--padded island--margined island--bottomed form hide">
    <label class="form__label">Текущий пароль</label>
    <input id="change-password-input" class="form__input" type="password">
    <span class="button form__hint master" onclick="codex.user.changePassword.requestChange(this)">Подтвердить</span>
    <div id="password-change-message"></div>
</div>
<div id="change-password-success" class="island island--padded island--margined island--bottomed island--centered hide">
    <div class="profile-settings__change-password-result-text">
        Мы отправили на вашу почту письмо с подтверждением. Перейдите по ссылке в письме, чтобы установить новый пароль.
    </div>
    <button class="button master" onclick="codex.user.changePassword.requestChange(this)">Отправить еще раз</button>
</div>