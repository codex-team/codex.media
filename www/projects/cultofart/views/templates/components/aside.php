<aside class="island island--squared main-aside">

    <div class="mobile-menu-holder" id="js-mobile-menu-holder">

        <? if ($user->id): ?>
            <div class="user-panel auth-user-panel clearfix">
                <a href="/user/<?= $user->id ?>">
                    <img src="<?= $user->photo ?>" name="js-img-updatable" />
                </a>
                <div class="auth-user-panel__info">
                    <a href="/user/<?= $user->id ?>" class="user-panel__name auth-user-panel__name">
                        <?= $user->shortName ?>
                    </a>
                    <div class="auth-user-panel__settings">
                        <a href="\user\settings">Settings</a>
                        <a href="/logout">Logout</a>
                    </div>
                </div>
            </div>
            <?=  View::factory('templates/components/own_organizations')->render();?>
            <a href="" class="auth-user-panel__add-button">+  New page</a><br>
            <a href="" class="auth-user-panel__add-button">+  New event</a>
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


    </div>

</aside>
