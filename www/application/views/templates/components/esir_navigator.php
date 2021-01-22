<div
    class="schools-navigator"
    data-module="search"
    onclick="document.body.classList.add('navigator-shown'); var widget = document.getElementById('widgetNavigatorGovSpbRu'); widget && widget.click(); codex.search.show();"
>
    <module-settings hidden>
        {
        "elementId": "search-wrapper",
        "modalId": "search-modal",
        "closerId": "search-exit",
        "inputId": "search-input",
        "resultsId": "search-results",
        "placeholderId": "search-placeholder"
        }
    </module-settings>
    <div class="schools-navigator__included">
        Сайт включен в каталог ЕСИР
    </div>
    <div class="schools-navigator__navigator">
        Навигатор по гос. сайтам Санкт-Петербурга
    </div>
    <div class="schools-navigator__search">Поиск по сайту</div>
</div>
<? include(DOCROOT . 'public/app/svg/clip.svg') ?>
