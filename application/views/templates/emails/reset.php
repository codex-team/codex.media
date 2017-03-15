<h2>Здравствуйте, <?= $user->name; ?></h2>
<div>
    Вы запросили сброс пароля на <a href="<?= $_SERVER['HTTP_HOST']; ?>" style="color: blue;"><?= $_SERVER['HTTP_HOST']; ?></a>. Чтобы сменить пароль,
    перейдите по <a href="<?= $_SERVER['HTTP_HOST']; ?>/reset/<?= $hash; ?>" style="color: blue;">ссылке</a>.
</div>
<div>
    Если вы не запрашивали смену пароля, можете смело проигнорировать это письмо.
</div>
