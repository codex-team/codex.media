/**
 * Image plugin for codex-editor
 * @author CodeX Team <team@ifmo.su>
 *
 * @version 1.2.0
 */
var image = (function(image) {

    /**
     * @private
     *
     * CSS classNames
     */
    var elementClasses_ = {

        ce_image      : 'ce-image',
        loading       : 'ce-plugin-image__loader',
        blockStretched: 'ce-block--stretched',
        uploadedImage : {
            centered  : 'ce-plugin-image__uploaded--centered',
            stretched : 'ce-plugin-image__uploaded--stretched'
        },
        imageCaption  : 'ce-plugin-image__caption',
        imageWrapper  : 'ce-plugin-image__wrapper',
        formHolder    : 'ce-plugin-image__holder',
        uploadButton  : 'ce-plugin-image__button',
        imagePreview  : 'ce-image__preview',
        selectorHolder: 'ce-settings-checkbox',
        selectorButton: 'ce-settings-checkbox__toggler',
        settingsItem: 'ce-image-settings__item',
        imageWrapperBordered : 'ce-image__wrapper--bordered',
        toggled : 'ce-image-settings__item--toggled'

    };

    /**
     *
     * @private
     *
     * UI methods
     */
    var ui_ = {

        holder : function(){

            var element = document.createElement('DIV');

            element.classList.add(elementClasses_.formHolder);
            element.classList.add(elementClasses_.ce_image);

            return element;
        },

        uploadButton : function(){

            var button = document.createElement('SPAN');

            button.classList.add(elementClasses_.uploadButton);

            button.innerHTML = '<i class="ce-icon-picture"> </i>';
            button.innerHTML += 'Загрузить фотографию';

            return button;

        },

        /**
         * @param {object} file - file path
         * @param {string} style - css class
         * @return {object} image - document IMG tag
         */
        image : function(file, styles) {

            var image = document.createElement('IMG');

            styles.map(function(item) {
                image.classList.add(item);
            });

            image.src = file.url;
            image.dataset.bigUrl = file.bigUrl;

            return image;
        },

        wrapper : function() {

            var div = document.createElement('div');

            div.classList.add(elementClasses_.imageWrapper);

            return div;
        },

        caption : function() {

            var div  = document.createElement('div');

            div.classList.add(elementClasses_.imageCaption);
            div.contentEditable = true;

            return div;
        },
        /**
         * Draws form for image upload
         */
        makeForm : function() {

            var holder       = ui_.holder(),
                uploadButton = ui_.uploadButton();

            holder.appendChild(uploadButton);

            uploadButton.addEventListener('click', uploadButtonClicked_, false );

            image.holder = holder;

            return holder;
        },


        /**
         * wraps image and caption
         * @param {object} data - image information
         * @param {string} imageTypeClass - plugin's style
         * @param {boolean} stretched - stretched or not
         * @return wrapped block with image and caption
         */
        makeImage : function(data, imageTypeClasses, stretched, bordered) {

            var file = data.file,
                text = data.caption,
                type     = data.type,
                image    = ui_.image(file, imageTypeClasses),
                caption  = ui_.caption(),
                wrapper  = ui_.wrapper();

            caption.innerHTML = text || '';

            wrapper.dataset.stretched = stretched;
            wrapper.dataset.bordered = bordered;

            /** Appeding to the wrapper */
            wrapper.appendChild(image);
            wrapper.appendChild(caption);

            return wrapper;
        },

        /**
         * @param {HTML} data - Rendered block with image
         */
        getImage : function(data) {

            var image = data.querySelector('.' + elementClasses_.uploadedImage.centered) ||
                data.querySelector('.' + elementClasses_.uploadedImage.stretched);

            return image;
        },

        /**
         * wraps image and caption
         * @deprecated
         * @param {object} data - image information
         * @return wrapped block with image and caption
         */
        centeredImage : function(data) {

            var file = data.file,
                text = data.caption,
                type     = data.type,
                image    = ui_.image(file, elementClasses_.uploadedImage.centered),
                caption  = ui_.caption(),
                wrapper  = ui_.wrapper();

            caption.textContent = text;

            wrapper.dataset.stretched = 'false';

            /** Appeding to the wrapper */
            wrapper.appendChild(image);
            wrapper.appendChild(caption);

            return wrapper;
        },

        /**
         * wraps image and caption
         * @deprecated
         * @param {object} data - image information
         * @return stretched image
         */
        stretchedImage : function(data) {

            var file = data.file,
                text = data.caption,
                type     = data.type,
                image    = ui_.image(file, elementClasses_.uploadedImage.stretched),
                caption  = ui_.caption(),
                wrapper  = ui_.wrapper();

            caption.textContent = text;

            wrapper.dataset.stretched = 'true';

            /** Appeding to the wrapper */
            wrapper.appendChild(image);
            wrapper.appendChild(caption);

            return wrapper;

        }

    };

    /**
     * @private
     *
     * After render callback
     */
    var uploadButtonClicked_ = function(event) {

        var beforeSend = uploadingCallbacks_.ByClick.beforeSend,
            success    = uploadingCallbacks_.ByClick.success,
            error      = uploadingCallbacks_.ByClick.error;

        /** Define callbacks */
        codex.editor.transport.selectAndUpload({
            beforeSend: beforeSend,
            success: success,
            error: error
        });
    };

    var methods_ = {

        addSelectTypeClickListener : function(el, type) {

            el.addEventListener('click', function() {

                // el - settings element

                switch (type) {
                    case 'bordered':
                        methods_.toggleBordered(type, this); break;
                    case 'stretched':
                        methods_.toggleStretched(type, this); break;
                }


            }, false);

        },

        toggleBordered : function(type, clickedSettingsItem ) {

            var current = codex.editor.content.currentNode,
                blockContent = current.childNodes[0],
                img = ui_.getImage(current),
                wrapper = current.querySelector('.' + elementClasses_.imageWrapper);

            if (!img) {
                return;
            }

            /**
             * Add classes to the IMG tag and to the Settings element
             */
            img.classList.toggle(elementClasses_.imageWrapperBordered);
            clickedSettingsItem.classList.toggle(elementClasses_.toggled);

            /**
             * Save settings in dataset
             */
            if (img.classList.contains(elementClasses_.imageWrapperBordered)) {
                wrapper.dataset.bordered = true;
            } else {
                wrapper.dataset.bordered = false;
            }

            setTimeout(function() {
                codex.editor.toolbar.settings.close();
            }, 200);

        },

        toggleStretched : function( type, clickedSettingsItem ) {

            var current = codex.editor.content.currentNode,
                blockContent = current.childNodes[0],
                img = ui_.getImage(current),
                wrapper = current.querySelector('.' + elementClasses_.imageWrapper);

            if (!img) {
                return;
            }

            /** Clear classList */
            blockContent.classList.add(elementClasses_.blockStretched);
            img.classList.toggle(elementClasses_.uploadedImage.stretched);
            img.classList.toggle(elementClasses_.uploadedImage.centered);

            clickedSettingsItem.classList.toggle(elementClasses_.toggled);

            if (img.classList.contains(elementClasses_.uploadedImage.stretched)) {

                wrapper.dataset.stretched = true;

            } else {

                wrapper.dataset.stretched = false;

            }

            setTimeout(function() {
                codex.editor.toolbar.settings.close();
            }, 1000);

        }
    };

    /**
     * @private
     * Callbacks
     */
    var uploadingCallbacks_ = {

        ByClick : {

            /**
             * Before sending ajax request
             */
            beforeSend : function() {

                var input = codex.editor.transport.input,
                    files = input.files;

                var validFileExtensions = ["jpg", "jpeg", "bmp", "gif", "png"];

                var type = files[0].type.split('/');

                var result = validFileExtensions.some(function(ext) {
                    return ext == type[1];
                });

                if (!result) {
                    return;
                }

                var reader = new FileReader();
                reader.readAsDataURL(files[0]);

                reader.onload = function(e) {

                    var data = {
                        background : false,
                        border   : false,
                        isstretch : false,
                        file : {
                            url    : e.target.result,
                            bigUrl : null,
                            width  : null,
                            height : null,
                            additionalData : null
                        },
                        caption : '',
                        cover : null
                    };

                    var newImage = make_(data);

                    codex.editor.content.switchBlock(image.holder, newImage, 'image_extended');
                    newImage.classList.add(elementClasses_.imagePreview);

                    /**
                     * Change holder to image
                     */
                    image.holder = newImage;
                };

            },

            /** Photo was uploaded successfully */
            success : function(result) {

                var parsed = JSON.parse(result),
                    data,
                    currentBlock = codex.editor.content.currentNode;

                /**
                 * Preparing {Object} data to draw an image
                 * @uses ceImage.make method
                 */
                data = parsed.data;

                image.holder.classList.remove(elementClasses_.imagePreview);

                /**
                 * Change src of image
                 */
                var newImage = image.holder.getElementsByTagName('IMG')[0];

                newImage.src            = parsed.data.file.url;
                newImage.dataset.bigUrl = parsed.data.file.bigUrl;
                newImage.dataset.width  = parsed.data.file.width;
                newImage.dataset.height = parsed.data.file.height;
                newImage.dataset.additionalData = parsed.data.file.additionalData;

            },

            /** Error callback. Sends notification to user that something happend or plugin doesn't supports method */
            error : function(result) {

                var oldHolder = image.holder;
                var form = ui_.makeForm();

                codex.editor.content.switchBlock(oldHolder, form, 'image_extended');

            }
        },

        ByPaste : {

            /**
             * Direct upload
             * Any URL that contains image extension
             * @param url
             */
            uploadImageFromUrl : function(path) {

                var ajaxUrl = image.config.uploadUrl,
                    file,
                    image_plugin,
                    current = codex.editor.content.currentNode,
                    beforeSend,
                    success_callback;

                /** When image is uploaded to redactors folder */
                success_callback = function(data) {

                    var imageInfo = JSON.parse(data);

                    var newImage = image_plugin.getElementsByTagName('IMG')[0];

                    newImage.dataset.stretched = false;
                    newImage.dataset.src = imageInfo.file.url;
                    newImage.dataset.bigUrl = imageInfo.file.bigUrl;
                    newImage.dataset.width = imageInfo.file.width;
                    newImage.dataset.height = imageInfo.file.height;
                    newImage.dataset.additionalData = imageInfo.file.additionalData;

                    image_plugin.classList.remove(elementClasses_.imagePreview);

                };

                /** Before sending XMLHTTP request */
                beforeSend = function() {

                    var content = current.querySelector('.ce-block__content');

                    var data = {
                        background: false,
                        border: false,
                        isStretch: false,
                        file: {
                            url: path,
                            bigUrl: null,
                            width: null,
                            height: null,
                            additionalData: null
                        },
                        caption: '',
                        cover: null
                    };

                    image_plugin = codex.editor.tools.image_extended.render(data);

                    image_plugin.classList.add(elementClasses_.imagePreview);

                    var img = image_plugin.querySelector('img');

                    codex.editor.content.switchBlock(codex.editor.content.currentNode, image_plugin, 'image_extended');

                };

                /** Preparing data for XMLHTTP */
                var data = {
                    url: image.config.uploadUrl,
                    type: "POST",
                    data : {
                        url: path
                    },
                    beforeSend : beforeSend,
                    success : success_callback
                };

                codex.editor.core.ajax(data);
            }

        }
    };

    /**
     * Image path
     * @type {null}
     */
    image.path   = null;

	/**
     * Plugin configuration
     */
    image.config = null;

    /**
     *
     * @private
     *
     * @param data
     * @return {*}
     *
     */
    var make_ = function ( data ) {

        var holder,
            classes = [];

        if (data) {

            if (data.border) {
                classes.push(elementClasses_.imageWrapperBordered);
            }

            if ( data.isstretch || data.isstretch === 'true') {

                classes.push(elementClasses_.uploadedImage.stretched);
                holder = ui_.makeImage(data, classes, 'true', data.border);

            } else {

                classes.push(elementClasses_.uploadedImage.centered);
                holder = ui_.makeImage(data, classes, 'false', data.border);

            }

            return holder;

        } else {

            holder = ui_.makeForm();

            return holder;
        }
    };

    /**
     * @private
     *
     * Prepare clear data before save
     *
     * @param data
     */
    var prepareDataForSave_ = function(data) {

    };

    /**
     * @public
     * @param config
     */
    image.prepare = function(config) {

        image.config = config;

        return Promise.resolve();
    };

    /**
     * @public
     *
     * this tool works when tool is clicked in toolbox
     */
    image.appendCallback = function(event) {

        /** Upload image and call success callback*/
        uploadButtonClicked_(event);

    };

    /**
     * @public
     *
     * @param data
     * @return {*}
     */
    image.render = function( data ) {

        return make_(data);
    };

    /**
     * @public
     *
     * @param block
     * @return {{background: boolean, border: boolean, isstretch: boolean, file: {url: (*|string|Object), bigUrl: (null|*), width: *, height: *, additionalData: null}, caption: (string|*|string), cover: null}}
     */
    image.save = function ( block ) {

        var content    = block,
            image   = ui_.getImage(content),
            caption = content.querySelector('.' + elementClasses_.imageCaption);

        var data = {
            background : false,
            border : content.dataset.bordered === 'true' ? true : false,
            isstretch : content.dataset.stretched === 'true' ? true : false,
            file : {
                url : image.dataset.src || image.src,
                bigUrl : image.dataset.bigUrl,
                width  : image.width,
                height : image.height,
                additionalData :null
            },
            caption : caption.innerHTML || '',
            cover : null
        };

        return data;
    };

    /**
     * @public
     *
     * Settings panel content
     * @return {Element} element contains all settings
     */
    image.makeSettings = function () {

        var currentNode = codex.editor.content.currentNode,
            wrapper = currentNode.querySelector('.' + elementClasses_.imageWrapper),
            holder  = document.createElement('DIV'),
            types   = {
                stretched : "На всю ширину",
                bordered  : "Добавить рамку"
            },
            currentImageWrapper  = currentNode.querySelector('.' + elementClasses_.imageWrapper ),
            currentImageSettings = currentImageWrapper.dataset;

        /** Add holder classname */
        holder.className = 'ce-image-settings';

        /** Now add type selectors */
        for (var type in types){

            /**
             * Settings template
             */
            var settingsItem = document.createElement('DIV'),
                selectorsHolder = document.createElement('SPAN'),
                selectorsButton = document.createElement('SPAN');

            settingsItem.classList.add(elementClasses_.settingsItem);
            selectorsHolder.classList.add(elementClasses_.selectorHolder);
            selectorsButton.classList.add(elementClasses_.selectorButton);

            selectorsHolder.appendChild(selectorsButton);
            settingsItem.appendChild(selectorsHolder);

            selectTypeButton = document.createTextNode(types[type]);
            settingsItem.appendChild(selectTypeButton);

            /**
             * Activate previously selected settings
             */
            if ( currentImageSettings[type] == 'true' ){
                settingsItem.classList.add(elementClasses_.toggled);
            }

            methods_.addSelectTypeClickListener(settingsItem, type);

            holder.appendChild(settingsItem);

        }

        return holder;

    };

    /**
     * Share as API
     */
    image.uploadImageFromUri = uploadingCallbacks_.ByPaste.uploadImageFromUrl;

    return image;

})({});