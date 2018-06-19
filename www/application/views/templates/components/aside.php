<aside class="island main-aside <?= !empty($site_info['branding']) ? 'main-aside--offset-top' : '' ?>">

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

    <div class="mobile-menu-holder" id="js-mobile-menu-holder">

        <? if ($user->id): ?>
            <div class="user-panel clearfix">
                <a class="fl_r user-panel__logout" href="/logout" data-title="Выйти">
                    <i class="icon-logout"></i>
                </a>
                <a href="/user/<?= $user->id ?>">
                    <img src="<?= $user->photo ?>" name="js-img-updatable" />
                    <span class="user-panel__name">
                        <?= $user->shortName ?>
                    </span>
                </a>
            </div>
        <? endif ?>

        <? /* Email confirmation */ ?>
        <? if ($user->id && !$user->isConfirmed): ?>

            <?= View::factory('templates/components/email_confirm')->render(); ?>

        <? endif; ?>

        <?= View::factory('templates/components/menu')->render(); ?>

        <? if (!$user->id): ?>
            <section class="aside-section">
                <a class="button master" href="/auth">Войти на сайт</a>
            </section>
        <? endif ?>

        <? /* Footer */ ?>
        <?= View::factory('templates/components/footer')->render(); ?>


    </div>

</aside>
