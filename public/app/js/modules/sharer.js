var sharer = {

    init : function () {

        var shareButtons = document.querySelectorAll('.sharing .but, .sharing .main_but, .quiz__sharing .but');

        for (var i = shareButtons.length - 1; i >= 0; i--) {

            shareButtons[i].addEventListener('click', sharer.click, true);

        }

    },

    shareVk : function (data) {

        var link  = 'https://vk.com/share.php?';

        link += 'url='          + data.url;
        link += '&title='       + data.title;
        link += '&description=' + data.desc;
        link += '&image='       + data.img;
        link += '&noparse=true';

        this.popup( link, 'vkontakte'  );

    },

    shareFacebook : function (data) {

        var FB_APP_ID = 1740455756240878,
            link      = 'https://www.facebook.com/dialog/share?display=popup';

        link += '&app_id='       + FB_APP_ID;
        link += '&href='         + data.url;
        link += '&redirect_uri=' + document.location.href;

        this.popup( link, 'facebook' );

    },

    shareTwitter : function (data) {

        var link = 'https://twitter.com/share?';

        link += 'text='      + data.title;
        link += '&url='      + data.url;
        link += '&counturl=' + data.url;

        this.popup( link, 'twitter' );

    },

    shareTelegram : function (data) {

        var link  = 'https://telegram.me/share/url';

        link += '?text=' + data.title;
        link += '&url='  + data.url;

        this.popup( link, 'telegram' );

    },

    popup : function ( url, socialType ) {

        window.open( url, '', 'toolbar=0,status=0,width=626,height=436' );

        /**
         * Write analytics goal
         */
        if ( window.yaCounter32652805 ) {

            window.yaCounter32652805.reachGoal('article-share', function () {}, this, {type: socialType, url: url});

        }

    },

    click : function (event) {

        var target = event.target;

        /**
         * Social provider stores in data 'shareType' attribute on share-button
         * But click may be fired on child-element in button, so we need to handle it.
         */
        var type = target.dataset.shareType || target.parentNode.dataset.shareType;

        if (!sharer[type]) return;

        /**
         * Sanitize share params
         * @todo test for taint strings
         */
            // for (key in window.shareData){
            //      window.shareData[key] = encodeURIComponent(window.shareData[key]);
            // }

        var shareData = {

            url:    target.dataset.url || target.parentNode.dataset.url,
            title:  target.dataset.title || target.parentNode.dataset.title,
            desc:   target.dataset.desc || target.parentNode.dataset.desc,
            img:    target.dataset.img || target.parentNode.dataset.title

        };

        /**
         * Fire click handler
         */

        sharer[type](shareData);

    }

};

module.exports = sharer;