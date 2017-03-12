module.exports = function () {

    var init = function () {

        bindEvents();

    };

    var bindEvents = function () {

        var repeatConfirmEmailBtn = document.getElementById('repeat-email-confirmation');

        repeatConfirmEmailBtn.addEventListener('click', sendEmeailConfirmation);

    };

    var sendEmeailConfirmation = function (e) {

        var success = function (response) {

            response = JSON.parse(response);

            codex.alerts.show(response.message);
            e.target.classList.remove('loading');

        };

        e.target.classList.add('loading');

        codex.ajax.call({
            url: '/ajax/confirmation-email',
            success: success
        });

    };

    return {
        init: init
    };

}();
