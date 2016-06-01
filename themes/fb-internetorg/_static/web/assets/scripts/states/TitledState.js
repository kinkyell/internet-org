define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var BasicState = require('./BasicState');
    var apiService = require('services/apiService');
    var spread = require('stark/promise/spread');
    var tap = require('stark/promise/tap');

    var viewWindow = require('services/viewWindow');
    var templates = require('templates');

    var CarouselView = require('views/CarouselView');
    var EnglishContentDialogView = require('views/EnglishContentDialogView');
    var SelectView = require('views/SelectView');
    var ShowMoreView = require('views/ShowMoreView');
    var ImagePlaceholderView = require('views/ImagePlaceholderView');
    var VideoModalView = require('views/VideoModalView');
    var CustomRadioView = require('views/CustomRadioView');
    var $ = require('jquery');
    var Tween = require('gsap-tween');
    var LoadingContainer = require('util/LoadingContainer');

    var log = require('util/log');
    var setheader = "";

    /**
     * Manages the stack of active states
     *
     * @class TitledState
     * @param {Object} options State configuration options
     * @extends BasicState
     * @constructor
     */
    var TitledState = function(options) {

        this._handlePanelContentLoad = this._onPanelContentLoad.bind(this);
        this._handleStaticContent = this._onStaticContent.bind(this);
        this._handleLoaderInit = this._onLoaderInit.bind(this);


        BasicState.call(this, options);
        this._options.social = (this._options.social === 'true');
    };

    TitledState.prototype = Object.create(BasicState.prototype);
    TitledState.prototype.constructor = TitledState;

    /**
     * List of components to initialize
     * @property COMPONENTS
     * @static
     * @type {Object}
     */
    TitledState.prototype.COMPONENTS = {
        '.js-carouselView': CarouselView,
        '.js-englishContentDialog': EnglishContentDialogView,
        '.js-select': SelectView,
        '.js-ShowMoreView': ShowMoreView,
        '.js-videoModal': VideoModalView,
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
    TitledState.prototype.onActivate = function(event) {
        var transitions = this.getAnimationDirections(event);
        var tasks = [];
        if((this._options['header-img-color']!="") && (this._options['header-img-color']!="black") && (this._options['header-img-color']!="000000")) {
            $('.header-logo rect').css("fill", "#"+this._options['header-img-color']);
        } else {
            $('.header-logo rect').css("fill", "#000000");
        }
        if((this._options['header-color']!="") && (this._options['header-color']!="black") && (this._options['header-color']!="000000")) {
           $('.menuTrigger-label').css("color", "#"+this._options['header-color']);
           $('.menuTrigger-icon').css("border-color", "#"+this._options['header-color']);
           if( $( '#pseudo' ).length ) {
                $( '#pseudo' ).remove();
            }
            var css = '<style id="pseudo">.menuTrigger-icon::after, .menuTrigger-icon::before{background-color: #'+this._options['header-color']+' !important;}</style>';
            document.head.insertAdjacentHTML( 'beforeEnd', css );

        } else {
           $('.menuTrigger-label').css("color", "#000000");
           $('.menuTrigger-icon').css("border-color", "#000000");
           if( $( '#pseudo' ).length ) {
                $( '#pseudo' ).remove();
            }
        }

        if(this._options['story-page']=="full_screen") {
            $('.viewWindow').css({"right": "0"});
            $('.dialog-confirm-english').css({'width': '100%', 'left':'0', 'right':'0' });
        if(this._options.path) {
            var checkifStory = this._options.path;
            if(checkifStory.indexOf('blog') > -1) {

                tasks = [
                            apiService.getPanelContent(this._options.path),
                            viewWindow.replaceFeatureContentBlog(templates['page-title-panel'](this._options), transitions.feature),
                            viewWindow.replaceStoryContentBlog('', transitions.content).then(tap(this._handleLoaderInit)),
                            viewWindow.changetoFullScreen(this._options)
                        ];
            } else {
                if (event.silent) {
                    viewWindow.getCurrentStory().then(this._handleStaticContent, log);
                    return;
                }

                tasks = [
                            apiService.getPanelContent(this._options.path),
                            viewWindow.replaceFeatureContent(templates['page-title-panel'](this._options), transitions.feature),
                            viewWindow.replaceStoryContent('', transitions.content).then(tap(this._handleLoaderInit)),
                            viewWindow.changetoOriginal()
                        ];
            }
        } else {
            if (event.silent) {
                viewWindow.getCurrentStory().then(this._handleStaticContent, log);
                return;
            }

            tasks = [
                            apiService.getPanelContent(this._options.path),
                            viewWindow.replaceFeatureContent(templates['page-title-panel'](this._options), transitions.feature),
                            viewWindow.replaceStoryContent('', transitions.content).then(tap(this._handleLoaderInit)),
                            viewWindow.changetoOriginal()
                        ];
        }
    } else {
        $('.viewWindow').css({"right": ""});
         $('.dialog-confirm-english').css({'width': '', 'left':'', 'right':'' });
         if (event.silent) {
                viewWindow.getCurrentStory().then(this._handleStaticContent, log);
                return;
            }

            tasks = [
                            apiService.getPanelContent(this._options.path),
                            viewWindow.replaceFeatureContent(templates['page-title-panel'](this._options), transitions.feature),
                            viewWindow.replaceStoryContent('', transitions.content).then(tap(this._handleLoaderInit)),
                            viewWindow.changetoOriginal()
                        ];
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
    TitledState.prototype._onPanelContentLoad = function(markup, $feature, $panel) {
        if (!this.active) {
            return;
        }

        var $markup = $(markup);
        $panel.append($markup);
        Tween.from($markup[0], 0.25, { opacity: 0 });

        this.refreshComponents($panel);

        // remove loader
        this.loader.removeThrobber();
        this.loader = null;

        FB.XFBML.parse( $markup[0] ); // jshint ignore:line

    };

    /**
     * Handle static content when loaded
     *
     * @method _onStaticContent
     * @param {jQuery} $panel Panel that wraps static content
     * @private
     */
    TitledState.prototype._onStaticContent = function($panel) {
        if (!this.active) {
            return;
        }
        this.refreshComponents($panel);
    };

    /**
     * Initialize loader
     *
     * @method _onLoaderInit
     * @param {jQuery} $panel Panel that wraps static content
     * @private
     */
    TitledState.prototype._onLoaderInit = function($panel) {
        this.loader = new LoadingContainer($panel[0]);
        this.loader.addThrobber();
    };

    return TitledState;

});
