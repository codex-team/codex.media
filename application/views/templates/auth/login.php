<div class="social_auth">
	Вы можете войти на сайт через аккаунт ВКонтакте
	<div class="social_buttons">
	    <a class="button main" href="/login/vk"><i class="pic vk"></i>Войти через VK.com</a>
    </div>
</div>

<? if (!empty($errors)): ?>
<div class="form_error">
    <? foreach ($errors as $error_text): ?>
    <p>
        <?php echo($error_text); ?>
    </p>
    <? endforeach; ?>
</div>
<? endif; ?>

<? if (Arr::get($_GET, 'action', '') == 'approve'): ?>
<div class="form_saved">
    <p>На указанный e-mail адрес отослано письмо для подтверждения регистрации.</p>
    <p>Перейдите по ссылке указанной в письме для активации вашего аккаунта.</p>
</div>
<? endif; ?>
    
<div class="auth_form">
    
	<div class="fl_l left">		
		<h3>Вход</h3>
	    Введите email и пароль

        <form class="ajaxfree" action="/user/login" method="post">
            <div class="input_text mt20"><input type="text" name="email" placeholder="Email" required="required" value="<?= Arr::get($_POST, 'email', '') ?>" /></div>
            <div class="input_text mt15"><input type="password" name="password" placeholder="Пароль" autocomplete="off"  required="required"/></div>
            <div class="input_text mt15">
                <label>
                    <input type="checkbox" name="remember" value="1"> Запомнить данные
                </label>
            </div>
            <div class="mt15">
                <input type="submit" value="Войти" />
                <!-- <a href="/recover" class="form_bottom_link">Восстановить пароль</a> -->
            </div>


            <?= Form::hidden('csrf', Security::token()); ?>
            <?= Form::hidden('from', Arr::get($_SERVER, 'HTTP_REFERER', '/') ); ?>
        </form>
    </div>

    <div class="right">		
		<h3>Регистрация</h3>
        <form class="ajaxfree" action="/user/register" method="post">
            <div class="input_text mt20">
                <input type="text" name="email" placeholder="Email" required="required" value="<?= Arr::get($_POST, 'email', '') ?>" />
                <p class="mt15">На указанный e-mail адрес будет отправлена ссылка на подтверждение регистрации</p>
            </div>
            <div class="input_text mt15">
                <input type="password" name="password" placeholder="Пароль" autocomplete="off"  required="required"/>
                <p class="mt15">Разрешено использовать буквы и цифры. Пароль должен быть длиной от 6 символов</p>
            </div>
            <div class="input_text mt15"><input type="password" name="password_confirm" placeholder="Повторите пароль" autocomplete="off"  required="required"/></div>
            
            <?= Form::hidden('csrf', Security::token()); ?>
            <?= Form::hidden('from', Arr::get($_SERVER, 'HTTP_REFERER', '/') ); ?>
            <div class="mt15">
                <input type="submit" value="Зарегистрироваться" />
            </div>
        </form>
    </div>

</div>


 