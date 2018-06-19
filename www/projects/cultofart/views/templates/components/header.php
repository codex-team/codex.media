<header class="header">
    <div class="header__content center-col">
        <a href="" class="header__title">
            <? if (!empty($site_info['logo'])): ?>
                <img class="header__logo" src="<?=  $site_info['logo'] ?>">
            <? endif ?>
            <?= $site_info['title'] ?>
        </a>
        <span class="header__address">
            <?= $site_info['address'] ?>
        </span>
        <div class="header__lang">
            <span class="header__lang-item">RU</span>|<span class="header__lang-item">EN</span>
        </div>
        <a href="" class="header__button">
            Support the Project
        </a>
        <span class="mobile-menu-toggler header__mobile-menu-toggler" id="js-mobile-menu-toggler">
            <? include(DOCROOT . "public/app/svg/menu.svg") ?>
        </span>
    </div>
</header>