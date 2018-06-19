<aside class="island main-aside <?= !empty($site_info['branding']) ? 'main-aside--offset-top' : '' ?>">

    <?= View::factory('templates/components/header')->render(); ?>

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
