/**
 * Page significant methods
 */
module.exports = (function () {

    /**
     * Opens page-writing form
     */
    var openWriting = function () {

        var itemClicked = this,
            targetId = itemClicked.dataset.id;

        document.location = '/p/writing?id=' + targetId;

    };

    /**
     * Opens page-writing form
     */
    var remove = function () {

        var itemClicked = this,
            targetId    = itemClicked.dataset.id;

        if (!window.confirm('Подтвердите удаление страницы')) {

            return;

        }

        codex.ajax.call({
            url : '/p/' + targetId + '/delete',
            success: ajaxResponses.delete
        });

    };

    var newChild = function () {

        var itemClicked = this,
            targetId = itemClicked.dataset.id;

        document.location = '/p/writing?parent=' + targetId;

    };

    var addToMenu = function () {

        var itemClicked = this,
            targetId = itemClicked.dataset.id;

        codex.ajax.call({
            url : '/p/' + targetId + '/promote?list=menu',
            success: ajaxResponses.promote
        });

    };

    var addToNews = function () {

        var itemClicked = this,
            targetId = itemClicked.dataset.id;

        codex.ajax.call({
            url : '/p/' + targetId + '/promote?list=news',
            success: ajaxResponses.promote
        });

    };

    var ajaxResponses = {

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

        delete: function (response) {

            response = ajaxResponses.getResponse(response);

            if (response.success) {

                window.location.replace(response.redirect);
                return;

            }

            codex.alerts.show(response.message);

        },

        promote: function (response) {

            response = ajaxResponses.getResponse(response);

            if (response.success) {

                codex.alerts.show(response.message);

                window.setTimeout(function () {

                    window.location.href = window.location.href;

                }, 1000);

                return;

            }

            codex.alerts.show(response.message);

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