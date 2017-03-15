/**
 * Appender is being used for ajax-loading next pages of lists
 *
 *    codex.appender.init({
 *        buttonId       : 'button_load_news',       // button for listening
 *        currentPage    : '<?= $page_number ?>',    // currentPage number
 *        url             : '/',                      // url for ajax-requests
 *        targetBlockId : 'list_of_news',           // target for appending
 *        autoLoading    : true,                     // allow loading when reach bottom while scrolling
 *    });
 */

var appender = {

    /* Pagination. Here is a number of current page */
    page : 1,

    settings : null,

    blockForItems : null,

    loadMoreButton : null,

    /**
     * Button's text for saving it.
     * On its place dots will be while news are loading
     */
    buttonText : null,

    init : function (settings) {

        this.settings = settings;

        /* Checking for existing button and field for loaded info */
        this.loadMoreButton = document.getElementById(this.settings.buttonId);

        if (!this.loadMoreButton) return false;

        this.blockForItems = document.getElementById(this.settings.targetBlockId);

        if (!this.blockForItems) return false;

        this.page        = settings.currentPage;
        this.buttonText = this.loadMoreButton.innerHTML;

        if (this.settings.autoLoading) this.autoLoading.isAllowed = true;

        console.log(this.autoLoading.isAllowed);

        this.loadMoreButton.addEventListener('click', function (event) {

            codex.appender.load();

            event.preventDefault();

            codex.appender.autoLoading.init();

        }, false);

    },

    load : function () {

        var requestUrl = this.settings.url + (parseInt(this.page) + 1);
            // separator   = '<a href="' + requestUrl + '"><div class="article post-list-item w_island separator">Page ' + (parseInt(this.page) + 1) + '</div></a>';

        codex.ajax.call({
            type: 'post',
            url: requestUrl,
            data: {},
            beforeSend : function () {

                codex.appender.loadMoreButton.innerHTML = ' ';
                codex.appender.loadMoreButton.classList.add('loading');

            },
            success : function (response) {

                response = JSON.parse(response);

                if (response.success) {

                    if (!response.pages) return;

                    /* Append items */
                    // codex.appender.blockForItems.innerHTML += separator;
                    codex.appender.blockForItems.innerHTML += response.pages;

                    /* Next page */
                    codex.appender.page++;

                    if (codex.appender.settings.autoLoading) {

                        /* Removing restriction for auto loading */
                        codex.appender.autoLoading.canLoad = true;

                    }

                    /* Checking for next page's existing. If no — hide the button for loading news and remove listener */
                    if (!response.next_page) codex.appender.disable();

                } else {

                    codex.core.showException('Не удалось подгрузить новости');

                }

                codex.appender.loadMoreButton.classList.remove('loading');
                codex.appender.loadMoreButton.innerHTML = codex.appender.buttonText;

            }

        });

    },

    disable : function () {

        codex.appender.loadMoreButton.style.display = 'none';

        if (codex.appender.autoLoading.isLaunched) {

            codex.appender.autoLoading.disable();

        }

    },

    autoLoading : {

        isAllowed : false,

        isLaunched : false,

        /**
         * Possibility to load news by scrolling.
         * Restriction for reduction requests which could be while scrolling
         */
        canLoad : true,

        init : function () {

            if (!this.isAllowed) return;

            window.addEventListener('scroll', codex.appender.autoLoading.scrollEvent);

            codex.appender.autoLoading.isLaunched = true;

        },

        disable : function () {

            window.removeEventListener('scroll', codex.appender.autoLoading.scrollEvent);

            codex.appender.autoLoading.isLaunched = false;

        },

        scrollEvent : function () {

            var scrollReachedEnd = window.pageYOffset + window.innerHeight >= document.body.clientHeight;

            if (scrollReachedEnd && codex.appender.autoLoading.canLoad) {

                codex.appender.autoLoading.canLoad = false;

                codex.appender.load();

            }

        },

    }

};

module.exports = appender;
