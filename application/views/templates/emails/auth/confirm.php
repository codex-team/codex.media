<h2>Здравствуйте, <?= $user->name; ?>!</h2>
<div>
    <p>
        Вы зарегистрировались на <a href="<?= $_SERVER['HTTP_HOST']; ?>" style="color: blue;"><?= $_SERVER['HTTP_HOST']; ?></a>. Чтобы подтвердить адрес электронной почты, перейдите по следующей ссылке:
    </p>
    <p>
        <a href="<?= $_SERVER['HTTP_HOST']; ?>/confirm/<?= $hash; ?>" style="color: blue;"><?= $_SERVER['HTTP_HOST']; ?>/confirm/<?= $hash; ?></a>
    </p>
    <p>
        Если вы получили это письмо по ошибке, можете проигнорировать его.
    </p>
</div>
