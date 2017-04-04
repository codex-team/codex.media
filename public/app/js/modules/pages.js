/**
 * Page significant methods
 */
module.exports = (function () {

    /**
     * Saves current clicked item in page drop-down menu
     * @type {Element}
     */
    var currentItemClicked = null;

    /**
     * Opens page-writing form
     */
    var openWriting = function () {

        currentItemClicked = this;

        var targetId = currentItemClicked.dataset.id;

        document.location = '/p/writing?id=' + targetId;

    };

    /**
     * Opens page-writing form
     */
    var remove = function () {

        currentItemClicked = this;

        var targetId    = currentItemClicked.dataset.id;

        if (!window.confirm('Подтвердите удаление страницы')) {

            return;

        }

        codex.ajax.call({
            url : '/p/' + targetId + '/delete',
            success: ajaxResponses.delete
        });

    };

    /**
     * Opens writing form for child page
     */
    var newChild = function () {

        currentItemClicked = this;

        var targetId = currentItemClicked.dataset.id;

        document.location = '/p/writing?parent=' + targetId;

    };

    /**
     * Send ajax request to add page to menu
     */
    var addToMenu = function () {

        currentItemClicked = this;
        currentItemClicked.classList.add('loading');

        var targetId =currentItemClicked.dataset.id;

        codex.ajax.call({
            url : '/p/' + targetId + '/promote?list=menu',
            success: ajaxResponses.promote
        });

    };

    /**
     * Send ajax request to add page to news
     */
    var addToNews = function () {

        currentItemClicked = this;
        currentItemClicked.classList.add('loading');

        var targetId =currentItemClicked.dataset.id;

        codex.ajax.call({
            url : '/p/' + targetId + '/promote?list=news',
            success: ajaxResponses.promote
        });

    };

    var ajaxResponses = {

        /**
         * Parse JSON response
         * @param {JSON} response
         * @returns {Object} response
         */
        getResponse: function (response) {

            try {

                response = JSON.parse(response);

            } catch(e) {

                return {
                    success: 0,
                    message: 'Произошла ошибка, попробуйте позже'
                };

            }

            return response;

        },

        /**
         * Response handler for page remove
         * @param response
         */
        delete: function (response) {

            response = ajaxResponses.getResponse(response);

            if (response.success) {

                window.location.replace(response.redirect);
                return;

            }

            codex.alerts.show({
                type: 'error',
                message: response.message
            });

        },

        /**
         * Response handler for page promotion
         * @param response
         */
        promote: function (response) {

            response = ajaxResponses.getResponse(response);

            currentItemClicked.classList.remove('loading');

            if (response.success) {

                if (response.menu) {

                    ajaxResponses.replaceMenu(response.menu);

                }

                /**
                 * TODO: сделать замену текста кнопки
                 **/

                codex.alerts.show({
                    type: 'success',
                    message: response.message
                });

                return;

            }

            codex.alerts.show({
                type: 'error',
                message: response.message
            });

        },

        /**
         * Replace site menu with code from server response
         * @param menu
         */
        replaceMenu: function (menu) {

            var oldMenu = document.getElementById('menu'),
                newMenu = codex.core.parseHTML(menu)[0];

            codex.core.replace(oldMenu, newMenu);

        }

    };



    return {
        openWriting: openWriting,
        newChild: newChild,
        addToMenu: addToMenu,
        addToNews: addToNews,
        remove : remove
    };

}());