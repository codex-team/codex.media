<div class="island island--padded island--margined island--centered island--bottomed form profile-settings__change-password-btn"
     onclick="<?= $user->password ? 'codex.user.changePassword.showForm(this)' : 'codex.user.changePassword.requestChange()'; ?>">
    <? include(DOCROOT . "public/app/svg/lock.svg") ?>
    <?= $user->password ? 'Изменить пароль' : 'Установить пароль'; ?>
</div>
