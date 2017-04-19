/**
 * Pins page in news feed
 */
module.exports = function () {

    var currentItemClicked = this;

    currentItemClicked.classList.add('loading');

    var targetId = currentItemClicked.dataset.id;

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

    var error = function () {

        currentItemClicked.classList.remove('loading');

        codex.alerts.show({
            type: 'error',
            message: 'Произошла ошибка'
        });

    };

    codex.ajax.call({
        url : '/p/' + targetId + '/pin',
        success: success,
        error: error
    });

};
