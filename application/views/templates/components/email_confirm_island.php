<div class="form island island--padded island--margined" id="email-confirmation">

    <label class="form__label" for="email">Email</label>
    <input class="form__input <?= !$user->isConfirmed ? 'form__input--invalid' : '' ?>" type="email" name="email" id="email" value="<?= $user->email; ?>">

    <? if ( $user->email ): ?>

        <span class="form__hint">

            <span class="form__hint-icon" style="color:<?= $user->isConfirmed?'#1EDA8A':'#D86565'; ?>;">
                 <? include(DOCROOT . "public/app/svg/".($user->isConfirmed?'check-circle':'cross-circle').".svg") ?>
            </span>

            <?= $user->isConfirmed ? 'Подтвержден' : 'Не подтвержден' ?>

        </span>

        <? if ( !$user->isConfirmed ): ?>

            <div class="profile-settings__caption">
                Мы отправили вам на эту почту письмо. Перейдите по ссылке внутри него, чтобы подтвердить владение данным аккаунтом
            </div>

            <div class="profile-settings__buttons">
                <button class="button master" onclick="codex.user.sendEmailConfirmation(this)">Выслать повторно</button>
            </div>

        <? endif; ?>

    <? endif; ?>
</div>