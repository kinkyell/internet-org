define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var BasicState = require('./BasicState');
    var viewWindow = require('services/viewWindow');
    var apiService = require('services/apiService');
    var spread = require('stark/promise/spread');

    var SearchFormView = require('views/SearchFormView');
    var ShowMoreView = require('views/ShowMoreView');

    var templates = require('templates');

    var log = require('util/log');

    /**
     * Manages search state
     *
     * @class SearchState
     * @param {Object} options State configuration options
     * @extends BasicState
     * @constructor
     */
    var SearchState = function(options) {
        this._handlePanelContentLoad = this._onPanelContentLoad.bind(this);
        this._handleStaticContent = this._onStaticContent.bind(this);
        this._handleSearchFormCreation = this._onSearchFormCreation.bind(this);
        BasicState.call(this, options);

        this.doublePanel = true;
    };

    SearchState.prototype = Object.create(BasicState.prototype);
    SearchState.prototype.constructor = SearchState;

    /**
     * List of components to initialize
     * @property COMPONENTS
     * @static
     * @type {Object}
     */
    SearchState.prototype.COMPONENTS = {
        '.js-ShowMoreView': ShowMoreView,
        '.js-searchFormView': SearchFormView
    };

    /**
     * Activate state
     *  - request panel content from server
     *  - create panel markup
     *
     * @method onActivate
     * @fires State:activate
     */
    SearchState.prototype.onActivate = function(event) {
        var transitions = this.getAnimationDirections(event);
        var tmplArgs = { searchText: this._options.searchText };

        if (event.silent) {
            viewWindow.getCurrentStory().then(this._handleStaticContent);
            return;
        }

        var tasks = [
            apiService.getSearchResults(tmplArgs.searchText),
            viewWindow.replaceStoryContent(
                templates['search-results-header'](tmplArgs),
                transitions.content
            )
        ];

        viewWindow.replaceFeatureContent(templates['search-input-panel'](tmplArgs), transitions.feature)
            .then(this.refreshComponents)
            .catch(log);

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
    SearchState.prototype._onPanelContentLoad = function(res, $panel) {
        if (!this.active) {
            return;
        }
        $panel.find('.js-searchState-results').prepend(res.results);
        $panel.find('.js-searchState-num').html(res.totalResults);
        $panel.find('.js-searchState-ft').toggle(res.hasNextPage);
        this.refreshComponents($panel);
    };

    /**
     * Handle static content when loaded
     *
     * @method _onStaticContent
     * @param {jQuery} $panel Panel that wraps static content
     * @private
     */
    SearchState.prototype._onStaticContent = function($panel) {
        if (!this.active) {
            return;
        }
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
