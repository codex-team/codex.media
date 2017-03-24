<?
    $brandingStyle = '';

    if (Arr::get($site_info, 'branding')) {
        $branding = 'upload/branding/o_' . Arr::get($site_info, 'branding');
        $brandingStyle = "style=\"background-image: url($branding)\"";
    }
?>

<div class="branding" id="brandingSection" <?= $brandingStyle ?>>

    <div class="branding-content center-col">

        <? if ($user->isAdmin) : ?>
            <span id="changeBrandingButton" class="fl_r branding-content__change-button">
                <i class="icon-camera"></i>
                Изменить обложку
            </span>
        <? endif; ?>

    </div>

</div>
