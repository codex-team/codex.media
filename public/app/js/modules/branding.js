/**
 * Admin module
 */

module.exports = (function () {

    /**
     * changes site branding
     * @private
     */
    var change = function () {

        var brandingSection = document.getElementById('brandingSection');

        codex.transport.init({

            url : '/upload/4',
            accept : 'image/*',
            success : function (result) {

                var response = JSON.parse(result),
                    file,
                    url;

                if ( response.success ) {

                    file = response.data;
                    url = file.url;

                    brandingSection.style.backgroundImage = "url('" + url + "')";

                }

            }

        });

    };

    return {
        change : change
    };

})({});
