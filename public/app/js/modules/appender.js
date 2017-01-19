/**
 * Appender is being used for ajax-loading next pages of lists
 *
 *    codex.appender.init({
 *        button_id       : 'button_load_news',       // button for listening
 *        current_page    : '<?= $page_number ?>',    // current_page number
 *        url             : '/',                      // url for ajax-requests
 *        target_block_id : 'list_of_news',           // target for appending
 *        auto_loading    : true,                     // allow loading when reach bottom while scrolling
 *    });
 */

/* eslint-disable */

var appender = {

    /* Pagination. Here is a number of current page */
    page : 1,

    settings : null,

    block_for_items : null,

    load_more_button : null,

    /**
     * Button's text for saving it.
     * On its place dots will be  while news are loading
     */
    button_text : null,

    init : function (settings) {

        this.settings = settings;

        /* Checking for existing button and field for loaded info */
        this.load_more_button = document.getElementById(this.settings.button_id);

        if (!this.load_more_button) return false;

        this.block_for_items = document.getElementById(this.settings.target_block_id);

        if (!this.block_for_items) return false;

        this.page        = settings.current_page;
        this.button_text = this.load_more_button.innerHTML;

        if (this.settings.auto_loading) this.auto_loading.is_allowed = true;

        this.load_more_button.addEventListener('click', function (event){

            codex.appender.load();

            event.preventDefault();

            codex.appender.auto_loading.init();

        }, false);
    },

    load : function () {

        var request_url = this.settings.url + (parseInt(this.page) + 1);
            // separator   = '<a href="' + request_url + '"><div class="article post-list-item w_island separator">Page ' + (parseInt(this.page) + 1) + '</div></a>';

        if (this.settings.getParams) {

            request_url += '?' + codex.appender.serialize(this.settings.getParams);
        }

        codex.core.ajax({
            type: 'post',
            url: request_url,
            data: {},
            beforeSend : function () {

                codex.appender.load_more_button.innerHTML = ' ';
                codex.appender.load_more_button.classList.add('loading');
            },
            success : function(response) {

                response = JSON.parse(response);

                if (response.success) {

                    if (!response.pages) return;

                    /* Append items */
                    //codex.appender.block_for_items.innerHTML += separator;
                    codex.appender.block_for_items.innerHTML += response.pages;

                    /* Next page */
                    codex.appender.page++;

                    if (codex.appender.settings.auto_loading) {
                        /* Removing restriction for auto loading */
                        codex.appender.auto_loading.can_load = true;
                    }

                    /* Checking for next page's existing. If no — hide the button for loading news and remove listener */
                    if (!response.next_page) codex.appender.disable();

                } else {

                    codex.core.showException('Не удалось подгрузить новости');
                }

                codex.appender.load_more_button.classList.remove('loading');
                codex.appender.load_more_button.innerHTML = codex.appender.button_text;
            }

        });
    },

    disable : function () {

        codex.appender.load_more_button.style.display = "none";

        if (codex.appender.auto_loading.is_launched) {

            codex.appender.auto_loading.disable();
        }
    },

    auto_loading : {

        is_allowed : false,

        is_launched : false,

        /**
         * Possibility to load news by scrolling.
         * Restriction for reduction requests which could be while scrolling
         */
        can_load : true,

        init : function () {

            if (!this.is_allowed) return;

            window.addEventListener("scroll", codex.appender.auto_loading.scrollEvent);

            codex.appender.auto_loading.is_launched = true;
        },

        disable : function () {

            window.removeEventListener("scroll", codex.appender.auto_loading.scrollEvent);

            codex.appender.auto_loading.is_launched = false;
        },

        scrollEvent : function () {

            var scroll_reached_end = window.pageYOffset + window.innerHeight >= document.body.clientHeight;

            if (scroll_reached_end && codex.appender.auto_loading.can_load) {

                codex.appender.auto_loading.can_load = false;

                codex.appender.load();
            }
        },
    },

    /**
     * Transform object to string for GET request
     */
    serialize : function(obj, prefix) {
        var str = [],
            p;

        for (p in obj) {

            if (obj.hasOwnProperty(p)) {

                var k = prefix ? prefix + "[" + p + "]" : p,
                    v = obj[p];

                str.push((v !== null && typeof v === "object") ?
                serialize(v, k) :
                encodeURIComponent(k) + "=" + encodeURIComponent(v));
            }
        }

        return str.join("&");
    },
};

module.exports = appender;
