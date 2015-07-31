define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var BasicState = require('./BasicState');
    var HomeState = require('./HomeState');
    var viewWindow = require('services/viewWindow');
    var apiService = require('services/apiService');
    var spread = require('stark/promise/spread');

    var SearchFormView = require('views/SearchFormView');

    var templates = require('templates');

    var log = console.log.bind(console);

    /**
     * Manages search state
     *
     * @class SearchState
     * @extends BasicState
     * @constructor
     */
    var SearchState = function(options) {
        this._handlePanelContentLoad = this._onPanelContentLoad.bind(this);
        this._handleSearchFormCreation = this._onSearchFormCreation.bind(this);
        BasicState.call(this, options);

        this.doublePanel = true;
    };

    SearchState.prototype = Object.create(BasicState.prototype);
    SearchState.prototype.constructor = SearchState;

    SearchState.prototype.COMPONENTS = {
        '.js-searchFormView': SearchFormView
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
       var tmplArgs = { searchText: searchText };

        if (event.method === 'pop') {
            transition = 'left';
        }

        var tasks = [
            apiService.getSearchResults(searchText),
            viewWindow.replaceStoryContent(
                templates['search-results-header'](tmplArgs),
                event.method === 'push' && fromHome ? 'none' : transition
            )
        ];

        viewWindow.replaceFeatureContent(templates['search-input-panel'](tmplArgs), transition)
            .then(this.refreshComponents)
            .catch(log);

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
    SearchState.prototype._onPanelContentLoad = function(markup, $panel) {
        if (!this.active) {
            return;
        }
        $panel.append(markup);
        this.refreshComponents($panel);
    };

    /**
     * Add search functionality
     *
     * @method _onSearchFormCreation
     * @param {jQuery} $panel Created panel
     * @private
     */
    SearchState.prototype._onSearchFormCreation = function($panel) {
        var $formView = $panel.find('.js-searchFormView');
        this._searchFormView =  new SearchFormView($formView);
    };

    return SearchState;

});
