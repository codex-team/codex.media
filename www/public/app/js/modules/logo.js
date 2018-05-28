module.exports = function () {

    var change = function (e) {

        e.preventDefault();
        e.stopImmediatePropagation();

        var wrapper = this.parentNode;

        codex.transport.init({

            url : '/upload/7',
            accept : 'image/*',
            success : function (result) {

                var response = JSON.parse(result),
                    img = document.getElementById('js-site-logo');

                if ( response.success ) {

                    if ( !img ) {

                        img = document.createElement('IMG');
                        wrapper.appendChild(img);

                    }

                    img.src = response.data.url;
                    wrapper.classList.remove('site-head__logo--empty');

                } else {

                    codex.alerts.show({
                        type: 'error',
                        message: 'Uploading failed'
                    });

                }

            },
            error: function () {

                codex.alerts.show({
                    type: 'error',
                    message: 'Error while uploading logo'
                });

            }

        });

    };

    return {
        change : change
    };

}();