<div class="social_auth">
	Вы можете войти на сайт через аккаунт ВКонтакте
	<div class="social_buttons">
	    <a class="button main" href="/login/vk"><i class="pic vk"></i>Войти через VK.com</a>
    </div>
</div>

<div class="auth_form">


	

	<div class="fl_l left">		
		<h3>Вход</h3>
	    Введите email и пароль

        <form class="ajaxfree" action="/login" method="post">
            <div class="input_text mt20"><input type="text" name="email" placeholder="Email" required="required" value="<?= Arr::get($_POST, 'email', '') ?>" /></div>
            <div class="input_text mt15"><input type="password" name="password" placeholder="Пароль" autocomplete="off"  required="required"/></div>

            <div class="mt15">
                <input type="submit" value="Войти" />
                <a href="/recover" class="form_bottom_link">Восстановить пароль</a>
            </div>

            <?= Form::hidden('csrf', Security::token()); ?>
            <?= Form::hidden('from', Arr::get($_SERVER, 'HTTP_REFERER', '/') ); ?>
        </form>
    </div>

    <div class="right">		
		<h3>Регистрация</h3>
        <form class="ajaxfree" action="/login" method="post">
            <div class="input_text mt20"><input type="text" name="email" placeholder="Email" required="required" value="<?= Arr::get($_POST, 'email', '') ?>" /></div>
            <div class="input_text mt15"><input type="password" name="password" placeholder="Пароль" autocomplete="off"  required="required"/></div>
            <div class="input_text mt15"><input type="password" name="password2" placeholder="Повторите пароль" autocomplete="off"  required="required"/></div>

            <?= Form::hidden('csrf', Security::token()); ?>
            <?= Form::hidden('from', Arr::get($_SERVER, 'HTTP_REFERER', '/') ); ?>
        </form>
    </div>

</div>


 