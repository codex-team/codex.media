<?
    $protocol = "http" . (Arr::get($_SERVER, 'HTTPS') ? 's' : '') . "://";
    $host     = $_SERVER['HTTP_HOST'];
    $uri      = "/confirm/" . $hash;

    $link = $protocol . $host . $uri;
?>
Здравствуйте, <?= $user->name; ?>!

Вы зарегистрировались на <?= $_SERVER['HTTP_HOST']; ?>. Чтобы подтвердить адрес электронной почты, перейдите по следующей ссылке:

<?= $link ?>


Если вы получили это письмо по ошибке, можете проигнорировать его.
