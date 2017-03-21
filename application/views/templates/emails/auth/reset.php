<?
    $resetPasswordLink = "http<?= Arr::get($_SERVER, 'HTTPS') ? 's' : '' ?>://<?= $_SERVER['HTTP_HOST']; ?>/reset/<?= $hash; ?>";

?>

<h2>Здравствуйте, <?= $user->name; ?>!</h2>
<div>
    <p>
        Вы запросили сброс пароля на <?= $_SERVER['HTTP_HOST']; ?>. Чтобы установить новый пароль, перейдите по следующей ссылке:
    </p>
    <p>
        <a href="<?= $resetPasswordLink ?>" style="color: blue;"><?= $resetPasswordLink ?></a>
    </p>
    <p>
        Если вы не запрашивали смену пароля, то проигнорируйте это письмо.
    </p>
</div>
