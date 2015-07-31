define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var BasicState = require('./BasicState');
    var HomeState = require('./HomeState');
    var viewWindow = require('services/viewWindow');
    var apiService = require('services/apiService');
    var spread = require('stark/promise/spread');

    var templates = require('templates');

    /**
     * Manages search state
     *
     * @class SearchState
     * @extends BasicState
     * @constructor
     */
    var SearchState = function(options) {
        this._handlePanelContentLoad = this._onPanelContentLoad.bind(this);
        this._handlePanelContentError = this._onPanelContentError.bind(this);
        BasicState.call(this, options);
    };

    SearchState.prototype = Object.create(BasicState.prototype);
    SearchState.prototype.constructor = SearchState;

    SearchState.prototype.activate = function(event) {
        var searchText = this._options.searchText;
        viewWindow.replaceStoryContent('<div>Search Results for "' + searchText + '"</div>', 'right');
        BasicState.prototype.activate.call(this, event);
    };

    /**
     * Activate state
     *  - request panel content from server
     *  - create panel markup
     *
     * @method activate
     * @fires State:activate
     */
    SearchState.prototype.activate = function(event) {
       var transition = 'right';
       var searchText = this._options.searchText;
       var stateLen = event.states.length;
       var fromHome = stateLen > 1 && (event.states[stateLen - 2] instanceof HomeState);

        if (event.method === 'push' && fromHome) {
            transition = 'none';
        }

        if (event.method === 'pop') {
            transition = 'left';
        }

        var tasks = [
            apiService.getSearchResults(searchText),
            viewWindow.replaceStoryContent('<div>Search Results for "' + searchText + '"</div>', transition)
        ];

        viewWindow.replaceFeatureContent('<input value="' + searchText + '" />', 'right');

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
    SearchState.prototype._onPanelContentLoad = function(markup, $panel) {
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
    SearchState.prototype._onPanelContentError = function(error) {
        if (!this.active) {
            return;
        }
        console.log(error);
    };

    return SearchState;

});
