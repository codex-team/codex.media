<style type="text/css">
.page_simple_form_wrapper {
    margin-top: 100px;
}
</style>

<div class="page_simple_form_wrapper">
<form class="page_simple_form" action="/auth" method="post">

    <h3>Регистрация</h3>

    <? $regFields = array(
        'name' => array(
            'label' => 'Фамилия, Имя',
            'value' => isset( $inviteData['name'] ) ? $inviteData['name'] : Arr::get($_POST, 'name', ''),
        ),
        'email' => array(
            'label' => 'Email',
            'type'  => 'email',
            'value' => isset( $inviteData['mail'] ) ? $inviteData['mail'] : Arr::get($_POST, 'email'),
            'events' => 'onblur="user.validateEmail($(this));"'
        ),
        'password' => array(
            'label' => 'Пароль',
            'type'  => 'password',
            'events' => 'onkeypress="show_confirmation(event)"'
        ),
        'password_repeat' => array(
            'label' => 'Повторите пароль',
            'type'  => 'password',
            'id' => 'password_repeat'
        )
    ); ?>

    <? if (!empty($signup_error_fields)): ?>
        <? foreach($signup_error_fields as $fieldName => $errorText ): ?>
            <div class="error"><?= $errorText ?></div>
        <? endforeach; ?>
    <? endif; ?>


    <fieldset>
        <? foreach ($regFields as $fieldName => $field): ?>
            <input placeholder="<?= Arr::get($field, 'label') ?>" type="<?= Arr::get($field, 'type', 'text') ?>" name="signup_<?= $fieldName?>" id="signup_<?= $fieldName ?>" value="<?= Arr::get($field, 'value') ?>" <?= Arr::get($field, 'events')?> />
        <? endforeach ?>
    </fieldset>

    <?= Form::hidden('csrf', Security::token()); ?>
    <?= Form::hidden('action', 'signup' ); ?>

    <button class="button main">Зарегистрироваться</button>
</form>
<div>