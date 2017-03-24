<?
    $brandingStyle = '';

    if (Arr::get($site_info, 'branding')) {
        $branding = 'upload/branding/o_' . Arr::get($site_info, 'branding');
        $brandingStyle = 'style="background-image: url(' . $branding . ')"';
    }

?>

<div class="branding" id="brandingSection" <?= $brandingStyle ?>>
    <div class="branding__content center-col">
        <? if ($user->isAdmin) : ?>
            <span class="branding__button" onclick="codex.branding.change()">
                <? include(DOCROOT . "public/app/svg/camera.svg") ?>
                Изменить обложку
            </span>
        <? endif; ?>
    </div>
</div>
