
<style>
    .sidebar{
        display: none;
    }
</style>
<div class="page_landing">

    <h1 class="title">Войти на сайт</h1>
    <div class="desc">Вы можете войти на сайт через аккаунт в социальной сети</div>

    <a class="button iconic vk" href="/auth/vk"><i class="icon-vkontakte"></i>ВКонтакте</a>
    <a class="button iconic facebook" href="/auth/fb"><i class="icon-facebook"></i>Facebook</a>
    <a class="button iconic twitter" href="/auth/tw"><i class="icon-twitter"></i>Twitter</a>

</div>

<form class="page_simple_form" action="/signup" method="post">

    <h3>Регистрация</h3>

    <? $regFields = array(
        'name' => array(
            'label' => 'Фамилия, Имя',
            'value' => isset( $inviteData['name'] ) ? $inviteData['name'] : Arr::get($_POST, 'signup_name', ''),
        ),
        'email' => array(
            'label' => 'Email',
            'type'  => 'email',
            'value' => isset( $inviteData['mail'] ) ? $inviteData['mail'] : Arr::get($_POST, 'signup_email'),
        ),
        'password' => array(
            'label' => 'Пароль',
            'type'  => 'password',
        ),
        'password_repeat' => array(
            'label' => 'Повторите пароль',
            'type'  => 'password',
            'id' => 'password_repeat'
        )
    ); ?>

    <? if (!empty($signup_error_fields)): ?>
        <? foreach($signup_error_fields as $fieldName => $errorText ): ?>
            <div class="auth-error"><?= $errorText ?></div>
        <? endforeach; ?>
    <? endif; ?>

    <fieldset>
        <? foreach ($regFields as $fieldName => $field): ?>
            <input <?= !empty($signup_error_fields[$fieldName]) ? 'class="invalid"' : '' ?> placeholder="<?= Arr::get($field, 'label') ?>" type="<?= Arr::get($field, 'type', 'text') ?>" name="signup_<?= $fieldName?>" id="signup_<?= $fieldName ?>" value="<?= Arr::get($field, 'value') ?>"  <?= Arr::get($field, 'events')?> required />
        <? endforeach ?>
    </fieldset>

    <?= Form::hidden('csrf', Security::token()); ?>
    <?= Form::hidden('action', 'signup' ); ?>

    <button class="button main">Зарегистрироваться</button>
</form>

<div class="page_footer_links">
    <a href="/recover">Восстановить пароль</a>
    <a href="/auth">Войти на сайт</a>
</div>