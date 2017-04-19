/**
 * Pins page in news feed
 */
module.exports = function () {

    var currentItemClicked = null,
        targetId           = null;

    /**
     * Requests page pin toggle via ajax
     */
    var toggle = function () {

        currentItemClicked = this;
        targetId = currentItemClicked.dataset.id;

        currentItemClicked.classList.add('loading');

        codex.ajax.call({
            url : '/p/' + targetId + '/pin',
            success: success,
            error: error
        });

    };

    /** Response for success ajax request **/
    var success =  function (response) {

        currentItemClicked.classList.remove('loading');
        response = JSON.parse(response);

        codex.alerts.show({
            type: response.success ? 'success' : 'error',
            message: response.message
        });

        var page = document.getElementById('js-page-' + targetId);

        var time = page.querySelector('time');

        time.querySelector('a').innerHTML = response.message;


    };

    /** Response for ajax request errors **/
    var error = function () {

        currentItemClicked.classList.remove('loading');

        codex.alerts.show({
            type: 'error',
            message: 'Произошла ошибка'
        });

    };

    return {
        toggle: toggle
    };

}();
