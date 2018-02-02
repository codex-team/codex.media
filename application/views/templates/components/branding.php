<?
    $preload = '';
    $branding = '';

    if (!empty($site_info['branding'])) {
        $preload = '/upload/branding/preload_' . $site_info['branding'];
        $branding = '/upload/branding/o_' . $site_info['branding'];
    }
?>
<div class="branding <?= !$branding ? 'branding--empty' : ''?>" id="brandingSection" data-src="<?= $branding ?>">
    <div class="branding__preloader branding__preloader--shown" style="background-image: url(<?= $preload ?>); "></div>
    <div class="branding__content center-col">
        <? if ($user->isAdmin) : ?>
            <span class="branding__button" onclick="codex.branding.change()">
                <? include(DOCROOT . "public/app/svg/camera.svg") ?>
                Изменить обложку
            </span>
        <? endif; ?>
    </div>
</div>