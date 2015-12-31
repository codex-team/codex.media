<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?= substr(I18n::$lang, 0, 2); ?>" lang="<?= substr(I18n::$lang, 0, 2); ?>">

<head>

    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <meta name="language" content="<?= I18n::$lang ?>" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <meta property="og:site_name" content="<?= $GLOBALS['SITE_NAME'] ?>" />

    <title><?= $title ? $title : $GLOBALS['SITE_NAME'] . ': ' . $GLOBALS['SITE_SLOGAN'] ?></title>

    <base href="/" />
    <script type="text/javascript" src="<?= isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] ? 'https' : 'http'; ?>://code.jquery.com/jquery-1.8.3.min.js"></script>

    <link href="//fonts.googleapis.com/css?family=PT+Sans:400italic,400,700italic,700" rel="stylesheet" type="text/css">
    <link href='http://fonts.googleapis.com/css?family=PT+Serif+Caption&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=PT+Serif:400,400italic,700,700italic&subset=latin,cyrillic' rel='stylesheet' type='text/css'>


    <link rel="stylesheet" type="text/css" media="all" href="/public/css/main.css?v=<?= time() ?>">
    <link rel="icon" type="image/png" href="/favicon.png">

    <script src="/public/js/main.js?v=<?= time() ?>"></script>

</head>
<body>

    <div class="main_wrap">

        <aside>
            <a class="main_logo clear" href="/">
                <i class="spb_shield fl_l"></i>
                <div class="r_col">
                    <?= $site_info->title ?><br>
                    <?= $site_info->city ?>
                </div>
            </a>

            <? if ( $user->id ): ?>
                <a class="user_panel cf" href="/user/<?= $user->id ?>">
                    <img src="<?= $user->photo ?>" />
                    <?= $user->name ?>
                </a>
            <? endif ?>

            <ul class="menu">
                <? $menu = $methods->getSiteMenu(); ?>
                <? foreach ($menu as $item): ?>
                    <li><a href="/page/<?= $item->id ?>/<?= $item->uri ?>"><?= $item->title ?></a></li>
                <? endforeach ?>
            </ul>

            <? if (!$user->id): ?>
                <a class="button green" href="/auth">Войти на сайт</a>
            <? else: ?>
                <a class="button logout" href="/logout">Выйти</a>
            <? endif ?>


            <ul class="submenu">
                <li><a href="/admin">Admin</a></li>
            </ul>

            <footer class="site_footer">
                <p><?= $site_info->full_name ?></p>
                <p>
                    <a href="/contacts">
                        <?= $site_info->address ?>
                    </a>
                </p>
                <p>
                    Телефон:&nbsp;<?= $site_info->phone ?><br />
                    Факс:&nbsp;<?= $site_info->fax ?><br />
                    Почта:&nbsp;<?= $site_info->email ?>
                </p>
            </footer>

        </aside>

        <div class="page_wrap">
            <?= $content ?>
        </div>


    </div>

    <div id="utils" class="hidden">
        <iframe name="transport" _onload="transport.checkErrorLoading(event)"></iframe>
        <form class="ajaxfree" id="transport_form" method="post" enctype="multipart/form-data"  target="transport" action="/ajax/transport" accept-charset="utf-8" >
            <input type="file" name="files" id="transportInput"/>
        </form>
    </div>





    <? if ( Kohana::$environment === Kohana::PRODUCTION ): ?>

        <!-- Yandex.Metrika counter -->

        <!-- /Yandex.Metrika counter -->

    <? endif; ?>
</body>
</html>