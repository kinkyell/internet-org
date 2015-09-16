define(function(require, exports, module) { // jshint ignore:line
    'use strict';

    var AbstractView = require('./AbstractView');
    var apiService = require('services/apiService');
    var tweenAsync = require('util/tweenAsync');
    var animationSpeeds = require('appConfig').animationSpeeds;
    var eventHub = require('services/eventHub');

    /**
     * A view to load in more content
     *
     * @class ShowMoreView
     * @param {jQuery} $element A reference to the containing DOM element.
     * @constructor
     */
    var ShowMoreView = function($element) {
        AbstractView.call(this, $element);
    };

    var proto = AbstractView.createChild(ShowMoreView);

    /**
     * Binds the scope of any handler functions.
     * Should only be run on initialization of the view.
     *
     * @method setupHandlers
     * @returns {ShowMoreView}
     * @private
     */
    proto.setupHandlers = function() {
        this._handleTriggerClick = this._onTriggerClick.bind(this);
        this._handleContentLoad = this._onContentLoad.bind(this);
        this._handleFilterChange = this._onFilterChange.bind(this);
        this.enableButton = this.enableButton.bind(this);
        this.disableButton = this.disableButton.bind(this);
    };

    /**
     * Create any child objects or references to DOM elements.
     * Should only be run on initialization of the view.
     *
     * @method createChildren
     * @returns {ShowMoreView}
     * @private
     */
    proto.createChildren = function() {
        var targetId = this.element.getAttribute('data-target');
        this.targetEl = document.getElementById(targetId);
        this.contentType = this.element.getAttribute('data-src');
        this.contentArgs = this.element.getAttribute('data-args');
        this.filterId = this.element.getAttribute('data-filter');
        this.currentFilter = null;
        this.nextPage = 2;
    };

    /**
     * Remove any child objects or references to DOM elements.
     *
     * @method removeChildren
     * @returns {ShowMoreView}
     * @public
     */
    proto.removeChildren = function() {
        this.targetEl = null;
    };

    /**
     * Performs any event binding to handlers.
     *
     * @method onEnable
     * @public
     */
    proto.onEnable = function() {
        this.$element.on('click', this._handleTriggerClick);
        eventHub.subscribe('SelectView:change', this._handleFilterChange);
    };

    /**
     * Tears down any event binding to handlers.
     *
     * @method onDisable
     * @public
     */
    proto.onDisable = function() {
        this.$element.off('click', this._handleTriggerClick);
        eventHub.unsubscribe('SelectView:change', this._handleFilterChange);
    };

    //////////////////////////////////////////////////////////////////////////////////
    // EVENT HANDLERS
    //////////////////////////////////////////////////////////////////////////////////

    /**
     * Handle click event, load more content
     *
     * @method _onTriggerClick
     * @param {ClickEvent} event Click event
     * @private
     */
    proto._onTriggerClick = function(event) {
        event.preventDefault();
        this.disableButton();
        this._loadAddlContent()
            .then(this._handleContentLoad)
            .then(this.enableButton);
    };

    /**
     * Handle content load, append content and animate
     *
     * @method _onContentLoad
     * @param {String} html HTML string content
     * @private
     */
    proto._onContentLoad = function(res) {
        var newContent = document.createElement('div');
        newContent.className = 'resultsList-list';
        newContent.innerHTML = res.results;
        this.targetEl.appendChild(newContent);

        return tweenAsync.from(newContent, animationSpeeds.ADDL_CONTENT, {
            height: 0,
            opacity: 0
        }).then(function() {
            // return if it's the last page
            return !res.hasNextPage;
        });
    };

    /**
     * Handle filter change, refresh results
     *
     * @method _onFilterChange
     * @param {String} html HTML string content
     * @private
     */
    proto._onFilterChange = function(select) {
        this._clearPosts();
        this.nextPage = 1;
        this.disableButton();
        this._loadAddlContent(select.value)
            .then(this._handleContentLoad)
            .then(this.enableButton);
    };


    //////////////////////////////////////////////////////////////////////////////////
    // HELPERS
    //////////////////////////////////////////////////////////////////////////////////



    /**
     * Load the next section of content from apiService
     *
     * @method _loadAddlContent
     * @returns {Promise} content promise
     * @private
     */
    proto._loadAddlContent = function(year) {
        year = parseInt(year, 10) || this.currentFilter;
        var currentPage = this.nextPage;
        this.nextPage++;
        this.currentFilter = year;
        return apiService.getMoreContent(this.contentType, currentPage, this.contentArgs, year);
    };

    /**
     * Clear loaded posts
     *
     * @method _clearPosts
     * @private
     */
    proto._clearPosts = function() {
        this.targetEl.innerHTML = '';
    };

    /**
     * Enable button clicks
     *
     * @method enableButton
     * @private
     */
    proto.enableButton = function(isLastPage) {
        if (isLastPage) {
            this.$element.remove();
            return;
        }
        this.$element.removeClass('isLoading').removeAttr('disabled');
    };

    /**
     * Disable button clicks
     *
     * @method disableButton
     * @private
     */
    proto.disableButton = function() {
        this.$element.addClass('isLoading').attr('disabled', '');
    };


    module.exports = ShowMoreView;

});
