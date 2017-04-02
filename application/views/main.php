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

    <? if (empty($contentOnly)): ?>
        <?= View::factory('templates/components/branding')->render(); ?>
    <? endif; ?>

    <div class="center-col" id="js-layout-holder">

        <div class="grid-cols-wrapper">

            <? if(empty($contentOnly)): ?>
                <? /* Left */ ?>
                <div class="grid-col grid-col--left">

                    <?= View::factory('templates/components/aside')->render(); ?>

                </div>
            <? endif; ?>

            <? /* Main block for page */ ?>
            <div class="grid-content <?= !empty($contentOnly) ? 'grid-content--stretched' : '' ?>">
                <?= $content ?>
            </div>

        </div>

    </div>

    <? /* Scripts */ ?>
    <script>
        window.csrf = '<?= Security::token(); ?>';
    </script>

    <? if(empty($contentOnly)): ?>
        <script src="https://cdn.ifmo.su/special/v1.1/codex-special.min.js" onload="codexSpecial.init({blockId : 'js-contrast-version-holder',})"></script>
    <? endif; ?>
    <? /* end Scripts */ ?>


    <? if ( Kohana::$environment === Kohana::PRODUCTION ): ?>

        <!-- Yandex.Metrika counter -->

        <!-- /Yandex.Metrika counter -->

    <? endif; ?>

</body>
</html>
