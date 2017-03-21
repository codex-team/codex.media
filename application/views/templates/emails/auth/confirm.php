<?
    $protocol = "http" . (Arr::get($_SERVER, 'HTTPS') ? 's' : '') . "://";
    $host     = $_SERVER['HTTP_HOST'];
    $uri      = "/confirm/" . $hash;

    $link = $protocol . $host . $uri;
?>
Здравствуйте, <?= $user->name; ?>!\r\n
\r\n
Вы зарегистрировались на <?= $_SERVER['HTTP_HOST']; ?>. Чтобы подтвердить адрес электронной почты, перейдите по следующей ссылке:\r\n
<?= $link ?>\r\n
\r\n
Если вы получили это письмо по ошибке, можете проигнорировать его.
