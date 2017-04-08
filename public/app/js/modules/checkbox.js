module.exports = function () {

    var CheckEvent = new window.CustomEvent('check'),
        CLASSES    = {
            wrapper: 'cdx-checkbox-wrapper',
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

        checkbox.parentNode.classList.toggle(CLASSES.checked);
        input.checked = !input.checked;

        CheckEvent.checked = input.checked;

        checkbox.dispatchEvent(CheckEvent);

    };

    var prepareCheckbox = function (wrapper) {

        var input      = document.createElement('INPUT'),
            checkbox   = document.createElement('SPAN'),
            firstChild = wrapper.firstChild;

        input.type  = 'checkbox';
        input.name  = checkbox.dataset.name || NAMES.defaultInput;
        input.value = 1;
        input.classList.add('hide');

        checkbox.addEventListener('click', clicked);
        checkbox.classList.add(CLASSES.checkbox);
        checkbox.appendChild(input);

        if (wrapper.dataset.checked) {

            input.checked = true;
            wrapper.classList.add(CLASSES.checked);

        }

        wrapper.classList.add(CLASSES.wrapper);

        if (firstChild) {

            wrapper.insertBefore(checkbox, firstChild);

        } else {

            wrapper.appendChild(checkbox);

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