<div class="social_auth">
	Вы можете войти на сайт через аккаунт ВКонтакте
	<div class="social_buttons">
	    <a class="button main" href="/auth/vk"><i class="pic vk"></i>Войти через VK.com</a>
    </div>
</div>

<div class="auth_form">

    <? if (!empty($login_error_text)): ?>
        <p class="form_error mb20"><?= $login_error_text ?></p>
    <? endif; ?>


	<div class="fl_l left">
		<h3>Вход</h3>
        <form class="ajaxfree" action="/auth" method="post">

            <? $loginFields = array(
                'email' => array(
                    'label' => 'Email',
                    'type'  => 'email',
                    'value' => isset( $inviteData['mail'] ) ? $inviteData['mail'] : Arr::get($_POST, 'email'),
                    'class' => 'mb5'
                ),
                'password' => array(
                    'label' => 'Пароль',
                    'type'  => 'password',
                    'class' => 'mb30'
                )
                );
            ?>


            <? foreach ($loginFields as $fieldName => $field): ?>
                <div class="input <?= Arr::get($field, 'class') ?> <?= !empty($login_error_fields[$fieldName]) ? 'error' : ''?> <?= !empty($field['value']) ? 'focus' : ''?>">
                    <label for="login_<?= $fieldName ?>"><?= $field['label'] ?></label>
                    <input type="<?= Arr::get($field, 'type', 'text') ?>" name="login_<?= $fieldName?>" id="login_<?= $fieldName ?>" value="<?= Arr::get($field, 'value') ?>" />
                    <? if (!empty($login_error_fields[$fieldName])): ?>
                        <div class="validation_error"><?= $login_error_fields[$fieldName] ?></div>
                    <? endif ?>
                </div>
            <? endforeach ?>

            <div class="mt15">
                <input type="submit" value="Войти" />
                <a href="/recover" class="form_bottom_link">Восстановить пароль</a>
            </div>

            <?= Form::hidden('csrf', Security::token()); ?>
            <?= Form::hidden('action', 'login' ); ?>
        </form>
    </div>

    <div class="right">
		<h3>Регистрация</h3>
        <form class="ajaxfree" action="/auth" method="post">

            <? $regFields = array(
                'name' => array(
                    'label' => 'Фамилия, Имя',
                    'value' => isset( $inviteData['name'] ) ? $inviteData['name'] : Arr::get($_POST, 'name', ''),
                    'class' => 'mb5'
                ),
                'email' => array(
                    'label' => 'Email',
                    'type'  => 'email',
                    'value' => isset( $inviteData['mail'] ) ? $inviteData['mail'] : Arr::get($_POST, 'email'),
                    'class' => 'mb5',
                    'events' => 'onblur="user.validateEmail($(this));"'
                ),
                'password' => array(
                    'label' => 'Пароль',
                    'type'  => 'password',
                    'class' => 'mb5',
                    'events' => 'onkeypress="show_confirmation(event)"'
                ),
                'password_repeat' => array(
                    'label' => 'Повторите пароль',
                    'type'  => 'password',
                    'class' => empty($signup_error_fields['password_repeat']) ? '_hide mb30' : '',
                    'id' => 'password_repeat'
                )
            ); ?>

            <? foreach ($regFields as $fieldName => $field): ?>
                <div class="input <?= Arr::get($field, 'class') ?> <?= !empty($signup_error_fields[$fieldName]) ? 'error' : ''?> <?= !empty($field['value']) ? 'focus' : ''?>" <?= !empty($field['id']) ? 'id="' . $field['id'] . '"' : '' ?> >
                    <label for="signup_<?= $fieldName ?>"><?= $field['label'] ?></label>
                    <input type="<?= Arr::get($field, 'type', 'text') ?>" name="signup_<?= $fieldName?>" id="signup_<?= $fieldName ?>" value="<?= Arr::get($field, 'value') ?>" <?= Arr::get($field, 'events')?> />
                    <? if (!empty($signup_error_fields[$fieldName])): ?>
                        <div class="validation_error"><?= $signup_error_fields[$fieldName] ?></div>
                    <? endif ?>
                </div>
            <? endforeach ?>

            <?= Form::hidden('csrf', Security::token()); ?>
            <?= Form::hidden('action', 'signup' ); ?>

            <input type="submit" value="Зарегистрироваться" />
        </form>
    </div>

</div>


