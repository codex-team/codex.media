/**
 * Admin module
 */

module.exports = (function () {

    var init = function () {

        clickedToChangeBranding_();

    };

    /**
     * changes site branding
     * @private
     */
    var changeBranding_ = function () {

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

                    brandingSection.style.backgroundImage = "url('" + url + "')"; // instead of babel polyfill
                    brandingSection.style.backgroundSize = '100% 100%';

                }

            }

        });

    };

    var clickedToChangeBranding_ = function () {

        var changeBrandingButton = document.getElementById('changeBrandingButton');
        changeBrandingButton.addEventListener('click', changeBranding_, false);

    };

    return {
        init : init
    };

})({});
