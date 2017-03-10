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

                <img src="<?= $user->photo ?>" />
                <span class="user_panel__name overflow_long"><?= $user->name ?></span>

            </a>
        <? endif ?>

        <? if ($user->id && !$user->isConfirmed): ?>

        <span class="confirm-reminder__wrapper">
            <a class="confirm-reminder__arrow fl_r" href="/user/settings">
                <? include(DOCROOT . "public/app/svg/arrow-right.svg") ?>
            </a>

            <a class="confirm-reminder" href="/user/settings">

                <span class="confirm-reminder__attention">
                    <? include(DOCROOT . "public/app/svg/attention.svg") ?>
                </span>
                <span class="confirm-reminder__message">Подтвердите e-mail</span>

            </a>
        </span>

        <? endif; ?>

        <? /* Menu */ ?>
        <ul class="menu">

            <? foreach ($site_menu as $item): ?>
                <li><a href="/p/<?= $item->id ?>/<?= $item->uri ?>"><?= $item->title ?></a></li>
            <? endforeach ?>

        </ul>

        <? if (!$user->id): ?>
            <section class="aside-section">
                <a class="button master" href="/auth">Войти на сайт</a>
            </section>
        <? endif ?>

        <? /* Footer */ ?>
        <?= View::factory('templates/components/footer')->render(); ?>


    </div>

</aside>