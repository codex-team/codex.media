module.exports = function () {

    var checked = new window.CustomEvent('checked');

    var clicked = function (click) {

        var checkbox = click.target

        checkbox.classList.toggle('checkbox--checked');

        checked.checked = checkbox.classList.contains('checkbox--checked');

        checkbox.dispatchEvent(checked);

    };

    var init = function () {

        var checkboxes = document.getElementsByClassName('cdx-custom-checkbox');

        Array.prototype.map.call(checkboxes, function (checkbox) {

            checkbox.addEventListener('click', clicked);
            checkbox.classList.add('checkbox');

        });

    };

    return {
        init: init
    };

}();