define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var $ = require('jquery');
    var Modernizr = require('modernizr');
    var eventHub = require('services/eventHub');
    var appConfig = require('appConfig');

    var UrlHistoryManager = require('util/history/UrlHistoryManager');
    var FallbackHistoryManager = require('util/history/FallbackHistoryManager');

    var ROUTER_LINK_SELECTOR = '.js-stateLink';
    var ROUTER_BACK_SELECTOR = '.js-stateBack';
    var ROUTER_SWAP_SELECTOR = '.js-stateSwap';

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
        this._handleStateBack = this._onStateBack.bind(this);
        this._handleStateSwap = this._onStateSwap.bind(this);
        this._handleSearch = this._onSearch.bind(this);

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
        eventHub.subscribe('Search:submit', this._handleSearch);
        $(document.body).on('click', ROUTER_LINK_SELECTOR, this._handleStateTrigger);
        $(document.body).on('click', ROUTER_BACK_SELECTOR, this._handleStateBack);
        $(document.body).on('click', ROUTER_SWAP_SELECTOR, this._handleStateSwap);
    };

    /**
     * Handle popstate event from browser
     *
     * @method _onPopState
     * @param {Object} state State info from previous _currentStates
     * @private
     */
    Router.prototype._onPopState = function(state) {
        var prevStates = this._currentStates.slice(0);
        this._currentStates = state || [];

        if (!prevStates.length && !this._currentStates.length) {
            return;
        }

        eventHub.publish('Router:stateChange', this._currentStates, prevStates);
    };

    /**
     * Handle route click in UI
     *
     * @method _onStateTrigger
     * @param {ClickEvent} event Click event from router link
     * @private
     */
    Router.prototype._onStateTrigger = function(event) {
        var prevStates = this._currentStates.slice(0);
        event.preventDefault();
        this._currentStates.push(event.currentTarget.pathname);
        this.historyManager.pushState(this._currentStates, null, event.currentTarget.pathname);
        eventHub.publish('Router:stateChange', this._currentStates, prevStates);
    };

    /**
     * Handle route swap in UI
     *
     * @method _onStateSwap
     * @param {ClickEvent} event Click event from router link
     * @private
     */
    Router.prototype._onStateSwap = function(event) {
        var prevStates = this._currentStates.slice(0);
        event.preventDefault();
        this._currentStates[this._currentStates.length - 1] = event.currentTarget.pathname;
        this.historyManager.replaceState(this._currentStates, null, event.currentTarget.pathname);
        eventHub.publish('Router:stateChange', this._currentStates, prevStates);
    };

    /**
     * Handle back link click in UI
     *
     * @method _onStateBack
     * @param {ClickEvent} event Click event from router link
     * @private
     */
    Router.prototype._onStateBack = function(event) {
        event.preventDefault();
        this.historyManager.back();
    };

    /**
     * Handle back link click in UI
     *
     * @method _onSearch
     * @param {ClickEvent} event Click event from router link
     * @private
     */
    Router.prototype._onSearch = function(event) {
        var prevStates = this._currentStates.slice(0);
        var url = appConfig.searchPath + '/' + encodeURIComponent(event.searchText);
        this._currentStates.push(url);
        this.historyManager.pushState(this._currentStates, null, url);
        eventHub.publish('Router:stateChange', this._currentStates, prevStates);
    };

    /**
     * Get all current states
     *
     * @method getActiveStates
     * @returns {Array} A list of the currently active states (history)
     */
    Router.prototype.getActiveStates = function(event) {
        return this._currentStates.slice(0);
    };

    /**
     * Get top state
     *
     * @method getTopState
     * @returns {String} The top state identifier
     */
    Router.prototype.getTopState = function(event) {
        return this._currentStates[this._currentStates.length - 1];
    };

    /**
     * Navigate to route
     *
     * @method navigateTo
     * @returns {String} The top state identifier
     */
    Router.prototype.navigateTo = function(stateName, silent) {
        var prevStates = this._currentStates.slice(0);
        this._currentStates.push(stateName);
        if (!silent) {
            eventHub.publish('Router:stateChange', this._currentStates, prevStates);
        }
    };

    return Router;

});
