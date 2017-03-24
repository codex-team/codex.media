<aside class="island main-aside">

    <a class="site-head clear" href="/">

        <i class="spb_shield fl_l"></i>

        <div class="r_col">
            <?= $site_info['title'] ?><br>
            <?= $site_info['city'] ?>
        </div>

        <span class="mobile-menu-toggler" onclick="codex.content.toggleMobileMenu(event);">
            <? include(DOCROOT . "public/app/svg/menu.svg") ?>
        </span>

    </a>

    <div class="mobile-menu-holder" id="js-mobile-menu-holder">

        <? /* User badge */ ?>
        <? if ( $user->id ): ?>
            <a class="fl_r logout" href="/logout" data-title="Выйти">

                <i class="icon-logout"></i>

            </a>

            <a class="user_panel cf" href="/user/<?= $user->id ?>">

                <img src="<?= $user->photo ?>" name="js-img-updatable" />
                <span class="user_panel__name overflow_long"><?= $user->name ?></span>

            </a>
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
