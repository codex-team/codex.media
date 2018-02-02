<div class="island island--padded island--centered">

    <form class="auth-form" action="" method="post">

        <?php if ($method == Model_Auth::TYPE_EMAIL_RESET): ?>

                <h3>Восстановление пароля</h3>

        <?php elseif ($method == Model_Auth::TYPE_EMAIL_CHANGE): ?>

                <h3>Смена пароля</h3>

        <?php endif; ?>

        <?php $regFields = [
            'password' => [
                'label' => 'Новый пароль',
                'type' => 'password',
            ],
            'password_repeat' => [
                'label' => 'Повторите пароль',
                'type' => 'password',
                'id' => 'password_repeat'
            ]
        ]; ?>

        <?php foreach ($regFields as $fieldName => $field): ?>
            <?php if (isset($reset_password_error_fields[$fieldName])): ?>
                <p class="auth-field__error">
            <?php else: ?>
                <p>
            <?php endif; ?>
            <input placeholder="<?= Arr::get($field, 'label') ?>" type="<?= Arr::get($field, 'type', 'text') ?>" name="reset_<?= $fieldName?>" id="reset_<?= $fieldName ?>" value="<?= Arr::get($field, 'value') ?>" required />
            </p>
        <?php endforeach ?>

        <?= Form::hidden('csrf', Security::token()); ?>

        <button class="button master">Отправить</button>

    </form>

</div>


<script>
    <?php if (!empty($reset_password_error_fields)): ?>
        <?php foreach ($reset_password_error_fields as $fieldName => $errorText): ?>
            codex.alerts.show({
                type: 'error',
                message: '<?= $errorText ?>'
            });
        <?php endforeach; ?>
    <?php endif; ?>
</script>
