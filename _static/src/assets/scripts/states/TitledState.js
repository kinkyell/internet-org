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
    var $ = require('jquery');
    var Tween = require('gsap-tween');

    var log = console.log.bind(console);

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
        '.js-ShowMoreView': ShowMoreView
    };

    /**
     * Activate state
     *  - request panel content from server
     *  - create panel markup
     *
     * @method activate
     * @fires State:activate
     */
    TitledState.prototype.activate = function(event) {
        var transitions = this.getAnimationDirections(event);

        if (event.silent) {
            viewWindow.getCurrentStory().then(this._handleStaticContent, log);
            return BasicState.prototype.activate.call(this, event);
        }

        var tasks = [
            apiService.getPanelContent(this._options.path),
            viewWindow.replaceFeatureContent(templates['page-title-panel']({
                title: this._options.title,
                theme: this._options.theme,
                desc: this._options.desc,
                date: this._options.date
            }), transitions.feature),
            viewWindow.replaceStoryContent('', transitions.content)
        ];

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
