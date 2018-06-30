/**
 * Layout module
 */
module.exports = function (module) {

    /**
     * Activates mobile hamburger
     */
    function initMobileMenuToggler() {

        /**
         * Modifier for <body>
         */
        const menuOpenedModifier = 'body--menu-opened';

        /**
         * Hamburger at the header
         */
        let toggler = document.getElementById('js-mobile-menu-toggler');

        if (!toggler) {

            return;

        }

        toggler.addEventListener('click', (event) => {

            document.body.classList.toggle(menuOpenedModifier);

            event.stopPropagation();
            event.stopImmediatePropagation();
            event.preventDefault();

        });

    }

    module.init = function () {

        /**
         * Activate mobile menu toggler
         */
        initMobileMenuToggler();

    };

    return module;

}({});