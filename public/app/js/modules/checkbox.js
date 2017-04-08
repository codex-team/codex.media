module.exports = function () {

    var CheckEvent = new window.CustomEvent('check'),
        CLASSES    = {
            checkbox: 'cdx-checkbox',
            checked: 'cdx-checkbox--checked'
        },
        NAMES      = {
            checkbox: 'cdx-custom-checkbox',
            defaultInput: 'cdx-custom-checkbox'
        };


    var clicked = function () {

        var checkbox = this,
            input = checkbox.querySelector('input');

        checkbox.classList.toggle(CLASSES.checked);
        input.checked = !input.checked;

        CheckEvent.checked = input.checked;

        checkbox.dispatchEvent(CheckEvent);

    };

    var prepareCheckbox = function (checkbox) {

        var input = document.createElement('INPUT');

        input.type = 'checkbox';
        input.name = checkbox.dataset.name || NAMES.defaultInput;
        input.classList.add('hide');

        checkbox.addEventListener('click', clicked, false);
        checkbox.classList.add(CLASSES.checkbox);
        checkbox.appendChild(input);

        if (checkbox.dataset.checked) {

            input.checked = true;
            checkbox.classList.add(CLASSES.checked);

        }


    };

    var init = function () {

        var checkboxes = document.getElementsByName(NAMES.checkbox);

        Array.prototype.forEach.call(checkboxes, prepareCheckbox);

    };

    return {
        init: init
    };

}();