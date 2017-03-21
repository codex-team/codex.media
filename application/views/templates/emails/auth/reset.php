<?
    $resetPasswordLink = "http<?= Arr::get($_SERVER, 'HTTPS') ? 's' : '' ?>://<?= $_SERVER['HTTP_HOST']; ?>/reset/<?= $hash; ?>";
?>
Здравствуйте, <?= $user->name; ?>!

Вы запросили сброс пароля на <?= $_SERVER['HTTP_HOST']; ?>. Чтобы установить новый пароль, перейдите по следующей ссылке:

<?= $resetPasswordLink ?>

Если вы получили это письмо по ошибке, можете проигнорировать его.
