var alerts = {

    show : function (message) {

        var wrapper = document.querySelector('.exceptionWrapper'),
            notify;

        if (!wrapper) {

            wrapper = document.createElement('div');
            wrapper.classList.add('exceptionWrapper');

            document.body.appendChild(wrapper);

        }

        notify = document.createElement('div');
        notify.classList.add('clientException');

        notify.innerHTML = message;

        wrapper.appendChild(notify);

        notify.classList.add('bounceIn');

        window.setTimeout(function () {

            notify.remove();

        }, 8000);

    }

};

module.exports = alerts;
