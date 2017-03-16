<div class="island island--padded island--margined profile-settings__island" id="email-confirmation">
    <label class="profile-settings__label" for="email">Email</label>
    <input class="profile-settings__input <?= !$user->isConfirmed?'profile-settings__input--invalid':''; ?>" type="email" name="email" id="email" value="<?= $user->email; ?>">

    <? if($user->email): ?>

        <span class="profile-settings__hint">

            <span class="profile-settings__hint-icon" style="color:<?= $user->isConfirmed?'#1EDA8A':'#D86565'; ?>;">
                 <? include(DOCROOT . "public/app/svg/".($user->isConfirmed?'check-circle':'cross-circle').".svg") ?>
            </span>

            <?= $user->isConfirmed?'Подтвержден':'Не подтвержден' ?>


        </span>
        <? if (!$user->isConfirmed): ?>
            <div class="profile-settings__caption">
                Мы отправили вам на эту почту письмо. Перейдите по ссылке внутри него, чтобы подтвердить владение данным аккаунтом
            </div>

            <div class="profile-settings__buttons">
                <button class="button master">Выслать повторно</button>
            </div>

        <? endif; ?>

    <? endif; ?>
</div>

<script>

</script>
