<h2>Здравствуйте, <?= $user->name; ?></h2>
<div>
    Вы зарегистрировались на <a href="<?= $_SERVER['HTTP_HOST']; ?>" style="color: blue;"><?= $_SERVER['HTTP_HOST']; ?></a>. Чтобы подтвердить адрес электронной почты,
    перейдите по <a href="<?= $_SERVER['HTTP_HOST']; ?>/confirm/<?= $hash; ?>" style="color: blue;">ссылке</a>.
</div>
<div>
    Если вы не регистрировались на нашем сайте, пожалуйста, напишите в службу поддержки - <a href="mailto: <?= $GLOBALS['SITE_SUPPORT']; ?>" style="color: blue;"><?= $GLOBALS['SITE_SUPPORT']; ?></a>
</div>
