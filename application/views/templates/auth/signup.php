<div class="island island--padded island--centered">

    <h1 class="title">Войти на сайт</h1>
    <p class="desc">Вы можете войти на сайт через аккаунт в социальной сети</p>

    <a class="button button--vk" href="/auth/vk"><i class="icon-vkontakte"></i>ВКонтакте</a>
    <a class="button button--facebook" href="/auth/fb"><i class="icon-facebook"></i>Facebook</a>
    <a class="button button--twitter" href="/auth/tw"><i class="icon-twitter"></i>Twitter</a>

    <form class="auth-form" action="/signup" method="post">

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
            'password'  => array(
                'label' => 'Пароль',
                'type'  => 'password',
            ),
            'password_repeat' => array(
                'label' => 'Повторите пароль',
                'type'  => 'password',
                'id'    => 'password_repeat'
            )
        ); ?>

        <? foreach ($regFields as $fieldName => $field): ?>
            <? if (isset($signup_error_fields[$fieldName])): ?>
                <p class="auth-field__error">
            <? else: ?>
                <p>
            <? endif; ?>
                    <input placeholder="<?= Arr::get($field, 'label') ?>" type="<?= Arr::get($field, 'type', 'text') ?>" name="signup_<?= $fieldName?>" id="signup_<?= $fieldName ?>" value="<?= Arr::get($field, 'value') ?>" required />
                </p>
        <? endforeach ?>

        <?= Form::hidden('csrf', Security::token()); ?>
        <?= Form::hidden('action', 'signup' ); ?>

        <button class="button master">Зарегистрироваться</button>

    </form>

    <? if (!empty($site_info['privacy_policy_url'])): ?>
        <div class="privacy-policy">
            <span class="privacy-policy__ic">
                <? include(DOCROOT . "public/app/svg/info.svg") ?>
            </span>
            <span class="privacy-policy__text">
                Регистрируясь, вы соглашаетесь с <a class="privacy-policy__link" href="<?= $site_info['privacy_policy_url'] ?>" target="_blank">политикой конфиденциальности</a>
            </span>
        </div>
    <? endif; ?>

    <div class="auth-form__footer">
        <a href="/reset">Восстановить пароль</a>
        <a href="/auth">Вход</a>
    </div>

</div>

<script>
    <? if (!empty($signup_error_fields)): ?>
        <? foreach($signup_error_fields as $fieldName => $errorText ): ?>
            codex.alerts.show({
                type: 'error',
                message: '<?= $errorText ?>'
            });
        <? endforeach; ?>
    <? endif; ?>
</script>

