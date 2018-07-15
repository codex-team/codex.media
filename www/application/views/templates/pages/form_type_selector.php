<? $currentType = isset($specialPageType) && ($specialPageType != 0) ? $specialPageType : $page->type ?>

<div data-module="pageTypeSelector">
    <module-settings hidden>
        {
            "currentType" : "<?= $currentType ?>"
        }
    </module-settings>
</div>
<div class="form-type-selector">
    <div class="form-type-selector__content">

        <span class="form-type-selector__separator"></span>

        <div class="form-type-selector__item js-form-type-selector__item">
            <img class="form-type-selector__item-photo" src="<?= $user->photo ?>" alt="<?= $user->name ?>">
            <input type="radio" class="js-page-type" name="type" value="<?= Model_Page::BLOG ?>" id="isPersonalBlog">
            <label for="isPersonalBlog">Блог</label>
        </div>

        <? if ($user->isAdmin()): ?>
            <div class="form-type-selector__item js-form-type-selector__item">
                <input type="radio" name="type" id="isNews" value="<?= Model_Page::NEWS ?>">
                    <? include(DOCROOT . 'public/app/svg/news-icon.svg'); ?>
                <label for="isNews">Новость</label>
            </div>

            <div class="form-type-selector__item js-form-type-selector__item">
                <input type="radio" name="type" id="isPage" value="<?= Model_Page::PAGE ?>">
                <? include(DOCROOT . 'public/app/svg/page-icon.svg'); ?>
                <label for="isPage">Страница</label>
            </div>

            <div class="form-type-selector__item js-form-type-selector__item">
                <input type="radio" name="type" id="isEvent" value="<?= Model_Page::EVENT ?>">
                    <? include(DOCROOT . 'public/app/svg/event-icon.svg'); ?>
                <label for="isEvent">Событие</label>
            </div>
        <? endif ?>

        <div class="form-type-selector__item js-form-type-selector__item">
            <input type="radio" name="type" id="isCommunity" value="<?= Model_Page::COMMUNITY ?>">
                <? include(DOCROOT . 'public/app/svg/community-icon.svg'); ?>
            <label for="isCommunity">Сообщество</label>
        </div>

        <span class="form-type-selector__island-settings island-settings">
            <? /*include(DOCROOT . 'public/app/svg/ellipsis.svg'); */?>
        </span>
    </div>
</div>