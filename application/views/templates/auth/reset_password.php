<div class="island island--padded island--centered">

    <form class="auth-form" action="" method="post">

        <h3><?= $header ?></h3>

        <?php $regFields = [
            'email' => [
                'label' => 'Ваш email',
                'type' => $email ? 'hidden' : 'email',
                'value' => $email
            ]
        ]; ?>

        <?php foreach ($regFields as $fieldName => $field): ?>
            <?php if (isset($signup_error_fields[$fieldName])): ?>
                <p class="auth-field__error">
            <?php else: ?>
                <p>
            <?php endif; ?>
            <input placeholder="<?= Arr::get($field, 'label') ?>" type="<?= Arr::get($field, 'type', 'text') ?>" name="reset_<?= $fieldName?>" id="reset_<?= $fieldName ?>" value="<?= Arr::get($field, 'value') ?>" required />
            </p>
        <?php endforeach ?>

        <?= Form::hidden('csrf', Security::token()); ?>

        <button class="button master"><?= $email ? 'Отправить еще раз' : 'Отправить';?></button>

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
