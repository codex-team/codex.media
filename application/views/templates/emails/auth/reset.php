<h2>Здравствуйте, <?= $user->name; ?>!</h2>
<div>
    <p>
        Вы запросили сброс пароля на <a href="<?= $_SERVER['HTTP_HOST']; ?>" style="color: blue;"><?= $_SERVER['HTTP_HOST']; ?></a>. Чтобы установить новый пароль, перейдите по следующей ссылке:
    </p>
    <p>
        <a href="<?= $_SERVER['HTTP_HOST']; ?>/reset/<?= $hash; ?>" style="color: blue;"><?= $_SERVER['HTTP_HOST']; ?>/reset/<?= $hash; ?></a>
    </p>
    <p>
        Если вы не запрашивали смену пароля, то проигнорируйте это письмо.
    </p>
</div>
