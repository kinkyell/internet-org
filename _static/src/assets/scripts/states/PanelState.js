define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var BasicState = require('./BasicState');
    var HomeState = require('./HomeState');
    var apiService = require('services/apiService');
    var spread = require('stark/promise/spread');

    var viewWindow = require('services/viewWindow');
    var templates = require('templates');

    var CarouselView = require('views/CarouselView');
    var SelectView = require('views/SelectView');
    var $ = require('jquery');
    var Tween = require('gsap-tween');

    var log = console.log.bind(console);

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
        this.invertLeft = true;
        this._lastScrollTop = 0;
        this.refreshScrollerInfo = this._refreshScrollerInfo.bind(this);

        BasicState.call(this, options);
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
        '.js-select': SelectView
    };

    /**
     * Activate state
     *  - request panel content from server
     *  - create panel markup
     *
     * @method activate
     * @fires State:activate
     */
    PanelState.prototype.activate = function(event) {
        var transition = 'right';
        var stateLen = event.states.length;
        var fromHome = stateLen > 1 && (event.states[stateLen - 2] instanceof HomeState);

        if (event.silent) {
            viewWindow.getCurrentStory().then(this._handleStaticContent);
            return BasicState.prototype.activate.call(this, event);
        }

        if (event.method === 'pop') {
            transition = fromHome ? 'right' : 'left';
        }

        if (event.method === 'swap') {
            transition = 'bottom';
        }

        var tasks = [
            apiService.getPanelContent(this._options.path),
            viewWindow.replaceStoryContent(templates['article-header']({
                title: this._options.title,
                description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse es suscipit euante lorepehicula nulla, suscipit dela eu ante vel vehicula.', //jshint ignore:line
                theme: this._options.theme
            }), fromHome ? 'none' : transition)
        ];

        if (this._options.image) {
            tasks.push(viewWindow.replaceFeatureImage(this._options.image, transition));
        }

        Promise.all(tasks)
            .then(spread(this._handlePanelContentLoad))
            .catch(log);

        BasicState.prototype.activate.call(this, event);
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
        var $markup = $(markup);
        $panel.append($markup);
        Tween.from($markup[0], 0.25, { opacity: 0 });
        this.refreshComponents($panel);
        this._initializeScrollWatcher($panel);
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
     * Deactivate the panel
     *
     * @method deactivate
     * @fires State:deactivate
     */
    PanelState.prototype.deactivate = function(event) {
        this._destroyScrollWatcher();
        BasicState.prototype.deactivate.call(this, event);
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

        viewWindow.replaceFeatureImage(image, direction);
    };

    /**
     * Refresh info about scroll positions and cues
     *
     * @method _refreshScrollerInfo
     * @private
     */
    PanelState.prototype._refreshScrollerInfo = function() {
        var OFFSET = 0.25;

        // add child elements
        this._scrollBlocks = this._panelImages.map(function(idx, el) {
            var url = el.getAttribute('data-image');
            var fromTop = $(el).offset().top;
            var height = el.offsetHeight;

            return {
                img: url,
                top: fromTop + (OFFSET * height),
                bottom: fromTop + height + (OFFSET * height)
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
