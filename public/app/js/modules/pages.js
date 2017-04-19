/**
 * Page significant methods
 */
module.exports = (function () {

    /**
     * Page cover module
     */
    var cover = require('./pages/cover');

    /**
     * Page pin module
     */
    var pin = require('./pages/pin');

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
            success: removeHandler
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

        var targetId = currentItemClicked.dataset.id;

        codex.ajax.call({
            url : '/p/' + targetId + '/promote?list=menu',
            success: promote
        });

    };

    /**
     * Send ajax request to add page to news
     */
    var addToNews = function () {

        currentItemClicked = this;
        currentItemClicked.classList.add('loading');

        var targetId = currentItemClicked.dataset.id;

        codex.ajax.call({
            url : '/p/' + targetId + '/promote?list=news',
            success: promote
        });

    };

    /**
     * Parse JSON response
     * @param {JSON} response
     * @returns {Object} response
     */
    var getResponse = function (response) {

        try {

            response = JSON.parse(response);

        } catch(e) {

            return {
                success: 0,
                message: 'Произошла ошибка, попробуйте позже'
            };

        }

        return response;

    };

    /**
     * Response handler for page remove
     * @param response
     */
    var removeHandler = function (response) {

        response = getResponse(response);

        if (response.success) {

            window.location.replace(response.redirect);
            return;

        }

        codex.alerts.show({
            type: 'error',
            message: response.message
        });

    };

    /**
     * Response handler for page promotion
     * @param response
     */
    var promote = function (response) {

        response = getResponse(response);
        currentItemClicked.classList.remove('loading');

        if (response.success) {

            if (response.buttonText) {

                replaceMenu(currentItemClicked, response.buttonText);

            }

            if (response.menu) {

                updateSiteMenu(response.menu);

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

    };

    /**
     * Replace site menu with new button text from server response
     * @param currentItemMenu
     * @param newResponseMenuText
     */
    var replaceMenu = function (currentItemMenu, newResponseMenuText) {

        var itemIndex = currentItemMenu.dataset.itemIndex,
            menuIndex = currentItemMenu.dataset.index;

        /** update item on menu */
        codex.islandSettings.updateItem(menuIndex, itemIndex, newResponseMenuText);

        /** update item text immediatelly */
        currentItemMenu.textContent = newResponseMenuText;

    };

    /**
     * Replace site menu with menu form server response
     *
     * @param menu
     */
    var updateSiteMenu = function (menu) {

        var oldMenu = document.getElementById('js-site-menu'),
            newMenu = codex.core.parseHTML(menu)[0];

        codex.core.replace(oldMenu, newMenu);

    };

    return {
        openWriting: openWriting,
        newChild: newChild,
        addToMenu: addToMenu,
        addToNews: addToNews,
        remove : remove,
        pin: pin,
        cover: cover
    };

}());
