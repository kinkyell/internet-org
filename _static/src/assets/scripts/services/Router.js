define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var $ = require('jquery');
    var Modernizr = require('modernizr');
    var eventHub = require('services/eventHub');

    var UrlHistoryManager = require('util/history/UrlHistoryManager');
    var FallbackHistoryManager = require('util/history/FallbackHistoryManager');

    var ROUTER_LINK_SELECTOR = '.js-stateLink';

    /**
     * Manages the stack of active states
     *
     * @class Router
     * @constructor
     */
    var Router = function() {
        this._init();
    };

    /**
     * Activate a state on top of the state
     *
     * @method _init
     * @private
     */
    Router.prototype._init = function() {
        this._handlePopState = this._onPopState.bind(this);
        this._handleStateTrigger = this._onStateTrigger.bind(this);

        /**
         * Current list of state data
         *
         * @property _currentStates
         * @type Array
         * @default []
         * @private
         */
        this._currentStates = [];

        /**
         * History management abstraction to manipulate browser and url bar
         *
         * @property historyManager
         * @type AbstractHistoryManager
         */
        this.historyManager = Modernizr.history ?
            new UrlHistoryManager() :
            new FallbackHistoryManager();

        eventHub.subscribe('HistoryManager:popState', this._handlePopState);
        $(document.body).on('click', ROUTER_LINK_SELECTOR, this._handleStateTrigger);
    };

    /**
     * Handle popstate event from browser
     *
     * @method _onPopState
     * @param {Object} state State info from previous _currentStates
     * @private
     */
    Router.prototype._onPopState = function(state) {
        this._currentStates = state || [];
        eventHub.publish('Router:stateChange', this._currentStates);
    };

    /**
     * Handle route click in UI
     *
     * @method _onStateTrigger
     * @param {ClickEvent} event Click event from router link
     * @private
     */
    Router.prototype._onStateTrigger = function(event) {
        event.preventDefault();
        this._currentStates.push(event.currentTarget.pathname);
        this.historyManager.pushState(this._currentStates, null, event.currentTarget.pathname);
        eventHub.publish('Router:stateChange', this._currentStates);
    };

    return Router;

});
