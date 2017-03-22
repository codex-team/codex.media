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

        // codex.ajax.call({
        //     url : '/p/' + targetId + '/delete',
        //     success: function (response) {
        //         console.log("response: %o", response);
        //     }
        // });

        document.location = '/p/' + targetId + '/delete';

    };

    return {
        openWriting: openWriting,
        remove : remove
    };

}());