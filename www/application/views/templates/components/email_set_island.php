<div class="island island--padded island--margined island--centered island--bottomed profile-settings__change-password-btn" onclick="codex.user.email.set(this)">
    Привязать email
</div>
<div id="set-email-form" class="island island--padded island--margined island--bottomed form hide">
    <label class="form__label">Email</label>
    <input id="set-email-input" class="form__input" type="email">
    <span class="button form__hint master" onclick="codex.user.email.send.call(this)">Привязать</span>
</div>
