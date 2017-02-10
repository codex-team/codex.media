/**
* Notifications tips module
*/
module.exports = (function () {

    require('../../css/modules/alerts.css');

    var CSS_ = {
        wrapper : 'exceptionWrapper',
        exception : 'clientException'
    };

    var wrapper_ = null;

    function prepare_() {

        if ( wrapper_ ) {

            return true;

        }

        wrapper_ = document.createElement('DIV');
        wrapper_.classList.add(CSS_.wrapper);

        document.body.appendChild(wrapper_);

    }

    /**
    * @param {String} message - may content HTML
    */
    function show(message) {

        prepare_();

        var notify = document.createElement('DIV');

        notify.classList.add(CSS_.exception);
        notify.innerHTML = message;

        wrapper_.appendChild(notify);

        notify.classList.add('bounceIn');

        window.setTimeout(function () {

            notify.remove();

        }, 8000);

    }

    return {
        show : show
    };

})({});
