<div class="island island--padded island--centered">

    <h1 class="title">Войти на сайт</h1>
    <p class="desc">Вы можете войти на сайт через аккаунт в социальной сети</p>

    <a class="button button--vk" href="/auth/vk"><i class="icon-vkontakte"></i>ВКонтакте</a>
    <a class="button button--facebook" href="/auth/fb"><i class="icon-facebook"></i>Facebook</a>
    <a class="button button--twitter" href="/auth/tw"><i class="icon-twitter"></i>Twitter</a>

    <form class="auth-form" action="/auth" method="post">

    	<h3>Вход через пароль</h3>

        <? if (!empty($login_error_text)): ?>
            <p class="form_error mb20 mt20"><?= $login_error_text ?></p>
        <? endif; ?>

        <? $loginFields = array(
                'email' => array(
                    'label' => 'Email',
                    'type'  => 'email',
                    'value' => isset( $inviteData['mail'] ) ? $inviteData['mail'] : Arr::get($_POST, 'login_email'),
                ),
                'password' => array(
                    'label' => 'Пароль',
                    'type'  => 'password',
                )
            );
        ?>

        <? foreach ($loginFields as $fieldName => $field): ?>
            <p>
                <input placeholder="<?= Arr::get($field, 'label') ?>" type="<?= Arr::get($field, 'type', 'text') ?>" name="login_<?= $fieldName?>" id="login_<?= $fieldName ?>" value="<?= Arr::get($field, 'value') ?>" />
            </p>
        <? endforeach ?>


        <button class="button master">Войти</button>

        <?= Form::hidden('csrf', Security::token()); ?>
        <?= Form::hidden('action', 'login' ); ?>

    </form>

    <div class="auth-form__footer">
        <a href="/reset">Восстановить пароль</a>
        <a href="/signup">Регистрация</a>
    </div>

</div>