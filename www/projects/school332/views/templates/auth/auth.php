<div class="island island--padded island--centered">

    <h1 class="title">Войти на сайт</h1>
    <p class="desc">Вы можете войти на сайт через аккаунт в социальной сети</p>

    <div class="auth-social">

        <a class="button button--vk" href="/auth/vk">
            <? include(DOCROOT . "public/app/svg/vk.svg") ?>
            ВКонтакте
        </a>
        <a class="button button--facebook" href="/auth/fb">
            <? include(DOCROOT . "public/app/svg/facebook-circle.svg") ?>
            Facebook
        </a>
        <a class="button button--twitter" href="/auth/tw">
            <? include(DOCROOT . "public/app/svg/twitter.svg") ?>
            Twitter
        </a>

    </div>

    <form class="auth-form" action="/auth" method="post">

    	<h3>Вход через пароль</h3>

        <? $loginFields = [
                'email' => [
                    'label' => 'Email',
                    'type' => 'email',
                    'value' => isset($inviteData['mail']) ? $inviteData['mail'] : Arr::get($_POST, 'login_email'),
                ],
                'password' => [
                    'label' => 'Пароль',
                    'type' => 'password',
                ]
            ];
        ?>

        <? foreach ($loginFields as $fieldName => $field): ?>
            <p>
                <input placeholder="<?= Arr::get($field, 'label') ?>" type="<?= Arr::get($field, 'type', 'text') ?>" name="login_<?= $fieldName?>" id="login_<?= $fieldName ?>" value="<?= Arr::get($field, 'value') ?>" />
            </p>
        <? endforeach ?>


        <button class="button master">Войти</button>

        <?= Form::hidden('csrf', Security::token()); ?>
        <?= Form::hidden('action', 'login'); ?>

    </form>

    <div class="auth-form__footer">
        <a href="/reset">Восстановить пароль</a>
        <a href="/signup">Регистрация</a>
    </div>

</div>

<script>
    <? if ($passwordReseted): ?>
        codex.alerts.show({
            type: 'success',
            message: 'Новый пароль установлен'
        });
    <? endif; ?>

    <? if (!empty($login_error_text)): ?>
        codex.alerts.show({
            type: 'error',
            message: '<?= $login_error_text ?>'
        });
    <? endif; ?>
</script>
