<!DOCTYPE html>
<html>

<head>

    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
    <meta name="language" content="<?= I18n::$lang ?>">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <meta property="og:site_name" content="<?= $GLOBALS['SITE_NAME'] ?>" />

    <title><?= $title ? $title : $GLOBALS['SITE_NAME'] . ': ' . $GLOBALS['SITE_SLOGAN'] ?></title>

    <base href="/" />

    <link href="https://fonts.googleapis.com/css?family=Lora:400,400i,700&subset=cyrillic" rel="stylesheet">


    <link rel="stylesheet" type="text/css" media="all" href="/public/css/main.css?v=<?= time() ?>">
    <link rel="icon" type="image/png" href="/favicon.png">

</head>
<body>

    <div class="main_wrap">

        <? /* Left */ ?>
        <aside>

            <div class="mobile_menu_toggler fl_r" onclick="document.getElementById('js-mobile-menu-holder').classList.toggle('mobile_menu_holder--opened')"></div>

            <a class="main_logo clear" href="/">

                <i class="spb_shield fl_l"></i>

                <div class="r_col">
                    <?= $site_info['title'] ?><br>
                    <?= $site_info['city'] ?>
                </div>

            </a>

            <div class="mobile_menu_holder" id="js-mobile-menu-holder">

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

                <? /* Menu */ ?>
                <ul class="menu">

                    <? foreach ($site_menu as $item): ?>
                        <li><a href="/p/<?= $item->id ?>/<?= $item->uri ?>"><?= $item->title ?></a></li>
                    <? endforeach ?>

                </ul>

                <? /* Log in button */ ?>
                <? if (!$user->id): ?>
                    <a class="button green" href="/auth">Войти на сайт</a>
                <? endif ?>

                <? /* Footer */ ?>
                <footer class="site_footer">

                    <? /* Contacts */ ?>
                    <p><?= $site_info['full_name'] ?></p>
                    <p>
                        <a href="/contacts">
                            <?= $site_info['address'] ?>
                        </a>
                    </p>
                    <p>
                        Телефон:&nbsp;<?= $site_info['phone'] ?><br />
                        Факс:&nbsp;<?= $site_info['fax'] ?><br />
                        Почта:&nbsp;<?= $site_info['email'] ?>
                    </p>

                    <? /* codex-special block */ ?>
                    <div id="js-contrast-version-holder"></div>

                </footer>


            </div>

        </aside>

        <? /* Main block for page */ ?>
        <div class="page_wrap">
            <?= $content ?>
        </div>

        <? /* Side block */ ?>
        <div class="sidebar">

            <? include(APPPATH .'views/templates/sidebar.php') ?>

        </div>

    </div>

    <div id="utils" class="hidden">

        <iframe name="transport" _onload="transport.checkErrorLoading(event)"></iframe>

        <form id="transportForm" method="post" enctype="multipart/form-data"  target="transport" action="/file/transport" accept-charset="utf-8" >

            <input type="file" name="files" id="transportInput"/>

        </form>

    </div>


    <? /* Scripts */ ?>

    <script src="/public/js/codex.js?v=<?= filemtime('public/js/codex.js'); ?>"></script>

    <script src="/public/extensions/codex-special/codex-special.v.1.0.min.js?v=2"></script>

    <? /* end Scripts */ ?>


    <? if ( Kohana::$environment === Kohana::PRODUCTION ): ?>

        <!-- Yandex.Metrika counter -->

        <!-- /Yandex.Metrika counter -->

    <? endif; ?>

</body>
</html>
