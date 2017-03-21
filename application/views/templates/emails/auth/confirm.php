<?
    $confirmProfile = "http<?= Arr::get($_SERVER, 'HTTPS') ? 's' : '' ?>://<?= $_SERVER['HTTP_HOST']; ?>/confirm/<?= $hash; ?>";
?>

<h2>Здравствуйте, <?= $user->name; ?>!</h2>
<div>
    <p>
        Вы зарегистрировались на <?= $_SERVER['HTTP_HOST']; ?>. Чтобы подтвердить адрес электронной почты, перейдите по следующей ссылке:
    </p>
    <p>
        <a href="<?= $confirmProfile ?>" style="color: blue;"><?= $confirmProfile ?></a>
    </p>
    <p>
        Если вы получили это письмо по ошибке, можете проигнорировать его.
    </p>
</div>
