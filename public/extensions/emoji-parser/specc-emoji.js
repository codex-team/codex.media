/**
* Module for emoji fallback images
* @usage mark all elements where emoji can placed with 'js-emoji-included' class and fire Emoji.parse();
* @requires emoji.css file
* @author Savchenko Peter <specc.dev@gmail.com>
*/
var Emoji = (function () {

    /**
    * @private
    * Path where styles stored
    */
    var CSS_FILE_PATH = '/public/extensions/emoji-parser/css/emoji.css';

    /**
    * @private
    * Emoji UTF-16 ranges with surrogate pairs
    * @todo add support for skin-tones (U+1F3FB–U+1F3FF)
    * @todo add support for chars like 0023-20e3 \u0023\uFE0F\u20E3 - hash
    * @todo add support for \1F469\200D\2764\FE0F\200D\1F48B\200D\1F469 - \FE0F variation selector
    */
    var RANGES_ = [
              '(\ud83d[\udc00-\ude4f]\u200D?)+', // multi emoji with WSJ like 1f468-200d-1f469-200d-1f466-200d-1f466 man-woman-boy-boy
              '(\ud83c[\udc00-\ude4f])+', // 1f1ed-1f1f9 (flags) like \uD83C\uDDED\uD83C\uDDF9 flag-haiti
              '\ud83c[\udf00-\udfff]', // U+1F300 to U+1F3FF
              '\ud83d[\udc00-\ude4f]', // U+1F400 to U+1F64F
              '\ud83d[\ude80-\udeff]',  // U+1F680 to U+1F6FF
            ];

    /**
    * Public methods and properties
    */
    var _emoji = function () {

        /**
        * @protected
        * Marks true after load required emoji.css file
        */
        this.stylesLoaded = false;
    };

    /**
    * @protected
    * Checks for emoji supprots and make fallback elements
    * @param {Bool} forceParsing    - pass TRUE to parse emoji even if they're natively supported
    * @fires Emoji.supported
    */
    _emoji.prototype.parse = function( forceParsing ){

        /**
        * Do nothing when emoji natively supported
        */
        if (this.supported && !forceParsing){
            return;
        }

        /**
        * Loads required stylesheets
        */
        if ( !this.stylesLoaded ) {
            prepare_();
        }

        var emoji   = document.querySelectorAll('.js-emoji-included'),
            emojiRX = new RegExp(RANGES_.join('|'), 'g'),
            replacing,
            alreadyParsed = false;


        for (var i = emoji.length - 1; i >= 0; i--) {

            /**
            * Prevent double parsing form data-emoji
            */
            alreadyParsed = !!emoji[i].querySelector('.emoji');

            if (alreadyParsed) {
                continue;
            }

            replacing = emoji[i].innerHTML.replace(emojiRX ,'<em class="emoji" data-emoji="$&"></em>');

            emoji[i].innerHTML = replacing;
        }

    };

    /**
    * @protected
    * Fallback element to emoji parser
    */
    _emoji.prototype.parseBack = function(){

        var emoji = document.querySelectorAll('.emoji'),
            data_attr,
            origin;

        for (var i = emoji.length - 1; i >= 0; i--) {

            data_attr = emoji[i].dataset.emoji;

            if (data_attr) {

                origin = document.createTextNode(data_attr);

                emoji[i].parentNode.replaceChild(origin, emoji[i]);

            }
        }

    };

    /**
    * @private
    * Finds Unicode surrogate pairs
    *
    * JavaScript defines strings as sequences of UTF-16 code units,
    * not as sequences of characters or code points.
    * This is fine for characters in the Basic Multilingual Plane (BMP),
    * or Unicode range of U+0000 to U+FFFF, but for characters outside this range,
    * in Supplementary Planes (note emoticons starting at U+1F600), two code units are necessary.
    * @see http://crocodillon.com/blog/parsing-emoji-unicode-in-javascript
    *
    * @uses to find surrogate pairs for this.ranges
    * @usage
    *
    *   findSurrogatePair_(0x1f600); // ["d83d", "de00"]
    *   findSurrogatePair_(0x1f64f); // ["d83d", "de4f"]
    */
    function findSurrogatePair_(point) {
        // assumes point > 0xffff
        var offset = point - 0x10000,
            lead   = 0xd800 + (offset >> 10),
            trail  = 0xdc00 + (offset & 0x3ff);

        return [lead.toString(16), trail.toString(16)];
    }

    /**
    * @private
    * Loads styles required for fallback elements
    */
    function prepare_(){

        var style = document.createElement( 'link' );

        style.setAttribute( 'type', 'text/css' );
        style.setAttribute( 'rel', 'stylesheet');

        style.onload = function () {

            Emoji.stylesLoaded = true;

        };

        style.href = CSS_FILE_PATH;

        document.head.appendChild( style );

    }

    /**
    * @protected
    * Checks for emoji codes supports
    */
    _emoji.prototype.supported = (function (){

        var node = document.createElement('canvas');

        if (!node.getContext || !node.getContext('2d') || typeof node.getContext('2d').fillText !== 'function'){
            return false;
        }

        var ctx = node.getContext('2d');

        ctx.textBaseline = 'top';
        ctx.font = '32px Arial';
        ctx.fillText('\ud83d\ude03', 0, 0);

        return ctx.getImageData(16, 16, 1, 1).data[0] !== 0;

    })();

    return new _emoji();

})({});
