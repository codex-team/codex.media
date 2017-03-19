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

    <div class="stretched-cover">

        <img id="brandingCover" src="" width="100%" height="100%">

        <a id="changeCoverButton" class="stretched-cover__change-button">
            <i class="stretched-cover__change-button--icon icon-camera"></i>
            Изменить обложку
        </a>

    </div>

    <div class="center-col">

        <div class="grid-cols-wrapper">

            <? /* Left */ ?>
            <div class="grid-col grid-col--left">

                <?= View::factory('templates/components/aside')->render(); ?>

            </div>

            <? /* Main block for page */ ?>
            <div class="grid-content">
                <?= $content ?>
            </div>

        </div>

    </div>

    <? /* Scripts */ ?>

    <script src="/public/extensions/codex.special/codex-special.v.1.0.2.min.js?v=2"></script>

    <script>

        window.csrf = '<?= Security::token(); ?>';

        codex.docReady(function() {

            var changeCoverButton = document.getElementById('changeCoverButton'),
                brandingCover = document.getElementById('brandingCover');

            changeCoverButton.addEventListener('click', function() {

                codex.transport.init({
                    url : '/file/transport/',
                    accept : 'image/*',
                    beforeSend : function() {

                        var fileReader = new FileReader(),
                            input = codex.transport.input,
                            uploadedfile = input.files[0];

                        /** Load from Browsers memory */
                        fileReader.readAsDataURL(uploadedfile);
                        fileReader.onload = function(e) {

                            brandingCover.classList.add('stretched-cover__branding');
                            brandingCover.src = e.target.result;

                        };


                    },
                    success : function(result) {

                        var data = JSON.parse(result);

                        if ( data.success ) {

//                            brandingCover.src =  меняем вот тут файл
                            brandingCover.classList.remove('stretched-cover__branding');

                        }

                    },
                    error : function() {

                        brandingCover.src = '';

                    }
                })

            });

        });

    </script>

    <? /* end Scripts */ ?>


    <? if ( Kohana::$environment === Kohana::PRODUCTION ): ?>

        <!-- Yandex.Metrika counter -->

        <!-- /Yandex.Metrika counter -->

    <? endif; ?>

</body>
</html>
