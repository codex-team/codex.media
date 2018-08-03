/**
 * Initialize a date picker in editor form
 */
import tinyDatePicker from 'tiny-date-picker';

module.exports = function () {

    /**
     * Initialize date picker with the following options
     * @param {String} settings.selector — selector of target input where date picker will be applied
     * @param {String} settings.mode — the way date picker will be displayed, for example, 'dp-below'
     */

    function init(settings) {

        tinyDatePicker(settings.selector, {
            mode: settings.mode
        });

    }

    return {
        init : init
    };

}({});