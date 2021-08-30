<!DOCTYPE html>
<html>
<head>

    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
    <meta name="language" content="<?= I18n::$lang ?>">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <meta property="og:site_name" content="<?= Arr::get($site_info, 'title', 'CodeX Media') ?>" />

    <title>
        <?= $title ?: Arr::get($site_info, 'title', 'CodeX Media') . ': ' . Arr::get($site_info, 'description', '') ?>
    </title>

    <base href="/" />

    <!-- <link href="https://fonts.googleapis.com/css?family=Lora:400,400i,700&subset=cyrillic" rel="stylesheet"> -->

    <link rel="stylesheet" type="text/css" media="all" href="/public/build/bundle.css?v=<?= filemtime('public/build/bundle.css'); ?>">
    <link rel="icon" type="image/png" href="/favicon.png">

    <? if (!empty($site_info['meta_image'])): ?>
        <meta name="image" property="og:image"  content="<?= $site_info['meta_image'] ?>" />
        <link rel="image_src" href="<?= $site_info['meta_image'] ?>" />
    <? endif; ?>

    <? if (!empty($_SERVER['HAWK_TOKEN'])): ?>
        <script src="/public/build/HawkCatcher.bundle.js?v=<?= filemtime('public/build/HawkCatcher.bundle.js'); ?>" onload="new HawkCatcher({token: '<?= $_SERVER['HAWK_TOKEN'] ?>'});"></script>
    <? endif; ?>
</head>
<?
    $bodyModifiers = [];

    $possibleModifiers = [
       [!empty($site_info['branding']) && empty($hideBranding), 'body--with-branding'],
       [!empty($contentOnly), 'body--content-only']
    ];

    foreach ($possibleModifiers as $modifiers) {
        if ($modifiers[0]) {
            $bodyModifiers[] = $modifiers[1];
        }
    }
?>
<body class="<?= implode(' ', $bodyModifiers) ?>">
    <?= View::factory('templates/components/header')->render(); ?>

    <? if (empty($hideBranding)): ?>
        <?= View::factory('templates/components/branding')->render(); ?>
    <? endif; ?>

    <div class="center-col" id="js-layout-holder" data-module="layout">
        <div class="grid-cols-wrapper">
            <? /* Left */ ?>
            <div class="grid-col grid-col--left">
                <? if (!empty($aside)): ?>
                    <?= $aside ?>
                <? else: ?>
                    <?= View::factory('templates/components/aside')->render(); ?>
                <? endif; ?>
            </div>

            <? /* Main block for page */ ?>
            <div class="grid-content <?= !empty($contentOnly) ? 'grid-content--stretched' : '' ?>">
                <?= $content ?>
            </div>

            <? /* Right col */ ?>
            <?= View::factory('templates/components/right_col')->render(); ?>
        </div>
    </div>

    <script>window.csrf = '<?= Security::token(); ?>';</script>
    <script src="/public/build/codex.bundle.js?v=<?= filemtime('public/build/codex.bundle.js'); ?>" onload="codex.init({uploadMaxSize : <?= UPLOAD_MAX_SIZE ?>})"></script>
    <script src="/public/extensions/emoji-parser/specc-emoji.js?v=<?= filemtime('public/extensions/emoji-parser/specc-emoji.js') ?>" onload="Emoji.parse()"></script>

    <? if (Kohana::$environment === Kohana::PRODUCTION): ?>
        <?= View::factory('templates/components/gov/esir')->render(); ?>
        <?= View::factory('templates/components/gov/gosuslugi-feedback')->render(); ?>

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
