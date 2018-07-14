<?
    $hideClass = $hidePageTypesBlock ? 'hide' : '';
?>
<div data-module="pageTypeSelector">
    <module-settings hidden>
        {

        }
    </module-settings>
</div>
<div class="form-type-selector <?= $hideClass ?>">
    <div class="form-type-selector__content">

        <span class="form-type-selector__separator"></span>

        <div class="form-type-selector__item js-form-type-selector__item">
            <img class="form-type-selector__item-photo" src="<?= $user->photo ?>" alt="<?= $user->name ?>">
            <input type="radio" class="js-page-type" name="isPersonalBlog" value="<?= $isPersonalBlog ?>" id="isPersonalBlog">
            <label for="isPersonalBlog">Блог</label>
        </div>

        <? if ($user->isAdmin()): ?>
            <div class="form-type-selector__item js-form-type-selector__item">
                <input type="radio" name="isNews" id="isNews" value="<?= $isNews ?>">
                    <? include(DOCROOT . 'public/app/svg/news-icon.svg'); ?>
                <label for="isNews">Новость</label>
            </div>

            <div class="form-type-selector__item js-form-type-selector__item">
                <input type="radio" name="isPage" id="isPage" value="<?= $isPage ?>">
                <? include(DOCROOT . 'public/app/svg/page-icon.svg'); ?>
                <label for="isPage">Страница</label>
            </div>

            <div class="form-type-selector__item js-form-type-selector__item">
                <input type="radio" name="isEvent" id="isEvent" value="<?= $isEvent ?>">
                    <? include(DOCROOT . 'public/app/svg/event-icon.svg'); ?>
                <label for="isEvent">Событие</label>
            </div>
        <? endif ?>

        <div class="form-type-selector__item js-form-type-selector__item">
            <input type="radio" name="isCommunity" id="isCommunity" value="<?= $isCommunity ?>">
                <? include(DOCROOT . 'public/app/svg/community-icon.svg'); ?>
            <label for="isCommunity">Сообщество</label>
        </div>

        <span class="form-type-selector__island-settings island-settings">
            <? /*include(DOCROOT . 'public/app/svg/ellipsis.svg'); */?>
        </span>
    </div>
</div>