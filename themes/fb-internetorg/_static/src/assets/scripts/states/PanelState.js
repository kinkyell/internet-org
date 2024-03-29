define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var BasicState = require('./BasicState');
    var apiService = require('services/apiService');
    var spread = require('stark/promise/spread');
    var capitalize = require('stark/string/capitalize');
    var tap = require('stark/promise/tap');

    var viewWindow = require('services/viewWindow');
    var templates = require('templates');

    var CarouselView = require('views/CarouselView');
    var SelectView = require('views/SelectView');
    var CustomRadioView = require('views/CustomRadioView');
    var ImagePlaceholderView = require('views/ImagePlaceholderView');
    var SearchFormView = require('views/SearchFormView');
    var VideoModalView = require('views/VideoModalView');
    var $ = require('jquery');
    var Tween = require('gsap-tween');
    var LoadingContainer = require('util/LoadingContainer');

    var log = require('util/log');

    /**
     * Manages the stack of active states
     *
     * @class PanelState
     * @param {Object} options State configuration options
     * @extends BasicState
     * @constructor
     */
    var PanelState = function(options) {
        this._handlePanelContentLoad = this._onPanelContentLoad.bind(this);
        this._handleStaticContent = this._onStaticContent.bind(this);
        this._handlePanelScroll = this._onPanelScroll.bind(this);
        this._handleLoaderInit = this._onLoaderInit.bind(this);
        this.refreshScrollerInfo = this._refreshScrollerInfo.bind(this);
        this.currentFeatureImage = '';


        // Get feature image
        var $featureImage = $('#featurePanel').find('.viewWindow-panel-content-inner').first();
        if( $featureImage.length >= 1 ) {
            this.currentFeatureImage = $featureImage
                .css('background-image')
                .replace(/^url|[\(\)]/g, '')
                .replace(/"/g, '') || '';
        }

        /**
         * The last saved scroll top value
         *
         * @default 0
         * @property _lastScrollTop
         * @type {Number}
         * @private
         */
        this._lastScrollTop = 0;

        BasicState.call(this, options);
        this.invertLeft = true;
    };

    PanelState.prototype = Object.create(BasicState.prototype);
    PanelState.prototype.constructor = PanelState;

    /**
     * List of components to initialize
     * @property COMPONENTS
     * @static
     * @type {Object}
     */
    PanelState.prototype.COMPONENTS = {
        '.js-carouselView': CarouselView,
        '.js-select': SelectView,
        '.js-videoModal': VideoModalView,
        '.js-searchFormView': SearchFormView,
        'input.radio': CustomRadioView,
        'img': ImagePlaceholderView
    };

    /**
     * Activate state
     *  - request panel content from server
     *  - create panel markup
     *
     * @method onActivate
     * @fires State:activate
     */
    PanelState.prototype.onActivate = function(event) {
        var transitions = this.getAnimationDirections(event);
        var theme = this._options.theme;
        var mobileImage = this._options['mobile-image'];
        var tasks = [];
        if(this._options['story-page']=="full_screen") {
            if(this._options.path) {
                var checkifStory = this._options.path;
                if(checkifStory.indexOf('blog') > -1) {

                    tasks = [
                                apiService.getPanelContent(this._options.path),
                                viewWindow.replaceStoryContent(templates['article-header']({
                                    title: this._options.title,
                                    desc: this._options.desc,
                                    image: mobileImage ? mobileImage : this._options.image,
                                    theme: (typeof theme === 'string' && theme.length) ? capitalize(theme) : theme
                                }), transitions.content).then(tap(this._handleLoaderInit)),
                                viewWindow.changetoFullScreen(this._options)
                            ];
                } else {
                    if (event.silent) {
                viewWindow.getCurrentStory().then(this._handleStaticContent);
                return;
            }
                    tasks = [
                                apiService.getPanelContent(this._options.path),
                                viewWindow.replaceStoryContent(templates['article-header']({
                                    title: this._options.title,
                                    desc: this._options.desc,
                                    image: mobileImage ? mobileImage : this._options.image,
                                    theme: (typeof theme === 'string' && theme.length) ? capitalize(theme) : theme
                                }), transitions.content).then(tap(this._handleLoaderInit)),
                                viewWindow.changetoOriginal()
                            ];
                }
            } else {
                if (event.silent) {
                    viewWindow.getCurrentStory().then(this._handleStaticContent);
                    return;
                }
                tasks = [
                            apiService.getPanelContent(this._options.path),
                            viewWindow.replaceStoryContent(templates['article-header']({
                                title: this._options.title,
                                desc: this._options.desc,
                                image: mobileImage ? mobileImage : this._options.image,
                                theme: (typeof theme === 'string' && theme.length) ? capitalize(theme) : theme
                            }), transitions.content).then(tap(this._handleLoaderInit)),
                            viewWindow.changetoOriginal()
                        ];
            }
        } else {

            if (event.silent) {
                viewWindow.getCurrentStory().then(this._handleStaticContent);
                return;
            }
            tasks = [
                        apiService.getPanelContent(this._options.path),
                        viewWindow.replaceStoryContent(templates['article-header']({
                            title: this._options.title,
                            desc: this._options.desc,
                            image: mobileImage ? mobileImage : this._options.image,
                            theme: (typeof theme === 'string' && theme.length) ? capitalize(theme) : theme
                        }), transitions.content).then(tap(this._handleLoaderInit)),
                        viewWindow.changetoOriginal()
                    ];
        }


        if (this._options.image) {
            tasks.push(viewWindow.replaceFeatureImage(this._options.image, transitions.feature));
        }

        Promise.all(tasks)
            .then(spread(this._handlePanelContentLoad))
            .catch(log);
    };

    /**
     * Append markup to panel when loaded
     *
     * @method _onPanelContentLoad
     * @param {String} markup HTML content from ajax request
     * @private
     */
    PanelState.prototype._onPanelContentLoad = function(markup, $panel) {
        if (!this.active) {
            return;
        }

        var $markup = $(markup).children();

        // remove any duplicate introblocks
        $markup.children('.introBlock').eq(0).remove();
        $markup.find('.js-introImage').remove();

        $panel.append($markup);
        Tween.from($markup[0], 0.25, { opacity: 0 });
        this.refreshComponents($panel);
        this._initializeScrollWatcher($panel);

        // remove loader
        if (this.loader) {
            this.loader.removeThrobber();
            this.loader = null;
        }

    };

    /**
     * Handle static content when loaded
     *
     * @method _onStaticContent
     * @param {jQuery} $panel Panel that wraps static content
     * @private
     */
    PanelState.prototype._onStaticContent = function($panel) {
        if (!this.active) {
            return;
        }
        this.refreshComponents($panel);
        this._initializeScrollWatcher($panel);
    };

    /**
     * Initialize loader
     *
     * @method _onLoaderInit
     * @param {jQuery} $panel Panel that wraps static content
     * @private
     */
    PanelState.prototype._onLoaderInit = function($panel) {
        this.loader = new LoadingContainer($panel[0]);
        this.loader.addThrobber();
    };

    /**
     * Deactivate the panel
     *
     * @method onDeactivate
     * @fires State:deactivate
     */
    PanelState.prototype.onDeactivate = function(event) {
        this._destroyScrollWatcher();
    };

    /**
     * Look for images to scroll
     *
     * @method _initializeScrollWatcher
     * @param {jQuery} $panel Panel to listen for scrolling upon
     * @private
     */
    PanelState.prototype._initializeScrollWatcher = function($panel) {
        this._scrollPanel = $panel.parent();
        this._panelImages = $panel.find('.js-scrollImage');

        this.refreshScrollerInfo();

        this._scrollPanel
            .on('scroll', this._handlePanelScroll)
            .find('img')
            .on('load', this.refreshScrollerInfo);
    };

    /**
     * Destroy event listeners for scroll watching
     *
     * @method _destroyScrollWatcher
     * @private
     */
    PanelState.prototype._destroyScrollWatcher = function() {
        if (!this._scrollPanel) {
            return;
        }
        this._scrollPanel
            .off('scroll', this._handlePanelScroll)
            .find('img')
            .off('load', this.refreshScrollerInfo);
        this._scrollImages = null;
    };

    /**
     * Update scrolled image based on position
     *
     * @method _onPanelScroll
     * @private
     */
    PanelState.prototype._onPanelScroll = function() {
        var panel = this;
        var scrollTop = this._scrollPanel[0].scrollTop;
        var view = scrollTop + this._windowHeight;
        var image = null;
        var direction = scrollTop > this._lastScrollTop ? 'bottom' : 'top';

        this._lastScrollTop = scrollTop;
        this._scrollBlocks.forEach(function(block) {
            if (view >= block.top && view <= block.bottom) {
                image = block.img;
            }
        });

        if ( panel.currentFeatureImage !== image ) {
            panel.currentFeatureImage = image;
            viewWindow.replaceFeatureImage(image, direction);
        }
    };

    /**
     * Refresh info about scroll positions and cues
     *
     * @method _refreshScrollerInfo
     * @private
     */
    PanelState.prototype._refreshScrollerInfo = function() {
        var OFFSET = 0.25;
        var parentHeight = this._scrollPanel.children()[0].offsetHeight;

        // add child elements
        this._scrollBlocks = this._panelImages.map(function(idx, el) {
            var url = el.getAttribute('data-image');
            var fromTop = $(el).offset().top;
            var height = el.offsetHeight;
            var bottomVal = fromTop + height + (OFFSET * height);

            return {
                img: url,
                top: fromTop + (OFFSET * height),
                bottom: bottomVal > parentHeight ? Infinity : bottomVal
            };
        }).toArray();

        // add default image
        this._scrollBlocks.unshift({
            img: this._options.image,
            top: 0,
            bottom: Infinity
        });

        this._windowHeight = $(window).height();
    };

    return PanelState;

});
