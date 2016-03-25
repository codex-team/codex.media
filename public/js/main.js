
//hashchange
(function($,e,b){var c="hashchange", is_msie = /MSIE/.test(navigator.useragent),h=document,f,g=$.event.special,i=h.documentMode,d="on"+c in e&&(i===b||i>7);function a(j){j=j||location.href;return"#"+j.replace(/^[^#]*#?(.*)$/,"$1")}$.fn[c]=function(j){return j?this.bind(c,j):this.trigger(c)};$.fn[c].delay=50;g[c]=$.extend(g[c],{setup:function(){if(d){return false}$(f.start)},teardown:function(){if(d){return false}$(f.stop)}});f=(function(){var j={},p,m=a(),k=function(q){return q},l=k,o=k;j.start=function(){p||n()};j.stop=function(){p&&clearTimeout(p);p=b};function n(){var r=a(),q=o(m);if(r!==m){l(m=r,q);$(e).trigger(c)}else{if(q!==m){location.href=location.href.replace(/#.*/,"")+q}}p=setTimeout(n,$.fn[c].delay)}is_msie&&!d&&(function(){var q,r;j.start=function(){if(!q){r=$.fn[c].src;r=r&&r+a();q=$('<iframe tabindex="-1" title="empty"/>').hide().one("load",function(){r||l(a());n()}).attr("src",r||"javascript:0").insertAfter("body")[0].contentWindow;h.onpropertychange=function(){try{if(event.propertyName==="title"){q.document.title=h.title}}catch(s){}}}};j.stop=k;o=function(){return a(q.location.href)};l=function(v,s){var u=q.document,t=$.fn[c].domain;if(v!==s){u.title=h.title;u.open();t&&u.write('<script>document.domain="'+t+'"<\/script>');u.close();q.location.hash=v}}})();return j})()})(jQuery,this);

// User methods Class.
var user = function ( window ){

    // Private methods & properties. __________________________________________________________________

    // Detect the browser out of list.
    function isOthers(arr){
        for(var i in arr){
            if(arr[i]){
                return false;
            }
        }
        return true;
    }

    // Event support detect method.
    function isEventSupported( eventName ) {
        if(!eventName) return false;
        var el = document.createElement('div');
        if(!el) return false;
        eventName = 'on' + eventName;
        var isSupported = (eventName in el);
        if (!isSupported) {
            el.setAttribute(eventName, 'return;');
            isSupported = typeof el[eventName] == 'function';
        }
        el = null;
        return isSupported;
    }

    var ua = navigator.userAgent.toLowerCase();

    var platform = {
            'WIN'       : /win/i.test(ua) && !(/windows phone/i.test(ua)),
            'MAC'       : /macintosh/i.test(ua),
            'LINUX'     : /linux/i.test(ua),

            'IPHONE'    : /iphone/i.test(ua),
            'IPAD'      : /ipad/i.test(ua),
            'IPOD'      : /ipod/i.test(ua),
            'ANDROID'   : /android/i.test(ua),
            'PIKE'      : /pike/i.test(ua),
            'SYMBIAN'   : /symbian/i.test(ua),
            'WINPHONE'  : /windows phone/i.test(ua)
        },
        browser = {
            'IE'        : /msie/i.test(ua) && !(/iemobile/i.test(ua)) && /MSIE ([0-9]{1,}[\.0-9]{0,})/.exec(navigator.userAgent) && parseInt(/MSIE ([0-9]{1,}[\.0-9]{0,})/.exec(navigator.userAgent)[1] , 10 ),
            'OPERA'     : /opera/i.test(ua) && window.opera && !(/opera mobi|opera mini/i.test(ua)),
            'SAFARI'    : /webkit|safari|khtml/i.test(ua) && !(/chrome|mobile safari/i.test(ua)),
            'FIREFOX'   : /firefox/i.test(ua),
            'CHROME'    : /chrome/i.test(ua),
            'YANDEX'    : /yabrowser/i.test(ua),

            'OPERA_MOBILE'   : /opera mobi/i.test(ua) && window.opera,
            'OPERA_MINI'     : /opera mini/i.test(ua) && window.opera,
            'OVI'            : /nokiabrowser/i.test(ua),
            'UC'             : /ucbrowser|ucweb/i.test(ua),
            'ANDROID'        : /android/i.test(ua) && /mobile safari/i.test(ua),
            'SAFARI_MOBILE'  : /webkit|safari|khtml/i.test(ua) && !(/chrome/i.test(ua)) && !(/crios/i.test(ua)),
            'FIREFOX_MOBILE' : /mobile/i.test(ua) && /firefox/i.test(ua),
            'CHROME_MOBILE'  : /webkit|safari|khtml/i.test(ua) && (!(/chrome/i.test(ua)) && /crios/i.test(ua) || /chrome/i.test(ua) && /mobile safari/i.test(ua)),
            'IE_MOBILE'      : /iemobile/i.test(ua),
            'BLACKBERRY'     : /blackberry/i.test(ua)
        };

    platform.OTHERS = isOthers(platform);
    browser.OTHERS = isOthers(browser);

    function setCookie(name, value, expires, path, domain){
        var str = name + '='+value;
        if (expires) str += '; expires=' + expires.toGMTString();
        if (path)    str += '; path=' + path;
        if (domain)  str += '; domain=' + domain;
        document.cookie = str;
    }

    function getCookie(name) {
        var dc = document.cookie;

        var prefix = name + "=";
        var begin = dc.indexOf("; " + prefix);
        if (begin == -1) {
            begin = dc.indexOf(prefix);
            if (begin != 0) return null;
        } else
            begin += 2;

        var end = document.cookie.indexOf(";", begin);
        if (end == -1) end = dc.length;

        return unescape(dc.substring(begin + prefix.length, end));
    }

    function loadScript( settings ){

        var tag = document.createElement( 'script' );

        tag.setAttribute( 'async', !!settings.async );
        tag.setAttribute( 'type', 'text/javascript' );
        tag.setAttribute( 'charset', 'windows-1251'); // Safari doesn't work without it correctly
        tag.setAttribute( 'src', settings.url );

        tag.onload  = ( typeof settings.callback == 'function' ) ? settings.callback : function(){};

        var firstScriptTag = document.getElementsByTagName('script');
        firstScriptTag = ( firstScriptTag.length ) ? firstScriptTag[0] : null;
        if( !!firstScriptTag )
            firstScriptTag.parentNode.insertBefore( tag, firstScriptTag );
        else
            document.head.appendChild( tag );
    }
    // Settings are:
    // {
    //      'url'       :   url,
    //      'async'     :   true/false,
    //      'callback'  :   function(){...}
    //      'instance'  :   'instance of the loadings class'
    // }
    function loadClass( settings ){

        if( !!settings.instance && !!window[ settings.instance ] ){
            settings.callback();
            return;
        }
        delete settings.instance;
        loadScript( settings );
    }

    // Collect History Object.
    function collect_History_Object( settings, state ){
        state = !!state ? state : window.history.state;
        return $.extend( state, settings );
    }

    // Get History Object Field.
    function get_History_Object_Field( object, requested ){

        var field = null;
        if( !object )                       return field;
        if( typeof object !== 'object' )    return field;
        field = object[ requested ];
        if( typeof field === 'undefined' )  return !!field;
        return field;
    }

    // Append each element from jQuery object to DOM element.
    function append_jQuery_object_to_DOM_element( DOM_elem, jQuery_obj, is_not_replace_content ){

        var idx = DOM_elem.childNodes.length;

        if ( ! is_not_replace_content ) {
            while (idx--) {
                DOM_elem.removeChild(DOM_elem.childNodes[idx]);
            }
        }

        for( var i = 0, len = jQuery_obj.length; i < len; ){
            DOM_elem.appendChild( jQuery_obj[ i ] );
            try{
                jQuery_obj[ i ].nodeName === 'SCRIPT' && eval.call( window, jQuery_obj[ i ].innerHTML );
            }catch(e){}
            i++;
        }
    }

    // Collecting data from server and push a new state.
    function addHistoryRecord (state, url, title) {

        if (state === undefined || url === undefined) return;
        state.sequential_number = ++state.sequential_number;
        title = title ? title : document.title;
        if (user.isHistoryAPI) {
            window.history.pushState(
                state || {},
                title,
                url
            );
        }
    }

    // Update current page in history.
    function updateHistoryRecord ( url ) {

        window.our_variables.state.url = url || window.our_variables.state.url || document.URL;
        if (user.isHistoryAPI) {
            window.history.replaceState(
                window.our_variables.state || {},
                document.title,
                window.our_variables.state.url
            );
        }
    }

    // Update window state object.
    function updateLocalState ( data ) {

        if (data === undefined) return {};

        var state = window.our_variables.state;

        state = state || {};

        state.content           = data.content;
        state.url               = data.url;
        state.title             = data.title || document.title;
        state.infListPortion    = data.infListPortion || {};


        state.offset            = data.offset || 0;
        state.sequential_number = data.sequential_number || state.sequential_number;

        return state;
    }

    function renderPage (){

        var state = window.our_variables.state,
            $html = $( state.content ),
            undefined;

        processing.replaceSrc( $html );

        // jQuery ".append()" method seems no working properly with script tags. It executes it, but doesn't append into DOM.
        // We work around such behavior with following issue.
        user.append_jQuery_object_to_DOM_element( window.static_nodes.$main_content.get(0), $html );
        // Sources:
        // http://stackoverflow.com/questions/610995/jquery-cant-append-script-element
        // http://api.jquery.com/append/#comment-61121802
        // http://forum.jquery.com/topic/jquery-dommanip-script-tag-will-be-removed


        processing.catchImages( 0 );

        setTimeout(function () {
            if (state.offset === undefined || isNaN(state.offset)) {
                window.static_nodes.$document.scrollTop(0);
            } else {
                window.static_nodes.$document.scrollTop(state.offset);
            }
        }, 0);

        $('title').text( state.title );

        fsAjax.pageEvents( false );
    }

    function handleLimitedInput ( obj ){

        var value         = obj.val(),
            originalLimit = obj.data('originalLimit');

        if ( value.length > originalLimit ) {
            obj.attr("value", obj.attr("value").slice(0, originalLimit));
            obj.parent(".input_text").attr("data-limit", 0);
        } else {
            obj.parent(".input_text").attr("data-limit", originalLimit - value.length);
        }

    }


    function getInputLimits ( obj ) {

        if ( obj.length > 0 ){

            obj.each(function(){

                var $this = $(this);

                $this.data('originalLimit' , $this.parent('.input_text').data('limit') );

                handleLimitedInput( $this );

                $this.bind('input',function(e){
                    handleLimitedInput( $this );
                });

            });
        }
    }

    function placeScrollUpBlock(){

        var window_width = parseInt(window.innerWidth, 10),
            center_side_width = parseInt($('#head_center').width(), 10),
            $scrollUp = $('.scroll_up'),
            scrollBlockWidth = (window_width - center_side_width) / 2 ;

        // if ( window_width > center_side_width ){
            $scrollUp.css( 'width' , scrollBlockWidth > 30 ? scrollBlockWidth + 'px' : 30 + 'px' );
        // } else {
        //     $scrollUp.css( 'width' , '30px' );
        // }

    }

    function renderRedactor() {
        $('textarea.redactor').each(function(){
            var textarea = $(this),
                is_extra = textarea.hasClass('extra'),
                settings = {
                    lang           : navigator.language == 'ru' ? 'ru' : 'en',
                    minHeight      : 250,
                    linebreaks     : true,
                    formattingTags : ['p','blockquote', 'h2', 'h3'],
                    buttons        : ['formatting', '|', 'bold', '|', 'unorderedlist', 'orderedlist', 'outdent', 'indent', '|', 'image', 'link', '|', 'horizontalrule'],
                    imageUpload    : '/startup/fileUpload'
                };
            if (is_extra) settings.buttons = ['html', 'formatting', 'bold', 'italic', 'deleted', 'unorderedlist', 'orderedlist', 'outdent', 'indent', 'image', 'video', 'file', 'table', 'link', 'alignment', 'horizontalrule'];
            textarea.redactor(settings);
        });
    }

    function initialize_GoogleMap ( DOM_elem , latitude , longitude , disableDefaultUI , zoom , isSettings ){

        if ( ! window.google ){

            loadScript({ 'url' : 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&callback=user.initialize_GoogleMap' });

            window.our_variables.map = {
                DOM_elem         : DOM_elem,
                latitude         : latitude,
                longitude        : longitude,
                disableDefaultUI : disableDefaultUI,
                zoom             : zoom,
                isSettings       : isSettings
            };

        } else {


            latitude          = latitude          ? latitude          : window.our_variables.map.latitude;
            longitude         = longitude         ? longitude         : window.our_variables.map.longitude;
            DOM_elem          = DOM_elem          ? DOM_elem          : window.our_variables.map.DOM_elem;
            disableDefaultUI  = disableDefaultUI  ? disableDefaultUI  : window.our_variables.map.disableDefaultUI;
            zoom              = zoom              ? zoom              : window.our_variables.map.zoom;
            isSettings        = isSettings        ? isSettings        : window.our_variables.map.isSettings;

            google.maps.visualRefresh = true;

            var LatLng = new google.maps.LatLng( latitude , longitude ),
                options = {
                    zoom      : !zoom ? 17 : zoom,
                    center    : !isSettings ? LatLng : new google.maps.LatLng( parseFloat(latitude) + 0.0001 , parseFloat(longitude) - 0.0063 ),
                    mapTypeId : google.maps.MapTypeId.ROADMAP,
                    disableDefaultUI : !disableDefaultUI ? false : true
                },

                map = new google.maps.Map( DOM_elem , options );


            setTimeout( function(){

                var marker = new google.maps.Marker({
                        map       : map,
                        draggable : true,
                        animation : google.maps.Animation.DROP,
                        position  : LatLng
                    });

                if ( isSettings ){

                    google.maps.event.addListener(map, 'click', function( event ) {

                        $('#lat_input').val(event.latLng.nb);
                        $('#lon_input').val(event.latLng.ob);

                        marker.setPosition( new google.maps.LatLng( event.latLng.nb , event.latLng.ob ) );
                        return false;
                    });
                }

            }, 1000 );


        }


    }

    function inputFilter( $this ) {
        $this.val($this.val().replace(/[^\-a-z\d]+/g ,''));
    }

     // Some significant methods.
    window.cLog = function( str, prefix, type ){

        var static_length = 32;

        if( prefix ){
            prefix = ( prefix.length < static_length ) ? prefix : prefix.substr( 0, static_length-2 );

            while( prefix.length < static_length-1 ){
                prefix += ' ';
            }

            prefix += ':';
            str = prefix + str;
        }

        type = ( !type ) ? 'log' : type;

        try{
            ( 'console' in window ) && ( console[ type ] ) && console[ type ]( str );
        }catch(e){}
    };

    Number.prototype.div = function(by){
        return (this - this % by) / by;
    };

    // Public methods & properties. __________________________________________________________________

    function USER(){

        this.setCookie = setCookie;
        this.getCookie = getCookie;
        this.loadClass = loadClass;

        this.collect_History_Object              = collect_History_Object;
        this.get_History_Object_Field            = get_History_Object_Field;
        this.append_jQuery_object_to_DOM_element = append_jQuery_object_to_DOM_element;
        this.updateHistoryRecord                 = updateHistoryRecord;
        this.addHistoryRecord                    = addHistoryRecord;
        this.updateLocalState                    = updateLocalState;
        this.renderPage                          = renderPage;


        this.getInputLimits       = getInputLimits;
        this.renderRedactor       = renderRedactor;
        this.inputFilter          = inputFilter;
        this.initialize_GoogleMap = initialize_GoogleMap;


        this.placeScrollUpBlock   = placeScrollUpBlock;


        this.platform     = platform;
        this.browser      = browser;
        this.isHistoryAPI = 'history'       in window &&
                            'pushState'     in window.history &&
                            'replaceState'  in window.history;

    }

    return new USER();

}( window );

// Image processing class.
function IMAGE_PROCESSING(){
    this.images = null;
    this.imagesPull = [];
}

IMAGE_PROCESSING.prototype.catchImages = function(start_delay, step_delay){

    var _CLASS = this;
    this.images = $('[data-processing="need"]');
    this.images.each(function(){
        //this.setAttribute( "data-turn", window.our_variables.image_processing_turn );
        _CLASS.imagesPull.push( this );
        window.our_variables.image_processing_turn++;
    });
    this.loadImage(start_delay, step_delay);
};

IMAGE_PROCESSING.prototype.loadImage = function(start_delay, step_delay){



    if( !this.imagesPull.length ) return;
    var _CLASS  = this,
        startLen,
        len     = startLen = this.imagesPull.length,
        elem    = null,
        delay   = isNaN(start_delay) ? 0 : start_delay;


    while(len){
        elem = this.imagesPull.splice( 0, 1 )[ 0 ];
        elem.load = (function( elem, len ){
            // setTimeout(function(){
                $( elem ).css("opacity", 1);
            // }, delay);
            startLen--;
            startLen == 1;
        })( elem, len );
        $( elem ).attr("src", elem.getAttribute("data-src")).removeAttr("data-processing").removeAttr("data-src");
        len = this.imagesPull.length;
        delay += isNaN(step_delay) ? 0 : step_delay;
    }

};

IMAGE_PROCESSING.prototype.replaceSrc = function( $data ){

    var src;

    $data.find( 'img' ).each(function(){
        src = this.getAttribute( 'src' );
        this.removeAttribute( 'src' );
        this.setAttribute( 'data-src', src );
        this.setAttribute( 'data-processing', 'need' );
        $( this ).css("opacity", 0);
    });
};

// Inheritance method.
var __PROTO__;
function inherit(CLASS, proto){
    __PROTO__ = function(){};
    __PROTO__.prototype = proto;
    CLASS.prototype = new __PROTO__();
    CLASS.prototype.constructor = CLASS;
    CLASS.prototype.name = CLASS.name;
    return new CLASS();
}

// Create instances.
// var methods         = inherit( USER_METHODS ),
var processing      = new IMAGE_PROCESSING();

//____

var GarbageCollector = (function(GarbageCollector, $){

    GarbageCollector.kill = function(){

        var iframes = $('iframe');

        iframes.each(function(){
            var src = $(this).attr('src');
            if ( /vk\.com/.test(src) ){
                $(this).remove();
            }
        });

        if (infLists && infLists.status){
            infLists.destroy(true);
        }

    };

    return GarbageCollector;

})({}, jQuery, window);

// simple ajax request // NOT for editing !!
var simpleAjax = (function(simpleAjax,$,gl){
    simpleAjax.call = function(sett){
        var _before   = sett.beforeSend || function(){},
            _success  = sett.success    || function(){},
            _complete = sett.complete   || function(){},
            _error    = sett.error      || function(){},
            _type     = sett.type       || 'post',
            tp = {
                type : _type,
                beforeSend: function(jqXHR, settings){
                    _before.apply(gl,[jqXHR, settings]);
                },
                success: function(data, textStatus, jqXHR){
                    _success.apply(gl,[data, textStatus, jqXHR]);
                },
                error: function(jqXHR, textStatus, errorThrown){
                    _error.apply(gl,[jqXHR, textStatus, errorThrown]);
                },
                complete: function(jqXHR, textStatus){
                    _complete.apply(gl,[jqXHR, textStatus]);
                }
            };
        $.ajax($.extend(sett,tp));
    };
    return simpleAjax;
})({},jQuery,window);


//ajax requests class
var fsAjax = (function(fsAjax, $){

    fsAjax.ChangeLocationOrHash = function(url,data){

        if ( user.isHistoryAPI ){

            fsAjax.getPage( url , data );

        } else {

            var _idx;
            // **
            // The same situation with browsers do not support History API
            _idx = url.indexOf( '?' );
            _idx = ( _idx + 1 ) ? _idx : url.length;
            window.our_variables.BrowserOnlyPath = url.substr( 0, _idx );
            window.our_variables.BrowserPathName = url;
            // **
            window.parent.location.href = '#' + url;
        }
    };

    fsAjax.getPage = function(url,data){

        data = data || {};

        simpleAjax.call({
            url: url,
            type: "POST",
            data: data,
            dataType: 'json',
            beforeSend: function(){
                //$(".loader").show();
                GarbageCollector.kill();
            },
            error: function(jqXHR, textStatus, errorThrown){
                cLog("Ajax error", 'Ajax', 'error');
                // location.href = url;
            },
            success: function(response){

                if ( response.prepend || response.append || response.before || response.after ){

                    var el;

                    if (response.prepend) el = $( response.content ).prependTo( response.prepend );
                    if (response.append)  el = $( response.content ).appendTo( response.append );
                    if (response.before)  el = $( response.content ).insertBefore( response.before );
                    if (response.after)   el = $( response.content ).insertAfter( response.after );

                } else {

                    var state = user.updateLocalState( response );

                    user.addHistoryRecord( state , state.url || url, state.title );

                    user.renderPage();

                }

            }
        });

    };

    fsAjax.pageEvents = function( firstLoad ){

        window.static_nodes.$main_content.css('minHeight', window.innerHeight);


        // window.static_nodes.$scrollUp.css({'opacity': 0, 'visibility' : 'hidden'});

        // pins.init();

        // Fixy.takeFilters();

        // if (window.isLogon) user.getNotify();

        user.getInputLimits($(".limited input, .limited textarea"));

        // $('textarea.redactor').redactor({
        //     lang: 'ru',
        //     imageUpload: '/startup/fileUpload'
        // });






        // dropdown.ajaxSelectHandler();
        // dropdown.initHeadSearch();


        // $("#templateAllowed").each(function(){
        //     $(this).css('height', $(this).get(0).scrollHeight);
        // });

        fsAjax.coolSelects();
        fsAjax.coolCheckboxes();


        // $('.injector').each(function(){
        //     fsAjax.injectBlock($(this));
        // });

        // renderTweetButton("tbutton",".twitterButton");

        // if ( firstLoad ){
        //     setTimeout(function(){FB.XFBML.parse();}, 500);
        // } else {
        //     FB.XFBML.parse();
        // }

        // if ( firstLoad ){
        //     setTimeout(function(){
        //         $('.vk_like').each(function(){API_blocks.VK.Like($(this));});
        //     }, 500);
        // } else {
        //     $('.vk_like').each(function(){API_blocks.VK.Like($(this));});
        // }


        // if ( ! window.static_nodes.replyForm ){

        //     simpleAjax.call({
        //         url : '/ajax/getReplyForm',
        //         success : function( response ){
        //             if ( response.result == 'ok' ){
        //                 window.static_nodes.replyForm = $( response.html );
        //                 cLog('Node reply form was updated' , 'Static nodes', 'info');
        //             }
        //         }
        //     });

        // }

        // $('#showFullFinances').bind('mouseover', function(){
        //     $('.finance_info_full').removeClass('hide');
        // });

        // $('.finance_info_full').bind('mouseleave', function(){
        //     $('.finance_info_full').addClass('hide');
        // });

        // $(".autosubmit").bind("change", function(){
        //     $(this).parents("form").find('input[type=submit]').click();
        // });

        $(".limited input, .limited textarea").bind("keydown", function(e){

            var value = $(this).val(),
                max_length = parseInt( $(this).parent(".input_text").attr("maxlength") , 10 );

            $(this).parent(".input_text").attr("data-limit", max_length - value.length);

            switch (e.keyCode){
                case 13: case 8: case 9: case 46: case 37: case 38: case 39: case 40: return true;
            }

            var limit = value.length ? max_length - value.length : max_length;
            return limit > 0;

        });



        $(".aFileUpload").bind("click", transport.buttonCallback);
        window.static_nodes.$transport_input.bind( "change", transport.submitCallback );


        $('#pageFileUpload').bind( "change", callback.selectFile );


    };

    fsAjax.injectBlock = function( $wrapper ){

        var url          = $wrapper.data('injection'),
            loader_class = $wrapper.data('loaderClass') || 'list_loader' ,
            scroll_id    = $wrapper.data('scroll'),
            post_data    = $wrapper.data('postData'),
            callback     = $wrapper.data('callback');

        if ( !url ){
            cLog('Injection failed: no url defined','Comments', 'error');
            return;
        }

        simpleAjax.call({
            type : 'post',
            data : post_data,
            url  : url,
            beforeSend : function(){
                $wrapper.addClass(loader_class);
            },
            success : function( response ){

                $wrapper.removeClass(loader_class);

                if ( response.result == 'success' && response.content ){

                    $wrapper.html(response.content);

                    if ( scroll_id ){
                        scroller.to( $('#comment' + scroll_id) , false );
                        $('#comment' + scroll_id).find('.message:first').addClass('colorBlink');
                    }

                    if ( response.addCommentForm ){
                        window.static_nodes.replyForm = response.addCommentForm ;
                    }

                    if ( callback  ){
                        eval( callback + '();' );
                    }
                }
            }
        });

    };

    fsAjax.coolSelects = function(){
        var selects = $(".select");
        if( selects.find("select") !== 0 ){
            jQuery.each(selects,function(){
                var hasGroups = ($(this).find("optgroup").length >= 1),
                    text;

                if (hasGroups){
                    text = $(this).children("select").children("optgroup").children("option:selected").html();
                } else {
                    text = $(this).children("select").children("option:selected").html();
                }
                $(this).prepend('<span>'+text+'</span>');
                $(this).children("select").change(function(){
                    var nval = $(this).val();
                    var nval_text = $(this).find("option[value='"+nval+"']").html();
                    $(this).parent(".select").children("span").html(nval_text);
                });
            });
        }
    };

    fsAjax.coolCheckboxes = function(){

        var checkboxes     = $(".checkbox");

        if ( checkboxes.length > 0 ){

            checkboxes.each(function(){

                var checkbox = $(this);
                    is_fake  = checkbox.data('fake');

                checkbox.bind('click', function(e){

                    var is_checked = $(this).hasClass("checked");

                    if ( ! is_checked ) {

                        $(this).find('input[type="checkbox"]').attr("checked","checked");
                        $(this).addClass('checked');

                    } else {

                        $(this).find('input[type="checkbox"]').removeAttr("checked");
                        $(this).removeClass('checked');

                    }

                    if ( ! is_fake ){
                        e.stopPropagation();
                        e.stopImmediatePropagation();
                        e.preventDefault();
                    }

                });


            });


        }

    };

    return fsAjax;
})({}, jQuery);

var transport = (function(transport, $){

    transport.response = function( response ){

        if (response.callback) {

            eval( response.callback );

        }

        if ( response.result ){

            if ( response.result == 'error' ){

                cLog( response.message || 'error' , 'TRANSPORT' , 'info' );

            }

        }
    };

    transport.buttonCallback = function(event){

        var $transport_form  = window.static_nodes.$transport_form,
            $transport_input = window.static_nodes.$transport_input;

        var name          = $(this).data('name'),
            target_id     = $(this).data('id'),
            action        = $(this).data('action'),
            is_multiple   = !!$(this).data('multiple') || false,
            $hiddenName   = $('<input type="hidden" name="name" />').val(name).appendTo($transport_form),
            $hiddenId     = $('<input type="hidden" name="id" />').val(target_id).appendTo($transport_form);
            $hiddenAction = $('<input type="hidden" name="action" />').val(action).appendTo($transport_form);

        if ( is_multiple ) $transport_input.attr('multiple', 'multiple');

        $transport_form.data('action', action);

        $transport_input.click();

    };

    transport.submitCallback = function (){
        var $form       = window.static_nodes.$transport_form,
            $fileInputs = $form.find('input[type="file"]'),
            action      = $form.data('action');


        var files = transport.getFileObject( $(this).get(0) );

        for (var i = files.length - 1; i >= 0; i--) {
            console.log( transport.validateExtension(files[i]) );
            if ( transport.validateExtension(files[i]) && transport.validateMIME(files[i]) ){
                if ( transport.validateSize( files[i] , 30 * 1024 * 1024) ) {

                        window.static_nodes.$transport_form.submit();

                        if ( action == 'pageFiles' ) callback.uploadpageFile.beforeSend();

                } else {
                    CLIENT.showException('File size exceeded limit - ' + (files[i].size / (1000*1000)).toFixed(2) + 'MB');
                }
            } else {
                CLIENT.showException('Wrong file type: <b>' + files[i].name + '</b>');
            }
        }

    };

    transport.checkErrorLoading = function( event ){

        if ( event.currentTarget.contentDocument.title == '413 Request Entity Too Large' ){

            callback.uploadpageFile.clearForm();

            $('#entityError').removeClass('hide');
            $('#pageFileUpload').parent('.button').addClass('wobble');
        }

    };

    transport.getFileObject = function ( fileInput ) {
        if ( !fileInput ) return false;
        return typeof ActiveXObject == "function" ? (new ActiveXObject("Scripting.FileSystemObject")).getFile(fileInput.value) : fileInput.files;
    };

    transport.validateMIME = function ( fileObj , accept ){

        accept = typeof accept == 'array' ? accept : ['image/jpeg','image/png'];

        for (var i = accept.length - 1; i >= 0; i--) {
            if ( fileObj.type == accept[i] ) return true;
        }
        return false;

    };

    transport.validateExtension = function( fileObj , accept ){

        var ext = fileObj.name.match(/\.(\w+)($|#|\?)/);
        if (!ext) return false;

        ext = ext[1].toLowerCase();

        accept = typeof accept == 'array' ? accept : ['jpg','jpeg','png'];

        for (var i = accept.length - 1; i >= 0; i--) {
            if ( ext == accept[i] ) return true;
        }

        return false;

    };

    transport.validateSize = function ( fileObj , max_size) {
        return fileObj.size < max_size;
    };

    return transport;

})({}, $);


var scroller = (function(scroller, $){

    scroller.to = function( $element , is_animate ){

        is_animate = is_animate ? is_animate : false;

        if ( $element ){

            var pos = $element.offset();

            if ( is_animate ){

                $('html').animate({ scrollTop : pos.top  }, 300);

            } else {

                $(window).scrollTop( pos.top );

            }

        }

    };

    return scroller;

})({}, $);



// dropdown searching
var dropdown = (function(dropdown,$){

    var search_hide=1;

    dropdown.init = function(input, search_url, results_block, input_to_paste){

        cLog("Going into init method" , 'Dropdown' , 'error');

        /* ............................................................
            input           -> input whom bind
            search_url      -> url to send ajax request for search
            results_block   -> block in whom we append results div's .r_block
        ............................................................... */
    };

    dropdown.initHeadSearch = function (  ) {

        var obj    = $("#head_select");
            holder = obj.parent('.search_holder');

        dropdown.ajaxSelectInit( holder );

        obj.bind( 'blur' , function(){

            var val = $(this).val();

            if ( val.length !== 0 ){
                if (search_hide){
                    holder.removeClass('downed');
                }
            }
        });
        $('.head_search .items').bind( 'mouseenter' , function(){
            search_hide=0;
        });
        $('.head_search .items').bind( 'mouseleave' , function(){
            search_hide=1;
            if (!obj.is(':focus')){
                holder.removeClass('downed');
            }
        });
    };

    dropdown.responseHandler = function (response, results_block, data){

        if( response.result == "ok"){
            if(!$.isEmptyObject(response)){

                var select_block  = results_block.parents(".ajaxselect"),
                    search_string = data['search_string'],
                    action        = data['action'],
                    items_title;

                results_block.html('');


                if ( select_block.data("type") == 'cities' ) {
                    items_title = 'cities';
                } else if ( select_block.data("type") == 'industries' ){
                    items_title = 'industries';
                } else if ( select_block.data("type") == 'countries' ){
                    items_title = 'countries';
                } else if ( select_block.data("type") == 'job_types' ){
                    items_title = 'types';
                } else if ( select_block.data("type") == 'operations' ){
                    items_title = 'operations';
                }

                if ( action == 'getCitiesByCountryId'){
                    results_block.html('');
                } else if ( action == 'global_search' ){
                    select_block = results_block.parents('.search_holder');
                    items_title = 'items';
                }


                if( !$.isEmptyObject(response[items_title]) && response[items_title] != '[]' ){

                    var items = JSON.parse(response[items_title]);

                    for (var numb in items){

                        if ( action != 'global_search' ){ // JSON elements structurize

                            var reg = new RegExp(search_string, 'gi'),
                                item_name, subtext, id_name;

                            if ( items_title == 'cities' ){

                                item_name = 'city';
                                id_name   = 'city_id';
                                subtext   = '<div class="subtext">' + items[numb]['state'] + '</div>';

                            } else if (items_title == 'countries' ){

                                item_name = 'country';
                                id_name   = 'country_id';
                                subtext   = '';

                            } else if (items_title == 'industries' ){

                                // var links = items[numb]['links'] ? items[numb]['links'] : '';

                                item_name = 'title_ru';
                                id_name   = 'id';
                                subtext   = '<span>' + items[numb]['title_en'] + '</span>';

                            } else if (items_title == 'types' ){

                                item_name = 'title_ru';
                                id_name   = 'id';
                                subtext   = '<span>' + items[numb]['title_en'] + '</span>';

                            } else if (items_title == 'operations' ){

                                item_name = 'title_ru';
                                id_name   = 'id';
                                subtext   = '';

                            }

                            var founded_words  = reg.exec(items[numb][item_name]),
                                main_word      = '<div class="name" data-name="' + items[numb][item_name] + '">' + items[numb][item_name].replace(reg, '<i class="founded_words">' + founded_words + '</i>') + '</div>',
                                searched_block = ''.concat(main_word, subtext),
                                block          = $('<div class="select_item r_block" data-id="' + items[numb][id_name] + '" />').html(searched_block).appendTo(results_block);

                            dropdown.bindItemEvents(block);

                        } else { // JSON items has html markup

                            var item = $( items[numb] ).appendTo(results_block);

                            dropdown.bindItemEvents( item );

                        }
                    }


                    select_block.addClass("downed");


                } else {

                    select_block.addClass("downed");

                    if ( items_title == 'operations' && select_block.data('action') == 'paste_id' ){

                        var addButton = $('<div class="r_block select_item add_new">').html('<i class="icon plus"></i>Добавить услугу');
                        results_block.html(addButton);

                        dropdown.bindItemEvents(addButton);

                    } else {
                        results_block.html('<div class="r_block select_item not_found">Ничего не найдено </div>');
                    }
                }

            }

        }
    };

    dropdown.ajaxSelectInit = function (obj){

        var select_block  = obj,
            corner        = select_block.children(".corner"),
            search_input  = select_block.children(".search"),
            items_block   = select_block.find(".items"),
            select_type   = select_block.data("type"),
            url_to_select;

        select_block.removeClass("disabled");

        // unbind events from disabled mode
        search_input.unbind("keydown");
        search_input.unbind("focus");


        if (items_block.length === 0){
            items_block = $('<div class="items" />').appendTo(select_block);
        }


        if ( select_type == 'cities' ){
            url_to_select = '/search/city';
        } else if ( select_type == 'industries' ){
            url_to_select = '/search/industry';
        } else if ( select_type == 'countries' ){
            url_to_select = '/search/country';
        } else if ( select_type == 'job_types' ){
            url_to_select = '/search/job_types';
        } else if ( select_type == 'operations' ){
            url_to_select = '/search/operations';
        } else if ( select_type == 'global_search' ){
            url_to_select = '/globalsearch';
        }

        corner.bind("click", function () {
            if(!select_block.hasClass("downed")){
                select_block.addClass("downed");
            } else {
                select_block.removeClass("downed");
            }
        });
        search_input.focus(function (e) {
            select_block.addClass("downed");
            items_block.parent(".items_wrap").scrollTop(0);
            e.stopPropagation();
            return false;
        });
        search_input.blur(function () {

            window.our_variables.blur_blocked = true;

            if ( !window.our_variables.blur_blocked ){
                select_block.removeClass("downed");
            }

            setTimeout(function(){
                window.our_variables.blur_blocked = false;
                if (search_hide)
                {
                    select_block.removeClass("downed");
                }
            }, 200);


            // window.our_variables.blur_blocked = false;
            // if (search_hide){
            //     select_block.removeClass("downed");
            // }
        });

        search_input.bind('input paste', function() {
            var data               = {};
                data.search_string = $(this).val();


            if ( select_type == 'cities' ){

                data.countryId = $(".ajaxselect").filter(function(){return $(this).data("type") == 'countries';}).data("selected-id");

            } else if ( select_type == 'global_search' ) {

                data.action = 'global_search';
            }

            dropdown.find(data, url_to_select, items_block, '', dropdown.responseHandler );
        });
        search_input.bind("keydown", function(e){

            if(select_block.hasClass("downed") && (e.keyCode == 38 || e.keyCode == 40)){
                switch(e.keyCode){
                    case 38: dropdown.select(items_block, "up"); break;
                    case 40: dropdown.select(items_block, "down"); break;
                }
            } else if( !select_block.hasClass("downed") && (e.keyCode == 38 || e.keyCode == 40) ){
                select_block.addClass("downed");
            }
            if(e.keyCode == 13){
                var active = items_block.find(".active");
                if ( active.length == 1 ){
                    dropdown.selectItem(window.our_variables.selected_item, true);
                    return false;
                }
            }
        });
    };

    dropdown.disableInput = function(obj){

        var search_input  = obj.children(".search");

        if ( ! obj.hasClass("disabled") ){
            obj.children(".corner").unbind("click");
            search_input.unbind("focus");
            search_input.unbind("keydown");
            obj.addClass("disabled");
        }
        search_input.bind("keydown", function(e){
            $(this).blur();
            return false;
        });
        search_input.focus(function(e){
            $(this).blur();
        });
    };

    dropdown.ajaxSelectHandler = function(){

        var corner = $('<div class="corner"/>').appendTo(".ajaxselect");

        dropdown.bindItemEvents($(".select_item"));

        $(".del_tag").bind('click', dropdown.delTagHandler);


        if( $(".ajaxselect").length !== 0 ){
            $(".ajaxselect").each(function(){

                if( !$(this).hasClass("disabled") ){
                    dropdown.ajaxSelectInit($(this));
                } else {
                    dropdown.disableInput($(this));
                }

            });
        }
    };

    dropdown.bindItemEvents = function(obj){

        obj.bind("click", dropdown.selectItem );

        obj.bind("mouseover", function(){
            if( !$(this).hasClass('not_found')) {
                $(".select_item").removeClass("active");
                $(this).addClass("active");
            }
        });

        obj.bind("mouseout", function(){$(this).removeClass("active");});
    };

    dropdown.selectItem = function(obj, is_direct_obj){

        obj             = is_direct_obj ? obj : $(this);

        var is_global_search = obj.data('type') == 'global_search';

        if ( is_global_search ){

            var link = obj.attr('href');
            fsAjax.ChangeLocationOrHash(link);

            return false;

        }

        var type           = obj.parents(".ajaxselect").data("type"),
            action         = obj.parents(".ajaxselect").data("action"),
            first_parametr = obj.parents(".ajaxselect").data("firstParametr"),
            id             = obj.data("id"),
            industry_id    = 0,
            city_id        = 0,
            type_id        = 0,
            service_id     = 0,
            url            = '';


        if ( type == 'cities' ){

            city_id     = id;
            industry_id = $(".ajaxselect").filter(function(){return $(this).data("type") == 'industries';}).data("selected-id");
            country_id  = $(".ajaxselect").filter(function(){return $(this).data("type") == 'industries';}).data("selected-id");
            type_id     = $(".ajaxselect").filter(function(){return $(this).data("type") == 'job_types';}).data("selected-id");
            service_id  = $(".ajaxselect").filter(function(){return $(this).data("type") == 'operations';}).data("selected-id");

        } else if ( type == 'industries' ){

            city_id     = $(".ajaxselect").filter(function(){return $(this).data("type") == 'cities';}).data("selected-id");
            industry_id = id;

        } else if ( type == 'countries' ){

            city_id    = $(".ajaxselect").filter(function(){return $(this).data("type") == 'cities';}).data("selected-id");
            country_id = id;

        } else if ( type == 'job_types' ){

            type_id    = id;
            city_id    = $(".ajaxselect").filter(function(){return $(this).data("type") == 'cities';}).data("selected-id");

        } else if ( type == 'operations' ){

            city_id     = $(".ajaxselect").filter(function(){return $(this).data("type") == 'cities';}).data("selected-id");
            industry_id = $(".ajaxselect").filter(function(){return $(this).data("type") == 'industries';}).data("selected-id");
            service_id  = id;

        }


        if( action == 'paste_id' ){

            var input_to_paste = obj.parents(".ajaxselect").find('#value'),
                search_input   = obj.parents(".ajaxselect").find('.search'),
                selected_name  = obj.find('.name').data("name"),
                result_input_value, splitted_array, tagBlock, delTag;


            if ( type == 'cities' ){

                input_to_paste.val(city_id);
                obj.parents(".ajaxselect").data("selected-id", city_id);

                search_input.val(selected_name);

            } else if ( type == 'countries' ){

                input_to_paste.val(country_id);

                cLog('Entered from country' , 'Dropdown'  , 'warn' );

                if( $("#selectCity").hasClass("disabled") ){

                    dropdown.ajaxSelectInit( $("#selectCity") );

                }

                var data               = {};
                    data.search_string = '';
                    data.action        = 'getCitiesByCountryId';
                    data.countryId     = country_id;

                $("#selectCity .search").val('');
                $("#selectCity #value").val('');
                $("#selectCity").data('selected-id','');

                dropdown.find(data, '/search/city', $("#selectCity").find(".items"), '', dropdown.responseHandler);

                obj.parents(".ajaxselect").data("selected-id", country_id);

                search_input.val(selected_name).blur();

            } else if ( type == 'industries' ){

                dropdown.makeTag( industry_id , obj , $('.industries_tags') );

            } else if ( type == 'job_types' ){

                dropdown.makeTag( type_id , obj , $('.types_tags') );

            } else if ( type == 'operations' ){

                if ( ! obj.hasClass('add_new') ){

                    dropdown.makeTag( service_id , obj , $('.operations_tags') );

                } else {

                    dropdown.add_New_Item_Handler('add_operation' , obj);

                }

            }


            obj.parents(".ajaxselect").removeClass("downed");


            return false;



        } else if ( action == 'open_page' ){

            if( type == 'industries' || type == 'cities' || type == 'job_types' || type == 'operations' ){

                var page = obj.parents(".ajaxselect").data("page");

                if( page && page != 'undefined'){

                    if( !industry_id ) industry_id = 0;
                    if( !type_id ) type_id = 0;
                    if( !city_id ) city_id = 0;



                    if ( type == 'job_types' || first_parametr == 'job_type' ){
                        url = page + type_id + '/' + city_id;
                    } else if ( type == 'industries' || type == 'cities' ){

                        if ( first_parametr == 'service_city' ){
                            url = page + type_id + '/' + industry_id +'/' + service_id + '/' + city_id;
                        } else {
                            url = page + industry_id + '/' + city_id;
                        }

                    } else if ( type == 'operations' ){
                        url = page + type_id + '/' + industry_id +'/' + service_id + '/' + city_id;
                    }


                    fsAjax.ChangeLocationOrHash(url);

                    if ( type == 'industries' || type == 'job_types'){
                        obj.parents(".ajaxselect").data("selected-id", industry_id !== 0 ? industry_id : type_id );
                    } else if ( type == 'cities' ){
                        obj.parents(".ajaxselect").data("selected-id", city_id);
                    } else if ( type == 'operations' ){
                        obj.parents(".ajaxselect").data("selected-id", service_id);
                    }
                } else {
                    cLog('Data-page attribute is required' , 'Dropdown' , 'error');
                }

            }

        }
    };

    dropdown.makeTag = function( item_id , obj , $tags_block, title ){

        var $ajaxselect        = obj.parents(".ajaxselect"),
            input_to_paste     = $ajaxselect.find('#value'),
            search_input       = $ajaxselect.find('.search'),
            type               = $ajaxselect.data('type'),
            result_input_value = input_to_paste.val(),
            splitted_array     = result_input_value.split(","),
            selected_name      = title || obj.find('.name').data("name");

        if( $.inArray( String(item_id), splitted_array ) != -1 ) {
            search_input.val('');
            return false;
        }

        result_input_value += (result_input_value.length === 0) ? item_id : ',' + item_id;
        input_to_paste.val(result_input_value);

        if ( $ajaxselect.hasClass('only_one') ){
            dropdown.disableInput( $ajaxselect );
        }

        search_input.css({"display":"inline-block","width":"auto"}).val('');
        tagBlock = $('<span class="tagblock" data-id="' + item_id +'" />').html(selected_name).appendTo( $tags_block );
        delTag   = $('<span class="del_tag" />').data('type', type).appendTo(tagBlock).bind('click', dropdown.delTagHandler);
    };

    dropdown.delTagHandler = function ( e ){

        var $delTag        = $(this),
            type           = $delTag.data('type'),
            id             = $delTag.parent(".tagblock").data('id'),
            start_stack, old_values, input_to_paste, new_stack, $ajaxselect;


            if ( type == 'job_types' ){

                input_to_paste = $('input[name="job_type"]');
                start_stack    = input_to_paste.val();
                old_values     = start_stack ? start_stack.split(',') : '';

                input_to_paste.val('');

                $ajaxselect = $('#selectType');

                $ajaxselect.removeClass("disabled");
                dropdown.ajaxSelectInit( $ajaxselect );

            } else {

                if ( type == 'industries' ){
                    input_to_paste = $('input[name="industries"]');
                    $ajaxselect    = $('#selectIndustry');

                } else if ( type == 'operations' ){
                    input_to_paste = $('input[name="operations"]');
                    $ajaxselect    = $('#selectOperation');
                } else {
                    cLog('Ajaxselect data type is not defined' , warn);
                    return false;
                }

                start_stack    = input_to_paste.val();
                old_values     = start_stack ? start_stack.split(',') : '';

                if ( $ajaxselect.hasClass("only_one") ) {

                    input_to_paste.val(id);
                    $ajaxselect.removeClass("disabled");
                    dropdown.ajaxSelectInit( $ajaxselect );


                } else {

                    var pos = $.inArray( String(id), old_values );

                    if( pos != -1 ) delete old_values[ pos ];

                    new_stack = old_values.toString();

                    // // 15,,46,1 -> 15,45,1
                    new_stack   = new_stack.replace(',,', ',');

                    // // ,15,16,46 -> 15,16,46
                    if ( new_stack.charAt(0) == ',' ) new_stack = new_stack.slice(1);

                    // // 15,16,46, -> 15,16,46
                    if ( new_stack.charAt(new_stack.length - 1) == ',' ) new_stack = new_stack.slice(0, new_stack.length - 1);

                    input_to_paste.val(new_stack);

                }

            }

            $delTag.parent(".tagblock").remove();
    };

    dropdown.find = function(data, search_url, results_block, keyCode, responseHandler){

        // data must have property "search_string"

        if( keyCode && (
                keyCode == 46 ||  // delete
                (keyCode > 8 && keyCode < 32) || // backspace, enter, alt, ctrl, etc.
                keyCode ==  144 || //numlock
                (keyCode >= 112 && keyCode <= 122) || //f1-f12
                keyCode == 91 // windows button
            )
        ){
            return false;
        }

        if (data.action || data['search_string'].length > 1) {
            // если поиск глобальный, то минимум 3 символа
            if (data.action && data.action=='global_search' && data['search_string'].length<3){
                return true;
            }
            simpleAjax.call({
                type: 'post',
                url: search_url,
                dataType: "json",
                data: data,
                success: function(response){
                    responseHandler(response, results_block, data);
                }
            });
        }
    };

    dropdown.add_New_Item_Handler = function( action , obj ){

        var value = obj.parents('.ajaxselect').find('.search').val();

        if ( ! value ) return;

        if ( action == 'add_operation' ){

            simpleAjax.call({
                type : 'post',
                url  : '/ajax/addOperation',
                data : { value : value },
                success : function( response ){

                    if ( response.result == 'ok' ){

                         dropdown.makeTag( response.sid , obj , $('.operations_tags'), response.title );

                    }
                }

            });

        }
    };

    dropdown.select = function(results_block, direction){

        var active = results_block.find(".active"),
            toSelect;


        if ( active.length === 0 ) {

            if ( direction == 'down' ){

                if ( !results_block.children(".r_block:first").hasClass("not_found") ){

                    toSelect = results_block.children(".r_block:first");
                    if ( toSelect.hasClass('heading') ) toSelect = toSelect.next('.r_block');

                }

            } else {

                if ( !results_block.children(".r_block:last").hasClass("not_found") ){

                    toSelect = results_block.children(".r_block:last");
                    if ( toSelect.hasClass('heading') ) toSelect = toSelect.prev('.r_block');

                }

            }

            toSelect.addClass("active");

            window.our_variables.selected_item = toSelect;

        } else {
            if(direction == 'down'){

                toSelect = results_block.find(".active").next(".r_block");

                if ( toSelect.hasClass('heading') ) toSelect = toSelect.next('.r_block');


            } else {

                toSelect = results_block.find(".active").prev(".r_block");

                if ( toSelect.hasClass('heading') ) toSelect = toSelect.prev('.r_block');
            }

            results_block.children(".r_block").removeClass("active");

            window.our_variables.selected_item = toSelect;

            var st                    = toSelect.position(),
                parent_height         = results_block.parent(".items_wrap").height(),
                scrolled_block_offset = results_block.position(),
                scrollY;

            if(direction == 'down'){
                if( st && st.top >= (parent_height - 30) ){
                    scrollY = parseInt( - scrolled_block_offset.top) + parseInt(parent_height) - 30;
                    results_block.parent(".items_wrap").stop().animate({ 'scrollTop' : scrollY }, 200);
                }
            } else {
                if( st && st.top <= 30 ){
                    scrollY = parseInt( - scrolled_block_offset.top) - parseInt(parent_height) + 30;
                    results_block.parent(".items_wrap").stop().animate({ 'scrollTop' : scrollY }, 200);
                }
            }
            if( !toSelect.hasClass("not_found") ) {
                toSelect.addClass("active");
            }
        }
    };

    return dropdown;

})({},jQuery,window);

// directajax callback functions
var directajax = (function(directajax,$){

    directajax.addedIndustry = function(data){
        var tr         = $('<tr/>'),
            td_en      = $('<td/>').html('<span class="editable" data-id="' + data.id + '" data-key="title_en">' + data.title_en + '</span>').appendTo(tr),
            td_ru      = $('<td/>').html('<span class="editable" data-id="' + data.id + '" data-key="title_ru">' + data.title_ru + '</span>').appendTo(tr),
            td_links   = $('<td/>').appendTo(tr),
            td_actions = $('<td class="actions" />').html('<i class="icon cross deleteIndustry pointer" data-id="' + data.id + '" data-title="Удалить индустрию">').appendTo(tr);

        $(".industries_table tr:first").after(tr);

    };

    directajax.addedType = function(data){
        var tr         = $('<tr/>'),
            td_en      = $('<td/>').html('<span class="editable" data-id="' + data.id + '" data-key="title_en">' + data.title_en + '</span>').appendTo(tr),
            td_ru      = $('<td/>').html('<span class="editable" data-id="' + data.id + '" data-key="title_ru">' + data.title_ru + '</span>').appendTo(tr),
            td_links   = $('<td/>').appendTo(tr),
            td_actions = $('<td class="actions" />').html('<i class="icon cross deleteIndustry pointer" data-id="' + data.id + '" data-title="Удалить тип">').appendTo(tr);

        $(".types_table tr:first").after(tr);

    };

    directajax.followStartup = function($this){

        var sid = $this.data('sid');

        simpleAjax.call({
            type    : 'post',
            url     : '/follow',
            data    : { sid : sid },
            success : function( response ){

                if ( response.result == 'success' ) {

                    $this.addClass('hide');
                    $( '#' + (response.msg == 'followed' ? 'u' : 'f') + sid ).removeClass('hide');

                }

            }

        });

    };

    directajax.toggleFinanceStatus = function( sid , action ){

        var status = $('#need_invest').val() == 1 ? 0 : 1;

        if ( action == 'closeRound' ){

            status = 0;

            $('#need_invest_checkbox').removeClass('checked');

            $('#roundClosedSuccess').removeClass('hide')
            $('.add_round').removeClass('hide bounceIn').addClass('bounceIn');
            scroller.to($('.add_round'));

        }

        simpleAjax.call({
            type    : 'post',
            url     : '/togglefinancestatus',
            data    : { need_invest : status , sid : sid },
            success : function( response ){

                if ( response.result == 'success' ) {

                    $("#need_invest").val(status);

                }

            }

        });

        $('.invest_tour').toggleClass('hide');

    };

    directajax.removeRound = function ( $this ){

        simpleAjax.call({
            type    : 'post',
            url     : $this.attr('href'),
            data    : { ajax : 1 },
            success : function( response ){

                if ( response.result == 'success' ) {

                    $this.parents('.item').fadeOut(300);

                }

            }

        });

    };

    directajax.saveDraft = function( form , url , is_publish ){

        var dataObj = {},
            data    = form.serializeArray();

        $.each( data , function(_, kv) {
          dataObj[kv.name] = kv.value;
        });

        dataObj.draft_id = window.our_variables.blog_draft_id;

        if ( is_publish ){

            dataObj.from = 'blog';

            if ( !window.our_variables.blog_draft_id ){
                form.submit();
                return true;
            }
            dataObj.publish = 1;

        }

        simpleAjax.call({
            url  : url,
            data : dataObj,
            type : 'post',
            success : function( response ){

                if ( response.result == 'success' ){

                    var href = $('.updating_time').attr('href');

                    if ( response.draft_id ){

                        window.our_variables.blog_draft_id = response.draft_id;

                        if ( ! is_publish ){

                            $('.updating_time').attr('href' , response.redirect);

                        }

                    }

                    if ( is_publish ){
                        window.our_variables.blog_draft_id = 0;
                        fsAjax.ChangeLocationOrHash( response.redirect ? response.redirect : href );
                    }

                    $('.updating_time').html(response.msg).removeClass('hide').addClass('bounceIn');

                }

            }
        })

    };

    directajax.getPostComments = function ( $this ){

        var post_id = $this.data('postId'),
            startup = $this.data('startup'),
            ignore  = $this.data('ignore');

        simpleAjax.call({
            type    : 'post',
            url     : $this.attr('href'),
            data    : { ajax : 1, postId : post_id, startup: startup, ignore: ignore },
            success : function( response ){
                if ( response.result == 'success' ) {
                    $this.parents('.comments').html(response.content);
                }
            }

        });

    };

    directajax.getWallQuestions = function ( $this ){

        var startup = $this.data('startup');

        simpleAjax.call({
            type    : 'post',
            url     : $this.attr('href'),
            data    : { ajax : 1, startup: startup },
            success : function( response ){
                if ( response.result == 'success' ) {
                    $this.parents('.wall').html(response.content);
                }
            }

        });

    };

    directajax.submitComment = function ( data , form ){

        $new_comment = $( data.new_comment );

        if ( data.parent_id ){

            $new_comment.addClass('children');

            var cBlock = $('#comment' + data.parent_id);

            if ( cBlock.hasClass('children') ){
                cBlock.parent('.body').append( $new_comment );
            } else {
                cBlock.children('.body').append( $new_comment );
            }
        } else {
            $('.comments_block').append( $new_comment );
        }
        form.find('textarea').val('');

        if ( form.hasClass('reply_form') ){
            form.remove();
        }



    };


    return directajax;

})({},jQuery,window);


var infLists = function(infLists, $, window){

    infLists.init = function ( params ){

        infLists.status = true;

        params = params ? params : {};


        infLists.settings = {
            'url'            : params.url || ( user.isHistoryAPI ? (window.location.pathname ? window.location.pathname : '/') : (window.location.hash ? window.location.hash : '/') ),
            'portion_length' : params.portionLength  || 10,
            'callback'       : params.callback       || false,
            'blocksSelector' : params.blocksSelector || '.blist',
            'blocksWrapper'  : params.blocksWrapper  || '.blist_wrap',

            'wheight'        : $(window).height(),
            'dheight'        : $(document).height(),
            'dif'            : this.dheight - this.wheight,
            'loadblock'      : false,
            'iterations'     : 0,
            'full_url'       : '',
            'page_numb'      : 1
        };

        //if ( $(".blist").length >= infLists.settings.portion_length ){

            infLists.settings.loadblock = true;

            $(".pager").hide();

            infLists.showGoUpButton();

            // if ( params.url ) {

            //     infLists.settings.full_url  = params.url;
            //     infLists.settings.page_numb = 1;

            // } else {

            if ( window.our_variables.state.infListPortion.url ){
                infLists.settings.url = window.our_variables.state.infListPortion.url;
            }

            infLists.setPageUrl( infLists.settings.url );

            // }



            setTimeout(function(){

                infLists.settings.loadblock = false;
                infLists.ajaxAndAppend(true);

            }, 500);

            $(window).bind("scroll",infLists.callback);


            cLog("infLists was successfully initialized");

        // } else {

        //     cLog("infLists was not initialized because there is not enought .blist's (need " +  infLists.settings.portion_length + "). There is " + $(".blist").length );

        // }


    };

    infLists.setPageUrl = function( url ){

         var section   = url.match(/[a-z]+/gi),
             url_parts = (url != '/') ? url.match(/\w+/gi) : '/',
             numbers   = url.match(/[0-9]+/g);

            if ( parseFloat(url_parts[url_parts.length - 1]) ){
                infLists.settings.page_numb =  url_parts[url_parts.length - 1];
                infLists.settings.full_url  = url.substring(0, url.length - infLists.settings.page_numb.length);
            } else {
                infLists.settings.page_numb =  1;
                infLists.settings.full_url  = url;
            }

    };

    infLists.showGoUpButton = function(){

        if( $(".goUp").length === 0 ){

            var goUp = $('<div class="goUp hided" />').appendTo("body").bind( 'click' , function(e){

                e.stopImmediatePropagation();
                e.stopPropagation();

                window.our_variables.ajax_blocked = true;

                $('html, body').animate({scrollTop: 0}, 500);
                setTimeout(function(){window.our_variables.ajax_blocked = false;}, 900);

            });
        }

    };

    infLists.callback = function(){

        infLists.settings.dif = $(document).height() - infLists.settings.wheight;

        var scroll_distance = 700;

        if ( !infLists.settings.loadblock && infLists.settings.dif - $(window).scrollTop() <= scroll_distance ){

            infLists.ajaxAndAppend();

        }

        if ( $(window).scrollTop() <= 300 && !$(".goUp").hasClass("hided") ){
            $(".goUp").addClass("hided");
        }

        if ( infLists.settings.iterations >= 1  && $(window).scrollTop() > 300 && $(".goUp").hasClass("hided") ){
            $(".goUp").removeClass("hided");
        }

    };

    infLists.ajaxAndAppend = function(is_preScroll_calling){

        if ( !infLists.status ) {
            return false;
        };

        infLists.settings.loadblock = true;

        if ( (infLists.settings.iterations == 1 || is_preScroll_calling) && $(".list_loader").length === 0 ){
            $(".blist_wrap").append("<div class='list_loader hide'></div>");
        }

        infLists.settings.page_numb++;

        var page        = infLists.settings.page_numb,
            request_url = ((infLists.settings.full_url.substr(-1) == '/') ? infLists.settings.full_url : infLists.settings.full_url + '/') + page;

        var data = 'ajax=infinite';

        $.ajax({
            url:  request_url,
            type: 'POST',
            data: data,
            dataType: 'json',
            beforeSend: function(){
                if ( infLists.settings.iterations > 0 ){
                    $(".list_loader").removeClass('hide');
                }
            },
            success: function(response){


                var html = response ? response.content : '';

                if(typeof html == 'string') html = html.trim();

                if( html && html != 'empty' ){

                    setTimeout(function(){

                        $(".list_loader").addClass('hide');

                        if(html && !html.error){

                            var $html = $( html );

                            processing.replaceSrc( $html );

                            window.our_variables.state.infListPortion = { url : request_url };

                            $(".blist_wrap .list_loader").before( $html );

                            if ( infLists.settings.callback ){
                                eval( infLists.settings.callback + '( $html )' );
                            }

                            processing.catchImages();

                        } else if( html.error ){

                            infLists.ajaxAndAppend();
                            return false;

                        }

                        infLists.settings.loadblock = false;

                        if(infLists.settings.iterations == 1 && $(".goUp").hasClass("hided")) $(".goUp").removeClass("hided");

                        infLists.settings.iterations++;

                    }, 100);

                } else {

                    infLists.settings.loadblock = true;
                    $(".list_loader").addClass('hide');
                    cLog("Empty portion. InfList blocked");

                }
            }
        });

        return false;

    };

    infLists.destroy = function(isPageChange){

        cLog("inf lists destroyed");
        $(window).unbind("scroll", infLists.callback);
        infLists.status = false;
        if(isPageChange) $(".goUp").addClass("hided");

    };

    return infLists;

}({}, jQuery, window);

// fixed block module
var Fixy = (function(Fixy,$){

    Fixy.init = function( block ){

        var blockHeight = block.innerHeight(),
            blockOffset  = block.offset(),
            blockX       = blockOffset.top;

        block.parent('.fixy_wrap').css('height', blockHeight);



        var $window = $(window);

        $(window).bind('scroll', function( e ){

            var blockStyle  = {

                lPadding : parseInt(block.css('paddingLeft')),
                rPadding : parseInt(block.css('paddingRight')),
                width    : parseInt(block.css('width'))

            };

            if ( $window.scrollTop() >= blockX ){

                var blockWidth = block.innerWidth() - blockStyle.lPadding - blockStyle.rPadding;

                block.addClass('fixy_top').css('width', blockWidth )

            } else {

                block.removeClass('fixy_top').css('width', 'auto');

            }

        });

    };

    return Fixy;

})({},jQuery,window);


var CLIENT = (function(CLIENT, $){

    CLIENT.showException = function ( message , type ){

        type = type || 'error';

        var isFirst   = !$('.exceptionWrapper').length,
            $excWrap  = isFirst ? $('<div class="exceptionWrapper" />').prependTo( $('.main_wrap') ) : $('.exceptionWrapper'),
            $excBlock = $('<div class="clientException" />').addClass(type).html(message).appendTo($excWrap).addClass('bounceIn');

        setTimeout(function(){$excBlock.remove();}, 8000);

    };

    return CLIENT;

})({}, $);

var callback =  function (callback, window, $) {

    callback.a_click = function (e) {

        if (this.getAttribute("target") == "_blank") return;

        var url = $(this).attr("href");
        if (!url || (url == '#')) return;

        var isExternal = /(?:ht|f)tp/.test( url.substr(0,4) ),
            isInternal = false;

        if (e.ctrlKey || e.metaKey) return;

        if ( isExternal ){ // checking for internal links with protocol

            var host = new RegExp('^(?:ht|f)tp:\/\/' + location.host);
            isInternal = host.test(url);

        }

        if ( (!isExternal || isInternal) && !$(this).hasClass("ajaxfree") && ! $(this).hasClass("directajax") ){

            window.our_variables.data = {};

            if ( !window.our_variables.ajax_blocked ){

                window.our_variables.state.content = window.static_nodes.$main_content.html();
                window.our_variables.state.offset  = window.static_nodes.$window.scrollTop();

                user.updateHistoryRecord();

                fsAjax.ChangeLocationOrHash( url , window.our_variables.data );

            }

            window.our_variables.ajax_blocked = true;
            setTimeout(function(){window.our_variables.ajax_blocked = false}, 500);

            e.preventDefault();

        } else if ( $(this).hasClass("moderate_action") ) {

            e.stopPropagation();
            e.stopImmediatePropagation();
            e.preventDefault();

            var _this  = $(this),
                action = _this.data('action'),
                url    = _this.attr("href");

            simpleAjax.call({
                url: url,
                type: 'post',
                data: {"action" : action},
                success: function(response){
                    if(response.result == 'ok'){
                        cLog( _this.parents('.item') , 'Moderate_action');
                        var type = /(approve|rise)/.test(url) ? 'approved' : 'rejected';
                        _this.parents('.item').addClass(type);
                        setTimeout(function(){
                             _this.parents('.item').fadeOut(100);
                        }, 100)
                    }
                }
            });

            return false;

        } else if ( $(this).hasClass("directajax") ){

            e.stopPropagation();
            e.stopImmediatePropagation();
            e.preventDefault();

            eval('directajax.' + $(this).data('callback') + '($(this))');

            return false;

        } else if ( isExternal ){

            $(this).attr("target","_blank");

        }

    };

    callback.form_submit = function (event) {

        var form              = $(this),
            is_file_uploading = form.attr('enctype') == 'multipart/form-data';

        if ( form.hasClass("blocked") || window.our_variables.ajax_blocked ) {

            event.preventDefault;
            return false;

        } else if( !form.hasClass("ajaxfree") && !is_file_uploading ){

            window.our_variables.ajax_blocked = true;

            setTimeout(function(){window.our_variables.ajax_blocked = false}, 1500);

            var url      = form.attr("action"),
                callback = form.data("callback");

            window.our_variables.data = form.serialize();

            if( !form.hasClass("directajax") ){
                if( user.isHistoryAPI ){
                    if(!url) url = location.pathname+location.search;
                    fsAjax.ChangeLocationOrHash(url,window.our_variables.data);
                } else {
                    if(!url) url = location.hash;
                    if ('#'+url == location.hash){
                        $(window).triggerHandler("hashchange");
                    } else {
                        window.parent.location.href = '#'+url;
                    }
                }
            } else {
                simpleAjax.call({
                    url: url,
                    type: 'post',
                    data: window.our_variables.data,
                    beforeSend: function(){
                        form.addClass('blocked');
                    },
                    success: function(response){
                        form.removeClass('blocked');
                        if( response.result == 'ok' && callback ){
                            eval('directajax.' + callback + '( response.data , form );');
                        } else {

                        }
                    }
                });
            }

            event.preventDefault();
        }
    };

    callback.hash_change = function (event, data) {
        var url = location.hash
            ? location.hash.slice(1)
            : location.pathname + location.search;
        // Save transition trick for those browsers which do not support History API.
        // As far as url doesn't changes its value during ajax transits, and we change
        // only hash value. Some browsers like:
        // Android 2.1 & 3.0 - 4.1, Opera Mini, iOS Safari 3.2 - 4.3, IE 8.0 and 9.0
        // may do not change cookies (iw) in case of travelling via browsers buttons (back & forward)
        // on same domain. Thus we keep track hash change and keep smart cookie in its actual value.
        user.setCookie('iw', 0, new Date((new Date()).getTime()+60*60*24*365*1000), '/', window.location.host.substring(1));

        fsAjax.getPage(url, data);
    };

    callback.popstate = function (event) {


        var saved_state = event.originalEvent.state, undefined;

        if (!saved_state) return;

        if (saved_state === undefined || saved_state.content === undefined) {

            fsAjax.ChangeLocationOrHash(location.pathname+location.search);

        } else {

            GarbageCollector.kill();

            var state = user.updateLocalState( saved_state );
            user.renderPage();

        }
    };
    callback.page_scroll = function (event) {

        if ( !window.our_variables.scroll_handler_blocked ){

            window.static_nodes.$scrollUp.removeClass('up');
            window.static_nodes.$scrollUp.data('is_scrolled', true);

            if ( window.scrollY > 200 ){
                window.static_nodes.$scrollUp.css({'opacity': 1, 'visibility' : 'visible'});
            } else if ( !window.static_nodes.$scrollUp.hasClass('up') ) {
                window.static_nodes.$scrollUp.css({'opacity': 0, 'visibility' : 'hidden'});
            }

        }
    };
    callback.scroll_to_top = function (event , is_animate ) {

        is_animate = false;

        var $scrollUp   = window.static_nodes.$scrollUp;
            is_scrolled = $scrollUp.data('is_scrolled'),
            prevY       = is_scrolled ? window.scrollY : $scrollUp.data('prevY'),
            newY        = is_scrolled ? 0 : prevY;

        if ( newY === 0 ){
            $scrollUp.addClass('up');
            prevY = window.scrollY;
        } else {
            $scrollUp.removeClass('up');
            prevY = 0;
        }

        if ( is_animate ){
            $('html').animate({ scrollTop : newY  }, 300, function () {
                setTimeout(function() {
                    $scrollUp.data('is_scrolled', false).data('prevY', prevY );
                    // $scrollUp.addClass('up');
                }, 200);
            });
        } else {
            window.our_variables.scroll_handler_blocked = true;
            $(window).scrollTop( newY );
            setTimeout(function() {
                $scrollUp.data('is_scrolled', false).data('prevY', prevY );
                window.our_variables.scroll_handler_blocked = false;
            }, 100);
        }
    };

    callback.uploadpageFile = {

        firstButtonContent : '',

        beforeSend : function (){

            var $button     = $('#submit_file_button'),
                loadingText = $button.data('loadingText') || 'Loading';

            this.firstButtonContent = $button.html();

            setTimeout(function() {
                $button.html(loadingText).addClass('loading');
            }, 100);

        },

        success : function( html ){
            if ( html ) {

                $('.page_files').prepend(html);
                this.clearForm();

            } else {
                CLIENT.showException('Error loading image. Try again later');
            }
        },

        clearForm : function () {
            $('#pageFileUpload').val('');
            $('#pageFileTitle').val('');
            $('#pageFileUpload').next('.button_text').html( $('#pageFileUpload').next('.button_text').data('defaultText') );
            $('#submit_file_button').addClass('hide').removeClass('loading').html(this.firstButtonContent);
        }

    };

    callback.selectFile = function ( event ){

        var $errorBlock       = $('#pageFileError'),
            $errorBlock2      = $('#entityError'),
            $fileInput        = $(this),
            $submitButton     = $('#submit_file_button'),
            $buttonText       = $fileInput.next('.button_text'),
            $fileTitleInput   = $('#pageFileTitle'),
            $selectFileButton = $('#pageFileUpload').parent('.button');


        $errorBlock.addClass('hide');
        $errorBlock2.addClass('hide');

        var files = transport.getFileObject( $fileInput.get(0) );

        for (var i = files.length - 1; i >= 0; i--) {

            if ( transport.validateSize( files[i] , 30 * 1024 * 1024) ) {

                $buttonText.html( files[i]['name'] );
                $submitButton.removeClass('hide');

                var title = $fileTitleInput.val();

                if ( !title ){
                    $fileTitleInput.addClass('focused').focus();
                }

            } else {

                $errorBlock.toggleClass('hide');
                $selectFileButton.addClass('wobble'); setTimeout(function() {$selectFileButton.removeClass('wobble');}, 450);
                $fileInput.val('');

            }

            if ( i < files.length - 1 ) { break; };
        }

    };

    callback.savePageFile = function( $this ){

        callback.uploadpageFile.beforeSend();

        $('#submitPageFile').submit();

    };

    return callback;

} ({}, window, jQuery);

window.our_variables = {
    data                   : {},
    ajax_blocked           : false,
    scroll_handler_blocked : false,
    image_processing_turn  : 1,
    selected_item          : {},
    blur_blocked           : false,
    finance_hover_block    : false,
    blog_draft_id          : 0,
    scrollToComment        : 0,
    VKload                 : false,
    state                  : {
        url     : location.pathname,
        infListPortion : { url : null }
    }

};

$(document).ready(function(){

    window.static_nodes = {
        $window         : $(window),
        $document       : $(document),
        $main_content   : $('.page_wrap'),
        replyForm       : false,
        $scrollUp       : $('.scroll_up'),
        $transport_form : $("#transport_form"),
        $transport_input : $("#transportInput")
    };

    // window.our_variables.state.url     = location.pathname;
    // window.our_variables.state.content = window.static_nodes.$main_content.html();
    // window.our_variables.state.title   = document.title;
    // window.our_variables.state.sequential_number = history && history.state && history.state.sequential_number || 1;

    // user.updateHistoryRecord();

    // Bindings.
    // window.static_nodes.$document.on( "click" , "a" , callback.a_click );
    // window.static_nodes.$document.on( "submit" , "form" , callback.form_submit );
    // window.static_nodes.$document.on( "scroll" , callback.page_scroll );
    // window.static_nodes.$document.on( "click" , '.scroll_up' , callback.scroll_to_top );
    // window.static_nodes.$window.on( "resize" , callback.body_resize );

    // if ( ! user.isHistoryAPI ){

    //     window.static_nodes.$window.hashchange(callback.hash_change);
    //     jQuery.fn.hashchange.delay = 1;

    // } else {

    //     if (user.browser.CHROME || user.browser.CHROME_MOBILE) {
    //         // Work around Chrome bug, which trigger pop state on page load.
    //         setTimeout(function () {
    //             window.static_nodes.$window.bind('popstate', callback.popstate);
    //         }, 1500);
    //     } else {
    //         window.static_nodes.$window.bind('popstate', callback.popstate);
    //     }
    // }

    // if ( location.hash ) { fsAjax.ChangeLocationOrHash(document.location.hash.replace('#','')); }

    // user.loadClass({
    //     async    : true,
    //     url      : '//vk.com/js/api/openapi.js?92',
    //     callback : function(response){
    //         window.our_variables.VKload = true;
    //         VK.init({apiId: 3595265 , onlyWidgets: true});
    //     }
    // });



    fsAjax.pageEvents(true);


    // user.placeScrollUpBlock();




    /**************** social sharing ****************/

    // /***  facebook ***/
    // !(function(d, s, id) {
    //     var js, fjs = d.getElementsByTagName(s)[0];
    //         if (d.getElementById(id)) return;
    //         js = d.createElement(s); js.id = id;
    //         js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=235445193176758";
    //         fjs.parentNode.insertBefore(js, fjs);
    // }(document, 'script', 'facebook-jssdk'));


    // /***  twitter ***/
    // !(function() {
    //     var s = document.createElement('SCRIPT');
    //     var c = document.getElementsByTagName('script')[0];
    //     s.type = 'text/javascript';
    //     s.defer = "defer";
    //     s.async = true;
    //     s.src = 'http://platform.twitter.com/widgets.js';
    //     c.parentNode.insertBefore(s, c);
    // })();

    /************************************************/

});

/**
 * Parser code
 * @author Taly Guryn
 */



var parser = {

    input : null,

    init : function (settings){

         this.input = document.getElementById(settings.input_id);

         var _this = this;

         this.input.addEventListener('paste', function (event) {
         
             _this.inputPasteCallback()
         
         } , false)

    },

    inputPasteCallback : function () {

        var e = this.input;

        var _this = this;

        setTimeout(function(){

            _this.sendRequest(e.value);

        }, 100);
    },
    

    sendRequest : function (url) {

        simpleAjax.call({
        type: 'get',
        url: '/ajax/get_page',
        data: { 'url' : url },
        success: function(response){
            
            if ( response.success ) {
            
                var title = document.getElementById('page_form_title');
                title.value = response.title;

                var content = document.getElementById('page_form_content');
                content.value = response.article;

                // while we have no own editor, we should use this getting element
                // cause I can't edit code for external editor
                document.getElementsByClassName('redactor_redactor')[0].innerHTML   = response.article;

            } else {

                CLIENT.showException('Не удалось импортировать страницу');
                
            }
        }
    });
    }


};