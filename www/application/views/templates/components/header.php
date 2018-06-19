<a class="site-head site-head--in-aside clear" href="/">
    <?
    $logoIsEmpty = empty($site_info['logo']);
    $logoClasses = 'site-head__logo ';
    $logoClasses .= $user->isAdmin ? 'site-head__logo--admin ' : '';
    $logoClasses .= $logoIsEmpty ? 'site-head__logo--empty' : '';
    ?>

    <span class="<?= $logoClasses ?>" data-placeholder="<?= mb_substr($site_info['title'], 0, 1, "UTF-8"); ?>">
        <? if (!empty($site_info['logo'])): ?>
            <img id="js-site-logo" src="/upload/logo/m_<?=  $site_info['logo'] ?>">
        <? endif ?>
        <? if ($user->isAdmin): ?>
            <span class="site-head__logo-upload" onclick="codex.logo.change.call(this, event)">
                <? include(DOCROOT . "public/app/svg/picture-upload.svg") ?>
            </span>
        <? endif; ?>
    </span>

    <div class="r_col site-head__title">
        <?= $site_info['title'] ?><br>
        <?= $site_info['city'] ?>
    </div>

    <span class="mobile-menu-toggler" id="js-mobile-menu-toggler">
        <? include(DOCROOT . "public/app/svg/menu.svg") ?>
    </span>
</a>