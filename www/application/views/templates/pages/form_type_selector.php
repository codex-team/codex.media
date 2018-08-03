<? $currentType = isset($selectedPageType) && ($selectedPageType != 0) ? $selectedPageType : $page->type ?>

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

        <div class="form-type-selector__item js-form-type-selector__item" data-type="<?= Model_Page::BLOG ?>">
            <img class="form-type-selector__item-photo" src="<?= $user->photo ?>" alt="<?= $user->name ?>">
            Блог
        </div>

        <? if ($user->isAdmin()): ?>
            <div class="form-type-selector__item js-form-type-selector__item" data-type="<?= Model_Page::NEWS ?>">
                <? include(DOCROOT . 'public/app/svg/news-icon.svg'); ?>
                Новость
            </div>

            <div class="form-type-selector__item js-form-type-selector__item" data-type="<?= Model_Page::PAGE ?>">
                <? include(DOCROOT . 'public/app/svg/page-icon.svg'); ?>
                Страница
            </div>

            <? if (Arr::get($_SERVER, 'ENABLE_EVENT_PAGES')): ?>
                <div class="form-type-selector__item js-form-type-selector__item" data-type="<?= Model_Page::EVENT ?>">
                    <? include(DOCROOT . 'public/app/svg/event-icon.svg'); ?>
                    Событие
                </div>
            <? endif ?>
        <? endif ?>

        <div class="form-type-selector__item js-form-type-selector__item" data-type="<?= Model_Page::COMMUNITY ?>">
            <? include(DOCROOT . 'public/app/svg/community-icon.svg'); ?>
            Сообщество
        </div>

        <span class="form-type-selector__island-settings island-settings">
            <? /*include(DOCROOT . 'public/app/svg/ellipsis.svg'); */?>
        </span>
    </div>
</div>

<input type="hidden" class="js-page-type-input" name="type" value="<?= $page->type ?>">