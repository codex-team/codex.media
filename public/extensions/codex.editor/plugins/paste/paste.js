/**
 * Paste plugin.
 *
 * Listens on paste event and pastes content from:
 *  - Instagram
 *  - Twitter
 *  - VK
 *  - Facebook
 *  - Image
 *  - External Link
 *
 * @author Codex Team
 * @copyright Khaydarov Murod
 *
 * @version 1.0.0
 */

/**
 * @protected
 *
 * Main tool settings.
 */
var pasteTool = {

};

/**
 * Make elements to insert or switch
 *
 * @uses Core codex.editor.draw module
 */
pasteTool.ui = {

    /**
     * Upload image by URL
     *
     * @uses codex Image tool
     * @param filename
     * @returns {Element}
     */
    uploadedImage : function(filename) {

        var data = {
            background: false,
            border: false,
            isStretch: false,
            file: {
                url: "upload/redactor_images/" + filename,
                bigUrl: "upload/redactor_images/" + filename,
                width: null,
                height: null,
                additionalData: "null"
            },
            caption: '',
            cover: null
        };

        /** Using Image plugin make method */
        var image = codex.editor.tools.image.make(data);

        return image;

    }

};


/**
 *
 * Callbacks
 */
pasteTool.callbacks = {

    /**
     * Saves data
     * @param event
     */
    pasted : function(event) {

        var clipBoardData = event.clipboardData || window.clipboardData,
            content = clipBoardData.getData('Text');

        pasteTool.callbacks.analize(content);
    },

    /**
     * Analizes pated string and calls necessary method
     */
    analize : function(string) {

        var regexTemplates = {
                image : /(?:([^:\/?#]+):)?(?:\/\/([^\/?#]*))?([^?#]*\.(?:jpe?g|gif|png))(?:\?([^#]*))?(?:#(.*))?/i,
                instagram : new RegExp("http?.+instagram.com\/p?."),
                twitter : new RegExp("http?.+twitter.com?.+\/"),
                facebook : /https?.+facebook.+\/\d+\?/,
                vk : /https?.+vk?.com\/feed\?w=wall\d+_\d+/,
            },

            image  = regexTemplates.image.test(string),
            instagram = regexTemplates.instagram.exec(string),
            twitter = regexTemplates.twitter.exec(string),
            facebook = regexTemplates.facebook.test(string),
            vk = regexTemplates.vk.test(string);

        if (image) {

            pasteTool.callbacks.uploadImage(string);

        } else if (instagram) {

            pasteTool.callbacks.instagramMedia(instagram);

        } else if (twitter) {

            pasteTool.callbacks.twitterMedia(twitter);

        } else if (facebook) {

            pasteTool.callbacks.facebookMedia(string);

        } else if (vk) {

            pasteTool.callbacks.vkMedia(string);

        }

    },

    /**
     * Direct upload
     * @param url
     */
    uploadImage : function(path) {

        var ajaxUrl = location.protocol + '//' + location.hostname,
            file,
            image,
            current = codex.editor.content.currentNode,
            beforeSend,
            success_callback;

        /** When image is uploaded to redactors folder */
        success_callback = function(data) {

            var file = JSON.parse(data);
            image = pasteTool.ui.uploadedImage(file.filename);
            codex.editor.content.switchBlock(current, image, 'image');

        };

        /** Before sending XMLHTTP request */
        beforeSend = function() {
            var content = current.querySelector('.ce-block__content');
            content.classList.add('ce-plugin-image__loader');
        };

        /** Preparing data for XMLHTTP */
        var data = {
            url: ajaxUrl + '/editor/transport/',
            type: "POST",
            data : {
                file: path
            },
            beforeSend : beforeSend,
            success : success_callback
        };

        codex.editor.core.ajax(data);
    },

    /**
     * callback for instagram url's
     * Using instagram Embed Widgete to render
     * @uses Instagram tool
     * @param url
     */
    instagramMedia : function(url) {

        var fullUrl = url.input,
            data;


        data = {
            url: fullUrl
        };

        codex.editor.tools.instagram.make(data);

    },

    /**
     * callback for tweets
     * Using Twittter Widget to render
     * @uses Twitter tool
     * @param url
     */
    twitterMedia : function(url) {

        var fullUrl = url.input,
            tweetId,
            arr,
            data;

        arr = fullUrl.split('/');
        tweetId = arr.pop();

        /** Example */
        data = {
            media:true,
            conversation:false,
            user:{
                profile_image_url:"http:\/\/pbs.twimg.com\/profile_images\/1817165982\/nikita-likhachev-512_normal.jpg",
                profile_image_url_https:"https:\/\/pbs.twimg.com\/profile_images\/1817165982\/nikita-likhachev-512_normal.jpg",
                screen_name:"Niketas",
                name:"Никита Лихачёв"
            },
            id: tweetId,
            text:"ВНИМАНИЕ ЧИТАТЬ ВСЕМ НЕ ДАЙ БОГ ПРОПУСТИТЕ НУ ИЛИ ХОТЯ БЫ КЛИКНИ И ПОДОЖДИ 15 СЕКУНД https:\/\/t.co\/iWyOHf4xr2",
            created_at:"Tue Jun 28 14:09:12 +0000 2016",
            status_url:"https:\/\/twitter.com\/Niketas\/status\/747793978511101953",
            caption:"Caption"
        };

        codex.editor.tools.twitter.make(data);
    }

};