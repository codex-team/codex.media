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

    <? if (!empty($_SERVER['HAWK_TOKEN'])): ?>
        <script src="https://rawgit.com/codex-team/hawk.client/master/hawk.js" onload="hawk.init('<?= $_SERVER['HAWK_TOKEN'] ?>');"></script>
    <? endif; ?>

    <link rel="stylesheet" type="text/css" media="all" href="/public/build/bundle.css?v=<?= filemtime('public/build/bundle.css'); ?>">
    <link rel="icon" type="image/png" href="/favicon.png">

     <!--  TODO: Add corresponding meta -->
     <!--  <meta name="image" property="og:image"  content="https://school332.ru/public/app/img/meta-image.png" /> -->
     <!--  <link rel="image_src" href="https://school332.ru/public/app/img/meta-image.png" /> -->

    <script src="/public/build/bundle.js?v=<?= filemtime('public/build/bundle.js'); ?>" onload="codex.init({uploadMaxSize : <?= UPLOAD_MAX_SIZE ?>})"></script>

</head>
<?
    $bodyModifiers = array();

    if (!empty($site_info['branding']) && empty($hideBranding)){
        $bodyModifiers[] = 'body--with-branding';
    }

    if (!empty($contentOnly)){
        $bodyModifiers[] = 'body--content-only';
    }
?>
<body class="<?= implode(' ', $bodyModifiers) ?>">

    <? if (empty($hideBranding)): ?>
        <?= View::factory('templates/components/branding')->render(); ?>
    <? endif; ?>

    <div class="center-col center-col-1075" id="js-layout-holder">

        <div class="grid-cols-wrapper">

            <? /* Left */ ?>
            <div class="grid-col grid-col--left grid-col--organizations-aside">

                <?= View::factory('templates/components/organizations_aside')->render(); ?>

            </div>

            <? /* Main block for page */ ?>
            <div class="grid-content grid-col--content <?= !empty($contentOnly) ? 'grid-content--stretched' : '' ?>">
                <?= $content ?>
            </div>

            <? /* Right */ ?>
            <div class="grid-col grid-col--right grid-col--auth-aside">

                <?= View::factory('templates/components/aside')->render(); ?>

            </div>

        </div>

    </div>


    <script>

        window.csrf = '<?= Security::token(); ?>';

    </script>

    <script src="/public/extensions/emoji-parser/specc-emoji.js?v=<?= filemtime('public/extensions/emoji-parser/specc-emoji.js') ?>" onload="Emoji.parse()"></script>

    <? if (Kohana::$environment === Kohana::PRODUCTION): ?>

        <? if (!empty($_SERVER['YANDEX_METRIKA_ID'])): ?>
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