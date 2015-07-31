define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var BasicState = require('./BasicState');
    var HomeState = require('./HomeState');
    var apiService = require('services/apiService');
    var spread = require('stark/promise/spread');

    var viewWindow = require('services/viewWindow');
    var templates = require('templates');

    /**
     * Manages the stack of active states
     *
     * @class PanelState
     * @extends BasicState
     * @constructor
     */
    var PanelState = function(options) {
        this._handlePanelContentLoad = this._onPanelContentLoad.bind(this);
        this._handlePanelContentError = this._onPanelContentError.bind(this);

        BasicState.call(this, options);
    };

    PanelState.prototype = Object.create(BasicState.prototype);
    PanelState.prototype.constructor = PanelState;

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

        var tasks = [
            apiService.getPanelContent(this._options.path),
            viewWindow.replaceStoryContent(templates['article-header']({
                title: this._options.title,
                description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse es suscipit euante lorepehicula nulla, suscipit dela eu ante vel vehicula.'
            }), event.method === 'push' && fromHome ? 'none' : transition)
        ];

        if (this._options.image) {
            tasks.push(viewWindow.replaceFeatureImage(this._options.image, transition));
        }

        Promise.all(tasks).then(spread(this._handlePanelContentLoad), this._handlePanelContentError);

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
    };

    /**
     * Append error message when content fails to load
     *
     * @method _onPanelContentError
     * @param {Object} error Ajax error object
     * @private
     */
    PanelState.prototype._onPanelContentError = function(error) {
        if (!this.active) {
            return;
        }
        console.log(error);
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
