<?php
    $protocol = "http" . (Arr::get($_SERVER, 'HTTPS') ? 's' : '') . "://";
    $host = $_SERVER['HTTP_HOST'];
    $uri = "/change/" . $hash;

    $link = $protocol . $host . $uri;
?>
Здравствуйте, <?= $user['name'] ?>!

Вы запросили изменение пароля на <?= $_SERVER['HTTP_HOST'] ?>. Чтобы установить новый пароль, перейдите по следующей ссылке:

<?= $link ?>


Если вы получили это письмо по ошибке, можете проигнорировать его.
