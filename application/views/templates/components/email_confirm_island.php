<div class="form island island--padded island--margined island-bottomed" id="email-confirmation">

    <label class="form__label" for="email">Email</label>
    <input class="form__input <?= !$user->isConfirmed ? 'form__input--invalid' : '' ?>" type="email" name="email" id="email" value="<?= $user->email; ?>" oninput="codex.user.email.changed(this)">

    <?php if ($user->email): ?>

        <span class="form__hint">

            <span class="form__hint-icon" style="color:<?= $user->isConfirmed ? '#1EDA8A' : '#D86565'; ?>;">
                 <?php include(DOCROOT . "public/app/svg/" . ($user->isConfirmed ? 'check-circle' : 'cross-circle') . ".svg") ?>
            </span>

            <?= $user->isConfirmed ? 'Подтвержден' : 'Не подтвержден' ?>

        </span>

        <?php if (!$user->isConfirmed): ?>

            <div class="form__caption">
                Мы отправили вам на эту почту письмо. Перейдите по ссылке внутри него, чтобы подтвердить владение данным аккаунтом
            </div>

            <button class="button master" onclick="codex.user.email.sendConfirmation(this)">Выслать повторно</button>

        <?php endif; ?>

    <?php endif; ?>
</div>