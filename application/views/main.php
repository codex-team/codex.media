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

    <!-- <link href="https://fonts.googleapis.com/css?family=Lora:400,400i,700&subset=cyrillic" rel="stylesheet"> -->

    <? if ( Kohana::$environment === Kohana::PRODUCTION ): ?>

        <? if ( !empty($_SERVER['HAWK_TOKEN']) && $_SERVER['HAWK_TOKEN'] ): ?>
            <script src="https://cdn.rawgit.com/codex-team/hawk.client/master/hawk.js" onload="hawk.init('<?= $_SERVER['HAWK_TOKEN'] ?>');"></script>
        <? endif; ?>

    <? endif; ?>

    <link rel="stylesheet" type="text/css" media="all" href="/public/build/bundle.css?v=<?= filemtime('public/build/bundle.css'); ?>">
    <link rel="icon" type="image/png" href="/favicon.png">

    <meta name="image" property="og:image"  content="https://school332.ru/public/app/img/meta-image.png" />
    <link rel="image_src" href="https://school332.ru/public/app/img/meta-image.png" />


    <script src="/public/build/bundle.js?v=<?= filemtime('public/build/bundle.js'); ?>"></script>

</head>
<body>

    <?= View::factory('templates/components/esir_navigator')->render(); ?>

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


    <script>

        window.csrf = '<?= Security::token(); ?>';

        codex.docReady(function () {

            codex.init({
                uploadMaxSize : <?= UPLOAD_MAX_SIZE ?>
            });

        });

    </script>

    <? if ( empty($contentOnly) ): ?>

        <?
            $specialPath = 'https://cdn.ifmo.su/special/v1.2';

            if ( Kohana::$environment === Kohana::DEVELOPMENT ) {
                $specialPath = '/public/extensions/codex.special';
            }
        ?>

        <script src="<?= $specialPath ?>/codex-special.min.js" onload="codexSpecial.init({blockId : 'js-contrast-version-holder',})"></script>

    <? endif; ?>

    <script src="/public/extensions/emoji-parser/specc-emoji.js?v=<?= filemtime('public/extensions/emoji-parser/specc-emoji.js') ?>" onload="Emoji.parse()"></script>

    <? if ( Kohana::$environment === Kohana::PRODUCTION ): ?>

        <? if ( !empty($_SERVER['ENABLE_GOV_SITE_WIDGET']) && $_SERVER['ENABLE_GOV_SITE_WIDGET'] ): ?>
            <script type="text/javascript" src="https://esir.gov.spb.ru/static/widget/js/widget.js" charset="utf-8"></script>
        <? endif; ?>

        <? if ( !empty($_SERVER['YANDEX_METRIKA_ID'] )): ?>
            <!-- Yandex.Metrika counter -->
            <script type="text/javascript">
                (function (d, w, c) {
                    (w[c] = w[c] || []).push(function() {
                        try {
                            w.yaCounter<?= $_SERVER['YANDEX_METRIKA_ID'] ?> = new Ya.Metrika({
                                id:<?= $_SERVER['YANDEX_METRIKA_ID'] ?>,
                                clickmap:true,
                                trackLinks:true,
                                accurateTrackBounce:true
                            });
                        } catch(e) { }
                    });

                    var n = d.getElementsByTagName("script")[0],
                        s = d.createElement("script"),
                        f = function () { n.parentNode.insertBefore(s, n); };
                    s.type = "text/javascript";
                    s.async = true;
                    s.src = "https://mc.yandex.ru/metrika/watch.js";

                    if (w.opera == "[object Opera]") {
                        d.addEventListener("DOMContentLoaded", f, false);
                    } else { f(); }
                })(document, window, "yandex_metrika_callbacks");
            </script>
            <noscript><div><img src="https://mc.yandex.ru/watch/<?= $_SERVER['YANDEX_METRIKA_ID'] ?>" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
            <!-- /Yandex.Metrika counter -->
        <? endif; ?>

    <? endif; ?>

</body>
</html>
