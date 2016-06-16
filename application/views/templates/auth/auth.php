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

<form class="page_simple_form" action="/auth" method="post">

	<h3>Вход через пароль</h3>

    <? if (!empty($login_error_text)): ?>
        <p class="form_error mb20 mt20"><?= $login_error_text ?></p>
    <? endif; ?>

    <? $loginFields = array(
            'email' => array(
                'label' => 'Email',
                'type'  => 'email',
                'value' => isset( $inviteData['mail'] ) ? $inviteData['mail'] : Arr::get($_POST, 'email'),
            ),
            'password' => array(
                'label' => 'Пароль',
                'type'  => 'password',
            )
        );
    ?>

    <fieldset>
        <? foreach ($loginFields as $fieldName => $field): ?>
            <input placeholder="<?= Arr::get($field, 'label') ?>" type="<?= Arr::get($field, 'type', 'text') ?>" name="login_<?= $fieldName?>" id="login_<?= $fieldName ?>" value="<?= Arr::get($field, 'value') ?>" />
        <? endforeach ?>
    </fieldset>

    <button class="button main">Войти</button>

    <?= Form::hidden('csrf', Security::token()); ?>
    <?= Form::hidden('action', 'login' ); ?>

</form>

<div class="page_footer_links">
    <a href="/recover">Восстановить пароль</a>
    <a href="/signup">Регистрация</a>
</div>

<? /*
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
*/ ?>
