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


    <link rel="stylesheet" type="text/css" media="all" href="/public/build/bundle.css?v=<?= filemtime('public/build/bundle.css'); ?>">
    <link rel="icon" type="image/png" href="/favicon.png">

    <script src="/public/build/bundle.js?v=<?= filemtime('public/build/bundle.js'); ?>"></script>

</head>
<body>

    <div class="center-col">

        <? /* Left */ ?>
        <div class="grid-col grid-col--left">

            <?= View::factory('templates/components/aside')->render(); ?>

        </div>

        <? /* Main block for page */ ?>
        <div class="grid-content">
            <?= $content ?>
        </div>

    </div>


    <!-- <div id="utils" class="hidden">

        <iframe name="transport" _onload="transport.checkErrorLoading(event)"></iframe>

        <form id="transportForm" method="post" enctype="multipart/form-data"  target="transport" action="/file/transport" accept-charset="utf-8" >

            <input type="file" name="files" id="transportInput"/>

        </form>

    </div> -->


    <? /* Scripts */ ?>

    <script src="/public/extensions/codex.special/codex-special.v.1.0.2.min.js?v=2"></script>

    <? /* end Scripts */ ?>


    <? if ( Kohana::$environment === Kohana::PRODUCTION ): ?>

        <!-- Yandex.Metrika counter -->

        <!-- /Yandex.Metrika counter -->

    <? endif; ?>

</body>
</html>
