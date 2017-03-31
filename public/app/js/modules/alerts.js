/**
* Notifications tips module
*/
module.exports = (function () {

    require('../../css/modules/alerts.css');

    var CSS_ = {
        wrapper : 'cdx-notifies-wrapper',
        notification : 'cdx-notifies'
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
    * @param {Object} options:
    *
    * @property {String} type    - type of notification. Just adds {CSS_.notification + '--' + type} class. 'notify' by default
    * @property {String} message - text to notify, can contains HTML
    * @property {String} time    - expiring time
    */
    function show(options) {

        prepare_();

        var notify  = document.createElement('DIV'),
            message = options.message,
            type    = options.type || 'notify',
            time    = options.time || 8000;

        if (!message) {

            return;

        }

        notify.classList.add(CSS_.notification);
        notify.classList.add(CSS_.notification + '--' + type);
        notify.innerHTML = message;

        wrapper_.appendChild(notify);

        notify.classList.add('bounceIn');

        window.setTimeout(function () {

            notify.remove();

        }, time);

    }

    return {
        show : show
    };

})({});
