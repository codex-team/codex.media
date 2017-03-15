<div class="island island--padded island--centered">

    <form class="auth-form" action="" method="post">

        <h3><?= $header ?></h3>

        <? $regFields = array(
            'email'  => array(
                'label' => 'Ваш email',
                'type'  => $email?'hidden':'email',
                'value' => $email
            )
        ); ?>

        <? foreach ($regFields as $fieldName => $field): ?>
            <? if (isset($signup_error_fields[$fieldName])): ?>
                <p class="auth-field__error">
            <? else: ?>
                <p>
            <? endif; ?>
            <input placeholder="<?= Arr::get($field, 'label') ?>" type="<?= Arr::get($field, 'type', 'text') ?>" name="reset_<?= $fieldName?>" id="reset_<?= $fieldName ?>" value="<?= Arr::get($field, 'value') ?>" required />
            </p>
        <? endforeach ?>

        <?= Form::hidden('csrf', Security::token()); ?>

        <? if (!empty($reset_password_error_fields)): ?>
            <? foreach($reset_password_error_fields as $fieldName => $errorText ): ?>
                <div class="auth-form__error"><?= $errorText ?></div>
            <? endforeach; ?>
        <? endif; ?>

        <button class="button master"><?= $email?'Отправить еще раз':'Отправить';?></button>

    </form>


</div>