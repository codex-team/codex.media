/**
 * Toggle content in about block
 */
module.exports = function () {

    /**
     * Toggle block content into view
     *
     * @param {elem} - trigger button
     */
    var toggleContent = function (elem, event) {

        event.preventDefault();
        elem.previousElementSibling.classList.toggle('island__content--show');

    };

    return {
        toggleContent : toggleContent
    };

}();