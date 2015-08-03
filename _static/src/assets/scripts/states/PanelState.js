define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var BasicState = require('./BasicState');
    var HomeState = require('./HomeState');
    var apiService = require('services/apiService');
    var spread = require('stark/promise/spread');

    var viewWindow = require('services/viewWindow');
    var templates = require('templates');

    var CarouselView = require('views/CarouselAltView');

    var log = console.log.bind(console);
    /**
     * Manages the stack of active states
     *
     * @class PanelState
     * @extends BasicState
     * @constructor
     */
    var PanelState = function(options) {
        this._handlePanelContentLoad = this._onPanelContentLoad.bind(this);

        BasicState.call(this, options);
    };

    PanelState.prototype = Object.create(BasicState.prototype);
    PanelState.prototype.constructor = PanelState;

    PanelState.prototype.COMPONENTS = {
        '.js-carouselView': CarouselView
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

        if (event.method === 'pop') {
            transition = 'left';
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
            }), event.method === 'push' && fromHome ? 'none' : transition)
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
        $panel.append(markup);
        this.refreshComponents($panel);
    };

    /**
     * Deactivate the panel
     *
     * @method deactivate
     * @fires State:deactivate
     */
    PanelState.prototype.deactivate = function(event) {
        BasicState.prototype.deactivate.call(this, event);
    };

    return PanelState;

});
