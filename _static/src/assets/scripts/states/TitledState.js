define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var BasicState = require('./BasicState');
    var apiService = require('services/apiService');
    var spread = require('stark/promise/spread');

    var viewWindow = require('services/viewWindow');
    var templates = require('templates');

    var CarouselView = require('views/CarouselView');
    var SelectView = require('views/SelectView');
    var ShowMoreView = require('views/ShowMoreView');
    var ImagePlaceholderView = require('views/ImagePlaceholderView');
    var VideoModalView = require('views/VideoModalView');
    var $ = require('jquery');
    var Tween = require('gsap-tween');

    var log = require('util/log');

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

        BasicState.call(this, options);
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
        '.js-select': SelectView,
        '.js-ShowMoreView': ShowMoreView,
        '.js-videoModal': VideoModalView,
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

        if (event.silent) {
            viewWindow.getCurrentStory().then(this._handleStaticContent, log);
            return;
        }

        var tasks = [
            apiService.getPanelContent(this._options.path),
            viewWindow.replaceFeatureContent(templates['page-title-panel'](this._options), transitions.feature),
            viewWindow.replaceStoryContent('', transitions.content)
        ];

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

    return TitledState;

});
