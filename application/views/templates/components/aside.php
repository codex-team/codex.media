<aside class="island main-aside <?= !empty($site_info['branding']) ? 'main-aside--offset-top' : '' ?>">

    <a class="site-head clear" href="/">

        <?php
            $logoIsEmpty = empty($site_info['logo']);
            $logoClasses = 'site-head__logo ';
            $logoClasses .= $user->isAdmin ? 'site-head__logo--admin ' : '';
            $logoClasses .= $logoIsEmpty ? 'site-head__logo--empty' : '';
        ?>

        <span class="<?= $logoClasses ?>" data-placeholder="<?= mb_substr($site_info['title'], 0, 1, "UTF-8"); ?>">

            <?php if (!empty($site_info['logo'])): ?>
                <img id="js-site-logo" src="/upload/logo/m_<?=  $site_info['logo'] ?>">
            <?php endif ?>

            <?php if ($user->isAdmin): ?>
                <span class="site-head__logo-upload" onclick="codex.logo.change.call(this, event)">
                    <?php include(DOCROOT . "public/app/svg/picture-upload.svg") ?>
                </span>
            <?php endif; ?>
        </span>


        <div class="r_col site-head__title">
            <?= $site_info['title'] ?><br>
            <?= $site_info['city'] ?>
        </div>

        <span class="mobile-menu-toggler" id="js-mobile-menu-toggler">
            <?php include(DOCROOT . "public/app/svg/menu.svg") ?>
        </span>

    </a>

    <div class="mobile-menu-holder" id="js-mobile-menu-holder">

        <?php /* User badge */ ?>
        <?php if ($user->id): ?>
            <a class="fl_r logout" href="/logout" data-title="Выйти">

                <i class="icon-logout"></i>

            </a>

            <a class="user_panel cf" href="/user/<?= $user->id ?>">

                <img src="<?= $user->photo ?>" name="js-img-updatable" />
                <span class="user_panel__name overflow_long"><?= $user->name ?></span>

            </a>
        <?php endif ?>

        <?php /* Email confirmation */ ?>
        <?php if ($user->id && !$user->isConfirmed): ?>

            <?= View::factory('templates/components/email_confirm')->render(); ?>

        <?php endif; ?>

        <?= View::factory('templates/components/menu')->render(); ?>

        <?php if (!$user->id): ?>
            <section class="aside-section">
                <a class="button master" href="/auth">Войти на сайт</a>
            </section>
        <?php endif ?>

        <?php /* Footer */ ?>
        <?= View::factory('templates/components/footer')->render(); ?>


    </div>

</aside>
